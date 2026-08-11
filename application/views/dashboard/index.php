<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$tahun_id = $this->session->userdata('tahun_id');

$tahun = $this->db
    ->get_where('tahun_ajaran', ['id' => $tahun_id])
    ->row();
?>

<style>

/* =========================================================
   ADMIN DASHBOARD
========================================================= */

.admin-dashboard {
    padding-bottom: 30px;
}


/* =========================================================
   HEADER
========================================================= */

.admin-header {

    position: relative;
    overflow: hidden;

    border-radius: 22px;

    padding: 32px 36px;

    margin-bottom: 30px;

    color: #fff;

    background:
        linear-gradient(
            135deg,
            #4e73df 0%,
            #224abe 100%
        );

    box-shadow:
        0 .6rem 1.8rem rgba(0,0,0,.15);

}


.admin-header:before {

    content: "";

    position: absolute;

    width: 280px;
    height: 280px;

    right: -90px;
    top: -110px;

    border-radius: 50%;

    background: rgba(255,255,255,.08);

}


.admin-header:after {

    content: "";

    position: absolute;

    width: 150px;
    height: 150px;

    right: 150px;
    bottom: -100px;

    border-radius: 50%;

    background: rgba(255,255,255,.06);

}


.admin-header-content {

    position: relative;
    z-index: 2;

}


.admin-header h2 {

    font-weight: 700;

    margin-bottom: 6px;

}


.admin-header p {

    margin-bottom: 4px;

    opacity: .9;

}


.admin-header small {

    opacity: .85;

}


.admin-header-icon {

    position: relative;

    z-index: 2;

    text-align: right;

    opacity: .25;

}


/* =========================================================
   SUMMARY CARD
========================================================= */

.admin-summary {

    border: none;

    border-radius: 18px;

    overflow: hidden;

    transition: all .25s ease;

}


.admin-summary:hover {

    transform: translateY(-5px);

    box-shadow:
        0 .9rem 2rem rgba(0,0,0,.15) !important;

}


.admin-summary .card-body {

    padding: 23px;

}


.admin-summary-title {

    font-size: 12px;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .5px;

    color: #888;

}


.admin-summary-number {

    font-size: 32px;

    font-weight: 700;

    line-height: 1;

    margin: 9px 0;

}


.admin-summary-desc {

    font-size: 11px;

    color: #999;

}


.admin-summary-icon {

    width: 62px;

    height: 62px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    color: #fff;

    font-size: 25px;

    flex-shrink: 0;

}


/* =========================================================
   SUMMARY COLORS
========================================================= */

.admin-primary {

    background: #4e73df;

}


.admin-success {

    background: #1cc88a;

}


.admin-info {

    background: #36b9cc;

}


.admin-warning {

    background: #f6c23e;

}


.admin-danger {

    background: #e74a3b;

}


/* =========================================================
   CONTENT CARD
========================================================= */

.admin-card {

    border: none;

    border-radius: 18px;

    overflow: hidden;

}


.admin-card .card-header {

    background: #fff;

    border-bottom: 1px solid #eee;

    padding: 18px 22px;

}


.admin-card-title {

    font-size: 18px;

    font-weight: 700;

}


.admin-card-subtitle {

    font-size: 12px;

    color: #999;

    margin-top: 2px;

}


.admin-card-body {

    padding: 22px;

}


/* =========================================================
   TABLE
========================================================= */

.admin-table {

    margin-bottom: 0;

}


.admin-table thead th {

    font-size: 12px;

    text-transform: uppercase;

    letter-spacing: .3px;

    white-space: nowrap;

}


.admin-table tbody td {

    vertical-align: middle;

}


.admin-table tbody tr {

    transition: .15s;

}


.admin-table tbody tr:hover {

    background: rgba(78,115,223,.04);

}


.admin-table-total {

    font-weight: 700;

}


/* =========================================================
   TOTAL BADGE
========================================================= */

.admin-total {

    font-size: 14px;

    font-weight: 700;

    padding: 7px 12px;

    border-radius: 20px;

}


/* =========================================================
   CHART CARD
========================================================= */

.admin-chart-card {

    border: none;

    border-radius: 18px;

    overflow: hidden;

}


.admin-chart-card .card-body {

    padding: 22px;

}


.admin-chart-title {

    font-size: 17px;

    font-weight: 700;

    margin-bottom: 20px;

}


/* =========================================================
   INFORMATION
========================================================= */

.admin-info-box {

    border-radius: 14px;

    padding: 18px;

    margin-bottom: 12px;

    border: none;

}


.admin-info-box:last-child {

    margin-bottom: 0;

}


/* =========================================================
   DARK MODE
========================================================= */

body.dark-mode .admin-summary {

    background: #2d2d42 !important;

    color: #fff;

}


body.dark-mode .admin-card,
body.dark-mode .admin-chart-card {

    background: #2d2d42 !important;

    color: #ddd;

}


body.dark-mode .admin-card .card-header {

    background: #2d2d42 !important;

    border-color: #44445a;

}


body.dark-mode .admin-card-title {

    color: #fff;

}


body.dark-mode .admin-summary-title {

    color: #aaa;

}


body.dark-mode .admin-summary-desc {

    color: #aaa;

}


body.dark-mode .admin-table {

    color: #ddd;

}


body.dark-mode .admin-table thead th {

    background: #353549 !important;

    color: #fff;

    border-color: #4a4a5f;

}


body.dark-mode .admin-table td {

    border-color: #4a4a5f;

}


body.dark-mode .admin-table tbody tr:hover {

    background: rgba(255,255,255,.03);

}


body.dark-mode .table-secondary {

    background: #40405b !important;

    color: #fff !important;

}


body.dark-mode .text-muted {

    color: #aaa !important;

}


/* =========================================================
   LIGHT MODE
========================================================= */

body.light-mode .admin-card,
body.light-mode .admin-chart-card {

    background: #fff;

}


body.light-mode .admin-summary {

    background: #fff;

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767px) {

    .admin-header {

        padding: 25px 20px;

    }


    .admin-header-icon {

        display: none;

    }


    .admin-header h2 {

        font-size: 25px;

    }


    .admin-summary-number {

        font-size: 27px;

    }


    .admin-card .card-header {

        padding: 16px;

    }


    .admin-card-body {

        padding: 16px;

    }

}

</style>


<div class="container-fluid admin-dashboard">


<!-- =========================================================
     HEADER
========================================================= -->

<div class="admin-header">


    <div class="row align-items-center">


        <div class="col-lg-9 admin-header-content">


            <h2>

                Selamat Datang,
                <?= htmlspecialchars(
                    $this->session->userdata('nama')
                ); ?>

                👋

            </h2>


            <p>

                Dashboard Administrator
                <strong>

                    <?= ucfirst(
                        $this->session->userdata('role_name')
                    ); ?>

                </strong>

            </p>


            <small>

                <i class="fas fa-calendar-alt mr-1"></i>

                Tahun Ajaran Aktif:

                <strong>

                    <?= $tahun ? htmlspecialchars($tahun->tahun) : '-' ?>

                </strong>

            </small>


        </div>


        <div class="col-lg-3 admin-header-icon">


            <i class="fas fa-tachometer-alt fa-5x"></i>


        </div>


    </div>


</div>


<!-- =========================================================
     SUMMARY
========================================================= -->

<div class="row">


<!-- =====================================================
     GURU
===================================================== -->

<div class="col-xl-4 col-md-6 mb-4">


    <div class="card admin-summary shadow h-100">


        <div class="card-body">


            <div class="d-flex justify-content-between align-items-center">


                <div>


                    <div class="admin-summary-title">

                        Jumlah Guru

                    </div>


                    <div class="admin-summary-number text-success">

                        <?= number_format($jumlah_guru, 0, ',', '.') ?>

                    </div>


                    <div class="admin-summary-desc">

                        Tenaga pendidik sekolah

                    </div>


                </div>


                <div class="admin-summary-icon admin-success">

                    <i class="fas fa-chalkboard-teacher"></i>

                </div>


            </div>


        </div>


    </div>


</div>


<!-- =====================================================
     SISWA
===================================================== -->

<div class="col-xl-4 col-md-6 mb-4">


    <div class="card admin-summary shadow h-100">


        <div class="card-body">


            <div class="d-flex justify-content-between align-items-center">


                <div>


                    <div class="admin-summary-title">

                        Peserta Didik Aktif

                    </div>


                    <div class="admin-summary-number text-info">

                        <?= number_format(
                            $jumlah_siswa_aktif,
                            0,
                            ',',
                            '.'
                        ) ?>

                    </div>


                    <div class="admin-summary-desc">

                        Siswa aktif tahun ajaran ini

                    </div>


                </div>


                <div class="admin-summary-icon admin-info">

                    <i class="fas fa-user-graduate"></i>

                </div>


            </div>


        </div>


    </div>


</div>


<!-- =====================================================
     ROMBEL
===================================================== -->

<div class="col-xl-4 col-md-6 mb-4">


    <div class="card admin-summary shadow h-100">


        <div class="card-body">


            <div class="d-flex justify-content-between align-items-center">


                <div>


                    <div class="admin-summary-title">

                        Jumlah Rombel

                    </div>


                    <div class="admin-summary-number text-warning">

                        <?= number_format(
                            $jumlah_rombel,
                            0,
                            ',',
                            '.'
                        ) ?>

                    </div>


                    <div class="admin-summary-desc">

                        Rombel / kelas aktif

                    </div>


                </div>


                <div class="admin-summary-icon admin-warning">

                    <i class="fas fa-school"></i>

                </div>


            </div>


        </div>


    </div>


</div>


</div>


<!-- =========================================================
     ROMBEL + SISWA AKTIF
========================================================= -->

<div class="row">


<!-- =====================================================
     ROMBEL
===================================================== -->

<div class="col-lg-6 mb-4">


    <div class="card admin-card shadow h-100">


        <div class="card-header">


            <div class="admin-card-title text-primary">

                <i class="fas fa-school mr-2"></i>

                Jumlah Rombel / Kelas

            </div>


            <div class="admin-card-subtitle">

                Distribusi rombel berdasarkan tingkat

            </div>


        </div>


        <div class="card-body admin-card-body">


            <div class="table-responsive">


                <table class="table table-bordered table-hover admin-table">


                    <thead class="thead-light">

                        <tr>

                            <th>
                                Tingkat
                            </th>

                            <th width="120"
                                class="text-center">

                                Jumlah

                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <tr>

                            <td>
                                Kelas X
                            </td>

                            <td class="text-center">

                                <span class="admin-total badge badge-primary">

                                    <?= $rombel['x'] ?>

                                </span>

                            </td>

                        </tr>


                        <tr>

                            <td>
                                Kelas XI
                            </td>

                            <td class="text-center">

                                <span class="admin-total badge badge-info">

                                    <?= $rombel['xi'] ?>

                                </span>

                            </td>

                        </tr>


                        <tr>

                            <td>
                                Kelas XII
                            </td>

                            <td class="text-center">

                                <span class="admin-total badge badge-warning">

                                    <?= $rombel['xii'] ?>

                                </span>

                            </td>

                        </tr>


                        <tr class="table-secondary font-weight-bold">

                            <td>

                                Total

                            </td>


                            <td class="text-center">

                                <?= $rombel['total'] ?>

                            </td>

                        </tr>


                    </tbody>


                </table>


            </div>


        </div>


    </div>


</div>


<!-- =====================================================
     SISWA AKTIF
===================================================== -->

<div class="col-lg-6 mb-4">


    <div class="card admin-card shadow h-100">


        <div class="card-header">


            <div class="admin-card-title text-success">

                <i class="fas fa-user-graduate mr-2"></i>

                Siswa Aktif

            </div>


            <div class="admin-card-subtitle">

                Distribusi peserta didik berdasarkan tingkat

            </div>


        </div>


        <div class="card-body admin-card-body">


            <div class="table-responsive">


                <table class="table table-bordered table-hover admin-table">


                    <thead class="thead-light">

                        <tr>

                            <th>
                                Tingkat
                            </th>

                            <th width="120"
                                class="text-center">

                                Jumlah

                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <tr>

                            <td>
                                Kelas X
                            </td>

                            <td class="text-center">

                                <span class="admin-total badge badge-primary">

                                    <?= $aktif['x'] ?>

                                </span>

                            </td>

                        </tr>


                        <tr>

                            <td>
                                Kelas XI
                            </td>

                            <td class="text-center">

                                <span class="admin-total badge badge-info">

                                    <?= $aktif['xi'] ?>

                                </span>

                            </td>

                        </tr>


                        <tr>

                            <td>
                                Kelas XII
                            </td>

                            <td class="text-center">

                                <span class="admin-total badge badge-warning">

                                    <?= $aktif['xii'] ?>

                                </span>

                            </td>

                        </tr>


                        <tr class="table-secondary font-weight-bold">

                            <td>

                                Total

                            </td>


                            <td class="text-center">

                                <?= $aktif['total'] ?>

                            </td>

                        </tr>


                    </tbody>


                </table>


            </div>


        </div>


    </div>


</div>


</div>


<!-- =========================================================
     SISWA KELUAR + MASUK
========================================================= -->

<div class="row">


<!-- =====================================================
     SISWA KELUAR
===================================================== -->

<div class="col-lg-6 mb-4">


    <div class="card admin-card shadow h-100">


        <div class="card-header">


            <div class="admin-card-title text-danger">

                <i class="fas fa-door-open mr-2"></i>

                Siswa Keluar

            </div>


            <div class="admin-card-subtitle">

                Rekap siswa yang keluar

            </div>


        </div>


        <div class="card-body admin-card-body">


            <div class="table-responsive">


                <table class="table table-bordered table-hover admin-table">


                    <thead class="thead-light">

                        <tr>

                            <th>
                                Tingkat
                            </th>

                            <th width="120"
                                class="text-center">

                                Jumlah

                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <tr>

                            <td>Kelas X</td>

                            <td class="text-center">

                                <span class="admin-total badge badge-danger">

                                    <?= $keluar['x'] ?>

                                </span>

                            </td>

                        </tr>


                        <tr>

                            <td>Kelas XI</td>

                            <td class="text-center">

                                <span class="admin-total badge badge-danger">

                                    <?= $keluar['xi'] ?>

                                </span>

                            </td>

                        </tr>


                        <tr>

                            <td>Kelas XII</td>

                            <td class="text-center">

                                <span class="admin-total badge badge-danger">

                                    <?= $keluar['xii'] ?>

                                </span>

                            </td>

                        </tr>


                        <tr class="table-secondary font-weight-bold">

                            <td>Total</td>

                            <td class="text-center">

                                <?= $keluar['total'] ?>

                            </td>

                        </tr>


                    </tbody>


                </table>


            </div>


        </div>


    </div>


</div>


<!-- =====================================================
     SISWA MASUK
===================================================== -->

<div class="col-lg-6 mb-4">


    <div class="card admin-card shadow h-100">


        <div class="card-header">


            <div class="admin-card-title text-info">

                <i class="fas fa-door-closed mr-2"></i>

                Siswa Masuk

            </div>


            <div class="admin-card-subtitle">

                Rekap siswa yang masuk

            </div>


        </div>


        <div class="card-body admin-card-body">


            <div class="table-responsive">


                <table class="table table-bordered table-hover admin-table">


                    <thead class="thead-light">

                        <tr>

                            <th>
                                Tingkat
                            </th>

                            <th width="120"
                                class="text-center">

                                Jumlah

                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <tr>

                            <td>Kelas X</td>

                            <td class="text-center">

                                <span class="admin-total badge badge-info">

                                    <?= $masuk['x'] ?>

                                </span>

                            </td>

                        </tr>


                        <tr>

                            <td>Kelas XI</td>

                            <td class="text-center">

                                <span class="admin-total badge badge-info">

                                    <?= $masuk['xi'] ?>

                                </span>

                            </td>

                        </tr>


                        <tr>

                            <td>Kelas XII</td>

                            <td class="text-center">

                                <span class="admin-total badge badge-info">

                                    <?= $masuk['xii'] ?>

                                </span>

                            </td>

                        </tr>


                        <tr class="table-secondary font-weight-bold">

                            <td>Total</td>

                            <td class="text-center">

                                <?= $masuk['total'] ?>

                            </td>

                        </tr>


                    </tbody>


                </table>


            </div>


        </div>


    </div>


</div>


</div>


<!-- =========================================================
     SISWA LULUS
========================================================= -->

<div class="card admin-card shadow mb-4">


    <div class="card-header">


        <div class="admin-card-title text-warning">

            <i class="fas fa-graduation-cap mr-2"></i>

            Siswa Lulus per Tahun Ajaran

        </div>


        <div class="admin-card-subtitle">

            Rekap kelulusan siswa berdasarkan tahun ajaran

        </div>


    </div>


    <div class="card-body admin-card-body">


        <div class="table-responsive">


            <table class="table table-bordered table-hover admin-table">


                <thead class="thead-light">

                    <tr>

                        <th>
                            Tahun Ajaran
                        </th>

                        <th width="220"
                            class="text-center">

                            Jumlah Siswa Lulus

                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php if (!empty($lulus)): ?>


                    <?php foreach ($lulus as $r): ?>


                        <tr>

                            <td>

                                <i class="fas fa-calendar-alt text-warning mr-2"></i>

                                <?= htmlspecialchars($r->tahun) ?>

                            </td>


                            <td class="text-center">

                                <span class="badge badge-warning px-3 py-2">

                                    <?= $r->jumlah ?>

                                </span>

                            </td>

                        </tr>


                    <?php endforeach; ?>


                <?php else: ?>


                    <tr>

                        <td colspan="2"
                            class="text-center text-muted py-4">

                            <i class="fas fa-info-circle mr-1"></i>

                            Belum ada data siswa lulus.

                        </td>

                    </tr>


                <?php endif; ?>


                </tbody>


            </table>


        </div>


    </div>


</div>


<!-- =========================================================
     GRAFIK
========================================================= -->

<div class="row">


<!-- =====================================================
     BAR CHART
===================================================== -->

<div class="col-lg-8 mb-4">


    <div class="card admin-chart-card shadow h-100">


        <div class="card-body">


            <div class="admin-chart-title text-primary">

                <i class="fas fa-chart-bar mr-2"></i>

                Grafik Siswa per Tingkat

            </div>


            <div style="height:320px;">

                <canvas id="chartAktifKeluar"></canvas>

            </div>


        </div>


    </div>


</div>


<!-- =====================================================
     DOUGHNUT
===================================================== -->

<div class="col-lg-4 mb-4">


    <div class="card admin-chart-card shadow h-100">


        <div class="card-body">


            <div class="admin-chart-title text-info">

                <i class="fas fa-chart-pie mr-2"></i>

                Rasio Aktif vs Keluar

            </div>


            <div style="height:300px;">

                <canvas id="chartRasio"></canvas>

            </div>


        </div>


    </div>


</div>


</div>


<!-- =========================================================
     INFORMASI RINGKAS
========================================================= -->

<div class="card admin-card shadow mb-4">


    <div class="card-header">


        <div class="admin-card-title text-primary">

            <i class="fas fa-info-circle mr-2"></i>

            Informasi Dashboard

        </div>


    </div>


    <div class="card-body admin-card-body">


        <div class="row">


            <div class="col-md-4 mb-3 mb-md-0">


                <div class="alert alert-primary admin-info-box mb-0">


                    <i class="fas fa-chalkboard-teacher mr-2"></i>

                    <strong>
                        <?= number_format($jumlah_guru, 0, ',', '.') ?>
                    </strong>

                    guru tercatat dalam sistem.

                </div>


            </div>


            <div class="col-md-4 mb-3 mb-md-0">


                <div class="alert alert-success admin-info-box mb-0">


                    <i class="fas fa-user-graduate mr-2"></i>

                    <strong>
                        <?= number_format($jumlah_siswa_aktif, 0, ',', '.') ?>
                    </strong>

                    peserta didik aktif.

                </div>


            </div>


            <div class="col-md-4">


                <div class="alert alert-warning admin-info-box mb-0">


                    <i class="fas fa-school mr-2"></i>

                    <strong>
                        <?= number_format($jumlah_rombel, 0, ',', '.') ?>
                    </strong>

                    rombel aktif.

                </div>


            </div>


        </div>


    </div>


</div>


</div>


<!-- =========================================================
     CHART.JS
========================================================= -->

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =====================================================
       BAR CHART
    ===================================================== */

    var canvas1 = document.getElementById('chartAktifKeluar');


    if (canvas1) {


        var ctx1 = canvas1.getContext('2d');


        new Chart(ctx1, {

            type: 'bar',


            data: {

                labels: [
                    'Kelas X',
                    'Kelas XI',
                    'Kelas XII'
                ],


                datasets: [

                    {

                        label: 'Siswa Aktif',

                        data: [

                            <?= (int)$aktif['x'] ?>,
                            <?= (int)$aktif['xi'] ?>,
                            <?= (int)$aktif['xii'] ?>

                        ],

                        backgroundColor:
                            'rgba(28, 200, 138, 0.75)',

                        borderColor:
                            'rgba(28, 200, 138, 1)',

                        borderWidth: 1

                    },


                    {

                        label: 'Siswa Keluar',

                        data: [

                            <?= (int)$keluar['x'] ?>,
                            <?= (int)$keluar['xi'] ?>,
                            <?= (int)$keluar['xii'] ?>

                        ],

                        backgroundColor:
                            'rgba(231, 74, 59, 0.75)',

                        borderColor:
                            'rgba(231, 74, 59, 1)',

                        borderWidth: 1

                    }

                ]

            },


            options: {

                responsive: true,

                maintainAspectRatio: false,


                legend: {

                    position: 'top'

                },


                scales: {

                    yAxes: [

                        {

                            ticks: {

                                beginAtZero: true,

                                precision: 0

                            }

                        }

                    ]

                }

            }

        });

    }



    /* =====================================================
       DOUGHNUT
    ===================================================== */

    var canvas2 = document.getElementById('chartRasio');


    if (canvas2) {


        var ctx2 = canvas2.getContext('2d');


        new Chart(ctx2, {

            type: 'doughnut',


            data: {

                labels: [

                    'Siswa Aktif',

                    'Siswa Keluar'

                ],


                datasets: [{

                    data: [

                        <?= (int)$aktif['total'] ?>,
                        <?= (int)$keluar['total'] ?>

                    ],


                    backgroundColor: [

                        'rgba(28, 200, 138, 0.85)',

                        'rgba(231, 74, 59, 0.85)'

                    ],


                    borderWidth: 0

                }]

            },


            options: {

                responsive: true,

                maintainAspectRatio: false,


                cutoutPercentage: 65,


                legend: {

                    position: 'bottom'

                }

            }

        });

    }


});

</script>