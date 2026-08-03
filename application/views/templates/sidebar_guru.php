<style>

/* ===============================
   SIDEBAR GURU
=============================== */

#accordionSidebar{
    background:linear-gradient(180deg,#28a745,#1e7e34);
}

.sidebar-brand{
    height:70px;
}

.sidebar-brand-text{
    font-size:1rem;
    font-weight:700;
}

.sidebar .nav-link{
    font-size:.9rem;
}

.sidebar .nav-link i{
    width:20px;
    text-align:center;
}

.sidebar-heading{
    font-size:.72rem;
    font-weight:700;
    letter-spacing:.08rem;
}

body.dark-mode #accordionSidebar{
    background:linear-gradient(180deg,#1f2d27,#121a17);
}

body.dark-mode .topbar{
    background:#23232e!important;
}

body.dark-mode #content-wrapper{
    background:#1e1e2f;
}

body.dark-mode .sidebar .nav-link{
    color:#ddd!important;
}

body.dark-mode .sidebar .nav-link:hover{
    background:#34344a!important;
}

body.dark-mode .sidebar .nav-item.active .nav-link{
    background:#40405b!important;
}
/* ==========================
   TOPBAR GURU
========================== */

.topbar{
    min-height:70px;
}

.topbar .navbar-nav{
    align-items:center;
}

.topbar .nav-item{
    display:flex;
    align-items:center;
}

#toggleMode{
    width:42px;
    height:42px;

    display:flex;
    align-items:center;
    justify-content:center;

    border-radius:10px;
    margin-top:0;
}

.topbar .nav-link{
    height:70px;
    display:flex;
    align-items:center;
}
</style>

<!-- ===========================
     SIDEBAR
=========================== -->

<ul class="navbar-nav sidebar sidebar-dark accordion"
    id="accordionSidebar">

    <!-- Brand -->

    <a class="sidebar-brand d-flex align-items-center justify-content-center"
       href="<?= site_url('guru_dashboard') ?>">

        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>

        <div class="sidebar-brand-text mx-2">
            GURU
        </div>

    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->

    <li class="nav-item <?= ($active=='guru_dashboard')?'active':'' ?>">

        <a class="nav-link"
           href="<?= site_url('guru_dashboard') ?>">

            <i class="fas fa-fw fa-tachometer-alt"></i>

            <span>Dashboard</span>

        </a>

    </li>

    <hr class="sidebar-divider">

    <!-- Profile -->

    <li class="nav-item <?= ($active=='guru_account')?'active':'' ?>">

        <a class="nav-link"
           href="<?= site_url('guru_account') ?>">

            <i class="fas fa-id-badge"></i>

            <span>Profile</span>

        </a>

    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Menu Guru
    </div>

    <!-- ===========================
     DATA GURU
=========================== -->

<li class="nav-item <?= ($active=='guru_profile')?'active':'' ?>">

    <a class="nav-link"
       href="<?= site_url('guru_profile') ?>">

        <i class="fas fa-user"></i>

        <span>Data Guru</span>

    </a>

</li>

<!-- ===========================
     CEK JJM
=========================== -->

<li class="nav-item <?= ($active=='cek_jjm')?'active':'' ?>">

    <a class="nav-link"
       href="<?= site_url('cek_jjm') ?>">

        <i class="fas fa-file-pdf"></i>

        <span>Cek JJM</span>

    </a>

</li>

<!-- ===========================
     JADWAL
=========================== -->

<li class="nav-item <?= ($active=='guru_jadwal')?'active':'' ?>">

    <a class="nav-link"
       href="<?= site_url('guru_jadwal') ?>">

        <i class="fas fa-calendar-alt"></i>

        <span>Jadwal Mengajar</span>

    </a>

</li>

<!-- ===========================
     LAPORAN
=========================== -->

<li class="nav-item <?= ($active=='laporan_guru')?'active':'' ?>">

    <a class="nav-link"
       href="<?= site_url('laporan_guru') ?>">

        <i class="fas fa-file-pdf"></i>

        <span>Laporan Mengajar</span>

    </a>

</li>

<!-- ===========================
     SERTIFIKASI
=========================== -->

<li class="nav-item <?= ($active=='guru_sertifikasi')?'active':'' ?>">

    <a class="nav-link"
       href="<?= site_url('guru_sertifikasi_guru') ?>">

        <i class="fas fa-certificate"></i>

        <span>Riwayat Sertifikasi</span>

    </a>

</li>

<?php if($this->session->userdata('is_walikelas')): ?>

<hr class="sidebar-divider">

<div class="sidebar-heading">

    Akses Tambahan

</div>

<li class="nav-item">

    <a class="nav-link"
       href="<?= site_url('walikelas') ?>">

        <i class="fas fa-users text-warning"></i>

        <span>Menu Wali Kelas</span>

    </a>

</li>

<?php endif; ?>


<?php if($this->session->userdata('role_id') == 4): ?>

<hr class="sidebar-divider">

<div class="sidebar-heading">

    Akses Wakakur

</div>

<li class="nav-item <?= ($active=='wakakur_report_siswa')?'active':'' ?>">

    <a class="nav-link"
       href="<?= site_url('WakakurLaporanPembelajaran') ?>">

        <i class="fas fa-chart-line text-info"></i>

        <span>Report Siswa</span>

    </a>

</li>

<li class="nav-item <?= ($active=='nilai_daftar')?'active':'' ?>">

    <a class="nav-link"
       href="<?= site_url('Nilairapor_admin/rekap') ?>">

        <i class="fas fa-book text-primary"></i>

        <span>Rekap Nilai</span>

    </a>

</li>

<?php endif; ?>


<hr class="sidebar-divider d-none d-md-block">

<!-- Desktop Sidebar Toggle -->

<div class="text-center d-none d-md-inline">

    <button
        class="rounded-circle border-0"
        id="sidebarToggle">
    </button>

</div>

</ul>

<!-- ===========================
     CONTENT WRAPPER
=========================== -->

<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- ===========================
             TOPBAR
        =========================== -->

        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Mobile) -->

            <button id="sidebarToggleTop"
                    class="btn btn-link d-md-none rounded-circle mr-3">

                <i class="fa fa-bars"></i>

            </button>

            <!-- Right Navbar -->

            <ul class="navbar-nav ml-auto">

                <!-- Dark Mode -->

                <li class="nav-item mr-3">

                    <button id="toggleMode"
                            class="btn btn-sm btn-outline-secondary">

                        <i class="fas fa-moon"></i>

                    </button>

                </li>

                <!-- User -->

                <li class="nav-item no-arrow">

                    <span class="nav-link">

                        <?= $this->session->userdata('nama'); ?>

                        |

                        <strong>Guru</strong>

                        <?php if($this->session->userdata('is_walikelas')): ?>

                            <span class="badge badge-warning">

                                Wali Kelas

                            </span>

                        <?php endif; ?>

                    </span>

                </li>

            </ul>

        </nav>

        <!-- ===========================
             PAGE CONTENT
        =========================== -->

        <div class="container-fluid px-2">