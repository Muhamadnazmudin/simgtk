<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>

/* =========================================================
   TKA ADMIN
========================================================= */

.tka-admin-page {
    padding-bottom: 30px;
}

.tka-admin-header {
    position: relative;
    overflow: hidden;
    padding: 28px 30px;
    margin-bottom: 22px;
    border-radius: 20px;
    background: linear-gradient(
        135deg,
        #4f9cf9,
        #3b82f6
    );
    color: #fff;
}

.tka-admin-header h4 {
    margin: 0;
    font-weight: 700;
}

.tka-admin-header p {
    margin: 7px 0 0;
    opacity: .88;
    font-size: .88rem;
}

.tka-stat {
    height: 100%;
    border: 0;
    border-radius: 17px;
    background: #fff;
    box-shadow: 0 7px 22px rgba(15,23,42,.06);
}

.tka-stat-body {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 19px;
}

.tka-stat-icon {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 13px;
    background: #edf5ff;
    color: #3b82f6;
}

.tka-stat-number {
    font-size: 1.35rem;
    font-weight: 700;
    line-height: 1;
}

.tka-stat-label {
    margin-top: 5px;
    color: #6b7280;
    font-size: .76rem;
}

.tka-filter-card,
.tka-table-card {
    border: 0;
    border-radius: 20px;
    box-shadow: 0 7px 24px rgba(15,23,42,.06);
}

.tka-filter-card .card-body {
    padding: 22px;
}

.tka-filter-label {
    margin-bottom: 7px;
    color: #374151;
    font-size: .78rem;
    font-weight: 700;
}

.tka-filter {
    min-height: 43px;
    border-radius: 11px;
}

.tka-filter-btn {
    min-height: 43px;
    border-radius: 11px;
    font-weight: 600;
}

.tka-table-card .card-header {
    padding: 19px 22px;
    background: #fff;
    border-bottom: 1px solid #edf0f5;
    border-radius: 20px 20px 0 0;
}

.tka-table-title {
    margin: 0;
    color: #1f2937;
    font-weight: 700;
}

.tka-table {
    margin: 0;
}

.tka-table thead th {
    padding: 13px 14px;
    border-top: 0;
    background: #f8fafc;
    color: #64748b;
    font-size: .73rem;
    text-transform: uppercase;
    letter-spacing: .3px;
    white-space: nowrap;
}

.tka-table tbody td {
    padding: 13px 14px;
    vertical-align: middle;
    border-color: #edf0f5;
    color: #374151;
    font-size: .83rem;
}

.tka-name {
    color: #1f2937;
    font-weight: 700;
}

.tka-nisn {
    color: #8a94a6;
    font-size: .73rem;
}

.tka-mapel {
    color: #374151;
    font-weight: 600;
}

.tka-empty {
    color: #9ca3af;
    font-style: italic;
}

.tka-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 9px;
    border-radius: 8px;
    font-size: .68rem;
    font-weight: 700;
}

.tka-status-sudah {
    background: #dcfce7;
    color: #15803d;
}

.tka-status-belum {
    background: #fff7ed;
    color: #c2410c;
}

.tka-edit-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border-radius: 9px;
    font-size: .75rem;
    font-weight: 600;
}

/* =========================================================
   DARK MODE
========================================================= */

body.dark-mode .tka-stat,
body.dark-mode .tka-filter-card,
body.dark-mode .tka-table-card {
    background: #202333 !important;
    color: #e5e7eb;
}

body.dark-mode .tka-stat-icon {
    background: #293f63;
    color: #7eb4ff;
}

body.dark-mode .tka-stat-label {
    color: #9ca7ba;
}

body.dark-mode .tka-filter-label {
    color: #e5e7eb;
}

body.dark-mode .tka-filter {
    background: #292d40;
    color: #f3f4f6;
    border-color: #454b61;
}

body.dark-mode .tka-filter option {
    background: #292d40;
    color: #fff;
}

body.dark-mode .tka-table-card .card-header {
    background: #202333;
    border-color: #3c4255;
}

body.dark-mode .tka-table-title {
    color: #f3f4f6;
}

body.dark-mode .tka-table thead th {
    background: #292d40;
    color: #aeb6c7;
    border-color: #3c4255;
}

body.dark-mode .tka-table tbody td {
    color: #d9deea;
    border-color: #3c4255;
}

body.dark-mode .tka-name {
    color: #f3f4f6;
}

body.dark-mode .tka-nisn {
    color: #929caf;
}

body.dark-mode .tka-mapel {
    color: #e5e7eb;
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

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    .tka-admin-header {
        padding: 23px 20px;
    }

    .tka-table-card {
        overflow-x: auto;
    }

}


/* =========================================================
   SEARCH NAMA
========================================================= */

.tka-search-wrapper {
    position: relative;
}

.tka-search-input {
    padding-left: 39px !important;
    padding-right: 38px !important;
}

.tka-search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 3;

    color: #94a3b8;
    font-size: .82rem;
    pointer-events: none;
}

.tka-search-clear {
    position: absolute;
    right: 11px;
    top: 50%;
    transform: translateY(-50%);

    width: 25px;
    height: 25px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 7px;

    color: #94a3b8;
    background: transparent;

    text-decoration: none !important;

    transition: all .2s ease;
}

.tka-search-clear:hover {
    background: #eef2f7;
    color: #ef4444;
}


/* =========================================================
   DARK MODE SEARCH
========================================================= */

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
</style>


<div class="container-fluid tka-admin-page">

    <!-- HEADER -->

    <div class="tka-admin-header">

        <h4>
            <i class="fas fa-graduation-cap mr-2"></i>
            Peserta TKA
        </h4>

        <p>
            Pengelolaan pilihan mata pelajaran TKA siswa kelas XII.
        </p>

    </div>


    <!-- STATISTIK -->

    <div class="row mb-4">

        <div class="col-md-4 mb-3">

            <div class="card tka-stat">

                <div class="tka-stat-body">

                    <div class="tka-stat-icon">
                        <i class="fas fa-users"></i>
                    </div>

                    <div>

                        <div class="tka-stat-number">
                            <?= number_format($total_siswa); ?>
                        </div>

                        <div class="tka-stat-label">
                            Siswa Kelas XII
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4 mb-3">

            <div class="card tka-stat">

                <div class="tka-stat-body">

                    <div class="tka-stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>

                    <div>

                        <div class="tka-stat-number">
                            <?= number_format($total_sudah); ?>
                        </div>

                        <div class="tka-stat-label">
                            Sudah Mengisi
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4 mb-3">

            <div class="card tka-stat">

                <div class="tka-stat-body">

                    <div class="tka-stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>

                    <div>

                        <div class="tka-stat-number">
                            <?= number_format($total_belum); ?>
                        </div>

                        <div class="tka-stat-label">
                            Belum Mengisi
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="d-flex justify-content-end mb-3">

    <div class="btn-group">

        <a
            href="<?= site_url('Tka_admin/export_excel?' . http_build_query([
                'tahun_id' => $tahun_id,
                'kelas_id' => $kelas_id,
                'status'   => $status
            ])); ?>"
            class="btn btn-success"
        >
            <i class="fas fa-file-excel mr-1"></i>
            Excel
        </a>

        <a
            href="<?= site_url('Tka_admin/export_pdf?' . http_build_query([
                'tahun_id' => $tahun_id,
                'kelas_id' => $kelas_id,
                'status'   => $status
            ])); ?>"
            class="btn btn-danger"
            target="_blank"
        >
            <i class="fas fa-file-pdf mr-1"></i>
            PDF
        </a>

    </div>

</div>

    <!-- =========================================================
     FILTER
========================================================= -->

<div class="card tka-filter-card mb-4">

    <div class="card-body">

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

                        <?php foreach ($tahun_list as $t): ?>

                            <option
                                value="<?= (int) $t->id; ?>"
                                <?= ($tahun_id == $t->id)
                                    ? 'selected'
                                    : ''; ?>
                            >

                                <?= html_escape($t->tahun); ?>

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

                        <?php foreach ($kelas_list as $k): ?>

                            <option
                                value="<?= (int) $k->id; ?>"
                                <?= ($kelas_id == $k->id)
                                    ? 'selected'
                                    : ''; ?>
                            >

                                <?= html_escape($k->nama); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- STATUS -->
                <div class="col-lg-2 col-md-6 mb-3">

                    <label class="tka-filter-label">
                        Status Pengisian
                    </label>

                    <select
                        name="status"
                        class="form-control tka-filter"
                    >

                        <option
                            value=""
                            <?= ($status === '')
                                ? 'selected'
                                : ''; ?>
                        >
                            Semua
                        </option>

                        <option
                            value="sudah"
                            <?= ($status === 'sudah')
                                ? 'selected'
                                : ''; ?>
                        >
                            Sudah Mengisi
                        </option>

                        <option
                            value="belum"
                            <?= ($status === 'belum')
                                ? 'selected'
                                : ''; ?>
                        >
                            Belum Mengisi
                        </option>

                    </select>

                </div>


                <!-- CARI NAMA -->
                <div class="col-lg-3 col-md-6 mb-3">

                    <label class="tka-filter-label">
                        Cari Nama Siswa
                    </label>

                    <div class="tka-search-wrapper">

                        <i class="fas fa-search tka-search-icon"></i>

                        <input
                            type="text"
                            name="nama"
                            value="<?= html_escape(
                                isset($nama)
                                    ? $nama
                                    : ''
                            ); ?>"
                            class="form-control tka-filter tka-search-input"
                            placeholder="Ketik nama siswa..."
                            autocomplete="off"
                        >

                        <?php if (!empty($nama)): ?>

                            <a
                                href="<?= site_url(
                                    'Tka_admin?' .
                                    http_build_query([
                                        'tahun_id' => $tahun_id,
                                        'kelas_id' => $kelas_id,
                                        'status'   => $status
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
                        title="Filter"
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

    <!-- TABLE -->

    <div class="card tka-table-card">

        <div class="card-header">

            <h5 class="tka-table-title">

                <i class="fas fa-list mr-2"></i>

                Daftar Peserta TKA

            </h5>

        </div>


        <div class="table-responsive">

            <table class="table tka-table">

                <thead>

                    <tr>

                        <th width="45">
                            No
                        </th>

                        <th>
                            Siswa
                        </th>

                        <th>
                            Kelas
                        </th>

                        <th>
                            Pilihan 1
                        </th>

                        <th>
                            Pilihan 2
                        </th>

                        <th>
                            Status
                        </th>

                        <th width="90">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php if (empty($siswa)): ?>

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5 text-muted"
                            >

                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>

                                Tidak ada data siswa.

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php $no = 1; ?>

                        <?php foreach ($siswa as $row): ?>

                            <tr>

                                <td>
                                    <?= $no++; ?>
                                </td>


                                <td>

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

                                </td>


                                <td>

                                    <?= html_escape(
                                        $row->nama_kelas
                                    ); ?>

                                </td>


                                <td>

                                    <?php if (!empty($row->mapel_1)): ?>

                                        <span class="tka-mapel">

                                            <?= html_escape(
                                                $row->mapel_1
                                            ); ?>

                                        </span>

                                    <?php else: ?>

                                        <span class="tka-empty">
                                            Belum dipilih
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if (!empty($row->mapel_2)): ?>

                                        <span class="tka-mapel">

                                            <?= html_escape(
                                                $row->mapel_2
                                            ); ?>

                                        </span>

                                    <?php else: ?>

                                        <span class="tka-empty">
                                            Belum dipilih
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if (!empty($row->tka_id)): ?>

                                        <span class="tka-status tka-status-sudah">

                                            <i class="fas fa-check"></i>

                                            Sudah Mengisi

                                        </span>

                                    <?php else: ?>

                                        <span class="tka-status tka-status-belum">

                                            <i class="fas fa-clock"></i>

                                            Belum Mengisi

                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if (!empty($row->tka_id)): ?>

                                        <a
                                            href="<?= site_url(
                                                'Tka_admin/edit/' .
                                                $row->tka_id
                                            ); ?>"
                                            class="btn btn-sm btn-primary tka-edit-btn"
                                        >

                                            <i class="fas fa-edit"></i>

                                            Edit

                                        </a>

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