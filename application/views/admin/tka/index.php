<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| DEFAULT DATA
|--------------------------------------------------------------------------
*/

$tahun_id = isset($tahun_id)
    ? $tahun_id
    : '';

$kelas_id = isset($kelas_id)
    ? $kelas_id
    : '';

$status = isset($status)
    ? $status
    : '';

$nama = isset($nama)
    ? $nama
    : '';

$siswa = isset($siswa)
    ? $siswa
    : [];

$total_siswa = isset($total_siswa)
    ? (int) $total_siswa
    : 0;

$total_sudah = isset($total_sudah)
    ? (int) $total_sudah
    : 0;

$total_belum = isset($total_belum)
    ? (int) $total_belum
    : 0;
?>


<style>

/* =========================================================
   TKA ADMIN PAGE
========================================================= */

.tka-admin-page {
    padding: 18px 0 40px;
    color: #1f2937;
}


/* =========================================================
   HEADER
========================================================= */

.tka-admin-header {
    position: relative;
    overflow: hidden;

    margin-bottom: 24px;
    padding: 29px 31px;

    border-radius: 22px;

    background:
        linear-gradient(
            135deg,
            #4f9cf9 0%,
            #3b82f6 100%
        );

    color: #ffffff;

    box-shadow:
        0 10px 28px rgba(59,130,246,.15);
}

.tka-admin-header::before {
    content: "";

    position: absolute;

    width: 210px;
    height: 210px;

    top: -115px;
    right: -55px;

    border-radius: 50%;

    background: rgba(255,255,255,.10);
}

.tka-admin-header::after {
    content: "";

    position: absolute;

    width: 130px;
    height: 130px;

    right: 110px;
    bottom: -90px;

    border-radius: 50%;

    background: rgba(255,255,255,.07);
}

.tka-admin-header-content {
    position: relative;
    z-index: 2;

    display: flex;
    align-items: center;
    gap: 16px;
}

.tka-admin-header-icon {
    width: 54px;
    height: 54px;

    flex: 0 0 auto;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 16px;

    background: rgba(255,255,255,.16);

    font-size: 21px;
}

.tka-admin-header h4 {
    margin: 0;

    color: #ffffff;

    font-size: 1.32rem;
    font-weight: 700;
}

.tka-admin-header p {
    margin: 6px 0 0;

    color: rgba(255,255,255,.88);

    font-size: .84rem;
    line-height: 1.5;
}


/* =========================================================
   STATISTICS
========================================================= */

.tka-stat-card {
    height: 100%;

    overflow: hidden;

    border: 0;
    border-radius: 17px;

    background: #ffffff;

    box-shadow:
        0 7px 22px rgba(15,23,42,.06);
}

.tka-stat-body {
    display: flex;
    align-items: center;
    gap: 14px;

    padding: 19px;
}

.tka-stat-icon {
    width: 46px;
    height: 46px;

    flex: 0 0 auto;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 13px;

    background: #edf5ff;
    color: #3b82f6;

    font-size: 17px;
}

.tka-stat-content {
    min-width: 0;
}

.tka-stat-number {
    color: #1f2937;

    font-size: 1.32rem;
    font-weight: 700;
    line-height: 1;
}

.tka-stat-label {
    margin-top: 6px;

    color: #6b7280;

    font-size: .75rem;
}


/* =========================================================
   EXPORT ACTION
========================================================= */

.tka-export-wrapper {
    display: flex;
    justify-content: flex-end;

    margin-bottom: 18px;
}

.tka-export-group {
    display: inline-flex;
    gap: 8px;
}

.tka-export-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;

    min-height: 40px;

    padding: 8px 14px;

    border-radius: 10px;

    font-size: .76rem;
    font-weight: 700;
}

.tka-export-excel {
    background: #16a34a;
    border-color: #16a34a;
    color: #ffffff !important;
}

.tka-export-excel:hover {
    background: #15803d;
    border-color: #15803d;
}

.tka-export-pdf {
    background: #dc2626;
    border-color: #dc2626;
    color: #ffffff !important;
}

.tka-export-pdf:hover {
    background: #b91c1c;
    border-color: #b91c1c;
}


/* =========================================================
   FILTER CARD
========================================================= */

.tka-filter-card {
    margin-bottom: 22px;

    border: 0;
    border-radius: 20px;

    background: #ffffff;

    box-shadow:
        0 7px 24px rgba(15,23,42,.06);
}

.tka-filter-card .card-body {
    padding: 22px;
}

.tka-filter-heading {
    display: flex;
    align-items: center;
    gap: 9px;

    margin-bottom: 18px;

    color: #1f2937;

    font-size: .91rem;
    font-weight: 700;
}

.tka-filter-heading i {
    color: #3b82f6;
}

.tka-filter-label {
    display: block;

    margin-bottom: 7px;

    color: #374151;

    font-size: .75rem;
    font-weight: 700;
}

.tka-filter {
    width: 100%;

    min-height: 43px;

    border: 1px solid #dce3ed !important;
    border-radius: 11px !important;

    background: #ffffff !important;
    color: #1f2937 !important;

    font-size: .80rem;

    box-shadow: none !important;

    transition: .2s ease;
}

.tka-filter:hover {
    border-color: #b9cbea !important;
}

.tka-filter:focus {
    border-color: #4f9cf9 !important;

    box-shadow:
        0 0 0 3px rgba(79,156,249,.11) !important;
}


/* =========================================================
   SEARCH
========================================================= */

.tka-search-wrapper {
    position: relative;
}

.tka-search-input {
    padding-left: 39px !important;
    padding-right: 39px !important;
}

.tka-search-icon {
    position: absolute;

    left: 14px;
    top: 50%;

    z-index: 3;

    transform: translateY(-50%);

    color: #94a3b8;

    font-size: .78rem;

    pointer-events: none;
}

.tka-search-clear {
    position: absolute;

    right: 10px;
    top: 50%;

    width: 25px;
    height: 25px;

    display: flex;
    align-items: center;
    justify-content: center;

    transform: translateY(-50%);

    border-radius: 7px;

    background: transparent;

    color: #94a3b8;

    text-decoration: none !important;

    transition: .2s ease;
}

.tka-search-clear:hover {
    background: #eef2f7;

    color: #ef4444;
}


/* =========================================================
   FILTER BUTTON
========================================================= */

.tka-filter-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;

    min-height: 43px;

    border: 0 !important;
    border-radius: 11px !important;

    background:
        linear-gradient(
            135deg,
            #4f9cf9,
            #3b82f6
        ) !important;

    color: #ffffff !important;

    font-size: .78rem;
    font-weight: 700;

    box-shadow: none !important;
}


/* =========================================================
   TABLE CARD
========================================================= */

.tka-table-card {
    overflow: hidden;

    border: 0;
    border-radius: 20px;

    background: #ffffff;

    box-shadow:
        0 7px 24px rgba(15,23,42,.06);
}

.tka-table-card .card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    padding: 19px 22px;

    border-bottom: 1px solid #edf0f5;

    background: #ffffff;
}

.tka-table-title {
    display: flex;
    align-items: center;
    gap: 9px;

    margin: 0;

    color: #1f2937;

    font-size: .95rem;
    font-weight: 700;
}

.tka-table-title i {
    color: #3b82f6;
}

.tka-table-count {
    padding: 5px 9px;

    border-radius: 8px;

    background: #edf5ff;
    color: #3578d4;

    font-size: .67rem;
    font-weight: 700;
}


/* =========================================================
   TABLE
========================================================= */

.tka-table {
    margin: 0;

    color: #374151;
}

.tka-table thead th {
    padding: 13px 14px;

    border-top: 0;
    border-bottom: 1px solid #edf0f5;

    background: #f8fafc;

    color: #64748b;

    font-size: .69rem;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .3px;

    white-space: nowrap;
}

.tka-table tbody td {
    padding: 13px 14px;

    vertical-align: middle;

    border-color: #edf0f5;

    color: #374151;

    font-size: .80rem;
}

.tka-table tbody tr {
    transition: background .15s ease;
}

.tka-table tbody tr:hover {
    background: #fbfdff;
}


/* =========================================================
   STUDENT
========================================================= */

.tka-no {
    color: #8a94a6;

    font-size: .76rem;
    font-weight: 600;
}

.tka-student {
    min-width: 180px;
}

.tka-name {
    margin-bottom: 3px;

    color: #1f2937;

    font-size: .82rem;
    font-weight: 700;
}

.tka-nisn {
    color: #8a94a6;

    font-size: .69rem;
}

.tka-class {
    display: inline-flex;
    align-items: center;

    padding: 5px 8px;

    border-radius: 8px;

    background: #f1f5f9;

    color: #475569;

    font-size: .69rem;
    font-weight: 700;

    white-space: nowrap;
}


/* =========================================================
   MAPEL
========================================================= */

.tka-mapel-wrapper {
    min-width: 175px;
}

.tka-mapel-label {
    display: block;

    margin-bottom: 3px;

    color: #94a3b8;

    font-size: .62rem;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .25px;
}

.tka-mapel {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    color: #374151;

    font-size: .78rem;
    font-weight: 600;
}

.tka-mapel i {
    color: #3b82f6;

    font-size: .68rem;
}

.tka-mapel-pilihan i {
    color: #059669;
}

.tka-empty {
    color: #9ca3af;

    font-size: .75rem;
    font-style: italic;
}


/* =========================================================
   STATUS
========================================================= */

.tka-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    padding: 6px 9px;

    border-radius: 8px;

    font-size: .65rem;
    font-weight: 700;

    white-space: nowrap;
}

.tka-status-sudah {
    background: #dcfce7;
    color: #15803d;
}

.tka-status-belum {
    background: #fff7ed;
    color: #c2410c;
}


/* =========================================================
   ACTION
========================================================= */

.tka-edit-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;

    min-height: 34px;

    padding: 6px 10px;

    border-radius: 9px;

    font-size: .70rem;
    font-weight: 700;
}

.tka-no-action {
    color: #cbd5e1;

    font-size: .72rem;
}


/* =========================================================
   EMPTY
========================================================= */

.tka-empty-state {
    padding: 55px 20px !important;

    color: #94a3b8 !important;

    text-align: center;
}

.tka-empty-icon {
    width: 52px;
    height: 52px;

    margin: 0 auto 12px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 15px;

    background: #f1f5f9;

    color: #94a3b8;

    font-size: 20px;
}

.tka-empty-title {
    margin-bottom: 4px;

    color: #64748b;

    font-size: .84rem;
    font-weight: 700;
}

.tka-empty-text {
    color: #9ca3af;

    font-size: .75rem;
}


/* =========================================================
   DARK MODE
========================================================= */

body.dark-mode .tka-admin-page {
    color: #e5e7eb;
}

body.dark-mode .tka-stat-card,
body.dark-mode .tka-filter-card,
body.dark-mode .tka-table-card {
    background: #202333 !important;
    color: #e5e7eb;
}

body.dark-mode .tka-stat-number {
    color: #f3f4f6;
}

body.dark-mode .tka-stat-label {
    color: #9ca7ba;
}

body.dark-mode .tka-stat-icon {
    background: #293f63;
    color: #7eb4ff;
}

body.dark-mode .tka-filter-heading {
    color: #f3f4f6;
}

body.dark-mode .tka-filter-label {
    color: #e5e7eb;
}

body.dark-mode .tka-filter {
    background: #292d40 !important;
    border-color: #454b61 !important;
    color: #f3f4f6 !important;
}

body.dark-mode .tka-filter option {
    background: #292d40;
    color: #ffffff;
}

body.dark-mode .tka-search-icon {
    color: #7f899d;
}

body.dark-mode .tka-search-clear {
    color: #9ca7ba;
}

body.dark-mode .tka-search-clear:hover {
    background: #3a3f52;
    color: #ff8a8a;
}

body.dark-mode .tka-table-card .card-header {
    background: #202333;
    border-color: #3c4255;
}

body.dark-mode .tka-table-title {
    color: #f3f4f6;
}

body.dark-mode .tka-table-count {
    background: #293f63;
    color: #8bbcff;
}

body.dark-mode .tka-table thead th {
    background: #292d40;
    border-color: #3c4255;
    color: #aeb6c7;
}

body.dark-mode .tka-table tbody td {
    border-color: #3c4255;
    color: #d9deea;
}

body.dark-mode .tka-table tbody tr:hover {
    background: #25293a;
}

body.dark-mode .tka-name {
    color: #f3f4f6;
}

body.dark-mode .tka-nisn {
    color: #929caf;
}

body.dark-mode .tka-class {
    background: #303548;
    color: #cbd5e1;
}

body.dark-mode .tka-mapel-label {
    color: #7f899d;
}

body.dark-mode .tka-mapel {
    color: #e5e7eb;
}

body.dark-mode .tka-mapel i {
    color: #7eb4ff;
}

body.dark-mode .tka-mapel-pilihan i {
    color: #72e4ae;
}

body.dark-mode .tka-empty {
    color: #7f899d;
}

body.dark-mode .tka-status-sudah {
    background: #214936;
    color: #72e4ae;
}

body.dark-mode .tka-status-belum {
    background: #4a3224;
    color: #ffbd8a;
}

body.dark-mode .tka-empty-icon {
    background: #292d40;
    color: #7f899d;
}

body.dark-mode .tka-empty-title {
    color: #aeb6c7;
}

body.dark-mode .tka-empty-text {
    color: #7f899d;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 992px) {

    .tka-filter-card .card-body {
        padding: 19px;
    }

    .tka-table-card {
        overflow: hidden;
    }

    .tka-table {
        min-width: 980px;
    }
}


@media (max-width: 768px) {

    .tka-admin-page {
        padding: 12px 0 30px;
    }

    .tka-admin-header {
        padding: 23px 20px;

        border-radius: 18px;
    }

    .tka-admin-header-content {
        align-items: flex-start;
    }

    .tka-admin-header-icon {
        width: 47px;
        height: 47px;

        border-radius: 13px;

        font-size: 18px;
    }

    .tka-admin-header h4 {
        font-size: 1.13rem;
    }

    .tka-admin-header p {
        font-size: .79rem;
    }

    .tka-export-wrapper {
        justify-content: stretch;
    }

    .tka-export-group {
        width: 100%;
    }

    .tka-export-btn {
        flex: 1;
    }

    .tka-table-card {
        border-radius: 17px;
    }

    .tka-table-card .card-header {
        padding: 17px;
    }
}


@media (max-width: 480px) {

    .tka-admin-header-content {
        gap: 11px;
    }

    .tka-export-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .tka-filter-card .card-body {
        padding: 17px;
    }

    .tka-stat-body {
        padding: 16px;
    }
}

</style>


<div class="container-fluid tka-admin-page">


    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="tka-admin-header">

        <div class="tka-admin-header-content">

            <div class="tka-admin-header-icon">

                <i class="fas fa-graduation-cap"></i>

            </div>


            <div>

                <h4>
                    Peserta TKA
                </h4>

                <p>
                    Kelola dan pantau pengisian TKA siswa kelas XII.
                    Pilihan pertama mengikuti Program Keahlian,
                    sedangkan pilihan kedua ditentukan siswa.
                </p>

            </div>

        </div>

    </div>


    <!-- =====================================================
         STATISTIK
    ====================================================== -->

    <div class="row mb-4">


        <!-- TOTAL -->

        <div class="col-lg-4 col-md-4 mb-3">

            <div class="card tka-stat-card">

                <div class="tka-stat-body">

                    <div class="tka-stat-icon">

                        <i class="fas fa-users"></i>

                    </div>


                    <div class="tka-stat-content">

                        <div class="tka-stat-number">

                            <?= number_format(
                                $total_siswa
                            ); ?>

                        </div>

                        <div class="tka-stat-label">
                            Total Siswa Kelas XII
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- SUDAH -->

        <div class="col-lg-4 col-md-4 mb-3">

            <div class="card tka-stat-card">

                <div class="tka-stat-body">

                    <div class="tka-stat-icon">

                        <i class="fas fa-circle-check"></i>

                    </div>


                    <div class="tka-stat-content">

                        <div class="tka-stat-number">

                            <?= number_format(
                                $total_sudah
                            ); ?>

                        </div>

                        <div class="tka-stat-label">
                            Sudah Mengisi TKA
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- BELUM -->

        <div class="col-lg-4 col-md-4 mb-3">

            <div class="card tka-stat-card">

                <div class="tka-stat-body">

                    <div class="tka-stat-icon">

                        <i class="fas fa-clock"></i>

                    </div>


                    <div class="tka-stat-content">

                        <div class="tka-stat-number">

                            <?= number_format(
                                $total_belum
                            ); ?>

                        </div>

                        <div class="tka-stat-label">
                            Belum Mengisi TKA
                        </div>

                    </div>

                </div>

            </div>

        </div>


    </div>


    <!-- =====================================================
         EXPORT
    ====================================================== -->

    <div class="tka-export-wrapper">

        <div class="tka-export-group">


            <a
                href="<?= site_url(
                    'Tka_admin/export_excel?' .
                    http_build_query([
                        'tahun_id' => $tahun_id,
                        'kelas_id' => $kelas_id,
                        'status'   => $status,
                        'nama'     => $nama
                    ])
                ); ?>"
                class="btn tka-export-btn tka-export-excel"
            >

                <i class="fas fa-file-excel"></i>

                Excel

            </a>


            <a
                href="<?= site_url(
                    'Tka_admin/export_pdf?' .
                    http_build_query([
                        'tahun_id' => $tahun_id,
                        'kelas_id' => $kelas_id,
                        'status'   => $status,
                        'nama'     => $nama
                    ])
                ); ?>"
                class="btn tka-export-btn tka-export-pdf"
                target="_blank"
                rel="noopener noreferrer"
            >

                <i class="fas fa-file-pdf"></i>

                PDF

            </a>


        </div>

    </div>


    <!-- =====================================================
         FILTER
    ====================================================== -->

    <div class="card tka-filter-card">

        <div class="card-body">


            <div class="tka-filter-heading">

                <i class="fas fa-sliders"></i>

                Filter Data Peserta

            </div>


            <form
                method="get"
                action="<?= site_url('Tka_admin'); ?>"
            >


                <div class="row align-items-end">


                    <!-- TAHUN -->

                    <div class="col-lg-3 col-md-6 mb-3">

                        <label class="tka-filter-label">

                            Tahun Ajaran

                        </label>


                        <select
                            name="tahun_id"
                            class="form-control tka-filter"
                        >

                            <?php foreach (
                                $tahun_list
                                as $t
                            ): ?>

                                <option
                                    value="<?= (int) $t->id; ?>"
                                    <?= (
                                        $tahun_id ==
                                        $t->id
                                    )
                                        ? 'selected'
                                        : ''; ?>
                                >

                                    <?= html_escape(
                                        $t->tahun
                                    ); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <!-- KELAS -->

                    <div class="col-lg-3 col-md-6 mb-3">

                        <label class="tka-filter-label">

                            Kelas

                        </label>


                        <select
                            name="kelas_id"
                            class="form-control tka-filter"
                        >

                            <option value="">

                                Semua Kelas XII

                            </option>


                            <?php foreach (
                                $kelas_list
                                as $k
                            ): ?>

                                <option
                                    value="<?= (int) $k->id; ?>"
                                    <?= (
                                        $kelas_id ==
                                        $k->id
                                    )
                                        ? 'selected'
                                        : ''; ?>
                                >

                                    <?= html_escape(
                                        $k->nama
                                    ); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <!-- STATUS -->

                    <div class="col-lg-2 col-md-6 mb-3">

                        <label class="tka-filter-label">

                            Status

                        </label>


                        <select
                            name="status"
                            class="form-control tka-filter"
                        >

                            <option
                                value=""
                                <?= (
                                    $status === ''
                                )
                                    ? 'selected'
                                    : ''; ?>
                            >

                                Semua

                            </option>


                            <option
                                value="sudah"
                                <?= (
                                    $status ===
                                    'sudah'
                                )
                                    ? 'selected'
                                    : ''; ?>
                            >

                                Sudah Mengisi

                            </option>


                            <option
                                value="belum"
                                <?= (
                                    $status ===
                                    'belum'
                                )
                                    ? 'selected'
                                    : ''; ?>
                            >

                                Belum Mengisi

                            </option>

                        </select>

                    </div>


                    <!-- SEARCH -->

                    <div class="col-lg-3 col-md-6 mb-3">

                        <label class="tka-filter-label">

                            Cari Nama Siswa

                        </label>


                        <div class="tka-search-wrapper">


                            <i
                                class="fas fa-search tka-search-icon"
                            ></i>


                            <input
                                type="text"
                                name="nama"
                                value="<?= html_escape(
                                    $nama
                                ); ?>"
                                class="form-control tka-filter tka-search-input"
                                placeholder="Ketik nama siswa..."
                                autocomplete="off"
                            >


                            <?php if (
                                !empty($nama)
                            ): ?>

                                <a
                                    href="<?= site_url(
                                        'Tka_admin?' .
                                        http_build_query([
                                            'tahun_id' =>
                                                $tahun_id,

                                            'kelas_id' =>
                                                $kelas_id,

                                            'status' =>
                                                $status
                                        ])
                                    ); ?>"
                                    class="tka-search-clear"
                                    title="Hapus pencarian"
                                >

                                    <i class="fas fa-times"></i>

                                </a>

                            <?php endif; ?>


                        </div>

                    </div>


                    <!-- BUTTON -->

                    <div class="col-lg-1 col-md-6 mb-3">

                        <button
                            type="submit"
                            class="btn btn-primary tka-filter-btn w-100"
                            title="Terapkan filter"
                        >

                            <i class="fas fa-filter"></i>

                            <span class="d-lg-none ml-1">
                                Filter
                            </span>

                        </button>

                    </div>


                </div>


            </form>

        </div>

    </div>


    <!-- =====================================================
         TABLE
    ====================================================== -->

    <div class="card tka-table-card">


        <!-- TABLE HEADER -->

        <div class="card-header">


            <h5 class="tka-table-title">

                <i class="fas fa-list"></i>

                Daftar Peserta TKA

            </h5>


            <span class="tka-table-count">

                <?= number_format(
                    count($siswa)
                ); ?>

                DATA

            </span>


        </div>


        <!-- TABLE -->

        <div class="table-responsive">

            <table class="table tka-table">


                <thead>

                    <tr>

                        <th width="45">
                            #
                        </th>

                        <th>
                            Siswa
                        </th>

                        <th>
                            Kelas
                        </th>

                        <th>
                            Program Keahlian
                        </th>

                        <th>
                            Pilihan Siswa
                        </th>

                        <th>
                            Status
                        </th>

                        <th width="85">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <?php if (
                        empty($siswa)
                    ): ?>


                        <tr>

                            <td
                                colspan="7"
                                class="tka-empty-state"
                            >


                                <div class="tka-empty-icon">

                                    <i class="fas fa-inbox"></i>

                                </div>


                                <div class="tka-empty-title">

                                    Tidak ada data peserta

                                </div>


                                <div class="tka-empty-text">

                                    Belum ada siswa yang sesuai
                                    dengan filter yang dipilih.

                                </div>


                            </td>

                        </tr>


                    <?php else: ?>


                        <?php
                        $no = 1;
                        ?>


                        <?php foreach (
                            $siswa
                            as $row
                        ): ?>


                            <tr>


                                <!-- NOMOR -->

                                <td>

                                    <span class="tka-no">

                                        <?= $no++; ?>

                                    </span>

                                </td>


                                <!-- SISWA -->

                                <td>

                                    <div class="tka-student">


                                        <div class="tka-name">

                                            <?= html_escape(
                                                $row->nama
                                            ); ?>

                                        </div>


                                        <div class="tka-nisn">

                                            NISN:

                                            <?= html_escape(
                                                $row->nisn
                                            ); ?>

                                        </div>


                                    </div>

                                </td>


                                <!-- KELAS -->

                                <td>

                                    <span class="tka-class">

                                        <?= html_escape(
                                            $row->nama_kelas
                                        ); ?>

                                    </span>

                                </td>


                                <!-- PROGRAM KEAHLIAN -->

                                <td>


                                    <?php if (
                                        !empty(
                                            $row->mapel_1
                                        )
                                    ): ?>


                                        <div class="tka-mapel-wrapper">


                                            <span class="tka-mapel-label">

                                                Pilihan 1 · Otomatis

                                            </span>


                                            <span class="tka-mapel">

                                                <i
                                                    class="fas fa-graduation-cap"
                                                ></i>


                                                <?= html_escape(
                                                    $row->mapel_1
                                                ); ?>


                                            </span>


                                        </div>


                                    <?php else: ?>


                                        <span class="tka-empty">

                                            Belum tersedia

                                        </span>


                                    <?php endif; ?>


                                </td>


                                <!-- PILIHAN SISWA -->

                                <td>


                                    <?php if (
                                        !empty(
                                            $row->mapel_2
                                        )
                                    ): ?>


                                        <div class="tka-mapel-wrapper">


                                            <span class="tka-mapel-label">

                                                Pilihan 2 · Siswa

                                            </span>


                                            <span
                                                class="tka-mapel tka-mapel-pilihan"
                                            >

                                                <i
                                                    class="fas fa-circle-check"
                                                ></i>


                                                <?= html_escape(
                                                    $row->mapel_2
                                                ); ?>


                                            </span>


                                        </div>


                                    <?php else: ?>


                                        <span class="tka-empty">

                                            Belum memilih

                                        </span>


                                    <?php endif; ?>


                                </td>


                                <!-- STATUS -->

                                <td>


                                    <?php if (
                                        !empty(
                                            $row->tka_id
                                        )
                                    ): ?>


                                        <span
                                            class="tka-status tka-status-sudah"
                                        >

                                            <i
                                                class="fas fa-check"
                                            ></i>

                                            Sudah Mengisi

                                        </span>


                                    <?php else: ?>


                                        <span
                                            class="tka-status tka-status-belum"
                                        >

                                            <i
                                                class="fas fa-clock"
                                            ></i>

                                            Belum Mengisi

                                        </span>


                                    <?php endif; ?>


                                </td>


                                <!-- AKSI -->

                                <td>


                                    <?php if (
                                        !empty(
                                            $row->tka_id
                                        )
                                    ): ?>


                                        <a
                                            href="<?= site_url(
                                                'Tka_admin/edit/' .
                                                (int) $row->tka_id
                                            ); ?>"
                                            class="btn btn-sm btn-primary tka-edit-btn"
                                        >

                                            <i
                                                class="fas fa-pen"
                                            ></i>

                                            Edit

                                        </a>


                                    <?php else: ?>


                                        <span
                                            class="tka-no-action"
                                        >
                                            —
                                        </span>


                                    <?php endif; ?>


                                </td>


                            </tr>


                        <?php endforeach; ?>


                    <?php endif; ?>


                </tbody>


            </table>

        </div>


    </div>


</div>