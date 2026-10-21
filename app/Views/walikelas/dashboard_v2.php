<?php
$tahun = isset($tahun) ? $tahun : null;
?>

<style>

.dashboard-header{
    background:linear-gradient(135deg,#4e73df,#224abe);
    border-radius:20px;
    color:#fff;
    padding:35px;
    margin-bottom:30px;
    position:relative;
    overflow:hidden;
}

.dashboard-header:after{
    content:'';
    position:absolute;
    width:220px;
    height:220px;
    right:-60px;
    top:-60px;
    background:rgba(255,255,255,.08);
    border-radius:50%;
}

.dashboard-header h2{
    font-weight:700;
}

.dashboard-header p{
    margin-bottom:3px;
}

.summary-card{
    border:none;
    border-radius:18px;
    transition:.25s;
    overflow:hidden;
}

.summary-card:hover{
    transform:translateY(-5px);
    box-shadow:0 .8rem 2rem rgba(0,0,0,.12);
}

.summary-icon{

    width:62px;
    height:62px;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    color:#fff;
    font-size:25px;

}

.summary-number{

    font-size:34px;
    font-weight:bold;

}

.summary-title{

    font-size:13px;
    text-transform:uppercase;
    color:#888;
    font-weight:600;

}

.bg-primary-soft{
    background:#4e73df;
}

.bg-success-soft{
    background:#1cc88a;
}

.bg-warning-soft{
    background:#f6c23e;
}

.bg-danger-soft{
    background:#e74a3b;
}

.card{
    border:none;
    border-radius:18px;
}

.progress{
    height:12px;
    border-radius:30px;
}

</style>

<div class="container-fluid">

<!-- ===================================================== -->
<!-- HEADER -->
<!-- ===================================================== -->

<div class="dashboard-header shadow">

<div class="row align-items-center">

<div class="col-lg-8">

<h2>

Selamat Datang,
<?= $this->session->userdata('nama'); ?> 👋

</h2>

<p>

Wali Kelas
<b><?= $kelas_nama ?></b>

</p>

<small>

Tahun Ajaran Aktif

<b><?= $tahun ? $tahun->tahun : '-' ?></b>

</small>

</div>

<div class="col-lg-4 text-right">

<i class="fas fa-user-graduate fa-5x opacity-50"></i>

</div>

</div>

</div>


<!-- ===================================================== -->
<!-- CARD SUMMARY -->
<!-- ===================================================== -->

<div class="row">

<!-- TOTAL SISWA -->

<div class="col-xl-3 col-md-6 mb-4">

<div class="card summary-card shadow h-100">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<div class="summary-title">

Total Siswa

</div>

<div class="summary-number">

<?= $total_siswa ?>

</div>

<small>

<?= $laki ?> Laki-laki

<br>

<?= $perempuan ?> Perempuan

</small>

</div>

<div class="summary-icon bg-primary-soft">

<i class="fas fa-users"></i>

</div>

</div>

</div>

</div>

</div>


<!-- HADIR -->

<div class="col-xl-3 col-md-6 mb-4">

<div class="card summary-card shadow h-100">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<div class="summary-title">

Hadir

</div>

<div class="summary-number text-success">

<?= $hari_H ?>

</div>

<small>

Otomatis dihitung

</small>

</div>

<div class="summary-icon bg-success-soft">

<i class="fas fa-check-circle"></i>

</div>

</div>

</div>

</div>

</div>


<!-- IZIN + SAKIT -->

<div class="col-xl-3 col-md-6 mb-4">

<div class="card summary-card shadow h-100">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<div class="summary-title">

Izin / Sakit

</div>

<div class="summary-number text-warning">

<?= $hari_I + $hari_S ?>

</div>

<small>

<?= $hari_I ?> Izin
|
<?= $hari_S ?> Sakit

</small>

</div>

<div class="summary-icon bg-warning-soft">

<i class="fas fa-notes-medical"></i>

</div>

</div>

</div>

</div>

</div>


<!-- ALPA -->

<div class="col-xl-3 col-md-6 mb-4">

<div class="card summary-card shadow h-100">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<div class="summary-title">

Alpa

</div>

<div class="summary-number text-danger">

<?= $hari_A ?>

</div>

<small>

Tanpa Keterangan

</small>

</div>

<div class="summary-icon bg-danger-soft">

<i class="fas fa-user-times"></i>

</div>

</div>

</div>

</div>

</div>

</div>

<!-- part2 -->
 <!-- ===================================================== -->
<!-- PROGRESS HARI INI + REKAP BULAN -->
<!-- ===================================================== -->

<?php

$totalHari = $total_siswa;

$persenHadir = ($totalHari > 0) ? round(($hari_H/$totalHari)*100) : 0;
$persenIzin  = ($totalHari > 0) ? round(($hari_I/$totalHari)*100) : 0;
$persenSakit = ($totalHari > 0) ? round(($hari_S/$totalHari)*100) : 0;
$persenAlpa  = ($totalHari > 0) ? round(($hari_A/$totalHari)*100) : 0;

?>

<div class="row">

<!-- ===================================================== -->
<!-- KEHADIRAN HARI INI -->
<!-- ===================================================== -->

<div class="col-lg-6 mb-4">

<div class="card shadow h-100">

<div class="card-header bg-white border-0">

<h5 class="font-weight-bold text-success mb-0">

<i class="fas fa-calendar-check mr-2"></i>

Kehadiran Hari Ini

</h5>

</div>

<div class="card-body">

<div class="mb-4">

<div class="d-flex justify-content-between">

<span>Hadir</span>

<strong><?= $hari_H ?> Siswa</strong>

</div>

<div class="progress mt-2">

<div class="progress-bar bg-success"

style="width:<?= $persenHadir ?>%">

<?= $persenHadir ?>%

</div>

</div>

</div>


<div class="mb-4">

<div class="d-flex justify-content-between">

<span>Izin</span>

<strong><?= $hari_I ?> Siswa</strong>

</div>

<div class="progress mt-2">

<div class="progress-bar bg-info"

style="width:<?= $persenIzin ?>%">

<?= $persenIzin ?>%

</div>

</div>

</div>


<div class="mb-4">

<div class="d-flex justify-content-between">

<span>Sakit</span>

<strong><?= $hari_S ?> Siswa</strong>

</div>

<div class="progress mt-2">

<div class="progress-bar bg-warning"

style="width:<?= $persenSakit ?>%">

<?= $persenSakit ?>%

</div>

</div>

</div>


<div>

<div class="d-flex justify-content-between">

<span>Alpa</span>

<strong><?= $hari_A ?> Siswa</strong>

</div>

<div class="progress mt-2">

<div class="progress-bar bg-danger"

style="width:<?= $persenAlpa ?>%">

<?= $persenAlpa ?>%

</div>

</div>

</div>

</div>

</div>

</div>



<!-- ===================================================== -->
<!-- REKAP BULAN -->
<!-- ===================================================== -->

<div class="col-lg-6 mb-4">

<div class="card shadow h-100">

<div class="card-header bg-white border-0">

<h5 class="font-weight-bold text-primary mb-0">

<i class="fas fa-chart-bar mr-2"></i>

Rekap Bulan Ini

</h5>

</div>

<div class="card-body">

<div class="row text-center">

<div class="col-3">

<div class="display-4 text-success">

<?= $bulan_H ?>

</div>

<small>Hadir</small>

</div>

<div class="col-3">

<div class="display-4 text-info">

<?= $bulan_I ?>

</div>

<small>Izin</small>

</div>

<div class="col-3">

<div class="display-4 text-warning">

<?= $bulan_S ?>

</div>

<small>Sakit</small>

</div>

<div class="col-3">

<div class="display-4 text-danger">

<?= $bulan_A ?>

</div>

<small>Alpa</small>

</div>

</div>

<hr>

<div class="row text-center mt-4">

<div class="col-md-6">

<h6 class="text-muted">

Total Ketidakhadiran

</h6>

<h2 class="font-weight-bold text-danger">

<?= $bulan_I+$bulan_S+$bulan_A ?>

</h2>

</div>

<div class="col-md-6">

<h6 class="text-muted">

Persentase Hadir

</h6>

<h2 class="font-weight-bold text-success">

<?php

$totalBulan = $bulan_H+$bulan_I+$bulan_S+$bulan_A;

echo ($totalBulan>0)
? round(($bulan_H/$totalBulan)*100)
:0;

?>%

</h2>

</div>

</div>

</div>

</div>

</div>

</div>


<!-- part3 -->

<!-- ===================================================== -->
<!-- DAFTAR SISWA TIDAK HADIR & TOP ALPA -->
<!-- ===================================================== -->

<div class="row">

<!-- ===================================================== -->
<!-- SISWA TIDAK HADIR HARI INI -->
<!-- ===================================================== -->

<div class="col-lg-7 mb-4">

<div class="card shadow h-100">

<div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">

<h5 class="mb-0 font-weight-bold text-danger">

<i class="fas fa-user-slash mr-2"></i>

Siswa Tidak Hadir Hari Ini

</h5>

<span class="badge badge-danger">

<?= count($tidak_hadir) ?> Siswa

</span>

</div>

<div class="card-body p-0">

<?php if(empty($tidak_hadir)): ?>

<div class="text-center py-5">

<i class="fas fa-check-circle fa-4x text-success mb-3"></i>

<h5 class="text-success">

Semua siswa hadir

</h5>

<p class="text-muted mb-0">

Tidak ada siswa yang dicatat BP/BK hari ini.

</p>

</div>

<?php else: ?>

<div class="table-responsive">

<table class="table table-hover mb-0">

<thead class="thead-light">

<tr>

<th width="5%">#</th>

<th>Nama</th>

<th width="15%">Status</th>

<th>Keterangan</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

foreach($tidak_hadir as $s):

$status = strtoupper($s->status);

$badge='secondary';

if($status=='I') $badge='info';
if($status=='S') $badge='warning';
if($status=='A') $badge='danger';

?>

<tr>

<td><?= $no++ ?></td>

<td>

<strong><?= $s->nama ?></strong>

</td>

<td>

<span class="badge badge-<?= $badge ?>">

<?= $status ?>

</span>

</td>

<td>

<?= $s->keterangan ?: '-' ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php endif; ?>

</div>

</div>

</div>



<!-- ===================================================== -->
<!-- TOP ALPA -->
<!-- ===================================================== -->

<div class="col-lg-5 mb-4">

<div class="card shadow h-100">

<div class="card-header bg-white border-0">

<h5 class="font-weight-bold text-warning mb-0">

<i class="fas fa-trophy mr-2"></i>

Top Alpa Bulan Ini

</h5>

</div>

<div class="card-body">

<?php if(empty($top_alpha)): ?>

<div class="text-center py-5">

<i class="fas fa-smile fa-4x text-success mb-3"></i>

<h5 class="text-success">

Belum ada siswa Alpa

</h5>

<small class="text-muted">

Semoga tetap dipertahankan 😊

</small>

</div>

<?php else: ?>

<?php

$rank=1;

foreach($top_alpha as $t):

?>

<div class="d-flex justify-content-between align-items-center mb-3">

<div>

<h6 class="mb-1">

<?= $rank ?>.

<?= $t->nama ?>

</h6>

<small class="text-muted">

Jumlah Alpa

</small>

</div>

<span class="badge badge-danger badge-pill px-3 py-2">

<?= $t->jumlah ?>

</span>

</div>

<?php

$rank++;

endforeach;

?>

<?php endif; ?>

</div>

</div>

</div>

</div>



<!-- ===================================================== -->
<!-- INFORMASI KELAS -->
<!-- ===================================================== -->

<div class="row">

<div class="col-lg-12 mb-4">

<div class="card shadow">

<div class="card-header bg-white border-0">

<h5 class="font-weight-bold text-primary mb-0">

<i class="fas fa-school mr-2"></i>

Informasi Kelas

</h5>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-3">

<h6 class="text-muted">

Nama Kelas

</h6>

<h5>

<?= $kelas_nama ?>

</h5>

</div>

<div class="col-md-3">

<h6 class="text-muted">

Total Siswa

</h6>

<h5>

<?= $total_siswa ?>

</h5>

</div>

<div class="col-md-3">

<h6 class="text-muted">

Laki-laki

</h6>

<h5 class="text-primary">

<?= $laki ?>

</h5>

</div>

<div class="col-md-3">

<h6 class="text-muted">

Perempuan

</h6>

<h5 class="text-danger">

<?= $perempuan ?>

</h5>

</div>

</div>

</div>

</div>

</div>

</div>


<!-- part4 -->

<!-- ===================================================== -->
<!-- MENU CEPAT + JAM DIGITAL -->
<!-- ===================================================== -->

<?php
$persen_hadir = ($total_siswa > 0)
    ? round(($hari_H / $total_siswa) * 100)
    : 0;
?>

<div class="row">

<!-- ================= MENU CEPAT ================= -->

<div class="col-lg-7 mb-4">

<div class="card shadow h-100">

<div class="card-header bg-white border-0">

<h5 class="font-weight-bold mb-0">

<i class="fas fa-bolt text-warning mr-2"></i>

Menu Cepat

</h5>

</div>

<div class="card-body">

<div class="row text-center">

<div class="col-md-3 col-6 mb-3">

<a href="<?= site_url('walikelas/absensi') ?>" class="text-decoration-none">

<div class="p-3 rounded shadow-sm bg-light">

<i class="fas fa-user-check fa-2x text-success"></i>

<div class="mt-2">

Presensi

</div>

</div>

</a>

</div>



<div class="col-md-3 col-6 mb-3">

<a href="<?= site_url('walikelas/siswa') ?>" class="text-decoration-none">

<div class="p-3 rounded shadow-sm bg-light">

<i class="fas fa-users fa-2x text-primary"></i>

<div class="mt-2">

Data Siswa

</div>

</div>

</a>

</div>



<div class="col-md-3 col-6 mb-3">

<a href="<?= site_url('buku_induk') ?>" class="text-decoration-none">

<div class="p-3 rounded shadow-sm bg-light">

<i class="fas fa-book fa-2x text-warning"></i>

<div class="mt-2">

Buku Induk

</div>

</div>

</a>

</div>



<div class="col-md-3 col-6 mb-3">

<a href="<?= site_url('hasilverval') ?>" class="text-decoration-none">

<div class="p-3 rounded shadow-sm bg-light">

<i class="fas fa-check-circle fa-2x text-info"></i>

<div class="mt-2">

Hasil Verval

</div>

</div>

</a>

</div>

</div>

</div>

</div>

</div>



<!-- ================= JAM DIGITAL ================= -->

<div class="col-lg-5 mb-4">

<div class="card shadow h-100">

<div class="card-body text-center">

<div class="text-muted">

Hari Ini

</div>

<div id="clock"

style="font-size:42px;font-weight:bold;letter-spacing:2px">

00:00:00

</div>

<div id="today"

class="mb-4 text-muted">

</div>

<hr>

<h6>

Persentase Kehadiran

</h6>

<h2 class="font-weight-bold text-success">

<?= $persen_hadir ?>%

</h2>

<div class="progress">

<div class="progress-bar bg-success"

style="width:<?= $persen_hadir ?>%">

</div>

</div>

</div>

</div>

</div>

</div>



<!-- ===================================================== -->
<!-- INFORMASI -->
<!-- ===================================================== -->

<div class="row">

<div class="col-lg-12">

<div class="card shadow">

<div class="card-header bg-white border-0">

<h5 class="font-weight-bold mb-0">

<i class="fas fa-info-circle text-primary mr-2"></i>

Informasi

</h5>

</div>

<div class="card-body">

<div class="alert alert-success">

<i class="fas fa-check-circle"></i>

Dashboard ini menggunakan sistem <strong>Absensi Manual BP/BK</strong>.

</div>

<div class="alert alert-info">

<i class="fas fa-user-check"></i>

BP/BK hanya mencatat siswa yang <strong>Izin, Sakit, atau Alpa</strong>.

</div>

<div class="alert alert-warning">

<i class="fas fa-lightbulb"></i>

Siswa yang tidak tercatat otomatis dianggap <strong>Hadir</strong>.

</div>

</div>

</div>

</div>

</div>


<script>

function updateClock(){

let now=new Date();

document.getElementById("clock").innerHTML=

String(now.getHours()).padStart(2,'0')+":"+

String(now.getMinutes()).padStart(2,'0')+":"+

String(now.getSeconds()).padStart(2,'0');

let hari=[
"Minggu","Senin","Selasa","Rabu",
"Kamis","Jumat","Sabtu"
];

let bulan=[
"Januari","Februari","Maret","April",
"Mei","Juni","Juli","Agustus",
"September","Oktober","November","Desember"
];

document.getElementById("today").innerHTML=

hari[now.getDay()] + ", " +

now.getDate() + " " +

bulan[now.getMonth()] + " " +

now.getFullYear();

}

updateClock();

setInterval(updateClock,1000);

</script>