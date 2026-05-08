<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Edit Data Kelas</h4>
  <a href="<?= site_url('kelas') ?>" class="btn btn-secondary btn-sm">
    <i class="fas fa-arrow-left"></i> Kembali
  </a>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="post" action="<?= site_url('kelas/edit/'.$kelas->id) ?>">

      <input type="hidden"
             name="<?= $this->security->get_csrf_token_name(); ?>"
             value="<?= $this->security->get_csrf_hash(); ?>">

      <!-- Nama Kelas -->
      <div class="form-group">
        <label>Nama Kelas</label>
        <input type="text"
               name="nama"
               class="form-control"
               value="<?= $kelas->nama ?>"
               required>
      </div>
      <!-- Jurusan -->
<div class="form-group">
  <label>Jurusan</label>
  <input type="text"
         name="jurusan"
         class="form-control"
         value="<?= $kelas->jurusan ?? '' ?>"
         required>
</div>
      <!-- Wali Kelas -->
      <div class="form-group">
        <label>Wali Kelas</label>
        <select name="wali_kelas_id" class="form-control">
          <option value="">-- Pilih Guru --</option>
          <?php foreach ($guru as $g): ?>
            <option value="<?= $g->id ?>"
              <?= ($g->id == $kelas->wali_kelas_id ? 'selected' : '') ?>>
              <?= $g->nama ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Ketua Kelas -->
      <div class="form-group">
        <label>Ketua Kelas</label>
        <select name="ketua_kelas_id" class="form-control">
          <option value="">-- Pilih Siswa --</option>
          <?php foreach ($siswa as $s): ?>
            <option value="<?= $s->id ?>"
              <?= ($s->id == $kelas->ketua_kelas_id ? 'selected' : '') ?>>
              <?= $s->nama ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Kapasitas -->
      <div class="form-group">
        <label>Kapasitas</label>
        <input type="number"
               name="kapasitas"
               class="form-control"
               value="<?= $kelas->kapasitas ?>"
               required>
      </div>

      <div class="text-right">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i> Simpan Perubahan
        </button>
      </div>

    </form>
  </div>
</div>
