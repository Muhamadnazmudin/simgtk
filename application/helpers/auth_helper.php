<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function is_logged_in() {
    $CI =& get_instance();
    if (!$CI->session->userdata('user_id')) {
        redirect('auth/login');
        exit;
    }
}

function is_admin() {
    $CI =& get_instance();
    return $CI->session->userdata('role_name') === 'admin';
}

function is_kesiswaan() {
    $CI =& get_instance();
    return $CI->session->userdata('role_name') === 'kesiswaan';
}

function only_admin() {
    if (!is_admin()) {
        show_error(
            'Akses ditolak. Hanya Admin yang dapat mengakses halaman ini.',
            403,
            'Forbidden'
        );
        exit;
    }
}

/**
 * ✅ GURU & WAKAKUR
 */
function is_guru() {
    $CI =& get_instance();

    // wajib login
    if (!$CI->session->userdata('user_id')) {
        redirect('auth/login');
        exit;
    }

    // guru ATAU wakakur
    if (!in_array($CI->session->userdata('role_id'), [3, 4])) {
        redirect('auth/login');
        exit;
    }
}
