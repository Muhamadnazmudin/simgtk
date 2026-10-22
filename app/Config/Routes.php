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

// Absensi sub-namespace routes
$routes->match(['GET', 'POST'], 'Absensi/Absensi', '\App\Controllers\Absensi\Absensi::index');
$routes->match(['GET', 'POST'], 'Absensi/Absensi/(:segment)', '\App\Controllers\Absensi\Absensi::$1');
$routes->match(['GET', 'POST'], 'Absensi/Absensi/(:segment)/(:any)', '\App\Controllers\Absensi\Absensi::$1/$2');
$routes->match(['GET', 'POST'], 'absensi/absensi', '\App\Controllers\Absensi\Absensi::index');
$routes->match(['GET', 'POST'], 'absensi/absensi/(:segment)', '\App\Controllers\Absensi\Absensi::$1');
$routes->match(['GET', 'POST'], 'absensi/absensi/(:segment)/(:any)', '\App\Controllers\Absensi\Absensi::$1/$2');

$routes->match(['GET', 'POST'], 'Absensi/Laporan', '\App\Controllers\Absensi\Laporan::index');
$routes->match(['GET', 'POST'], 'Absensi/Laporan/(:segment)', '\App\Controllers\Absensi\Laporan::$1');
$routes->match(['GET', 'POST'], 'Absensi/Laporan/(:segment)/(:any)', '\App\Controllers\Absensi\Laporan::$1/$2');
$routes->match(['GET', 'POST'], 'absensi/laporan', '\App\Controllers\Absensi\Laporan::index');
$routes->match(['GET', 'POST'], 'absensi/laporan/(:segment)', '\App\Controllers\Absensi\Laporan::$1');
$routes->match(['GET', 'POST'], 'absensi/laporan/(:segment)/(:any)', '\App\Controllers\Absensi\Laporan::$1/$2');

// Explicit routes for modules (ensures case-insensitivity and sub-methods match smoothly)
$modules = [
    'tendik'                   => 'Tendik',
    'buku_induk'               => 'Buku_induk',
    'berkas_siswa'             => 'Berkas_siswa',
    'idcard_premium'           => 'Idcard_premium',
    'monitoring_mengajar'      => 'Monitoring_mengajar',
    'rekap_jjm'                => 'Rekap_jjm',
    'guru_sertifikasi'         => 'Guru_sertifikasi',
    'jam_sekolah'              => 'Jam_sekolah',
    'jadwal_mengajar'          => 'Jadwal_mengajar',
    'laporan_mengajar'         => 'Laporan_mengajar',
    'AdminLaporanPembelajaran' => 'AdminLaporanPembelajaran',
    'adminlaporanpembelajaran' => 'AdminLaporanPembelajaran',
    'AdminBacaan'              => 'AdminBacaan',
    'adminbacaan'              => 'AdminBacaan',
    'Nilairapor_admin'         => 'Nilairapor_admin',
    'nilairapor_admin'         => 'Nilairapor_admin',
    'Tka_admin'                => 'Tka_admin',
    'tka_admin'                => 'Tka_admin',
    'sptjm'                    => 'Sptjm',
    'akun_belajar'             => 'Akun_belajar',
    'tutorial'                 => 'Tutorial',
    'mutasi'                   => 'Mutasi',
    'kenaikan'                 => 'Kenaikan',
    'siswa_keluar'             => 'Siswa_keluar',
    'siswa_lulus'              => 'Siswa_lulus',
    'laporan'                  => 'Laporan',
    'AbsensiQRAdmin'           => 'AbsensiQRAdmin',
    'absensiqradmin'           => 'AbsensiQRAdmin',
    'izin'                     => 'Izin',
    'Izin'                     => 'Izin',
    'verval'                   => 'Verval',
    'Verval'                   => 'Verval',
    'riwayat_biodata'          => 'Riwayat_biodata',
    'Riwayat_biodata'          => 'Riwayat_biodata',
    'Sync_google'              => 'Sync_google',
    'sync_google'              => 'Sync_google',
    'jadwalabsensi'            => 'Jadwalabsensi',
    'JadwalAbsensi'            => 'Jadwalabsensi',
    'backup'                   => 'Backup',
    'Backup'                   => 'Backup',
];

foreach ($modules as $slug => $controller) {
    $routes->match(['GET', 'POST'], $slug, "{$controller}::index");
    $routes->match(['GET', 'POST'], "{$slug}/(:segment)", "{$controller}::$1");
    $routes->match(['GET', 'POST'], "{$slug}/(:segment)/(:any)", "{$controller}::$1/$2");
}
