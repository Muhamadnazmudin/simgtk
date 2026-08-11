<div class="container-fluid walikelas-page">

<style>

/* =========================================================
   WALIKELAS SISWA - MODERN UI
========================================================= */

.walikelas-page {
    padding-bottom: 30px;
}


/* =========================================================
   PAGE HEADER
========================================================= */

.wk-header {
    background: linear-gradient(
        135deg,
        #4e73df,
        #224abe
    );

    color: #fff;

    border-radius: 16px;

    padding: 22px 25px;

    margin-bottom: 20px;

    box-shadow:
        0 8px 25px rgba(0,0,0,.12);

    position: relative;

    overflow: hidden;
}


.wk-header::after {
    content: "";

    position: absolute;

    width: 220px;
    height: 220px;

    border-radius: 50%;

    right: -70px;
    top: -100px;

    background:
        rgba(255,255,255,.08);
}


.wk-header-content {
    position: relative;
    z-index: 2;
}


.wk-title {
    font-size: 1.35rem;

    font-weight: 700;

    margin: 0 0 5px 0;
}


.wk-subtitle {
    font-size: .85rem;

    opacity: .88;

    margin: 0;
}


.wk-class-badge {
    display: inline-flex;

    align-items: center;

    gap: 7px;

    margin-top: 12px;

    padding: 7px 13px;

    border-radius: 20px;

    background:
        rgba(255,255,255,.16);

    border:
        1px solid
        rgba(255,255,255,.22);

    font-size: .8rem;

    font-weight: 600;
}


/* =========================================================
   HEADER ACTION
========================================================= */

.wk-actions {
    display: flex;

    align-items: center;

    justify-content: flex-end;

    gap: 8px;

    flex-wrap: wrap;
}


.wk-actions .btn {
    border-radius: 9px;

    font-weight: 600;

    padding:
        8px 13px;
}


.wk-actions .btn-success {
    background: #198754;

    border-color: #198754;
}


.wk-actions .btn-light {
    color: #212529;

    background: #fff;

    border: none;
}


/* =========================================================
   SEARCH CARD
========================================================= */

.wk-card {
    background: #fff;

    border: none;

    border-radius: 15px;

    box-shadow:
        0 5px 20px rgba(0,0,0,.07);

    margin-bottom: 20px;
}


.wk-card-header {
    padding:
        17px 20px;

    border-bottom:
        1px solid #e9ecef;

    display: flex;

    align-items: center;

    gap: 12px;
}


.wk-card-icon {
    width: 38px;
    height: 38px;

    border-radius: 10px;

    display: flex;

    align-items: center;

    justify-content: center;

    background: #eef3ff;

    color: #4e73df;

    flex-shrink: 0;
}


.wk-card-title {
    font-weight: 700;

    font-size: .95rem;

    color: #343a40;

    margin: 0;
}


.wk-card-desc {
    font-size: .75rem;

    color: #6c757d;

    margin-top: 2px;
}


.wk-card-body {
    padding: 20px;
}


/* =========================================================
   SEARCH
========================================================= */

.wk-search-label {
    font-size: .78rem;

    font-weight: 600;

    color: #495057;

    margin-bottom: 6px;
}


.wk-search-input {
    min-height: 42px;

    border-radius: 9px;

    border:
        1px solid #dee2e6;

    box-shadow: none;
}


.wk-search-input:focus {
    border-color: #4e73df;

    box-shadow:
        0 0 0 .18rem
        rgba(78,115,223,.12);
}


.wk-search-btn {
    min-height: 42px;

    border-radius: 9px;

    font-weight: 600;
}


/* =========================================================
   TABLE CARD
========================================================= */

.wk-table-card {
    background: #fff;

    border-radius: 15px;

    box-shadow:
        0 5px 20px rgba(0,0,0,.07);

    overflow: hidden;
}


.wk-table-header {
    padding:
        17px 20px;

    border-bottom:
        1px solid #e9ecef;

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 15px;

    flex-wrap: wrap;
}


.wk-table-title {
    font-size: .95rem;

    font-weight: 700;

    color: #343a40;
}


.wk-table-subtitle {
    color: #6c757d;

    font-size: .75rem;

    margin-top: 2px;
}


.wk-count {
    display: inline-flex;

    align-items: center;

    gap: 6px;

    padding:
        6px 11px;

    border-radius: 20px;

    background: #eef3ff;

    color: #4e73df;

    font-size: .75rem;

    font-weight: 700;
}


/* =========================================================
   TABLE
========================================================= */

.wk-table-wrapper {
    overflow-x: auto;
}


.wk-table {
    width: 100%;

    margin: 0;

    font-size: .82rem;

    white-space: nowrap;
}


.wk-table thead th {
    background: #f5f7fb;

    color: #495057;

    border-bottom:
        2px solid #e1e5eb;

    border-top: none;

    font-size: .72rem;

    text-transform: uppercase;

    letter-spacing: .35px;

    font-weight: 700;

    padding:
        13px 11px;

    vertical-align: middle;
}


.wk-table tbody td {
    padding:
        11px;

    vertical-align: middle;

    border-color: #edf0f4;

    color: #343a40;
}


.wk-table tbody tr {
    transition:
        background .15s ease;
}


.wk-table tbody tr:hover {
    background:
        rgba(78,115,223,.045);
}


/* =========================================================
   COLUMN WIDTH
========================================================= */

.wk-table th:nth-child(1),
.wk-table td:nth-child(1) {
    width: 55px;

    text-align: center;
}


.wk-table th:nth-child(2),
.wk-table td:nth-child(2) {
    min-width: 115px;
}


.wk-table th:nth-child(3),
.wk-table td:nth-child(3) {
    min-width: 120px;
}


.wk-table th:nth-child(4),
.wk-table td:nth-child(4) {
    min-width: 220px;

    white-space: normal;
}


.wk-table th:nth-child(5),
.wk-table td:nth-child(5) {
    width: 70px;

    text-align: center;
}


.wk-table th:nth-child(6),
.wk-table td:nth-child(6) {
    min-width: 150px;

    white-space: normal;
}


.wk-table th:nth-child(7),
.wk-table td:nth-child(7) {
    min-width: 120px;
}


.wk-table th:nth-child(8),
.wk-table td:nth-child(8) {
    min-width: 120px;
}


.wk-table th:nth-child(9),
.wk-table td:nth-child(9) {
    min-width: 160px;
}


.wk-table th:nth-child(10),
.wk-table td:nth-child(10) {
    width: 100px;

    text-align: center;
}


.wk-table th:nth-child(11),
.wk-table td:nth-child(11) {
    min-width: 110px;

    text-align: center;
}


/* =========================================================
   NAMA SISWA
========================================================= */

.student-name {
    font-weight: 700;

    color: #2f3e5c;
}


.student-sub {
    font-size: .7rem;

    color: #6c757d;

    margin-top: 2px;
}


/* =========================================================
   BADGE STATUS
========================================================= */

.status-active {
    display: inline-flex;

    align-items: center;

    gap: 5px;

    padding:
        6px 10px;

    border-radius: 20px;

    background:
        rgba(25,135,84,.11);

    border:
        1px solid
        rgba(25,135,84,.18);

    color: #198754;

    font-size: .7rem;

    font-weight: 700;
}


/* =========================================================
   ACTION BUTTON
========================================================= */

.wk-actions-cell {
    display: flex;

    align-items: center;

    justify-content: center;

    gap: 5px;
}


.wk-actions-cell .btn {
    width: 34px;

    height: 34px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border-radius: 8px;

    border: none;
}


.btn-idcard {
    background: #4e73df;

    color: #fff;
}


.btn-idcard:hover {
    background: #224abe;

    color: #fff;
}


.btn-biodata {
    background: #36b9cc;

    color: #fff;
}


.btn-biodata:hover {
    background: #258391;

    color: #fff;
}


/* =========================================================
   EMPTY
========================================================= */

.wk-empty {
    text-align: center;

    padding:
        60px 20px;

    color: #6c757d;
}


.wk-empty-icon {
    width: 70px;

    height: 70px;

    border-radius: 50%;

    background: #eef3ff;

    color: #4e73df;

    display: flex;

    align-items: center;

    justify-content: center;

    margin:
        0 auto 15px;

    font-size: 27px;
}


.wk-empty h5 {
    font-size: .95rem;

    font-weight: 700;

    color: #495057;
}


/* =========================================================
   DARK MODE
========================================================= */

body.dark-mode .walikelas-page {
    color: #f1f3f5;
}


body.dark-mode .wk-card,
body.dark-mode .wk-table-card {
    background: #1c1f2b;

    box-shadow:
        0 8px 25px rgba(0,0,0,.25);
}


body.dark-mode .wk-card-header,
body.dark-mode .wk-table-header {
    border-color: #343a55;
}


body.dark-mode .wk-card-title,
body.dark-mode .wk-table-title {
    color: #f1f3f5;
}


body.dark-mode .wk-card-desc,
body.dark-mode .wk-table-subtitle,
body.dark-mode .student-sub {
    color: #adb5bd;
}


body.dark-mode .wk-card-icon {
    background: #252b43;

    color: #8faeff;
}


body.dark-mode .wk-search-label {
    color: #dce1ea;
}


body.dark-mode .wk-search-input {
    background: #161a27;

    color: #f1f3f5;

    border-color: #3b4260;
}


body.dark-mode .wk-search-input::placeholder {
    color: #8e96a8;
}


body.dark-mode .wk-search-input:focus {
    border-color: #6f91ff;

    box-shadow:
        0 0 0 .18rem
        rgba(78,115,223,.22);
}


body.dark-mode .wk-table thead th {
    background: #252b43;

    color: #ffffff;

    border-color: #3b4260;
}


body.dark-mode .wk-table tbody td {
    background: #171b2b;

    color: #e9edf5;

    border-color: #303750;
}


body.dark-mode .wk-table tbody tr:hover td {
    background:
        #202641;

    color: #ffffff;
}


body.dark-mode .student-name {
    color: #ffffff;
}


body.dark-mode .student-sub {
    color: #aeb7c8;
}


body.dark-mode .wk-count {
    background: #252b43;

    color: #a9beff;
}


body.dark-mode .status-active {
    background:
        rgba(25,135,84,.18);

    border-color:
        rgba(68,210,143,.28);

    color: #62e3a2;
}


body.dark-mode .wk-empty {
    color: #adb5bd;
}


body.dark-mode .wk-empty-icon {
    background: #252b43;

    color: #9bb4ff;
}


body.dark-mode .wk-empty h5 {
    color: #e9edf5;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 768px) {

    .wk-header {
        padding: 20px;
    }


    .wk-title {
        font-size: 1.1rem;
    }


    .wk-actions {
        width: 100%;

        justify-content: stretch;

        margin-top: 15px;
    }


    .wk-actions .btn {
        flex: 1;
    }


    .wk-card-body {
        padding: 16px;
    }


    .wk-table-header {
        padding: 15px;
    }


    .wk-table {
        min-width: 1200px;

        font-size: .76rem;
    }


    .wk-table thead th {
        font-size: .68rem;

        padding:
            11px 9px;
    }


    .wk-table tbody td {
        padding:
            9px;
    }


    .wk-actions-cell {
        flex-direction: row;
    }

}

</style>


<!-- =========================================================
     HEADER
========================================================= -->

<div class="wk-header">

    <div class="wk-header-content">

        <div class="row align-items-center">

            <div class="col-lg-8">

                <h4 class="wk-title">

                    <i class="fas fa-users me-2"></i>

                    Data Siswa Kelas <?= $kelas_nama ?>

                </h4>


                <p class="wk-subtitle">

                    Kelola dan lihat data peserta didik
                    pada rombongan belajar Anda.

                </p>


                <span class="wk-class-badge">

                    <i class="fas fa-school"></i>

                    Kelas <?= $kelas_nama ?>

                </span>

            </div>


            <div class="col-lg-4">

                <div class="wk-actions">

                    <!-- Export Excel -->

                    <a
                        href="<?= site_url('walikelas/siswa_export_excel') ?>"
                        class="btn btn-success btn-sm">

                        <i class="fas fa-file-excel me-1"></i>

                        Export Excel

                    </a>


                    <!-- Cetak Semua -->

                    <a
                        href="<?= site_url('walikelas/cetak_biodata_all') ?>"
                        target="_blank"
                        class="btn btn-light btn-sm">

                        <i class="fas fa-print me-1"></i>

                        Cetak Biodata

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     SEARCH CARD
========================================================= -->

<div class="wk-card">

    <div class="wk-card-header">

        <div class="wk-card-icon">

            <i class="fas fa-search"></i>

        </div>


        <div>

            <div class="wk-card-title">

                Cari Data Siswa

            </div>


            <div class="wk-card-desc">

                Cari berdasarkan nama, NIS, atau NISN.

            </div>

        </div>

    </div>


    <div class="wk-card-body">

        <form method="get">

            <div class="row g-3 align-items-end">


                <div class="col-lg-6">

                    <label class="wk-search-label">

                        Kata Pencarian

                    </label>


                    <input
                        type="text"
                        name="search"
                        value="<?= htmlspecialchars($search) ?>"
                        class="form-control wk-search-input"
                        placeholder="Cari nama / NIS / NISN">


                </div>


                <div class="col-lg-2 col-md-4">

                    <button
                        type="submit"
                        class="btn btn-primary wk-search-btn w-100">

                        <i class="fas fa-search me-1"></i>

                        Cari

                    </button>

                </div>


                <div class="col-lg-2 col-md-4">

                    <a
                        href="<?= site_url('walikelas/siswa') ?>"
                        class="btn btn-outline-secondary wk-search-btn w-100">

                        <i class="fas fa-rotate-left me-1"></i>

                        Reset

                    </a>

                </div>


            </div>

        </form>

    </div>

</div>


<!-- =========================================================
     TABLE
========================================================= -->

<div class="wk-table-card">


    <!-- TABLE HEADER -->

    <div class="wk-table-header">


        <div>

            <div class="wk-table-title">

                <i class="fas fa-table text-primary me-2"></i>

                Daftar Peserta Didik

            </div>


            <div class="wk-table-subtitle">

                Data siswa aktif pada kelas
                <?= $kelas_nama ?>

            </div>

        </div>


        <div class="wk-count">

            <i class="fas fa-users"></i>

            <?= count($siswa) ?> Siswa

        </div>


    </div>


    <!-- TABLE -->

    <div class="wk-table-wrapper">


        <table class="table wk-table table-hover">


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


            <?php if (!empty($siswa)): ?>


                <?php $no = 1; ?>


                <?php foreach ($siswa as $s): ?>


                <tr>


                    <!-- NO -->

                    <td>

                        <?= $no++ ?>

                    </td>


                    <!-- NIS -->

                    <td>

                        <?= !empty($s->nis)
                            ? htmlspecialchars($s->nis)
                            : '-'
                        ?>

                    </td>


                    <!-- NISN -->

                    <td>

                        <?= !empty($s->nisn)
                            ? htmlspecialchars($s->nisn)
                            : '-'
                        ?>

                    </td>


                    <!-- NAMA -->

                    <td>

                        <div class="student-name">

                            <?= htmlspecialchars($s->nama) ?>

                        </div>


                        <div class="student-sub">

                            Peserta Didik

                        </div>

                    </td>


                    <!-- JK -->

                    <td>

                        <?= !empty($s->jk)
                            ? htmlspecialchars($s->jk)
                            : '-'
                        ?>

                    </td>


                    <!-- TEMPAT LAHIR -->

                    <td>

                        <?= !empty($s->tempat_lahir)
                            ? htmlspecialchars($s->tempat_lahir)
                            : '-'
                        ?>

                    </td>


                    <!-- TANGGAL LAHIR -->

                    <td>

                        <?= !empty($s->tgl_lahir)
                            ? htmlspecialchars($s->tgl_lahir)
                            : '-'
                        ?>

                    </td>


                    <!-- AGAMA -->

                    <td>

                        <?= !empty($s->agama)
                            ? htmlspecialchars($s->agama)
                            : '-'
                        ?>

                    </td>


                    <!-- IJAZAH -->

                    <td>

                        <?= !empty($s->skhun)
                            ? htmlspecialchars($s->skhun)
                            : '-'
                        ?>

                    </td>


                    <!-- STATUS -->

                    <td>

                        <span class="status-active">

                            <i class="fas fa-circle-check"></i>

                            Aktif

                        </span>

                    </td>


                    <!-- ACTION -->

                    <td>

                        <div class="wk-actions-cell">


                            <!-- ID CARD -->

                            <a
                                href="<?= site_url('idcard/cetak/'.$s->id) ?>"
                                class="btn btn-idcard"
                                target="_blank"
                                title="Cetak ID Card">

                                <i class="fas fa-id-card"></i>

                            </a>


                            <!-- BIODATA -->

                            <a
                                href="<?= site_url('walikelas/cetak_biodata/'.$s->id) ?>"
                                class="btn btn-biodata"
                                target="_blank"
                                title="Cetak Biodata">

                                <i class="fas fa-print"></i>

                            </a>


                        </div>

                    </td>


                </tr>


                <?php endforeach; ?>


            <?php else: ?>


                <tr>

                    <td colspan="11">


                        <div class="wk-empty">


                            <div class="wk-empty-icon">

                                <i class="fas fa-user-slash"></i>

                            </div>


                            <h5>

                                Data Siswa Tidak Ditemukan

                            </h5>


                            <p>

                                Belum ada data siswa yang sesuai
                                dengan pencarian Anda.

                            </p>


                        </div>


                    </td>

                </tr>


            <?php endif; ?>


            </tbody>


        </table>


    </div>

</div>


</div>