<?php

/**
 * CI3 to CI4 Compatibility Helper
 */

if (!function_exists('html_escape')) {
    function html_escape($var, bool $double_encode = true)
    {
        return esc($var);
    }
}

if (!function_exists('show_error')) {

    function show_error($message, int $status_code = 500, string $heading = 'An Error Was Encountered')
    {
        throw new \CodeIgniter\Exceptions\HTTPException($message, $status_code);
    }
}

if (!function_exists('show_404')) {
    function show_404(string $page = '', bool $log_error = true)
    {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($page);
    }
}

if (!function_exists('get_instance')) {
    function &get_instance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new class {
                public $db;
                public $session;
                public $security;

                public function __construct()
                {
                    $this->db = \Config\Database::connect();
                    $this->session = session();
                    $this->security = new class {
                        public function get_csrf_token_name() { return csrf_token(); }
                        public function get_csrf_hash() { return csrf_hash(); }
                    };
                }
            };
        }
        return $instance;
    }
}
