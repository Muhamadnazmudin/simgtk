<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-alt text-primary mr-2"></i>
            <?= esc($title ?? 'Laporan Izin Keluar Siswa') ?>
        </h1>
        <a href="<?= site_url('izin') ?>" class="btn btn-secondary shadow-sm btn-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Data Izin
        </a>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter mr-1"></i> Filter Laporan</h6>
        </div>
        <div class="card-body">
            <form method="get" action="<?= site_url('izin/laporan') ?>">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-3 mb-2">
                        <label class="small font-weight-bold">Dari Tanggal</label>
                        <input type="date" name="dari" class="form-control form-control-sm" value="<?= esc($dari ?? '') ?>">
                    </div>
                    <div class="form-group col-md-3 mb-2">
                        <label class="small font-weight-bold">Sampai Tanggal</label>
                        <input type="date" name="sampai" class="form-control form-control-sm" value="<?= esc($sampai ?? '') ?>">
                    </div>
                    <div class="form-group col-md-3 mb-2">
                        <label class="small font-weight-bold">Kelas</label>
                        <select name="kelas" class="form-control form-control-sm">
                            <option value="">-- Semua Kelas --</option>
                            <?php foreach ($kelas as $k): ?>
                                <option value="<?= esc($k->id) ?>" <?= ($filter_kls == $k->id) ? 'selected' : '' ?>>
                                    <?= esc($k->nama) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <i class="fas fa-search mr-1"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
            <h6 class="m-0 font-weight-bold text-primary">
                Hasil Pencarian: <strong><?= number_format(count($list ?? [])) ?></strong> data
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0 text-nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th width="40" class="text-center">No</th>
                            <th>Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Jenis Izin</th>
                            <th>Jam Keluar</th>
                            <th>Jam Kembali</th>
                            <th>Status</th>
                            <th>Keperluan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($list)): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-2 d-block"></i>
                                    Tidak ada data izin pada rentang tanggal yang dipilih.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($list as $r): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= esc(date('d-m-Y', strtotime($r->tanggal))) ?></td>
                                    <td><strong><?= esc($r->nama_siswa ?? '-') ?></strong></td>
                                    <td><?= esc($r->nama_kelas ?? '-') ?></td>
                                    <td>
                                        <span class="badge badge-<?= ($r->jenis_izin === 'pulang') ? 'danger' : 'info' ?>">
                                            <?= esc(ucfirst($r->jenis_izin ?? 'keluar')) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($r->jam_keluar ? substr($r->jam_keluar, 0, 5) : '-') ?></td>
                                    <td><?= esc($r->jam_masuk ? substr($r->jam_masuk, 0, 5) : '-') ?></td>
                                    <td>
                                        <?php if ($r->status === 'kembali'): ?>
                                            <span class="badge badge-success">Kembali</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Di Luar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($r->keperluan ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
