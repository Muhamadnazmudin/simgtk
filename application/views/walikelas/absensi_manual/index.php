<h1 style="color:red">INI VIEW BARU</h1>
<div class="container-fluid">

<div class="d-sm-flex align-items-center justify-content-between mb-4">

    <div class="card shadow-sm border-left-success mb-4">
    <div class="card-body">

        <h3 class="mb-1">
            <i class="fas fa-clipboard-list text-success"></i>
            Rekap Absensi Manual
        </h3>

        <div class="text-muted">

            <strong>Kelas :</strong>
            <?= $kelas_nama; ?>

            &nbsp;&nbsp;|&nbsp;&nbsp;

            <strong>Tahun Ajaran :</strong>
            <?= !empty($tahun_aktif) ? $tahun_aktif->tahun : '-'; ?>

        </div>

    </div>
</div>


<div class="card shadow mb-4">

    <div class="card-header py-3 bg-success text-white">

        <h6 class="m-0 font-weight-bold">
            Filter Laporan
        </h6>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-3">

                <label>Dari Tanggal</label>

                <input
                    type="date"
                    class="form-control"
                    id="dari"
                    value="<?= date('Y-m-01') ?>"
                >

            </div>

            <div class="col-md-3">

                <label>Sampai Tanggal</label>

                <input
                    type="date"
                    class="form-control"
                    id="sampai"
                    value="<?= date('Y-m-d') ?>"
                >

            </div>

            <div class="col-md-3">

                <label>Status</label>

                <select class="form-control" id="keterangan">

                    <option value="all">Semua</option>
                    <option value="H">Hadir</option>
                    <option value="S">Sakit</option>
                    <option value="I">Izin</option>
                    <option value="A">Alpa</option>

                </select>

            </div>

            <div class="col-md-3">

                <label>&nbsp;</label>

                <div>

                    <button
                        class="btn btn-success"
                        id="btnFilter">

                        <i class="fas fa-search"></i>
                        Tampilkan

                    </button>

                    <button
                        class="btn btn-danger"
                        id="btnPDF">

                        <i class="fas fa-file-pdf"></i>

                    </button>

                    <button
                        class="btn btn-success"
                        id="btnExcel">

                        <i class="fas fa-file-excel"></i>

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>


<div class="card shadow">

    <div class="card-header py-3">

        <h6 class="m-0 font-weight-bold text-primary">

            Hasil Rekap Absensi

        </h6>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table
                class="table table-bordered table-hover"
                id="tblAbsensi">

                <thead class="thead-light">

                    <tr>

                        <th width="60">No</th>

                        <th width="120">
                            Tanggal
                        </th>

                        <th>
                            Nama Siswa
                        </th>

                        <th width="140">
                            Kelas
                        </th>

                        <th width="100">
                            Status
                        </th>

                        <th>
                            Keterangan
                        </th>

                    </tr>

                </thead>

                <tbody>

                </tbody>

            </table>

        </div>

    </div>

</div>

</div>

<script>

let table = $('#tblAbsensi').DataTable({

    processing:true,
    destroy:true,
    paging:true,
    searching:false,
    ordering:false,
    info:true,
    autoWidth:false,

    language:{
        emptyTable:"Belum ada data absensi."
    },

    ajax:{
        url:"<?= site_url('Walikelas_absensi_manual/data') ?>",
        type:"POST",

        data:function(d){

            d.dari       = $('#dari').val();
            d.sampai     = $('#sampai').val();
            d.keterangan = $('#keterangan').val();

        },

        dataSrc:""

    },

    columns:[

        {

            data:null,
            width:"50px",

            render:function(data,type,row,meta){

                return meta.row+1;

            }

        },

        {

            data:"tanggal",

            render:function(data){

                if(data==null) return "-";

                let t = data.split("-");

                return t[2]+"-"+t[1]+"-"+t[0];

            }

        },

        {
            data:"nama_siswa"
        },

        {
            data:"nama_kelas"
        },

        {

            data:"status",

            className:"text-center",

            render:function(data){

                if(data=="H"){

                    return '<span class="badge badge-success">Hadir</span>';

                }

                if(data=="S"){

                    return '<span class="badge badge-warning">Sakit</span>';

                }

                if(data=="I"){

                    return '<span class="badge badge-info">Izin</span>';

                }

                if(data=="A"){

                    return '<span class="badge badge-danger">Alpa</span>';

                }

                return '<span class="badge badge-secondary">'+data+'</span>';

            }

        },

        {

            data:"keterangan",

            render:function(data){

                if(data==null || data==""){

                    return "-";

                }

                return data;

            }

        }

    ]

});

</script>

<script>

/* ==========================================================
 * FILTER
 * ========================================================== */

$('#btnFilter').click(function(){

    table.ajax.reload();

});


/* ==========================================================
 * EXPORT PDF
 * ========================================================== */

$('#btnPDF').click(function(){

    let dari   = $('#dari').val();
    let sampai = $('#sampai').val();

    if(dari=='' || sampai==''){

        alert('Tanggal harus diisi.');

        return;

    }

    window.open(
        "<?= site_url('Walikelas_absensi_manual/pdf') ?>"+
        "?dari="+dari+
        "&sampai="+sampai,
        "_blank"
    );

});


/* ==========================================================
 * EXPORT EXCEL
 * ========================================================== */

$('#btnExcel').click(function(){

    let dari   = $('#dari').val();
    let sampai = $('#sampai').val();

    if(dari=='' || sampai==''){

        alert('Tanggal harus diisi.');

        return;

    }

    window.open(
        "<?= site_url('Walikelas_absensi_manual/excel') ?>"+
        "?dari="+dari+
        "&sampai="+sampai
    );

});


/* ==========================================================
 * LOAD PERTAMA
 * ========================================================== */

$(document).ready(function(){

    table.ajax.reload();

});

</script>