<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$sesi_hari_ini = [];
$sesi_lainnya = [];

if (!empty($sesi)) {
    foreach ($sesi as $item) {
        if ($item->tanggal === date('Y-m-d')) {
            $sesi_hari_ini[] = $item;
        } else {
            $sesi_lainnya[] = $item;
        }
    }
}
?>

<div class="container-fluid py-3">

    <!-- HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">
                <i class="fas fa-mosque text-primary mr-2"></i>
                Absensi Salat Dzuhur
            </h1>
            <p class="text-muted mb-0">Kelola sesi dan pantau kehadiran salat siswa.</p>
        </div>

        <div class="mt-3 mt-sm-0">
            <button type="button" class="btn btn-primary btn-sm shadow-sm"
                    data-toggle="modal" data-target="#modalBuatSesi">
                <i class="fas fa-plus mr-1"></i> Buat Sesi
            </button>
            <a href="<?= site_url('absensi_solat/import_jadwal'); ?>"
               class="btn btn-success btn-sm shadow-sm">
                <i class="fas fa-file-import mr-1"></i> Import Jadwal
            </a>
            <a href="<?= site_url('absensi_solat/rekap'); ?>"
               class="btn btn-outline-primary btn-sm shadow-sm">
                <i class="fas fa-clipboard-list mr-1"></i> Rekap Absensi
            </a>
        </div>
    </div>

    <!-- FLASH MESSAGE -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i>
            <?= html_escape($this->session->flashdata('success')); ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-1"></i>
            <?= html_escape($this->session->flashdata('error')); ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php endif; ?>

    <!-- RINGKASAN -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Sesi Tahun Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= (int) ($statistik_sesi ?? 0); ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-calendar-alt fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Kehadiran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= (int) ($statistik_hadir ?? 0); ?> siswa</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-user-check fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tahun Ajaran Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">ID <?= (int) $tahun_id; ?></div>
                            <div class="small text-muted mt-2">Mengikuti tahun aktif aplikasi.</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-graduation-cap fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SESI HARI INI -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-calendar-day mr-2"></i>Sesi Hari Ini</h6>
            <span class="badge badge-light"><?= html_escape(date('d-m-Y')); ?></span>
        </div>

        <div class="card-body">
            <?php if (!empty($sesi_hari_ini)): ?>
                <?php foreach ($sesi_hari_ini as $item): ?>
                    <?php
                    $solatModel = $solat ?? ($this->solat ?? model(\App\Models\AbsensiSolatModel::class));
                    $stat = $solatModel->get_statistik($item->id);
                    $status_buka = $item->status === 'buka';
                    ?>
                    <div class="border rounded p-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-lg-3 mb-3 mb-lg-0">
                                <div class="small text-muted mb-1">JENIS SALAT</div>
                                <div class="font-weight-bold text-gray-800"><i class="fas fa-mosque text-primary mr-1"></i><?= ucfirst(html_escape($item->jenis_solat)); ?></div>
                            </div>
                            <div class="col-lg-3 mb-3 mb-lg-0">
                                <div class="small text-muted mb-1">WAKTU ABSENSI</div>
                                <div class="font-weight-bold text-gray-800"><?= html_escape(substr($item->jam_mulai, 0, 5)); ?> - <?= html_escape(substr($item->jam_selesai, 0, 5)); ?></div>
                            </div>
                            <div class="col-lg-3 mb-3 mb-lg-0">
                                <div class="small text-muted mb-1">KEHADIRAN</div>
                                <div class="font-weight-bold text-gray-800"><?= (int) $stat['total_hadir']; ?> siswa</div>
                                <div class="small text-muted">Terlambat: <?= (int) $stat['terlambat']; ?></div>
                            </div>
                            <div class="col-lg-3 text-lg-right">
                                <?php if ($status_buka): ?>
                                    <span class="badge badge-success mb-2"><i class="fas fa-circle mr-1"></i>Dibuka</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary mb-2">Ditutup</span>
                                <?php endif; ?>
                                <div class="mt-2">
                                    <a href="<?= site_url('absensi_solat/edit_sesi/' . $item->id); ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-edit mr-1"></i>Edit</a>
                                    <a href="<?= site_url('absensi_solat/scan/' . $item->id); ?>" class="btn btn-primary btn-sm"><i class="fas fa-qrcode mr-1"></i>Buka Scanner</a>
                                    <?php if ($status_buka): ?>
                                        <?= form_open('absensi_solat/tutup_sesi/' . $item->id, ['class' => 'd-inline']); ?>
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Tutup sesi ini?');">Tutup</button>
                                        <?= form_close(); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-gray-300 mb-3"></i>
                    <h6 class="font-weight-bold text-gray-700">Belum Ada Sesi Hari Ini</h6>
                    <p class="text-muted small mb-3">Buat sesi secara manual dengan menentukan tanggal, jam mulai, dan jam selesai.</p>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalBuatSesi">
                        <i class="fas fa-plus mr-1"></i> Buat Sesi Sekarang
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- RIWAYAT SESI -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history mr-2"></i>Riwayat Sesi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%">
                    <thead class="thead-light">
                        <tr><th>Tanggal</th><th>Jenis Salat</th><th>Waktu</th><th>Status</th><th class="text-center">Aksi</th></tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($sesi_lainnya)): ?>
                        <?php foreach ($sesi_lainnya as $item): ?>
                            <tr>
                                <td><?= html_escape($item->tanggal); ?></td>
                                <td><?= ucfirst(html_escape($item->jenis_solat)); ?></td>
                                <td><?= html_escape(substr($item->jam_mulai, 0, 5)); ?> - <?= html_escape(substr($item->jam_selesai, 0, 5)); ?></td>
                                <td>
                                    <?php if ($item->status === 'buka'): ?>
                                        <span class="badge badge-success">Dibuka</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Ditutup</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= site_url('absensi_solat/edit_sesi/' . $item->id); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit mr-1"></i>Edit</a>
                                    <a href="<?= site_url('absensi_solat/scan/' . $item->id); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-qrcode mr-1"></i>Scanner</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat sesi sebelumnya.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL BUAT SESI MANUAL -->
<div class="modal fade" id="modalBuatSesi" tabindex="-1" role="dialog" aria-labelledby="modalBuatSesiLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= form_open('absensi_solat/buat_sesi'); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBuatSesiLabel"><i class="fas fa-calendar-plus mr-2 text-primary"></i>Buat Sesi Absensi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= html_escape(date('Y-m-d')); ?>" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="jam_mulai">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="11:30" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="jam_selesai">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" value="12:00" required>
                        </div>
                    </div>
                    <small class="text-muted">Sesi tidak akan dibuat otomatis. Pastikan tanggal dan waktu sudah benar sebelum menyimpan.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Simpan Sesi</button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
