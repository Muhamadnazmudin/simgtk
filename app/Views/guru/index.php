<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| DEFAULT DATA
|--------------------------------------------------------------------------
*/

$search = isset($search)
    ? trim($search)
    : '';

$guru = isset($guru) && is_array($guru)
    ? $guru
    : [];

$total_guru = isset($total_guru)
    ? (int) $total_guru
    : 0;

$guru_pppk = isset($guru_pppk)
    ? (int) $guru_pppk
    : 0;

$guru_pns = isset($guru_pns)
    ? (int) $guru_pns
    : 0;

$guru_pppkpw = isset($guru_pppkpw)
    ? (int) $guru_pppkpw
    : 0;

$guru_honorer = isset($guru_honorer)
    ? (int) $guru_honorer
    : 0;

$start = isset($start)
    ? (int) $start
    : 0;


/*
|--------------------------------------------------------------------------
| JUMLAH HASIL PENCARIAN
|--------------------------------------------------------------------------
*/

$jumlah_hasil = count($guru);


/*
|--------------------------------------------------------------------------
| FUNGSI STATUS
|--------------------------------------------------------------------------
*/

$statusClass = function ($status) {

    $status = strtoupper(
        trim((string) $status)
    );

    switch ($status) {

        case 'PNS':
            return 'guru-status-pns';

        case 'PPPK':
            return 'guru-status-pppk';

        case 'PPPKPW':
            return 'guru-status-pppkpw';

        case 'HONOR':
        case 'HONORER':
            return 'guru-status-honorer';

        default:
            return 'guru-status-other';
    }
};


/*
|--------------------------------------------------------------------------
| LABEL STATUS
|--------------------------------------------------------------------------
*/

$statusLabel = function ($status) {

    $status = strtoupper(
        trim((string) $status)
    );

    if ($status === '') {
        return 'Belum Diisi';
    }

    if ($status === 'HONOR') {
        return 'HONORER';
    }

    return $status;
};

?>


<style>

/* =========================================================
   PAGE
========================================================= */

.guru-page {
    padding-bottom: 35px;
}


/* =========================================================
   HEADER HERO
========================================================= */

.guru-hero {
    position: relative;
    overflow: hidden;
    border-radius: 22px;
    padding: 30px 34px;
    margin-bottom: 25px;
    color: #fff;
    background: linear-gradient(
        135deg,
        #4e73df 0%,
        #224abe 100%
    );
    box-shadow:
        0 .5rem 1.5rem rgba(0,0,0,.12);
}

.guru-hero::before {
    content: "";
    position: absolute;
    width: 280px;
    height: 280px;
    right: -100px;
    top: -140px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
}

.guru-hero::after {
    content: "";
    position: absolute;
    width: 170px;
    height: 170px;
    right: 130px;
    bottom: -120px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}

.guru-hero-content {
    position: relative;
    z-index: 2;
}

.guru-hero-title {
    margin: 0 0 6px;
    font-size: 28px;
    font-weight: 700;
}

.guru-hero-subtitle {
    margin: 0;
    font-size: 13px;
    opacity: .9;
}

.guru-hero-icon {
    position: relative;
    z-index: 2;
    opacity: .18;
}


/* =========================================================
   STATISTICS
========================================================= */

.guru-stat-card {
    border: 0;
    border-radius: 17px;
    overflow: hidden;
    transition:
        transform .2s ease,
        box-shadow .2s ease;
}

.guru-stat-card:hover {
    transform: translateY(-4px);
    box-shadow:
        0 .8rem 2rem rgba(0,0,0,.13) !important;
}

.guru-stat-card .card-body {
    padding: 20px;
}

.guru-stat-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #858796;
}

.guru-stat-number {
    margin: 6px 0 3px;
    font-size: 30px;
    line-height: 1.1;
    font-weight: 700;
    color: #343a40;
}

.guru-stat-desc {
    font-size: 11px;
    color: #9a9aa0;
}

.guru-stat-icon {
    width: 58px;
    height: 58px;
    min-width: 58px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 22px;
}

.guru-bg-primary {
    background: #4e73df;
}

.guru-bg-success {
    background: #1cc88a;
}

.guru-bg-info {
    background: #36b9cc;
}

.guru-bg-warning {
    background: #f6c23e;
}

.guru-bg-danger {
    background: #e74a3b;
}


/* =========================================================
   ALERT
========================================================= */

.guru-alert {
    border: 0;
    border-radius: 12px;
}


/* =========================================================
   MAIN CARD
========================================================= */

.guru-card {
    border: 0;
    border-radius: 18px;
    overflow: hidden;
}

.guru-card-header {
    padding: 20px 22px;
    background: #fff;
    border-bottom: 1px solid #eaecf4;
}

.guru-card-title {
    margin: 0;
    font-size: 19px;
    font-weight: 700;
    color: #4e73df;
}

.guru-card-subtitle {
    margin-top: 3px;
    font-size: 12px;
    color: #858796;
}


/* =========================================================
   ACTION BUTTON
========================================================= */

.guru-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
}

.guru-actions .btn {
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
}

.guru-actions .btn i {
    margin-right: 3px;
}


/* =========================================================
   SEARCH
========================================================= */

.guru-search-area {
    padding: 18px 22px;
    background: #f8f9fc;
    border-bottom: 1px solid #eaecf4;
}

.guru-search-form {
    width: 100%;
}

.guru-search-box {
    position: relative;
    max-width: 430px;
}

.guru-search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    z-index: 2;
    transform: translateY(-50%);
    color: #858796;
    pointer-events: none;
}

.guru-search-input {
    height: 42px;
    padding-left: 40px;
    padding-right: 85px;
    border-radius: 10px !important;
    border: 1px solid #d1d3e2;
    font-size: 13px;
    background: #fff;
}

.guru-search-input:focus {
    border-color: #4e73df;
    box-shadow:
        0 0 0 .15rem rgba(78,115,223,.15);
}

.guru-search-button {
    position: absolute;
    right: 5px;
    top: 5px;
    height: 32px;
    border: 0;
    border-radius: 7px;
    padding: 0 12px;
    color: #fff;
    background: #4e73df;
    font-size: 12px;
    font-weight: 600;
}

.guru-search-button:hover {
    background: #224abe;
}

.guru-search-clear {
    position: absolute;
    right: 74px;
    top: 50%;
    transform: translateY(-50%);
    color: #858796;
    text-decoration: none;
    font-size: 12px;
}

.guru-search-clear:hover {
    color: #e74a3b;
    text-decoration: none;
}

.guru-search-info {
    margin-top: 8px;
    font-size: 12px;
    color: #858796;
}

.guru-search-keyword {
    font-weight: 700;
    color: #4e73df;
}


/* =========================================================
   TABLE
========================================================= */

.guru-table-wrapper {
    overflow-x: auto;
}

.guru-table {
    margin: 0;
    min-width: 900px;
}

.guru-table thead th {
    padding: 13px 14px;
    border-bottom-width: 1px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .35px;
    white-space: nowrap;
    vertical-align: middle;
    color: #5a5c69;
    background: #f8f9fc;
}

.guru-table tbody td {
    padding: 13px 14px;
    vertical-align: middle;
    font-size: 13px;
}

.guru-table tbody tr {
    transition:
        background .15s ease;
}

.guru-table tbody tr:hover {
    background: rgba(78,115,223,.045);
}

.guru-number {
    width: 55px;
    text-align: center;
    color: #858796;
    font-weight: 600;
}

.guru-nip {
    white-space: nowrap;
    font-family: monospace;
    font-size: 12px;
    color: #5a5c69;
}

.guru-name {
    font-weight: 700;
    color: #343a40;
}

.guru-email {
    max-width: 220px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.guru-phone {
    white-space: nowrap;
}


/* =========================================================
   STATUS
========================================================= */

.guru-status {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 78px;
    padding: 6px 10px;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 700;
    text-align: center;
}

.guru-status-pppk {
    color: #fff;
    background: #1cc88a;
}

.guru-status-pns {
    color: #fff;
    background: #36b9cc;
}

.guru-status-pppkpw {
    color: #fff;
    background: #f6c23e;
}

.guru-status-honorer {
    color: #fff;
    background: #e74a3b;
}

.guru-status-other {
    color: #fff;
    background: #858796;
}


/* =========================================================
   ACTION TABLE
========================================================= */

.guru-row-actions {
    white-space: nowrap;
}

.guru-row-actions .btn {
    width: 32px;
    height: 32px;
    padding: 0;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}


/* =========================================================
   EMPTY
========================================================= */

.guru-empty {
    padding: 70px 20px !important;
    text-align: center;
}

.guru-empty-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 15px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f3f9;
    color: #858796;
    font-size: 28px;
}

.guru-empty-title {
    font-weight: 700;
    color: #5a5c69;
}

.guru-empty-text {
    margin-top: 5px;
    font-size: 12px;
    color: #858796;
}


/* =========================================================
   PAGINATION
========================================================= */

.guru-pagination {
    padding: 18px 22px;
    border-top: 1px solid #eaecf4;
}

.guru-pagination .pagination {
    margin: 0;
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
    padding: 18px 22px;
    border-bottom: 0;
    color: #fff;
    background: linear-gradient(
        135deg,
        #4e73df,
        #224abe
    );
}

.guru-modal .modal-title {
    font-size: 17px;
    font-weight: 700;
}

.guru-modal .modal-body {
    padding: 24px;
}

.guru-modal .modal-footer {
    padding: 15px 24px;
    border-top: 1px solid #eaecf4;
}

.guru-modal .form-group {
    margin-bottom: 17px;
}

.guru-modal label {
    margin-bottom: 6px;
    font-size: 12px;
    font-weight: 700;
}

.guru-modal .form-control {
    min-height: 40px;
    border-radius: 8px;
    font-size: 13px;
}

.guru-modal select.form-control {
    height: 40px;
}

.guru-section-title {
    margin-bottom: 15px;
    font-size: 13px;
    font-weight: 700;
    color: #4e73df;
}

.guru-section-title i {
    margin-right: 6px;
}


/* =========================================================
   IMPORT MODAL
========================================================= */

.guru-import-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 15px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    background: #1cc88a;
    font-size: 28px;
}

.guru-import-description {
    font-size: 12px;
    color: #858796;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 991.98px) {

    .guru-hero {
        padding: 25px;
    }

    .guru-hero-icon {
        display: none;
    }

    .guru-card-header {
        padding: 18px;
    }

    .guru-actions {
        width: 100%;
        margin-top: 15px;
    }

    .guru-actions .btn {
        flex: 1;
    }

}


@media (max-width: 767.98px) {

    .guru-page {
        padding-left: 10px;
        padding-right: 10px;
    }

    .guru-hero {
        border-radius: 17px;
        padding: 23px 20px;
    }

    .guru-hero-title {
        font-size: 23px;
    }

    .guru-stat-card .card-body {
        padding: 17px;
    }

    .guru-stat-number {
        font-size: 27px;
    }

    .guru-stat-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        font-size: 19px;
    }

    .guru-search-area {
        padding: 15px;
    }

    .guru-search-box {
        max-width: none;
    }

    .guru-search-button {
        padding: 0 10px;
    }

    .guru-pagination {
        padding: 15px 10px;
        overflow-x: auto;
    }

    .guru-pagination .pagination {
        justify-content: flex-start !important;
        min-width: max-content;
    }

    .guru-modal .modal-body {
        padding: 18px;
    }

}


/* =========================================================
   DARK MODE
========================================================= */

body.dark-mode .guru-stat-card {
    background: #29283d;
    color: #fff;
}

body.dark-mode .guru-stat-title {
    color: #a6a6b5;
}

body.dark-mode .guru-stat-number {
    color: #fff;
}

body.dark-mode .guru-stat-desc {
    color: #8f8fa1;
}

body.dark-mode .guru-card {
    background: #29283d;
}

body.dark-mode .guru-card-header {
    background: #29283d;
    border-color: #414052;
}

body.dark-mode .guru-card-title {
    color: #6f8df5;
}

body.dark-mode .guru-card-subtitle {
    color: #9999aa;
}

body.dark-mode .guru-search-area {
    background: #242336;
    border-color: #414052;
}

body.dark-mode .guru-search-input {
    background: #302f45;
    border-color: #4a495d;
    color: #fff;
}

body.dark-mode .guru-search-input::placeholder {
    color: #858597;
}

body.dark-mode .guru-search-input:focus {
    border-color: #6f8df5;
    box-shadow:
        0 0 0 .15rem rgba(111,141,245,.15);
}

body.dark-mode .guru-search-info {
    color: #9999aa;
}

body.dark-mode .guru-search-keyword {
    color: #7d99ff;
}

body.dark-mode .guru-table {
    color: #eee;
}

body.dark-mode .guru-table thead th {
    background: #302f45;
    border-color: #464558;
    color: #e8e8ef;
}

body.dark-mode .guru-table tbody td {
    border-color: #414052;
}

body.dark-mode .guru-table tbody tr:hover {
    background: rgba(111,141,245,.07);
}

body.dark-mode .guru-name {
    color: #fff;
}

body.dark-mode .guru-nip {
    color: #aaaabd;
}

body.dark-mode .guru-email {
    color: #d2d2df;
}

body.dark-mode .guru-empty-icon {
    background: #343348;
    color: #88889b;
}

body.dark-mode .guru-empty-title {
    color: #ddd;
}

body.dark-mode .guru-empty-text {
    color: #9999aa;
}

body.dark-mode .guru-pagination {
    border-color: #414052;
}

body.dark-mode .guru-modal .modal-content {
    background: #29283d;
    color: #fff;
}

body.dark-mode .guru-modal .modal-body {
    background: #29283d;
}

body.dark-mode .guru-modal .modal-footer {
    border-color: #414052;
}

body.dark-mode .guru-modal .form-control {
    background: #343348;
    border-color: #4a495d;
    color: #fff;
}

body.dark-mode .guru-modal .form-control::placeholder {
    color: #8f8fa1;
}

body.dark-mode .guru-section-title {
    color: #7d99ff;
}

</style>


<div class="container-fluid guru-page">


<!-- =========================================================
     HERO
========================================================= -->

<div class="guru-hero">

    <div class="row align-items-center">

        <div class="col-lg-9 guru-hero-content">

            <h1 class="guru-hero-title">
                Data Guru
            </h1>

            <p class="guru-hero-subtitle">
                Kelola dan pantau data tenaga pendidik sekolah
            </p>

        </div>

        <div class="col-lg-3 text-right guru-hero-icon">

            <i class="fas fa-chalkboard-teacher fa-5x"></i>

        </div>

    </div>

</div>


<!-- =========================================================
     STATISTIK
========================================================= -->

<div class="row">


    <!-- TOTAL -->

    <div class="col-xl col-lg-4 col-md-6 mb-4">

        <div class="card guru-stat-card shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="guru-stat-title">
                            Total Guru
                        </div>

                        <div class="guru-stat-number">
                            <?= number_format($total_guru); ?>
                        </div>

                        <div class="guru-stat-desc">
                            Seluruh tenaga pendidik
                        </div>

                    </div>

                    <div class="guru-stat-icon guru-bg-primary">

                        <i class="fas fa-users"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- PPPK -->

    <div class="col-xl col-lg-4 col-md-6 mb-4">

        <div class="card guru-stat-card shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="guru-stat-title">
                            Guru PPPK
                        </div>

                        <div class="guru-stat-number text-success">
                            <?= number_format($guru_pppk); ?>
                        </div>

                        <div class="guru-stat-desc">
                            Pegawai Pemerintah
                        </div>

                    </div>

                    <div class="guru-stat-icon guru-bg-success">

                        <i class="fas fa-user-tie"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- PNS -->

    <div class="col-xl col-lg-4 col-md-6 mb-4">

        <div class="card guru-stat-card shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="guru-stat-title">
                            Guru PNS
                        </div>

                        <div class="guru-stat-number text-info">
                            <?= number_format($guru_pns); ?>
                        </div>

                        <div class="guru-stat-desc">
                            Pegawai Negeri Sipil
                        </div>

                    </div>

                    <div class="guru-stat-icon guru-bg-info">

                        <i class="fas fa-user-shield"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- PPPK PARUH WAKTU -->

    <div class="col-xl col-lg-4 col-md-6 mb-4">

        <div class="card guru-stat-card shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="guru-stat-title">
                            Guru PPPKPW
                        </div>

                        <div class="guru-stat-number text-warning">
                            <?= number_format($guru_pppkpw); ?>
                        </div>

                        <div class="guru-stat-desc">
                            PPPK Paruh Waktu
                        </div>

                    </div>

                    <div class="guru-stat-icon guru-bg-warning">

                        <i class="fas fa-user-clock"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- HONORER -->

    <div class="col-xl col-lg-4 col-md-6 mb-4">

        <div class="card guru-stat-card shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="guru-stat-title">
                            Honorer
                        </div>

                        <div class="guru-stat-number text-danger">
                            <?= number_format($guru_honorer); ?>
                        </div>

                        <div class="guru-stat-desc">
                            Guru Honorer
                        </div>

                    </div>

                    <div class="guru-stat-icon guru-bg-danger">

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

    <div class="alert alert-success guru-alert alert-dismissible fade show">

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

    <div class="alert alert-danger guru-alert alert-dismissible fade show">

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

<div class="card guru-card shadow-sm mb-4">


    <!-- =====================================================
         CARD HEADER
    ====================================================== -->

    <div class="guru-card-header">

        <div class="d-flex align-items-center justify-content-between flex-wrap">


            <div>

                <div class="guru-card-title">

                    <i class="fas fa-chalkboard-teacher mr-2"></i>

                    Daftar Guru

                </div>

                <div class="guru-card-subtitle">

                    Data tenaga pendidik sekolah

                </div>

            </div>


            <!-- =================================================
                 ACTION
            ================================================= -->

            <div class="guru-actions">


                <!-- TEMPLATE -->

                <a href="<?= base_url(
                    'assets/templates/templateimporguru.xlsx'
                ); ?>"
                   class="btn btn-outline-success btn-sm"
                   download
                   title="Download template Excel">

                    <i class="fas fa-download"></i>

                    Template

                </a>


                <!-- EXPORT -->

                <a href="<?= site_url(
                    'guru/export_excel'
                ); ?>"
                   class="btn btn-success btn-sm"
                   title="Export data guru">

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


    <!-- =====================================================
         SEARCH
    ====================================================== -->

    <div class="guru-search-area">

        <form method="get"
              action="<?= site_url('guru'); ?>"
              class="guru-search-form">


            <div class="guru-search-box">

                <i class="fas fa-search guru-search-icon"></i>


                <input type="text"
                       name="search"
                       class="form-control guru-search-input"
                       value="<?= html_escape($search); ?>"
                       placeholder="Cari nama atau NIP guru..."
                       autocomplete="off">


                <?php if ($search !== ''): ?>

                    <a href="<?= site_url('guru'); ?>"
                       class="guru-search-clear"
                       title="Hapus pencarian">

                        <i class="fas fa-times"></i>

                    </a>

                <?php endif; ?>


                <button type="submit"
                        class="guru-search-button">

                    <i class="fas fa-search mr-1"></i>

                    Cari

                </button>

            </div>


            <?php if ($search !== ''): ?>

                <div class="guru-search-info">

                    Menampilkan
                    <strong>
                        <?= number_format($jumlah_hasil); ?>
                    </strong>
                    guru pada halaman ini untuk pencarian:

                    <span class="guru-search-keyword">

                        "<?= html_escape($search); ?>"

                    </span>

                    <?php if (!empty($pagination)): ?>

                        <span>
                            — gunakan pagination untuk melihat hasil lainnya.
                        </span>

                    <?php endif; ?>

                </div>

            <?php else: ?>

                <div class="guru-search-info">

                    <i class="fas fa-info-circle mr-1"></i>

                    Cari berdasarkan nama atau NIP.

                </div>

            <?php endif; ?>


        </form>

    </div>


    <!-- =====================================================
         TABLE
    ====================================================== -->

    <div class="guru-table-wrapper">

        <table class="table table-bordered table-hover guru-table">


            <thead>

                <tr>

                    <th class="guru-number">
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

                    <th width="100"
                        class="text-center">

                        Aksi

                    </th>

                </tr>

            </thead>


            <tbody>


            <?php if (!empty($guru)): ?>


                <?php

                $no = $start + 1;

                foreach ($guru as $g):


                    $status =
                        isset($g->status_kepegawaian)
                        ? $g->status_kepegawaian
                        : '';


                    $status_class =
                        $statusClass($status);


                    $status_label =
                        $statusLabel($status);


                    $nama =
                        isset($g->nama)
                        ? $g->nama
                        : '';


                    $nip =
                        isset($g->nip)
                        ? $g->nip
                        : '';


                    $email =
                        isset($g->email)
                        ? $g->email
                        : '';


                    $telp =
                        isset($g->telp)
                        ? $g->telp
                        : '';

                ?>


                    <tr>


                        <!-- NO -->

                        <td class="guru-number">

                            <?= $no++; ?>

                        </td>


                        <!-- NIP -->

                        <td>

                            <?php if ($nip !== ''): ?>

                                <span class="guru-nip">

                                    <?= html_escape($nip); ?>

                                </span>

                            <?php else: ?>

                                <span class="text-muted">
                                    -
                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- NAMA -->

                        <td>

                            <span class="guru-name">

                                <?= html_escape($nama); ?>

                            </span>

                        </td>


                        <!-- STATUS -->

                        <td>

                            <span class="guru-status <?= $status_class; ?>">

                                <?= html_escape(
                                    $status_label
                                ); ?>

                            </span>

                        </td>


                        <!-- EMAIL -->

                        <td>

                            <?php if ($email !== ''): ?>

                                <div class="guru-email"
                                     title="<?= html_escape($email); ?>">

                                    <?= html_escape($email); ?>

                                </div>

                            <?php else: ?>

                                <span class="text-muted">
                                    -
                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- TELEPON -->

                        <td class="guru-phone">

                            <?php if ($telp !== ''): ?>

                                <?= html_escape($telp); ?>

                            <?php else: ?>

                                <span class="text-muted">
                                    -
                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- AKSI -->

                        <td class="guru-row-actions text-center">


                            <a href="<?= site_url(
                                'guru/edit/' .
                                (int) $g->id
                            ); ?>"
                               class="btn btn-warning btn-sm"
                               title="Edit guru">

                                <i class="fas fa-edit"></i>

                            </a>


                            <a href="<?= site_url(
                                'guru/delete/' .
                                (int) $g->id
                            ); ?>"
                               class="btn btn-danger btn-sm"
                               title="Hapus guru"
                               onclick="return confirm(
                                   'Yakin ingin menghapus data guru ini?'
                               );">

                                <i class="fas fa-trash"></i>

                            </a>


                        </td>


                    </tr>


                <?php endforeach; ?>


            <?php else: ?>


                <tr>

                    <td colspan="7"
                        class="guru-empty">


                        <?php if ($search !== ''): ?>


                            <div class="guru-empty-icon">

                                <i class="fas fa-search"></i>

                            </div>


                            <div class="guru-empty-title">

                                Guru tidak ditemukan

                            </div>


                            <div class="guru-empty-text">

                                Tidak ada guru yang sesuai dengan pencarian

                                <strong>
                                    "<?= html_escape($search); ?>"
                                </strong>.

                            </div>


                            <div class="mt-3">

                                <a href="<?= site_url('guru'); ?>"
                                   class="btn btn-secondary btn-sm">

                                    <i class="fas fa-times mr-1"></i>

                                    Hapus Pencarian

                                </a>

                            </div>


                        <?php else: ?>


                            <div class="guru-empty-icon">

                                <i class="fas fa-user-slash"></i>

                            </div>


                            <div class="guru-empty-title">

                                Data guru belum tersedia

                            </div>


                            <div class="guru-empty-text">

                                Silakan tambahkan atau import data guru.

                            </div>


                        <?php endif; ?>


                    </td>

                </tr>


            <?php endif; ?>


            </tbody>

        </table>

    </div>


    <!-- =====================================================
         PAGINATION
    ====================================================== -->

    <?php if (!empty($pagination) || (isset($total) && $total > 0)): ?>

        <div class="d-flex justify-content-between align-items-center flex-wrap mt-3 px-2">
            <div class="text-muted small mb-2 mb-md-0">
                Menampilkan <strong><?= count($guru); ?></strong> dari total <strong><?= number_format($total ?? count($guru)); ?></strong> guru
            </div>
            <div class="guru-pagination">
                <?= $pagination; ?>
            </div>
        </div>

    <?php endif; ?>


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
                  action="<?= site_url('guru/add'); ?>">


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


                    <!-- =================================================
                         IDENTITAS
                    ================================================== -->

                    <div class="guru-section-title">

                        <i class="fas fa-id-card"></i>

                        Identitas Guru

                    </div>


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

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>

                                <input type="text"
                                       name="nama"
                                       class="form-control"
                                       placeholder="Nama lengkap guru"
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


                    <!-- =================================================
                         KEPEGAWAIAN
                    ================================================== -->

                    <div class="guru-section-title">

                        <i class="fas fa-user-tie"></i>

                        Kepegawaian

                    </div>


                    <div class="row">


                        <!-- STATUS -->

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


                    <!-- =================================================
                         KONTAK
                    ================================================== -->

                    <div class="guru-section-title">

                        <i class="fas fa-address-book"></i>

                        Kontak

                    </div>


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
     MODAL IMPORT
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
                  action="<?= site_url(
                      'guru/import_excel'
                  ); ?>"
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


                        <div class="guru-import-icon">

                            <i class="fas fa-file-excel"></i>

                        </div>


                        <h5 class="font-weight-bold mb-2">

                            Import Data Guru

                        </h5>


                        <p class="guru-import-description mb-0">

                            Upload file Excel untuk menambahkan
                            data guru ke dalam sistem.

                        </p>


                    </div>


                    <!-- FILE -->

                    <div class="form-group">


                        <label>

                            File Excel

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <input type="file"
                               name="file"
                               class="form-control"
                               accept=".xls,.xlsx"
                               required>


                        <small class="form-text text-muted">

                            Format yang diperbolehkan:

                            <strong>
                                .xls
                            </strong>
                            atau
                            <strong>
                                .xlsx
                            </strong>

                        </small>


                    </div>


                    <!-- INFO -->

                    <div class="alert alert-info small">


                        <i class="fas fa-info-circle mr-1"></i>


                        Pastikan baris pertama Excel
                        berisi nama kolom yang sesuai
                        dengan template.


                    </div>


                    <!-- TEMPLATE -->

                    <div class="text-center">


                        <a href="<?= base_url(
                            'assets/templates/templateimporguru.xlsx'
                        ); ?>"
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
     MODAL SCRIPT
========================================================= -->

<script>

/*
|--------------------------------------------------------------------------
| OPEN MODAL
|--------------------------------------------------------------------------
*/

function openGuruModal(id)
{
    var modal =
        document.getElementById(id);

    if (!modal) {
        return;
    }


    /*
     * Bootstrap 4
     */

    if (
        typeof jQuery !== 'undefined' &&
        typeof jQuery.fn.modal === 'function'
    ) {

        jQuery('#' + id).modal('show');

        return;
    }


    /*
     * Bootstrap 5
     */

    if (
        typeof bootstrap !== 'undefined' &&
        bootstrap.Modal
    ) {

        var instance =
            bootstrap.Modal.getOrCreateInstance(
                modal
            );

        instance.show();

        return;
    }


    /*
     * Fallback
     */

    modal.style.display = 'block';

    modal.classList.add('show');

    modal.setAttribute(
        'aria-hidden',
        'false'
    );

    document.body.classList.add(
        'modal-open'
    );
}


/*
|--------------------------------------------------------------------------
| CLOSE MODAL
|--------------------------------------------------------------------------
*/

function closeGuruModal(id)
{
    var modal =
        document.getElementById(id);

    if (!modal) {
        return;
    }


    /*
     * Bootstrap 4
     */

    if (
        typeof jQuery !== 'undefined' &&
        typeof jQuery.fn.modal === 'function'
    ) {

        jQuery('#' + id).modal('hide');

        return;
    }


    /*
     * Bootstrap 5
     */

    if (
        typeof bootstrap !== 'undefined' &&
        bootstrap.Modal
    ) {

        var instance =
            bootstrap.Modal.getInstance(
                modal
            );

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

    modal.setAttribute(
        'aria-hidden',
        'true'
    );

    document.body.classList.remove(
        'modal-open'
    );
}


/*
|--------------------------------------------------------------------------
| ENTER SEARCH
|--------------------------------------------------------------------------
|
| Tidak menggunakan AJAX / filter JavaScript.
| Form dikirim ke server sehingga pencarian
| dilakukan terhadap seluruh database.
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    function ()
    {

        var searchInput =
            document.querySelector(
                '.guru-search-input'
            );


        if (searchInput) {

            searchInput.addEventListener(
                'keydown',
                function (event)
                {

                    if (
                        event.key === 'Enter'
                    ) {

                        event.preventDefault();

                        this.form.submit();

                    }

                }
            );

        }

    }
);

</script>