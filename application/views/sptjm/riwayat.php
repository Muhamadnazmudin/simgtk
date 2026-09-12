<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| LABEL JENIS SPTJM
|--------------------------------------------------------------------------
*/

$jenis_label = array(
    'cut_off'            => 'Cut Off 31 Agustus',
    'cut_off_31_agustus' => 'Cut Off 31 Agustus',
    'khusus'             => 'SPTJM Khusus'
);


/*
|--------------------------------------------------------------------------
| HELPER AMBIL NILAI ROW
|--------------------------------------------------------------------------
*/

$get_row_value = function ($row, $key, $default = '') {

    if (is_object($row) && isset($row->{$key})) {
        return $row->{$key};
    }

    if (is_array($row) && isset($row[$key])) {
        return $row[$key];
    }

    return $default;
};

?>

<div class="container-fluid">

    <!-- =========================================================
         HEADER
    ========================================================== -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>

            <h1 class="h3 mb-1 text-gray-800">
                Riwayat SPTJM Dapodik
            </h1>

            <div class="small text-muted">
                Daftar seluruh dokumen SPTJM Dapodik yang telah diupload.
            </div>

        </div>


        <a
            href="<?= site_url('sptjm/upload') ?>"
            class="btn btn-primary btn-sm shadow-sm"
        >
            <i class="fas fa-upload mr-1"></i>
            Upload SPTJM
        </a>

    </div>


    <!-- =========================================================
         FLASH SUCCESS
    ========================================================== -->

    <?php if ($this->session->flashdata('success')): ?>

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle mr-1"></i>

            <?= html_escape($this->session->flashdata('success')) ?>

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
         FLASH ERROR
    ========================================================== -->

    <?php if ($this->session->flashdata('error')): ?>

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle mr-1"></i>

            <?= html_escape($this->session->flashdata('error')) ?>

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
         FILTER
    ========================================================== -->

    <div class="card shadow mb-4">

        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter mr-1"></i>
                Filter Riwayat
            </h6>

        </div>


        <div class="card-body">

            <form
                method="get"
                action="<?= site_url('sptjm/riwayat') ?>"
            >

                <div class="row">

                    <!-- =================================================
                         TAHUN AJARAN
                    ================================================== -->

                    <div class="col-md-5">

                        <div class="form-group mb-3 mb-md-0">

                            <label for="filter_tahun">
                                Tahun Ajaran
                            </label>

                            <select
                                name="tahun_ajaran"
                                id="filter_tahun"
                                class="form-control"
                            >

                                <option value="">
                                    Semua Tahun Ajaran
                                </option>

                                <?php if (!empty($tahun_list)): ?>

                                    <?php foreach ($tahun_list as $tahun): ?>

                                        <?php
                                        $tahun_value = '';

                                        if (is_object($tahun)) {

                                            $tahun_value = isset($tahun->tahun_ajaran)
                                                ? $tahun->tahun_ajaran
                                                : '';

                                        } elseif (is_array($tahun)) {

                                            $tahun_value = isset($tahun['tahun_ajaran'])
                                                ? $tahun['tahun_ajaran']
                                                : '';

                                        }
                                        ?>

                                        <?php if ($tahun_value !== ''): ?>

                                            <option
                                                value="<?= html_escape($tahun_value) ?>"
                                                <?= ((string) $tahun_ajaran === (string) $tahun_value) ? 'selected' : '' ?>
                                            >
                                                <?= html_escape($tahun_value) ?>
                                            </option>

                                        <?php endif; ?>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </select>

                        </div>

                    </div>


                    <!-- =================================================
                         JENIS
                    ================================================== -->

                    <div class="col-md-5">

                        <div class="form-group mb-3 mb-md-0">

                            <label for="filter_jenis">
                                Jenis SPTJM
                            </label>

                            <select
                                name="jenis_sptjm"
                                id="filter_jenis"
                                class="form-control"
                            >

                                <option value="">
                                    Semua Jenis SPTJM
                                </option>

                                <option
                                    value="cut_off"
                                    <?= ($jenis_sptjm === 'cut_off') ? 'selected' : '' ?>
                                >
                                    Cut Off 31 Agustus
                                </option>

                                <option
                                    value="khusus"
                                    <?= ($jenis_sptjm === 'khusus') ? 'selected' : '' ?>
                                >
                                    SPTJM Khusus
                                </option>

                            </select>

                        </div>

                    </div>


                    <!-- =================================================
                         TOMBOL
                    ================================================== -->

                    <div class="col-md-2">

                        <label class="d-none d-md-block">
                            &nbsp;
                        </label>

                        <button
                            type="submit"
                            class="btn btn-primary btn-block"
                        >
                            <i class="fas fa-search mr-1"></i>
                            Tampilkan
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <!-- =========================================================
         DATA SPTJM
    ========================================================== -->

    <div class="card shadow mb-4">

        <div class="card-header py-3 d-flex align-items-center justify-content-between">

            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history mr-1"></i>
                Data SPTJM
            </h6>


            <span class="badge badge-secondary">

                <?= is_array($rows) ? count($rows) : 0 ?>

                Data

            </span>

        </div>


        <div class="card-body">

            <?php if (empty($rows)): ?>

                <!-- =================================================
                     DATA KOSONG
                ================================================== -->

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="fas fa-folder-open fa-3x text-gray-300"
                        ></i>

                    </div>


                    <h5 class="text-gray-600">
                        Belum ada data SPTJM
                    </h5>


                    <p class="text-muted mb-3">
                        Belum terdapat dokumen SPTJM sesuai filter yang dipilih.
                    </p>


                    <a
                        href="<?= site_url('sptjm/upload') ?>"
                        class="btn btn-primary"
                    >
                        <i class="fas fa-upload mr-1"></i>
                        Upload SPTJM
                    </a>

                </div>


            <?php else: ?>


                <!-- =================================================
                     TABLE
                ================================================== -->

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="thead-light">

                            <tr>

                                <th
                                    width="55"
                                    class="text-center"
                                >
                                    No
                                </th>

                                <th width="120">
                                    Tahun Ajaran
                                </th>

                                <th width="190">
                                    Jenis SPTJM
                                </th>

                                <th width="130">
                                    Tanggal Cut Off
                                </th>

                                <th width="130">
                                    Tanggal SPTJM
                                </th>

                                <th width="260">
                                    Dokumen
                                </th>

                                <th>
                                    Keterangan
                                </th>

                                <th
                                    width="80"
                                    class="text-center"
                                >
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php $no = 1; ?>


                            <?php foreach ($rows as $row): ?>

                                <?php

                                /*
                                |--------------------------------------------------------------------------
                                | AMBIL DATA
                                |--------------------------------------------------------------------------
                                */

                                $id = $get_row_value(
                                    $row,
                                    'id',
                                    0
                                );


                                $tahun_ajaran_row = $get_row_value(
                                    $row,
                                    'tahun_ajaran',
                                    '-'
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | JENIS
                                |--------------------------------------------------------------------------
                                */

                                $jenis = trim(
                                    (string) $get_row_value(
                                        $row,
                                        'jenis_sptjm',
                                        ''
                                    )
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | FALLBACK JIKA FIELD BERBEDA
                                |--------------------------------------------------------------------------
                                */

                                if ($jenis === '') {

                                    $jenis = trim(
                                        (string) $get_row_value(
                                            $row,
                                            'jenis',
                                            ''
                                        )
                                    );

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | NORMALISASI JENIS
                                |--------------------------------------------------------------------------
                                */

                                if ($jenis === 'cut_off_31_agustus') {
                                    $jenis_normal = 'cut_off';
                                } else {
                                    $jenis_normal = $jenis;
                                }


                                $label_jenis = isset(
                                    $jenis_label[$jenis_normal]
                                )
                                    ? $jenis_label[$jenis_normal]
                                    : 'Jenis SPTJM';


                                /*
                                |--------------------------------------------------------------------------
                                | TANGGAL
                                |--------------------------------------------------------------------------
                                */

                                $tanggal_cut_off = $get_row_value(
                                    $row,
                                    'tanggal_cut_off',
                                    ''
                                );


                                $tanggal_sptjm = $get_row_value(
                                    $row,
                                    'tanggal_sptjm',
                                    ''
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | FILE
                                |--------------------------------------------------------------------------
                                */

                                $nama_file_sptjm = $get_row_value(
                                    $row,
                                    'nama_file_sptjm',
                                    ''
                                );


                                $file_sptjm = $get_row_value(
                                    $row,
                                    'file_sptjm',
                                    ''
                                );


                                $nama_file_excel = $get_row_value(
                                    $row,
                                    'nama_file_excel',
                                    ''
                                );


                                $file_excel = $get_row_value(
                                    $row,
                                    'file_excel',
                                    ''
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | KETERANGAN
                                |--------------------------------------------------------------------------
                                */

                                $keterangan = $get_row_value(
                                    $row,
                                    'keterangan',
                                    ''
                                );

                                ?>


                                <tr>

                                    <!-- =================================================
                                         NO
                                    ================================================== -->

                                    <td class="text-center align-middle">

                                        <?= $no++ ?>

                                    </td>


                                    <!-- =================================================
                                         TAHUN
                                    ================================================== -->

                                    <td class="align-middle">

                                        <span class="font-weight-bold">

                                            <?= html_escape($tahun_ajaran_row) ?>

                                        </span>

                                    </td>


                                    <!-- =================================================
                                         JENIS
                                    ================================================== -->

                                    <td class="align-middle">

                                        <?php if ($jenis_normal === 'cut_off'): ?>

                                            <span class="badge badge-primary px-2 py-1">

                                                <i class="fas fa-calendar-check mr-1"></i>

                                                Cut Off

                                            </span>

                                            <div class="small text-muted mt-1">
                                                31 Agustus
                                            </div>


                                        <?php elseif ($jenis_normal === 'khusus'): ?>

                                            <span class="badge badge-warning px-2 py-1">

                                                <i class="fas fa-file-signature mr-1"></i>

                                                SPTJM Khusus

                                            </span>


                                        <?php elseif ($jenis !== ''): ?>

                                            <span class="badge badge-secondary px-2 py-1">

                                                <?= html_escape(
                                                    ucfirst(
                                                        str_replace(
                                                            '_',
                                                            ' ',
                                                            $jenis
                                                        )
                                                    )
                                                ) ?>

                                            </span>


                                        <?php else: ?>

                                            <span class="badge badge-danger px-2 py-1">

                                                <i class="fas fa-question-circle mr-1"></i>

                                                Jenis Tidak Diketahui

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- =================================================
                                         TANGGAL CUT OFF
                                    ================================================== -->

                                    <td class="align-middle">

                                        <?php if (!empty($tanggal_cut_off)): ?>

                                            <i class="fas fa-calendar-alt text-muted mr-1"></i>

                                            <?= date(
                                                'd-m-Y',
                                                strtotime($tanggal_cut_off)
                                            ) ?>

                                        <?php else: ?>

                                            <span class="text-muted">
                                                -
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- =================================================
                                         TANGGAL SPTJM
                                    ================================================== -->

                                    <td class="align-middle">

                                        <?php if (!empty($tanggal_sptjm)): ?>

                                            <i class="fas fa-calendar-day text-muted mr-1"></i>

                                            <?= date(
                                                'd-m-Y',
                                                strtotime($tanggal_sptjm)
                                            ) ?>

                                        <?php else: ?>

                                            <span class="text-muted">
                                                -
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- =================================================
                                         DOKUMEN
                                    ================================================== -->

                                    <td class="align-middle">

                                        <!-- PDF -->

                                        <?php if (!empty($file_sptjm)): ?>

                                            <div class="mb-2">

                                                <a
                                                    href="<?= site_url(
                                                        'sptjm/download_pdf/' .
                                                        (int) $id
                                                    ) ?>"
                                                    class="btn btn-danger btn-sm"
                                                >

                                                    <i class="fas fa-file-pdf mr-1"></i>

                                                    Download PDF

                                                </a>

                                            </div>


                                            <?php if (!empty($nama_file_sptjm)): ?>

                                                <div
                                                    class="small text-muted text-truncate"
                                                    style="max-width: 230px;"
                                                    title="<?= html_escape($nama_file_sptjm) ?>"
                                                >

                                                    <?= html_escape($nama_file_sptjm) ?>

                                                </div>

                                            <?php endif; ?>


                                        <?php else: ?>

                                            <div class="small text-danger">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                PDF tidak tersedia
                                            </div>

                                        <?php endif; ?>


                                        <!-- EXCEL -->

                                        <?php if (!empty($file_excel)): ?>

                                            <div class="mt-2">

                                                <a
                                                    href="<?= site_url(
                                                        'sptjm/download_excel/' .
                                                        (int) $id
                                                    ) ?>"
                                                    class="btn btn-success btn-sm"
                                                >

                                                    <i class="fas fa-file-excel mr-1"></i>

                                                    Download Excel

                                                </a>

                                            </div>


                                            <?php if (!empty($nama_file_excel)): ?>

                                                <div
                                                    class="small text-muted text-truncate mt-1"
                                                    style="max-width: 230px;"
                                                    title="<?= html_escape($nama_file_excel) ?>"
                                                >

                                                    <?= html_escape($nama_file_excel) ?>

                                                </div>

                                            <?php endif; ?>


                                        <?php else: ?>

                                            <div class="small text-danger mt-2">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Excel tidak tersedia
                                            </div>

                                        <?php endif; ?>

                                    </td>


                                    <!-- =================================================
                                         KETERANGAN
                                    ================================================== -->

                                    <td class="align-middle">

                                        <?php if (!empty($keterangan)): ?>

                                            <div
                                                class="small"
                                                style="white-space: pre-line;"
                                            >
                                                <?= html_escape($keterangan) ?>
                                            </div>

                                        <?php else: ?>

                                            <span class="text-muted">
                                                -
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- =================================================
                                         AKSI
                                    ================================================== -->

                                    <td class="text-center align-middle">

                                        <button
                                            type="button"
                                            class="btn btn-outline-danger btn-sm btn-hapus-sptjm"
                                            data-id="<?= (int) $id ?>"
                                            title="Hapus SPTJM"
                                        >

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>


                <!-- =================================================
                     INFORMASI
                ================================================== -->

                <div class="alert alert-light border mt-3 mb-0">

                    <div class="small text-muted">

                        <i class="fas fa-info-circle mr-1"></i>

                        Setiap tahun ajaran dapat memiliki maksimal
                        <strong>1 SPTJM Cut Off</strong> dan
                        <strong>1 SPTJM Khusus</strong>.

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>


<!-- =============================================================
     JAVASCRIPT
============================================================= -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    var tombolHapus = document.querySelectorAll(
        '.btn-hapus-sptjm'
    );


    tombolHapus.forEach(function (button) {

        button.addEventListener('click', function () {

            var id = this.getAttribute('data-id');


            if (!id) {
                return;
            }


            var konfirmasi = confirm(
                'Yakin ingin menghapus data SPTJM ini?\n\n' +
                'File PDF dan Excel yang terkait juga akan dihapus.'
            );


            if (!konfirmasi) {
                return;
            }


            window.location.href =
                '<?= site_url('sptjm/hapus/') ?>' + id;

        });

    });

});

</script>