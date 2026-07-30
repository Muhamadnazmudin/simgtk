<div class="container-fluid">
<?php
$isOperator = ($this->session->userdata('role_name') == 'operator');
?>
    <!-- ========================= -->
    <!-- HEADER -->
    <!-- ========================= -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>

            <h1 class="h3 mb-1 text-gray-800">
                <i class="fas fa-book text-primary mr-2"></i>
                Buku Induk Peserta Didik
            </h1>

            <p class="mb-0 text-muted">
                Data lengkap peserta didik SMKN 1 Cilimus
            </p>

        </div>

        <div>

            <a href="<?= site_url('buku_induk') ?>"
               class="btn btn-secondary shadow-sm">

                <i class="fas fa-arrow-left"></i>

                Kembali

            </a>

            <a href="<?= site_url('buku_induk/cetak/'.$siswa->id) ?>"
               class="btn btn-danger shadow-sm">

                <i class="fas fa-file-pdf"></i>

                Cetak PDF

            </a>

            <?php if (!$isOperator): ?>

<a href="<?= site_url('siswa/edit/'.$siswa->id) ?>"
   class="btn btn-warning shadow-sm">

    <i class="fas fa-edit"></i>

    Edit

</a>

<?php endif; ?>

        </div>

    </div>


    <!-- ========================= -->
    <!-- PROFILE -->
    <!-- ========================= -->

    <div class="card shadow mb-4">

        <div class="card-body">

            <div class="row">

                <!-- FOTO -->

                <div class="col-lg-3 text-center">

                    <?php

                    if(!empty($siswa->foto) && file_exists(FCPATH.'uploads/foto/'.$siswa->foto))
                    {
                        $foto = base_url('uploads/foto/'.$siswa->foto);
                    }
                    else
                    {
                        $foto = base_url('assets/img/default.png');
                    }

                    ?>

   <img
    src="<?= $foto ?>"
    class="rounded-circle shadow-sm mb-3"
    style="
        width:100px;
        height:100px;
        object-fit:cover;
        object-position:center 15%;
        border:4px solid #e9ecef;
        transform:scale(1.35);
    ">

                    <br>

                    <?php

                    switch($siswa->status)
                    {
                        case 'aktif':

                            $badge='success';
                            $icon='fa-user-check';
                            break;

                        case 'lulus':

                            $badge='primary';
                            $icon='fa-graduation-cap';
                            break;

                        case 'keluar':

                            $badge='danger';
                            $icon='fa-sign-out-alt';
                            break;

                        default:

                            $badge='secondary';
                            $icon='fa-info-circle';
                    }

                    ?>

                    <span class="badge badge-<?= $badge ?> p-2">

                        <i class="fas <?= $icon ?>"></i>

                        <?= strtoupper($siswa->status) ?>

                    </span>

                </div>


                <!-- IDENTITAS -->

                <div class="col-lg-9">

                    <h2 class="font-weight-bold text-primary mb-1">

                        <?= strtoupper($siswa->nama) ?>

                    </h2>

                    <p class="text-muted mb-4">

                        Buku Induk Peserta Didik

                    </p>

                    <div class="row">

                        <div class="col-md-6">

                            <table class="table table-borderless table-sm">

                                <tr>

                                    <th width="150">

                                        NIS

                                    </th>

                                    <td>

                                        :

                                    </td>

                                    <td>

                                        <?= $siswa->nis ?: '-' ?>

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        NISN

                                    </th>

                                    <td>:</td>

                                    <td>

                                        <?= $siswa->nisn ?: '-' ?>

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        Jenis Kelamin

                                    </th>

                                    <td>:</td>

                                    <td>

                                        <?= $siswa->jk ?: '-' ?>

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        Tempat, Tgl Lahir

                                    </th>

                                    <td>:</td>

                                    <td>

                                        <?= $siswa->tempat_lahir ?>

                                        <?php if(!empty($siswa->tgl_lahir)){ ?>

                                            ,

                                            <?= date('d-m-Y',strtotime($siswa->tgl_lahir)) ?>

                                        <?php } ?>

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        Agama

                                    </th>

                                    <td>:</td>

                                    <td>

                                        <?= $siswa->agama ?: '-' ?>

                                    </td>

                                </tr>

                            </table>

                        </div>

                        <div class="col-md-6">

                            <table class="table table-borderless table-sm">

                                <tr>

                                    <th width="150">

                                        Kelas

                                    </th>

                                    <td>:</td>

                                    <td>

                                        <?= $siswa->nama_kelas ?: '-' ?>

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        Tahun Ajaran

                                    </th>

                                    <td>:</td>

                                    <td>

                                        <?= $siswa->tahun_ajaran ?: '-' ?>

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        No. HP

                                    </th>

                                    <td>:</td>

                                    <td>

                                        <?= $siswa->hp ?: '-' ?>

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        Email

                                    </th>

                                    <td>:</td>

                                    <td>

                                        <?= $siswa->email ?: '-' ?>

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        Alamat

                                    </th>

                                    <td>:</td>

                                    <td>

                                        <?= $siswa->alamat ?: '-' ?>

                                    </td>

                                </tr>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- ========================================================= -->
<!-- NAVIGATION -->
<!-- ========================================================= -->

<div class="card shadow mb-4">

    <div class="card-body p-2">

        <ul class="nav nav-pills nav-fill" id="bukuIndukTab" role="tablist">

            <li class="nav-item">

                <a class="nav-link active"
                   id="profil-tab"
                   data-toggle="pill"
                   href="#profil"
                   role="tab">

                    <i class="fas fa-user mr-1"></i>

                    Profil

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link"
                   id="ortu-tab"
                   data-toggle="pill"
                   href="#ortu"
                   role="tab">

                    <i class="fas fa-users mr-1"></i>

                    Orang Tua

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link"
                   id="pendidikan-tab"
                   data-toggle="pill"
                   href="#pendidikan"
                   role="tab">

                    <i class="fas fa-school mr-1"></i>

                    Pendidikan

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link"
                   id="periodik-tab"
                   data-toggle="pill"
                   href="#periodik"
                   role="tab">

                    <i class="fas fa-chart-line mr-1"></i>

                    Periodik

                </a>

            </li>
<li class="nav-item">

    <a class="nav-link"
       id="rapor-tab"
       data-toggle="pill"
       href="#rapor"
       role="tab">

        <i class="fas fa-book-open mr-1"></i>

        Nilai Rapor

    </a>

</li>
<li class="nav-item">

    <a class="nav-link"
       id="kehadiran-tab"
       data-toggle="pill"
       href="#kehadiran"
       role="tab">

        <i class="fas fa-calendar-check mr-1"></i>

        Kehadiran

    </a>

</li>
            <li class="nav-item">

                <a class="nav-link"
                   id="dokumen-tab"
                   data-toggle="pill"
                   href="#dokumen"
                   role="tab">

                    <i class="fas fa-folder-open mr-1"></i>

                    Dokumen

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link"
                   id="mutasi-tab"
                   data-toggle="pill"
                   href="#mutasi"
                   role="tab">

                    <i class="fas fa-random mr-1"></i>

                    Mutasi

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link"
                   id="timeline-tab"
                   data-toggle="pill"
                   href="#timeline"
                   role="tab">

                    <i class="fas fa-history mr-1"></i>

                    Timeline

                </a>

            </li>

        </ul>

    </div>

</div>
<!-- ========================================================= -->
<!-- TAB CONTENT -->
<!-- ========================================================= -->

<div class="tab-content" id="bukuIndukContent">

    <!-- ===================================== -->
    <!-- PROFIL -->
    <!-- ===================================== -->

    <div class="tab-pane fade show active"
         id="profil"
         role="tabpanel">

        <div class="card shadow mb-4">

            <div class="card-header bg-primary text-white">

                <i class="fas fa-user mr-2"></i>

                Profil Peserta Didik

            </div>

            <div class="row">

    <div class="col-lg-6">

        <table class="table table-borderless table-sm">

            <tr><th width="180">Nama Lengkap</th><td><?= $siswa->nama ?: '-' ?></td></tr>

            <tr><th>NIS</th><td><?= $siswa->nis ?: '-' ?></td></tr>

            <tr><th>NISN</th><td><?= $siswa->nisn ?: '-' ?></td></tr>

            <tr><th>Jenis Kelamin</th><td><?= $siswa->jk ?: '-' ?></td></tr>

            <tr>
                <th>Tempat, Tgl Lahir</th>
                <td>

                    <?= $siswa->tempat_lahir ?: '-' ?>

                    <?php if(!empty($siswa->tgl_lahir)): ?>

                        , <?= date('d-m-Y',strtotime($siswa->tgl_lahir)); ?>

                    <?php endif; ?>

                </td>
            </tr>

            <tr><th>Agama</th><td><?= $siswa->agama ?: '-' ?></td></tr>

            <tr><th>NIK</th><td><?= $siswa->nik ?: '-' ?></td></tr>

            <tr><th>No KK</th><td><?= $siswa->nomor_kk ?: '-' ?></td></tr>

            <tr><th>Anak Ke</th><td><?= $siswa->anak_keberapa ?: '-' ?></td></tr>

        </table>

    </div>

    <div class="col-lg-6">

        <table class="table table-borderless table-sm">

            <tr><th width="180">Alamat</th><td><?= $siswa->alamat ?: '-' ?></td></tr>

            <tr><th>RT / RW</th><td><?= ($siswa->rt ?: '-') .' / '. ($siswa->rw ?: '-') ?></td></tr>

            <tr><th>Dusun</th><td><?= $siswa->dusun ?: '-' ?></td></tr>

            <tr><th>Kecamatan</th><td><?= $siswa->kecamatan ?: '-' ?></td></tr>

            <tr><th>Kode POS</th><td><?= $siswa->kode_pos ?: '-' ?></td></tr>

            <tr><th>Jenis Tinggal</th><td><?= $siswa->jenis_tinggal ?: '-' ?></td></tr>

            <tr><th>Transportasi</th><td><?= $siswa->alat_transportasi ?: '-' ?></td></tr>

            <tr><th>Telepon</th><td><?= $siswa->telp ?: '-' ?></td></tr>

            <tr><th>No HP</th><td><?= $siswa->hp ?: '-' ?></td></tr>

            <tr><th>Email</th><td><?= $siswa->email ?: '-' ?></td></tr>

        </table>

    </div>

</div>

        </div>

    </div>


    <!-- ===================================== -->
    <!-- ORANG TUA -->
    <!-- ===================================== -->

    <div class="tab-pane fade"
         id="ortu"
         role="tabpanel">

        <div class="card shadow mb-4">

            <div class="card-header bg-success text-white">

                <i class="fas fa-users mr-2"></i>

                Data Orang Tua

            </div>

            <div class="row">

<div class="col-lg-4">

<div class="card border-left-primary shadow-sm">

<div class="card-header bg-primary text-white">

Ayah Kandung

</div>

<div class="card-body">

<table class="table table-sm table-borderless">

<tr><th>Nama</th><td><?= $siswa->nama_ayah ?: '-' ?></td></tr>

<tr><th>NIK</th><td><?= $siswa->nik_ayah ?: '-' ?></td></tr>

<tr><th>Tahun Lahir</th><td><?= $siswa->tahun_lahir_ayah ?: '-' ?></td></tr>

<tr><th>Pekerjaan</th><td><?= $siswa->pekerjaan_ayah ?: '-' ?></td></tr>

<tr><th>Penghasilan</th><td><?= $siswa->penghasilan_ayah ?: '-' ?></td></tr>

</table>

</div>

</div>

</div>


<div class="col-lg-4">

<div class="card border-left-success shadow-sm">

<div class="card-header bg-success text-white">

Ibu Kandung

</div>

<div class="card-body">

<table class="table table-sm table-borderless">

<tr><th>Nama</th><td><?= $siswa->nama_ibu ?: '-' ?></td></tr>

<tr><th>NIK</th><td><?= $siswa->nik_ibu ?: '-' ?></td></tr>

<tr><th>Tahun Lahir</th><td><?= $siswa->tahun_lahir_ibu ?: '-' ?></td></tr>

<tr><th>Pendidikan</th><td><?= $siswa->pendidikan_ibu ?: '-' ?></td></tr>

<tr><th>Pekerjaan</th><td><?= $siswa->pekerjaan_ibu ?: '-' ?></td></tr>

<tr><th>Penghasilan</th><td><?= $siswa->penghasilan_ibu ?: '-' ?></td></tr>

</table>

</div>

</div>

</div>


<div class="col-lg-4">

<div class="card border-left-warning shadow-sm">

<div class="card-header bg-warning text-white">

Wali

</div>

<div class="card-body">

<table class="table table-sm table-borderless">

<tr><th>Nama</th><td><?= $siswa->nama_wali ?: '-' ?></td></tr>

<tr><th>NIK</th><td><?= $siswa->nik_wali ?: '-' ?></td></tr>

<tr><th>Tahun Lahir</th><td><?= $siswa->tahun_lahir_wali ?: '-' ?></td></tr>

<tr><th>Pendidikan</th><td><?= $siswa->pendidikan_wali ?: '-' ?></td></tr>

<tr><th>Pekerjaan</th><td><?= $siswa->pekerjaan_wali ?: '-' ?></td></tr>

<tr><th>Penghasilan</th><td><?= $siswa->penghasilan_wali ?: '-' ?></td></tr>

</table>

</div>

</div>

</div>

</div>

        </div>

    </div>


    <!-- ===================================== -->
    <!-- PENDIDIKAN -->
    <!-- ===================================== -->

    <div class="tab-pane fade"
         id="pendidikan"
         role="tabpanel">

        <div class="card shadow mb-4">

            <div class="card-header bg-info text-white">

                <i class="fas fa-school mr-2"></i>

                Data Pendidikan

            </div>

            <table class="table table-bordered">

<tr>

<th width="250">Sekolah Asal</th>

<td><?= $siswa->sekolah_asal ?: '-' ?></td>

</tr>

<tr>

<th>Nomor Ijazah</th>

<td><?= $siswa->skhun ?: '-' ?></td>

</tr>

<tr>

<th>Kelas Saat Ini</th>

<td><?= $siswa->nama_kelas ?: '-' ?></td>

</tr>

<tr>

<th>Tahun Ajaran</th>

<td><?= $siswa->tahun_ajaran ?: '-' ?></td>

</tr>

<tr>

<th>Status</th>

<td>

<span class="badge badge-success">

<?= strtoupper($siswa->status) ?>

</span>

</td>

</tr>

</table>

        </div>

    </div>


    <!-- ===================================== -->
    <!-- PERIODIK -->
    <!-- ===================================== -->

    <div class="tab-pane fade"
         id="periodik"
         role="tabpanel">

        <div class="card shadow mb-4">

            <div class="card-header bg-warning text-white">

                <i class="fas fa-chart-line mr-2"></i>

                Data Periodik

            </div>

            <table class="table table-bordered">

<tr>

<th width="250">

Tinggi Badan

</th>

<td>

<?= $siswa->tinggi_badan ?: '-' ?> cm

</td>

</tr>

<tr>

<th>

Berat Badan

</th>

<td>

<?= $siswa->berat_badan ?: '-' ?> Kg

</td>

</tr>

<tr>

<th>

Hobi

</th>

<td>

<?= $siswa->hobi ?: '-' ?>

</td>

</tr>

<tr>

<th>

Cita-cita

</th>

<td>

<?= $siswa->cita_cita ?: '-' ?>

</td>

</tr>

<tr>

<th>

Penerima KPS

</th>

<td>

<?= $siswa->penerima_kps ?: '-' ?>

</td>

</tr>

<tr>

<th>

Nomor KPS

</th>

<td>

<?= $siswa->no_kps ?: '-' ?>

</td>

</tr>

</table>

        </div>

    </div>

<div class="tab-pane fade"
     id="rapor"
     role="tabpanel">

    <div class="card shadow mb-4">

        <div class="card-header bg-primary text-white">

            <i class="fas fa-book-open mr-2"></i>

            Riwayat Nilai Rapor

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

<tr>

<th width="50">No</th>

<th>Mata Pelajaran</th>

<th class="text-center">SMT 1</th>

<th class="text-center">SMT 2</th>

<th class="text-center">SMT 3</th>

<th class="text-center">SMT 4</th>

<th class="text-center">SMT 5</th>

</tr>

</thead>

                    <tbody>

<?php if(empty($rapor)){ ?>

<tr>

<td colspan="7" class="text-center">

Belum ada data nilai rapor.

</td>

</tr>

<?php }else{ ?>

<?php
$no=1;
foreach($rapor as $r){
?>

<tr>

<td><?= $no++ ?></td>

<td><?= $r->nama_mapel ?></td>

<td class="text-center"><?= $r->smt1 ?: '-' ?></td>

<td class="text-center"><?= $r->smt2 ?: '-' ?></td>

<td class="text-center"><?= $r->smt3 ?: '-' ?></td>

<td class="text-center"><?= $r->smt4 ?: '-' ?></td>

<td class="text-center"><?= $r->smt5 ?: '-' ?></td>

</tr>

<?php } ?>

<?php } ?>

</tbody>

                </table>

            </div>

        </div>

    </div>

</div>
<div class="tab-pane fade"
     id="kehadiran"
     role="tabpanel">

    <div class="card shadow mb-4">

        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">

            <div>

                <i class="fas fa-calendar-check mr-2"></i>

                Rekap Kehadiran Peserta Didik

            </div>

            <small>
                Berdasarkan Tahun Ajaran & Semester
            </small>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover table-striped">

                    <thead class="thead-light">

                        <tr class="text-center">

                            <th width="50">No</th>

                            <th>Tahun Ajaran</th>

                            <th width="120">Semester</th>

                            <th width="80">Hadir</th>

                            <th width="80">Sakit</th>

                            <th width="80">Izin</th>

                            <th width="80">Alpha</th>

                            <th width="90">Terlambat</th>

                            <th width="90">Detail</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php if (!empty($kehadiran)) : ?>

                        <?php $no = 1; ?>

                        <?php foreach ($kehadiran as $k) : ?>

                        <tr>

                            <td class="text-center align-middle">
                                <?= $no++ ?>
                            </td>

                            <td class="align-middle">
                                <strong><?= $k->tahun_ajaran ?></strong>
                            </td>

                            <td class="text-center align-middle">

                                <span class="badge badge-pill badge-primary px-3 py-2">

                                    Semester <?= $k->semester ?>

                                </span>

                            </td>

                            <td class="text-center align-middle">

                                <span class="badge badge-success">

                                    <?= (int)$k->hadir ?>

                                </span>

                            </td>

                            <td class="text-center align-middle">

                                <span class="badge badge-info">

                                    <?= (int)$k->sakit ?>

                                </span>

                            </td>

                            <td class="text-center align-middle">

                                <span class="badge badge-warning">

                                    <?= (int)$k->izin ?>

                                </span>

                            </td>

                            <td class="text-center align-middle">

                                <span class="badge badge-danger">

                                    <?= (int)$k->alpha ?>

                                </span>

                            </td>

                            <td class="text-center align-middle">

                                <?php if($k->terlambat > 0){ ?>

                                    <span class="badge badge-warning">

                                        <?= $k->terlambat ?>

                                    </span>

                                <?php }else{ ?>

                                    <span class="text-muted">

                                        -

                                    </span>

                                <?php } ?>

                            </td>

                            <td class="text-center align-middle">

                                <a href="#"
                                   class="btn btn-outline-primary btn-sm">

                                    <i class="fas fa-eye"></i>

                                </a>

                            </td>

                        </tr>

                        <?php endforeach; ?>

                    <?php else : ?>

                        <tr>

                            <td colspan="9"
                                class="text-center text-muted py-5">

                                <i class="fas fa-calendar-times fa-3x mb-3"></i>

                                <br>

                                Belum ada data kehadiran.

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
    <!-- ===================================== -->
    <!-- DOKUMEN -->
    <!-- ===================================== -->

    <div class="tab-pane fade"
         id="dokumen"
         role="tabpanel">

        <div class="card shadow mb-4">

            <div class="card-header bg-secondary text-white">

                <i class="fas fa-folder-open mr-2"></i>

                Dokumen

            </div>

            <div class="card-body text-center text-muted py-5">

                <i class="fas fa-folder-open fa-3x mb-3"></i>

                <br>

                Belum ada dokumen yang diunggah.

            </div>

        </div>

    </div>


    <!-- ===================================== -->
    <!-- MUTASI -->
    <!-- ===================================== -->

    <div class="tab-pane fade"
         id="mutasi"
         role="tabpanel">

        <div class="card shadow mb-4">

            <div class="card-header bg-danger text-white">

                <i class="fas fa-random mr-2"></i>

                Riwayat Mutasi

            </div>

            <div class="card-body text-center text-muted py-5">

                <i class="fas fa-exchange-alt fa-3x mb-3"></i>

                <br>

                Belum ada riwayat mutasi.

            </div>

        </div>

    </div>


    <!-- ===================================== -->
    <!-- TIMELINE -->
    <!-- ===================================== -->

    <div class="tab-pane fade"
         id="timeline"
         role="tabpanel">

        <div class="card shadow">

            <div class="card-header bg-dark text-white">

                <i class="fas fa-history mr-2"></i>

                Timeline Pendidikan

            </div>

            <div class="card-body text-center text-muted py-5">

                <i class="fas fa-stream fa-3x mb-3"></i>

                <br>

                Timeline akan dibuat otomatis dari data riwayat kelas dan mutasi.

            </div>

        </div>

    </div>

</div>