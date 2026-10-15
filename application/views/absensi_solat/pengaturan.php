
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">
                Pengaturan Absensi Salat
            </h1>
            <p class="mb-0 text-muted">
                Atur jadwal otomatis dan durasi absensi Dzuhur.
            </p>
        </div>

        <a href="<?= site_url('absensi_solat'); ?>"
           class="btn btn-secondary btn-sm mt-3 mt-sm-0">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= html_escape($this->session->flashdata('success')); ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= html_escape($this->session->flashdata('error')); ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="row">

        <div class="col-lg-8">

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-sliders-h mr-2"></i>
                        Konfigurasi Otomatisasi
                    </h6>
                </div>

                <div class="card-body">

                    <form method="post"
                          action="<?= site_url('absensi_solat/pengaturan'); ?>">

                        <?php if ($this->security->get_csrf_token_name()): ?>
                            <input type="hidden"
                                   name="<?= $this->security->get_csrf_token_name(); ?>"
                                   value="<?= $this->security->get_csrf_hash(); ?>">
                        <?php endif; ?>

                        <div class="form-group">
                            <label>Tahun Ajaran ID</label>
                            <input type="text"
                                   class="form-control"
                                   value="<?= (int) $tahun_id; ?>"
                                   readonly>
                            <small class="form-text text-muted">
                                Mengikuti tahun ajaran aktif aplikasi.
                            </small>
                        </div>

                        <div class="form-group">
                            <label>Durasi Absensi</label>

                            <div class="row">
                                <?php foreach ([15, 30, 45, 60] as $menit): ?>
                                    <div class="col-6 col-md-3 mb-2">
                                        <label class="border rounded p-3 d-block text-center h-100">
                                            <input type="radio"
                                                   name="durasi_menit"
                                                   value="<?= $menit; ?>"
                                                <?= (int) $durasi_menit === $menit
                                                    ? 'checked' : ''; ?>
                                                   required>
                                            <div class="font-weight-bold mt-2">
                                                <?= $menit; ?> menit
                                            </div>
                                            <?php if ($menit === 30): ?>
                                                <small class="text-success">
                                                    Default
                                                </small>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label>Status Otomatisasi</label>

                            <div class="custom-control custom-switch">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="aktif"
                                       name="aktif"
                                       value="1"
                                    <?= (int) $aktif === 1
                                        ? 'checked' : ''; ?>>

                                <label class="custom-control-label"
                                       for="aktif">
                                    Aktifkan jadwal otomatis
                                </label>
                            </div>

                            <small class="form-text text-muted">
                                Jika aktif, sistem boleh membuat sesi Dzuhur
                                otomatis ketika pemeriksaan jadwal dijalankan.
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>
                            Simpan Pengaturan
                        </button>

                    </form>

                </div>
            </div>

        </div>

        <div class="col-lg-4">

            <div class="card shadow mb-4 border-left-primary">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-2">
                        Sumber Jadwal
                    </div>

                    <h5 class="font-weight-bold">MuslimKita.id</h5>

                    <p class="text-muted small mb-2">
                        Kabupaten Kuningan, Jawa Barat
                    </p>

                    <a href="https://www.muslimkita.id/api/jadwal-sholat/v1/kuningan"
                       target="_blank"
                       rel="noopener noreferrer">
                        Tes endpoint jadwal
                        <i class="fas fa-external-link-alt ml-1"></i>
                    </a>

                    <hr>

                    <div class="small text-muted">
                        Durasi dihitung dari waktu Dzuhur yang dikembalikan
                        API. Sesi tidak boleh menggunakan jadwal dengan
                        tanggal yang tidak sesuai.
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4 border-left-warning">
                <div class="card-body">
                    <h6 class="font-weight-bold text-warning">
                        Catatan
                    </h6>
                    <p class="small mb-0">
                        Pengaturan ini belum menjamin sesi dibuat tepat waktu
                        ketika tidak ada halaman yang dibuka. Untuk eksekusi
                        latar belakang, diperlukan Windows Task Scheduler
                        atau cron.
                    </p>
                </div>
            </div>

        </div>

    </div>
</div>
