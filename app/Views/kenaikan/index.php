<div class="content-wrapper mt-4 px-4">
  <h4 class="fw-bold mb-3">Kenaikan Kelas Siswa</h4>

  <form method="get" class="mb-3">
    <label>Pilih Kelas</label>
    <select name="kelas_id" class="form-control" onchange="this.form.submit()">
      <option value="">-- Pilih Kelas / Rombel --</option>
      <?php foreach($kelas as $k): ?>
        <option value="<?= $k->id ?>" <?= ($this->input->get('kelas_id') == $k->id ? 'selected' : '') ?>><?= $k->nama ?></option>
      <?php endforeach; ?>
    </select>
  </form>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
  <?php endif; ?>

  <?php if (!empty($siswa)): ?>
    <div class="card mb-3">
    <div class="card-body">

        <button
            type="button"
            class="btn btn-success"
            onclick="naikSemua()">

            <i class="fas fa-level-up-alt"></i>
            Naikkan Semua

        </button>

    </div>
</div>
  <form method="post" action="<?= site_url('kenaikan/simpan_massal') ?>">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
           value="<?= $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="kelas_id" value="<?= $this->input->get('kelas_id'); ?>">

    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>No</th>
          <th>Nama Siswa</th>
          <th>NIS</th>
          <th>Kelas Sekarang</th>
          <th>Naik ke</th>
        </tr>
      </thead>
      <tbody>
        <?php $no=1; foreach($siswa as $s): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= $s->nama ?></td>
          <td><?= $s->nis ?></td>
          <td><?= $s->nama_kelas ?></td>
          <td>
            <input type="hidden" name="siswa_id[]" value="<?= $s->id ?>">
            <select
    name="kelas_tujuan[]"
    class="form-control form-control-sm kelas-tujuan">
              <?php

if (stripos($s->nama_kelas, 'XII') !== false) {

    echo '<option value="lulus">Lulus</option>';

} else {

    echo '<option value="">-- Pilih --</option>';

    foreach ($kelas as $k) {

        if ($k->id == $s->id_kelas) {
            continue;
        }

        echo '<option
                value="'.$k->id.'"
                data-kelas="'.$k->nama.'">

                '.$k->nama.'

              </option>';
    }

}
?>
            </select>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="text-end">
      <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Simpan Kenaikan
      </button>
    </div>
  </form>
  <?php else: ?>
    <div class="alert alert-info">Silakan pilih kelas terlebih dahulu untuk melihat data siswa.</div>
  <?php endif; ?>
</div>
<script>

function naikSemua() {

    const mapping = {
        'MPLB': 'MP',
        'AKL'  : 'AK',
        'PH'   : 'PH',
        'ULP' : 'ULW',
        'PPLG' : 'PPLG',
        'DKV'  : 'DKV'
    };

    $('.kelas-tujuan').each(function () {

        let select = $(this);

        let kelasSekarang = select.closest('tr')
            .find('td:eq(3)')
            .text()
            .trim()
            .toUpperCase();

        let tujuan = '';

        // =========================
        // KELAS X
        // =========================
        let x = kelasSekarang.match(/^X\s+([A-Z]+)(\d+)$/);

        if (x) {

            let jurusan = x[1];
            let rombel  = x[2];

            if (mapping[jurusan]) {
                jurusan = mapping[jurusan];
            }

            tujuan = 'XI ' + jurusan + rombel;
        }

        // =========================
        // KELAS XI
        // =========================
        let xi = kelasSekarang.match(/^XI\s+([A-Z]+)(\d+)$/);

        if (xi) {

            tujuan = 'XII ' + xi[1] + xi[2];

        }

        select.find('option').each(function () {

            let namaKelas = $(this).data('kelas');

if (!namaKelas) {
    return;
}

if (namaKelas.toUpperCase() === tujuan) {
    select.val($(this).val());
    return false;
}

        });

    });

}

</script>