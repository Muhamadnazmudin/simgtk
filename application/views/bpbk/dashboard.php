<?php
$tahun = isset($tahun) ? $tahun : null;
?>

<style>

.dashboard-header{
    background:linear-gradient(135deg,#1cc88a,#13855c);
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

                <b>BP / BK</b>

            </p>

            <small>

                Monitoring Kehadiran Seluruh Siswa

                <br>

                Tahun Ajaran Aktif

                <b><?= $tahun ? $tahun->tahun : '-' ?></b>

            </small>

        </div>

        <div class="col-lg-4 text-right">

            <i class="fas fa-user-shield fa-5x opacity-50"></i>

        </div>

    </div>

</div>


<!-- ===================================================== -->
<!-- CARD SUMMARY -->
<!-- ===================================================== -->

<div class="row">

<!-- JUMLAH SISWA -->

<div class="col-xl-3 col-md-6 mb-4">

<div class="card summary-card shadow h-100">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<div class="summary-title">

Jumlah Siswa

</div>

<div class="summary-number">

<?= number_format($jumlah_siswa) ?>

</div>

<small>

Siswa Aktif

</small>

</div>

<div class="summary-icon bg-primary-soft">

<i class="fas fa-user-graduate"></i>

</div>

</div>

</div>

</div>

</div>



<!-- JUMLAH GURU -->

<div class="col-xl-3 col-md-6 mb-4">

<div class="card summary-card shadow h-100">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<div class="summary-title">

Jumlah Guru

</div>

<div class="summary-number">

<?= number_format($jumlah_guru) ?>

</div>

<small>

Guru Aktif

</small>

</div>

<div class="summary-icon bg-success-soft">

<i class="fas fa-chalkboard-teacher"></i>

</div>

</div>

</div>

</div>

</div>



<!-- JUMLAH KELAS -->

<div class="col-xl-3 col-md-6 mb-4">

<div class="card summary-card shadow h-100">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<div class="summary-title">

Jumlah Kelas

</div>

<div class="summary-number">

<?= number_format($jumlah_kelas) ?>

</div>

<small>

Kelas Aktif

</small>

</div>

<div class="summary-icon bg-warning-soft">

<i class="fas fa-school"></i>

</div>

</div>

</div>

</div>

</div>



<!-- TIDAK HADIR -->

<div class="col-xl-3 col-md-6 mb-4">

<div class="card summary-card shadow h-100">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<div class="summary-title">

Tidak Hadir Hari Ini

</div>

<div class="summary-number text-danger">

<?= isset($tidak_hadir_hari_ini) ? $tidak_hadir_hari_ini : 0 ?>

</div>

<small>

Izin • Sakit • Alpa

</small>

</div>

<div class="summary-icon bg-danger-soft">

<i class="fas fa-user-slash"></i>

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

$totalHari = $jumlah_siswa;

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

<!-- HADIR -->

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

<!-- IZIN -->

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

<!-- SAKIT -->

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

<!-- ALPA -->

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

<?= $bulan_I + $bulan_S + $bulan_A ?>

</h2>

</div>

<div class="col-md-6">

<h6 class="text-muted">

Persentase Hadir

</h6>

<h2 class="font-weight-bold text-success">

<?php

$totalBulan = $bulan_H + $bulan_I + $bulan_S + $bulan_A;

echo ($totalBulan > 0)
    ? round(($bulan_H / $totalBulan) * 100)
    : 0;

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

Tidak ada siswa yang tercatat tidak hadir hari ini.

</p>

</div>

<?php else: ?>

<div class="table-responsive">

<table class="table table-hover mb-0">

<thead class="thead-light">

<tr>

<th width="5%">#</th>

<th>Nama</th>

<th width="18%">Kelas</th>

<th width="12%">Status</th>

<th>Keterangan</th>

</tr>

</thead>

<tbody>

<?php

$no = 1;

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

<?= $s->nama_kelas ?>

</td>

<td>

<span class="badge badge-<?= $badge ?>">

<?= $status ?>

</span>

</td>

<td>

<?= !empty($s->keterangan) ? $s->keterangan : '-' ?>

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

<?= $t->nama_kelas ?>

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
<!-- INFORMASI SEKOLAH -->
<!-- ===================================================== -->

<div class="row">

<div class="col-lg-12 mb-4">

<div class="card shadow">

<div class="card-header bg-white border-0">

<h5 class="font-weight-bold text-primary mb-0">

<i class="fas fa-school mr-2"></i>

Informasi Sekolah

</h5>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-3">

<h6 class="text-muted">

Jumlah Siswa

</h6>

<h5>

<?= number_format($jumlah_siswa) ?>

</h5>

</div>

<div class="col-md-3">

<h6 class="text-muted">

Jumlah Guru

</h6>

<h5>

<?= number_format($jumlah_guru) ?>

</h5>

</div>

<div class="col-md-3">

<h6 class="text-muted">

Jumlah Kelas

</h6>

<h5>

<?= number_format($jumlah_kelas) ?>

</h5>

</div>

<div class="col-md-3">

<h6 class="text-muted">

Tahun Ajaran

</h6>

<h5>

<?= $tahun ? $tahun->tahun : '-' ?>

</h5>

</div>

</div>

</div>

</div>

</div>

</div>


<!-- prat4 -->
 <!-- ===================================================== -->
<!-- MENU CEPAT + JAM DIGITAL -->
<!-- ===================================================== -->

<?php
$persen_hadir = ($jumlah_siswa > 0)
    ? round(($hari_H / $jumlah_siswa) * 100)
    : 0;
?>

<div class="row">

<!-- ================= MENU CEPAT ================= -->

<div class="col-lg-7 mb-4">

<div class="card shadow h-100">

<div class="card-header bg-white border-0">

<h5 class="font-weight-bold mb-0">

<i class="fas fa-bolt text-warning mr-2"></i>

Menu Cepat BP/BK

</h5>

</div>

<div class="card-body">

<div class="row text-center">

<!-- ABSENSI MANUAL -->

<div class="col-md-3 col-6 mb-3">

<a href="<?= site_url('bpbk/absensi_manual') ?>" class="text-decoration-none">

<div class="p-3 rounded shadow-sm bg-light">

<i class="fas fa-user-check fa-2x text-success"></i>

<div class="mt-2">

Absensi Manual

</div>

</div>

</a>

</div>

<!-- ABSENSI QR -->

<div class="col-md-3 col-6 mb-3">

<a href="<?= site_url('bpbk/absensi_qr') ?>" class="text-decoration-none">

<div class="p-3 rounded shadow-sm bg-light">

<i class="fas fa-qrcode fa-2x text-primary"></i>

<div class="mt-2">

Absensi QR

</div>

</div>

</a>

</div>

<!-- IZIN -->

<div class="col-md-3 col-6 mb-3">

<a href="<?= site_url('bpbk/izin') ?>" class="text-decoration-none">

<div class="p-3 rounded shadow-sm bg-light">

<i class="fas fa-notes-medical fa-2x text-warning"></i>

<div class="mt-2">

Izin Siswa

</div>

</div>

</a>

</div>

<!-- BUKU TAMU -->

<div class="col-md-3 col-6 mb-3">

<a href="<?= site_url('buku_tamu') ?>" class="text-decoration-none">

<div class="p-3 rounded shadow-sm bg-light">

<i class="fas fa-address-book fa-2x text-info"></i>

<div class="mt-2">

Buku Tamu

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

Persentase Kehadiran Hari Ini

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
<!-- INFORMASI DASHBOARD -->
<!-- ===================================================== -->

<div class="row">

<div class="col-lg-12">

<div class="card shadow">

<div class="card-header bg-white border-0">

<h5 class="font-weight-bold mb-0">

<i class="fas fa-info-circle text-primary mr-2"></i>

Informasi Dashboard BP/BK

</h5>

</div>

<div class="card-body">

<div class="alert alert-success">

<i class="fas fa-check-circle"></i>

Dashboard ini menampilkan rekap kehadiran seluruh siswa berdasarkan data absensi manual dan QR.

</div>

<div class="alert alert-info">

<i class="fas fa-user-check"></i>

BP/BK dapat memantau siswa yang hadir, izin, sakit, maupun alpa dari seluruh kelas.

</div>

<div class="alert alert-warning">

<i class="fas fa-chart-line"></i>

Data statistik akan berubah otomatis sesuai tanggal dan tahun ajaran yang aktif.

</div>

</div>

</div>

</div>

</div>



<script>

function updateClock(){

let now = new Date();

document.getElementById("clock").innerHTML =

String(now.getHours()).padStart(2,'0') + ":" +

String(now.getMinutes()).padStart(2,'0') + ":" +

String(now.getSeconds()).padStart(2,'0');

let hari = [

"Minggu","Senin","Selasa","Rabu",

"Kamis","Jumat","Sabtu"

];

let bulan = [

"Januari","Februari","Maret","April",

"Mei","Juni","Juli","Agustus",

"September","Oktober","November","Desember"

];

document.getElementById("today").innerHTML =

hari[now.getDay()] + ", " +

now.getDate() + " " +

bulan[now.getMonth()] + " " +

now.getFullYear();

}

updateClock();

setInterval(updateClock,1000);

</script>

</div>