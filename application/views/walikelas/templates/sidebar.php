<!-- ================= SIDEBAR WALIKELAS ================= -->
<style>

/* ================= SIDEBAR WALIKELAS ================= */

html, body{
    height:100%;
    margin:0;
    overflow-x:hidden;
}

/* ================= SIDEBAR ================= */

#accordionSidebar{
    position:fixed;
    top:0;
    left:0;
    width:190px;
    height:100vh;
    z-index:9999;
    overflow-y:auto;
    overflow-x:hidden;
    transition:all .3s ease;
    background:linear-gradient(180deg,#28a745,#1e7e34);
}

/* Scroll */
#accordionSidebar::-webkit-scrollbar{
    width:5px;
}

#accordionSidebar::-webkit-scrollbar-thumb{
    background:rgba(255,255,255,.2);
    border-radius:10px;
}

/* ================= CONTENT ================= */

#content-wrapper{
    margin-left:190px;
    min-height:100vh;
    padding-top:70px;
    transition:all .3s ease;
    background:#f8f9fc;
}

/* ================= TOPBAR ================= */

.topbar{
    position:fixed;
    top:0;
    left:190px;
    right:0;
    z-index:1031;
    background:#28a745 !important;
    transition:all .3s ease;
}

/* ================= BRAND ================= */

.sidebar-brand{
    height:60px;
}

.sidebar-brand-text{
    font-size:.95rem;
    font-weight:700;
}

/* ================= MENU ================= */

#accordionSidebar .nav-item{
    margin:3px 10px;
}

#accordionSidebar .nav-link{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px 12px;
    border-radius:10px;
    font-size:.86rem;
    font-weight:500;
    color:#f1fff1;
    line-height:1.3;
    transition:all .2s ease;
}

#accordionSidebar .nav-link i{
    width:18px;
    min-width:18px;
    text-align:center;
    font-size:.9rem;
}

/* Hover */

#accordionSidebar .nav-link:hover{
    background:rgba(255,255,255,.12);
}

/* Active */

#accordionSidebar .nav-item.active .nav-link{
    background:rgba(255,255,255,.18);
    box-shadow:inset 3px 0 0 #ffffff;
    font-weight:600;
}

/* Heading */

#accordionSidebar .sidebar-heading{
    margin-top:10px;
    margin-bottom:6px;
    padding-left:16px;
    font-size:.72rem;
    font-weight:600;
    letter-spacing:.08em;
    color:rgba(255,255,255,.75);
}

/* Divider */

#accordionSidebar .sidebar-divider{
    margin:10px 14px;
    border-top:1px solid rgba(255,255,255,.12);
}

/* Text menu */

#accordionSidebar .nav-link span{
    line-height:1.25;
    white-space:normal;
}

/* Logout */

#accordionSidebar .btn-danger{
    border-radius:10px;
    font-size:.83rem;
}

/* ================= DESKTOP TOGGLE ================= */

.sidebar-toggled #accordionSidebar{
    width:78px;
}

.sidebar-toggled #content-wrapper{
    margin-left:78px;
}

.sidebar-toggled .topbar{
    left:78px;
}

.sidebar-toggled .sidebar-brand-text,
.sidebar-toggled .nav-link span,
.sidebar-toggled .sidebar-heading{
    display:none;
}

.sidebar-toggled .nav-link{
    justify-content:center;
}

/* ================= MOBILE ================= */

@media (max-width:768px){

    #accordionSidebar{
        transform:translateX(-100%);
        width:190px;
    }

    #accordionSidebar.show{
        transform:translateX(0);
    }

    #content-wrapper{
        margin-left:0 !important;
        padding-top:70px;
    }

    .topbar{
        left:0 !important;
    }

    .sidebar-overlay{
        display:none;
        position:fixed;
        inset:0;
        background:rgba(0,0,0,.45);
        z-index:9000;
    }

    .sidebar-overlay.show{
        display:block;
    }
}

/* ================= DARK MODE ================= */

body.dark-mode #accordionSidebar{
    background:linear-gradient(180deg,#1f2d27,#121a17);
}

body.dark-mode #content-wrapper{
    background:#1e1e2f;
}

body.dark-mode .topbar{
    background:#23232e !important;
}

</style>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center"
     href="<?= site_url('walikelas') ?>">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-exchange-alt"></i>
    </div>
    <div class="sidebar-brand-text mx-3">WALI KELAS</div>
  </a>

  <hr class="sidebar-divider my-0">

  <!-- Dashboard -->
  <li class="nav-item <?= ($active=='dashboard')?'active':'' ?>">
    <a class="nav-link" href="<?= site_url('walikelas') ?>">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span>
    </a>
  </li>
<hr class="sidebar-divider">

<!-- KEMBALI KE MENU GURU -->
<li class="nav-item">
  <a class="nav-link" href="<?= site_url('guru_dashboard') ?>">
    <i class="fas fa-chalkboard-teacher"></i>
    <span>Kembali ke Menu Guru</span>
  </a>
</li>

  <hr class="sidebar-divider">

  <div class="sidebar-heading">
    Menu Wali Kelas
  </div>

  <!-- Data Siswa -->
  <li class="nav-item <?= ($active=='wk_siswa')?'active':'' ?>">
    <a class="nav-link" href="<?= site_url('walikelas/siswa') ?>">
      <i class="fas fa-users"></i>
      <span>Data Siswa</span>
    </a>
  </li>
<!-- Hasil Verval PD -->
<li class="nav-item <?= ($active=='wk_vervalpd')?'active':'' ?>">
  <a class="nav-link" href="<?= site_url('hasilverval') ?>">
    <i class="fas fa-check-circle"></i>
    <span>Hasil Verval PD</span>
  </a>
</li>


  <!-- Rekap Absensi -->
  <li class="nav-item <?= ($active=='wk_absensi')?'active':'' ?>">
    <a class="nav-link" href="<?= site_url('walikelas/absensi') ?>">
      <i class="fas fa-clipboard-check"></i>
      <span>Rekap Absensi</span>
    </a>
  </li>

  <!-- Rekap Izin -->
  <li class="nav-item <?= ($active=='wk_izin')?'active':'' ?>">
    <a class="nav-link" href="<?= site_url('walikelas/izin') ?>">
      <i class="fas fa-door-open"></i>
      <span>Rekap Izin Siswa</span>
    </a>
  </li>

  <hr class="sidebar-divider d-none d-md-block">

  <!-- Logout -->
  <div class="text-center mb-2">
    <a href="<?= site_url('auth/logout') ?>" class="btn btn-danger btn-sm w-75 shadow-sm">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </div>

  <div class="text-center d-none d-md-inline mb-3">
    <button class="rounded-circle border-0 bg-white shadow-sm" id="sidebarToggle">
      <i class="fas fa-angle-double-left text-primary"></i>
    </button>
  </div>
</ul>
</ul>

<!-- ========== TOPBAR ========== -->
<nav class="navbar navbar-expand navbar-dark topbar mb-4 static-top shadow">
  <button class="btn btn-sm btn-outline-light me-2 d-md-none" id="btnToggleSidebar">
    <i class="fas fa-bars"></i>
</button>


    <ul class="navbar-nav ml-auto align-items-center">

        <li class="nav-item mr-3">
            <button id="toggleMode" class="btn btn-sm btn-outline-light">
                <i class="fas fa-moon"></i>
            </button>
        </li>

        <li class="nav-item dropdown no-arrow">
            <span class="nav-link text-white">
                <?= $this->session->userdata('nama'); ?> |
                <strong>Wali Kelas <?= $this->session->userdata('kelas_nama'); ?></strong>
            </span>
        </li>

    </ul>

</nav>


<!-- ============================ -->

<!-- Content Wrapper -->
 <div class="sidebar-overlay" id="sidebarOverlay"></div>
<div id="content-wrapper" class="d-flex flex-column">

<script>
const btnToggleSidebar = document.getElementById('btnToggleSidebar');
const sidebar = document.getElementById('accordionSidebar');
const overlay = document.getElementById('sidebarOverlay');

if (btnToggleSidebar) {
  btnToggleSidebar.addEventListener('click', () => {
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
  });
}

if (overlay) {
  overlay.addEventListener('click', () => {
    sidebar.classList.remove('show');
    overlay.classList.remove('show');
  });
}
</script>

<script>

/* ===== Desktop Toggle ===== */
const sidebarToggle = document.getElementById('sidebarToggle');

if(sidebarToggle){
    sidebarToggle.addEventListener('click', function(){
        document.body.classList.toggle('sidebar-toggled');
    });
}

/* ===== Mobile Sidebar ===== */
const btnToggleSidebar = document.getElementById('btnToggleSidebar');
const sidebar = document.getElementById('accordionSidebar');
const overlay = document.getElementById('sidebarOverlay');

if (btnToggleSidebar) {
    btnToggleSidebar.addEventListener('click', () => {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    });
}

if (overlay) {
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });
}

</script>

