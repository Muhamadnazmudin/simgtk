<style>

/* ===============================
   SIDEBAR WALI KELAS
=============================== */

#accordionSidebar{
    background:linear-gradient(180deg,#2563eb,#1d4ed8);
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

/* ==========================
   TOPBAR
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
}

.topbar .nav-link{

    height:70px;

    display:flex;
    align-items:center;

}

/* ===============================
   DARK MODE
=============================== */

body.dark-mode #accordionSidebar{
    background:linear-gradient(180deg,#1f2937,#111827);
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

</style>


<!-- ===========================
     SIDEBAR
=========================== -->

<ul class="navbar-nav sidebar sidebar-dark accordion"
    id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center"
       href="<?= site_url('walikelas') ?>">

        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-user-graduate"></i>
        </div>

        <div class="sidebar-brand-text mx-2">
            WALI KELAS
        </div>

    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item <?= ($active=='dashboard')?'active':'' ?>">
        <a class="nav-link" href="<?= site_url('walikelas') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard QR</span>
        </a>
    </li>

    <li class="nav-item <?= ($active=='dashboard_v2')?'active':'' ?>">
        <a class="nav-link" href="<?= site_url('Walikelas_dashboard_v2') ?>">
            <i class="fas fa-chart-pie"></i>
            <span>Dashboard Manual</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <li class="nav-item">

        <a class="nav-link"
           href="<?= site_url('guru_dashboard') ?>">

            <i class="fas fa-chalkboard-teacher text-warning"></i>

            <span>Kembali ke Menu Guru</span>

        </a>

    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Menu Wali Kelas
    </div>

    <li class="nav-item <?= ($active=='wk_siswa')?'active':'' ?>">
        <a class="nav-link" href="<?= site_url('walikelas/siswa') ?>">
            <i class="fas fa-users"></i>
            <span>Data Siswa</span>
        </a>
    </li>

    <li class="nav-item <?= ($active=='wk_berkas')?'active':'' ?>">
    <a class="nav-link"
       href="<?= site_url('Walikelas_berkas') ?>">

        <i class="fas fa-folder-open"></i>

        <span>Berkas Siswa</span>

    </a>
</li>

    <li class="nav-item <?= ($active=='wk_vervalpd')?'active':'' ?>">
        <a class="nav-link" href="<?= site_url('hasilverval') ?>">
            <i class="fas fa-check-circle"></i>
            <span>Hasil Verval PD</span>
        </a>
    </li>

    <li class="nav-item <?= ($active=='wk_buku_induk')?'active':'' ?>">
        <a class="nav-link" href="<?= site_url('buku_induk') ?>">
            <i class="fas fa-book"></i>
            <span>Buku Induk</span>
        </a>
    </li>

    <li class="nav-item <?= ($active=='wk_absensi')?'active':'' ?>">
        <a class="nav-link" href="<?= site_url('walikelas/absensi') ?>">
            <i class="fas fa-clipboard-check"></i>
            <span>Rekap Absensi</span>
        </a>
    </li>
<li class="nav-item <?= ($active=='wk_absensi_manual')?'active':'' ?>">

    <a class="nav-link"
       href="<?= site_url('Walikelas_laporan') ?>">

        <i class="fas fa-clipboard-list"></i>

        <span>Rekap Absensi Manual</span>

    </a>

<li class="nav-item <?= ($active=='wk_izin')?'active':'' ?>">
    <a class="nav-link" href="<?= site_url('walikelas/izin') ?>">
        <i class="fas fa-door-open"></i>
        <span>Rekap Izin Siswa</span>
    </a>
</li>

<hr class="sidebar-divider d-none d-md-block">

<!-- Logout -->
<div class="text-center mb-3">

    <a href="<?= site_url('auth/logout') ?>"
       class="btn btn-danger btn-sm w-75">

        <i class="fas fa-sign-out-alt"></i>

        Logout

    </a>

</div>

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

<div id="content">

<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <button id="sidebarToggleTop"
            class="btn btn-link d-md-none rounded-circle mr-3">

        <i class="fa fa-bars"></i>

    </button>

    <ul class="navbar-nav ml-auto">

        <li class="nav-item mr-3">

            <button id="toggleMode"
                    class="btn btn-sm btn-outline-secondary">

                <i class="fas fa-moon"></i>

            </button>

        </li>

        <li class="nav-item no-arrow">

            <span class="nav-link">

                <?= $this->session->userdata('nama'); ?>

                |

                <strong>

                    Wali Kelas

                    <?= $this->session->userdata('kelas_nama'); ?>

                </strong>

            </span>

        </li>

    </ul>

</nav>

<div class="container-fluid px-2">
    