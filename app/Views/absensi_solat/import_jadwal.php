<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid py-3">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div><h1 class="h3 mb-1 text-gray-800"><i class="fas fa-file-import text-success mr-2"></i>Import Jadwal Sesi Salat</h1>
        <p class="text-muted mb-0">Import jadwal harian untuk satu bulan atau satu tahun sekaligus.</p></div>
        <a href="<?= site_url('absensi_solat'); ?>" class="btn btn-light btn-sm border"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>
    </div>
    <?php if ($this->session->flashdata('success')): ?><div class="alert alert-success"><?= html_escape($this->session->flashdata('success')); ?></div><?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?><div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')); ?></div><?php endif; ?>
    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card shadow h-100"><div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">1. Download Template Excel</h6></div>
                <div class="card-body">
                    <p class="text-muted">Pilih tanggal awal dan akhir. Template akan dibuat satu baris per tanggal dengan jam awal 11:30–12:00 yang bisa diedit.</p>
                    <?= form_open('absensi_solat/download_template', ['method' => 'get']); ?>
                        <div class="form-group"><label>Tanggal Mulai</label><input type="date" name="tanggal_mulai" class="form-control" value="<?= date('Y-m-01'); ?>" required></div>
                        <div class="form-group"><label>Tanggal Selesai</label><input type="date" name="tanggal_selesai" class="form-control" value="<?= date('Y-m-t'); ?>" required></div>
                        <button type="submit" class="btn btn-outline-success"><i class="fas fa-download mr-1"></i>Download Template</button>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-7 mb-4">
            <div class="card shadow h-100"><div class="card-header py-3"><h6 class="m-0 font-weight-bold text-success">2. Import Jadwal</h6></div>
                <div class="card-body">
                    <div class="alert alert-info"><strong>Perhatian:</strong> sesi hasil import dibuat dengan status <strong>Ditutup</strong>. Admin membuka sesi secara manual saat diperlukan. Tanggal yang sudah memiliki sesi akan dilewati, bukan ditimpa.</div>
                    <?= form_open_multipart('absensi_solat/import_proses'); ?>
                        <div class="form-group"><label>File Jadwal (.xlsx, .xls, .csv)</label><input type="file" name="file_jadwal" class="form-control-file" accept=".xlsx,.xls,.csv" required></div>
                        <small class="form-text text-muted mb-3">Kolom harus berurutan: Tanggal (YYYY-MM-DD), Jam Mulai (HH:MM), Jam Selesai (HH:MM). Jangan menghapus baris header.</small>
                        <button type="submit" class="btn btn-success" onclick="return confirm('Import jadwal dari file ini? Sesi yang sudah ada tidak akan ditimpa.');"><i class="fas fa-upload mr-1"></i>Import Jadwal</button>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow"><div class="card-body"><h6 class="font-weight-bold"><i class="fas fa-info-circle text-primary mr-1"></i>Format kolom</h6>
        <div class="table-responsive"><table class="table table-sm table-bordered mb-0"><thead class="thead-light"><tr><th>Tanggal (YYYY-MM-DD)</th><th>Jam Mulai (HH:MM)</th><th>Jam Selesai (HH:MM)</th></tr></thead><tbody><tr><td>2026-10-01</td><td>11:30</td><td>12:00</td></tr><tr><td>2026-10-02</td><td>11:31</td><td>12:01</td></tr></tbody></table></div>
    </div></div>
</div>
