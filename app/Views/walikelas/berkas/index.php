<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>
            <h1 class="h3 mb-1 text-gray-800">
                Berkas Siswa
            </h1>

            <p class="mb-0 text-muted">
                Daftar berkas siswa kelas Anda.
            </p>
        </div>

    </div>


    <div class="card shadow mb-4">

        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-primary">
                Data Berkas Siswa
            </h6>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover"
                    width="100%"
                >

                    <thead>

                        <tr>

                            <th width="50">
                                No
                            </th>

                            <th>
                                NIS
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
                                Jumlah Berkas
                            </th>

                            <th width="100"
                                class="text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (empty($siswa)): ?>

                        <tr>

                            <td
                                colspan="7"
                                class="text-center text-muted"
                            >
                                Tidak ada siswa.
                            </td>

                        </tr>

                    <?php else: ?>

                        <?php $no = 1; ?>

                        <?php foreach ($siswa as $s): ?>

    <tr>

        <td>
            <?= $no++; ?>
        </td>

        <td>
            <?= html_escape($s->nis ?? '-'); ?>
        </td>

        <td>
            <?= html_escape($s->nisn ?? '-'); ?>
        </td>

        <td>
            <strong>
                <?= html_escape($s->nama ?? '-'); ?>
            </strong>
        </td>

        <td>
            <?= html_escape($s->nama_kelas ?? '-'); ?>
        </td>

        <td class="text-center">

            <?php if ((int)$s->jumlah_berkas > 0): ?>

                <span class="badge badge-success">
                    <?= (int)$s->jumlah_berkas; ?> berkas
                </span>

            <?php else: ?>

                <span class="badge badge-secondary">
                    Belum ada
                </span>

            <?php endif; ?>

        </td>

        <td class="text-center">

            <a
                href="<?= site_url('Walikelas_berkas/detail/' . (int)$s->id); ?>"
                class="btn btn-sm btn-primary"
            >
                <i class="fas fa-eye"></i>
                Lihat
            </a>

        </td>

    </tr>

<?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>