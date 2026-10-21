<?php
$isOperator = ($this->session->userdata('role_name') == 'operator');
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Data Kelas</h4>

    <div>

        <a href="<?= site_url('kelas/export_excel') ?>" class="btn btn-success btn-sm">
            <i class="fas fa-file-excel"></i> Export
        </a>

        <?php if (!$isOperator): ?>

            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#importModal">
                <i class="fas fa-upload"></i> Import
            </button>

            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#addModal">
                <i class="fas fa-plus"></i> Tambah
            </button>

        <?php endif; ?>

    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i>
    <?= $this->session->flashdata('success'); ?>
    <button type="button" class="close" data-dismiss="alert">
      <span>&times;</span>
    </button>
  </div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>

<div class="alert alert-danger alert-dismissible fade show">

    <i class="fas fa-exclamation-circle"></i>

    <?= $this->session->flashdata('error') ?>

    <button class="close" data-dismiss="alert">

        <span>&times;</span>

    </button>

</div>

<?php endif; ?>

<table class="table table-bordered table-hover table-striped table-responsive-sm">
    <thead class="thead-dark text-center">
        <tr>
            <th width="50">No</th>
            <th>Nama Kelas</th>
            <th>Jurusan</th>
            <th>Wali Kelas</th>
            <th>Ketua Kelas</th>
            <th width="90">Kapasitas</th>
            <th width="100">Jml Siswa</th>
            <th width="100">Status</th>
            <th width="140">Aksi</th>
        </tr>
    </thead>

    <tbody>

    <?php if(empty($kelas)): ?>

        <tr>
            <td colspan="9" class="text-center text-muted">
                Tidak ada data kelas.
            </td>
        </tr>

    <?php else: ?>

        <?php $no = $start + 1; ?>

        <?php foreach($kelas as $k): ?>

        <tr>

            <td class="text-center">
                <?= $no++ ?>
            </td>

            <td>
                <strong><?= $k->nama ?></strong>
            </td>

            <td>
                <?= $k->jurusan ?: '-' ?>
            </td>

            <td>
                <?= $k->wali_nama ?: '-' ?>
            </td>

            <td>
                <?= $k->ketua_nama ?: '-' ?>
            </td>

            <td class="text-center">
                <?= $k->kapasitas ?>
            </td>

            <td class="text-center">

                <span class="badge badge-info">

                    <?= $k->jumlah_siswa ?>

                </span>

            </td>

            <td class="text-center">

                <?php if($k->is_active == 1): ?>

                    <span class="badge badge-success">
                        Aktif
                    </span>

                <?php else: ?>

                    <span class="badge badge-danger">
                        Nonaktif
                    </span>

                <?php endif; ?>

            </td>

            <td class="text-center">

<?php if (!$isOperator): ?>

    <?php if($k->is_active == 1): ?>

        <a href="<?= site_url('kelas/edit/'.$k->id) ?>"
           class="btn btn-warning btn-sm"
           title="Edit">
            <i class="fas fa-edit"></i>
        </a>

        <a href="<?= site_url('kelas/nonaktif/'.$k->id) ?>"
           class="btn btn-danger btn-sm"
           title="Nonaktifkan"
           onclick="return confirm('Yakin ingin menonaktifkan kelas <?= $k->nama ?> ?')">
            <i class="fas fa-ban"></i>
        </a>

    <?php else: ?>

        <a href="<?= site_url('kelas/aktif/'.$k->id) ?>"
           class="btn btn-success btn-sm"
           title="Aktifkan"
           onclick="return confirm('Aktifkan kembali kelas <?= $k->nama ?> ?')">
            <i class="fas fa-check"></i>
        </a>

    <?php endif; ?>

<?php else: ?>

    <span class="badge badge-secondary">View Only</span>

<?php endif; ?>

</td>

        </tr>

        <?php endforeach; ?>

    <?php endif; ?>

    </tbody>

</table>

<?= $pagination ?>

<!-- ========================= MODAL TAMBAH ========================= -->
<div class="modal fade" id="addModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="<?= site_url('kelas/add') ?>">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Kelas</h5>
        </div>
        <div class="modal-body">

          <input type="hidden"
                 name="<?= $this->security->get_csrf_token_name(); ?>"
                 value="<?= $this->security->get_csrf_hash(); ?>">

          <div class="form-group">
            <label>Nama Kelas</label>
            <input type="text" name="nama" class="form-control" required>
          </div>
<div class="form-group">
    <label>Jurusan</label>
    <input type="text"
           name="jurusan"
           class="form-control"
           placeholder="Contoh : MPLB"
           required>
</div>
          <div class="form-group">
            <label>Wali Kelas</label>
            <select name="wali_kelas_id" class="form-control">
              <option value="">-- Pilih Guru --</option>
              <?php foreach ($guru as $g): ?>
                <option value="<?= $g->id ?>"><?= $g->nama ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Ketua Kelas</label>
            <select name="ketua_kelas_id" class="form-control">
              <option value="">-- Pilih Siswa --</option>
              <?php foreach ($siswa as $s): ?>
                <option value="<?= $s->id ?>"><?= $s->nama ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Kapasitas</label>
            <input type="number" name="kapasitas" class="form-control" value="30">
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ========================= MODAL IMPORT ========================= -->
<div class="modal fade" id="importModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post"
            action="<?= site_url('kelas/import_excel') ?>"
            enctype="multipart/form-data">

        <div class="modal-header">
          <h5 class="modal-title">Import Data Kelas</h5>
        </div>

        <div class="modal-body">
          <input type="hidden"
                 name="<?= $this->security->get_csrf_token_name(); ?>"
                 value="<?= $this->security->get_csrf_hash(); ?>">

          <div class="form-group">
            <label>Pilih File Excel</label>
            <input type="file"
                   name="file"
                   accept=".xls,.xlsx"
                   class="form-control"
                   required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Import</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>

      </form>
    </div>
  </div>
</div>

<style>
.pagination .page-item .page-link {
  color: #007bff;
  border: 1px solid #dee2e6;
  margin: 0 2px;
  border-radius: 6px;
}
.pagination .page-item.active .page-link {
  background-color: #007bff;
  color: #fff;
}
.pagination .page-item .page-link:hover {
  background-color: #007bff;
  color: #fff;
}
</style>
