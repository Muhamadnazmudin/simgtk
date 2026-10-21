<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$pending_count = (int) $pending;
$biodata_count = (int) $jumlah_biodata;
$berkas_count  = (int) $jumlah_berkas;

$sync_url = site_url('Sync_google/proses');

$flash_success = $this->session->flashdata('success');
$flash_error   = $this->session->flashdata('error');

$sync_finished = !empty($flash_success) || !empty($flash_error);
?>

<style>

/* =========================================================
   GOOGLE SYNC
========================================================= */

.sync-google-page {
    padding-bottom: 30px;
}


/* =========================================================
   HEADER
========================================================= */

.sync-google-title {
    display: flex;
    align-items: center;
    gap: 10px;
}


/* =========================================================
   MAIN CARD
========================================================= */

.sync-google-card {
    border: 0;
    border-radius: 16px;
    overflow: hidden;
}

.sync-google-card .card-header {
    background: #fff;
    border-bottom: 1px solid #edf0f5;
    padding: 18px 22px;
}

.sync-google-card .card-body {
    padding: 28px;
}


/* =========================================================
   STATUS
========================================================= */

.sync-status-title {
    margin-bottom: 12px;

    font-size: 22px;
    font-weight: 700;

    color: #343a40;
}

.sync-status-description {
    max-width: 720px;

    color: #858796;

    line-height: 1.7;
}

.sync-status-info {
    color: #858796;
    font-size: 13px;
}


/* =========================================================
   COUNTER
========================================================= */

.sync-counter-wrapper {
    text-align: center;
}

.sync-counter {
    font-size: 58px;
    line-height: 1;

    font-weight: 700;

    color: #4e73df;
}

.sync-counter-label {
    margin-top: 8px;

    color: #858796;

    font-size: 14px;
}


/* =========================================================
   BUTTON
========================================================= */

.sync-button {
    min-width: 220px;

    margin-top: 24px;

    padding: 12px 22px;

    border-radius: 10px;

    font-weight: 600;
}

.sync-button i {
    margin-right: 7px;
}

.sync-button:disabled {
    cursor: not-allowed;
}


/* =========================================================
   SUMMARY
========================================================= */

.sync-summary-card {
    border: 0;
    border-radius: 14px;
    overflow: hidden;
}

.sync-summary-card .card-body {
    padding: 22px;
}

.sync-summary-title {
    margin-bottom: 5px;

    font-size: 11px;
    font-weight: 700;

    text-transform: uppercase;
}

.sync-summary-number {
    font-size: 30px;
    font-weight: 700;

    color: #343a40;
}

.sync-summary-number small {
    font-size: 14px;
    font-weight: 400;

    color: #858796;
}

.sync-summary-icon {
    font-size: 29px;
    opacity: .35;
}


/* =========================================================
   DETAIL TABLE
========================================================= */

.sync-detail-card {
    border: 0;
    border-radius: 14px;
    overflow: hidden;
}

.sync-detail-card .card-header {
    background: #fff;

    border-bottom: 1px solid #edf0f5;

    padding: 17px 22px;
}

.sync-detail-card .card-body {
    padding: 0;
}

.sync-detail-table {
    margin-bottom: 0;
}

.sync-detail-table th,
.sync-detail-table td {
    vertical-align: middle;
}

.sync-detail-table th {
    white-space: nowrap;
}


/* =========================================================
   BADGE
========================================================= */

.sync-badge {
    display: inline-flex;

    align-items: center;
    justify-content: center;

    gap: 5px;

    padding: 7px 10px;

    border-radius: 7px;

    font-size: 12px;
}


/* =========================================================
   INFORMATION
========================================================= */

.sync-info-label {
    margin-bottom: 7px;

    color: #4e73df;

    font-size: 11px;
    font-weight: 700;

    text-transform: uppercase;
}

.sync-info-value {
    color: #343a40;
}


/* =========================================================
   PROCESS OVERLAY
========================================================= */

#syncProcessOverlay {

    position: fixed;

    top: 0;
    right: 0;
    bottom: 0;
    left: 0;

    z-index: 99999;

    display: none;

    align-items: center;
    justify-content: center;

    padding: 20px;

    background: rgba(15, 23, 42, .80);

    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
}

#syncProcessOverlay.active {
    display: flex;
}


/* =========================================================
   PROCESS BOX
========================================================= */

.sync-process-box {

    width: 100%;
    max-width: 540px;

    padding: 35px;

    border-radius: 20px;

    background: #fff;

    text-align: center;

    box-shadow: 0 25px 70px rgba(0, 0, 0, .30);

    animation: syncProcessShow .25s ease-out;
}

@keyframes syncProcessShow {

    from {
        opacity: 0;
        transform: translateY(15px) scale(.97);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

}


/* =========================================================
   PROCESS ICON
========================================================= */

.sync-process-icon {

    width: 82px;
    height: 82px;

    margin: 0 auto 22px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #eef2ff;

    color: #4e73df;

    font-size: 34px;
}

.sync-process-icon i {
    animation: syncRotate 1.2s linear infinite;
}

@keyframes syncRotate {

    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }

}


/* =========================================================
   PROCESS TITLE
========================================================= */

.sync-process-title {

    margin-bottom: 9px;

    color: #343a40;

    font-size: 23px;
    font-weight: 700;
}

.sync-process-description {

    margin-bottom: 25px;

    color: #858796;

    line-height: 1.7;

    font-size: 14px;
}


/* =========================================================
   PROGRESS
========================================================= */

.sync-progress {

    width: 100%;
    height: 9px;

    margin-bottom: 18px;

    overflow: hidden;

    border-radius: 20px;

    background: #e9ecef;
}

.sync-progress-bar {

    width: 35%;
    height: 100%;

    border-radius: 20px;

    background: #4e73df;

    animation: syncProgress 1.5s ease-in-out infinite;
}

@keyframes syncProgress {

    0% {
        transform: translateX(-160%);
    }

    50% {
        transform: translateX(150%);
    }

    100% {
        transform: translateX(330%);
    }

}


/* =========================================================
   PROCESS STATUS
========================================================= */

.sync-process-status {

    display: flex;

    align-items: center;
    justify-content: center;

    gap: 8px;

    color: #6c757d;

    font-size: 13px;
}

.sync-process-status i {

    color: #4e73df;

    font-size: 8px;
}


/* =========================================================
   PROCESS WARNING
========================================================= */

.sync-process-warning {

    margin-top: 20px;

    padding: 12px 14px;

    border-radius: 9px;

    background: #f8f9fc;

    color: #858796;

    font-size: 12px;

    line-height: 1.6;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 767.98px) {

    .sync-google-card .card-body {
        padding: 22px;
    }

    .sync-counter-wrapper {
        margin-top: 28px;
    }

    .sync-counter {
        font-size: 48px;
    }

    .sync-process-box {
        padding: 28px 22px;
    }

    .sync-process-title {
        font-size: 20px;
    }

}

</style>


<div class="container-fluid sync-google-page">


    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <h1 class="h3 mb-0 text-gray-800 sync-google-title">

            <i class="fas fa-sync-alt text-primary"></i>

            Sinkronisasi Google Spreadsheet

        </h1>

    </div>


    <!-- =====================================================
         FLASH MESSAGE
    ====================================================== -->

    <?php if (!empty($flash_success)): ?>

        <div class="alert alert-success shadow-sm">

            <i class="fas fa-check-circle mr-1"></i>

            <?= $flash_success; ?>

        </div>

    <?php endif; ?>


    <?php if (!empty($flash_error)): ?>

        <div class="alert alert-danger shadow-sm">

            <i class="fas fa-exclamation-circle mr-1"></i>

            <?= $flash_error; ?>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         STATUS
    ====================================================== -->

    <div class="card shadow-sm mb-4 sync-google-card">

        <div class="card-header">

            <h6 class="m-0 font-weight-bold text-primary">

                <i class="fas fa-cloud-upload-alt mr-1"></i>

                Status Sinkronisasi

            </h6>

        </div>


        <div class="card-body">

            <div class="row align-items-center">


                <!-- INFO -->

                <div class="col-md-7">

                    <div class="sync-status-title">

                        Data Menunggu Sinkronisasi

                    </div>


                    <div class="sync-status-description mb-3">

                        Perubahan biodata dan berkas siswa
                        disimpan terlebih dahulu di database.

                        Data akan dikirim ke Google Spreadsheet
                        ketika Admin melakukan sinkronisasi.

                    </div>


                    <div class="sync-status-info">

                        <i class="fas fa-info-circle mr-1"></i>

                        Sinkronisasi dilakukan secara manual oleh Admin.

                    </div>

                </div>


                <!-- COUNTER -->

                <div class="col-md-5">

                    <div class="sync-counter-wrapper">

                        <div class="sync-counter">

                            <?= $pending_count; ?>

                        </div>


                        <div class="sync-counter-label">

                            siswa menunggu sinkronisasi

                        </div>


                        <?php if ($pending_count > 0): ?>

                            <button
                                type="button"
                                id="btnSyncGoogle"
                                class="btn btn-primary btn-lg shadow-sm sync-button"
                                data-url="<?= html_escape($sync_url); ?>"
                                data-pending="<?= $pending_count; ?>"
                            >

                                <i class="fas fa-sync-alt"></i>

                                Kirim-sinkron Data

                            </button>

                        <?php else: ?>

                            <button
                                type="button"
                                class="btn btn-secondary btn-lg sync-button"
                                disabled
                            >

                                <i class="fas fa-check"></i>

                                Semua Sudah Sinkron

                            </button>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         RINGKASAN
    ====================================================== -->

    <?php if ($pending_count > 0): ?>

        <div class="row mb-4">


            <!-- BIODATA -->

            <div class="col-md-6 mb-3">

                <div class="card shadow-sm h-100 sync-summary-card border-left-primary">

                    <div class="card-body">

                        <div class="row align-items-center">

                            <div class="col">

                                <div class="sync-summary-title text-primary">

                                    Perubahan Biodata

                                </div>


                                <div class="sync-summary-number">

                                    <?= $biodata_count; ?>

                                    <small>
                                        siswa
                                    </small>

                                </div>

                            </div>


                            <div class="col-auto">

                                <i class="fas fa-user-edit text-gray-300 sync-summary-icon"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- BERKAS -->

            <div class="col-md-6 mb-3">

                <div class="card shadow-sm h-100 sync-summary-card border-left-success">

                    <div class="card-body">

                        <div class="row align-items-center">

                            <div class="col">

                                <div class="sync-summary-title text-success">

                                    Berkas Akan Disinkronkan

                                </div>


                                <div class="sync-summary-number">

                                    <?= $berkas_count; ?>

                                    <small>
                                        file
                                    </small>

                                </div>

                            </div>


                            <div class="col-auto">

                                <i class="fas fa-file-upload text-gray-300 sync-summary-icon"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         DETAIL SISWA
    ====================================================== -->

    <?php if (!empty($pending_detail)): ?>

        <div class="card shadow-sm mb-4 sync-detail-card">

            <div class="card-header">

                <h6 class="m-0 font-weight-bold text-primary">

                    <i class="fas fa-list mr-1"></i>

                    Detail Data yang Akan Disinkronkan

                </h6>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover sync-detail-table">

                        <thead class="thead-light">

                            <tr>

                                <th width="50">
                                    #
                                </th>

                                <th>
                                    NISN
                                </th>

                                <th>
                                    Nama Siswa
                                </th>

                                <th>
                                    Kelas
                                </th>

                                <th class="text-center">
                                    Biodata
                                </th>

                                <th class="text-center">
                                    Berkas
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        <?php $no = 1; ?>

                        <?php foreach ($pending_detail as $item): ?>

                            <tr>

                                <td>
                                    <?= $no++; ?>
                                </td>

                                <td>
                                    <?= html_escape($item->nisn); ?>
                                </td>

                                <td>
                                    <?= html_escape($item->nama); ?>
                                </td>

                                <td>
                                    <?= html_escape($item->kelas); ?>
                                </td>


                                <!-- BIODATA -->

                                <td class="text-center">

                                    <?php if ((int) $item->biodata_pending === 1): ?>

                                        <span class="badge badge-primary sync-badge">

                                            <i class="fas fa-edit"></i>

                                            Berubah

                                        </span>

                                    <?php else: ?>

                                        <span class="badge badge-secondary sync-badge">

                                            Tidak berubah

                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- BERKAS -->

                                <td class="text-center">

                                    <?php if ((int) $item->berkas_pending === 1): ?>

                                        <span class="badge badge-success sync-badge">

                                            <i class="fas fa-file"></i>

                                            <?= (int) $item->jumlah_berkas; ?>

                                            file

                                        </span>

                                    <?php else: ?>

                                        <span class="badge badge-secondary sync-badge">

                                            Tidak berubah

                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         INFORMASI
    ====================================================== -->

    <div class="card shadow-sm mb-4 sync-google-card">

        <div class="card-header">

            <h6 class="m-0 font-weight-bold text-primary">

                <i class="fas fa-info-circle mr-1"></i>

                Informasi

            </h6>

        </div>


        <div class="card-body">

            <div class="row">


                <!-- STATUS -->

                <div class="col-md-4 mb-3">

                    <div class="sync-info-label">

                        Status

                    </div>


                    <?php if ($pending_count > 0): ?>

                        <span class="badge badge-warning p-2">

                            <i class="fas fa-clock mr-1"></i>

                            Menunggu Sinkronisasi

                        </span>

                    <?php else: ?>

                        <span class="badge badge-success p-2">

                            <i class="fas fa-check mr-1"></i>

                            Semua Sudah Sinkron

                        </span>

                    <?php endif; ?>

                </div>


                <!-- DATA -->

                <div class="col-md-4 mb-3">

                    <div class="sync-info-label">

                        Data Pending

                    </div>


                    <div class="sync-info-value">

                        <strong>
                            <?= $pending_count; ?>
                        </strong>

                        siswa

                    </div>

                </div>


                <!-- TUJUAN -->

                <div class="col-md-4 mb-3">

                    <div class="sync-info-label">

                        Tujuan

                    </div>


                    <div class="sync-info-value">

                        <strong>

                            <i class="fas fa-table text-success mr-1"></i>

                            Google Spreadsheet

                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     OVERLAY PROSES
========================================================== -->

<div
    id="syncProcessOverlay"
    aria-hidden="true"
>

    <div class="sync-process-box">


        <!-- ICON -->

        <div class="sync-process-icon">

            <i class="fas fa-sync-alt"></i>

        </div>


        <!-- TITLE -->

        <div class="sync-process-title">

            Sinkronisasi Sedang Berjalan

        </div>


        <!-- DESCRIPTION -->

        <div class="sync-process-description">

            Sistem sedang mengirim

            <strong id="syncTotal">

                <?= $pending_count; ?>

            </strong>

            siswa ke Google Spreadsheet.

            <br>

            Mohon tunggu sampai proses selesai.

        </div>


        <!-- PROGRESS -->

        <div class="sync-progress">

            <div class="sync-progress-bar"></div>

        </div>


        <!-- STATUS -->

        <div class="sync-process-status">

            <i class="fas fa-circle"></i>

            <span id="syncProcessText">

                Menyiapkan data untuk dikirim...

            </span>

        </div>


        <!-- WARNING -->

        <div class="sync-process-warning">

            <i class="fas fa-info-circle mr-1"></i>

            Jangan menutup halaman selama proses sinkronisasi berlangsung.

        </div>

    </div>

</div>


<script>

(function () {

    'use strict';


    /* =====================================================
       CONFIG
    ====================================================== */

    const STORAGE_KEY = 'sync_google_process';


    /* =====================================================
       ELEMENT
    ====================================================== */

    const button = document.getElementById(
        'btnSyncGoogle'
    );

    const overlay = document.getElementById(
        'syncProcessOverlay'
    );

    const statusText = document.getElementById(
        'syncProcessText'
    );


    if (!overlay) {
        return;
    }


    /* =====================================================
       URL
    ====================================================== */

    const syncUrl = button
        ? button.getAttribute('data-url')
        : '';


    const pending = button
        ? parseInt(
            button.getAttribute('data-pending') || '0',
            10
        )
        : <?= $pending_count; ?>;


    /* =====================================================
       BACKEND SELESAI
    ====================================================== */

    const syncFinished = <?= $sync_finished ? 'true' : 'false'; ?>;


    /* =====================================================
       STORAGE
    ====================================================== */

    function saveProcess() {

        try {

            sessionStorage.setItem(
                STORAGE_KEY,
                JSON.stringify({
                    running: true,
                    pending: pending,
                    url: syncUrl,
                    started: Date.now()
                })
            );

        } catch (error) {

            console.warn(
                'Gagal menyimpan status sinkronisasi.',
                error
            );

        }

    }


    function getProcess() {

        try {

            const value = sessionStorage.getItem(
                STORAGE_KEY
            );

            if (!value) {
                return null;
            }

            return JSON.parse(value);

        } catch (error) {

            sessionStorage.removeItem(
                STORAGE_KEY
            );

            return null;

        }

    }


    function clearProcess() {

        try {

            sessionStorage.removeItem(
                STORAGE_KEY
            );

        } catch (error) {

            console.warn(
                'Gagal menghapus status sinkronisasi.',
                error
            );

        }

    }


    /* =====================================================
       SHOW OVERLAY
    ====================================================== */

    function showOverlay() {

        overlay.classList.add('active');

        overlay.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.style.overflow = 'hidden';

    }


    /* =====================================================
       LOCK BUTTON
    ====================================================== */

    function lockButton() {

        if (!button) {
            return;
        }

        button.disabled = true;

        button.innerHTML =
            '<i class="fas fa-sync-alt fa-spin"></i> ' +
            'Sedang Mengirim...';

    }


    /* =====================================================
       STATUS TEXT
    ====================================================== */

    function setStatus(text) {

        if (!statusText) {
            return;
        }

        statusText.textContent = text;

    }


    /* =====================================================
       ANIMASI STATUS
    ====================================================== */

    function runStatusAnimation() {

        setStatus(
            'Menyiapkan data untuk dikirim...'
        );


        setTimeout(function () {

            setStatus(
                'Mengirim data siswa ke Google Spreadsheet...'
            );

        }, 900);


        setTimeout(function () {

            setStatus(
                'Memproses data dan berkas siswa...'
            );

        }, 1900);


        setTimeout(function () {

            setStatus(
                'Menunggu respons dari Google Spreadsheet...'
            );

        }, 2900);

    }


    /* =====================================================
       JALANKAN PROSES BACKEND
    ====================================================== */

    function redirectToSync() {

        if (!syncUrl) {
            return;
        }

        window.location.href = syncUrl;

    }


    /* =====================================================
       PROSES BARU
    ====================================================== */

    function startNewSync() {

        saveProcess();

        lockButton();

        showOverlay();

        runStatusAnimation();


        setTimeout(function () {

            redirectToSync();

        }, 3000);

    }


    /* =====================================================
       PROSES MASIH TERSIMPAN
    ====================================================== */

    function restoreProcess() {

        const process = getProcess();

        if (!process || !process.running) {
            return;
        }


        /*
         * Jangan mempertahankan status terlalu lama.
         * 30 menit dianggap sebagai proses yang sudah tidak valid.
         */

        const started = parseInt(
            process.started || '0',
            10
        );

        const elapsed = Date.now() - started;

        const maxAge = 30 * 60 * 1000;


        if (
            started <= 0 ||
            elapsed > maxAge
        ) {

            clearProcess();

            return;

        }


        lockButton();

        showOverlay();

        setStatus(
            'Sinkronisasi masih sedang diproses...'
        );


        /*
         * Lanjutkan request hanya jika halaman sebelumnya
         * ter-refresh sebelum proses backend selesai.
         */

        setTimeout(function () {

            redirectToSync();

        }, 500);

    }


    /* =====================================================
       JIKA SUDAH SELESAI
    ====================================================== */

    if (syncFinished) {

        clearProcess();

    } else {

        restoreProcess();

    }


    /* =====================================================
       CLICK
    ====================================================== */

    if (button) {

        button.addEventListener(
            'click',
            function () {

                if (button.disabled) {
                    return;
                }


                const confirmed = window.confirm(
                    'Sinkronkan ' +
                    pending +
                    ' siswa ke Google Spreadsheet sekarang?'
                );


                if (!confirmed) {
                    return;
                }


                startNewSync();

            }
        );

    }

})();

</script>