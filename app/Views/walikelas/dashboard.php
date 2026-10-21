<?php
$tahun_id = $this->session->userdata('tahun_id');
$tahun = $this->db->get_where('tahun_ajaran', ['id'=>$tahun_id])->row();
?>

<style>

.dashboard-header{
    background:linear-gradient(135deg,#4e73df,#224abe);
    border-radius:20px;
    color:#fff;
    padding:35px;
    margin-bottom:30px;
    overflow:hidden;
    position:relative;
}

.dashboard-header:after{
    content:'';
    position:absolute;
    right:-60px;
    top:-60px;
    width:220px;
    height:220px;
    border-radius:50%;
    background:rgba(255,255,255,.08);
}

.dashboard-header h2{
    font-weight:700;
}

.dashboard-header small{
    color:rgba(255,255,255,.85);
}

.summary-card{

    border:0;
    border-radius:18px;
    transition:.25s;
    overflow:hidden;

}

.summary-card:hover{

    transform:translateY(-5px);

    box-shadow:0 .75rem 2rem rgba(0,0,0,.12);

}

.summary-icon{

    width:60px;
    height:60px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:24px;

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

.summary-number{

    font-size:32px;
    font-weight:700;

}

.summary-title{

    color:#858796;
    text-transform:uppercase;
    font-size:13px;
    letter-spacing:.5px;

}

body.light-mode .summary-card{

    background:#fff;

}

@media(max-width:768px){

.dashboard-header{

padding:25px;

}

.summary-number{

font-size:26px;

}

}
/* ================= FOOTER WIDGET ================= */

.quick-card{
    border:0;
    border-radius:18px;
    transition:.25s;
}

.quick-card:hover{
    transform:translateY(-4px);
    box-shadow:0 .75rem 2rem rgba(0,0,0,.08);
}

.quick-btn{

    display:flex;
    align-items:center;
    justify-content:center;
    flex-direction:column;

    height:120px;

    border-radius:15px;

    text-decoration:none!important;

    transition:.25s;

    color:#555;

}

.quick-btn:hover{

    background:#f8f9fc;

}

.quick-btn i{

    font-size:32px;
    margin-bottom:10px;

}

.progress{

height:12px;

border-radius:20px;

}

.dashboard-clock{

font-size:34px;

font-weight:700;

}

.notice-box{

background:#f8f9fc;

border-radius:15px;

padding:18px;

}

.notice-item{

padding:10px 0;

border-bottom:1px solid #ececec;

}

.notice-item:last-child{

border-bottom:none;

}

</style>

<div class="container-fluid pt-4">

<div class="dashboard-header shadow">

<div class="row align-items-center">

<div class="col-lg-8">

<h2>

Selamat Datang,
<?= $this->session->userdata('nama'); ?> 👋

</h2>

<p class="mb-1">

Wali Kelas

<strong><?= $kelas_nama ?></strong>

</p>

<small>

Tahun Ajaran Aktif

<strong><?= $tahun ? $tahun->tahun : '-' ?></strong>

</small>

</div>

<div class="col-lg-4 text-lg-right mt-4 mt-lg-0">

<i class="fas fa-user-graduate fa-5x opacity-50"></i>

</div>

</div>

</div>

<div class="row">

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

<div class="col-xl-3 col-md-6 mb-4">

<div class="card summary-card shadow h-100">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<div class="summary-title">

Hadir Hari Ini

</div>

<div class="summary-number">

<?= $hari_H ?>

</div>

<small>

Siswa Hadir

</small>

</div>

<div class="summary-icon bg-success-soft">

<i class="fas fa-check-circle"></i>

</div>

</div>

</div>

</div>

</div>

<div class="col-xl-3 col-md-6 mb-4">

<div class="card summary-card shadow h-100">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<div class="summary-title">

Izin / Sakit

</div>

<div class="summary-number">

<?= $hari_I+$hari_S ?>

</div>

<small>

Hari Ini

</small>

</div>

<div class="summary-icon bg-warning-soft">

<i class="fas fa-notes-medical"></i>

</div>

</div>

</div>

</div>

</div>

<div class="col-xl-3 col-md-6 mb-4">

<div class="card summary-card shadow h-100">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<div class="summary-title">

Alpa

</div>

<div class="summary-number">

<?= $hari_A ?>

</div>

<small>

Hari Ini

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

<!-- ========================= DETAIL DASHBOARD ========================= -->

<div class="row">

    <!-- ================= KEHADIRAN HARI INI ================= -->
    <div class="col-lg-6 mb-4">

        <div class="card shadow border-0 h-100">

            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">

                <h5 class="mb-0 font-weight-bold text-success">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Kehadiran Hari Ini
                </h5>

                <span class="badge badge-success px-3 py-2">
                    <?= date('d M Y') ?>
                </span>

            </div>

            <div class="card-body">

                <?php
                $totalHari = $hari_H + $hari_I + $hari_S + $hari_A;
                ?>

                <div class="mb-3">

                    <div class="d-flex justify-content-between mb-1">
                        <span>Hadir</span>
                        <strong><?= $hari_H ?></strong>
                    </div>

                    <div class="progress" style="height:10px;">
                        <div class="progress-bar bg-success"
                             style="width:<?= $totalHari ? ($hari_H/$totalHari)*100 : 0 ?>%">
                        </div>
                    </div>

                </div>

                <div class="mb-3">

                    <div class="d-flex justify-content-between mb-1">
                        <span>Izin</span>
                        <strong><?= $hari_I ?></strong>
                    </div>

                    <div class="progress" style="height:10px;">
                        <div class="progress-bar bg-info"
                             style="width:<?= $totalHari ? ($hari_I/$totalHari)*100 : 0 ?>%">
                        </div>
                    </div>

                </div>

                <div class="mb-3">

                    <div class="d-flex justify-content-between mb-1">
                        <span>Sakit</span>
                        <strong><?= $hari_S ?></strong>
                    </div>

                    <div class="progress" style="height:10px;">
                        <div class="progress-bar bg-warning"
                             style="width:<?= $totalHari ? ($hari_S/$totalHari)*100 : 0 ?>%">
                        </div>
                    </div>

                </div>

                <div>

                    <div class="d-flex justify-content-between mb-1">
                        <span>Alpa</span>
                        <strong><?= $hari_A ?></strong>
                    </div>

                    <div class="progress" style="height:10px;">
                        <div class="progress-bar bg-danger"
                             style="width:<?= $totalHari ? ($hari_A/$totalHari)*100 : 0 ?>%">
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- ================= BULAN INI ================= -->

    <div class="col-lg-6 mb-4">

        <div class="card shadow border-0 h-100">

            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">

                <h5 class="mb-0 font-weight-bold text-warning">

                    <i class="fas fa-calendar-alt mr-2"></i>

                    Rekap Bulan Ini

                </h5>

                <span class="badge badge-warning">

                    <?= date('F') ?>

                </span>

            </div>

            <div class="card-body">

                <div class="row text-center">

                    <div class="col-3">

                        <h2 class="text-success">

                            <?= $bulan_H ?>

                        </h2>

                        <small>Hadir</small>

                    </div>

                    <div class="col-3">

                        <h2 class="text-info">

                            <?= $bulan_I ?>

                        </h2>

                        <small>Izin</small>

                    </div>

                    <div class="col-3">

                        <h2 class="text-warning">

                            <?= $bulan_S ?>

                        </h2>

                        <small>Sakit</small>

                    </div>

                    <div class="col-3">

                        <h2 class="text-danger">

                            <?= $bulan_A ?>

                        </h2>

                        <small>Alpa</small>

                    </div>

                </div>

                <hr>

                <div class="text-center">

                    <h5 class="mb-0">

                        Total Presensi

                    </h5>

                    <h2 class="font-weight-bold text-primary">

                        <?= $bulan_H+$bulan_I+$bulan_S+$bulan_A ?>

                    </h2>

                </div>

            </div>

        </div>

    </div>

</div>



<!-- ====================== BARIS KEDUA ====================== -->

<div class="row">

    <!-- ================= IZIN SISWA ================= -->

    <div class="col-lg-5 mb-4">

        <div class="card shadow border-0 h-100">

            <div class="card-header bg-white border-0">

                <h5 class="font-weight-bold text-danger mb-0">

                    <i class="fas fa-door-open mr-2"></i>

                    Izin Siswa Hari Ini

                </h5>

            </div>

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center py-3">

                    <div>

                        <h6 class="mb-1">

                            Izin Keluar

                        </h6>

                        <small class="text-muted">

                            Keluar saat jam sekolah

                        </small>

                    </div>

                    <span class="badge badge-primary badge-pill px-4 py-2">

                        <?= $izin_keluar_hari_ini ?>

                    </span>

                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center py-3">

                    <div>

                        <h6 class="mb-1">

                            Izin Pulang

                        </h6>

                        <small class="text-muted">

                            Pulang sebelum waktunya

                        </small>

                    </div>

                    <span class="badge badge-success badge-pill px-4 py-2">

                        <?= $izin_pulang_hari_ini ?>

                    </span>

                </div>

                <hr>

                <div class="text-center mt-3">

                    <h6>Total Izin Hari Ini</h6>

                    <h2 class="font-weight-bold text-danger">

                        <?= $izin_keluar_hari_ini+$izin_pulang_hari_ini ?>

                    </h2>

                </div>

            </div>

        </div>

    </div>



    <!-- ================= INFO KELAS ================= -->

    <div class="col-lg-7 mb-4">

        <div class="card shadow border-0 h-100">

            <div class="card-header bg-white border-0">

                <h5 class="font-weight-bold text-primary mb-0">

                    <i class="fas fa-school mr-2"></i>

                    Informasi Kelas

                </h5>

            </div>

            <div class="card-body">

                <table class="table table-borderless mb-0">

                    <tr>

                        <td width="35%">Nama Kelas</td>

                        <th><?= $kelas_nama ?></th>

                    </tr>

                    <tr>

                        <td>Tahun Ajaran</td>

                        <th><?= $tahun ? $tahun->tahun : '-' ?></th>

                    </tr>

                    <tr>

                        <td>Total Siswa</td>

                        <th><?= $total_siswa ?> Orang</th>

                    </tr>

                    <tr>

                        <td>Laki-laki</td>

                        <th><?= $laki ?></th>

                    </tr>

                    <tr>

                        <td>Perempuan</td>

                        <th><?= $perempuan ?></th>

                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>

<?php

$persen_hadir = 0;

if($total_siswa>0){

$persen_hadir = round(($hari_H/$total_siswa)*100);

}

?>

<div class="row">

<!-- ================= QUICK MENU ================= -->

<div class="col-lg-7 mb-4">

<div class="card quick-card shadow">

<div class="card-header bg-white border-0">

<h5 class="font-weight-bold mb-0">

<i class="fas fa-bolt text-warning mr-2"></i>

Menu Cepat

</h5>

</div>

<div class="card-body">

<div class="row text-center">

<div class="col-md-3 col-6 mb-3">

<a href="<?= site_url('walikelas/absensi') ?>" class="quick-btn">

<i class="fas fa-user-check text-success"></i>

Presensi

</a>

</div>

<div class="col-md-3 col-6 mb-3">

<a href="<?= site_url('walikelas/izin') ?>" class="quick-btn">

<i class="fas fa-door-open text-danger"></i>

Izin

</a>

</div>

<div class="col-md-3 col-6 mb-3">

<a href="<?= site_url('walikelas/siswa') ?>" class="quick-btn">

<i class="fas fa-users text-primary"></i>

Data Siswa

</a>

</div>

<div class="col-md-3 col-6 mb-3">

<a href="<?= site_url('hasilverval') ?>" class="quick-btn">

<i class="fas fa-user-circle text-info"></i>

Hasil Verval

</a>

</div>

</div>

</div>

</div>

</div>

<!-- ================= CLOCK ================= -->

<div class="col-lg-5 mb-4">

<div class="card shadow border-0">

<div class="card-body text-center">

<div class="text-muted">

Hari Ini

</div>

<div id="clock" class="dashboard-clock">

00:00:00

</div>

<div id="today">

</div>

<hr>

<h6>

Persentase Kehadiran

</h6>

<h2 class="text-success font-weight-bold">

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

<div class="row">

<!-- ================= PENGUMUMAN ================= -->

<div class="col-lg-6 mb-4">

<div class="card shadow border-0">

<div class="card-header bg-white border-0">

<h5 class="font-weight-bold mb-0">

<i class="fas fa-bullhorn text-primary mr-2"></i>

Informasi

</h5>

</div>

<div class="card-body">

<div class="notice-box">

<div class="notice-item">

<i class="fas fa-check-circle text-success mr-2"></i>

Selamat datang di Dashboard Wali Kelas.

</div>

<div class="notice-item">

<i class="fas fa-calendar text-primary mr-2"></i>

Pastikan presensi siswa diinput setiap hari.

</div>

<div class="notice-item">

<i class="fas fa-user-graduate text-warning mr-2"></i>

Pantau siswa yang sering alpa.

</div>

</div>

</div>

</div>

</div>

<!-- ================= RINGKASAN ================= -->

<div class="col-lg-6 mb-4">

<div class="card shadow border-0">

<div class="card-header bg-white border-0">

<h5 class="font-weight-bold mb-0">

<i class="fas fa-chart-line text-success mr-2"></i>

Ringkasan Hari Ini

</h5>

</div>

<div class="card-body">

<table class="table table-borderless">

<tr>

<td>Total Siswa</td>

<th><?= $total_siswa ?></th>

</tr>

<tr>

<td>Hadir</td>

<th class="text-success"><?= $hari_H ?></th>

</tr>

<tr>

<td>Izin</td>

<th class="text-info"><?= $hari_I ?></th>

</tr>

<tr>

<td>Sakit</td>

<th class="text-warning"><?= $hari_S ?></th>

</tr>

<tr>

<td>Alpa</td>

<th class="text-danger"><?= $hari_A ?></th>

</tr>

</table>

</div>

</div>

</div>

</div>

</div>
