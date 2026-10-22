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
 * DB Proxy to provide backward compatibility for CI3 $this->db->get_where(), ->get(), etc.
 */
class CompatDbProxy
{
    protected $conn;

    public function __construct()
    {
        $this->conn = \Config\Database::connect();
    }

    public function get_where(string $table, array $where = [], ?int $limit = null, ?int $offset = null)
    {
        $res = $this->conn->table($table)->getWhere($where, $limit, $offset);
        return new CompatResultProxy($res);
    }

    public function get(string $table, ?int $limit = null, ?int $offset = null)
    {
        $res = $this->conn->table($table)->get($limit, $offset);
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
