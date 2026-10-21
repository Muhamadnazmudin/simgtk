<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>

.card{
    border:none;
    border-radius:12px;
}

.card-header{
    font-weight:600;
}

.form-control{
    border-radius:8px;
}

.form-control:focus{
    box-shadow:none;
    border-color:#4e73df;
}

.btn{
    border-radius:8px;
}

.page-header{
    background:linear-gradient(135deg,#4e73df,#224abe) !important;
    border:0 !important;
}

.page-header .card-body{
    background:transparent !important;
}
/* Light Mode */
body.light-mode .page-header h4,
body.light-mode .page-header p,
body.light-mode .page-header strong,
body.light-mode .page-header i{
    color:#212529 !important;
}

/* Dark Mode */
body.dark-mode .page-header h4,
body.dark-mode .page-header p,
body.dark-mode .page-header strong,
body.dark-mode .page-header i{
    color:#ffffff !important;
}
.page-header h4{
    margin:0;
    font-weight:700;
}

.page-header p{
    margin:6px 0 0;
    opacity:.9;
}

.icon-header{

    width:70px;
    height:70px;
    border-radius:50%;
    background:rgba(255,255,255,.18);

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:28px;

}

.filter-title{

    font-size:15px;
    font-weight:600;

}

label{

    font-weight:600;
    font-size:13px;

}

</style>


<div class="container-fluid">

    <!-- ================= HEADER ================= -->

    <div class="card shadow mb-4 page-header">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-9">

                    <h4 class="text-white">
    <i class="fas fa-clipboard-check mr-2"></i>
    Rekap Absensi QR
</h4>

<p class="text-white mb-0">
    Rekap absensi siswa kelas
    <strong class="text-white"><?= $kelas_nama ?></strong>
    berdasarkan nama, tanggal dan status kehadiran.
</p>

                </div>

                <div class="col-md-3 text-right d-none d-md-block">

                    <div class="icon-header ml-auto">

                        <i class="fas fa-user-check"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ================= FILTER ================= -->

    <div class="card shadow mb-4">

        <div class="card-header bg-white">

            <span class="filter-title">

                <i class="fas fa-filter text-primary mr-2"></i>

                Filter Pencarian

            </span>

        </div>

        <div class="card-body">

            <form id="formLaporanQR">

                <input
                    type="hidden"
                    name="<?= $this->security->get_csrf_token_name();?>"
                    value="<?= $this->security->get_csrf_hash();?>">

                <div class="row">

                    <div class="col-lg-3 col-md-6 mb-3">

                        <label>Nama Siswa</label>

                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            class="form-control"
                            placeholder="Cari nama siswa...">

                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">

                        <label>Dari Tanggal</label>

                        <input
                            type="date"
                            id="dari"
                            name="dari"
                            class="form-control"
                            value="<?= date('Y-m-d')?>">

                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">

                        <label>Sampai Tanggal</label>

                        <input
                            type="date"
                            id="sampai"
                            name="sampai"
                            class="form-control"
                            value="<?= date('Y-m-d')?>">

                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">

                        <label>Status Kehadiran</label>

                        <select
                            id="keterangan"
                            name="keterangan"
                            class="form-control">

                            <option value="">Semua Status</option>
                            <option value="H">Hadir</option>
                            <option value="Terlambat">Terlambat</option>
                            <option value="I">Izin</option>
                            <option value="S">Sakit</option>
                            <option value="A">Alpa</option>

                        </select>

                    </div>

                </div>

                <hr>

                <div class="text-right">

                    <button
                        id="btnTampil"
                        type="button"
                        class="btn btn-primary">

                        <i class="fas fa-search"></i>

                        Tampilkan

                    </button>

                    <button
                        id="btnPdf"
                        type="button"
                        class="btn btn-danger">

                        <i class="fas fa-file-pdf"></i>

                        PDF

                    </button>

                    <button
                        id="btnExcel"
                        type="button"
                        class="btn btn-success">

                        <i class="fas fa-file-excel"></i>

                        Excel

                    </button>

                </div>

            </form>

        </div>

    </div>

       <!-- ================= RINGKASAN ================= -->

    <div class="row mb-4" id="summaryRow" style="display:none;">

        <div class="col-xl-3 col-md-6 mb-3">

            <div class="card border-left-primary shadow h-100">

                <div class="card-body">

                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">

                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">

                                Total Data

                            </div>

                            <div class="h4 mb-0 font-weight-bold text-gray-800" id="totalData">

                                0

                            </div>

                        </div>

                        <div class="col-auto">

                            <i class="fas fa-database fa-2x text-gray-300"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6 mb-3">

            <div class="card border-left-success shadow h-100">

                <div class="card-body">

                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">

                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">

                                Hadir

                            </div>

                            <div class="h4 mb-0 font-weight-bold text-gray-800" id="totalHadir">

                                0

                            </div>

                        </div>

                        <div class="col-auto">

                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6 mb-3">

            <div class="card border-left-warning shadow h-100">

                <div class="card-body">

                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">

                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">

                                Izin / Sakit

                            </div>

                            <div class="h4 mb-0 font-weight-bold text-gray-800" id="totalIzin">

                                0

                            </div>

                        </div>

                        <div class="col-auto">

                            <i class="fas fa-user-clock fa-2x text-gray-300"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6 mb-3">

            <div class="card border-left-danger shadow h-100">

                <div class="card-body">

                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">

                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">

                                Alpa

                            </div>

                            <div class="h4 mb-0 font-weight-bold text-gray-800" id="totalAlpa">

                                0

                            </div>

                        </div>

                        <div class="col-auto">

                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ================= HASIL ================= -->

    <div class="card shadow mb-4" id="hasilBox" style="display:none;">

        <div class="card-header py-3 d-flex justify-content-between align-items-center">

            <h6 class="m-0 font-weight-bold text-primary">

                <i class="fas fa-table mr-2"></i>

                Hasil Rekap Absensi

            </h6>

            <span class="badge badge-primary px-3 py-2" id="jumlahRecord">

                0 Data

            </span>

        </div>

        <div class="card-body">

            <!-- Loading -->

            <div id="loadingBox" class="text-center py-5" style="display:none;">

                <div class="spinner-border text-primary"></div>

                <div class="mt-3">

                    Sedang mengambil data...

                </div>

            </div>

            <!-- Tidak ada data -->

            <div id="emptyBox" class="text-center py-5" style="display:none;">

                <i class="fas fa-folder-open fa-4x text-gray-400 mb-3"></i>

                <h5 class="text-gray-700">

                    Data Tidak Ditemukan

                </h5>

                <p class="text-muted mb-0">

                    Silakan ubah filter pencarian.

                </p>

            </div>

            <!-- Tabel -->

            <div class="table-responsive" id="tableBox">

                <table class="table table-bordered table-hover">

                    <thead class="thead-light">

                        <tr>

                            <th width="60">No</th>

                            <th>Tanggal</th>

                            <th>Nama Siswa</th>

                            <th>Kehadiran</th>

                            <th>Jam Masuk</th>

                            <th>Jam Pulang</th>

                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody id="hasilBody">

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<script>

function badgeKehadiranJS(k){

    k = (k || "").toString().trim();

    switch(k){

        case "H":
            return "<span class='badge badge-success'>Hadir</span>";

        case "Terlambat":
            return "<span class='badge badge-warning text-dark'>Terlambat</span>";

        case "I":
            return "<span class='badge badge-primary'>Izin</span>";

        case "S":
            return "<span class='badge badge-info text-dark'>Sakit</span>";

        case "A":
            return "<span class='badge badge-danger'>Alpa</span>";

        default:
            return "<span class='badge badge-secondary'>-</span>";

    }

}

$(document).ready(function(){

    $("#btnTampil").click(function(){

        $("#hasilBox").show();

        $("#summaryRow").hide();

        $("#tableBox").hide();

        $("#emptyBox").hide();

        $("#loadingBox").show();

        $.ajax({

            url:"<?= site_url('walikelas/absensi_data') ?>",

            type:"POST",

            dataType:"json",

            data:{

                nama:$("#nama").val(),

                dari:$("#dari").val(),

                sampai:$("#sampai").val(),

                status:$("#keterangan").val(),

                "<?= $this->security->get_csrf_token_name();?>":
                "<?= $this->security->get_csrf_hash();?>"

            },

            success:function(res){

                $("#loadingBox").hide();

                if(!Array.isArray(res)){

                    $("#tableBox").hide();

                    $("#emptyBox").show().html(

                        "<div class='text-center py-5'><i class='fas fa-exclamation-circle fa-3x text-danger mb-3'></i><br>Format data tidak valid.</div>"

                    );

                    return;

                }

                if(res.length===0){

                    $("#tableBox").hide();

                    $("#summaryRow").hide();

                    $("#jumlahRecord").text("0 Data");

                    $("#emptyBox").show();

                    return;

                }

                let html="";

                let no=1;

                let hadir=0;

                let izin=0;

                let alpa=0;

                res.forEach(function(r){

                    if(r.kehadiran==="H"){

                        hadir++;

                    }

                    if(r.kehadiran==="I" || r.kehadiran==="S"){

                        izin++;

                    }

                    if(r.kehadiran==="A"){

                        alpa++;

                    }

                    html += `
                    <tr>

                        <td class="text-center">${no++}</td>

                        <td>${r.tanggal}</td>

                        <td>

                            <strong>${r.nama_siswa}</strong>

                        </td>

                        <td class="text-center">

                            ${badgeKehadiranJS(r.kehadiran)}

                        </td>

                        <td class="text-center">

                            ${r.jam_masuk ? r.jam_masuk : "-"}

                        </td>

                        <td class="text-center">

                            ${r.jam_pulang ? r.jam_pulang : "-"}

                        </td>

                        <td>

                            ${r.status ? r.status : "-"}

                        </td>

                    </tr>
                    `;

                });

                $("#hasilBody").html(html);

                $("#jumlahRecord").text(res.length+" Data");

                $("#totalData").text(res.length);

                $("#totalHadir").text(hadir);

                $("#totalIzin").text(izin);

                $("#totalAlpa").text(alpa);

                $("#summaryRow").fadeIn(200);

                $("#tableBox").fadeIn(200);

            },

            error:function(xhr){

                $("#loadingBox").hide();

                $("#tableBox").hide();

                $("#summaryRow").hide();

                $("#emptyBox").show().html(

                    "<div class='text-center py-5'><i class='fas fa-times-circle fa-3x text-danger mb-3'></i><br>Gagal mengambil data dari server.</div>"

                );

                console.log(xhr.responseText);

            }

        });

    });


    $("#btnPdf").click(function(){

        window.open(

            "<?= site_url('walikelas/absensi_pdf')?>"

            +"?dari="+$("#dari").val()

            +"&sampai="+$("#sampai").val()

            +"&nama="+encodeURIComponent($("#nama").val())

            +"&status="+$("#keterangan").val(),

            "_blank"

        );

    });


    $("#btnExcel").click(function(){

        window.location.href=

            "<?= site_url('walikelas/absensi_excel')?>"

            +"?dari="+$("#dari").val()

            +"&sampai="+$("#sampai").val()

            +"&nama="+encodeURIComponent($("#nama").val())

            +"&status="+$("#keterangan").val();

    });

});
</script>