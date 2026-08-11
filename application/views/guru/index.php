<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>

/* =========================================================
   GURU DASHBOARD
========================================================= */

.guru-page {
    padding-bottom: 30px;
}

/* =========================================================
   HEADER
========================================================= */

.guru-header {
    position: relative;
    overflow: hidden;
    border-radius: 22px;
    padding: 32px 36px;
    margin-bottom: 28px;
    color: #fff;
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.12);
}

.guru-header:before {
    content: "";
    position: absolute;
    width: 260px;
    height: 260px;
    right: -80px;
    top: -100px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
}

.guru-header:after {
    content: "";
    position: absolute;
    width: 150px;
    height: 150px;
    right: 110px;
    bottom: -100px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}

.guru-header-content {
    position: relative;
    z-index: 2;
}

.guru-header h2 {
    font-weight: 700;
    margin-bottom: 6px;
}

.guru-header p {
    margin-bottom: 0;
    opacity: .9;
}

.guru-header-icon {
    position: relative;
    z-index: 2;
    opacity: .25;
}

/* =========================================================
   SUMMARY CARD
========================================================= */

.guru-summary {
    border: 0;
    border-radius: 18px;
    overflow: hidden;
    transition: all .25s ease;
}

.guru-summary:hover {
    transform: translateY(-5px);
    box-shadow: 0 .8rem 2rem rgba(0,0,0,.15) !important;
}

.guru-summary .card-body {
    padding: 22px;
}

.guru-summary-title {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #888;
}

.guru-summary-number {
    font-size: 32px;
    line-height: 1.1;
    font-weight: 700;
    margin: 7px 0;
}

.guru-summary-desc {
    font-size: 11px;
    color: #999;
}

.guru-summary-icon {
    width: 62px;
    height: 62px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 24px;
    flex-shrink: 0;
}

.guru-primary {
    background: #4e73df;
}

.guru-success {
    background: #1cc88a;
}

.guru-info {
    background: #36b9cc;
}

.guru-warning {
    background: #f6c23e;
}

.guru-danger {
    background: #e74a3b;
}

/* =========================================================
   MAIN CARD
========================================================= */

.guru-main-card {
    border: 0;
    border-radius: 20px;
    overflow: hidden;
}

.guru-main-card .card-header {
    background: #fff;
    border-bottom: 1px solid #eee;
    padding: 20px 24px;
}

.guru-main-title {
    font-size: 20px;
    font-weight: 700;
    color: #4e73df;
}

.guru-main-subtitle {
    font-size: 12px;
    color: #999;
}

/* =========================================================
   BUTTONS
========================================================= */

.guru-actions .btn {
    margin-left: 4px;
    border-radius: 8px;
    font-weight: 600;
}

.guru-actions .btn i {
    margin-right: 3px;
}

/* =========================================================
   TABLE
========================================================= */

.guru-table {
    margin-bottom: 0;
}

.guru-table thead th {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .3px;
    white-space: nowrap;
    vertical-align: middle;
}

.guru-table tbody td {
    vertical-align: middle;
}

.guru-table tbody tr {
    transition: background .15s ease;
}

.guru-table tbody tr:hover {
    background: rgba(78,115,223,.05);
}

.guru-name {
    font-weight: 600;
}

/* =========================================================
   STATUS BADGE
========================================================= */

.guru-status {
    display: inline-block;
    min-width: 75px;
    padding: 7px 10px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    text-align: center;
}

.guru-status-pppk {
    background: #1cc88a;
    color: #fff;
}

.guru-status-pns {
    background: #36b9cc;
    color: #fff;
}

.guru-status-pppkpw {
    background: #f6c23e;
    color: #fff;
}

.guru-status-honorer {
    background: #e74a3b;
    color: #fff;
}

.guru-status-other {
    background: #858796;
    color: #fff;
}

/* =========================================================
   EMPTY
========================================================= */

.guru-empty {
    padding: 60px 20px !important;
}

.guru-empty i {
    font-size: 45px;
    margin-bottom: 15px;
    opacity: .35;
}

/* =========================================================
   MODAL
========================================================= */

.guru-modal .modal-content {
    border: 0;
    border-radius: 18px;
    overflow: hidden;
}

.guru-modal .modal-header {
    border-bottom: 0;
    color: #fff;
    background: linear-gradient(135deg, #4e73df, #224abe);
}

.guru-modal .modal-title {
    font-weight: 700;
}

.guru-modal .modal-body {
    padding: 25px;
}

.guru-modal .form-control {
    border-radius: 8px;
}

.guru-modal label {
    font-weight: 600;
    font-size: 13px;
}

/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767px) {

    .guru-header {
        padding: 25px 20px;
    }

    .guru-header-icon {
        display: none;
    }

    .guru-header h2 {
        font-size: 25px;
    }

    .guru-summary-number {
        font-size: 27px;
    }

    .guru-actions {
        margin-top: 15px;
        width: 100%;
    }

    .guru-actions .btn {
        margin: 3px 0;
    }

    .guru-main-card .card-header {
        padding: 18px;
    }

}

</style>


<div class="container-fluid guru-page">


<!-- =========================================================
     HEADER
========================================================= -->

<div class="guru-header">

    <div class="row align-items-center">

        <div class="col-lg-9 guru-header-content">

            <h2>
                Data Guru 👋
            </h2>

            <p>
                Kelola dan pantau data tenaga pendidik sekolah
            </p>

        </div>

        <div class="col-lg-3 text-right guru-header-icon">

            <i class="fas fa-chalkboard-teacher fa-5x"></i>

        </div>

    </div>

</div>


<!-- =========================================================
     STATISTIK
========================================================= -->

<div class="row">


<!-- TOTAL GURU -->

<div class="col-xl col-lg-4 col-md-6 mb-4">

    <div class="card guru-summary shadow h-100">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="guru-summary-title">
                        Total Guru
                    </div>

                    <div class="guru-summary-number">
                        <?= number_format(isset($total_guru) ? $total_guru : 0) ?>
                    </div>

                    <div class="guru-summary-desc">
                        Seluruh tenaga pendidik
                    </div>

                </div>

                <div class="guru-summary-icon guru-primary">

                    <i class="fas fa-users"></i>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- PPPK -->

<div class="col-xl col-lg-4 col-md-6 mb-4">

    <div class="card guru-summary shadow h-100">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="guru-summary-title">
                        Guru PPPK
                    </div>

                    <div class="guru-summary-number text-success">
                        <?= number_format(isset($guru_pppk) ? $guru_pppk : 0) ?>
                    </div>

                    <div class="guru-summary-desc">
                        Pegawai Pemerintah
                    </div>

                </div>

                <div class="guru-summary-icon guru-success">

                    <i class="fas fa-user-tie"></i>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- PNS -->

<div class="col-xl col-lg-4 col-md-6 mb-4">

    <div class="card guru-summary shadow h-100">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="guru-summary-title">
                        Guru PNS
                    </div>

                    <div class="guru-summary-number text-info">
                        <?= number_format(isset($guru_pns) ? $guru_pns : 0) ?>
                    </div>

                    <div class="guru-summary-desc">
                        Pegawai Negeri Sipil
                    </div>

                </div>

                <div class="guru-summary-icon guru-info">

                    <i class="fas fa-user-shield"></i>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- PPPKPW -->

<div class="col-xl col-lg-4 col-md-6 mb-4">

    <div class="card guru-summary shadow h-100">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="guru-summary-title">
                        Guru PPPKPW
                    </div>

                    <div class="guru-summary-number text-warning">
                        <?= number_format(isset($guru_pppkpw) ? $guru_pppkpw : 0) ?>
                    </div>

                    <div class="guru-summary-desc">
                        PPPK Paruh Waktu
                    </div>

                </div>

                <div class="guru-summary-icon guru-warning">

                    <i class="fas fa-user-clock"></i>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- HONORER -->

<div class="col-xl col-lg-4 col-md-6 mb-4">

    <div class="card guru-summary shadow h-100">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="guru-summary-title">
                        Honorer
                    </div>

                    <div class="guru-summary-number text-danger">
                        <?= number_format(isset($guru_honorer) ? $guru_honorer : 0) ?>
                    </div>

                    <div class="guru-summary-desc">
                        Guru Honorer
                    </div>

                </div>

                <div class="guru-summary-icon guru-danger">

                    <i class="fas fa-user"></i>

                </div>

            </div>

        </div>

    </div>

</div>


</div>


<!-- =========================================================
     FLASH MESSAGE
========================================================= -->

<?php if ($this->session->flashdata('success')): ?>

<div class="alert alert-success alert-dismissible fade show">

    <i class="fas fa-check-circle mr-2"></i>

    <?= $this->session->flashdata('success'); ?>

    <button type="button"
            class="close"
            data-dismiss="alert">

        <span>&times;</span>

    </button>

</div>

<?php endif; ?>


<?php if ($this->session->flashdata('error')): ?>

<div class="alert alert-danger alert-dismissible fade show">

    <i class="fas fa-exclamation-circle mr-2"></i>

    <?= $this->session->flashdata('error'); ?>

    <button type="button"
            class="close"
            data-dismiss="alert">

        <span>&times;</span>

    </button>

</div>

<?php endif; ?>


<!-- =========================================================
     DATA GURU
========================================================= -->

<div class="card guru-main-card shadow mb-4">


    <!-- HEADER CARD -->

    <div class="card-header">

        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <div>

                <div class="guru-main-title">

                    <i class="fas fa-chalkboard-teacher mr-2"></i>

                    Daftar Guru

                </div>

                <div class="guru-main-subtitle">

                    Data tenaga pendidik sekolah

                </div>

            </div>


            <!-- =================================================
                 ACTION BUTTON
            ================================================= -->

            <div class="guru-actions">


                <!-- TEMPLATE -->

                <a href="<?= base_url('assets/templates/templateimporguru.xlsx') ?>"
                   class="btn btn-outline-success btn-sm"
                   download>

                    <i class="fas fa-download"></i>

                    Template

                </a>


                <!-- EXPORT -->

                <a href="<?= site_url('guru/export_excel') ?>"
                   class="btn btn-success btn-sm">

                    <i class="fas fa-file-excel"></i>

                    Export

                </a>


                <!-- IMPORT -->

                <button type="button"
                        class="btn btn-primary btn-sm"
                        onclick="openGuruModal('importModal')">

                    <i class="fas fa-upload"></i>

                    Import

                </button>


                <!-- TAMBAH -->

                <button type="button"
                        class="btn btn-info btn-sm"
                        onclick="openGuruModal('addModal')">

                    <i class="fas fa-plus"></i>

                    Tambah

                </button>


            </div>

        </div>

    </div>


    <!-- =================================================
         TABLE
    ================================================= -->

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover guru-table">

                <thead class="thead-light">

                    <tr>

                        <th width="50">
                            No
                        </th>

                        <th>
                            NIP
                        </th>

                        <th>
                            Nama
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Telepon
                        </th>

                        <th width="120"
                            class="text-center">

                            Aksi

                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php if (!empty($guru)): ?>

                    <?php

                    $no = isset($start) ? $start + 1 : 1;

                    foreach ($guru as $g):

                        $status = strtoupper(
                            trim(
                                isset($g->status_kepegawaian)
                                ? $g->status_kepegawaian
                                : ''
                            )
                        );

                    ?>

                    <tr>


                        <td>
                            <?= $no++ ?>
                        </td>


                        <td>
                            <?= htmlspecialchars($g->nip ?? '') ?>
                        </td>


                        <td>

                            <span class="guru-name">

                                <?= htmlspecialchars($g->nama ?? '') ?>

                            </span>

                        </td>


                        <!-- STATUS -->

                        <td>

                            <?php if ($status == 'PPPK'): ?>

                                <span class="guru-status guru-status-pppk">
                                    PPPK
                                </span>

                            <?php elseif ($status == 'PNS'): ?>

                                <span class="guru-status guru-status-pns">
                                    PNS
                                </span>

                            <?php elseif ($status == 'PPPKPW'): ?>

                                <span class="guru-status guru-status-pppkpw">
                                    PPPKPW
                                </span>

                            <?php elseif ($status == 'HONORER'): ?>

                                <span class="guru-status guru-status-honorer">
                                    HONORER
                                </span>

                            <?php else: ?>

                                <span class="guru-status guru-status-other">
                                    <?= $status ?: 'Belum Diisi' ?>
                                </span>

                            <?php endif; ?>

                        </td>


                        <td>
                            <?= htmlspecialchars($g->email ?? '') ?>
                        </td>


                        <td>
                            <?= htmlspecialchars($g->telp ?? '') ?>
                        </td>


                        <!-- AKSI -->

                        <td class="text-center">


                            <a href="<?= site_url('guru/edit/'.$g->id) ?>"
                               class="btn btn-warning btn-sm"
                               title="Edit">

                                <i class="fas fa-edit"></i>

                            </a>


                            <a href="<?= site_url('guru/delete/'.$g->id) ?>"
                               class="btn btn-danger btn-sm"
                               title="Hapus"
                               onclick="return confirm('Hapus data guru ini?')">

                                <i class="fas fa-trash"></i>

                            </a>


                        </td>


                    </tr>

                    <?php endforeach; ?>


                <?php else: ?>


                    <tr>

                        <td colspan="7"
                            class="text-center guru-empty">

                            <i class="fas fa-user-slash d-block"></i>

                            <strong>
                                Data guru belum tersedia
                            </strong>

                            <div class="small text-muted mt-1">
                                Silakan tambahkan atau import data guru.
                            </div>

                        </td>

                    </tr>


                <?php endif; ?>


                </tbody>

            </table>

        </div>


        <!-- PAGINATION -->

        <?php if (!empty($pagination)): ?>

        <div class="mt-3">

            <?= $pagination ?>

        </div>

        <?php endif; ?>


    </div>

</div>


</div>


<!-- =========================================================
     MODAL TAMBAH GURU
========================================================= -->

<div class="modal fade guru-modal"
     id="addModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="addModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content">


            <form method="post"
                  action="<?= site_url('guru/add') ?>">


                <!-- HEADER -->

                <div class="modal-header">

                    <h5 class="modal-title"
                        id="addModalLabel">

                        <i class="fas fa-user-plus mr-2"></i>

                        Tambah Guru

                    </h5>


                    <button type="button"
                            class="close text-white"
                            onclick="closeGuruModal('addModal')">

                        <span>&times;</span>

                    </button>

                </div>


                <!-- BODY -->

                <div class="modal-body">


                    <input type="hidden"
                           name="<?= $this->security->get_csrf_token_name(); ?>"
                           value="<?= $this->security->get_csrf_hash(); ?>">


                    <!-- IDENTITAS -->

                    <h6 class="text-primary font-weight-bold mb-3">

                        <i class="fas fa-id-card mr-2"></i>

                        Identitas Guru

                    </h6>


                    <div class="row">


                        <!-- NIP -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    NIP
                                </label>

                                <input type="text"
                                       name="nip"
                                       class="form-control"
                                       placeholder="Masukkan NIP">

                            </div>

                        </div>


                        <!-- NAMA -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Nama
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="nama"
                                       class="form-control"
                                       placeholder="Nama lengkap"
                                       required>

                            </div>

                        </div>


                    </div>


                    <div class="row">


                        <!-- NIK -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    NIK
                                </label>

                                <input type="text"
                                       name="nik"
                                       class="form-control"
                                       placeholder="Nomor Induk Kependudukan">

                            </div>

                        </div>


                        <!-- JK -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Jenis Kelamin
                                </label>

                                <select name="jenis_kelamin"
                                        class="form-control">

                                    <option value="">
                                        - Pilih -
                                    </option>

                                    <option value="L">
                                        Laki-laki
                                    </option>

                                    <option value="P">
                                        Perempuan
                                    </option>

                                </select>

                            </div>

                        </div>


                    </div>


                    <hr>


                    <!-- KEPEGAWAIAN -->

                    <h6 class="text-primary font-weight-bold mb-3">

                        <i class="fas fa-user-tie mr-2"></i>

                        Kepegawaian

                    </h6>


                    <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Status Kepegawaian
                                </label>

                                <select name="status_kepegawaian"
                                        class="form-control">

                                    <option value="">
                                        - Pilih Status -
                                    </option>

                                    <option value="PNS">
                                        PNS
                                    </option>

                                    <option value="PPPK">
                                        PPPK
                                    </option>

                                    <option value="PPPKPW">
                                        PPPKPW
                                    </option>

                                    <option value="HONORER">
                                        HONORER
                                    </option>

                                </select>

                            </div>

                        </div>


                    </div>


                    <hr>


                    <!-- KONTAK -->

                    <h6 class="text-primary font-weight-bold mb-3">

                        <i class="fas fa-address-book mr-2"></i>

                        Kontak

                    </h6>


                    <div class="row">


                        <!-- EMAIL -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       placeholder="email@contoh.com">

                            </div>

                        </div>


                        <!-- TELEPON -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    No. Telepon
                                </label>

                                <input type="text"
                                       name="telp"
                                       class="form-control"
                                       placeholder="08xxxxxxxxxx">

                            </div>

                        </div>


                    </div>


                </div>


                <!-- FOOTER -->

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            onclick="closeGuruModal('addModal')">

                        <i class="fas fa-times mr-1"></i>

                        Batal

                    </button>


                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save mr-1"></i>

                        Simpan Guru

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>


<!-- =========================================================
     MODAL IMPORT GURU
========================================================= -->

<div class="modal fade guru-modal"
     id="importModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="importModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered"
         role="document">

        <div class="modal-content">


            <form method="post"
                  action="<?= site_url('guru/import_excel') ?>"
                  enctype="multipart/form-data">


                <!-- HEADER -->

                <div class="modal-header">

                    <h5 class="modal-title"
                        id="importModalLabel">

                        <i class="fas fa-file-import mr-2"></i>

                        Import Data Guru

                    </h5>


                    <button type="button"
                            class="close text-white"
                            onclick="closeGuruModal('importModal')">

                        <span>&times;</span>

                    </button>

                </div>


                <!-- BODY -->

                <div class="modal-body">


                    <input type="hidden"
                           name="<?= $this->security->get_csrf_token_name(); ?>"
                           value="<?= $this->security->get_csrf_hash(); ?>">


                    <div class="text-center mb-4">

                        <div class="guru-summary-icon guru-success mx-auto mb-3">

                            <i class="fas fa-file-excel"></i>

                        </div>

                        <h5 class="font-weight-bold">
                            Import Data Guru
                        </h5>

                        <p class="text-muted small mb-0">

                            Upload file Excel untuk menambahkan
                            atau memperbarui data guru.

                        </p>

                    </div>


                    <div class="form-group">

                        <label>

                            File Excel

                            <span class="text-danger">*</span>

                        </label>


                        <input type="file"
                               name="file"
                               class="form-control"
                               accept=".xls,.xlsx"
                               required>


                        <small class="form-text text-muted">

                            Format yang diperbolehkan:
                            <strong>.xls</strong> atau
                            <strong>.xlsx</strong>

                        </small>

                    </div>


                    <div class="alert alert-info small">

                        <i class="fas fa-info-circle mr-1"></i>

                        Pastikan baris pertama file Excel
                        berisi nama kolom sesuai template.

                    </div>


                    <div class="text-center">

                        <a href="<?= base_url('assets/templates/templateimporguru.xlsx') ?>"
                           class="btn btn-outline-success btn-sm"
                           download>

                            <i class="fas fa-download mr-1"></i>

                            Download Template

                        </a>

                    </div>


                </div>


                <!-- FOOTER -->

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            onclick="closeGuruModal('importModal')">

                        <i class="fas fa-times mr-1"></i>

                        Batal

                    </button>


                    <button type="submit"
                            class="btn btn-success">

                        <i class="fas fa-upload mr-1"></i>

                        Import Data

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>


<!-- =========================================================
     MODAL JAVASCRIPT
========================================================= -->

<script>

function openGuruModal(id) {

    var modal = document.getElementById(id);

    if (!modal) {
        console.log('Modal tidak ditemukan: ' + id);
        return;
    }

    /*
     * Bootstrap 4
     */
    if (typeof jQuery !== 'undefined' &&
        typeof jQuery.fn.modal === 'function') {

        jQuery('#' + id).modal('show');

        return;
    }

    /*
     * Bootstrap 5
     */
    if (typeof bootstrap !== 'undefined' &&
        bootstrap.Modal) {

        var instance = bootstrap.Modal.getOrCreateInstance(modal);

        instance.show();

        return;
    }

    /*
     * Fallback jika library modal tidak tersedia
     */

    modal.style.display = 'block';
    modal.classList.add('show');

    modal.setAttribute('aria-hidden', 'false');

    document.body.classList.add('modal-open');

}


function closeGuruModal(id) {

    var modal = document.getElementById(id);

    if (!modal) {
        return;
    }

    /*
     * Bootstrap 4
     */

    if (typeof jQuery !== 'undefined' &&
        typeof jQuery.fn.modal === 'function') {

        jQuery('#' + id).modal('hide');

        return;
    }

    /*
     * Bootstrap 5
     */

    if (typeof bootstrap !== 'undefined' &&
        bootstrap.Modal) {

        var instance = bootstrap.Modal.getInstance(modal);

        if (instance) {
            instance.hide();
        }

        return;
    }

    /*
     * Fallback
     */

    modal.style.display = 'none';
    modal.classList.remove('show');

    modal.setAttribute('aria-hidden', 'true');

    document.body.classList.remove('modal-open');

}


</script>