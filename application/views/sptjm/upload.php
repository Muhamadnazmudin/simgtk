<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">

    <!-- =========================================================
         HEADER
    ========================================================== -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>
            <h1 class="h3 mb-1 text-gray-800">
                Upload SPTJM Dapodik
            </h1>

            <div class="small text-muted">
                Kelola dokumen SPTJM Dapodik berdasarkan tahun ajaran.
            </div>
        </div>

        <a
            href="<?= site_url('sptjm/riwayat') ?>"
            class="btn btn-secondary btn-sm shadow-sm"
        >
            <i class="fas fa-history mr-1"></i>
            Riwayat SPTJM
        </a>

    </div>


    <!-- =========================================================
         FLASH MESSAGE
    ========================================================== -->

    <?php if ($this->session->flashdata('success')): ?>

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle mr-1"></i>

            <?= $this->session->flashdata('success') ?>

            <button
                type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close"
            >
                <span aria-hidden="true">&times;</span>
            </button>

        </div>

    <?php endif; ?>


    <?php if ($this->session->flashdata('error')): ?>

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle mr-1"></i>

            <?= $this->session->flashdata('error') ?>

            <button
                type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close"
            >
                <span aria-hidden="true">&times;</span>
            </button>

        </div>

    <?php endif; ?>


    <!-- =========================================================
         CARD FORM
    ========================================================== -->

    <div class="card shadow mb-4">

        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-file-signature mr-1"></i>
                Form Upload SPTJM
            </h6>

        </div>


        <div class="card-body">

            <?= form_open_multipart(
                'sptjm/simpan',
                array(
                    'id'           => 'formSptjm',
                    'autocomplete' => 'off'
                )
            ); ?>


            <!-- =================================================
                 INFORMASI
            ================================================== -->

            <div class="alert alert-info">

                <div class="font-weight-bold mb-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Informasi
                </div>

                <div class="small">
                    Setiap tahun ajaran dapat memiliki maksimal
                    <strong>2 dokumen SPTJM</strong>, yaitu
                    <strong>Cut Off</strong> dan
                    <strong>SPTJM Khusus</strong>.
                </div>

                <div class="small mt-1">
                    Setiap dokumen wajib dilengkapi dengan
                    <strong>file PDF SPTJM</strong> dan
                    <strong>file Excel</strong>.
                </div>

            </div>


            <!-- =================================================
                 TAHUN AJARAN
            ================================================== -->

            <div class="form-group">

                <label for="tahun_ajaran">

                    Tahun Ajaran

                    <span class="text-danger">*</span>

                </label>


                <select
                    name="tahun_ajaran"
                    id="tahun_ajaran"
                    class="form-control"
                    required
                >

                    <option value="">
                        -- Pilih Tahun Ajaran --
                    </option>


                    <?php
                    $tahun_sekarang = date('Y');
                    $tahun_awal     = $tahun_sekarang - 2;
                    $tahun_akhir    = $tahun_sekarang + 1;

                    for (
                        $tahun = $tahun_akhir;
                        $tahun >= $tahun_awal;
                        $tahun--
                    ):

                        $tahun_ajaran = $tahun . '/' . ($tahun + 1);
                    ?>

                        <option
                            value="<?= html_escape($tahun_ajaran) ?>"
                            <?= set_select(
                                'tahun_ajaran',
                                $tahun_ajaran
                            ) ?>
                        >
                            <?= html_escape($tahun_ajaran) ?>
                        </option>

                    <?php endfor; ?>

                </select>


                <small class="form-text text-muted">
                    Pilih tahun ajaran sesuai dengan dokumen SPTJM.
                </small>

            </div>


            <!-- =================================================
                 JENIS SPTJM
            ================================================== -->

            <div class="form-group">

                <label for="jenis_sptjm">

                    Jenis SPTJM

                    <span class="text-danger">*</span>

                </label>


                <select
                    name="jenis_sptjm"
                    id="jenis_sptjm"
                    class="form-control"
                    required
                >

                    <option value="">
                        -- Pilih Jenis SPTJM --
                    </option>

                    <option
                        value="cut_off"
                        <?= set_select(
                            'jenis_sptjm',
                            'cut_off'
                        ) ?>
                    >
                        Cut Off 31 Agustus
                    </option>

                    <option
                        value="khusus"
                        <?= set_select(
                            'jenis_sptjm',
                            'khusus'
                        ) ?>
                    >
                        SPTJM Khusus
                    </option>

                </select>


                <small class="form-text text-muted">
                    Pilih jenis dokumen SPTJM yang akan diupload.
                </small>

            </div>


            <!-- =================================================
                 TANGGAL CUT OFF
            ================================================== -->

            <div class="form-group">

                <label for="tanggal_cut_off">

                    Tanggal Cut Off

                    <span class="text-danger">*</span>

                </label>


                <input
                    type="date"
                    name="tanggal_cut_off"
                    id="tanggal_cut_off"
                    class="form-control"
                    value="<?= set_value('tanggal_cut_off') ?>"
                    required
                >


                <small class="form-text text-muted">
                    Contoh untuk SPTJM Cut Off:
                    <strong>31 Agustus</strong> pada tahun terkait.
                </small>

            </div>


            <!-- =================================================
                 TANGGAL SPTJM
            ================================================== -->

            <div class="form-group">

                <label for="tanggal_sptjm">

                    Tanggal SPTJM

                    <span class="text-danger">*</span>

                </label>


                <input
                    type="date"
                    name="tanggal_sptjm"
                    id="tanggal_sptjm"
                    class="form-control"
                    value="<?= set_value('tanggal_sptjm') ?>"
                    required
                >


                <small class="form-text text-muted">
                    Tanggal yang tercantum pada dokumen SPTJM.
                </small>

            </div>


            <!-- =================================================
                 FILE PDF
            ================================================== -->

            <div class="form-group">

                <label for="file_sptjm">

                    File SPTJM (PDF)

                    <span class="text-danger">*</span>

                </label>


                <div class="custom-file">

                    <input
                        type="file"
                        name="file_sptjm"
                        id="file_sptjm"
                        class="custom-file-input"
                        accept=".pdf,application/pdf"
                        required
                    >

                    <label
                        class="custom-file-label"
                        for="file_sptjm"
                    >
                        Pilih file PDF...
                    </label>

                </div>


                <small class="form-text text-muted">

                    Format:
                    <strong>PDF</strong>

                    &nbsp;|&nbsp;

                    Maksimal:
                    <strong>10 MB</strong>

                </small>

            </div>


            <!-- =================================================
                 FILE EXCEL
            ================================================== -->

            <div class="form-group">

                <label for="file_excel">

                    File Excel

                    <span class="text-danger">*</span>

                </label>


                <div class="custom-file">

                    <input
                        type="file"
                        name="file_excel"
                        id="file_excel"
                        class="custom-file-input"
                        accept=".xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        required
                    >

                    <label
                        class="custom-file-label"
                        for="file_excel"
                    >
                        Pilih file Excel...
                    </label>

                </div>


                <small class="form-text text-muted">

                    Format:
                    <strong>XLS / XLSX</strong>

                    &nbsp;|&nbsp;

                    Maksimal:
                    <strong>20 MB</strong>

                </small>

            </div>


            <!-- =================================================
                 KETERANGAN
            ================================================== -->

            <div class="form-group">

                <label for="keterangan">
                    Keterangan
                </label>


                <textarea
                    name="keterangan"
                    id="keterangan"
                    rows="4"
                    class="form-control"
                    maxlength="1000"
                    placeholder="Keterangan tambahan jika diperlukan..."
                ><?= set_value('keterangan') ?></textarea>


                <small class="form-text text-muted">
                    Opsional.
                </small>

            </div>


            <!-- =================================================
                 PEMBATAS
            ================================================== -->

            <hr>


            <!-- =================================================
                 TOMBOL
            ================================================== -->

            <div class="d-flex justify-content-between flex-wrap">

                <a
                    href="<?= site_url('sptjm/riwayat') ?>"
                    class="btn btn-secondary mb-2"
                >
                    <i class="fas fa-arrow-left mr-1"></i>
                    Batal
                </a>


                <button
                    type="submit"
                    id="btnSimpan"
                    class="btn btn-primary mb-2"
                >

                    <i class="fas fa-save mr-1"></i>

                    Simpan SPTJM

                </button>

            </div>


            <?= form_close(); ?>

        </div>

    </div>

</div>


<!-- =============================================================
     JAVASCRIPT
============================================================= -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       FILE PDF
    ========================================================== */

    var fileSptjm = document.getElementById('file_sptjm');
    var labelSptjm = document.querySelector(
        'label.custom-file-label[for="file_sptjm"]'
    );

    if (fileSptjm && labelSptjm) {

        fileSptjm.addEventListener('change', function () {

            if (this.files && this.files.length > 0) {

                labelSptjm.textContent = this.files[0].name;

            } else {

                labelSptjm.textContent = 'Pilih file PDF...';

            }

        });

    }


    /* =========================================================
       FILE EXCEL
    ========================================================== */

    var fileExcel = document.getElementById('file_excel');
    var labelExcel = document.querySelector(
        'label.custom-file-label[for="file_excel"]'
    );

    if (fileExcel && labelExcel) {

        fileExcel.addEventListener('change', function () {

            if (this.files && this.files.length > 0) {

                labelExcel.textContent = this.files[0].name;

            } else {

                labelExcel.textContent = 'Pilih file Excel...';

            }

        });

    }


    /* =========================================================
       VALIDASI PDF
    ========================================================== */

    if (fileSptjm) {

        fileSptjm.addEventListener('change', function () {

            var file = this.files[0];

            if (!file) {
                return;
            }

            var maxSize = 10 * 1024 * 1024;

            if (file.size > maxSize) {

                alert('Ukuran file PDF maksimal 10 MB.');

                this.value = '';

                if (labelSptjm) {
                    labelSptjm.textContent = 'Pilih file PDF...';
                }

            }

        });

    }


    /* =========================================================
       VALIDASI EXCEL
    ========================================================== */

    if (fileExcel) {

        fileExcel.addEventListener('change', function () {

            var file = this.files[0];

            if (!file) {
                return;
            }

            var maxSize = 20 * 1024 * 1024;

            if (file.size > maxSize) {

                alert('Ukuran file Excel maksimal 20 MB.');

                this.value = '';

                if (labelExcel) {
                    labelExcel.textContent = 'Pilih file Excel...';
                }

            }

        });

    }


    /* =========================================================
       SUBMIT
    ========================================================== */

    var formSptjm = document.getElementById('formSptjm');
    var btnSimpan = document.getElementById('btnSimpan');

    if (formSptjm && btnSimpan) {

        formSptjm.addEventListener('submit', function () {

            if (!formSptjm.checkValidity()) {
                return;
            }

            btnSimpan.disabled = true;

            btnSimpan.innerHTML =
                '<span class="spinner-border spinner-border-sm mr-1" ' +
                'role="status" aria-hidden="true"></span>' +
                ' Mengupload...';

        });

    }

});
</script>