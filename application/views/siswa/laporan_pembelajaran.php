<div class="container-fluid">

<h4 class="mb-4">
    <i class="fas fa-file-alt"></i> Laporan Jam Kosong (Ketua Kelas)
</h4>

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success">
    <?= $this->session->flashdata('success') ?>
</div>
<?php endif; ?>

<div class="card mb-4">
<div class="card-body">

<form method="post" action="<?= site_url('SiswaLaporanPembelajaran/simpan') ?>">
    <input type="hidden"
       name="<?= $this->security->get_csrf_token_name(); ?>"
       value="<?= $this->security->get_csrf_hash(); ?>">

<div class="form-row">
    <div class="col-md-3">
        <label>Hari</label>
        <select name="hari" id="hari" class="form-control" required>
            <option value="">-- Pilih Hari --</option>
            <option>Senin</option>
            <option>Selasa</option>
            <option>Rabu</option>
            <option>Kamis</option>
            <option>Jumat</option>
            <option>Sabtu</option>
        </select>
    </div>

    <div class="col-md-3">
        <label>Jam Awal</label>
        <select name="jam_mulai_id" id="jamMulai" class="form-control" required>
            <option value="">-- Jam Awal --</option>
        </select>
    </div>

    <div class="col-md-3">
        <label>Jam Akhir</label>
        <select name="jam_selesai_id" id="jamSelesai" class="form-control" required>
            <option value="">-- Jam Akhir --</option>
        </select>
    </div>
</div>

<div class="form-row mt-3">
    <div class="col-md-6">
        <label>Mapel</label>
        <select name="mapel_id" class="form-control select2" required>
            <option value="">-- Pilih Mapel --</option>
            <?php foreach($mapel as $m): ?>
                <option value="<?= $m->id_mapel ?>">
                    <?= $m->nama_mapel ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-6">
        <label>Guru</label>
        <select name="guru_id" class="form-control select2" required>
            <option value="">-- Pilih Guru --</option>
            <?php foreach($guru as $g): ?>
                <option value="<?= $g->id ?>">
                    <?= $g->nama ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-group mt-3">
    <label>Alasan</label>
    <textarea name="alasan" class="form-control" rows="3" required></textarea>
</div>

<button class="btn btn-danger">
    <i class="fas fa-paper-plane"></i> Kirim Laporan
</button>

</form>
<?php if (empty($laporan)): ?>
    <div class="alert alert-info">
        Belum ada laporan jam kosong.
    </div>
<?php else: ?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Hari</th>
            <th>Jam</th>
            <th>Mapel</th>
            <th>Guru</th>
            <th>Alasan</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($laporan as $l): ?>
        <tr>
            <td><?= $l->tanggal ?></td>
            <td><?= $l->hari ?></td>
            <td>
                <?= $l->jam_mulai ?> – <?= $l->jam_selesai ?>
            </td>
            <td><?= $l->nama_mapel ?></td>
            <td><?= $l->nama_guru ?></td>
            <td><?= $l->alasan ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

</div>
</div>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // INIT SELECT2
    if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
        $('.select2').select2({
            width: '100%'
        });
    }

    const hari       = document.getElementById('hari');
    const jamMulai   = document.getElementById('jamMulai');
    const jamSelesai = document.getElementById('jamSelesai');

    let jamData = [];

    function resetJam() {
        jamMulai.innerHTML   = '<option value="">-- Jam Awal --</option>';
        jamSelesai.innerHTML = '<option value="">-- Jam Akhir --</option>';
    }

    // LOAD JAM BY HARI
    hari.addEventListener('change', function () {
        resetJam();

        if (!this.value) return;

        fetch(`<?= site_url('SiswaLaporanPembelajaran/ajax_jam_by_hari') ?>?hari=${this.value}`)
            .then(res => res.json())
            .then(data => {
                jamData = data;

                data.forEach(jam => {
                    const opt = document.createElement('option');
                    opt.value = jam.id_jam;
                    opt.textContent =
                        `${jam.nama_jam} (${jam.jam_mulai.substring(0,5)}–${jam.jam_selesai.substring(0,5)})`;
                    jamMulai.appendChild(opt);
                });
            })
            .catch(err => console.error(err));
    });

    // JAM AKHIR FILTER
    jamMulai.addEventListener('change', function () {
        jamSelesai.innerHTML = '<option value="">-- Jam Akhir --</option>';

        const mulaiId = parseInt(this.value);
        if (!mulaiId) return;

        jamData
            .filter(j => parseInt(j.id_jam) >= mulaiId)
            .forEach(jam => {
                const opt = document.createElement('option');
                opt.value = jam.id_jam;
                opt.textContent =
                    `${jam.nama_jam} (${jam.jam_mulai.substring(0,5)}–${jam.jam_selesai.substring(0,5)})`;
                jamSelesai.appendChild(opt);
            });
    });

});
</script>
