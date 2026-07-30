<style>
    /* ===== JADWAL HARI INI SISWA ===== */

.jadwal-hari-ini {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 12px;
}

.jadwal-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
}

.jadwal-header {
    font-size: .8rem;
    color: #6c757d;
    margin-bottom: 6px;
}

.jadwal-body .mapel {
    font-size: 1rem;
    font-weight: 600;
    color: #212529;
}

.jadwal-body .guru {
    font-size: .85rem;
    color: #495057;
    margin-top: 4px;
}

/* DARK MODE */
.dark-mode .jadwal-card {
    background: #2a2d3e;
    color: #f1f1f1;
}

.dark-mode .jadwal-header {
    color: #b0b3c6;
}

.dark-mode .jadwal-body .guru {
    color: #d0d3e0;
}

    </style>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h4 mb-4 text-gray-800">Selamat Datang, <?= $siswa->nama ?> 👋</h1>
    <?php $is_lulus = strtolower($siswa->status) == 'lulus'; ?>

<?php if($is_lulus): ?>
<div class="alert alert-success shadow border-left-success mb-4">
    <div class="row align-items-center">
        <div class="col-md-2 text-center">
            <i class="fas fa-graduation-cap fa-4x"></i>
        </div>

        <div class="col-md-10">
            <h4 class="mb-2">
                Selamat Atas Kelulusan Anda 🎓
            </h4>

            <p class="mb-1">
                Anda telah menyelesaikan pendidikan di
                <strong>SMK Negeri 1 Cilimus</strong>.
            </p>

            <p class="mb-0">
                Tahun Lulus :
                <strong><?= $siswa->tahun_ajaran ?></strong>
            </p>
        </div>
    </div>
</div>
<?php endif; ?>

    <div class="row">
        <?php if (!empty($ketua_kelas)): ?>
<div class="col-md-4 col-sm-6 mb-3">
    <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                Jabatan
            </div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">
                🏅 Ketua Kelas
            </div>
            <small class="text-muted">
                <?= $ketua_kelas->nama ?>
            </small>
        </div>
    </div>
</div>
<?php endif; ?>


        <!-- Card NISN -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        NISN
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= $siswa->nisn ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Kelas -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
    <?= $is_lulus ? 'Kelas Terakhir' : 'Kelas / Rombel' ?>
</div>

<div class="h5 mb-0 font-weight-bold text-gray-800">
    <?= $siswa->nama_kelas ?>
</div>
                </div>
            </div>
        </div>

        <!-- Card Tahun Ajaran -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Tahun Ajaran
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= $siswa->tahun_ajaran ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Status -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Status
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= ucfirst($siswa->status) ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php if (!$is_lulus): ?>

<!-- =========================
     JADWAL HARI INI
========================= -->

<div class="mt-4">
    <h5 class="mb-3">
        <i class="fas fa-calendar-day"></i>
        Jadwal Pembelajaran Hari Ini (<?= $hari_ini ?>)
    </h5>

    <?php if (empty($jadwal_hari_ini)): ?>

        <div class="alert alert-secondary">
            <i class="fas fa-coffee"></i>
            Tidak ada jadwal pembelajaran hari ini.
        </div>

    <?php else: ?>

        <div class="jadwal-hari-ini">

            <?php foreach ($jadwal_hari_ini as $j): ?>

                <div class="jadwal-card">

                    <div class="jadwal-header">
                        <?= $j->nama_jam ?>
                        (<?= $j->jam_mulai ?> - <?= $j->jam_selesai ?>)
                    </div>

                    <div class="jadwal-body">
                        <div class="mapel"><?= $j->nama_mapel ?></div>

                        <div class="guru">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <?= $j->nama_guru ?>
                        </div>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

<?php endif; ?>
</div>

    <!-- LINK BIODATA -->
    <div class="mt-4">
        <a href="<?= site_url('SiswaDashboard/biodata') ?>" class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-id-card"></i> Lihat Biodata Lengkap
        </a>
    </div>

</div>
<!-- End Page Content -->

