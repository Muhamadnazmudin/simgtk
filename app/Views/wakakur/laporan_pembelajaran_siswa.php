<style>
.table-scroll {
    width: 100%;
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch;
}
.table-scroll table {
    min-width: 1000px;
}
.card,
.card-body,
.container-fluid,
.content-wrapper {
    overflow: visible !important;
}
.table th,
.table td {
    vertical-align: middle;
    white-space: nowrap;
}
.td-alasan {
    white-space: normal;
    min-width: 220px;
}
</style>

<div class="container-fluid">

<h4 class="mb-4">
    <i class="fas fa-chart-line"></i>
    Report Jam Kosong Siswa <small class="text-muted">(Akses Kurikulum)</small>
</h4>

<!-- ================= FILTER ================= -->
<form method="get" class="mb-3">
<div class="form-row">
    <div class="col-md-3">
        <select name="kelas_id" class="form-control">
            <option value="">-- Semua Kelas --</option>
            <?php foreach($kelas as $k): ?>
                <option value="<?= $k->id ?>"
                    <?= $this->input->get('kelas_id')==$k->id?'selected':'' ?>>
                    <?= $k->nama ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-3">
        <select name="guru_id" class="form-control">
            <option value="">-- Semua Guru --</option>
            <?php foreach($guru as $g): ?>
                <option value="<?= $g->id ?>"
                    <?= $this->input->get('guru_id')==$g->id?'selected':'' ?>>
                    <?= $g->nama ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-3">
        <input type="date" name="tanggal"
               value="<?= $this->input->get('tanggal') ?>"
               class="form-control">
    </div>

    <div class="col-md-3">
        <button class="btn btn-primary btn-block">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>
</div>
</form>

<div class="mb-3 text-right">
    <a href="<?= site_url('WakakurLaporanPembelajaran/export_excel?'.$_SERVER['QUERY_STRING']) ?>"
       class="btn btn-success btn-sm">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>

    <a href="<?= site_url('WakakurLaporanPembelajaran/export_pdf?'.$_SERVER['QUERY_STRING']) ?>"
       class="btn btn-danger btn-sm">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>
</div>

<!-- ================= TABEL ================= -->
<small class="text-muted d-block d-md-none mb-2">
    ← Geser tabel ke kanan →
</small>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-scroll">
            <table class="table table-bordered table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Kelas</th>
                        <th>Ketua Kelas</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Mapel</th>
                        <th>Guru</th>
                        <th>Alasan</th>
                    </tr>
                </thead>
                <tbody>

                <?php if (empty($laporan)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Tidak ada laporan.
                        </td>
                    </tr>
                <?php else: foreach ($laporan as $l): ?>
                    <tr>
                        <td><?= date('d-m-Y', strtotime($l->tanggal)) ?></td>
                        <td><?= $l->nama_kelas ?></td>
                        <td><?= $l->nama_siswa ?></td>
                        <td><?= $l->hari ?></td>
                        <td>
                            <?= $l->jam_awal ?> (<?= substr($l->jam_mulai,0,5) ?>)
                            –
                            <?= $l->jam_akhir ?> (<?= substr($l->jam_selesai,0,5) ?>)
                        </td>
                        <td><?= $l->nama_mapel ?></td>
                        <td><?= $l->nama_guru ?></td>
                        <td class="td-alasan">
                            <?= nl2br($l->alasan) ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
