<?php

namespace App\Libraries;

use CodeIgniter\View\View;

/**
 * Result Proxy to provide backward compatibility for CI3 ->row(), ->result(), etc.
 */
class CompatResultProxy
{
    protected $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function row($n = 0)
    {
        return $this->result->getRow($n);
    }

    public function result()
    {
        return $this->result->getResult();
    }

    public function row_array($n = 0)
    {
        return $this->result->getRowArray($n);
    }

    public function result_array()
    {
        return $this->result->getResultArray();
    }

    public function num_rows()
    {
        return $this->result->getNumRows();
    }

    public function __call($name, $arguments)
    {
        return $this->result->$name(...$arguments);
    }
}

/**
 * DB Proxy to provide backward compatibility for CI3 $this->db->get_where(), ->get(), ->select(), ->from(), etc.
 */
class CompatDbProxy
{
    protected $conn;
    protected $table = null;
    protected $pendingSelect = null;
    protected $pendingJoins = [];
    protected $pendingWhere = [];
    protected $pendingOrderBy = [];
    protected $builder = null;

    public function __construct()
    {
        $this->conn = \Config\Database::connect();
    }

    protected function initBuilder(string $table)
    {
        $this->table = $table;
        $this->builder = $this->conn->table($table);
        if ($this->pendingSelect !== null) {
            $this->builder->select($this->pendingSelect);
            $this->pendingSelect = null;
        }
        foreach ($this->pendingJoins as $j) {
            $this->builder->join($j[0], $j[1], $j[2] ?? 'left', $j[3] ?? null);
        }
        $this->pendingJoins = [];
        foreach ($this->pendingWhere as $w) {
            $this->builder->where($w[0], $w[1] ?? null, $w[2] ?? null);
        }
        $this->pendingWhere = [];
        foreach ($this->pendingOrderBy as $o) {
            $this->builder->orderBy($o[0], $o[1] ?? 'ASC', $o[2] ?? null);
        }
        $this->pendingOrderBy = [];
        return $this->builder;
    }

    public function select($select = '*', ?bool $escape = null)
    {
        if ($this->builder !== null) {
            $this->builder->select($select, $escape);
        } else {
            $this->pendingSelect = $select;
        }
        return $this;
    }

    public function from(string $table)
    {
        $this->initBuilder($table);
        return $this;
    }

    public function join(string $table, string $cond, string $type = 'left', ?bool $escape = null)
    {
        if ($this->builder !== null) {
            $this->builder->join($table, $cond, $type, $escape);
        } else {
            $this->pendingJoins[] = [$table, $cond, $type, $escape];
        }
        return $this;
    }

    public function where($key, $value = null, ?bool $escape = null)
    {
        if ($this->builder !== null) {
            $this->builder->where($key, $value, $escape);
        } else {
            $this->pendingWhere[] = [$key, $value, $escape];
        }
        return $this;
    }

    public function order_by(string $orderBy, string $direction = '', ?bool $escape = null)
    {
        if ($this->builder !== null) {
            $this->builder->orderBy($orderBy, $direction, $escape);
        } else {
            $this->pendingOrderBy[] = [$orderBy, $direction, $escape];
        }
        return $this;
    }

    public function get(?string $table = null, ?int $limit = null, ?int $offset = null)
    {
        if ($table !== null) {
            $this->initBuilder($table);
        }
        if ($this->builder === null) {
            if ($this->table !== null) {
                $this->initBuilder($this->table);
            } else {
                throw new \RuntimeException("Table not specified in CompatDbProxy");
            }
        }
        $res = $this->builder->get($limit, $offset ?? 0);
        $this->builder = null;
        $this->table = null;
        return new CompatResultProxy($res);
    }

    public function get_where(string $table, array $where = [], ?int $limit = null, ?int $offset = null)
    {
        $res = $this->conn->table($table)->getWhere($where, $limit, $offset ?? 0);
        return new CompatResultProxy($res);
    }

    public function table(string $table)
    {
        return $this->conn->table($table);
    }

    public function query(string $sql, $binds = false)
    {
        $res = $this->conn->query($sql, $binds);
        return is_object($res) ? new CompatResultProxy($res) : $res;
    }

    public function __call($name, $arguments)
    {
        if ($this->builder !== null && method_exists($this->builder, $name)) {
            $res = $this->builder->$name(...$arguments);
            return $res === $this->builder ? $this : $res;
        }
        return $this->conn->$name(...$arguments);
    }

    public function __get($name)
    {
        return $this->conn->$name;
    }
}

/**
 * CompatView - Seamless compatibility layer for CI3 view syntax in CI4
 * Supports $this->security, $this->session, $this->db inside view templates.
 */
class CompatView extends View
{
    protected ?object $securityProxy = null;
    protected ?object $sessionProxy  = null;
    protected ?object $dbProxy       = null;

    public function render(string $view, ?array $options = null, ?bool $saveData = null): string
    {
        if ($options !== null && is_array($options)) {
            $this->setData($options);
        }
        return parent::render($view, $options, $saveData);
    }

    public function __get(string $key)
    {
        if ($key === 'security') {
            return $this->securityProxy ??= new class {
                public function get_csrf_token_name(): string
                {
                    return csrf_token();
                }

                public function get_csrf_hash(): string
                {
                    return csrf_hash();
                }
            };
        }

        if ($key === 'session') {
            return $this->sessionProxy ??= new class {
                public function userdata(?string $item = null)
                {
                    $session = session();
                    if ($item === null) {
                        return $session->get();
                    }
                    return $session->get($item);
                }

                public function flashdata(?string $item = null)
                {
                    $session = session();
                    if ($item === null) {
                        return $session->getFlashdata();
                    }
                    return $session->getFlashdata($item);
                }

                public function set_userdata($data, $val = null)
                {
                    return session()->set($data, $val);
                }
            };
        }

        if ($key === 'db') {
            return $this->dbProxy ??= new CompatDbProxy();
        }

        if ($key === 'solat') {
            return model(\App\Models\AbsensiSolatModel::class);
        }

        if ($key === 'input') {
            return $this->inputProxy ??= new class {
                public function get(?string $index = null, $xssClean = false)
                {
                    $req = service('request');
                    return $index === null ? $req->getGet() : $req->getGet($index);
                }

                public function post(?string $index = null, $xssClean = false)
                {
                    $req = service('request');
                    return $index === null ? $req->getPost() : $req->getPost($index);
                }

                public function get_post(?string $index = null, $xssClean = false)
                {
                    $req = service('request');
                    return $req->getPostGet($index);
                }

                public function server(string $index)
                {
                    return service('request')->getServer($index);
                }
            };
        }

        // Return from view data if defined
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return null;
    }
}
