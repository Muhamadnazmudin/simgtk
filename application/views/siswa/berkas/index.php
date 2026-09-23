<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!isset($berkas) || !is_array($berkas)) {
    $berkas = [];
}

if (!isset($jenis_berkas) || !is_array($jenis_berkas)) {
    $jenis_berkas = [];
}

$total = count($jenis_berkas);

$ada = 0;

foreach ($jenis_berkas as $kode => $nama) {

    if (
        isset($berkas[$kode]) &&
        !empty($berkas[$kode])
    ) {
        $ada++;
    }
}

$persentase =
    $total > 0
    ? round(($ada / $total) * 100)
    : 0;

?>


<div class="container-fluid">


    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="mb-4">

        <h1 class="h3 text-gray-800 mb-1">

            <i class="fas fa-folder-open text-primary mr-2"></i>

            Upload Berkas

        </h1>

        <div class="small text-muted">

            Lengkapi dokumen administrasi kamu.

        </div>

    </div>


    <!-- =====================================================
         FLASH
    ====================================================== -->

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


    <!-- =====================================================
         IDENTITAS
    ====================================================== -->

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">


                <div class="col-md-8">

                    <div class="small text-muted">
                        Nama Siswa
                    </div>

                    <h5 class="font-weight-bold mb-1">

                        <?= html_escape(
                            $siswa->nama
                        ); ?>

                    </h5>


                    <div class="small text-muted">

                        NISN:

                        <strong>

                            <?= html_escape(
                                $siswa->nisn
                            ); ?>

                        </strong>

                    </div>

                </div>


                <div class="col-md-4 mt-3 mt-md-0">

                    <div class="d-flex justify-content-between">

                        <span class="small font-weight-bold">

                            Kelengkapan

                        </span>

                        <span class="small font-weight-bold">

                            <?= $ada; ?>/<?= $total; ?>

                        </span>

                    </div>


                    <div class="progress mt-2"
                         style="height:8px;">

                        <div class="progress-bar"
                             style="width:<?= $persentase; ?>%;">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         INFO
    ====================================================== -->

    <div class="alert alert-info shadow-sm">

        <i class="fas fa-info-circle mr-2"></i>

        Silakan upload dokumen sesuai jenisnya.
        Format yang diperbolehkan adalah
        <strong>PDF, JPG, JPEG, dan PNG</strong>
        dengan ukuran maksimal
        <strong>5 MB</strong> per dokumen.

    </div>


    <!-- =====================================================
         DAFTAR BERKAS
    ====================================================== -->

    <div class="card shadow-sm">

        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-primary">

                <i class="fas fa-file-alt mr-2"></i>

                Dokumen Administrasi

            </h6>

        </div>


        <div class="card-body">


            <?php foreach ($jenis_berkas as $kode => $nama): ?>


                <?php

                $item =
                    isset($berkas[$kode])
                    ? $berkas[$kode]
                    : null;

                $sudah_ada =
                    !empty($item);


                if ($sudah_ada) {

                    $item_id =
                        $item->id;

                    $nama_file =
                        $item->nama_file_asli;

                    $ukuran =
                        $item->ukuran_file;

                }

                ?>


                <div class="student-berkas-item">


                    <!-- ICON -->

                    <div class="student-berkas-icon">

                        <?php if ($sudah_ada): ?>

                            <i class="fas fa-check"></i>

                        <?php else: ?>

                            <i class="fas fa-file-alt"></i>

                        <?php endif; ?>

                    </div>


                    <!-- INFO -->

                    <div class="student-berkas-info">

                        <div class="font-weight-bold">

                            <?= html_escape(
                                $nama
                            ); ?>

                        </div>


                        <?php if ($sudah_ada): ?>

                            <div class="small text-muted">

                                <i class="fas fa-paperclip mr-1"></i>

                                <?= html_escape(
                                    $nama_file
                                ); ?>

                            </div>


                            <div class="small text-muted">

                                <?= number_format(
                                    $ukuran / 1024,
                                    1
                                ); ?>

                                KB

                            </div>

                        <?php else: ?>

                            <div class="small text-muted">

                                Belum diupload

                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- ACTION -->

                    <div class="student-berkas-action">


                        <?php if ($sudah_ada): ?>


                            <a href="<?= site_url(
                                'SiswaBerkas/lihat/' .
                                $item_id
                            ); ?>"
                               target="_blank"
                               class="btn btn-info btn-sm">

                                <i class="fas fa-eye mr-1"></i>

                                Lihat

                            </a>


                            <button type="button"
                                    class="btn btn-warning btn-sm btn-upload"
                                    data-jenis="<?= html_escape(
                                        $kode
                                    ); ?>"
                                    data-nama="<?= html_escape(
                                        $nama
                                    ); ?>"
                                    data-toggle="modal"
                                    data-target="#modalUpload">

                                <i class="fas fa-sync-alt mr-1"></i>

                                Ganti

                            </button>


                        <?php else: ?>


                            <button type="button"
                                    class="btn btn-primary btn-sm btn-upload"
                                    data-jenis="<?= html_escape(
                                        $kode
                                    ); ?>"
                                    data-nama="<?= html_escape(
                                        $nama
                                    ); ?>"
                                    data-toggle="modal"
                                    data-target="#modalUpload">

                                <i class="fas fa-upload mr-1"></i>

                                Upload

                            </button>


                        <?php endif; ?>


                    </div>


                </div>


            <?php endforeach; ?>


        </div>

    </div>

</div>



<!-- =============================================================
     MODAL
============================================================= -->

<div class="modal fade"
     id="modalUpload"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="fas fa-cloud-upload-alt mr-2"></i>

                    Upload Berkas

                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>


            <form method="post"
                  enctype="multipart/form-data"
                  action="<?= site_url(
                      'SiswaBerkas/upload'
                  ); ?>">


                <!-- CSRF lock upload berkas -->

                <input type="hidden"
                       name="<?= $this->security->get_csrf_token_name(); ?>"
                       value="<?= $this->security->get_csrf_hash(); ?>">


                <div class="modal-body">


                    <div class="form-group">

                        <label class="font-weight-bold">

                            Jenis Berkas

                        </label>

                        <input type="text"
                               id="nama_berkas"
                               class="form-control"
                               readonly>

                        <input type="hidden"
                               name="jenis_berkas"
                               id="jenis_berkas">

                    </div>


                    <div class="form-group">

                        <label class="font-weight-bold">

                            Pilih File

                        </label>

                        <input type="file"
                               name="file"
                               id="file_berkas"
                               class="form-control-file"
                               accept=".pdf,.jpg,.jpeg,.png"
                               required>

                        <small class="form-text text-muted">

                            PDF, JPG, JPEG, PNG.
                            Maksimal 5 MB.

                        </small>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-upload mr-1"></i>

                        Upload

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function()
    {

        document.querySelectorAll(
            '.btn-upload'
        ).forEach(
            function(button)
            {

                button.addEventListener(
                    'click',
                    function()
                    {

                        var jenis =
                            this.getAttribute(
                                'data-jenis'
                            );

                        var nama =
                            this.getAttribute(
                                'data-nama'
                            );


                        document.getElementById(
                            'jenis_berkas'
                        ).value = jenis;


                        document.getElementById(
                            'nama_berkas'
                        ).value = nama;


                        document.getElementById(
                            'file_berkas'
                        ).value = '';

                    }
                );

            }
        );

    }
);

</script>



<style>

.student-berkas-item {

    display: flex;

    align-items: center;

    padding: 16px;

    margin-bottom: 12px;

    border: 1px solid #e3e6f0;

    border-radius: 10px;

    transition: .2s ease;

}


.student-berkas-item:hover {

    box-shadow:
        0 3px 12px
        rgba(0,0,0,.08);

}


.student-berkas-icon {

    width: 44px;

    height: 44px;

    min-width: 44px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    margin-right: 14px;

    background: #f1f3f9;

    color: #4e73df;

}


.student-berkas-info {

    flex: 1;

    min-width: 0;

}


.student-berkas-action {

    margin-left: 12px;

}


@media(max-width:768px) {

    .student-berkas-item {

        align-items: flex-start;

        flex-wrap: wrap;

    }


    .student-berkas-action {

        width: 100%;

        margin-left: 58px;

        margin-top: 10px;

    }

}


</style>