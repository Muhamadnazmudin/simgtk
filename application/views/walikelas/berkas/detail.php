<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>

            <h1 class="h3 mb-1 text-gray-800">
                Berkas Siswa
            </h1>

            <p class="mb-0 text-muted">
                Data berkas siswa kelas Anda.
            </p>

        </div>

        <a
            href="<?= site_url('Walikelas_berkas'); ?>"
            class="btn btn-sm btn-secondary"
        >
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>

    </div>


    <!-- DATA SISWA -->
    <div class="card shadow mb-4">

        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-primary">
                Data Siswa
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-2">

                    <small class="text-muted">
                        NIS
                    </small>

                    <div class="font-weight-bold">
                        <?= html_escape($siswa->nis ?? '-'); ?>
                    </div>

                </div>


                <div class="col-md-4 mb-2">

                    <small class="text-muted">
                        NISN
                    </small>

                    <div class="font-weight-bold">
                        <?= html_escape($siswa->nisn ?? '-'); ?>
                    </div>

                </div>


                <div class="col-md-4 mb-2">

                    <small class="text-muted">
                        Kelas
                    </small>

                    <div class="font-weight-bold">
                        <?= html_escape($siswa->nama_kelas ?? '-'); ?>
                    </div>

                </div>


                <div class="col-md-12 mt-2">

                    <small class="text-muted">
                        Nama Siswa
                    </small>

                    <div class="h5 font-weight-bold text-primary">
                        <?= html_escape($siswa->nama ?? '-'); ?>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- BERKAS -->
    <div class="card shadow mb-4">

        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-primary">
                Daftar Berkas
            </h6>

        </div>


        <div class="card-body">

            <?php if (empty($berkas)): ?>

                <div class="alert alert-secondary">

                    <i class="fas fa-info-circle"></i>

                    Siswa belum memiliki berkas yang diupload.

                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead>

                            <tr>

                                <th width="60">
                                    No
                                </th>

                                <th>
                                    Jenis Berkas
                                </th>

                                <th>
                                    Nama File
                                </th>

                                <th>
                                    Tanggal Upload
                                </th>

                                <th
                                    width="120"
                                    class="text-center"
                                >
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php $no = 1; ?>

                            <?php foreach ($berkas as $b): ?>

                                <tr>

                                    <td>
                                        <?= $no++; ?>
                                    </td>


                                    <td>

                                        <?= html_escape(
                                            ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $b->jenis_berkas
                                                )
                                            )
                                        ); ?>

                                    </td>


                                    <td>

                                        <i class="fas fa-file-alt text-primary"></i>

                                        <?= html_escape(
                                            $b->nama_file_asli ?? $b->nama_file
                                        ); ?>

                                    </td>


                                    <td>

                                        <?= !empty($b->updated_at)
                                            ? date(
                                                'd-m-Y H:i',
                                                strtotime($b->updated_at)
                                            )
                                            : '-';
                                        ?>

                                    </td>


                                    <td class="text-center">

                                        <?php if (!empty($b->access_token)): ?>

                                            <a
                                                href="<?= site_url(
                                                    'SiswaBerkas/file/' .
                                                    $b->access_token
                                                ); ?>"
                                                target="_blank"
                                                class="btn btn-sm btn-primary"
                                            >

                                                <i class="fas fa-eye"></i>

                                                Lihat

                                            </a>

                                        <?php else: ?>

                                            <span class="text-muted">
                                                -
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>