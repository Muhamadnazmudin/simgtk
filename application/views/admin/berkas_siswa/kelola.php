<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| DATA YANG DIHARAPKAN DARI CONTROLLER
|--------------------------------------------------------------------------
|
| $siswa
| $jenis_berkas
| $berkas
|
| $jenis_berkas:
| [
|   'kartu_keluarga' => 'Kartu Keluarga',
|   'akta_lahir'     => 'Akta Lahir',
|   'ijazah_sd'      => 'Ijazah SD',
|   'ijazah_smp'     => 'Ijazah SMP/MTs',
|   'ktp_ayah'       => 'KTP Ayah',
|   'ktp_ibu'        => 'KTP Ibu'
| ]
|
*/


/*
|--------------------------------------------------------------------------
| NORMALISASI DATA BERKAS
|--------------------------------------------------------------------------
|
| Bisa mengantisipasi jika controller/model mengirim:
| - array
| - object
|
*/

if (!isset($berkas) || !is_array($berkas)) {
    $berkas = [];
}

if (!isset($jenis_berkas) || !is_array($jenis_berkas)) {
    $jenis_berkas = [];
}


/*
|--------------------------------------------------------------------------
| HITUNG KELENGKAPAN
|--------------------------------------------------------------------------
*/

$total_berkas = count($jenis_berkas);

$jumlah_berkas = 0;

foreach ($jenis_berkas as $kode => $nama) {

    if (isset($berkas[$kode]) && !empty($berkas[$kode])) {
        $jumlah_berkas++;
    }
}

$persentase = 0;

if ($total_berkas > 0) {
    $persentase = round(
        ($jumlah_berkas / $total_berkas) * 100
    );
}

?>


<!-- ================================================================
     PAGE
================================================================ -->

<div class="container-fluid">


    <!-- ============================================================
         HEADER
    ============================================================= -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>

            <h1 class="h3 mb-1 text-gray-800">

                <i class="fas fa-folder-open text-primary mr-2"></i>

                Berkas Siswa

            </h1>

            <div class="small text-muted">

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

    <div class="card shadow-sm mb-4">

        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-primary">

                <i class="fas fa-user-graduate mr-2"></i>

                Identitas Siswa

            </h6>

        </div>


        <div class="card-body">

            <div class="row">


                <!-- FOTO -->

                <div class="col-md-2 text-center mb-3 mb-md-0">

                    <?php

                    $foto = '';

                    if (!empty($siswa->foto)) {
                        $foto = base_url(
                            'uploads/foto/' .
                            $siswa->foto
                        );
                    }

                    ?>


                    <?php if ($foto): ?>

                        <img src="<?= $foto; ?>"
                             class="img-thumbnail"
                             style="
                                width:110px;
                                height:140px;
                                object-fit:cover;
                             ">

                    <?php else: ?>

                        <div class="border rounded d-flex align-items-center justify-content-center mx-auto"
                             style="
                                width:110px;
                                height:140px;
                                background:#f8f9fc;
                             ">

                            <i class="fas fa-user fa-3x text-gray-400"></i>

                        </div>

                    <?php endif; ?>

                </div>



                <!-- DATA SISWA -->

                <div class="col-md-10">

                    <div class="row">


                        <!-- NAMA -->

                        <div class="col-md-4 mb-3">

                            <div class="small text-muted">
                                Nama Siswa
                            </div>

                            <div class="font-weight-bold text-uppercase">

                                <?= html_escape(
                                    $siswa->nama
                                ); ?>

                            </div>

                        </div>


                        <!-- NIS -->

                        <div class="col-md-4 mb-3">

                            <div class="small text-muted">
                                NIS
                            </div>

                            <div class="font-weight-bold">

                                <?= html_escape(
                                    $siswa->nis
                                ); ?>

                            </div>

                        </div>


                        <!-- NISN -->

                        <div class="col-md-4 mb-3">

                            <div class="small text-muted">
                                NISN
                            </div>

                            <div class="font-weight-bold">

                                <?= html_escape(
                                    $siswa->nisn
                                ); ?>

                            </div>

                        </div>


                        <!-- KELAS -->

                        <?php if (isset($siswa->nama_kelas)): ?>

                            <div class="col-md-4 mb-3">

                                <div class="small text-muted">
                                    Kelas
                                </div>

                                <div class="font-weight-bold">

                                    <?= html_escape(
                                        $siswa->nama_kelas
                                    ); ?>

                                </div>

                            </div>

                        <?php endif; ?>


                        <!-- STATUS -->

                        <div class="col-md-4 mb-3">

                            <div class="small text-muted">
                                Status
                            </div>

                            <span class="badge badge-success">

                                <?= ucfirst(
                                    html_escape(
                                        $siswa->status
                                    )
                                ); ?>

                            </span>

                        </div>


                    </div>


                    <!-- PROGRESS -->

                    <div class="mt-2">

                        <div class="d-flex justify-content-between mb-1">

                            <span class="small font-weight-bold">

                                Kelengkapan Berkas

                            </span>

                            <span class="small font-weight-bold">

                                <?= $jumlah_berkas; ?>
                                /
                                <?= $total_berkas; ?>

                                (<?= $persentase; ?>%)

                            </span>

                        </div>


                        <div class="progress"
                             style="height:10px;">

                            <div class="progress-bar"
                                 role="progressbar"
                                 style="width:<?= $persentase; ?>%;">

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

    <div class="card shadow-sm mb-4">


        <!-- HEADER -->

        <div class="card-header py-3">

            <div class="d-flex align-items-center justify-content-between">

                <h6 class="m-0 font-weight-bold text-primary">

                    <i class="fas fa-file-alt mr-2"></i>

                    Daftar Berkas

                </h6>


                <span class="badge badge-primary">

                    <?= $jumlah_berkas; ?>
                    /
                    <?= $total_berkas; ?>

                </span>

            </div>

        </div>



        <!-- BODY -->

        <div class="card-body">


            <?php if (empty($jenis_berkas)): ?>

                <div class="alert alert-warning">

                    <i class="fas fa-exclamation-triangle mr-2"></i>

                    Daftar jenis berkas belum tersedia.

                </div>

            <?php endif; ?>



            <?php foreach ($jenis_berkas as $kode => $nama): ?>


                <?php

                /*
                |--------------------------------------------------------------------------
                | DATA BERKAS SAAT INI
                |--------------------------------------------------------------------------
                */

                $item = null;

                if (
                    isset($berkas[$kode]) &&
                    !empty($berkas[$kode])
                ) {
                    $item = $berkas[$kode];
                }


                $sudah_ada = !empty($item);


                /*
                |--------------------------------------------------------------------------
                | NORMALISASI OBJECT / ARRAY
                |--------------------------------------------------------------------------
                */

                if ($sudah_ada) {

                    $item_id =
                        is_object($item)
                        ? $item->id
                        : $item['id'];

                    $nama_file =
                        is_object($item)
                        ? $item->nama_file
                        : $item['nama_file'];

                    $nama_file_asli =
                        is_object($item)
                        ? $item->nama_file_asli
                        : $item['nama_file_asli'];

                    $ukuran_file =
                        is_object($item)
                        ? $item->ukuran_file
                        : $item['ukuran_file'];

                }

                ?>


                <!-- =================================================
                     ITEM BERKAS
                ================================================== -->

                <div class="berkas-item border rounded p-3 mb-3">


                    <div class="row align-items-center">


                        <!-- =================================================
                             ICON + NAMA
                        ================================================= -->

                        <div class="col-lg-4 col-md-5 mb-3 mb-md-0">


                            <div class="d-flex align-items-center">


                                <!-- ICON -->

                                <div class="mr-3 flex-shrink-0">


                                    <?php if ($sudah_ada): ?>

                                        <div class="berkas-icon bg-success">

                                            <i class="fas fa-check"></i>

                                        </div>

                                    <?php else: ?>

                                        <div class="berkas-icon bg-light text-secondary">

                                            <i class="fas fa-file"></i>

                                        </div>

                                    <?php endif; ?>


                                </div>


                                <!-- NAMA -->

                                <div style="min-width:0;">


                                    <div class="font-weight-bold">

                                        <?= html_escape(
                                            $nama
                                        ); ?>

                                    </div>


                                    <?php if ($sudah_ada): ?>

                                        <div class="small text-muted text-truncate"
                                             style="max-width:300px;"
                                             title="<?= html_escape(
                                                $nama_file_asli
                                             ); ?>">

                                            <i class="fas fa-paperclip mr-1"></i>

                                            <?= html_escape(
                                                $nama_file_asli
                                            ); ?>

                                        </div>


                                        <?php if ($ukuran_file): ?>

                                            <div class="small text-muted">

                                                <?= number_format(
                                                    $ukuran_file / 1024,
                                                    1
                                                ); ?>

                                                KB

                                            </div>

                                        <?php endif; ?>


                                    <?php else: ?>

                                        <div class="small text-muted">

                                            Belum ada berkas

                                        </div>

                                    <?php endif; ?>


                                </div>


                            </div>


                        </div>



                        <!-- =================================================
                             STATUS
                        ================================================== -->

                        <div class="col-lg-2 col-md-3 mb-3 mb-md-0">


                            <?php if ($sudah_ada): ?>

                                <span class="badge badge-success py-2 px-2">

                                    <i class="fas fa-check mr-1"></i>

                                    Sudah Ada

                                </span>

                            <?php else: ?>

                                <span class="badge badge-secondary py-2 px-2">

                                    <i class="fas fa-times mr-1"></i>

                                    Belum Ada

                                </span>

                            <?php endif; ?>


                        </div>



                        <!-- =================================================
                             ACTION
                        ================================================== -->

                        <div class="col-lg-6 col-md-4 text-md-right">


                            <?php if ($sudah_ada): ?>


                                <!-- ================================
                                     LIHAT
                                ================================= -->

                                <a href="<?= site_url(
                                    'berkas_siswa/lihat/' .
                                    $item_id
                                ); ?>"
                                   target="_blank"
                                   class="btn btn-info btn-sm mb-1">

                                    <i class="fas fa-eye mr-1"></i>

                                    Lihat

                                </a>



                                <!-- ================================
                                     DOWNLOAD
                                ================================= -->

                                <a href="<?= site_url(
                                    'berkas_siswa/download/' .
                                    $item_id
                                ); ?>"
                                   class="btn btn-secondary btn-sm mb-1">

                                    <i class="fas fa-download mr-1"></i>

                                    Download

                                </a>



                                <!-- ================================
                                     GANTI
                                ================================= -->

                                <button type="button"

                                        class="btn btn-warning btn-sm mb-1"

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



                                <!-- ================================
                                     HAPUS
                                ================================= -->

                                <a href="<?= site_url(
                                    'berkas_siswa/hapus/' .
                                    $item_id
                                ); ?>"

                                   class="btn btn-danger btn-sm mb-1"

                                   onclick="return confirm(
                                       'Yakin ingin menghapus berkas <?= html_escape(
                                           $nama
                                       ); ?>?'
                                   );">

                                    <i class="fas fa-trash mr-1"></i>

                                    Hapus

                                </a>


                            <?php else: ?>


                                <!-- ================================
                                     UPLOAD
                                ================================= -->

                                <button type="button"

                                        class="btn btn-primary btn-sm"

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


            <?php endforeach; ?>


        </div>

    </div>

</div>



<!-- =================================================================
     MODAL UPLOAD BERKAS
================================================================= -->

<div class="modal fade"

     id="modalUpload"

     tabindex="-1"

     role="dialog"

     aria-labelledby="modalUploadLabel"

     aria-hidden="true">


    <div class="modal-dialog modal-dialog-centered"

         role="document">


        <div class="modal-content">


            <!-- =====================================================
                 MODAL HEADER
            ====================================================== -->

            <div class="modal-header">


                <h5 class="modal-title"
                    id="modalUploadLabel">

                    <i class="fas fa-cloud-upload-alt mr-2"></i>

                    Upload Berkas

                </h5>


                <button type="button"

                        class="close"

                        data-dismiss="modal"

                        aria-label="Close">

                    <span aria-hidden="true">

                        &times;

                    </span>

                </button>


            </div>



            <!-- =====================================================
                 FORM
            ====================================================== -->

            <form method="post"

                  enctype="multipart/form-data"

                  action="<?= site_url(
                      'berkas_siswa/upload/' .
                      $siswa->id
                  ); ?>">


                <!-- =================================================
                     CSRF
                ================================================== -->

                <input type="hidden"

                       name="<?= $this->security->get_csrf_token_name(); ?>"

                       value="<?= $this->security->get_csrf_hash(); ?>">



                <div class="modal-body">


                    <!-- =============================================
                         JENIS BERKAS
                    ============================================== -->

                    <div class="form-group">


                        <label class="font-weight-bold">

                            Jenis Berkas

                        </label>


                        <input type="text"

                               id="nama_berkas"

                               class="form-control"

                               readonly

                               placeholder="Jenis berkas akan otomatis terisi">


                        <!-- HIDDEN VALUE YANG DIKIRIM -->

                        <input type="hidden"

                               name="jenis_berkas"

                               id="jenis_berkas">


                    </div>



                    <!-- =============================================
                         FILE
                    ============================================== -->

                    <div class="form-group mb-0">


                        <label class="font-weight-bold">

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


                        <small class="form-text text-muted mt-2">

                            <i class="fas fa-info-circle mr-1"></i>

                            Format:

                            <strong>
                                PDF, JPG, JPEG, PNG
                            </strong>

                            <br>

                            Maksimal ukuran:

                            <strong>
                                5 MB
                            </strong>

                        </small>


                    </div>


                </div>



                <!-- =================================================
                     MODAL FOOTER
                ================================================== -->

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



<!-- =================================================================
     JAVASCRIPT
================================================================= -->

<script>


/*
|--------------------------------------------------------------------------
| PILIH BERKAS
|--------------------------------------------------------------------------
|
| Dipanggil langsung dari tombol:
|
| onclick="pilihBerkas(this)"
|
*/

function pilihBerkas(button)
{

    /*
    |--------------------------------------------------------------------------
    | Ambil data dari tombol
    |--------------------------------------------------------------------------
    */

    var jenis = button.getAttribute('data-jenis');

    var nama = button.getAttribute('data-nama');


    /*
    |--------------------------------------------------------------------------
    | Isi hidden input
    |--------------------------------------------------------------------------
    */

    document.getElementById('jenis_berkas').value = jenis;


    /*
    |--------------------------------------------------------------------------
    | Isi jenis yang terlihat
    |--------------------------------------------------------------------------
    */

    document.getElementById('nama_berkas').value = nama;


    /*
    |--------------------------------------------------------------------------
    | Reset file
    |--------------------------------------------------------------------------
    */

    var fileInput =
        document.getElementById('file_berkas');

    var fileLabel =
        document.querySelector(
            'label[for="file_berkas"]'
        );


    if (fileInput) {

        fileInput.value = '';

    }


    if (fileLabel) {

        fileLabel.innerHTML =
            'Pilih file...';

    }


    /*
    |--------------------------------------------------------------------------
    | Update judul modal
    |--------------------------------------------------------------------------
    */

    var modalTitle =
        document.getElementById(
            'modalUploadLabel'
        );


    if (modalTitle) {

        modalTitle.innerHTML =
            '<i class="fas fa-cloud-upload-alt mr-2"></i>' +
            'Upload ' +
            nama;

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
            event.target &&
            event.target.id === 'file_berkas'
        ) {

            var fileName =
                event.target.files.length > 0
                ? event.target.files[0].name
                : 'Pilih file...';


            var label =
                document.querySelector(
                    'label[for="file_berkas"]'
                );


            if (label) {

                label.innerHTML =
                    fileName;

            }

        }

    }
);



/*
|--------------------------------------------------------------------------
| RESET MODAL SAAT DITUTUP
|--------------------------------------------------------------------------
*/

$('#modalUpload').on(
    'hidden.bs.modal',
    function()
    {

        document.getElementById(
            'jenis_berkas'
        ).value = '';


        document.getElementById(
            'nama_berkas'
        ).value = '';


        document.getElementById(
            'file_berkas'
        ).value = '';


        var label =
            document.querySelector(
                'label[for="file_berkas"]'
            );


        if (label) {

            label.innerHTML =
                'Pilih file...';

        }


        document.getElementById(
            'modalUploadLabel'
        ).innerHTML =
            '<i class="fas fa-cloud-upload-alt mr-2"></i>' +
            'Upload Berkas';

    }
);



/*
|--------------------------------------------------------------------------
| CEGAH SUBMIT JIKA JENIS BERKAS KOSONG
|--------------------------------------------------------------------------
*/

$('#modalUpload form').on(
    'submit',
    function(event)
    {

        var jenis =
            document.getElementById(
                'jenis_berkas'
            ).value;


        if (!jenis) {

            event.preventDefault();


            alert(
                'Jenis berkas belum dipilih.'
            );


            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | Cegah double submit
        |--------------------------------------------------------------------------
        */

        var button =
            document.getElementById(
                'btnSubmitUpload'
            );


        if (button) {

            button.disabled = true;

            button.innerHTML =
                '<i class="fas fa-spinner fa-spin mr-1"></i>' +
                'Mengupload...';

        }

    }
);


</script>



<!-- =================================================================
     STYLE
================================================================= -->

<style>


/*
|--------------------------------------------------------------------------
| ITEM BERKAS
|--------------------------------------------------------------------------
*/

.berkas-item {

    transition:
        all 0.2s ease;

}


.berkas-item:hover {

    box-shadow:
        0 0.15rem 0.75rem
        rgba(0, 0, 0, 0.08);

}



/*
|--------------------------------------------------------------------------
| ICON BERKAS
|--------------------------------------------------------------------------
*/

.berkas-icon {

    width: 44px;

    height: 44px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

}



/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 767.98px) {


    .berkas-item
    .text-md-right {

        text-align: left !important;

        margin-top: 5px;

    }


    .berkas-item .btn {

        margin-right: 3px;

        margin-bottom: 4px;

    }


}



/*
|--------------------------------------------------------------------------
| CUSTOM FILE
|--------------------------------------------------------------------------
*/

.custom-file-label {

    overflow: hidden;

    white-space: nowrap;

    text-overflow: ellipsis;

}



/*
|--------------------------------------------------------------------------
| DARK MODE
|--------------------------------------------------------------------------
|
| Mengikuti pola dark-mode project.
|
*/

body.dark-mode .berkas-item {

    border-color:
        #4a4a4a !important;

}


body.dark-mode .berkas-icon.bg-light {

    background-color:
        #343a40 !important;

    color:
        #adb5bd !important;

}


body.dark-mode .card-header {

    border-color:
        #4a4a4a;

}


body.dark-mode
.custom-file-label {

    background-color:
        #343a40;

    border-color:
        #555;

    color:
        #ddd;

}


body.dark-mode
.form-control {

    background-color:
        #343a40;

    border-color:
        #555;

    color:
        #fff;

}


</style>