<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title><?= htmlspecialchars($judul) ?></title>

    <meta name="description"
          content="Portal Informasi Mutasi Peserta Didik">

    <meta name="theme-color"
          content="#0d6efd">


    <!-- ================= ICON ================= -->

    <link rel="icon"
          href="<?= base_url('assets/pwa/favicon.ico') ?>">

    <link rel="apple-touch-icon"
          href="<?= base_url('assets/pwa/icon-192.png') ?>">


    <!-- ================= CSS ================= -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


<style>

/* =====================================================
   ROOT
===================================================== */

:root {

    --primary: #0d6efd;
    --primary-dark: #0b5ed7;
    --primary-soft: #eaf2ff;

    --bg: #f5f7fb;
    --card: #ffffff;

    --text: #212529;
    --muted: #6c757d;

    --border: #e5e7eb;

    --success: #198754;
    --danger: #dc3545;

    --shadow:
        0 8px 25px rgba(0,0,0,.07);

    --radius: 16px;

}


/* =====================================================
   DARK MODE
===================================================== */

body.dark {

    --bg: #12141c;
    --card: #1c1f2b;

    --text: #f1f3f5;
    --muted: #adb5bd;

    --border: #343a55;

    --primary-soft: #202b4a;

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

    background: var(--bg);

    color: var(--text);

    transition:
        background .25s,
        color .25s;

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

    color: #fff;

    padding:
        18px 0;

    box-shadow:
        0 5px 20px rgba(13,110,253,.30);

}


.brand-title {

    font-size: 24px;

    font-weight: 700;

    line-height: 1.2;

}


.brand-subtitle {

    font-size: 13px;

    opacity: .85;

    margin-top: 4px;

}


/* =====================================================
   HEADER BUTTON
===================================================== */

.header-actions {

    display: flex;

    align-items: center;

    gap: 8px;

}


.btn-theme {

    width: 40px;

    height: 40px;

    border: 1px solid
        rgba(255,255,255,.4);

    background:
        rgba(255,255,255,.15);

    color: #fff;

    border-radius: 50%;

}


.btn-theme:hover {

    background:
        rgba(255,255,255,.25);

    color: #fff;

}


.btn-dashboard {

    border: none;

    background: #fff;

    color: #0d6efd;

    font-weight: 600;

    border-radius: 9px;

    padding:
        9px 14px;

}


.btn-dashboard:hover {

    background: #f0f4ff;

    color: #0b5ed7;

}


/* =====================================================
   MAIN
===================================================== */

.main-container {

    max-width: 1250px;

    margin: auto;

    padding:
        30px 15px
        50px;

}


/* =====================================================
   HERO
===================================================== */

.hero-card {

    position: relative;

    overflow: hidden;

    border: none;

    border-radius: 20px;

    padding: 30px;

    margin-bottom: 25px;

    color: #fff;

    background:
        linear-gradient(
            135deg,
            #4e73df,
            #224abe
        );

    box-shadow:
        0 10px 30px rgba(0,0,0,.13);

}


.hero-card::after {

    content: "";

    position: absolute;

    width: 250px;

    height: 250px;

    border-radius: 50%;

    right: -90px;

    top: -110px;

    background:
        rgba(255,255,255,.08);

}


.hero-icon {

    font-size: 70px;

    opacity: .18;

}


.hero-title {

    font-size: 27px;

    font-weight: 700;

    margin-bottom: 6px;

}


.hero-subtitle {

    opacity: .9;

    margin-bottom: 10px;

}


.year-badge {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    background:
        rgba(255,255,255,.16);

    border:
        1px solid
        rgba(255,255,255,.2);

    padding:
        7px 13px;

    border-radius: 20px;

    font-size: 13px;

}


/* =====================================================
   CARD
===================================================== */

.public-card {

    border: none;

    border-radius:
        var(--radius);

    background:
        var(--card);

    color:
        var(--text);

    box-shadow:
        var(--shadow);

    overflow: hidden;

}


.card-header-modern {

    padding:
        18px 22px;

    background:
        transparent;

    border-bottom:
        1px solid
        var(--border);

}


.card-title-modern {

    font-size: 16px;

    font-weight: 700;

}


.card-subtitle-modern {

    font-size: 12px;

    color:
        var(--muted);

    margin-top: 3px;

}


.card-body-modern {

    padding: 22px;

}


/* =====================================================
   FILTER
===================================================== */

.filter-icon {

    width: 40px;

    height: 40px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 10px;

    background:
        var(--primary-soft);

    color:
        var(--primary);

}


.form-label {

    font-size: 13px;

    color:
        var(--text);

}


.form-control,
.form-select {

    min-height: 42px;

    border-radius: 9px;

    border-color:
        var(--border);

}


.form-control:focus,
.form-select:focus {

    border-color:
        var(--primary);

    box-shadow:
        0 0 0 .2rem
        rgba(13,110,253,.12);

}


/* =====================================================
   BUTTON
===================================================== */

.btn {

    border-radius: 9px;

    font-weight: 500;

}


.btn-primary {

    background:
        var(--primary);

    border-color:
        var(--primary);

}


.btn-success {

    background:
        var(--success);

    border-color:
        var(--success);

}


.btn-danger {

    background:
        var(--danger);

    border-color:
        var(--danger);

}


/* =====================================================
   TABLE
===================================================== */

.table-wrapper {

    overflow-x: auto;

}


.public-table {

    margin-bottom: 0;

    color:
        var(--text);

}


.public-table thead th {

    background:
        var(--primary);

    color: #fff;

    border: none;

    font-size: 12px;

    text-transform: uppercase;

    letter-spacing: .3px;

    white-space: nowrap;

    padding:
        13px 12px;

}


.public-table tbody td {

    padding:
        12px;

    vertical-align: middle;

    border-color:
        var(--border);

    white-space: nowrap;

}


.public-table tbody tr {

    transition:
        background .15s;

}


.public-table tbody tr:hover {

    background:
        rgba(13,110,253,.05);

}


body.dark .public-table {

    color:
        #e9ecef;

}


body.dark .public-table thead th {

    background:
        #252b43;

    color:
        #fff;

}


body.dark .public-table tbody td {

    background:
        #171b2b;

    color:
        #e9ecef;

    border-color:
        #343a55;

}


body.dark .public-table tbody tr:hover td {

    background:
        rgba(13,110,253,.16);

}


/* =====================================================
   MUTASI BADGE
===================================================== */

.badge-masuk {

    display: inline-flex;

    align-items: center;

    gap: 5px;

    background:
        rgba(25,135,84,.12);

    color:
        #198754;

    border:
        1px solid
        rgba(25,135,84,.2);

    padding:
        6px 10px;

    border-radius: 20px;

    font-size: 11px;

    font-weight: 600;

}


.badge-keluar {

    display: inline-flex;

    align-items: center;

    gap: 5px;

    background:
        rgba(220,53,69,.12);

    color:
        #dc3545;

    border:
        1px solid
        rgba(220,53,69,.2);

    padding:
        6px 10px;

    border-radius: 20px;

    font-size: 11px;

    font-weight: 600;

}


body.dark .badge-masuk {

    color:
        #55d89a;

    background:
        rgba(25,135,84,.15);

}


body.dark .badge-keluar {

    color:
        #ff7b87;

    background:
        rgba(220,53,69,.15);

}


/* =====================================================
   EMPTY STATE
===================================================== */

.empty-state {

    padding:
        60px 20px;

    text-align: center;

}


.empty-state-icon {

    width: 80px;

    height: 80px;

    margin:
        0 auto 18px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    background:
        var(--primary-soft);

    color:
        var(--primary);

    font-size: 30px;

}


.empty-state h5 {

    font-weight: 700;

}


.empty-state p {

    color:
        var(--muted);

    margin-bottom: 0;

}


/* =====================================================
   RESULT HEADER
===================================================== */

.result-count {

    background:
        var(--primary-soft);

    color:
        var(--primary);

    border-radius: 20px;

    padding:
        6px 12px;

    font-size: 12px;

    font-weight: 700;

}


/* =====================================================
   PAGINATION
===================================================== */

.pagination {

    margin-bottom: 0;

}


.pagination .page-link {

    border: none;

    margin:
        0 3px;

    border-radius: 8px;

    color:
        var(--primary);

}


.pagination .page-item.active .page-link {

    background:
        var(--primary);

    color: #fff;

}


/* =====================================================
   FOOTER
===================================================== */

.public-footer {

    margin-top: 10px;

    padding:
        25px 15px;

    background:
        var(--card);

    border-top:
        1px solid
        var(--border);

}


.footer-title {

    font-weight: 700;

}


.footer-sub {

    color:
        var(--muted);

    font-size: 12px;

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width: 768px){

    .brand-title {

        font-size: 19px;

    }


    .brand-subtitle {

        font-size: 11px;

    }


    .hero-card {

        padding: 24px 20px;

    }


    .hero-title {

        font-size: 22px;

    }


    .hero-icon {

        display: none;

    }


    .header-actions {

        margin-top: 12px;

    }


    .card-body-modern {

        padding: 16px;

    }


    .table-wrapper {

        margin:
            0 -16px;

    }


    .public-table {

        min-width:
            1050px;

    }


    .filter-actions {

        flex-direction:
            column;

    }


    .filter-actions .btn {

        width: 100%;

    }

}

</style>

</head>


<body>


<!-- =====================================================
     HEADER
===================================================== -->

<header class="public-header">

<div class="container">

<div class="d-flex
            justify-content-between
            align-items-center
            flex-wrap">


<div>

<div class="brand-title">

<i class="fas fa-right-left me-2"></i>

Data Mutasi Siswa

</div>


<div class="brand-subtitle">

Portal Informasi Mutasi Peserta Didik

</div>

</div>


<div class="header-actions">


<button
    id="toggleDark"
    class="btn-theme"
    type="button"
    title="Ganti Tema">

    <i class="fas fa-moon"></i>

</button>


<a
    href="<?= site_url('dashboard') ?>"
    class="btn-dashboard">

    <i class="fas fa-home me-1"></i>

    Dashboard

</a>


</div>


</div>

</div>

</header>


<!-- =====================================================
     MAIN
===================================================== -->

<main class="main-container">


<!-- =====================================================
     HERO
===================================================== -->

<div class="hero-card">


<div class="row align-items-center">


<div class="col-md-9">


<div class="hero-title">

Laporan Mutasi Siswa

</div>


<div class="hero-subtitle">

Data mutasi peserta didik berdasarkan
tahun ajaran aktif.

</div>


<div class="year-badge">

<i class="fas fa-calendar-alt"></i>

Tahun Ajaran

<strong>

<?= htmlspecialchars($tahun) ?>

</strong>

</div>


</div>


<div class="col-md-3 text-end">

<i class="fas fa-right-left hero-icon"></i>

</div>


</div>

</div>


<!-- =====================================================
     FILTER
===================================================== -->

<div class="public-card mb-4">


<div class="card-header-modern">


<div class="d-flex align-items-center gap-3">


<div class="filter-icon">

<i class="fas fa-filter"></i>

</div>


<div>

<div class="card-title-modern">

Filter Data Mutasi

</div>


<div class="card-subtitle-modern">

Gunakan filter untuk menemukan data
mutasi tertentu.

</div>

</div>


</div>


</div>


<div class="card-body-modern">


<form method="get">


<div class="row g-3">


<!-- TAHUN -->

<div class="col-xl-2 col-md-6">


<label class="form-label fw-semibold">

Tahun Ajaran

</label>


<select
    name="tahun_id"
    class="form-select">


<?php foreach ($tahun_list as $t): ?>


<option
    value="<?= $t->id ?>"
    <?= ($tahun_id == $t->id)
        ? 'selected'
        : ''
    ?>>

    <?= htmlspecialchars($t->tahun) ?>

</option>


<?php endforeach; ?>


</select>


</div>


<!-- KELAS -->

<div class="col-xl-2 col-md-6">


<label class="form-label fw-semibold">

Kelas

</label>


<select
    name="kelas"
    class="form-select">


<option value="">

Semua Kelas

</option>


<?php foreach ($kelas_list as $k): ?>


<option
    value="<?= $k->id ?>"
    <?= ($this->input->get('kelas') == $k->id)
        ? 'selected'
        : ''
    ?>>

    <?= htmlspecialchars($k->nama) ?>

</option>


<?php endforeach; ?>


</select>


</div>


<!-- JENIS -->

<div class="col-xl-2 col-md-6">


<label class="form-label fw-semibold">

Jenis Mutasi

</label>


<select
    name="jenis"
    class="form-select">


<option value="">

Semua

</option>


<option
    value="masuk"
    <?= ($this->input->get('jenis') == 'masuk')
        ? 'selected'
        : ''
    ?>>

    Mutasi Masuk

</option>


<option
    value="keluar"
    <?= ($this->input->get('jenis') == 'keluar')
        ? 'selected'
        : ''
    ?>>

    Mutasi Keluar

</option>


</select>


</div>


<!-- SEARCH -->

<div class="col-xl-4 col-md-6">


<label class="form-label fw-semibold">

Cari Nama Siswa

</label>


<div class="input-group">


<span class="input-group-text bg-transparent">

<i class="fas fa-search text-primary"></i>

</span>


<input
    type="text"
    name="search"
    class="form-control"
    placeholder="Ketik nama siswa..."
    value="<?= htmlspecialchars(
        $this->input->get('search')
    ) ?>">


</div>


</div>


<!-- BUTTON -->

<div class="col-xl-2 col-md-6 d-grid">


<label class="form-label invisible">

Tampilkan

</label>


<button
    type="submit"
    class="btn btn-primary">

    <i class="fas fa-search me-1"></i>

    Tampilkan

</button>


</div>


</div>


<hr class="my-4">


<div class="d-flex
            justify-content-between
            align-items-center
            flex-wrap
            gap-3
            filter-actions">


<a
    href="<?= site_url('public_mutasi') ?>"
    class="btn btn-outline-secondary">

    <i class="fas fa-rotate-left me-1"></i>

    Reset Filter

</a>


<div class="d-flex
            gap-2
            flex-wrap">


<a
    href="<?= site_url(
        'public_mutasi/export_excel?'
        . http_build_query($_GET)
    ) ?>"
    class="btn btn-success">

    <i class="fas fa-file-excel me-1"></i>

    Export Excel

</a>


<a
    href="<?= site_url(
        'public_mutasi/export_pdf?'
        . http_build_query($_GET)
    ) ?>"
    target="_blank"
    class="btn btn-danger">

    <i class="fas fa-file-pdf me-1"></i>

    Export PDF

</a>


</div>


</div>


</form>


</div>

</div>


<!-- =====================================================
     DATA MUTASI
===================================================== -->

<div class="public-card">


<div class="card-header-modern">


<div class="d-flex
            justify-content-between
            align-items-center
            gap-3">


<div>

<div class="card-title-modern">

<i class="fas fa-table text-primary me-2"></i>

Data Mutasi Siswa

</div>


<div class="card-subtitle-modern">

Daftar mutasi peserta didik sesuai
filter yang dipilih.

</div>

</div>


<div class="result-count">

<i class="fas fa-database me-1"></i>

<?= count($mutasi) ?> Data

</div>


</div>


</div>


<div class="card-body p-0">


<div class="table-wrapper">


<table class="table public-table table-hover mb-0">


<thead>


<tr>


<th width="60"
    class="text-center">

No

</th>


<th>

Nama Siswa

</th>


<th width="120">

NIS

</th>


<th width="120">

NISN

</th>


<th>

Kelas Asal

</th>


<th width="140"
    class="text-center">

Jenis

</th>


<th width="140"
    class="text-center">

Tanggal

</th>


<th>

Tujuan Sekolah

</th>


<th width="130"
    class="text-center">

Tahun

</th>


</tr>


</thead>


<tbody>


<?php if (!empty($mutasi)): ?>


<?php


$no =
    1 +
    (int)$this->input->get('page');


foreach ($mutasi as $m):


?>


<tr>


<td class="text-center">

<?= $no++ ?>

</td>


<td>


<div class="fw-semibold">

<?= htmlspecialchars(
    $m->nama_siswa
) ?>

</div>


</td>


<td>

<?= !empty($m->nis)
    ? htmlspecialchars($m->nis)
    : '-'
?>

</td>


<td>

<?= !empty($m->nisn)
    ? htmlspecialchars($m->nisn)
    : '-'
?>

</td>


<td>

<?= !empty($m->kelas_asal)
    ? htmlspecialchars($m->kelas_asal)
    : '-'
?>

</td>


<td class="text-center">


<?php if ($m->jenis == 'masuk'): ?>


<span class="badge-masuk">

<i class="fas fa-arrow-right-to-bracket"></i>

Mutasi Masuk

</span>


<?php else: ?>


<span class="badge-keluar">

<i class="fas fa-arrow-right-from-bracket"></i>

Mutasi Keluar

</span>


<?php endif; ?>


</td>


<td class="text-center">


<?php if (!empty($m->tanggal)): ?>


<?= date(
    'd-m-Y',
    strtotime($m->tanggal)
) ?>


<?php else: ?>


-

<?php endif; ?>


</td>


<td>


<?php if ($m->jenis == 'keluar'): ?>


<?= !empty($m->tujuan_sekolah)
    ? htmlspecialchars(
        $m->tujuan_sekolah
    )
    : '-'
?>


<?php else: ?>


<span class="text-muted">

-

</span>


<?php endif; ?>


</td>


<td class="text-center">


<?= !empty($m->tahun_ajaran)
    ? htmlspecialchars(
        $m->tahun_ajaran
    )
    : '-'
?>


</td>


</tr>


<?php endforeach; ?>


<?php else: ?>


<tr>


<td colspan="9">


<div class="empty-state">


<div class="empty-state-icon">

<i class="fas fa-folder-open"></i>

</div>


<h5>

Data Mutasi Tidak Ditemukan

</h5>


<p>

Silakan ubah filter pencarian
atau pilih tahun ajaran yang lain.

</p>


</div>


</td>


</tr>


<?php endif; ?>


</tbody>


</table>


</div>


</div>


</div>


<!-- =====================================================
     PAGINATION
===================================================== -->

<?php if (!empty($pagination)): ?>


<div class="mt-4 d-flex justify-content-center">

<?= $pagination ?>

</div>


<?php endif; ?>


</main>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="public-footer">


<div class="container">


<div class="row align-items-center">


<div class="col-md-6">


<div class="footer-title">

Sistem Mutasi Siswa

</div>


<div class="footer-sub">

Portal Informasi Mutasi Peserta Didik

</div>


</div>


<div class="col-md-6 text-md-end mt-3 mt-md-0">


<div class="footer-sub">

© <?= date('Y') ?>

All Rights Reserved.

</div>


</div>


</div>


</div>


</footer>


<!-- =====================================================
     JS
===================================================== -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>

/* =====================================================
   DARK MODE
===================================================== */

const btnDark =
    document.getElementById(
        'toggleDark'
    );


const darkIcon =
    btnDark.querySelector('i');


function applyTheme() {

    const isDark =
        localStorage.getItem(
            'publicDarkMode'
        ) === 'true';


    if (isDark) {

        document.body.classList.add(
            'dark'
        );

        darkIcon.classList.remove(
            'fa-moon'
        );

        darkIcon.classList.add(
            'fa-sun'
        );

    } else {

        document.body.classList.remove(
            'dark'
        );

        darkIcon.classList.remove(
            'fa-sun'
        );

        darkIcon.classList.add(
            'fa-moon'
        );

    }

}


applyTheme();


btnDark.addEventListener(
    'click',
    function() {

        const isDark =
            document.body.classList.toggle(
                'dark'
            );


        localStorage.setItem(
            'publicDarkMode',
            isDark
        );


        darkIcon.classList.remove(
            'fa-moon',
            'fa-sun'
        );


        darkIcon.classList.add(
            isDark
                ? 'fa-sun'
                : 'fa-moon'
        );

    }
);

</script>


</body>

</html>