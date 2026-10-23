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

if (!function_exists('is_admin')) {
    function is_admin(): bool
    {
        $role = strtolower((string) session()->get('role_name'));
        $roleId = (int) session()->get('role_id');
        return $role === 'admin' || $roleId === 1;
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in(): bool
    {
        return (bool) session()->get('logged_in');
    }
}

if (!function_exists('is_kesiswaan')) {
    function is_kesiswaan(): bool
    {
        $role = strtolower((string) session()->get('role_name'));
        $roleId = (int) session()->get('role_id');
        return $role === 'kesiswaan' || $roleId === 2;
    }
}

if (!function_exists('only_admin')) {
    function only_admin(): void
    {
        if (!is_admin()) {
            throw new \CodeIgniter\Exceptions\HTTPException('Akses ditolak. Hanya Admin yang dapat mengakses halaman ini.', 403);
        }
    }
}

if (!function_exists('is_guru')) {
    function is_guru(): bool
    {
        $roleId = (int) session()->get('role_id');
        return in_array($roleId, [3, 4], true);
    }
}

if (!function_exists('form_error')) {
    function form_error(string $field = '', string $prefix = '', string $suffix = ''): string
    {
        $error = '';
        if (function_exists('validation_show_error')) {
            $err = validation_show_error($field);
            if (!empty($err)) {
                $error = strip_tags($err);
            }
        }
        if (empty($error)) {
            $session = session();
            $errors = $session->getFlashdata('errors') ?? $session->get('errors');
            if (is_array($errors) && !empty($errors[$field])) {
                $error = $errors[$field];
            }
        }
        return !empty($error) ? $prefix . $error . $suffix : '';
    }
}

