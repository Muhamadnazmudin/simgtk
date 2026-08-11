<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>SimSGTK – Sistem Informasi Siswa & GTK</title>

    <meta name="description"
          content="SimSGTK adalah aplikasi manajemen sekolah modern untuk pengelolaan data siswa, guru, absensi, mutasi, dan administrasi pendidikan secara terintegrasi.">

    <meta name="theme-color"
          content="#007bff">


    <!-- ================= OPEN GRAPH ================= -->

    <meta property="og:title"
          content="SimSGTK – Sistem Informasi Siswa & GTK">

    <meta property="og:description"
          content="Aplikasi manajemen sekolah terpadu untuk pengelolaan siswa, guru, absensi, mutasi, dan administrasi pendidikan.">

    <meta property="og:type"
          content="website">

    <meta property="og:url"
          content="<?= base_url() ?>">

    <meta property="og:image"
          content="<?= base_url('assets/pwa/icon-512.png') ?>">


    <!-- ================= ICON ================= -->

    <link rel="icon"
          href="<?= base_url('assets/pwa/favicon.ico') ?>">

    <link rel="icon"
          type="image/png"
          sizes="192x192"
          href="<?= base_url('assets/pwa/icon-192.png') ?>">

    <link rel="icon"
          type="image/png"
          sizes="512x512"
          href="<?= base_url('assets/pwa/icon-512.png') ?>">

    <link rel="apple-touch-icon"
          href="<?= base_url('assets/pwa/icon-192.png') ?>">


    <!-- ================= PWA ================= -->

    <link rel="manifest"
          href="<?= base_url('assets/pwa/manifest.json') ?>">


    <!-- ================= CSS ================= -->

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">


<style>

/* =====================================================
   ROOT
===================================================== */

:root {

    --primary: #0d6efd;
    --primary-dark: #0b5ed7;

    --bg-light: #f5f7fb;
    --card-light: #ffffff;
    --text-light: #212529;
    --muted-light: #6c757d;
    --border-light: #dee2e6;

    --bg-dark: #12141c;
    --card-dark: #1c1f2b;
    --text-dark: #f1f3f5;
    --muted-dark: #adb5bd;
    --border-dark: #343a55;

    --radius: 18px;

    --shadow:
        0 6px 20px rgba(0,0,0,.08);

}


/* =====================================================
   BODY
===================================================== */

* {
    box-sizing: border-box;
}


body {

    margin: 0;

    font-family:
        system-ui,
        -apple-system,
        "Segoe UI",
        sans-serif;

    background: var(--bg-light);

    color: var(--text-light);

    transition:
        background .25s,
        color .25s;

}


body.dark-mode {

    background: var(--bg-dark);

    color: var(--text-dark);

}


/* =====================================================
   HEADER
===================================================== */

.public-header {

    background:
        linear-gradient(
            135deg,
            #0d6efd,
            #0b5ed7
        );

    box-shadow:
        0 4px 18px rgba(13,110,253,.35);

}


.public-header .navbar {

    padding:
        .8rem 0;

}


.navbar-brand {

    font-weight: 700;

    font-size: 1.25rem;

}


.nav-link {

    color:
        rgba(255,255,255,.9)
        !important;

    border-radius: 9px;

    padding:
        .55rem .8rem
        !important;

}


.nav-link:hover {

    background:
        rgba(255,255,255,.15);

}


/* =====================================================
   LOGIN
===================================================== */

.btn-login {

    border: none;

    background: #fff;

    color: #0d6efd;

    padding:
        9px 16px;

    border-radius: 10px;

    font-weight: 600;

}


.btn-theme-toggle {

    width: 42px;

    height: 42px;

    border-radius: 50%;

    border:
        1px solid
        rgba(255,255,255,.4);

    background:
        rgba(255,255,255,.18);

    color: #fff;

}


/* =====================================================
   MAIN
===================================================== */

.public-container {

    max-width: 1200px;

    margin: auto;

    padding:
        30px 15px
        50px;

}


/* =====================================================
   HERO
===================================================== */

.public-hero {

    position: relative;

    overflow: hidden;

    border-radius: 22px;

    padding: 35px;

    margin-bottom: 30px;

    color: #fff;

    background:
        linear-gradient(
            135deg,
            #4e73df,
            #224abe
        );

    box-shadow:
        0 10px 30px rgba(0,0,0,.15);

}


.public-hero:after {

    content: "";

    position: absolute;

    width: 250px;

    height: 250px;

    right: -80px;

    top: -100px;

    border-radius: 50%;

    background:
        rgba(255,255,255,.08);

}


.public-hero h2 {

    font-weight: 700;

    margin-bottom: 10px;

}


.public-hero p {

    margin-bottom: 5px;

    opacity: .9;

}


.hero-icon {

    position: relative;

    z-index: 2;

    text-align: right;

    opacity: .25;

}


/* =====================================================
   SUMMARY CARD
===================================================== */

.summary-card {

    border: none;

    border-radius: 18px;

    overflow: hidden;

    transition: .25s;

    background:
        var(--card-light);

}


.summary-card:hover {

    transform:
        translateY(-5px);

    box-shadow:
        0 12px 28px rgba(0,0,0,.15);

}


.summary-card .card-body {

    padding: 23px;

}


.summary-title {

    font-size: 12px;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .5px;

    color: #888;

}


.summary-number {

    font-size: 32px;

    font-weight: 700;

    margin:
        8px 0;

}


.summary-desc {

    font-size: 12px;

    color: #999;

}


.summary-icon {

    width: 62px;

    height: 62px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    color: #fff;

    font-size: 25px;

}


.icon-green {
    background: #1cc88a;
}

.icon-blue {
    background: #4e73df;
}

.icon-red {
    background: #e74a3b;
}

.icon-info {
    background: #36b9cc;
}

.icon-warning {
    background: #f6c23e;
}


/* =====================================================
   CONTENT CARD
===================================================== */

.info-card {

    border: none;

    border-radius: 18px;

    overflow: hidden;

    background:
        var(--card-light);

}


.info-card-header {

    padding:
        18px 22px;

    border-bottom:
        1px solid #eee;

}


.info-card-title {

    font-weight: 700;

    font-size: 17px;

}


.info-card-subtitle {

    font-size: 12px;

    color: #999;

    margin-top: 3px;

}


.info-card-body {

    padding: 22px;

}


/* =====================================================
   TABLE
===================================================== */

.public-table {

    margin-bottom: 0;

}


.public-table th {

    font-size: 12px;

    text-transform: uppercase;

    white-space: nowrap;

}


.public-table td {

    vertical-align: middle;

}


.public-table tbody tr {

    transition: .15s;

}


.public-table tbody tr:hover {

    background:
        rgba(13,110,253,.05);

}


.stat-badge {

    min-width: 42px;

    border-radius: 20px;

    padding:
        7px 11px;

}


/* =====================================================
   CHART
===================================================== */

.chart-box {

    height: 320px;

    position: relative;

}


/* =====================================================
   CLOCK
===================================================== */

.clock {

    font-size: 34px;

    font-weight: 700;

    letter-spacing: 2px;

}


/* =====================================================
   DARK MODE
===================================================== */

body.dark-mode .summary-card,
body.dark-mode .info-card {

    background:
        var(--card-dark);

    color:
        var(--text-dark);

}


body.dark-mode .info-card-header {

    border-color:
        var(--border-dark);

}


body.dark-mode .public-table {

    color:
        #ddd;

}


body.dark-mode .public-table thead th {

    background:
        #232844 !important;

    color:
        #fff !important;

    border-color:
        #3d4566 !important;

}


body.dark-mode .public-table td {

    background:
        #161b2e !important;

    color:
        #e9ecef !important;

    border-color:
        #2f3658 !important;

}


body.dark-mode .public-table tbody tr:hover td {

    background:
        rgba(13,110,253,.18) !important;

}


body.dark-mode .table-secondary td {

    background:
        #24305e !important;

    color:
        #fff !important;

}


body.dark-mode .summary-title {

    color:
        #aaa;

}


body.dark-mode .summary-desc {

    color:
        #aaa;

}


/* =====================================================
   FOOTER
===================================================== */

.public-footer {

    margin-top: 20px;

    padding:
        25px 15px;

    text-align: center;

    background:
        var(--card-light);

    border-top:
        1px solid
        var(--border-light);

}


body.dark-mode .public-footer {

    background:
        var(--card-dark);

    border-color:
        var(--border-dark);

}


/* =====================================================
   CHATBOT
===================================================== */

#chatbot {

    position: fixed;

    bottom: 90px;

    right: 20px;

    width: 340px;

    max-height: 520px;

    background:
        var(--card-light);

    border:
        1px solid
        var(--border-light);

    border-radius: 14px;

    display: none;

    flex-direction: column;

    overflow: hidden;

    z-index: 9999;

    box-shadow:
        0 12px 30px rgba(0,0,0,.25);

}


body.dark-mode #chatbot {

    background:
        var(--card-dark);

    border-color:
        var(--border-dark);

}


#chatbot-header {

    background:
        linear-gradient(
            135deg,
            #0d6efd,
            #0b5ed7
        );

    color: #fff;

    padding:
        .7rem 1rem;

    font-weight: 700;

    display: flex;

    align-items: center;

    justify-content: space-between;

}


#chatbot-close {

    border: none;

    background: transparent;

    color: #fff;

    font-size: 22px;

}


#chatbot-body {

    flex: 1;

    padding: .8rem;

    overflow-y: auto;

    font-size: .85rem;

}


#chatbot-footer {

    display: flex;

    border-top:
        1px solid
        var(--border-light);

}


#chatbot-input {

    flex: 1;

    border: none;

    padding:
        .7rem;

    outline: none;

    background: transparent;

    color: inherit;

}


#chatbot-send {

    border: none;

    padding:
        0 15px;

    background:
        #0d6efd;

    color: #fff;

}


.bot-msg {

    background:
        #e7f1ff;

    color:
        #084298;

    padding:
        .6rem .75rem;

    border-radius:
        12px 12px 12px 4px;

    margin-bottom:
        .5rem;

    max-width:
        85%;

}


.user-msg {

    background:
        #0d6efd;

    color: #fff;

    padding:
        .6rem .75rem;

    border-radius:
        12px 12px 4px 12px;

    margin-left:
        auto;

    margin-bottom:
        .5rem;

    max-width:
        85%;

}


#chatbot-wrapper {

    position: fixed;

    right: 22px;

    bottom: 22px;

    z-index: 9998;

    display: flex;

    flex-direction: column;

    align-items: center;

    gap: 6px;

}


#chatbot-toggle {

    width: 58px;

    height: 58px;

    border: none;

    border-radius: 50%;

    background:
        #0d6efd;

    color: #fff;

    font-size: 21px;

    box-shadow:
        0 8px 20px
        rgba(13,110,253,.45);

}


.chatbot-label {

    background:
        var(--card-light);

    color:
        var(--text-light);

    padding:
        4px 10px;

    border-radius:
        20px;

    font-size: 11px;

    box-shadow:
        0 4px 12px rgba(0,0,0,.15);

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:767px){

    .public-container {

        padding:
            20px 12px
            40px;

    }


    .public-hero {

        padding:
            25px 20px;

    }


    .public-hero h2 {

        font-size:
            23px;

    }


    .hero-icon {

        display: none;

    }


    .summary-number {

        font-size:
            27px;

    }


    .chart-box {

        height:
            270px;

    }


    #chatbot {

        width:
            calc(100% - 30px);

        right:
            15px;

    }

}

</style>

</head>


<body>


<!-- =====================================================
     HEADER
===================================================== -->

<header class="public-header">

<nav class="navbar navbar-expand-lg navbar-dark">

<div class="container-fluid px-3">


<a class="navbar-brand"
   href="<?= base_url() ?>">

    <i class="fas fa-chart-line mr-2"></i>

    SimSGTK

</a>


<button class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navMenu">

    <span class="navbar-toggler-icon"></span>

</button>


<div class="collapse navbar-collapse"
     id="navMenu">


<ul class="navbar-nav me-auto">


<li class="nav-item">

<a class="nav-link"
   href="<?= base_url('index.php/public_mutasi') ?>">

    <i class="fas fa-users mr-1"></i>

    Siswa Mutasi

</a>

</li>


<li class="nav-item">

<a class="nav-link"
   href="<?= base_url('index.php/cari_nisn') ?>">

    <i class="fas fa-id-card mr-1"></i>

    Cari NISN

</a>

</li>


<li class="nav-item">

<a class="nav-link"
   href="<?= site_url('buku_tamu/daftar_hari_ini') ?>">

    <i class="fas fa-clipboard-list mr-1"></i>

    Buku Tamu

</a>

</li>


<li class="nav-item">

<a class="nav-link"
   href="<?= base_url('index.php/izin/scan') ?>">

    <i class="fas fa-qrcode mr-1"></i>

    Izin Keluar

</a>

</li>


<li class="nav-item">

<a class="nav-link"
   href="<?= base_url('index.php/AbsensiQR/scan') ?>">

    <i class="fas fa-qrcode mr-1"></i>

    Absensi QR

</a>

</li>


<li class="nav-item">

<a class="nav-link"
   href="<?= base_url(
       'index.php/DashboardMBG?tanggal='
       . date('Y-m-d')
   ) ?>">

    <i class="fas fa-chart-bar mr-1"></i>

    Dashboard MBG

</a>

</li>


</ul>


<div class="d-flex align-items-center gap-2">


<button
    class="btn-theme-toggle"
    id="toggleDark"
    title="Ganti Tema">

    <i class="fas fa-moon"></i>

</button>


<div class="dropdown">


<button
    class="btn-login dropdown-toggle"
    type="button"
    data-bs-toggle="dropdown">

    <i class="fas fa-sign-in-alt mr-1"></i>

    Login

</button>


<ul class="dropdown-menu dropdown-menu-end">


<li>

<a class="dropdown-item"
   href="<?= base_url('index.php/auth/login') ?>">

    <i class="fas fa-user-shield text-primary mr-2"></i>

    Login Admin

</a>

</li>


<li>

<a class="dropdown-item"
   href="<?= base_url('index.php/SiswaAuth') ?>">

    <i class="fas fa-user-graduate text-success mr-2"></i>

    Login Siswa

</a>

</li>


<li>

<a class="dropdown-item"
   href="<?= base_url('index.php/auth/login') ?>">

    <i class="fas fa-chalkboard-teacher text-warning mr-2"></i>

    Login Guru / Wali Kelas

</a>

</li>


</ul>

</div>


</div>


</div>

</div>

</nav>

</header>


<!-- =====================================================
     MAIN
===================================================== -->

<main class="public-container">


<!-- HERO -->

<div class="public-hero">

<div class="row align-items-center">


<div class="col-lg-9">


<h2>

Selamat Datang di Sistem Mutasi Siswa 👋

</h2>


<p>

Dashboard informasi siswa dan mutasi sekolah

</p>


<small>

<i class="fas fa-calendar-alt mr-1"></i>

Tahun Ajaran Aktif:

<strong>

<?= isset($tahun_aktif) && $tahun_aktif
    ? htmlspecialchars($tahun_aktif->tahun)
    : '-'
?>

</strong>

</small>


</div>


<div class="col-lg-3 hero-icon">

<i class="fas fa-chart-line fa-5x"></i>

</div>


</div>

</div>


<!-- =====================================================
     SUMMARY
===================================================== -->

<div class="row">


<!-- AKTIF -->

<div class="col-xl-3 col-md-6 mb-4">

<div class="card summary-card shadow h-100">

<div class="card-body">


<div class="d-flex justify-content-between align-items-center">


<div>

<div class="summary-title">

Siswa Aktif

</div>


<div class="summary-number text-success">

<?= number_format(
    isset($aktif['total'])
        ? $aktif['total']
        : 0,
    0,
    ',',
    '.'
) ?>

</div>


<div class="summary-desc">

Tahun ajaran aktif

</div>

</div>


<div class="summary-icon icon-green">

<i class="fas fa-user-graduate"></i>

</div>


</div>


</div>

</div>

</div>


<!-- MASUK -->

<div class="col-xl-3 col-md-6 mb-4">

<div class="card summary-card shadow h-100">

<div class="card-body">


<div class="d-flex justify-content-between align-items-center">


<div>

<div class="summary-title">

Siswa Masuk

</div>


<div class="summary-number text-info">

<?= number_format(
    isset($masuk['total'])
        ? $masuk['total']
        : 0,
    0,
    ',',
    '.'
) ?>

</div>


<div class="summary-desc">

Mutasi masuk

</div>

</div>


<div class="summary-icon icon-info">

<i class="fas fa-door-open"></i>

</div>


</div>


</div>

</div>

</div>


<!-- KELUAR -->

<div class="col-xl-3 col-md-6 mb-4">

<div class="card summary-card shadow h-100">

<div class="card-body">


<div class="d-flex justify-content-between align-items-center">


<div>

<div class="summary-title">

Siswa Keluar

</div>


<div class="summary-number text-danger">

<?= number_format(
    isset($keluar['total'])
        ? $keluar['total']
        : 0,
    0,
    ',',
    '.'
) ?>

</div>


<div class="summary-desc">

Mutasi keluar

</div>

</div>


<div class="summary-icon icon-red">

<i class="fas fa-door-closed"></i>

</div>


</div>


</div>

</div>

</div>


<!-- ROMBEL -->

<div class="col-xl-3 col-md-6 mb-4">

<div class="card summary-card shadow h-100">

<div class="card-body">


<div class="d-flex justify-content-between align-items-center">


<div>

<div class="summary-title">

Jumlah Rombel

</div>


<div class="summary-number text-warning">

<?= isset($rombel['total'])
    ? $rombel['total']
    : 0
?>

</div>


<div class="summary-desc">

Rombel aktif

</div>

</div>


<div class="summary-icon icon-warning">

<i class="fas fa-school"></i>

</div>


</div>


</div>

</div>

</div>


</div>


<!-- =====================================================
     ROMBEL + SISWA AKTIF
===================================================== -->

<div class="row">


<div class="col-lg-6 mb-4">

<div class="card info-card shadow h-100">


<div class="info-card-header">


<div class="info-card-title text-primary">

<i class="fas fa-school mr-2"></i>

Jumlah Rombel / Kelas

</div>


<div class="info-card-subtitle">

Distribusi rombel berdasarkan tingkat

</div>


</div>


<div class="info-card-body">


<div class="table-responsive">


<table class="table table-bordered public-table">


<thead>

<tr>

<th>Tingkat</th>

<th class="text-center">Jumlah</th>

</tr>

</thead>


<tbody>


<tr>

<td>Kelas X</td>

<td class="text-center">

<?= isset($rombel['x'])
    ? $rombel['x']
    : 0
?>

</td>

</tr>


<tr>

<td>Kelas XI</td>

<td class="text-center">

<?= isset($rombel['xi'])
    ? $rombel['xi']
    : 0
?>

</td>

</tr>


<tr>

<td>Kelas XII</td>

<td class="text-center">

<?= isset($rombel['xii'])
    ? $rombel['xii']
    : 0
?>

</td>

</tr>


<tr class="table-secondary">

<td>

<strong>Total</strong>

</td>

<td class="text-center">

<strong>

<?= isset($rombel['total'])
    ? $rombel['total']
    : 0
?>

</strong>

</td>

</tr>


</tbody>

</table>


</div>

</div>

</div>

</div>


<div class="col-lg-6 mb-4">

<div class="card info-card shadow h-100">


<div class="info-card-header">


<div class="info-card-title text-success">

<i class="fas fa-user-graduate mr-2"></i>

Siswa Aktif

</div>


<div class="info-card-subtitle">

Peserta didik berdasarkan tingkat

</div>


</div>


<div class="info-card-body">


<div class="table-responsive">


<table class="table table-bordered public-table">


<thead>

<tr>

<th>Tingkat</th>

<th class="text-center">Jumlah</th>

</tr>

</thead>


<tbody>


<tr>

<td>Kelas X</td>

<td class="text-center">

<?= isset($aktif['x'])
    ? $aktif['x']
    : 0
?>

</td>

</tr>


<tr>

<td>Kelas XI</td>

<td class="text-center">

<?= isset($aktif['xi'])
    ? $aktif['xi']
    : 0
?>

</td>

</tr>


<tr>

<td>Kelas XII</td>

<td class="text-center">

<?= isset($aktif['xii'])
    ? $aktif['xii']
    : 0
?>

</td>

</tr>


<tr class="table-secondary">

<td>

<strong>Total</strong>

</td>

<td class="text-center">

<strong>

<?= isset($aktif['total'])
    ? $aktif['total']
    : 0
?>

</strong>

</td>

</tr>


</tbody>

</table>


</div>

</div>

</div>

</div>


</div>


<!-- =====================================================
     MUTASI
===================================================== -->

<div class="row">


<?php foreach ([

    [
        'title' => 'Siswa Masuk',
        'icon'  => 'fa-sign-in-alt',
        'color' => 'info',
        'data'  => $masuk
    ],

    [
        'title' => 'Siswa Keluar',
        'icon'  => 'fa-sign-out-alt',
        'color' => 'danger',
        'data'  => $keluar
    ]

] as $mutasi): ?>


<div class="col-lg-6 mb-4">


<div class="card info-card shadow h-100">


<div class="info-card-header">


<div class="info-card-title text-<?= $mutasi['color'] ?>">

<i class="fas <?= $mutasi['icon'] ?> mr-2"></i>

<?= $mutasi['title'] ?>

</div>


<div class="info-card-subtitle">

Rekap berdasarkan tingkat

</div>


</div>


<div class="info-card-body">


<table class="table table-bordered public-table">


<thead>

<tr>

<th>Tingkat</th>

<th class="text-center">Jumlah</th>

</tr>

</thead>


<tbody>


<tr>

<td>Kelas X</td>

<td class="text-center">

<?= isset($mutasi['data']['x'])
    ? $mutasi['data']['x']
    : 0
?>

</td>

</tr>


<tr>

<td>Kelas XI</td>

<td class="text-center">

<?= isset($mutasi['data']['xi'])
    ? $mutasi['data']['xi']
    : 0
?>

</td>

</tr>


<tr>

<td>Kelas XII</td>

<td class="text-center">

<?= isset($mutasi['data']['xii'])
    ? $mutasi['data']['xii']
    : 0
?>

</td>

</tr>


<tr class="table-secondary">

<td>

<strong>Total</strong>

</td>

<td class="text-center">

<strong>

<?= isset($mutasi['data']['total'])
    ? $mutasi['data']['total']
    : 0
?>

</strong>

</td>

</tr>


</tbody>

</table>


</div>

</div>


</div>


<?php endforeach; ?>


</div>


<!-- =====================================================
     GENDER
===================================================== -->

<div class="card info-card shadow mb-4">


<div class="info-card-header">


<div class="info-card-title text-primary">

<i class="fas fa-venus-mars mr-2"></i>

Siswa Aktif Berdasarkan Jenis Kelamin

</div>


<div class="info-card-subtitle">

Distribusi siswa laki-laki dan perempuan

</div>


</div>


<div class="info-card-body">


<div class="table-responsive">


<table class="table table-bordered public-table">


<thead>

<tr>

<th>Tingkat</th>

<th class="text-center">Laki-laki</th>

<th class="text-center">Perempuan</th>

<th class="text-center">Total</th>

</tr>

</thead>


<tbody>


<?php

$labelTingkat = [

    'x'   => 'X',

    'xi'  => 'XI',

    'xii' => 'XII'

];

?>


<?php foreach ($labelTingkat as $key => $label): ?>


<tr>

<td>

<strong>

Kelas <?= $label ?>

</strong>

</td>


<td class="text-center">

<?= isset($gender_tingkat[$key]['l'])
    ? $gender_tingkat[$key]['l']
    : 0
?>

</td>


<td class="text-center">

<?= isset($gender_tingkat[$key]['p'])
    ? $gender_tingkat[$key]['p']
    : 0
?>

</td>


<td class="text-center font-weight-bold">

<?= isset($gender_tingkat[$key]['total'])
    ? $gender_tingkat[$key]['total']
    : 0
?>

</td>

</tr>


<?php endforeach; ?>


<tr class="table-secondary">


<td>

<strong>Total</strong>

</td>


<td class="text-center">

<?= isset($gender_tingkat['total']['l'])
    ? $gender_tingkat['total']['l']
    : 0
?>

</td>


<td class="text-center">

<?= isset($gender_tingkat['total']['p'])
    ? $gender_tingkat['total']['p']
    : 0
?>

</td>


<td class="text-center">

<strong>

<?= isset($gender_tingkat['total']['total'])
    ? $gender_tingkat['total']['total']
    : 0
?>

</strong>

</td>


</tr>


</tbody>

</table>


</div>

</div>

</div>


<!-- =====================================================
     SISWA LULUS
===================================================== -->

<div class="card info-card shadow mb-4">


<div class="info-card-header">


<div class="info-card-title text-warning">

<i class="fas fa-graduation-cap mr-2"></i>

Siswa Lulus per Tahun Ajaran

</div>


<div class="info-card-subtitle">

Riwayat kelulusan siswa

</div>


</div>


<div class="info-card-body">


<div class="table-responsive">


<table class="table table-bordered public-table">


<thead>

<tr>

<th>Tahun Ajaran</th>

<th class="text-center">

Jumlah Siswa Lulus

</th>

</tr>

</thead>


<tbody>


<?php if (!empty($lulus)): ?>


<?php foreach ($lulus as $r): ?>


<tr>

<td>

<i class="fas fa-calendar-alt text-warning mr-2"></i>

<?= htmlspecialchars($r->tahun) ?>

</td>


<td class="text-center">

<span class="badge badge-warning stat-badge">

<?= $r->jumlah ?>

</span>

</td>

</tr>


<?php endforeach; ?>


<?php else: ?>


<tr>

<td colspan="2"
    class="text-center text-muted py-4">

<i class="fas fa-info-circle mr-1"></i>

Belum ada data siswa lulus.

</td>

</tr>


<?php endif; ?>


</tbody>

</table>


</div>

</div>

</div>


<!-- =====================================================
     PER ROMBEL
===================================================== -->

<div class="card info-card shadow mb-4">


<div class="info-card-header">


<div class="info-card-title text-primary">

<i class="fas fa-users mr-2"></i>

Jumlah Siswa per Rombongan Belajar

</div>


<div class="info-card-subtitle">

Data siswa aktif berdasarkan kelas

</div>


</div>


<div class="info-card-body">


<div class="table-responsive">


<table class="table table-bordered public-table">


<thead>

<tr>

<th width="50">No</th>

<th>Nama Kelas</th>

<th class="text-center">L</th>

<th class="text-center">P</th>

<th class="text-center">Total</th>

<th class="text-center">Download</th>

<th class="text-center">

Jumlah Download

</th>

</tr>

</thead>


<tbody>


<?php

$no = 1;

$sumL = 0;

$sumP = 0;

$sumTotal = 0;

?>


<?php if (!empty($per_rombel)): ?>


<?php foreach ($per_rombel as $r): ?>


<?php

$sumL += (int)$r->laki;

$sumP += (int)$r->perempuan;

$sumTotal += (int)$r->total;


$kelas = $this->db
    ->get_where(
        'kelas',
        ['nama' => $r->nama_kelas]
    )
    ->row();


$kelas_id = $kelas
    ? $kelas->id
    : 0;


$count = 0;

if ($kelas && isset($kelas->download_count)) {

    $count = (int)$kelas->download_count;

}

?>


<tr>


<td>

<?= $no++ ?>

</td>


<td>

<strong>

<?= htmlspecialchars(
    $r->nama_kelas
) ?>

</strong>

</td>


<td class="text-center">

<?= $r->laki ?>

</td>


<td class="text-center">

<?= $r->perempuan ?>

</td>


<td class="text-center font-weight-bold">

<?= $r->total ?>

</td>


<td class="text-center">


<?php if ($kelas_id): ?>


<a
    href="<?= base_url(
        'index.php/dashboard/download_excel/'
        . $kelas_id
    ) ?>"
    class="btn btn-sm btn-success"
>


<i class="fas fa-file-excel mr-1"></i>

Download


</a>


<?php else: ?>


<span class="text-muted">

-

</span>


<?php endif; ?>


</td>


<td class="text-center">


<?= $count > 0
    ? $count . 'x download'
    : '-'
?>


</td>


</tr>


<?php endforeach; ?>


<tr class="table-secondary">


<td colspan="2"
    class="text-right">

<strong>

Jumlah Keseluruhan

</strong>

</td>


<td class="text-center">

<?= $sumL ?>

</td>


<td class="text-center">

<?= $sumP ?>

</td>


<td class="text-center">

<?= $sumTotal ?>

</td>


<td colspan="2"></td>


</tr>


<?php else: ?>


<tr>

<td colspan="7"
    class="text-center text-muted py-4">

<i class="fas fa-users-slash fa-2x mb-2"></i>

<br>

Belum ada data siswa aktif.

</td>

</tr>


<?php endif; ?>


</tbody>

</table>


</div>

</div>

</div>


<!-- =====================================================
     CHART
===================================================== -->

<div class="row">


<div class="col-lg-8 mb-4">


<div class="card info-card shadow h-100">


<div class="info-card-header">


<div class="info-card-title text-primary">

<i class="fas fa-chart-bar mr-2"></i>

Grafik Siswa per Tingkat

</div>


<div class="info-card-subtitle">

Perbandingan siswa aktif dan keluar

</div>


</div>


<div class="info-card-body">


<div class="chart-box">

<canvas id="chartAktifKeluar"></canvas>

</div>


</div>

</div>


</div>


<div class="col-lg-4 mb-4">


<div class="card info-card shadow h-100">


<div class="info-card-header">


<div class="info-card-title text-info">

<i class="fas fa-chart-pie mr-2"></i>

Rasio Aktif vs Keluar

</div>


<div class="info-card-subtitle">

Perbandingan keseluruhan

</div>


</div>


<div class="info-card-body">


<div class="chart-box">

<canvas id="chartRasio"></canvas>

</div>


</div>

</div>


</div>


</div>


<!-- =====================================================
     CLOCK
===================================================== -->

<div class="row">


<div class="col-lg-5 mb-4">


<div class="card info-card shadow h-100">


<div class="info-card-body text-center">


<div class="text-muted">

Hari Ini

</div>


<div id="publicClock"
     class="clock">

00:00:00

</div>


<div id="publicDate"
     class="text-muted">

-

</div>


<hr>


<div class="text-muted">

Tahun Ajaran Aktif

</div>


<h4 class="text-primary font-weight-bold">


<?= isset($tahun_aktif) && $tahun_aktif
    ? htmlspecialchars($tahun_aktif->tahun)
    : '-'
?>


</h4>


</div>

</div>


</div>


<div class="col-lg-7 mb-4">


<div class="card info-card shadow h-100">


<div class="info-card-header">


<div class="info-card-title text-primary">

<i class="fas fa-info-circle mr-2"></i>

Informasi

</div>


</div>


<div class="info-card-body">


<div class="alert alert-primary">

<i class="fas fa-chart-line mr-2"></i>

Dashboard menampilkan data berdasarkan

<strong>

Tahun Ajaran Aktif.

</strong>

</div>


<div class="alert alert-success">

<i class="fas fa-user-graduate mr-2"></i>

Data siswa aktif mengikuti data siswa

yang berstatus aktif.

</div>


<div class="alert alert-info mb-0">

<i class="fas fa-sync-alt mr-2"></i>

Data akan mengikuti perubahan terbaru

pada sistem.

</div>


</div>

</div>


</div>


</div>


</main>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="public-footer">

<div class="container">

© <?= date('Y') ?>

<a href="https://www.profilsaya.my.id">

SimSGTK

</a>

—

Sistem Informasi Siswa dan GTK

<strong>Nazmudin</strong>

</div>

</footer>


<!-- =====================================================
     CHATBOT
===================================================== -->

<div id="chatbot">


<div id="chatbot-header">


<span>

<i class="fas fa-robot"></i>

SiMuMu

</span>


<button id="chatbot-close">

&times;

</button>


</div>


<div id="chatbot-body">


<div class="bot-msg">

👋 Halo! Saya Asisten Mutasi Siswa.

<br>

Silakan ketik

<strong>cek</strong>

untuk memunculkan list pertanyaan.

</div>


</div>


<div id="chatbot-footer">


<input
    type="text"
    id="chatbot-input"
    placeholder="Ketik 'cek' untuk lihat menu..."
>


<button id="chatbot-send">

<i class="fas fa-paper-plane"></i>

</button>


</div>


</div>


<div id="chatbot-wrapper">


<button id="chatbot-toggle">

<i class="fas fa-comments"></i>

</button>


<div class="chatbot-label">

Chat di sini

</div>


</div>


<!-- =====================================================
     JS
===================================================== -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>


<script>

/* =====================================================
   CLOCK
===================================================== */

function updatePublicClock() {

    var now = new Date();

    var hari = [
        'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    ];

    var bulan = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    var jam =
        String(now.getHours()).padStart(2,'0');

    var menit =
        String(now.getMinutes()).padStart(2,'0');

    var detik =
        String(now.getSeconds()).padStart(2,'0');


    document.getElementById(
        'publicClock'
    ).innerHTML =
        jam + ':' + menit + ':' + detik;


    document.getElementById(
        'publicDate'
    ).innerHTML =

        hari[now.getDay()] +
        ', ' +
        now.getDate() +
        ' ' +
        bulan[now.getMonth()] +
        ' ' +
        now.getFullYear();

}


updatePublicClock();

setInterval(
    updatePublicClock,
    1000
);


/* =====================================================
   DARK MODE
===================================================== */

var btnToggle =
    document.getElementById('toggleDark');


if (btnToggle) {


    var icon =
        btnToggle.querySelector('i');


    if (
        localStorage.getItem('darkMode')
        === 'true'
    ) {

        document.body.classList.add(
            'dark-mode'
        );

        icon.classList.remove(
            'fa-moon'
        );

        icon.classList.add(
            'fa-sun'
        );

    }


    btnToggle.addEventListener(
        'click',
        function() {

            document.body.classList.toggle(
                'dark-mode'
            );


            var dark =
                document.body.classList.contains(
                    'dark-mode'
                );


            localStorage.setItem(
                'darkMode',
                dark
            );


            icon.classList.remove(
                'fa-moon',
                'fa-sun'
            );


            icon.classList.add(
                dark
                    ? 'fa-sun'
                    : 'fa-moon'
            );

        }
    );

}


/* =====================================================
   BAR CHART
===================================================== */

var barCanvas =
    document.getElementById(
        'chartAktifKeluar'
    );


if (barCanvas) {


    new Chart(

        barCanvas.getContext('2d'),

        {

            type: 'bar',

            data: {

                labels: [
                    'Kelas X',
                    'Kelas XI',
                    'Kelas XII'
                ],

                datasets: [

                    {

                        label:
                            'Siswa Aktif',

                        data: [

                            <?= isset($aktif['x'])
                                ? (int)$aktif['x']
                                : 0
                            ?>,

                            <?= isset($aktif['xi'])
                                ? (int)$aktif['xi']
                                : 0
                            ?>,

                            <?= isset($aktif['xii'])
                                ? (int)$aktif['xii']
                                : 0
                            ?>

                        ],

                        backgroundColor:
                            'rgba(28,200,138,.75)'

                    },


                    {

                        label:
                            'Siswa Keluar',

                        data: [

                            <?= isset($keluar['x'])
                                ? (int)$keluar['x']
                                : 0
                            ?>,

                            <?= isset($keluar['xi'])
                                ? (int)$keluar['xi']
                                : 0
                            ?>,

                            <?= isset($keluar['xii'])
                                ? (int)$keluar['xii']
                                : 0
                            ?>

                        ],

                        backgroundColor:
                            'rgba(231,74,59,.75)'

                    }

                ]

            },


            options: {

                responsive: true,

                maintainAspectRatio: false,

                legend: {

                    position: 'top'

                },

                scales: {

                    yAxes: [

                        {

                            ticks: {

                                beginAtZero: true,

                                precision: 0

                            }

                        }

                    ]

                }

            }

        }

    );

}


/* =====================================================
   DOUGHNUT
===================================================== */

var doughnutCanvas =
    document.getElementById(
        'chartRasio'
    );


if (doughnutCanvas) {


    new Chart(

        doughnutCanvas.getContext('2d'),

        {

            type: 'doughnut',

            data: {

                labels: [
                    'Siswa Aktif',
                    'Siswa Keluar'
                ],

                datasets: [

                    {

                        data: [

                            <?= isset($aktif['total'])
                                ? (int)$aktif['total']
                                : 0
                            ?>,

                            <?= isset($keluar['total'])
                                ? (int)$keluar['total']
                                : 0
                            ?>

                        ],

                        backgroundColor: [

                            '#1cc88a',

                            '#e74a3b'

                        ],

                        borderWidth: 0

                    }

                ]

            },


            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutoutPercentage: 65,

                legend: {

                    position: 'bottom'

                }

            }

        }

    );

}


/* =====================================================
   CHATBOT
===================================================== */

var chatbot =
    document.getElementById('chatbot');

var toggleBtn =
    document.getElementById(
        'chatbot-toggle'
    );

var closeBtn =
    document.getElementById(
        'chatbot-close'
    );

var sendBtn =
    document.getElementById(
        'chatbot-send'
    );

var input =
    document.getElementById(
        'chatbot-input'
    );

var chatBody =
    document.getElementById(
        'chatbot-body'
    );


if (toggleBtn) {

    toggleBtn.onclick = function() {

        chatbot.style.display =
            'flex';

        input.focus();

    };

}


if (closeBtn) {

    closeBtn.onclick = function() {

        chatbot.style.display =
            'none';

    };

}


function appendMessage(
    text,
    className
) {

    var div =
        document.createElement('div');


    div.className =
        className;


    div.innerText =
        text;


    chatBody.appendChild(div);


    chatBody.scrollTop =
        chatBody.scrollHeight;

}


function sendMessage() {

    var msg =
        input.value.trim();


    if (!msg) {

        return;

    }


    appendMessage(
        msg,
        'user-msg'
    );


    input.value = '';


    setTimeout(
        function() {

            botReply(msg);

        },
        500
    );

}


if (sendBtn) {

    sendBtn.onclick =
        sendMessage;

}


if (input) {

    input.addEventListener(
        'keypress',
        function(e) {

            if (e.key === 'Enter') {

                sendMessage();

            }

        }
    );

}


function botReply(text) {


    var url =
        "<?= site_url('chatbot/reply') ?>";


    var csrfName =
        "<?= $this->security->get_csrf_token_name(); ?>";


    var csrfHash =
        "<?= $this->security->get_csrf_hash(); ?>";


    var params =

        "message=" +
        encodeURIComponent(text) +

        "&" +
        csrfName +
        "=" +
        csrfHash;


    fetch(
        url,
        {

            method: 'POST',

            headers: {

                'Content-Type':
                    'application/x-www-form-urlencoded'

            },

            body: params

        }
    )

    .then(
        function(res) {

            if (!res.ok) {

                throw new Error(
                    'HTTP Error'
                );

            }

            return res.json();

        }
    )

    .then(
        function(data) {

            appendMessage(
                data.reply ||
                'Tidak ada jawaban.',
                'bot-msg'
            );

        }
    )

    .catch(
        function() {

            appendMessage(
                '⚠️ Server sedang bermasalah.',
                'bot-msg'
            );

        }
    );

}


/* =====================================================
   SERVICE WORKER
===================================================== */

if ('serviceWorker' in navigator) {

    window.addEventListener(
        'load',
        function() {

            navigator.serviceWorker
                .register(
                    '<?= base_url(
                        'service-worker.js'
                    ) ?>'
                )
                .catch(
                    function(err) {

                        console.error(
                            'PWA gagal:',
                            err
                        );

                    }
                );

        }
    );

}

</script>


</body>

</html>