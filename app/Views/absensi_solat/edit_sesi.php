<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid py-3">
    <div class="d-sm-flex align-items-center justify-content-between mb-4"><div><h1 class="h3 mb-1 text-gray-800"><i class="fas fa-edit text-primary mr-2"></i>Edit Sesi Absensi</h1><p class="text-muted mb-0">Perubahan waktu tidak menghapus data absensi yang sudah tercatat.</p></div><a href="<?= site_url('absensi_solat'); ?>" class="btn btn-light border btn-sm"><i class="fas fa-arrow-left mr-1"></i>Kembali</a></div>
    <?php if ($this->session->flashdata('error')): ?><div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')); ?></div><?php endif; ?>
    <div class="row"><div class="col-lg-7"><div class="card shadow"><div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Sesi Dzuhur tanggal <?= html_escape($sesi->tanggal); ?></h6></div><div class="card-body">
        <?= form_open('absensi_solat/edit_sesi/' . (int) $sesi->id); ?>
            <div class="form-group"><label for="tanggal">Tanggal</label><input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= html_escape($sesi->tanggal); ?>" required></div>
            <div class="form-row"><div class="form-group col-md-6"><label for="jam_mulai">Jam Mulai</label><input type="time" id="jam_mulai" name="jam_mulai" class="form-control" value="<?= html_escape(substr($sesi->jam_mulai, 0, 5)); ?>" required></div><div class="form-group col-md-6"><label for="jam_selesai">Jam Selesai</label><input type="time" id="jam_selesai" name="jam_selesai" class="form-control" value="<?= html_escape(substr($sesi->jam_selesai, 0, 5)); ?>" required></div></div>
            <div class="alert alert-warning small">Status sesi saat ini: <strong><?= $sesi->status === 'buka' ? 'Dibuka' : 'Ditutup'; ?></strong>. Form ini hanya mengubah tanggal dan waktu, bukan status sesi.</div>
            <button type="submit" class="btn btn-primary" onclick="return confirm('Simpan perubahan sesi?');"><i class="fas fa-save mr-1"></i>Simpan Perubahan</button><a href="<?= site_url('absensi_solat'); ?>" class="btn btn-light">Batal</a>
        <?= form_close(); ?>
    </div></div></div></div>
</div>
