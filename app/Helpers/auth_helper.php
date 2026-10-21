<?php

if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        $session = session();
        if (!$session->get('user_id')) {
            header('Location: ' . base_url('auth/login'));
            exit;
        }
    }
}

if (!function_exists('is_admin')) {
    function is_admin() {
        return session()->get('role_name') === 'admin' || (int) session()->get('role_id') === 1;
    }
}

if (!function_exists('is_kesiswaan')) {
    function is_kesiswaan() {
        return session()->get('role_name') === 'kesiswaan' || (int) session()->get('role_id') === 2;
    }
}

if (!function_exists('only_admin')) {
    function only_admin() {
        if (!is_admin()) {
            http_response_code(403);
            echo '<h1>403 Forbidden</h1><p>Akses ditolak. Hanya Admin yang dapat mengakses halaman ini.</p>';
            exit;
        }
    }
}

if (!function_exists('is_guru')) {
    function is_guru() {
        $session = session();
        if (!$session->get('user_id') || !in_array((int) $session->get('role_id'), [3, 4], true)) {
            header('Location: ' . base_url('auth/login'));
            exit;
        }
    }
}
