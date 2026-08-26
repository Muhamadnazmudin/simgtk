<?php if (!empty($tka_notif)): ?>

<style>

.tka-notification {
    position: relative;
    overflow: hidden;
    border: 0;
    border-radius: 22px;
    padding: 24px 28px;
    margin-bottom: 25px;

    background:
        linear-gradient(
            135deg,
            #e3f2fd 0%,
            #bbdefb 100%
        );

    box-shadow:
        0 8px 25px rgba(30, 136, 229, .15);
}

.tka-notification::before {
    content: "";
    position: absolute;
    width: 150px;
    height: 150px;
    right: -50px;
    top: -70px;

    background: rgba(255,255,255,.35);
    border-radius: 50%;
}

.tka-notification-content {
    position: relative;
    z-index: 2;

    display: flex;
    align-items: center;
    gap: 20px;
}

.tka-notification-icon {
    width: 58px;
    height: 58px;

    min-width: 58px;

    border-radius: 17px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #1e88e5;
    color: #fff;

    font-size: 25px;

    box-shadow:
        0 6px 15px rgba(30,136,229,.25);
}

.tka-notification-text {
    flex: 1;
}

.tka-notification-title {
    margin: 0 0 5px;

    font-size: 18px;
    font-weight: 700;

    color: #0d47a1;
}

.tka-notification-desc {
    margin: 0;

    font-size: 14px;
    line-height: 1.6;

    color: #245078;
}

.tka-notification-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    padding: 11px 18px;

    border-radius: 12px;

    background: #1e88e5;
    color: #fff !important;

    font-size: 14px;
    font-weight: 600;

    text-decoration: none !important;

    white-space: nowrap;

    transition: .2s ease;
}

.tka-notification-button:hover {
    background: #1565c0;
    transform: translateY(-2px);
    box-shadow:
        0 6px 15px rgba(30,136,229,.25);
}


/* =========================
   MOBILE
========================= */

@media (max-width: 768px) {

    .tka-notification {
        padding: 20px;
    }

    .tka-notification-content {
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .tka-notification-text {
        min-width: calc(100% - 80px);
    }

    .tka-notification-button {
        width: 100%;
        justify-content: center;
    }

}

</style>


<div class="tka-notification">

    <div class="tka-notification-content">

        <!-- ICON -->
        <div class="tka-notification-icon">

            <i class="fas fa-graduation-cap"></i>

        </div>


        <!-- TEXT -->
        <div class="tka-notification-text">

            <h5 class="tka-notification-title">

                Pengisian TKA Dibuka

            </h5>

            <p class="tka-notification-desc">

                Anda adalah siswa kelas XII.
                Silakan menentukan
                <strong>2 mata pelajaran pilihan</strong>
                yang akan digunakan untuk TKA.

            </p>

        </div>


        <!-- BUTTON -->
        <div>

            <a
                href="<?= site_url('Tka'); ?>"
                class="tka-notification-button"
            >

                <i class="fas fa-edit"></i>

                Isi Form TKA

            </a>

        </div>

    </div>

</div>

<?php endif; ?>

<style>
/* =========================================================
   SISWA DASHBOARD
========================================================= */

.siswa-dashboard {
    padding-bottom: 30px;
}

/* ================= HEADER ================= */

.siswa-welcome {
    position: relative;
    overflow: hidden;
    border-radius: 16px;
    padding: 24px 26px;
    margin-bottom: 24px;

    background: linear-gradient(135deg, #4e73df, #224abe);
    color: #fff;

    box-shadow: 0 8px 24px rgba(78,115,223,.22);
}

.siswa-welcome::after {
    content: "\f19d";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;

    position: absolute;
    right: 28px;
    bottom: -15px;

    font-size: 100px;
    opacity: .10;
}

.siswa-welcome h4 {
    font-weight: 700;
    margin-bottom: 5px;
}

.siswa-welcome p {
    margin-bottom: 0;
    opacity: .9;
    font-size: .9rem;
}


/* ================= LULUS ================= */

.lulus-card {
    border: 0;
    border-radius: 16px;
    overflow: hidden;

    box-shadow: 0 6px 20px rgba(28,200,138,.15);
}

.lulus-icon {
    width: 70px;
    height: 70px;

    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    background: rgba(255,255,255,.2);

    font-size: 32px;
}


/* ================= INFO CARDS ================= */

.siswa-info-card {
    border: 0;
    border-radius: 14px;

    min-height: 120px;

    transition: all .25s ease;
}

.siswa-info-card:hover {
    transform: translateY(-4px);

    box-shadow: 0 8px 22px rgba(0,0,0,.10) !important;
}

.siswa-info-label {
    font-size: .70rem;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .5px;

    margin-bottom: 7px;
}

.siswa-info-value {
    font-size: 1.05rem;
    font-weight: 700;

    color: #3a3b45;

    word-break: break-word;
}

.siswa-info-icon {
    width: 42px;
    height: 42px;

    border-radius: 10px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 17px;
}


/* ================= KETUA KELAS ================= */

.ketua-card {
    border-radius: 14px;
    border: 0;

    background: linear-gradient(135deg, #e74a3b, #be2617);

    color: #fff;

    box-shadow: 0 6px 18px rgba(231,74,59,.20);
}

.ketua-icon {
    width: 46px;
    height: 46px;

    border-radius: 12px;

    background: rgba(255,255,255,.18);

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 20px;
}


/* ================= SECTION HEADER ================= */

.dashboard-section {
    margin-top: 28px;
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    margin-bottom: 14px;
}

.section-title {
    font-size: 1rem;
    font-weight: 700;

    color: #3a3b45;

    margin: 0;
}

.section-subtitle {
    font-size: .78rem;
    color: #858796;
    margin-top: 3px;
}


/* ================= JADWAL ================= */

.jadwal-hari-ini {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 14px;
}

.jadwal-card {
    position: relative;

    background: #fff;

    border-radius: 14px;

    padding: 17px;

    border: 1px solid #eaecf4;

    box-shadow: 0 3px 10px rgba(0,0,0,.05);

    transition: all .25s ease;
}

.jadwal-card:hover {
    transform: translateY(-4px);

    box-shadow: 0 8px 22px rgba(0,0,0,.10);

    border-color: rgba(78,115,223,.25);
}


/* Garis kecil di kiri */

.jadwal-card::before {
    content: "";

    position: absolute;

    left: 0;
    top: 16px;
    bottom: 16px;

    width: 4px;

    border-radius: 0 5px 5px 0;

    background: #4e73df;
}


/* WAKTU */

.jadwal-header {
    display: flex;
    align-items: center;

    font-size: .75rem;
    font-weight: 600;

    color: #858796;

    margin-bottom: 11px;

    padding-left: 7px;
}

.jadwal-header i {
    color: #4e73df;
    margin-right: 6px;
}


/* MAPEL */

.jadwal-body {
    padding-left: 7px;
}

.jadwal-body .mapel {
    font-size: 1rem;

    font-weight: 700;

    color: #3a3b45;

    line-height: 1.4;
}

.jadwal-body .guru {
    display: flex;
    align-items: center;

    font-size: .82rem;

    color: #858796;

    margin-top: 7px;
}

.jadwal-body .guru i {
    width: 20px;

    color: #4e73df;
}


/* ================= EMPTY JADWAL ================= */

.empty-jadwal {
    padding: 35px 20px;

    text-align: center;

    border-radius: 14px;

    background: #f8f9fc;

    border: 1px dashed #d1d3e2;
}

.empty-jadwal-icon {
    width: 55px;
    height: 55px;

    margin: 0 auto 12px;

    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    background: rgba(133,135,150,.10);

    color: #858796;

    font-size: 21px;
}


/* ================= BIODATA BUTTON ================= */

.biodata-button {
    border-radius: 10px;

    font-weight: 600;

    padding: 11px 20px;

    transition: all .2s ease;
}

.biodata-button:hover {
    transform: translateY(-2px);

    box-shadow: 0 6px 15px rgba(78,115,223,.20);
}


/* =========================================================
   DARK MODE
========================================================= */

.dark-mode .siswa-info-card,
body.dark-mode .siswa-info-card {
    background: #2a2d3e;
    color: #f1f1f1;
}

.dark-mode .siswa-info-value,
body.dark-mode .siswa-info-value {
    color: #f1f1f1;
}

.dark-mode .section-title,
body.dark-mode .section-title {
    color: #f1f1f1;
}

.dark-mode .section-subtitle,
body.dark-mode .section-subtitle {
    color: #b0b3c6;
}

.dark-mode .jadwal-card,
body.dark-mode .jadwal-card {
    background: #2a2d3e;
    border-color: #3b3e52;
}

.dark-mode .jadwal-body .mapel,
body.dark-mode .jadwal-body .mapel {
    color: #f1f1f1;
}

.dark-mode .jadwal-header,
body.dark-mode .jadwal-header {
    color: #b0b3c6;
}

.dark-mode .jadwal-body .guru,
body.dark-mode .jadwal-body .guru {
    color: #bfc2d0;
}

.dark-mode .empty-jadwal,
body.dark-mode .empty-jadwal {
    background: #242633;
    border-color: #44475a;
}


/* ================= MOBILE ================= */

@media (max-width: 767.98px) {

    .siswa-welcome {
        padding: 20px;
    }

    .siswa-welcome::after {
        font-size: 70px;
        right: 15px;
    }

    .siswa-welcome h4 {
        font-size: 1.05rem;
    }

    .siswa-info-card {
        min-height: 105px;
    }

    .jadwal-hari-ini {
        grid-template-columns: 1fr;
    }

}
</style>


<!-- =========================================================
     BEGIN PAGE
========================================================= -->

<div class="container-fluid siswa-dashboard">


    <!-- =====================================================
         WELCOME HEADER
    ====================================================== -->

    <div class="siswa-welcome">

        <h4>
            Selamat Datang, <?= htmlspecialchars($siswa->nama) ?> 👋
        </h4>

        <p>
            <i class="fas fa-user-graduate mr-1"></i>
            Dashboard Siswa
            <?php if (!empty($siswa->nama_kelas)): ?>
                &bull; <?= htmlspecialchars($siswa->nama_kelas) ?>
            <?php endif; ?>
        </p>

    </div>


    <?php $is_lulus = strtolower(trim($siswa->status)) == 'lulus'; ?>


    <!-- =====================================================
         STATUS LULUS
    ====================================================== -->

    <?php if ($is_lulus): ?>

        <div class="card lulus-card bg-success text-white mb-4">

            <div class="card-body p-4">

                <div class="row align-items-center">

                    <div class="col-auto d-none d-md-block">

                        <div class="lulus-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>

                    </div>

                    <div class="col">

                        <h4 class="font-weight-bold mb-2">
                            Selamat Atas Kelulusan Anda 🎓
                        </h4>

                        <p class="mb-2">
                            Anda telah menyelesaikan pendidikan di
                            <strong>SMK Negeri 1 Cilimus</strong>.
                        </p>

                        <div class="small">
                            <i class="fas fa-calendar-check mr-1"></i>
                            Tahun Lulus:
                            <strong><?= htmlspecialchars($siswa->tahun_ajaran) ?></strong>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         INFORMASI SISWA
    ====================================================== -->

    <div class="row">


        <!-- KETUA KELAS -->

        <?php if (!empty($ketua_kelas)): ?>

            <div class="col-xl-3 col-md-6 mb-3">

                <div class="card ketua-card h-100 shadow-sm">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="ketua-icon mr-3">
                                <i class="fas fa-crown"></i>
                            </div>

                            <div>

                                <div class="small font-weight-bold text-uppercase mb-1">
                                    Jabatan
                                </div>

                                <div class="font-weight-bold">
                                    Ketua Kelas
                                </div>

                                <div class="small mt-1">
                                    <?= htmlspecialchars($ketua_kelas->nama) ?>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        <?php endif; ?>


        <!-- NISN -->

        <div class="col-xl-3 col-md-6 mb-3">

            <div class="card siswa-info-card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="siswa-info-icon bg-primary text-white mr-3">
                            <i class="fas fa-id-card"></i>
                        </div>

                        <div>

                            <div class="siswa-info-label text-primary">
                                NISN
                            </div>

                            <div class="siswa-info-value">
                                <?= htmlspecialchars($siswa->nisn) ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- KELAS -->

        <div class="col-xl-3 col-md-6 mb-3">

            <div class="card siswa-info-card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="siswa-info-icon bg-success text-white mr-3">
                            <i class="fas fa-users"></i>
                        </div>

                        <div>

                            <div class="siswa-info-label text-success">
                                <?= $is_lulus ? 'Kelas Terakhir' : 'Kelas / Rombel' ?>
                            </div>

                            <div class="siswa-info-value">
                                <?= htmlspecialchars($siswa->nama_kelas) ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- TAHUN AJARAN -->

        <div class="col-xl-3 col-md-6 mb-3">

            <div class="card siswa-info-card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="siswa-info-icon bg-info text-white mr-3">
                            <i class="fas fa-calendar-alt"></i>
                        </div>

                        <div>

                            <div class="siswa-info-label text-info">
                                Tahun Ajaran
                            </div>

                            <div class="siswa-info-value">
                                <?= htmlspecialchars($siswa->tahun_ajaran) ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- STATUS -->

        <div class="col-xl-3 col-md-6 mb-3">

            <div class="card siswa-info-card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="siswa-info-icon bg-warning text-white mr-3">
                            <i class="fas fa-user-check"></i>
                        </div>

                        <div>

                            <div class="siswa-info-label text-warning">
                                Status
                            </div>

                            <div class="siswa-info-value">
                                <?= ucfirst(htmlspecialchars($siswa->status)) ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         JADWAL HARI INI
    ====================================================== -->

    <?php if (!$is_lulus): ?>

        <div class="dashboard-section">

            <div class="section-header">

                <div>

                    <h5 class="section-title">
                        <i class="fas fa-calendar-day text-primary mr-2"></i>
                        Jadwal Pembelajaran Hari Ini
                    </h5>

                    <div class="section-subtitle">
                        <?= htmlspecialchars($hari_ini) ?>
                    </div>

                </div>

                <?php if (!empty($jadwal_hari_ini)): ?>

                    <span class="badge badge-primary px-3 py-2">
                        <?= count($jadwal_hari_ini) ?> Pelajaran
                    </span>

                <?php endif; ?>

            </div>


            <?php if (empty($jadwal_hari_ini)): ?>


                <div class="empty-jadwal">

                    <div class="empty-jadwal-icon">
                        <i class="fas fa-coffee"></i>
                    </div>

                    <h6 class="font-weight-bold text-gray-800">
                        Tidak Ada Jadwal
                    </h6>

                    <p class="text-muted small mb-0">
                        Tidak ada jadwal pembelajaran untuk hari ini.
                    </p>

                </div>


            <?php else: ?>


                <div class="jadwal-hari-ini">

                    <?php foreach ($jadwal_hari_ini as $j): ?>

                        <div class="jadwal-card">

                            <div class="jadwal-header">

                                <i class="far fa-clock"></i>

                                <?= htmlspecialchars($j->nama_jam) ?>

                                <span class="ml-1">
                                    (<?= htmlspecialchars($j->jam_mulai) ?>
                                    -
                                    <?= htmlspecialchars($j->jam_selesai) ?>)
                                </span>

                            </div>


                            <div class="jadwal-body">

                                <div class="mapel">

                                    <?= htmlspecialchars($j->nama_mapel) ?>

                                </div>


                                <div class="guru">

                                    <i class="fas fa-chalkboard-teacher"></i>

                                    <span>
                                        <?= htmlspecialchars($j->nama_guru) ?>
                                    </span>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>


            <?php endif; ?>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         BIODATA
    ====================================================== -->

    <div class="dashboard-section">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">

                    <div class="mb-3 mb-md-0">

                        <h6 class="font-weight-bold text-gray-800 mb-1">
                            <i class="fas fa-address-card text-primary mr-2"></i>
                            Data Pribadi
                        </h6>

                        <div class="small text-muted">
                            Lihat dan periksa biodata lengkap Anda.
                        </div>

                    </div>


                    <a href="<?= site_url('SiswaDashboard/biodata') ?>"
                       class="btn btn-primary biodata-button">

                        <i class="fas fa-id-card mr-1"></i>

                        Lihat Biodata Lengkap

                        <i class="fas fa-arrow-right ml-1"></i>

                    </a>

                </div>

            </div>

        </div>

    </div>


</div>

<!-- =========================================================
     END PAGE
========================================================= -->