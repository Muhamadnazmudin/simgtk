<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">

    <!-- ==========================================
         JUDUL
    =========================================== -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-sync-alt text-primary"></i>
            Sinkronisasi Google Spreadsheet
        </h1>

    </div>


    <!-- ==========================================
         FLASH MESSAGE
    =========================================== -->

    <?php if ($this->session->flashdata('success')): ?>

        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= $this->session->flashdata('success'); ?>
        </div>

    <?php endif; ?>


    <?php if ($this->session->flashdata('error')): ?>

        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?= $this->session->flashdata('error'); ?>
        </div>

    <?php endif; ?>


    <!-- ==========================================
         STATUS
    =========================================== -->

    <div class="card shadow mb-4">

        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-cloud-upload-alt"></i>
                Status Sinkronisasi
            </h6>

        </div>


        <div class="card-body">

            <div class="row align-items-center">


                <!-- INFO -->
                <div class="col-md-7">

                    <h4 class="font-weight-bold text-gray-800">
                        Data Menunggu Sinkronisasi
                    </h4>

                    <p class="text-muted mb-3">

                        Perubahan biodata dan berkas siswa
                        disimpan terlebih dahulu di database.

                        Data akan dikirim ke Google Spreadsheet
                        ketika Admin melakukan sinkronisasi.

                    </p>

                    <small class="text-muted">

                        <i class="fas fa-info-circle"></i>

                        Sinkronisasi dilakukan secara manual oleh Admin.

                    </small>

                </div>


                <!-- ANGKA -->
                <div class="col-md-5 text-center">

                    <div class="mb-3">

                        <div
                            class="display-4 font-weight-bold text-primary"
                        >
                            <?= (int) $pending; ?>
                        </div>

                        <div class="text-muted">
                            siswa menunggu sinkronisasi
                        </div>

                    </div>


                    <?php if ($pending > 0): ?>

                        <a
                            href="<?= site_url('Sync_google/proses'); ?>"
                            class="btn btn-primary btn-lg shadow-sm"
                            onclick="
                                return confirm(
                                    'Sinkronkan <?= (int) $pending; ?> siswa ke Google Spreadsheet sekarang?'
                                );
                            "
                        >

                            <i class="fas fa-sync-alt"></i>

                            Sinkronkan Sekarang

                        </a>

                    <?php else: ?>

                        <button
                            class="btn btn-secondary btn-lg"
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



    <!-- ==========================================
         RINGKASAN PERUBAHAN
    =========================================== -->

    <?php if ($pending > 0): ?>

    <div class="row mb-4">


        <!-- BIODATA -->

        <div class="col-md-6 mb-3">

            <div class="card shadow h-100 border-left-primary">

                <div class="card-body">

                    <div class="row align-items-center">

                        <div class="col">

                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">

                                Perubahan Biodata

                            </div>

                            <div class="h3 mb-0 font-weight-bold text-gray-800">

                                <?= (int) $jumlah_biodata; ?>

                                <small class="text-muted">
                                    siswa
                                </small>

                            </div>

                        </div>


                        <div class="col-auto">

                            <i class="fas fa-user-edit fa-2x text-gray-300"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- BERKAS -->

        <div class="col-md-6 mb-3">

            <div class="card shadow h-100 border-left-success">

                <div class="card-body">

                    <div class="row align-items-center">

                        <div class="col">

                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">

                                Berkas Akan Disinkronkan

                            </div>

                            <div class="h3 mb-0 font-weight-bold text-gray-800">

                                <?= (int) $jumlah_berkas; ?>

                                <small class="text-muted">
                                    file
                                </small>

                            </div>

                        </div>


                        <div class="col-auto">

                            <i class="fas fa-file-upload fa-2x text-gray-300"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php endif; ?>



    <!-- ==========================================
         DETAIL SISWA
    =========================================== -->

    <?php if (!empty($pending_detail)): ?>

    <div class="card shadow mb-4">

        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-primary">

                <i class="fas fa-list"></i>

                Detail Data yang Akan Disinkronkan

            </h6>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

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

                                    <span class="badge badge-primary">

                                        <i class="fas fa-edit"></i>

                                        Berubah

                                    </span>

                                <?php else: ?>

                                    <span class="badge badge-secondary">

                                        Tidak berubah

                                    </span>

                                <?php endif; ?>

                            </td>


                            <!-- BERKAS -->

                            <td class="text-center">

                                <?php if ((int) $item->berkas_pending === 1): ?>

                                    <span class="badge badge-success">

                                        <i class="fas fa-file"></i>

                                        <?= (int) $item->jumlah_berkas; ?>
                                        file

                                    </span>

                                <?php else: ?>

                                    <span class="badge badge-secondary">

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



    <!-- ==========================================
         INFORMASI
    =========================================== -->

    <div class="card shadow mb-4">

        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-primary">

                <i class="fas fa-info-circle"></i>

                Informasi

            </h6>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">

                        Status

                    </div>

                    <?php if ($pending > 0): ?>

                        <span class="badge badge-warning p-2">

                            Menunggu Sinkronisasi

                        </span>

                    <?php else: ?>

                        <span class="badge badge-success p-2">

                            Semua Sudah Sinkron

                        </span>

                    <?php endif; ?>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">

                        Data Pending

                    </div>

                    <strong>
                        <?= (int) $pending; ?>
                    </strong>

                    siswa

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">

                        Tujuan

                    </div>

                    <strong>
                        Google Spreadsheet
                    </strong>

                </div>

            </div>

        </div>

    </div>

</div>