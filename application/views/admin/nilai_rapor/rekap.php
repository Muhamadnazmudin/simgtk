<?php
$isOperator = ($this->session->userdata('role_name') == 'operator');
?>

<style>

:root{
    --primary:#4e73df;
    --primary-dark:#375ad3;
    --border:#e3e6f0;
    --hover:#f8f9fc;
    --header:#4e73df;
}

/* =====================================================
   CARD
===================================================== */

.card{
    border:0;
    border-radius:10px;
}

.card-header{
    background:#fff;
    border-bottom:1px solid var(--border);
    padding:15px 20px;
}

/* =====================================================
   PAGE
===================================================== */

.page-title{

    font-size:26px;

    font-weight:700;

    color:#2e3a59;

}

.page-subtitle{

    color:#858796;

    font-size:13px;

}

/* =====================================================
   FILTER
===================================================== */

.filter-label{

    font-weight:600;

    color:#4b5563;

    margin-bottom:8px;

}

.filter-card .form-control{

    height:42px;

}

.filter-card .btn{

    height:42px;

}

/* =====================================================
   TOOLBAR
===================================================== */

.toolbar{

    display:flex;

    justify-content:space-between;

    align-items:center;

    flex-wrap:wrap;

    gap:15px;

}

.toolbar-title{

    font-size:17px;

    font-weight:700;

    color:#4e73df;

}

.toolbar-info{

    margin-top:4px;

    color:#858796;

    font-size:13px;

}

.toolbar-right{

    display:flex;

    gap:8px;

}

/* =====================================================
   TABLE
===================================================== */

.table-wrapper{

    overflow:auto;

    max-height:600px;

}

.table{

    margin-bottom:0;

    white-space:nowrap;

}

.table thead th{

    background:var(--header);

    color:#fff;

    text-align:center;

    vertical-align:middle;

    position:sticky;

    top:0;

    z-index:10;

}

.table td{

    vertical-align:middle;

}

/* =====================================================
   BUTTON
===================================================== */

.btn i{

    margin-right:4px;

}



/* =====================================================
   TABLE HEADER
===================================================== */

.table thead tr:first-child th{

    padding:12px 8px;

    font-size:13px;

    font-weight:700;

    border-color:#6b86ea;

    vertical-align:middle;

}

.table thead tr:nth-child(2) th{

    padding:6px;

    font-size:12px;

    font-weight:600;

    border-color:#6b86ea;

}

/* Sticky header 2 baris */

.table thead tr:first-child th{

    top:0;

    z-index:12;

}

.table thead tr:nth-child(2) th{

    top:44px;

    z-index:11;

}

/* =========================================
   KOLOM
========================================= */

.col-no{

    width:55px;
    min-width:55px;

}

.col-nisn{

    width:140px;
    min-width:140px;

}

.col-nama{

    width:280px;
    min-width:280px;

    text-align:left !important;

}

.col-kelas{

    width:110px;
    min-width:110px;

}

/* =========================================
   MAPEL
========================================= */

.mapel-header{

    min-width:170px;
    max-width:170px;

    white-space:normal;

    line-height:1.35;

    word-break:break-word;

    text-align:center;

}

/* =========================================
   SEMESTER
========================================= */

.semester{

    width:42px;
    min-width:42px;

    text-align:center;

}

/* Garis pemisah antar mapel */

.mapel-group{

    border-right:2px solid rgba(255,255,255,.25) !important;

}
</style>

<div class="container-fluid">

    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>

            <div class="page-title">

                <i class="fas fa-book text-primary mr-2"></i>

                Rekap Nilai Rapor

            </div>

            <div class="page-subtitle">

                Rekap nilai rapor siswa berdasarkan semester.

            </div>

        </div>

        <a href="<?= site_url('Nilairapor_admin') ?>"
           class="btn btn-secondary shadow-sm">

            <i class="fas fa-arrow-left"></i>

            Kembali

        </a>

    </div>

    <!-- =====================================================
         FILTER
    ====================================================== -->

    <div class="card shadow-sm filter-card mb-4">

        <div class="card-body">

            <form method="get">

                <div class="row align-items-end">

                    <div class="col-md-4">

                        <label class="filter-label">

                            Kelas

                        </label>

                        <select
                            name="kelas"
                            class="form-control">

                            <option value="">

                                Semua Kelas

                            </option>

                            <?php foreach($kelas as $k): ?>

                                <option
                                    value="<?= $k->id ?>"
                                    <?= $this->input->get('kelas')==$k->id?'selected':'' ?>
                                >

                                    <?= $k->nama ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <button
                            class="btn btn-primary btn-block">

                            <i class="fas fa-filter"></i>

                            Filter

                        </button>

                    </div>

                    

                </div>

            </form>

        </div>

    </div>

    <!-- =====================================================
         TABEL
    ====================================================== -->

    <div class="card shadow-sm">

        <div class="card-header">

            <div class="toolbar">

    <div>

        <div class="toolbar-title">

            <i class="fas fa-table mr-1"></i>

            Data Rekap Nilai

        </div>

        <div class="toolbar-info">

            Menampilkan

            <strong><?= number_format(count($rekap)) ?></strong>

            siswa •

            <strong><?= number_format(count($mapel)) ?></strong>

            mata pelajaran

        </div>

    </div>

    <div class="toolbar-right">

    <a href="<?= site_url('Nilairapor_admin/export_rekap_excel?kelas='.$this->input->get('kelas')) ?>"
       class="btn btn-success btn-sm">
        <i class="fas fa-file-excel"></i>
        Export Excel
    </a>

    <?php if (!$isOperator): ?>

    <button
        class="btn btn-danger btn-sm"
        data-toggle="modal"
        data-target="#modalHapusNilai">

        <i class="fas fa-trash"></i>

        Hapus Nilai

    </button>

    <?php endif; ?>

</div>

</div>

        </div>

        <div class="card-body p-0">

            <div class="table-wrapper">

                <table class="table table-bordered table-hover table-sm">
<thead>

<tr>

    <th rowspan="2" class="col-no">

        No

    </th>

    <th rowspan="2" class="col-nisn">

        NISN

    </th>

    <th rowspan="2" class="col-nama">

        Nama Siswa

    </th>

    <th rowspan="2" class="col-kelas">

        Kelas

    </th>

    <?php foreach($mapel as $m): ?>

        <th colspan="6" class="mapel-header mapel-group">

            <?= $m->nama_mapel ?>

        </th>

    <?php endforeach; ?>

</tr>

<tr>

<?php foreach($mapel as $m): ?>

    <?php for($i=1;$i<=6;$i++): ?>

        <th class="semester">

            <?= $i ?>

        </th>

    <?php endfor; ?>

<?php endforeach; ?>

</tr>

</thead>

<tbody>

<?php if(empty($rekap)): ?>

<tr>

    <td colspan="<?= (count($mapel) * 6) + 4 ?>" class="text-center py-5">

        <div class="text-muted">

            <i class="fas fa-folder-open fa-3x mb-3"></i>

            <h5 class="mb-2">

                Data nilai belum tersedia

            </h5>

            <small>

                Silakan lakukan import nilai rapor atau pilih kelas yang memiliki data.

            </small>

        </div>

    </td>

</tr>

<?php else: ?>

<?php

$no = (($page - 1) * 100) + 1;

foreach($rekap as $r):

?>

<tr>

    <td class="text-center">

        <?= $no++ ?>

    </td>

    <td>

        <?= $r['nisn'] ?>

    </td>

    <td>

        <strong>

            <?= $r['nama'] ?>

        </strong>

    </td>

    <td class="text-center">

        <?= $r['kelas'] ?>

    </td>

    <?php foreach($mapel as $m): ?>

        <?php for($smt=1;$smt<=6;$smt++): ?>

            <?php

            $nilai = $r['nilai'][$m->id_mapel][$smt] ?? null;

            if($nilai === null){

                $class = 'text-muted';

                $text  = '-';

            }elseif($nilai < 75){

                $class = 'text-danger font-weight-bold';

                $text  = fmod($nilai,1)==0
                        ? number_format($nilai,0)
                        : number_format($nilai,1);

            }elseif($nilai < 85){

                $class = 'text-warning font-weight-bold';

                $text  = fmod($nilai,1)==0
                        ? number_format($nilai,0)
                        : number_format($nilai,1);

            }else{

                $class = 'text-success font-weight-bold';

                $text  = fmod($nilai,1)==0
                        ? number_format($nilai,0)
                        : number_format($nilai,1);

            }

            ?>

            <td class="text-center <?= $class ?>">

                <?= $text ?>

            </td>

        <?php endfor; ?>

    <?php endforeach; ?>

</tr>

<?php endforeach; ?>

<?php endif; ?>

</tbody>

                </table>

            </div>
            <!-- /.table-wrapper -->

        </div>
        <!-- /.card-body -->

        <?php if(!empty($pagination)): ?>

        <div class="card-footer bg-white border-top">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <small class="text-muted">

                    Menampilkan

                    <strong><?= number_format(count($rekap)) ?></strong>

                    data pada halaman ini.

                </small>

                <div>

                    <?= $pagination ?>

                </div>

            </div>

        </div>

        <?php endif; ?>

    </div>
    <!-- /.card -->

</div>
<!-- /.container-fluid -->
 <?php if (!$isOperator): ?>
<div class="modal fade" id="modalHapusNilai" tabindex="-1">

    <div class="modal-dialog">

        <form action="<?= site_url('Nilairapor_admin/hapus_nilai') ?>" method="post">
<input type="hidden"
       name="<?= $this->security->get_csrf_token_name(); ?>"
       value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden"
                   name="kelas_id"
                   value="<?= $this->input->get('kelas') ?>">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">

                        Hapus Nilai

                    </h5>

                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <div class="form-group">

                        <label>Mata Pelajaran</label>

                        <select
                            name="mapel_id"
                            class="form-control"
                            required>

                            <option value="">

                                -- Pilih Mata Pelajaran --

                            </option>

                            <?php foreach($mapel as $m): ?>

                                <option value="<?= $m->id_mapel ?>">

                                    <?= $m->nama_mapel ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="form-group">

                        <label>Semester</label>

                        <select
                            name="semester"
                            class="form-control">

                            <option value="all">Semua Semester</option>

                            <?php for($i=1;$i<=6;$i++): ?>

                                <option value="<?= $i ?>">

                                    Semester <?= $i ?>

                                </option>

                            <?php endfor; ?>

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">

                        Batal

                    </button>

                    <button
                        class="btn btn-danger">

                        <i class="fas fa-trash"></i>

                        Hapus Nilai

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
<?php endif; ?>