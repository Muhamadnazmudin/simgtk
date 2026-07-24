<div class="container-fluid">

    <div class="card shadow mb-4">

        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                <i class="fas fa-user-times mr-2"></i>
                Data Siswa Keluar
            </h5>

            <span class="badge badge-light px-3 py-2">
                <?= count($siswa) ?> Siswa
            </span>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="thead-light">

                    <tr class="text-center">

                        <th width="5%">No</th>
                        <th width="10%">Tanggal</th>
                        <th width="22%">Identitas Siswa</th>
                        <th width="12%">Kelas Terakhir</th>
                        <th width="15%">Jenis Keluar</th>
                        <th>Tujuan / Alasan</th>
                        <th width="8%">Berkas</th>

                    </tr>

                    </thead>

                    <tbody>

                    <?php if (!empty($siswa)): ?>

                        <?php $no = 1; ?>

                        <?php foreach ($siswa as $row): ?>

                            <?php

                            $warna = 'secondary';

                            switch (strtolower($row->jenis_keluar)) {

                                case 'mutasi':
                                    $warna = 'primary';
                                    break;

                                case 'mengundurkan diri':
                                    $warna = 'warning';
                                    break;

                                case 'lulus':
                                    $warna = 'success';
                                    break;

                                case 'do':
                                    $warna = 'danger';
                                    break;

                            }

                            ?>

                            <tr>

                                <td class="text-center align-middle">
                                    <?= $no++ ?>
                                </td>

                                <td class="text-center align-middle">

                                    <?= !empty($row->tanggal)
                                        ? date('d-m-Y', strtotime($row->tanggal))
                                        : '-' ?>

                                </td>

                                <td>

                                    <strong><?= $row->nama ?></strong>

                                    <div class="small text-muted mt-1">

                                        <i class="fas fa-id-card"></i>

                                        NIS :
                                        <?= $row->nis ?>

                                        <br>

                                        <i class="fas fa-barcode"></i>

                                        NISN :
                                        <?= $row->nisn ?>

                                    </div>

                                </td>

                                <td class="text-center align-middle">

                                    <?= $row->nama_kelas ?>

                                </td>

                                <td class="text-center align-middle">

                                    <span class="badge badge-<?= $warna ?> px-3 py-2">

                                        <?= ucwords($row->jenis_keluar) ?>

                                    </span>

                                </td>

                                <td>

                                    <?php if (!empty($row->tujuan_sekolah)): ?>

                                        <strong>Tujuan :</strong>

                                        <?= $row->tujuan_sekolah ?>

                                        <br>

                                    <?php endif; ?>

                                    <?php if (!empty($row->alasan)): ?>

                                        <small class="text-muted">

                                            <?= $row->alasan ?>

                                        </small>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

                                </td>

                                <td class="text-center align-middle">

                                    <?php if (!empty($row->berkas)): ?>

                                        <a href="<?= base_url('uploads/mutasi/' . $row->berkas) ?>"
                                           target="_blank"
                                           class="btn btn-sm btn-info">

                                            <i class="fas fa-file-pdf"></i>

                                        </a>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="7" class="text-center py-5">

                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>

                                <br>

                                <span class="text-muted">

                                    Belum ada data siswa keluar.

                                </span>

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>