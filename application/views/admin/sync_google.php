<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">

    <!-- PAGE HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-sync-alt mr-2 text-primary"></i>
            Sinkronisasi Google Spreadsheet
        </h1>
    </div>


    <!-- FLASH SUCCESS -->
    <?php if ($this->session->flashdata('success')): ?>

        <div class="alert alert-success alert-dismissible fade show shadow-sm">

            <i class="fas fa-check-circle mr-2"></i>

            <?= $this->session->flashdata('success'); ?>

            <button
                type="button"
                class="close"
                data-dismiss="alert"
            >
                <span>&times;</span>
            </button>

        </div>

    <?php endif; ?>


    <!-- FLASH ERROR -->
    <?php if ($this->session->flashdata('error')): ?>

        <div class="alert alert-danger alert-dismissible fade show shadow-sm">

            <i class="fas fa-exclamation-circle mr-2"></i>

            <?= $this->session->flashdata('error'); ?>

            <button
                type="button"
                class="close"
                data-dismiss="alert"
            >
                <span>&times;</span>
            </button>

        </div>

    <?php endif; ?>


    <!-- CARD STATUS -->
    <div class="card shadow mb-4">

        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-cloud-upload-alt mr-2"></i>
                Status Sinkronisasi
            </h6>

        </div>


        <div class="card-body">

            <div class="row align-items-center">

                <!-- INFORMASI -->
                <div class="col-md-8 mb-4 mb-md-0">

                    <h5 class="font-weight-bold text-gray-800">
                        Data Menunggu Sinkronisasi
                    </h5>

                    <p class="text-muted mb-3">

                        Perubahan biodata dan berkas siswa
                        disimpan terlebih dahulu di database.

                        <br>

                        Data akan dikirim ke Google Spreadsheet
                        ketika Admin melakukan sinkronisasi.

                    </p>


                    <div class="small text-muted">

                        <i class="fas fa-info-circle mr-1"></i>

                        Sinkronisasi dilakukan secara manual oleh Admin.

                    </div>

                </div>


                <!-- JUMLAH PENDING -->
                <div class="col-md-4 text-center">

                    <div class="mb-3">

                        <div class="h1 font-weight-bold text-primary mb-0">

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

                            <i class="fas fa-sync-alt mr-2"></i>

                            Sinkronkan Sekarang

                        </a>


                    <?php else: ?>

                        <button
                            class="btn btn-success btn-lg shadow-sm"
                            disabled
                        >

                            <i class="fas fa-check-circle mr-2"></i>

                            Semua Sudah Sinkron

                        </button>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>


    <!-- INFORMASI SISTEM -->
    <div class="card shadow mb-4">

        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-primary">

                <i class="fas fa-info-circle mr-2"></i>

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

                        <span class="badge badge-warning px-3 py-2">
                            Menunggu Sinkronisasi
                        </span>

                    <?php else: ?>

                        <span class="badge badge-success px-3 py-2">
                            Sinkron
                        </span>

                    <?php endif; ?>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Data Pending
                    </div>

                    <div class="font-weight-bold text-gray-800">

                        <?= (int) $pending; ?> siswa

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Tujuan
                    </div>

                    <div class="font-weight-bold text-gray-800">

                        Google Spreadsheet

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>