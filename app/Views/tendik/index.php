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

$tendik = isset($tendik) && is_array($tendik)
    ? $tendik
    : [];

$total_tendik = isset($total_tendik)
    ? (int) $total_tendik
    : 0;

$tendik_pppk = isset($tendik_pppk)
    ? (int) $tendik_pppk
    : 0;

$tendik_pns = isset($tendik_pns)
    ? (int) $tendik_pns
    : 0;

$tendik_honorer = isset($tendik_honorer)
    ? (int) $tendik_honorer
    : 0;

$start = isset($start)
    ? (int) $start
    : 0;


/*
|--------------------------------------------------------------------------
| JUMLAH HASIL PENCARIAN
|--------------------------------------------------------------------------
*/

$jumlah_hasil = count($tendik);


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
            return 'tendik-status-pns';

        case 'PPPK':
            return 'tendik-status-pppk';

        case 'HONOR':
        case 'HONORER':
            return 'tendik-status-honorer';

        default:
            return 'tendik-status-other';
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

.tendik-page {
    padding-bottom: 35px;
}


/* =========================================================
   HEADER HERO
========================================================= */

.tendik-hero {
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

.tendik-hero::before {
    content: "";
    position: absolute;
    width: 280px;
    height: 280px;
    right: -100px;
    top: -140px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
}

.tendik-hero::after {
    content: "";
    position: absolute;
    width: 170px;
    height: 170px;
    right: 130px;
    bottom: -120px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}

.tendik-hero-content {
    position: relative;
    z-index: 2;
}

.tendik-hero-title {
    margin: 0 0 6px;
    font-size: 28px;
    font-weight: 700;
}

.tendik-hero-subtitle {
    margin: 0;
    font-size: 13px;
    opacity: .9;
}

.tendik-hero-icon {
    position: relative;
    z-index: 2;
    opacity: .18;
}


/* =========================================================
   STATISTICS
========================================================= */

.tendik-stat-card {
    border: 0;
    border-radius: 17px;
    overflow: hidden;
    transition:
        transform .2s ease,
        box-shadow .2s ease;
}

.tendik-stat-card:hover {
    transform: translateY(-4px);
    box-shadow:
        0 .8rem 2rem rgba(0,0,0,.13) !important;
}

.tendik-stat-card .card-body {
    padding: 20px;
}

.tendik-stat-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #858796;
}

.tendik-stat-number {
    margin: 6px 0 3px;
    font-size: 30px;
    line-height: 1.1;
    font-weight: 700;
    color: #343a40;
}

.tendik-stat-desc {
    font-size: 11px;
    color: #9a9aa0;
}

.tendik-stat-icon {
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

.tendik-bg-primary {
    background: #4e73df;
}

.tendik-bg-success {
    background: #1cc88a;
}

.tendik-bg-info {
    background: #36b9cc;
}

.tendik-bg-warning {
    background: #f6c23e;
}

.tendik-bg-danger {
    background: #e74a3b;
}


/* =========================================================
   ALERT
========================================================= */

.tendik-alert {
    border: 0;
    border-radius: 12px;
}


/* =========================================================
   MAIN CARD
========================================================= */

.tendik-card {
    border: 0;
    border-radius: 18px;
    overflow: hidden;
}

.tendik-card-header {
    padding: 20px 22px;
    background: #fff;
    border-bottom: 1px solid #eaecf4;
}

.tendik-card-title {
    margin: 0;
    font-size: 19px;
    font-weight: 700;
    color: #4e73df;
}

.tendik-card-subtitle {
    margin-top: 3px;
    font-size: 12px;
    color: #858796;
}


/* =========================================================
   ACTION BUTTON
========================================================= */

.tendik-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
}

.tendik-actions .btn {
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
}

.tendik-actions .btn i {
    margin-right: 3px;
}


/* =========================================================
   SEARCH
========================================================= */

.tendik-search-area {
    padding: 18px 22px;
    background: #f8f9fc;
    border-bottom: 1px solid #eaecf4;
}

.tendik-search-form {
    width: 100%;
}

.tendik-search-box {
    position: relative;
    max-width: 430px;
}

.tendik-search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    z-index: 2;
    transform: translateY(-50%);
    color: #858796;
    pointer-events: none;
}

.tendik-search-input {
    height: 42px;
    padding-left: 40px;
    padding-right: 85px;
    border-radius: 10px !important;
    border: 1px solid #d1d3e2;
    font-size: 13px;
    background: #fff;
}

.tendik-search-input:focus {
    border-color: #4e73df;
    box-shadow:
        0 0 0 .15rem rgba(78,115,223,.15);
}

.tendik-search-button {
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

.tendik-search-button:hover {
    background: #224abe;
}

.tendik-search-clear {
    position: absolute;
    right: 74px;
    top: 50%;
    transform: translateY(-50%);
    color: #858796;
    text-decoration: none;
    font-size: 12px;
}

.tendik-search-clear:hover {
    color: #e74a3b;
    text-decoration: none;
}

.tendik-search-info {
    margin-top: 8px;
    font-size: 12px;
    color: #858796;
}

.tendik-search-keyword {
    font-weight: 700;
    color: #4e73df;
}


/* =========================================================
   TABLE
========================================================= */

.tendik-table-wrapper {
    overflow-x: auto;
}

.tendik-table {
    margin: 0;
    min-width: 1050px;
}

.tendik-table thead th {
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

.tendik-table tbody td {
    padding: 13px 14px;
    vertical-align: middle;
    font-size: 13px;
}

.tendik-table tbody tr {
    transition:
        background .15s ease;
}

.tendik-table tbody tr:hover {
    background: rgba(78,115,223,.045);
}

.tendik-number {
    width: 55px;
    text-align: center;
    color: #858796;
    font-weight: 600;
}

.tendik-nuptk,
.tendik-nip {
    white-space: nowrap;
    font-family: monospace;
    font-size: 12px;
    color: #5a5c69;
}

.tendik-name {
    font-weight: 700;
    color: #343a40;
}

.tendik-email {
    max-width: 220px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.tendik-phone {
    white-space: nowrap;
}

.tendik-jenis {
    white-space: nowrap;
}


/* =========================================================
   STATUS
========================================================= */

.tendik-status {
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

.tendik-status-pppk {
    color: #fff;
    background: #1cc88a;
}

.tendik-status-pns {
    color: #fff;
    background: #36b9cc;
}

.tendik-status-honorer {
    color: #fff;
    background: #e74a3b;
}

.tendik-status-other {
    color: #fff;
    background: #858796;
}


/* =========================================================
   ACTION TABLE
========================================================= */

.tendik-row-actions {
    white-space: nowrap;
}

.tendik-row-actions .btn {
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

.tendik-empty {
    padding: 70px 20px !important;
    text-align: center;
}

.tendik-empty-icon {
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

.tendik-empty-title {
    font-weight: 700;
    color: #5a5c69;
}

.tendik-empty-text {
    margin-top: 5px;
    font-size: 12px;
    color: #858796;
}


/* =========================================================
   PAGINATION
========================================================= */

.tendik-pagination {
    padding: 18px 22px;
    border-top: 1px solid #eaecf4;
}

.tendik-pagination .pagination {
    margin: 0;
}


/* =========================================================
   MODAL
========================================================= */

.tendik-modal .modal-content {
    border: 0;
    border-radius: 18px;
    overflow: hidden;
}

.tendik-modal .modal-header {
    padding: 18px 22px;
    border-bottom: 0;
    color: #fff;
    background: linear-gradient(
        135deg,
        #4e73df,
        #224abe
    );
}

.tendik-modal .modal-title {
    font-size: 17px;
    font-weight: 700;
}

.tendik-modal .modal-body {
    padding: 24px;
}

.tendik-modal .modal-footer {
    padding: 15px 24px;
    border-top: 1px solid #eaecf4;
}

.tendik-modal .form-group {
    margin-bottom: 17px;
}

.tendik-modal label {
    margin-bottom: 6px;
    font-size: 12px;
    font-weight: 700;
}

.tendik-modal .form-control {
    min-height: 40px;
    border-radius: 8px;
    font-size: 13px;
}

.tendik-modal select.form-control {
    height: 40px;
}

.tendik-section-title {
    margin-bottom: 15px;
    font-size: 13px;
    font-weight: 700;
    color: #4e73df;
}

.tendik-section-title i {
    margin-right: 6px;
}


/* =========================================================
   IMPORT MODAL
========================================================= */

.tendik-import-icon {
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

.tendik-import-description {
    font-size: 12px;
    color: #858796;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 991.98px) {

    .tendik-hero {
        padding: 25px;
    }

    .tendik-hero-icon {
        display: none;
    }

    .tendik-card-header {
        padding: 18px;
    }

    .tendik-actions {
        width: 100%;
        margin-top: 15px;
    }

    .tendik-actions .btn {
        flex: 1;
    }

}


@media (max-width: 767.98px) {

    .tendik-page {
        padding-left: 10px;
        padding-right: 10px;
    }

    .tendik-hero {
        border-radius: 17px;
        padding: 23px 20px;
    }

    .tendik-hero-title {
        font-size: 23px;
    }

    .tendik-stat-card .card-body {
        padding: 17px;
    }

    .tendik-stat-number {
        font-size: 27px;
    }

    .tendik-stat-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        font-size: 19px;
    }

    .tendik-search-area {
        padding: 15px;
    }

    .tendik-search-box {
        max-width: none;
    }

    .tendik-search-button {
        padding: 0 10px;
    }

    .tendik-pagination {
        padding: 15px 10px;
        overflow-x: auto;
    }

    .tendik-pagination .pagination {
        justify-content: flex-start !important;
        min-width: max-content;
    }

    .tendik-modal .modal-body {
        padding: 18px;
    }

}


/* =========================================================
   DARK MODE
========================================================= */

body.dark-mode .tendik-stat-card {
    background: #29283d;
    color: #fff;
}

body.dark-mode .tendik-stat-title {
    color: #a6a6b5;
}

body.dark-mode .tendik-stat-number {
    color: #fff;
}

body.dark-mode .tendik-stat-desc {
    color: #8f8fa1;
}

body.dark-mode .tendik-card {
    background: #29283d;
}

body.dark-mode .tendik-card-header {
    background: #29283d;
    border-color: #414052;
}

body.dark-mode .tendik-card-title {
    color: #6f8df5;
}

body.dark-mode .tendik-card-subtitle {
    color: #9999aa;
}

body.dark-mode .tendik-search-area {
    background: #242336;
    border-color: #414052;
}

body.dark-mode .tendik-search-input {
    background: #302f45;
    border-color: #4a495d;
    color: #fff;
}

body.dark-mode .tendik-search-input::placeholder {
    color: #858597;
}

body.dark-mode .tendik-search-input:focus {
    border-color: #6f8df5;
    box-shadow:
        0 0 0 .15rem rgba(111,141,245,.15);
}

body.dark-mode .tendik-search-info {
    color: #9999aa;
}

body.dark-mode .tendik-search-keyword {
    color: #7d99ff;
}

body.dark-mode .tendik-table {
    color: #eee;
}

body.dark-mode .tendik-table thead th {
    background: #302f45;
    border-color: #464558;
    color: #e8e8ef;
}

body.dark-mode .tendik-table tbody td {
    border-color: #414052;
}

body.dark-mode .tendik-table tbody tr:hover {
    background: rgba(111,141,245,.07);
}

body.dark-mode .tendik-name {
    color: #fff;
}

body.dark-mode .tendik-nuptk,
body.dark-mode .tendik-nip {
    color: #aaaabd;
}

body.dark-mode .tendik-email {
    color: #d2d2df;
}

body.dark-mode .tendik-empty-icon {
    background: #343348;
    color: #88889b;
}

body.dark-mode .tendik-empty-title {
    color: #ddd;
}

body.dark-mode .tendik-empty-text {
    color: #9999aa;
}

body.dark-mode .tendik-pagination {
    border-color: #414052;
}

body.dark-mode .tendik-modal .modal-content {
    background: #29283d;
    color: #fff;
}

body.dark-mode .tendik-modal .modal-body {
    background: #29283d;
}

body.dark-mode .tendik-modal .modal-footer {
    border-color: #414052;
}

body.dark-mode .tendik-modal .form-control {
    background: #343348;
    border-color: #4a495d;
    color: #fff;
}

body.dark-mode .tendik-modal .form-control::placeholder {
    color: #8f8fa1;
}

body.dark-mode .tendik-section-title {
    color: #7d99ff;
}

</style>


<div class="container-fluid tendik-page">


<!-- =========================================================
     HERO
========================================================= -->

<div class="tendik-hero">

    <div class="row align-items-center">

        <div class="col-lg-9 tendik-hero-content">

            <h1 class="tendik-hero-title">
                Data Tendik
            </h1>

            <p class="tendik-hero-subtitle">
                Kelola dan pantau data tenaga kependidikan sekolah
            </p>

        </div>

        <div class="col-lg-3 text-right tendik-hero-icon">

            <i class="fas fa-user-cog fa-5x"></i>

        </div>

    </div>

</div>


<!-- =========================================================
     STATISTIK
========================================================= -->

<div class="row">


    <!-- TOTAL -->

    <div class="col-xl col-lg-4 col-md-6 mb-4">

        <div class="card tendik-stat-card shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="tendik-stat-title">
                            Total Tendik
                        </div>

                        <div class="tendik-stat-number">
                            <?= number_format($total_tendik); ?>
                        </div>

                        <div class="tendik-stat-desc">
                            Seluruh tenaga kependidikan
                        </div>

                    </div>

                    <div class="tendik-stat-icon tendik-bg-primary">

                        <i class="fas fa-users"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- PPPK -->

    <div class="col-xl col-lg-4 col-md-6 mb-4">

        <div class="card tendik-stat-card shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="tendik-stat-title">
                            Tendik PPPK Paruh Waktu
                        </div>

                        <div class="tendik-stat-number text-success">
                            <?= number_format($tendik_pppk); ?>
                        </div>

                        <div class="tendik-stat-desc">
                            Pegawai Pemerintah
                        </div>

                    </div>

                    <div class="tendik-stat-icon tendik-bg-success">

                        <i class="fas fa-user-tie"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- PNS -->

    <div class="col-xl col-lg-4 col-md-6 mb-4">

        <div class="card tendik-stat-card shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="tendik-stat-title">
                            Tendik PNS
                        </div>

                        <div class="tendik-stat-number text-info">
                            <?= number_format($tendik_pns); ?>
                        </div>

                        <div class="tendik-stat-desc">
                            Pegawai Negeri Sipil
                        </div>

                    </div>

                    <div class="tendik-stat-icon tendik-bg-info">

                        <i class="fas fa-user-shield"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- HONORER -->

    <div class="col-xl col-lg-4 col-md-6 mb-4">

        <div class="card tendik-stat-card shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="tendik-stat-title">
                            Honorer
                        </div>

                        <div class="tendik-stat-number text-danger">
                            <?= number_format($tendik_honorer); ?>
                        </div>

                        <div class="tendik-stat-desc">
                            Tendik Honorer
                        </div>

                    </div>

                    <div class="tendik-stat-icon tendik-bg-danger">

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

    <div class="alert alert-success tendik-alert alert-dismissible fade show">

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

    <div class="alert alert-danger tendik-alert alert-dismissible fade show">

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
     DATA TENDIK
========================================================= -->

<div class="card tendik-card shadow-sm mb-4">


    <!-- =====================================================
         CARD HEADER
    ====================================================== -->

    <div class="tendik-card-header">

        <div class="d-flex align-items-center justify-content-between flex-wrap">


            <div>

                <div class="tendik-card-title">

                    <i class="fas fa-user-cog mr-2"></i>

                    Daftar Tendik

                </div>

                <div class="tendik-card-subtitle">

                    Data tenaga kependidikan sekolah

                </div>

            </div>


            <!-- =================================================
                 ACTION
            ================================================= -->

            <div class="tendik-actions">


                <!-- TEMPLATE -->

                <a href="<?= site_url(
                    'tendik/download_template'
                ); ?>"
                   class="btn btn-outline-success btn-sm"
                   title="Download template Excel">

                    <i class="fas fa-download"></i>

                    Template

                </a>


                <!-- EXPORT -->

                <a href="<?= site_url(
                    'tendik/export_excel'
                ); ?>"
                   class="btn btn-success btn-sm"
                   title="Export data Tendik">

                    <i class="fas fa-file-excel"></i>

                    Export

                </a>


                <!-- IMPORT -->

                <button type="button"
                        class="btn btn-primary btn-sm"
                        onclick="openTendikModal('importModal')">

                    <i class="fas fa-upload"></i>

                    Import

                </button>


                <!-- TAMBAH -->

                <button type="button"
                        class="btn btn-info btn-sm"
                        onclick="openTendikModal('addModal')">

                    <i class="fas fa-plus"></i>

                    Tambah

                </button>


            </div>

        </div>

    </div>


    <!-- =====================================================
         SEARCH
    ====================================================== -->

    <div class="tendik-search-area">

        <form method="get"
              action="<?= site_url('tendik'); ?>"
              class="tendik-search-form">


            <div class="tendik-search-box">

                <i class="fas fa-search tendik-search-icon"></i>


                <input type="text"
                       name="search"
                       class="form-control tendik-search-input"
                       value="<?= html_escape($search); ?>"
                       placeholder="Cari nama, NUPTK, NIP, atau jenis PTK..."
                       autocomplete="off">


                <?php if ($search !== ''): ?>

                    <a href="<?= site_url('tendik'); ?>"
                       class="tendik-search-clear"
                       title="Hapus pencarian">

                        <i class="fas fa-times"></i>

                    </a>

                <?php endif; ?>


                <button type="submit"
                        class="tendik-search-button">

                    <i class="fas fa-search mr-1"></i>

                    Cari

                </button>

            </div>


            <?php if ($search !== ''): ?>

                <div class="tendik-search-info">

                    Menampilkan
                    <strong>
                        <?= number_format($jumlah_hasil); ?>
                    </strong>
                    Tendik pada halaman ini untuk pencarian:

                    <span class="tendik-search-keyword">

                        "<?= html_escape($search); ?>"

                    </span>

                    <?php if (!empty($pagination)): ?>

                        <span>
                            — gunakan pagination untuk melihat hasil lainnya.
                        </span>

                    <?php endif; ?>

                </div>

            <?php else: ?>

                <div class="tendik-search-info">

                    <i class="fas fa-info-circle mr-1"></i>

                    Cari berdasarkan nama, NUPTK, NIP, jenis PTK,
                    atau status kepegawaian.

                </div>

            <?php endif; ?>


        </form>

    </div>


    <!-- =====================================================
         TABLE
    ====================================================== -->

    <div class="tendik-table-wrapper">

        <table class="table table-bordered table-hover tendik-table">


            <thead>

                <tr>

                    <th class="tendik-number">
                        No
                    </th>

                    <th>
                        NUPTK
                    </th>

                    <th>
                        NIP
                    </th>

                    <th>
                        Nama
                    </th>

                    <th>
                        Jenis PTK
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Email
                    </th>

                    <th>
                        HP
                    </th>

                    <th width="100"
                        class="text-center">

                        Aksi

                    </th>

                </tr>

            </thead>


            <tbody>


            <?php if (!empty($tendik)): ?>


                <?php

                $no = $start + 1;

                foreach ($tendik as $t):


                    $nama =
                        isset($t->nama)
                        ? $t->nama
                        : '';


                    $nuptk =
                        isset($t->nuptk)
                        ? $t->nuptk
                        : '';


                    $nip =
                        isset($t->nip)
                        ? $t->nip
                        : '';


                    $jenis_ptk =
                        isset($t->jenis_ptk)
                        ? $t->jenis_ptk
                        : '';


                    $status =
                        isset($t->status_kepegawaian)
                        ? $t->status_kepegawaian
                        : '';


                    $email =
                        isset($t->email)
                        ? $t->email
                        : '';


                    $hp =
                        isset($t->hp)
                        ? $t->hp
                        : '';


                    $status_class =
                        $statusClass($status);


                    $status_label =
                        $statusLabel($status);

                ?>


                    <tr>


                        <!-- NO -->

                        <td class="tendik-number">

                            <?= $no++; ?>

                        </td>


                        <!-- NUPTK -->

                        <td>

                            <?php if ($nuptk !== ''): ?>

                                <span class="tendik-nuptk">

                                    <?= html_escape($nuptk); ?>

                                </span>

                            <?php else: ?>

                                <span class="text-muted">
                                    -
                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- NIP -->

                        <td>

                            <?php if ($nip !== ''): ?>

                                <span class="tendik-nip">

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

                            <span class="tendik-name">

                                <?= html_escape($nama); ?>

                            </span>

                        </td>


                        <!-- JENIS PTK -->

                        <td>

                            <?php if ($jenis_ptk !== ''): ?>

                                <span class="tendik-jenis">

                                    <?= html_escape($jenis_ptk); ?>

                                </span>

                            <?php else: ?>

                                <span class="text-muted">
                                    -
                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- STATUS -->

                        <td>

                            <span class="tendik-status <?= $status_class; ?>">

                                <?= html_escape(
                                    $status_label
                                ); ?>

                            </span>

                        </td>


                        <!-- EMAIL -->

                        <td>

                            <?php if ($email !== ''): ?>

                                <div class="tendik-email"
                                     title="<?= html_escape($email); ?>">

                                    <?= html_escape($email); ?>

                                </div>

                            <?php else: ?>

                                <span class="text-muted">
                                    -
                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- HP -->

                        <td class="tendik-phone">

                            <?php if ($hp !== ''): ?>

                                <?= html_escape($hp); ?>

                            <?php else: ?>

                                <span class="text-muted">
                                    -
                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- AKSI -->

                        <td class="tendik-row-actions text-center">


                            <a href="<?= site_url(
                                'tendik/edit/' .
                                (int) $t->id
                            ); ?>"
                               class="btn btn-warning btn-sm"
                               title="Edit Tendik">

                                <i class="fas fa-edit"></i>

                            </a>


                            <a href="<?= site_url(
                                'tendik/delete/' .
                                (int) $t->id
                            ); ?>"
                               class="btn btn-danger btn-sm"
                               title="Hapus Tendik"
                               onclick="return confirm(
                                   'Yakin ingin menghapus data Tendik ini?'
                               );">

                                <i class="fas fa-trash"></i>

                            </a>


                        </td>


                    </tr>


                <?php endforeach; ?>


            <?php else: ?>


                <tr>

                    <td colspan="9"
                        class="tendik-empty">


                        <?php if ($search !== ''): ?>


                            <div class="tendik-empty-icon">

                                <i class="fas fa-search"></i>

                            </div>


                            <div class="tendik-empty-title">

                                Tendik tidak ditemukan

                            </div>


                            <div class="tendik-empty-text">

                                Tidak ada Tendik yang sesuai dengan pencarian

                                <strong>
                                    "<?= html_escape($search); ?>"
                                </strong>.

                            </div>


                            <div class="mt-3">

                                <a href="<?= site_url('tendik'); ?>"
                                   class="btn btn-secondary btn-sm">

                                    <i class="fas fa-times mr-1"></i>

                                    Hapus Pencarian

                                </a>

                            </div>


                        <?php else: ?>


                            <div class="tendik-empty-icon">

                                <i class="fas fa-user-slash"></i>

                            </div>


                            <div class="tendik-empty-title">

                                Data Tendik belum tersedia

                            </div>


                            <div class="tendik-empty-text">

                                Silakan tambahkan atau import data Tendik.

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
                Menampilkan <strong><?= count($tendik); ?></strong> dari total <strong><?= number_format($total ?? count($tendik)); ?></strong> tendik
            </div>
            <div class="tendik-pagination">
                <?= $pagination; ?>
            </div>
        </div>

    <?php endif; ?>


</div>


</div>



<!-- =========================================================
     MODAL TAMBAH TENDIK
========================================================= -->

<div class="modal fade tendik-modal"
     id="addModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="addModalLabel"
     aria-hidden="true">


    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">


        <div class="modal-content">


            <form method="post"
                  action="<?= site_url('tendik/add'); ?>">


                <!-- HEADER -->

                <div class="modal-header">

                    <h5 class="modal-title"
                        id="addModalLabel">

                        <i class="fas fa-user-plus mr-2"></i>

                        Tambah Tendik

                    </h5>


                    <button type="button"
                            class="close text-white"
                            onclick="closeTendikModal('addModal')">

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

                    <div class="tendik-section-title">

                        <i class="fas fa-id-card"></i>

                        Identitas Tendik

                    </div>


                    <div class="row">


                        <!-- NUPTK -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    NUPTK

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>

                                <input type="text"
                                       name="nuptk"
                                       class="form-control"
                                       placeholder="Masukkan NUPTK"
                                       required>

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
                                       placeholder="Nama lengkap Tendik"
                                       required>

                            </div>

                        </div>


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


                        <!-- JK -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Jenis Kelamin
                                </label>

                                <select name="jk"
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

                    <div class="tendik-section-title">

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

                                    <option value="HONORER">
                                        HONORER
                                    </option>

                                </select>

                            </div>

                        </div>


                        <!-- JENIS PTK -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Jenis PTK
                                </label>

                                <input type="text"
                                       name="jenis_ptk"
                                       class="form-control"
                                       placeholder="Contoh: Tenaga Administrasi">

                            </div>

                        </div>


                    </div>


                    <hr>


                    <!-- =================================================
                         KONTAK
                    ================================================== -->

                    <div class="tendik-section-title">

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


                        <!-- HP -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    No. HP
                                </label>

                                <input type="text"
                                       name="hp"
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
                            onclick="closeTendikModal('addModal')">

                        <i class="fas fa-times mr-1"></i>

                        Batal

                    </button>


                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save mr-1"></i>

                        Simpan Tendik

                    </button>


                </div>


            </form>

        </div>

    </div>

</div>



<!-- =========================================================
     MODAL IMPORT
========================================================= -->

<div class="modal fade tendik-modal"
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
                      'tendik/import_excel'
                  ); ?>"
                  enctype="multipart/form-data">


                <!-- HEADER -->

                <div class="modal-header">

                    <h5 class="modal-title"
                        id="importModalLabel">

                        <i class="fas fa-file-import mr-2"></i>

                        Import Data Tendik

                    </h5>


                    <button type="button"
                            class="close text-white"
                            onclick="closeTendikModal('importModal')">

                        <span>&times;</span>

                    </button>

                </div>


                <!-- BODY -->

                <div class="modal-body">


                    <input type="hidden"
                           name="<?= $this->security->get_csrf_token_name(); ?>"
                           value="<?= $this->security->get_csrf_hash(); ?>">


                    <div class="text-center mb-4">


                        <div class="tendik-import-icon">

                            <i class="fas fa-file-excel"></i>

                        </div>


                        <h5 class="font-weight-bold mb-2">

                            Import Data Tendik

                        </h5>


                        <p class="tendik-import-description mb-0">

                            Upload file Excel untuk menambahkan
                            atau memperbarui data Tendik.

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

                        <div class="mb-1">

                            <i class="fas fa-info-circle mr-1"></i>

                            <strong>Aturan Import</strong>

                        </div>

                        <div>
                            NUPTK digunakan sebagai kunci data.
                        </div>

                        <div>
                            NUPTK baru akan ditambahkan.
                        </div>

                        <div>
                            NUPTK yang sudah ada akan diperbarui
                            jika terdapat perubahan.
                        </div>

                        <div>
                            Jika tidak ada perubahan,
                            data tetap seperti sebelumnya.
                        </div>

                    </div>


                    <!-- TEMPLATE -->

                    <div class="text-center">


                        <a href="<?= site_url(
                            'tendik/download_template'
                        ); ?>"
                           class="btn btn-outline-success btn-sm">

                            <i class="fas fa-download mr-1"></i>

                            Download Template

                        </a>


                    </div>


                </div>


                <!-- FOOTER -->

                <div class="modal-footer">


                    <button type="button"
                            class="btn btn-secondary"
                            onclick="closeTendikModal('importModal')">

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

function openTendikModal(id)
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

function closeTendikModal(id)
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
*/

document.addEventListener(
    'DOMContentLoaded',
    function ()
    {

        var searchInput =
            document.querySelector(
                '.tendik-search-input'
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