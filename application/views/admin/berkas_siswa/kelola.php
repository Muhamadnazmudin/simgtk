<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| NORMALISASI DATA
|--------------------------------------------------------------------------
*/

if (!isset($berkas) || !is_array($berkas)) {
    $berkas = [];
}

if (!isset($jenis_berkas) || !is_array($jenis_berkas)) {
    $jenis_berkas = [];
}


/*
|--------------------------------------------------------------------------
| KELENGKAPAN BERKAS
|--------------------------------------------------------------------------
*/

$total_berkas  = count($jenis_berkas);
$jumlah_berkas = 0;

foreach ($jenis_berkas as $kode => $nama) {

    if (
        isset($berkas[$kode]) &&
        !empty($berkas[$kode])
    ) {
        $jumlah_berkas++;
    }
}

$persentase = 0;

if ($total_berkas > 0) {

    $persentase = round(
        ($jumlah_berkas / $total_berkas) * 100
    );
}


/*
|--------------------------------------------------------------------------
| DATA SISWA
|--------------------------------------------------------------------------
*/

$nama_siswa = !empty($siswa->nama)
    ? $siswa->nama
    : '-';

$nis = !empty($siswa->nis)
    ? $siswa->nis
    : '-';

$nisn = !empty($siswa->nisn)
    ? $siswa->nisn
    : '-';

$status = !empty($siswa->status)
    ? $siswa->status
    : '-';

$nama_kelas = !empty($siswa->nama_kelas)
    ? $siswa->nama_kelas
    : '';


/*
|--------------------------------------------------------------------------
| NOMOR HP
|--------------------------------------------------------------------------
*/

$hp = !empty($siswa->hp)
    ? trim($siswa->hp)
    : '';

$wa_number = preg_replace(
    '/[^0-9]/',
    '',
    $hp
);


/*
|--------------------------------------------------------------------------
| NORMALISASI WHATSAPP
|--------------------------------------------------------------------------
|
| 08xxxxxxxxxx
| menjadi
| 628xxxxxxxxxx
|
*/

if (
    !empty($wa_number) &&
    substr($wa_number, 0, 1) === '0'
) {

    $wa_number =
        '62' .
        substr($wa_number, 1);
}


/*
|--------------------------------------------------------------------------
| URL WHATSAPP
|--------------------------------------------------------------------------
*/

$wa_message =
    'Assalamualaikum, ' .
    $nama_siswa;

$wa_url = '';

if (!empty($wa_number)) {

    $wa_url =
        'https://wa.me/' .
        $wa_number .
        '?text=' .
        rawurlencode(
            $wa_message
        );
}


/*
|--------------------------------------------------------------------------
| FOTO SISWA
|--------------------------------------------------------------------------
*/

$foto_url = '';

if (!empty($siswa->foto)) {

    $foto_url = base_url(
        'uploads/foto/' .
        $siswa->foto
    );
}

?>


<div class="container-fluid berkas-page">


    <!-- ============================================================
         HEADER
    ============================================================= -->

    <div class="page-header d-flex align-items-center justify-content-between mb-4">

        <div>

            <h1 class="page-title">

                <i class="fas fa-folder-open mr-2"></i>

                Berkas Siswa

            </h1>

            <div class="page-subtitle">

                Kelola dokumen administrasi siswa

            </div>

        </div>


        <a href="<?= site_url('berkas_siswa'); ?>"
           class="btn btn-secondary btn-sm shadow-sm">

            <i class="fas fa-arrow-left mr-1"></i>

            Kembali

        </a>

    </div>



    <!-- ============================================================
         FLASH SUCCESS
    ============================================================= -->

    <?php if ($this->session->flashdata('success')): ?>

        <div class="alert alert-success alert-dismissible fade show shadow-sm">

            <i class="fas fa-check-circle mr-2"></i>

            <?= $this->session->flashdata('success'); ?>

            <button type="button"
                    class="close"
                    data-dismiss="alert">

                <span>&times;</span>

            </button>

        </div>

    <?php endif; ?>



    <!-- ============================================================
         FLASH ERROR
    ============================================================= -->

    <?php if ($this->session->flashdata('error')): ?>

        <div class="alert alert-danger alert-dismissible fade show shadow-sm">

            <i class="fas fa-exclamation-circle mr-2"></i>

            <?= $this->session->flashdata('error'); ?>

            <button type="button"
                    class="close"
                    data-dismiss="alert">

                <span>&times;</span>

            </button>

        </div>

    <?php endif; ?>



    <!-- ============================================================
         IDENTITAS SISWA
    ============================================================= -->

    <div class="card identity-card shadow-sm mb-4">


        <!-- HEADER -->

        <div class="card-header identity-header">

            <div class="d-flex align-items-center">

                <div class="identity-header-icon">

                    <i class="fas fa-user-graduate"></i>

                </div>

                <div>

                    <h6 class="identity-title">

                        Identitas Siswa

                    </h6>

                    <div class="identity-subtitle">

                        Informasi siswa dan kelengkapan dokumen

                    </div>

                </div>

            </div>

        </div>



        <!-- BODY -->

        <div class="card-body identity-body">


            <div class="row align-items-start">


                <!-- ==================================================
                     FOTO
                =================================================== -->

                <div class="col-lg-2 col-md-3 text-center mb-4 mb-md-0">

                    <div class="student-photo-wrapper">


                        <?php if ($foto_url): ?>

                            <img src="<?= html_escape($foto_url); ?>"
                                 alt="Foto <?= html_escape($nama_siswa); ?>"
                                 class="student-photo">

                        <?php else: ?>

                            <div class="student-photo student-photo-empty">

                                <i class="fas fa-user"></i>

                            </div>

                        <?php endif; ?>


                    </div>

                </div>



                <!-- ==================================================
                     DATA SISWA
                =================================================== -->

                <div class="col-lg-10 col-md-9">


                    <div class="student-info-grid">


                        <!-- NAMA -->

                        <div class="student-info-item">

                            <div class="student-label">

                                Nama Siswa

                            </div>

                            <div class="student-value student-name">

                                <?= html_escape(
                                    $nama_siswa
                                ); ?>

                            </div>

                        </div>



                        <!-- NIS -->

                        <div class="student-info-item">

                            <div class="student-label">

                                NIS

                            </div>

                            <div class="student-value">

                                <?= html_escape(
                                    $nis
                                ); ?>

                            </div>

                        </div>



                        <!-- NISN -->

                        <div class="student-info-item">

                            <div class="student-label">

                                NISN

                            </div>

                            <div class="student-value">

                                <?= html_escape(
                                    $nisn
                                ); ?>

                            </div>

                        </div>



                        <!-- KELAS -->

                        <?php if ($nama_kelas !== ''): ?>

                            <div class="student-info-item">

                                <div class="student-label">

                                    Kelas

                                </div>

                                <div class="student-value">

                                    <?= html_escape(
                                        $nama_kelas
                                    ); ?>

                                </div>

                            </div>

                        <?php endif; ?>



                        <!-- STATUS -->

                        <div class="student-info-item">

                            <div class="student-label">

                                Status

                            </div>

                            <div>

                                <?php if (
                                    strtolower($status) === 'aktif'
                                ): ?>

                                    <span class="badge badge-success status-badge">

                                        <i class="fas fa-check-circle mr-1"></i>

                                        Aktif

                                    </span>

                                <?php else: ?>

                                    <span class="badge badge-secondary status-badge">

                                        <?= ucfirst(
                                            html_escape(
                                                $status
                                            )
                                        ); ?>

                                    </span>

                                <?php endif; ?>

                            </div>

                        </div>



                        <!-- ==================================================
                             HP
                        =================================================== -->

                        <div class="student-info-item">

                            <div class="student-label">

                                Nomor HP

                            </div>


                            <?php if ($hp): ?>

                                <div class="phone-wrapper">

                                    <div class="student-value phone-number">

                                        <i class="fas fa-phone-alt mr-1 text-primary"></i>

                                        <?= html_escape(
                                            $hp
                                        ); ?>

                                    </div>


                                    <?php if ($wa_url): ?>

                                        <a href="<?= html_escape($wa_url); ?>"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="btn btn-success btn-sm whatsapp-button">

                                            <i class="fab fa-whatsapp mr-1"></i>

                                            WhatsApp

                                        </a>

                                    <?php endif; ?>

                                </div>

                            <?php else: ?>

                                <div class="phone-empty">

                                    <i class="fas fa-phone-slash mr-1"></i>

                                    Nomor HP belum diisi

                                </div>

                            <?php endif; ?>

                        </div>


                    </div>



                    <!-- ==================================================
                         PROGRESS BERKAS
                    =================================================== -->

                    <div class="document-progress mt-4">


                        <div class="progress-info">

                            <div class="progress-title">

                                <i class="fas fa-file-alt mr-1"></i>

                                Kelengkapan Berkas

                            </div>


                            <div class="progress-count">

                                <?= $jumlah_berkas; ?>

                                /

                                <?= $total_berkas; ?>

                                <span>
                                    (<?= $persentase; ?>%)
                                </span>

                            </div>

                        </div>


                        <div class="progress progress-document">

                            <div class="progress-bar"
                                 role="progressbar"
                                 style="width:<?= $persentase; ?>%;"
                                 aria-valuenow="<?= $persentase; ?>"
                                 aria-valuemin="0"
                                 aria-valuemax="100">

                            </div>

                        </div>


                    </div>


                </div>


            </div>


        </div>


    </div>



    <!-- ============================================================
         DAFTAR BERKAS
    ============================================================= -->

    <div class="card shadow-sm document-card">


        <!-- HEADER -->

        <div class="card-header document-header">

            <div class="d-flex align-items-center justify-content-between">


                <div class="d-flex align-items-center">

                    <div class="document-header-icon">

                        <i class="fas fa-file-alt"></i>

                    </div>

                    <div>

                        <h6 class="document-title">

                            Daftar Berkas

                        </h6>

                        <div class="document-subtitle">

                            Dokumen administrasi siswa

                        </div>

                    </div>

                </div>


                <div class="document-counter">

                    <?= $jumlah_berkas; ?>

                    /

                    <?= $total_berkas; ?>

                </div>


            </div>

        </div>



        <!-- BODY -->

        <div class="card-body document-body">


            <?php if (empty($jenis_berkas)): ?>

                <div class="alert alert-warning mb-0">

                    <i class="fas fa-exclamation-triangle mr-2"></i>

                    Daftar jenis berkas belum tersedia.

                </div>

            <?php endif; ?>



            <?php foreach (
                $jenis_berkas
                as $kode => $nama
            ): ?>


                <?php

                /*
                |--------------------------------------------------------------------------
                | AMBIL BERKAS
                |--------------------------------------------------------------------------
                */

                $item = null;

                if (
                    isset($berkas[$kode]) &&
                    !empty($berkas[$kode])
                ) {

                    $item =
                        $berkas[$kode];
                }


                $sudah_ada =
                    !empty($item);


                /*
                |--------------------------------------------------------------------------
                | DATA ITEM
                |--------------------------------------------------------------------------
                */

                $item_id = '';
                $nama_file = '';
                $nama_file_asli = '';
                $ukuran_file = 0;


                if ($sudah_ada) {

                    if (is_object($item)) {

                        $item_id =
                            $item->id;

                        $nama_file =
                            $item->nama_file;

                        $nama_file_asli =
                            $item->nama_file_asli;

                        $ukuran_file =
                            $item->ukuran_file;

                    } else {

                        $item_id =
                            $item['id'];

                        $nama_file =
                            $item['nama_file'];

                        $nama_file_asli =
                            $item['nama_file_asli'];

                        $ukuran_file =
                            $item['ukuran_file'];
                    }
                }

                ?>


                <!-- =================================================
                     BERKAS ITEM
                ================================================== -->

                <div class="document-item">


                    <div class="row align-items-center">


                        <!-- =================================================
                             ICON + INFORMASI
                        ================================================= -->

                        <div class="col-xl-5 col-lg-5 col-md-5 mb-3 mb-md-0">


                            <div class="document-main">


                                <!-- ICON -->

                                <div class="document-icon-wrapper">

                                    <?php if ($sudah_ada): ?>

                                        <div class="document-icon document-icon-success">

                                            <i class="fas fa-check"></i>

                                        </div>

                                    <?php else: ?>

                                        <div class="document-icon document-icon-empty">

                                            <i class="fas fa-file-alt"></i>

                                        </div>

                                    <?php endif; ?>

                                </div>



                                <!-- INFORMASI -->

                                <div class="document-info">


                                    <div class="document-name">

                                        <?= html_escape(
                                            $nama
                                        ); ?>

                                    </div>


                                    <?php if ($sudah_ada): ?>

                                        <div class="document-file-name"
                                             title="<?= html_escape(
                                                $nama_file_asli
                                             ); ?>">

                                            <i class="fas fa-paperclip mr-1"></i>

                                            <?= html_escape(
                                                $nama_file_asli
                                            ); ?>

                                        </div>


                                        <?php if ($ukuran_file): ?>

                                            <div class="document-size">

                                                <?= number_format(
                                                    $ukuran_file / 1024,
                                                    1
                                                ); ?>

                                                KB

                                            </div>

                                        <?php endif; ?>


                                    <?php else: ?>

                                        <div class="document-empty-text">

                                            Belum ada berkas

                                        </div>

                                    <?php endif; ?>


                                </div>


                            </div>


                        </div>



                        <!-- =================================================
                             STATUS
                        ================================================== -->

                        <div class="col-xl-2 col-lg-2 col-md-3 mb-3 mb-md-0">


                            <?php if ($sudah_ada): ?>

                                <span class="document-status document-status-success">

                                    <i class="fas fa-check mr-1"></i>

                                    Sudah Ada

                                </span>

                            <?php else: ?>

                                <span class="document-status document-status-empty">

                                    <i class="fas fa-times mr-1"></i>

                                    Belum Ada

                                </span>

                            <?php endif; ?>


                        </div>



                        <!-- =================================================
                             ACTION
                        ================================================== -->

                        <div class="col-xl-5 col-lg-5 col-md-4">


                            <div class="document-actions">


                                <?php if ($sudah_ada): ?>


                                    <!-- LIHAT -->

                                    <a href="<?= site_url(
                                        'berkas_siswa/lihat/' .
                                        $item_id
                                    ); ?>"
                                       target="_blank"
                                       class="btn btn-info btn-sm action-button">

                                        <i class="fas fa-eye mr-1"></i>

                                        Lihat

                                    </a>



                                    <!-- DOWNLOAD -->

                                    <a href="<?= site_url(
                                        'berkas_siswa/download/' .
                                        $item_id
                                    ); ?>"
                                       class="btn btn-secondary btn-sm action-button">

                                        <i class="fas fa-download mr-1"></i>

                                        Download

                                    </a>



                                    <!-- GANTI -->

                                    <button type="button"
                                            class="btn btn-warning btn-sm action-button"
                                            data-toggle="modal"
                                            data-target="#modalUpload"
                                            data-jenis="<?= html_escape(
                                                $kode
                                            ); ?>"
                                            data-nama="<?= html_escape(
                                                $nama
                                            ); ?>"
                                            onclick="pilihBerkas(this)">

                                        <i class="fas fa-sync-alt mr-1"></i>

                                        Ganti

                                    </button>



                                    <!-- HAPUS -->

                                    <a href="<?= site_url(
                                        'berkas_siswa/hapus/' .
                                        $item_id
                                    ); ?>"
                                       class="btn btn-danger btn-sm action-button"
                                       onclick="return confirm(
                                           'Yakin ingin menghapus berkas <?= html_escape(
                                               $nama
                                           ); ?>?'
                                       );">

                                        <i class="fas fa-trash mr-1"></i>

                                        Hapus

                                    </a>


                                <?php else: ?>


                                    <!-- UPLOAD -->

                                    <button type="button"
                                            class="btn btn-primary btn-sm action-button"
                                            data-toggle="modal"
                                            data-target="#modalUpload"
                                            data-jenis="<?= html_escape(
                                                $kode
                                            ); ?>"
                                            data-nama="<?= html_escape(
                                                $nama
                                            ); ?>"
                                            onclick="pilihBerkas(this)">

                                        <i class="fas fa-upload mr-1"></i>

                                        Upload

                                    </button>


                                <?php endif; ?>


                            </div>


                        </div>


                    </div>


                </div>


            <?php endforeach; ?>


        </div>


    </div>


</div>



<!-- ================================================================
     MODAL UPLOAD
================================================================ -->

<div class="modal fade"
     id="modalUpload"
     tabindex="-1"
     role="dialog"
     aria-labelledby="modalUploadLabel"
     aria-hidden="true">


    <div class="modal-dialog modal-dialog-centered"
         role="document">


        <div class="modal-content">


            <!-- HEADER -->

            <div class="modal-header">


                <div>

                    <h5 class="modal-title"
                        id="modalUploadLabel">

                        <i class="fas fa-cloud-upload-alt mr-2"></i>

                        Upload Berkas

                    </h5>

                    <div class="modal-subtitle">

                        Tambahkan dokumen administrasi siswa

                    </div>

                </div>


                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">

                    <span aria-hidden="true">

                        &times;

                    </span>

                </button>


            </div>



            <!-- FORM -->

            <form method="post"
                  enctype="multipart/form-data"
                  action="<?= site_url(
                      'berkas_siswa/upload/' .
                      $siswa->id
                  ); ?>">


                <!-- CSRF -->

                <input type="hidden"
                       name="<?= $this->security->get_csrf_token_name(); ?>"
                       value="<?= $this->security->get_csrf_hash(); ?>">


                <div class="modal-body">


                    <!-- JENIS BERKAS -->

                    <div class="form-group">


                        <label class="form-label">

                            Jenis Berkas

                        </label>


                        <input type="text"
                               id="nama_berkas"
                               class="form-control"
                               readonly
                               placeholder="Pilih jenis berkas">


                        <input type="hidden"
                               name="jenis_berkas"
                               id="jenis_berkas">


                    </div>



                    <!-- FILE -->

                    <div class="form-group mb-0">


                        <label class="form-label">

                            File Berkas

                        </label>


                        <div class="custom-file">


                            <input type="file"
                                   name="file"
                                   id="file_berkas"
                                   class="custom-file-input"
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   required>


                            <label class="custom-file-label"
                                   for="file_berkas">

                                Pilih file...

                            </label>


                        </div>


                        <div class="upload-info mt-2">

                            <i class="fas fa-info-circle mr-1"></i>

                            Format:

                            <strong>
                                PDF, JPG, JPEG, PNG
                            </strong>

                            <br>

                            Maksimal:

                            <strong>
                                5 MB
                            </strong>

                        </div>


                    </div>


                </div>



                <!-- FOOTER -->

                <div class="modal-footer">


                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">

                        <i class="fas fa-times mr-1"></i>

                        Batal

                    </button>


                    <button type="submit"
                            class="btn btn-primary"
                            id="btnSubmitUpload">

                        <i class="fas fa-upload mr-1"></i>

                        Upload Berkas

                    </button>


                </div>


            </form>


        </div>


    </div>

</div>



<script>

/*
|--------------------------------------------------------------------------
| PILIH BERKAS
|--------------------------------------------------------------------------
*/

function pilihBerkas(button)
{

    var jenis =
        button.getAttribute(
            'data-jenis'
        );

    var nama =
        button.getAttribute(
            'data-nama'
        );


    var jenisInput =
        document.getElementById(
            'jenis_berkas'
        );

    var namaInput =
        document.getElementById(
            'nama_berkas'
        );

    var fileInput =
        document.getElementById(
            'file_berkas'
        );

    var fileLabel =
        document.querySelector(
            'label[for="file_berkas"]'
        );


    if (jenisInput) {

        jenisInput.value =
            jenis || '';

    }


    if (namaInput) {

        namaInput.value =
            nama || '';

    }


    if (fileInput) {

        fileInput.value =
            '';

    }


    if (fileLabel) {

        fileLabel.textContent =
            'Pilih file...';

    }


    var modalTitle =
        document.getElementById(
            'modalUploadLabel'
        );


    if (modalTitle) {

        modalTitle.innerHTML =
            '<i class="fas fa-cloud-upload-alt mr-2"></i>' +
            'Upload ' +
            (nama || 'Berkas');

    }

}



/*
|--------------------------------------------------------------------------
| TAMPILKAN NAMA FILE
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'change',
    function(event)
    {

        if (
            !event.target ||
            event.target.id !== 'file_berkas'
        ) {
            return;
        }


        var fileName =
            event.target.files &&
            event.target.files.length
                ? event.target.files[0].name
                : 'Pilih file...';


        var label =
            document.querySelector(
                'label[for="file_berkas"]'
            );


        if (label) {

            label.textContent =
                fileName;

        }

    }
);



/*
|--------------------------------------------------------------------------
| RESET MODAL
|--------------------------------------------------------------------------
*/

$('#modalUpload').on(
    'hidden.bs.modal',
    function()
    {

        var jenisInput =
            document.getElementById(
                'jenis_berkas'
            );

        var namaInput =
            document.getElementById(
                'nama_berkas'
            );

        var fileInput =
            document.getElementById(
                'file_berkas'
            );

        var fileLabel =
            document.querySelector(
                'label[for="file_berkas"]'
            );

        var modalTitle =
            document.getElementById(
                'modalUploadLabel'
            );


        if (jenisInput) {

            jenisInput.value =
                '';

        }


        if (namaInput) {

            namaInput.value =
                '';

        }


        if (fileInput) {

            fileInput.value =
                '';

        }


        if (fileLabel) {

            fileLabel.textContent =
                'Pilih file...';

        }


        if (modalTitle) {

            modalTitle.innerHTML =
                '<i class="fas fa-cloud-upload-alt mr-2"></i>' +
                'Upload Berkas';

        }

    }
);



/*
|--------------------------------------------------------------------------
| VALIDASI SUBMIT
|--------------------------------------------------------------------------
*/

$('#modalUpload form').on(
    'submit',
    function(event)
    {

        var jenis =
            document.getElementById(
                'jenis_berkas'
            );


        if (
            !jenis ||
            !jenis.value
        ) {

            event.preventDefault();


            alert(
                'Jenis berkas belum dipilih.'
            );


            return false;

        }


        var button =
            document.getElementById(
                'btnSubmitUpload'
            );


        if (button) {

            button.disabled =
                true;

            button.innerHTML =
                '<i class="fas fa-spinner fa-spin mr-1"></i>' +
                'Mengupload...';

        }

    }
);

</script>



<style>

/*
|--------------------------------------------------------------------------
| PAGE
|--------------------------------------------------------------------------
*/

.berkas-page {

    padding-bottom: 30px;

}


.page-title {

    font-size: 1.5rem;

    font-weight: 700;

    margin: 0;

    color: #4e73df;

}


.page-subtitle {

    font-size: .85rem;

    color: #858796;

    margin-top: 3px;

}



/*
|--------------------------------------------------------------------------
| IDENTITY CARD
|--------------------------------------------------------------------------
*/

.identity-card,
.document-card {

    border: 0;

    border-radius: 10px;

    overflow: hidden;

}


.identity-header,
.document-header {

    background: #fff;

    border-bottom: 1px solid #e3e6f0;

    padding: 16px 20px;

}


.identity-header-icon,
.document-header-icon {

    width: 38px;

    height: 38px;

    border-radius: 9px;

    background: rgba(78,115,223,.10);

    color: #4e73df;

    display: flex;

    align-items: center;

    justify-content: center;

    margin-right: 12px;

}


.identity-title,
.document-title {

    color: #4e73df;

    font-size: .95rem;

    font-weight: 700;

    margin: 0;

}


.identity-subtitle,
.document-subtitle {

    font-size: .75rem;

    color: #858796;

    margin-top: 2px;

}


.identity-body {

    padding: 24px;

}



/*
|--------------------------------------------------------------------------
| FOTO
|--------------------------------------------------------------------------
*/

.student-photo-wrapper {

    display: flex;

    justify-content: center;

}


.student-photo {

    width: 110px;

    height: 140px;

    object-fit: cover;

    border-radius: 8px;

    border: 3px solid #fff;

    box-shadow:
        0 3px 12px rgba(0,0,0,.12);

}


.student-photo-empty {

    background: #f8f9fc;

    color: #b7b9cc;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 2.5rem;

}



/*
|--------------------------------------------------------------------------
| DATA SISWA
|--------------------------------------------------------------------------
*/

.student-info-grid {

    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));

    gap: 18px 24px;

}


.student-info-item {

    min-width: 0;

}


.student-label {

    font-size: .72rem;

    color: #858796;

    margin-bottom: 4px;

}


.student-value {

    font-size: .9rem;

    font-weight: 700;

    color: #3a3b45;

    word-break: break-word;

}


.student-name {

    text-transform: uppercase;

}


.status-badge {

    font-size: .72rem;

    padding: 5px 9px;

}



/*
|--------------------------------------------------------------------------
| HP
|--------------------------------------------------------------------------
*/

.phone-wrapper {

    display: flex;

    align-items: center;

    flex-wrap: wrap;

    gap: 7px;

}


.phone-number {

    white-space: nowrap;

}


.whatsapp-button {

    font-size: .72rem;

    padding: 4px 8px;

    border-radius: 6px;

    white-space: nowrap;

}


.phone-empty {

    font-size: .82rem;

    color: #858796;

}



/*
|--------------------------------------------------------------------------
| PROGRESS
|--------------------------------------------------------------------------
*/

.document-progress {

    padding-top: 4px;

}


.progress-info {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 7px;

}


.progress-title {

    font-size: .78rem;

    font-weight: 700;

    color: #5a5c69;

}


.progress-count {

    font-size: .78rem;

    font-weight: 700;

    color: #5a5c69;

}


.progress-count span {

    font-weight: 600;

    color: #858796;

}


.progress-document {

    height: 9px;

    border-radius: 20px;

    background: #eaecf4;

    overflow: hidden;

}


.progress-document .progress-bar {

    border-radius: 20px;

    background: #4e73df;

}



/*
|--------------------------------------------------------------------------
| DOCUMENT HEADER
|--------------------------------------------------------------------------
*/

.document-counter {

    background: #4e73df;

    color: #fff;

    font-size: .75rem;

    font-weight: 700;

    padding: 5px 10px;

    border-radius: 20px;

}


.document-body {

    padding: 18px;

}



/*
|--------------------------------------------------------------------------
| DOCUMENT ITEM
|--------------------------------------------------------------------------
*/

.document-item {

    border: 1px solid #e3e6f0;

    border-radius: 10px;

    padding: 16px;

    margin-bottom: 12px;

    transition:
        box-shadow .2s ease,
        transform .2s ease,
        border-color .2s ease;

}


.document-item:last-child {

    margin-bottom: 0;

}


.document-item:hover {

    border-color: #d4d8e5;

    box-shadow:
        0 4px 15px rgba(0,0,0,.06);

    transform: translateY(-1px);

}


.document-main {

    display: flex;

    align-items: center;

    min-width: 0;

}


.document-icon-wrapper {

    flex: 0 0 auto;

    margin-right: 13px;

}


.document-icon {

    width: 43px;

    height: 43px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

}


.document-icon-success {

    background: rgba(28,200,138,.12);

    color: #1cc88a;

}


.document-icon-empty {

    background: #f1f3f9;

    color: #858796;

}


.document-info {

    min-width: 0;

}


.document-name {

    font-size: .88rem;

    font-weight: 700;

    color: #3a3b45;

    margin-bottom: 3px;

}


.document-file-name {

    max-width: 330px;

    overflow: hidden;

    white-space: nowrap;

    text-overflow: ellipsis;

    font-size: .75rem;

    color: #858796;

}


.document-size {

    font-size: .7rem;

    color: #858796;

    margin-top: 2px;

}


.document-empty-text {

    font-size: .75rem;

    color: #858796;

}



/*
|--------------------------------------------------------------------------
| STATUS BERKAS
|--------------------------------------------------------------------------
*/

.document-status {

    display: inline-flex;

    align-items: center;

    font-size: .72rem;

    font-weight: 700;

    padding: 7px 10px;

    border-radius: 6px;

}


.document-status-success {

    background: rgba(28,200,138,.12);

    color: #159a6b;

}


.document-status-empty {

    background: #f1f3f9;

    color: #858796;

}



/*
|--------------------------------------------------------------------------
| ACTION
|--------------------------------------------------------------------------
*/

.document-actions {

    display: flex;

    align-items: center;

    justify-content: flex-end;

    flex-wrap: wrap;

    gap: 5px;

}


.action-button {

    white-space: nowrap;

    border-radius: 6px;

}



/*
|--------------------------------------------------------------------------
| MODAL
|--------------------------------------------------------------------------
*/

.modal-content {

    border: 0;

    border-radius: 12px;

    overflow: hidden;

}


.modal-header {

    padding: 18px 20px;

    border-bottom: 1px solid #e3e6f0;

}


.modal-title {

    font-size: 1rem;

    font-weight: 700;

    color: #4e73df;

}


.modal-subtitle {

    font-size: .75rem;

    color: #858796;

    margin-top: 3px;

}


.form-label {

    font-size: .82rem;

    font-weight: 700;

    margin-bottom: 6px;

}


.upload-info {

    font-size: .75rem;

    color: #858796;

    line-height: 1.6;

}


.custom-file-label {

    overflow: hidden;

    white-space: nowrap;

    text-overflow: ellipsis;

}



/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 991.98px) {

    .student-info-grid {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

    }


    .document-actions {

        justify-content: flex-start;

    }

}


@media (max-width: 767.98px) {

    .page-header {

        align-items: flex-start !important;

        gap: 12px;

    }


    .page-title {

        font-size: 1.25rem;

    }


    .identity-body {

        padding: 18px;

    }


    .student-info-grid {

        grid-template-columns: 1fr;

        gap: 14px;

    }


    .student-photo {

        width: 100px;

        height: 125px;

    }


    .progress-info {

        align-items: flex-start;

        gap: 8px;

    }


    .document-item {

        padding: 14px;

    }


    .document-actions {

        justify-content: flex-start;

        margin-top: 4px;

    }


    .document-actions .btn {

        margin-bottom: 3px;

    }


    .document-file-name {

        max-width: 230px;

    }


    .phone-wrapper {

        align-items: flex-start;

    }

}


@media (max-width: 575.98px) {

    .page-header {

        display: block !important;

    }


    .page-header > a {

        display: inline-block;

        margin-top: 12px;

    }


    .identity-header,
    .document-header {

        padding: 14px 16px;

    }


    .document-body {

        padding: 12px;

    }


    .document-header-icon,
    .identity-header-icon {

        width: 34px;

        height: 34px;

    }


    .document-item .row > div {

        width: 100%;

    }


    .document-status {

        margin-bottom: 4px;

    }


    .document-actions .btn {

        font-size: .72rem;

        padding: 5px 8px;

    }

}



/*
|--------------------------------------------------------------------------
| DARK MODE
|--------------------------------------------------------------------------
*/

body.dark-mode .page-title {

    color: #7da2ff;

}


body.dark-mode .page-subtitle,
body.dark-mode .identity-subtitle,
body.dark-mode .document-subtitle {

    color: #aeb4c0;

}


body.dark-mode .identity-header,
body.dark-mode .document-header {

    background: #292a3d;

    border-color: #44465a;

}


body.dark-mode .identity-body,
body.dark-mode .document-body {

    background: #292a3d;

}


body.dark-mode .student-label {

    color: #aeb4c0;

}


body.dark-mode .student-value {

    color: #f1f3f5;

}


body.dark-mode .progress-title,
body.dark-mode .progress-count {

    color: #e5e7eb;

}


body.dark-mode .progress-count span {

    color: #aeb4c0;

}


body.dark-mode .progress-document {

    background: #45475a;

}


body.dark-mode .student-photo-empty {

    background: #343544;

    color: #8f94a3;

}


body.dark-mode .document-item {

    background: #292a3d;

    border-color: #46485c;

}


body.dark-mode .document-item:hover {

    border-color: #5a5d74;

}


body.dark-mode .document-name {

    color: #f1f3f5;

}


body.dark-mode .document-file-name,
body.dark-mode .document-size,
body.dark-mode .document-empty-text,
body.dark-mode .phone-empty {

    color: #aeb4c0;

}


body.dark-mode .document-icon-empty {

    background: #383a4c;

    color: #aeb4c0;

}


body.dark-mode .document-status-empty {

    background: #383a4c;

    color: #b8bdc8;

}


body.dark-mode .modal-content {

    background: #292a3d;

    color: #f1f3f5;

}


body.dark-mode .modal-header {

    border-color: #46485c;

}


body.dark-mode .modal-title {

    color: #7da2ff;

}


body.dark-mode .modal-subtitle,
body.dark-mode .upload-info {

    color: #aeb4c0;

}


body.dark-mode .form-control,
body.dark-mode .custom-file-label {

    background: #343544;

    border-color: #55586d;

    color: #f1f3f5;

}


body.dark-mode .custom-file-label::after {

    background: #45475a;

    border-color: #55586d;

    color: #f1f3f5;

}


body.dark-mode .close {

    color: #fff;

}

</style>