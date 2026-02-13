<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 font-weight-bold text-primary">
            <i class="fas fa-id-card"></i> ID Card Premium
        </h4>
    </div>

    <!-- FILTER -->
    <div class="card shadow mb-4">
        <div class="card-body">

            <form method="get">
                <div class="form-row">

                    <div class="col-md-4 mb-2">
                        <input type="text"
                               name="nama"
                               value="<?= $this->input->get('nama') ?>"
                               class="form-control"
                               placeholder="Cari Nama Siswa...">
                    </div>

                    <div class="col-md-4 mb-2">
                        <select name="kelas" class="form-control">
                            <option value="">-- Semua Kelas --</option>
                            <?php foreach($kelas_list as $k): ?>
                                <option value="<?= $k->id ?>"
                                    <?= $this->input->get('kelas') == $k->id ? 'selected':'' ?>>
                                    <?= $k->nama ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="<?= site_url('idcard_premium') ?>" 
                           class="btn btn-secondary">
                           Reset
                        </a>
                    </div>
                <a href="<?= site_url('idcard_premium/cetak_kelas/'.$this->input->get('kelas')) ?>" 
   target="_blank"
   class="btn btn-danger">
   <i class="fas fa-print"></i> Cetak 1 Kelas
</a>

                </div>
            </form>

        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-hover table-bordered align-middle">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th width="15%">NIS</th>
                            <th width="20%">Kelas</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if(!empty($siswa)): ?>
                            <?php $no=1; foreach($siswa as $s): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $s->nama ?></td>
                                <td><?= $s->nis ?></td>
                                <td><?= $s->nama_kelas ?></td>
                                <td class="text-center">
                                    <a href="<?= site_url('idcard_premium/preview/'.$s->id) ?>"
                                       target="_blank"
                                       class="btn btn-success btn-sm">
                                       <i class="fas fa-eye"></i> Preview
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Data tidak ditemukan
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>

</div>
