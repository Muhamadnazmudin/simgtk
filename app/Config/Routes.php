<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Default route
$routes->get('/', 'Dashboard::index');
$routes->get('dashboard', 'Dashboard::index');

// Authentication routes (Rate limited against brute-force)
$routes->match(['GET', 'POST'], 'auth', 'Auth::index');
$routes->match(['GET', 'POST'], 'auth/login', 'Auth::login', ['filter' => 'rateLimiter:10,60']);
$routes->get('auth/logout', 'Auth::logout');

// Siswa Authentication & Dashboard
$routes->get('siswa-login', 'SiswaAuth::index');
$routes->get('SiswaAuth', 'SiswaAuth::index');
$routes->post('SiswaAuth/cek_login', 'SiswaAuth::cek_login', ['filter' => 'rateLimiter:10,60']);
$routes->get('SiswaAuth/logout', 'SiswaAuth::logout');
$routes->get('SiswaDashboard', 'SiswaDashboard::index');

// Teacher Dashboard
$routes->get('guru_dashboard', 'Guru_dashboard::index');

// Absensi Salat routes
$routes->get('absensi_solat', 'Absensi_solat::index');
$routes->post('absensi_solat/buat_sesi', 'Absensi_solat::buat_sesi');
$routes->get('absensi_solat/scan/(:num)', 'Absensi_solat::scan/$1');
$routes->post('absensi_solat/proses_scan', 'Absensi_solat::proses_scan', ['filter' => 'rateLimiter:60,60']);
$routes->post('absensi_solat/tutup_sesi/(:num)', 'Absensi_solat::tutup_sesi/$1');
$routes->get('absensi_solat/rekap', 'Absensi_solat::rekap');
$routes->match(['GET', 'POST'], 'absensi_solat/edit_sesi/(:num)', 'Absensi_solat::edit_sesi/$1');

$routes->get('absensi_solat/import_jadwal', 'Absensi_solat::import_jadwal');
$routes->get('absensi_solat/download_template', 'Absensi_solat::download_template');
$routes->post('absensi_solat/import_proses', 'Absensi_solat::import_proses');

// Public prayer schedule
$routes->get('jadwal_solat_public', 'Jadwal_solat_public::index');
