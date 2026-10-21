<div class="container-fluid">

    <!-- ==================================================
         HEADER
    =================================================== -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>

            <h1 class="h3 mb-1 text-gray-800">
                <i class="fas fa-folder-open text-primary"></i>
                Upload Berkas Siswa
            </h1>

            <small class="text-muted">
                Kelola kelengkapan dokumen siswa aktif
            </small>

        </div>

    </div>


    <!-- ==================================================
         FLASH MESSAGE
    =================================================== -->

    <?php if ($this->session->flashdata('success')): ?>

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle mr-1"></i>

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

            <i class="fas fa-exclamation-circle mr-1"></i>

            <?= $this->session->flashdata('error'); ?>

            <button type="button"
                    class="close"
                    data-dismiss="alert">

                <span>&times;</span>

            </button>

        </div>

    <?php endif; ?>


    <!-- ==================================================
         FILTER
    =================================================== -->

    <div class="card shadow-sm mb-4">

        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-primary">

                <i class="fas fa-filter mr-1"></i>
                Filter Siswa

            </h6>

        </div>


        <div class="card-body">

            <form method="get"
                  action="<?= site_url('berkas_siswa'); ?>">

                <div class="row">

                    <!-- SEARCH -->

                    <div class="col-md-6 mb-3 mb-md-0">

                        <label class="small font-weight-bold">

                            Cari Siswa

                        </label>

                        <div class="input-group">

                            <input type="text"
                                   name="keyword"
                                   class="form-control"
                                   placeholder="NIS, NISN atau nama..."
                                   value="<?= html_escape($keyword); ?>">

                            <div class="input-group-append">

                                <button type="submit"
                                        class="btn btn-primary">

                                    <i class="fas fa-search"></i>
                                    Cari

                                </button>

                            </div>

                        </div>

                    </div>


                    <!-- KELAS -->

                    <div class="col-md-4 mb-3 mb-md-0">

                        <label class="small font-weight-bold">

                            Kelas

                        </label>

                        <select name="kelas"
                                class="form-control">

                            <option value="">
                                Semua Kelas
                            </option>

                            <?php foreach ($kelas as $k): ?>

                                <option value="<?= $k->id; ?>"
                                    <?= ($kelas_id == $k->id)
                                        ? 'selected'
                                        : ''; ?>>

                                    <?= html_escape($k->nama); ?>
                                    (<?= $k->jumlah_siswa; ?>)

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <!-- BUTTON -->

                    <div class="col-md-2 d-flex align-items-end">

                        <div class="w-100">

                            <button type="submit"
                                    class="btn btn-primary btn-block">

                                <i class="fas fa-filter"></i>
                                Filter

                            </button>

                            <?php if (
                                !empty($keyword) ||
                                !empty($kelas_id)
                            ): ?>

                                <a href="<?= site_url('berkas_siswa'); ?>"
                                   class="btn btn-outline-secondary btn-block mt-2">

                                    <i class="fas fa-sync-alt"></i>
                                    Reset

                                </a>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <!-- ==================================================
         DATA SISWA
    =================================================== -->

    <div class="card shadow-sm mb-4">

        <div class="card-header py-3 d-flex align-items-center justify-content-between">

            <h6 class="m-0 font-weight-bold text-primary">

                <i class="fas fa-users mr-1"></i>
                Daftar Siswa Aktif

            </h6>

            <span class="badge badge-primary">

                <?= number_format($total_rows); ?> siswa

            </span>

        </div>


        <div class="card-body p-0">

            <?php if (!empty($siswa)): ?>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover mb-0">

                        <thead class="thead-light">

                            <tr>

                                <th width="55"
                                    class="text-center">
                                    No
                                </th>

                                <th width="130">
                                    NIS
                                </th>

                                <th width="130">
                                    NISN
                                </th>

                                <th>
                                    Nama Siswa
                                </th>

                                <th width="170">
                                    Kelas
                                </th>

                                <th width="170">
                                    Kelengkapan
                                </th>

                                <th width="100"
                                    class="text-center">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($siswa as $index => $row): ?>

                                <?php

                                $jumlah =
                                    (int) $row->jumlah_berkas;

                                $persen =
                                    ($jumlah / 6) * 100;

                                $nomor =
                                    $offset + $index + 1;

                                ?>

                                <tr>

                                    <!-- NO -->

                                    <td class="text-center">

                                        <?= $nomor; ?>

                                    </td>


                                    <!-- NIS -->

                                    <td>

                                        <?= html_escape(
                                            $row->nis
                                        ); ?>

                                    </td>


                                    <!-- NISN -->

                                    <td>

                                        <?= html_escape(
                                            $row->nisn
                                        ); ?>

                                    </td>


                                    <!-- NAMA -->

                                    <td>

                                        <div class="font-weight-bold">

                                            <?= html_escape(
                                                $row->nama
                                            ); ?>

                                        </div>

                                    </td>


                                    <!-- KELAS -->

                                    <td>

                                        <?php if (
                                            !empty($row->nama_kelas)
                                        ): ?>

                                            <span class="badge badge-light border">

                                                <?= html_escape(
                                                    $row->nama_kelas
                                                ); ?>

                                            </span>

                                        <?php else: ?>

                                            <span class="text-muted">
                                                -
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- KELENGKAPAN -->

                                    <td>

                                        <div class="d-flex justify-content-between mb-1">

                                            <small class="font-weight-bold">

                                                <?= $jumlah; ?>/6

                                            </small>

                                            <small class="text-muted">

                                                <?= round($persen); ?>%

                                            </small>

                                        </div>


                                        <div class="progress"
                                             style="height: 8px;">

                                            <div class="progress-bar
                                                <?= $jumlah == 6
                                                    ? 'bg-success'
                                                    : ''; ?>"
                                                 role="progressbar"
                                                 style="width: <?= $persen; ?>%;">

                                            </div>

                                        </div>

                                    </td>


                                    <!-- AKSI -->

                                    <td class="text-center">

                                        <a href="<?= site_url(
                                            'berkas_siswa/kelola/' .
                                            $row->id
                                        ); ?>"
                                           class="btn btn-primary btn-sm"
                                           title="Kelola Berkas">

                                            <i class="fas fa-folder-open"></i>

                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>


            <?php else: ?>

                <!-- EMPTY -->

                <div class="text-center py-5">

                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>

                    <h6 class="font-weight-bold text-gray-600">

                        Data siswa tidak ditemukan

                    </h6>

                    <p class="text-muted mb-0">

                        Coba ubah kata pencarian atau filter kelas.

                    </p>

                </div>

            <?php endif; ?>

        </div>


        <!-- ==================================================
             FOOTER PAGINATION
        =================================================== -->

        <?php if ($total_rows > 0): ?>

            <div class="card-footer bg-white">

                <div class="row align-items-center">

                    <!-- INFO -->

                    <div class="col-md-6 mb-2 mb-md-0">

                        <small class="text-muted">

                            Menampilkan

                            <strong>
                                <?= $nomor_awal; ?>
                            </strong>

                            sampai

                            <strong>
                                <?= $nomor_akhir; ?>
                            </strong>

                            dari

                            <strong>
                                <?= number_format(
                                    $total_rows
                                ); ?>
                            </strong>

                            siswa

                        </small>

                    </div>


                    <!-- PAGINATION -->

                    <div class="col-md-6">

                        <?= $pagination; ?>

                    </div>

                </div>

            </div>

        <?php endif; ?>

    </div>

</div>