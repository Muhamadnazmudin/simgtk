<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Mutasi</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Riwayat Mutasi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr><th>Tanggal</th><th>Jenis Mutasi</th><th>Keterangan</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($mutasi)): ?>
                            <tr><td colspan="3" class="text-center text-muted">Belum ada riwayat mutasi.</td></tr>
                        <?php else: ?>
                            <?php foreach($mutasi as $m): ?>
                                <tr>
                                    <td><?= esc($m->tanggal) ?></td>
                                    <td><?= esc(ucfirst($m->jenis)) ?></td>
                                    <td><?= esc($m->keterangan) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
