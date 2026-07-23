<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= $judul ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<style>

:root{

    --primary:#0d6efd;
    --primary2:#4dabff;

    --bg:#f5f7fb;
    --card:#ffffff;

    --text:#212529;
    --border:#e9ecef;

    --thead:#0d6efd;
    --thead-text:#ffffff;

}

body.dark{

    --bg:#1b1b1b;
    --card:#2a2a2a;

    --text:#f1f1f1;
    --border:#444;

    --thead:#343a40;

}

body{

    background:var(--bg);
    color:var(--text);

    font-family:
    "Segoe UI",
    sans-serif;

    transition:.25s;

}

/* ======================================================
   HEADER
====================================================== */

.top-header{

    background:
    linear-gradient(
        135deg,
        var(--primary),
        var(--primary2)
    );

    color:#fff;

    padding:18px 0;

    box-shadow:
    0 5px 15px rgba(0,0,0,.15);

}

.logo-title{

    font-size:24px;
    font-weight:700;

}

.logo-sub{

    opacity:.85;
    font-size:14px;

}

/* ======================================================
   CARD
====================================================== */

.card{

    border:none;

    border-radius:14px;

    background:var(--card);

    box-shadow:
    0 5px 18px rgba(0,0,0,.08);

}

.card-header{

    background:transparent;

    border-bottom:1px solid var(--border);

    font-weight:600;

}

/* ======================================================
   TABLE
====================================================== */

.table{

    color:var(--text);

}

.table thead th{

    background:var(--thead);

    color:var(--thead-text);

    border:none;

    position:sticky;

    top:0;

    z-index:10;

    white-space:nowrap;

}

.table tbody tr:hover{

    background:
    rgba(13,110,253,.06);

}

/* ======================================================
   BADGE
====================================================== */

.badge-masuk{

    background:#198754;

}

.badge-keluar{

    background:#dc3545;

}

/* ======================================================
   PAGINATION
====================================================== */

.pagination .page-link{

    border-radius:8px;

    margin:0 2px;

}

/* ======================================================
   DARK
====================================================== */

body.dark .table{

    color:#fff;

}

body.dark input,
body.dark select{

    background:#333;

    color:#fff;

    border-color:#555;

}

body.dark .form-control,
body.dark .form-select{

    background:#333;

    color:#fff;

}

</style>

</head>

<body>

<header class="top-header">

<div class="container">

<div class="d-flex
justify-content-between
align-items-center">

<div>

<div class="logo-title">

<i class="fas fa-right-left me-2"></i>

Data Mutasi Siswa

</div>

<div class="logo-sub">

Portal Informasi Mutasi Peserta Didik

</div>

</div>

<div>

<button
id="toggleDark"
class="btn btn-light btn-sm">

<i class="fas fa-moon"></i>

</button>

<a
href="<?= site_url('dashboard') ?>"
class="btn btn-warning btn-sm">

<i class="fas fa-home"></i>

Dashboard

</a>

</div>

</div>

</div>

</header>

<main class="container py-4">

<div class="row">

<div class="col-lg-12">

<div class="card mb-4">

<div class="card-body text-center">

<h3 class="mb-1">

Laporan Mutasi Siswa

</h3>

<p class="text-muted mb-0">

Tahun Ajaran

<strong>

<?= $tahun ?>

</strong>

</p>

</div>

</div>
<!-- =====================================================
     FILTER
====================================================== -->

<div class="card mb-4">

    <div class="card-header">

        <i class="fas fa-filter me-2"></i>

        Filter Data Mutasi

    </div>

    <div class="card-body">

        <form method="get">

            <div class="row g-3">

                <!-- Tahun -->

                <div class="col-lg-2 col-md-6">

                    <label class="form-label fw-semibold">

                        Tahun Ajaran

                    </label>

                    <select
                        name="tahun_id"
                        class="form-select">

                        <?php foreach($tahun_list as $t): ?>

                            <option
                                value="<?= $t->id ?>"
                                <?= ($tahun_id == $t->id) ? 'selected' : '' ?>>

                                <?= $t->tahun ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Kelas -->

                <div class="col-lg-2 col-md-6">

                    <label class="form-label fw-semibold">

                        Kelas

                    </label>

                    <select
                        name="kelas"
                        class="form-select">

                        <option value="">

                            Semua Kelas

                        </option>

                        <?php foreach($kelas_list as $k): ?>

                            <option
                                value="<?= $k->id ?>"
                                <?= ($this->input->get('kelas') == $k->id) ? 'selected' : '' ?>>

                                <?= $k->nama ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Jenis -->

                <div class="col-lg-2 col-md-6">

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
                            <?= ($this->input->get('jenis')=='masuk')?'selected':'' ?>>

                            Mutasi Masuk

                        </option>

                        <option
                            value="keluar"
                            <?= ($this->input->get('jenis')=='keluar')?'selected':'' ?>>

                            Mutasi Keluar

                        </option>

                    </select>

                </div>

                <!-- Search -->

                <div class="col-lg-4 col-md-12">

                    <label class="form-label fw-semibold">

                        Cari Nama Siswa

                    </label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Ketik nama siswa..."

                        value="<?= $this->input->get('search') ?>">

                </div>

                <!-- Tombol -->

                <div class="col-lg-2 col-md-12 d-grid">

                    <label class="form-label invisible">

                        tombol

                    </label>

                    <button
                        class="btn btn-primary">

                        <i class="fas fa-search me-1"></i>

                        Tampilkan

                    </button>

                </div>

            </div>

            <hr>

            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">

                <div>

                    <a
                        href="<?= site_url('public_mutasi') ?>"
                        class="btn btn-outline-secondary">

                        <i class="fas fa-rotate-left me-1"></i>

                        Reset Filter

                    </a>

                </div>

                <div class="d-flex flex-wrap gap-2">

                    <a
                        href="<?= site_url('public_mutasi/export_excel?'.http_build_query($_GET)) ?>"
                        class="btn btn-success">

                        <i class="fas fa-file-excel me-1"></i>

                        Export Excel

                    </a>

                    <a
                        href="<?= site_url('public_mutasi/export_pdf?'.http_build_query($_GET)) ?>"
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
     TABEL
====================================================== -->
<!-- =====================================================
     TABEL
====================================================== -->

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>

            <i class="fas fa-table me-2"></i>

            Data Mutasi Siswa

        </div>

        <span class="badge bg-primary fs-6">

            <?= count($mutasi) ?> Data

        </span>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover table-bordered align-middle mb-0">

                <thead>

                    <tr>

                        <th width="60">No</th>

                        <th>Nama Siswa</th>

                        <th width="120">NIS</th>

                        <th width="120">NISN</th>

                        <th>Kelas Asal</th>

                        <th width="130">Jenis</th>

                        <th width="140">Tanggal</th>

                        <th>Tujuan Sekolah</th>

                        <th width="140">Tahun</th>

                    </tr>

                </thead>

                <tbody>

                <?php if(!empty($mutasi)): ?>

                    <?php

                    $no = 1 + (int)$this->input->get('page');

                    foreach($mutasi as $m):

                    ?>

                    <tr>

                        <td class="text-center">

                            <?= $no++ ?>

                        </td>

                        <td>

                            <strong>

                                <?= $m->nama_siswa ?>

                            </strong>

                        </td>

                        <td>

                            <?= $m->nis ?: '-' ?>

                        </td>

                        <td>

                            <?= $m->nisn ?: '-' ?>

                        </td>

                        <td>

                            <?= $m->kelas_asal ?: '-' ?>

                        </td>

                        <td class="text-center">

                            <?php if($m->jenis=='masuk'): ?>

                                <span class="badge badge-masuk">

                                    Mutasi Masuk

                                </span>

                            <?php else: ?>

                                <span class="badge badge-keluar">

                                    Mutasi Keluar

                                </span>

                            <?php endif; ?>

                        </td>

                        <td class="text-center">

                            <?= $m->tanggal ? date('d-m-Y',strtotime($m->tanggal)) : '-' ?>

                        </td>

                        <td>

                            <?=

                            $m->jenis=='keluar'

                            ?

                            ($m->tujuan_sekolah ?: '-')

                            :

                            '-'

                            ?>

                        </td>

                        <td class="text-center">

                            <?= $m->tahun_ajaran ?>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="9">

                            <div class="text-center py-5">

                                <i class="fas fa-folder-open fa-4x text-secondary mb-3"></i>

                                <h5>

                                    Data Mutasi Tidak Ditemukan

                                </h5>

                                <p class="text-muted mb-0">

                                    Silakan ubah filter pencarian atau pilih tahun ajaran yang lain.

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
====================================================== -->

<?php if(!empty($pagination)): ?>

<div class="mt-4 d-flex justify-content-center">

    <?= $pagination ?>

</div>

<?php endif; ?>

</div>

</div>

</main>

<footer class="mt-5 py-4 border-top">

    <div class="container">

        <div class="row">

            <div class="col-md-6">

                <strong>

                    Sistem Mutasi Siswa

                </strong>

                <br>

                <small class="text-muted">

                    Portal Informasi Mutasi Peserta Didik

                </small>

            </div>

            <div class="col-md-6 text-md-end mt-3 mt-md-0">

                <small class="text-muted">

                    © <?= date('Y') ?>

                    All Rights Reserved.

                </small>

            </div>

        </div>

    </div>

</footer>

<script>

const btnDark=document.getElementById('toggleDark');

btnDark.onclick=function(){

    document.body.classList.toggle('dark');

    localStorage.setItem(

        'darkmode',

        document.body.classList.contains('dark')

    );

}

if(localStorage.getItem('darkmode')==='true'){

    document.body.classList.add('dark');

}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>