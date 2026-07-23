<div class="d-sm-flex align-items-center justify-content-between mb-4">

    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-book mr-2 text-primary"></i>
        Buku Induk Siswa
    </h1>

</div>
<div class="row mb-4">

    <div class="col-lg-3 col-md-6">

        <div class="card border-left-primary shadow h-100 py-2">

            <div class="card-body">

                <div class="row no-gutters align-items-center">

                    <div class="col mr-2">

                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Siswa Aktif
                        </div>

                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?= number_format($total_rows) ?>
                        </div>

                    </div>

                    <div class="col-auto">

                        <i class="fas fa-user-graduate fa-2x text-gray-300"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card border-left-success shadow h-100 py-2">

            <div class="card-body">

                <div class="row no-gutters align-items-center">

                    <div class="col mr-2">

                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Kelas
                        </div>

                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?= count($kelas) ?>
                        </div>

                    </div>

                    <div class="col-auto">

                        <i class="fas fa-school fa-2x text-gray-300"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<div class="card shadow mb-4">

    <div class="card-header py-3">

        <h6 class="m-0 font-weight-bold text-primary">

            Filter Data

        </h6>

    </div>

    <div class="card-body">

        <form method="get">

            <div class="row">

               <?php if (!$this->session->userdata('is_walikelas')): ?>

<div class="col-md-3">

    <label>Kelas</label>

    <select
        name="kelas"
        class="form-control">

        <option value="">Semua Kelas</option>

        <?php foreach($kelas as $k): ?>

        <option
            value="<?= $k->id ?>"
            <?= ($filter['kelas']==$k->id)?'selected':'' ?>>

            <?= $k->nama ?>

        </option>

        <?php endforeach; ?>

    </select>

</div>

<?php endif; ?>

                <div class="<?= $this->session->userdata('is_walikelas') ? 'col-md-8' : 'col-md-5' ?>">

                    <label>Pencarian</label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Nama / NIS / NISN"
                        value="<?= $filter['search'] ?>">

                </div>

                <div class="col-md-2">

                    <label>Per Halaman</label>

                    <select
                        class="form-control"
                        name="limit">

                        <?php

                        foreach([10,20,50,100] as $v){

                        ?>

                        <option
                            value="<?= $v ?>"
                            <?= $limit==$v?'selected':'' ?>>

                            <?= $v ?>

                        </option>

                        <?php } ?>

                    </select>

                </div>

                <div class="col-md-2">

                    <label>&nbsp;</label>

                    <button
                        class="btn btn-primary btn-block">

                        <i class="fas fa-search"></i>

                        Cari

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
<div class="card shadow">

<div class="table-responsive">

<table class="table table-hover table-bordered mb-0">

<thead class="thead-light">

<tr>

<th width="60">No</th>

<th width="70">Foto</th>

<th>NIS</th>

<th>Nama</th>

<th>Kelas</th>

<th>Tahun</th>

<th width="130">Aksi</th>

</tr>

</thead>

<tbody>

<?php

if(empty($siswa)):

?>

<tr>

<td colspan="7" class="text-center py-5">

Tidak ada data.

</td>

</tr>

<?php

endif;

?>

<?php

$no=$offset+1;

foreach($siswa as $row):

?>

<tr>

<td class="text-center">

<?= $no++ ?>

</td>

<td class="text-center">

<?php

$foto = !empty($row->foto)
? base_url('uploads/foto/'.$row->foto)
: base_url('assets/img/default.png');

?>

<img
src="<?= $foto ?>"
width="55"
height="55"
style="border-radius:50%;object-fit:cover;">

</td>

<td>

<?= $row->nis ?>

</td>

<td>

<strong>

<?= $row->nama ?>

</strong>

<br>

<small class="text-muted">

NISN :
<?= $row->nisn ?>

</small>

</td>

<td>

<?= $row->nama_kelas ?>

</td>

<td>

<?= $row->tahun_ajaran ?>

</td>

<td class="text-center">

<a
href="<?= site_url('buku_induk/detail/'.$row->id) ?>"
class="btn btn-primary btn-sm">

<i class="fas fa-book-open"></i>

Detail

</a>

</td>

</tr>

<?php

endforeach;

?>

</tbody>

</table>

</div>
<div class="card-footer bg-white">

<div class="row align-items-center">

<div class="col-md-6">

<small class="text-muted">

Menampilkan

<strong>

<?= count($siswa)==0?0:$offset+1 ?>

</strong>

-

<strong>

<?= $offset+count($siswa) ?>

</strong>

dari

<strong>

<?= number_format($total_rows) ?>

</strong>

siswa

</small>

</div>

<div class="col-md-6 text-right">

<?= $pagination ?>

</div>

</div>

</div>

</div>
