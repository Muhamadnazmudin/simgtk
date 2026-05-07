<div class="container-fluid">

<style>

/* ================= PAGE HEADER ================= */

.top-action{
    gap:12px;
}

.top-action h4{
    font-size:1.2rem;
    font-weight:700;
    margin:0;
}

/* ================= SEARCH ================= */

.search-group .form-control{
    border-radius:10px;
}

.search-group .btn{
    border-radius:10px;
}

/* ================= TABLE ================= */

.table-wrapper{
    background:#fff;
    border-radius:14px;
    padding:14px;
    box-shadow:0 2px 10px rgba(0,0,0,.05);
}

.table-siswa{
    font-size:.88rem;
    margin-bottom:0;
    white-space:nowrap;
}

.table-siswa thead th{
    background:#f8f9fc;
    vertical-align:middle;
    font-size:.82rem;
    font-weight:700;
    border-top:none;
}

.table-siswa td{
    vertical-align:middle;
}

/* Nama siswa */
.table-siswa td:nth-child(4){
    min-width:180px;
    white-space:normal;
    font-weight:600;
}

/* Tempat lahir */
.table-siswa td:nth-child(6){
    min-width:140px;
    white-space:normal;
}

/* No ijazah */
.table-siswa td:nth-child(9){
    min-width:150px;
}

/* Badge */
.badge{
    font-size:.72rem;
    padding:.4em .6em;
    border-radius:8px;
}

/* Action Button */
.action-btn{
    display:flex;
    justify-content:center;
    gap:5px;
}

.action-btn .btn{
    border-radius:8px;
}

/* ================= MOBILE ================= */

@media (max-width:768px){

    /* Header */
    .top-action{
        flex-direction:column;
        align-items:stretch !important;
    }

    .top-action .btn{
        width:100%;
    }

    /* Search */
    .search-group > div{
        margin-bottom:8px;
    }

    /* Table */
    .table-wrapper{
        padding:10px;
        border-radius:12px;
    }

    .table-siswa{
        font-size:.76rem;
    }

    .table-siswa th,
    .table-siswa td{
        padding:.45rem;
    }

    .table-siswa td:nth-child(4){
        min-width:140px;
    }

    .action-btn{
        flex-direction:column;
    }

    .action-btn .btn{
        width:100%;
    }

}

</style>

<!-- ================= HEADER ================= -->

<div class="d-flex justify-content-between align-items-center mb-3 top-action">

    <h4>
        Data Siswa Kelas <?= $kelas_nama ?>
    </h4>

    <div class="d-flex gap-2 flex-wrap">

        <!-- Export Excel -->
        <a href="<?= site_url('walikelas/siswa_export_excel') ?>"
           class="btn btn-success btn-sm">
            <i class="fas fa-file-excel"></i>
            Export Excel
        </a>

        <!-- Cetak Semua -->
        <a href="<?= site_url('walikelas/cetak_biodata_all') ?>"
           target="_blank"
           class="btn btn-dark btn-sm">
            <i class="fas fa-print"></i>
            Cetak Semua Biodata
        </a>

    </div>

</div>

<!-- ================= SEARCH ================= -->

<form method="get" class="row mb-3 search-group">

    <div class="col-md-4">
        <input type="text"
               name="search"
               value="<?= $search ?>"
               class="form-control form-control-sm"
               placeholder="Cari nama / NIS / NISN">
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary btn-sm w-100">
            <i class="fas fa-search"></i>
            Cari
        </button>
    </div>

    <div class="col-md-2">
        <a href="<?= site_url('walikelas/siswa') ?>"
           class="btn btn-secondary btn-sm w-100">
            <i class="fas fa-sync-alt"></i>
            Reset
        </a>
    </div>

</form>

<!-- ================= TABLE ================= -->

<div class="table-wrapper">

<div class="table-responsive">

<table class="table table-bordered table-striped table-hover table-sm table-siswa">

    <thead>
        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>NISN</th>
            <th>Nama</th>
            <th>JK</th>
            <th>Tempat Lahir</th>
            <th>Tgl Lahir</th>
            <th>Agama</th>
            <th>No. Ijazah SMP/MTs</th>
            <th>Status</th>
            <th>ID Card</th>
        </tr>
    </thead>

    <tbody>

        <?php $no=1; foreach ($siswa as $s): ?>

        <tr>

            <td><?= $no++ ?></td>

            <td><?= $s->nis ?></td>

            <td><?= $s->nisn ?></td>

            <td><?= $s->nama ?></td>

            <td><?= $s->jk ?></td>

            <td><?= $s->tempat_lahir ?></td>

            <td><?= $s->tgl_lahir ?></td>

            <td><?= $s->agama ?></td>

            <td><?= $s->skhun ?></td>

            <td>
                <span class="badge badge-success">
                    Aktif
                </span>
            </td>

            <td class="text-center">

                <div class="action-btn">

                    <!-- ID Card -->
                    <a href="<?= site_url('idcard/cetak/'.$s->id) ?>"
                       class="btn btn-primary btn-sm"
                       target="_blank"
                       title="Cetak ID Card">

                        <i class="fas fa-id-card"></i>

                    </a>

                    <!-- Biodata -->
                    <a href="<?= site_url('walikelas/cetak_biodata/'.$s->id) ?>"
                       class="btn btn-info btn-sm"
                       target="_blank"
                       title="Cetak Biodata">

                        <i class="fas fa-print"></i>

                    </a>

                </div>

            </td>

        </tr>

        <?php endforeach; ?>

    </tbody>

</table>

</div>
</div>

</div>