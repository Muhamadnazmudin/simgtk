<?php
$role = $this->session->userdata('role_name');

$isOperator = ($role == 'operator');
$isBPBK     = ($role == 'bpbk');

// Role yang hanya boleh melihat data
$isViewOnly = in_array($role, ['operator', 'bpbk']);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Data Siswa</h4>

  <div>

    <a href="<?= site_url('siswa/download_template') ?>"
       class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-download"></i> Download Template
    </a>

    <a href="<?= site_url('siswa/cetak_semua?kelas=' . $kelas_id . '&search=' . $search) ?>"
       class="btn btn-dark btn-sm">
      <i class="fas fa-file-archive"></i> Cetak Semua ID Card (ZIP)
    </a>

    <a href="<?= site_url('siswa/export_excel') ?>"
       class="btn btn-success btn-sm">
      <i class="fas fa-file-excel"></i> Export
    </a>

    <?php if (!$isViewOnly): ?>

      <!-- Import Excel -->
      <button class="btn btn-primary btn-sm"
              data-toggle="modal"
              data-target="#importModal">
        <i class="fas fa-upload"></i> Import
      </button>

      <!-- Import Foto ZIP -->
      <button class="btn btn-warning btn-sm"
              data-toggle="modal"
              data-target="#importFotoModal">
        <i class="fas fa-images"></i> Foto ZIP
      </button>

      <!-- Tambah Siswa -->
      <button class="btn btn-info btn-sm"
              data-toggle="modal"
              data-target="#addModal">
        <i class="fas fa-plus"></i> Tambah
      </button>

    <?php endif; ?>

  </div>
</div>


<!-- =========================================================
     ALERT SUKSES / ERROR
========================================================= -->

<?php if ($this->session->flashdata('success')): ?>

  <div class="alert alert-success alert-dismissible fade show"
       role="alert">

    <i class="fas fa-check-circle"></i>
    <?= $this->session->flashdata('success'); ?>

    <button type="button"
            class="close"
            data-dismiss="alert">
      <span>&times;</span>
    </button>

  </div>

<?php endif; ?>


<?php if ($this->session->flashdata('error')): ?>

  <div class="alert alert-danger alert-dismissible fade show"
       role="alert">

    <i class="fas fa-exclamation-triangle"></i>
    <?= $this->session->flashdata('error'); ?>

    <button type="button"
            class="close"
            data-dismiss="alert">
      <span>&times;</span>
    </button>

  </div>

<?php endif; ?>


<!-- =========================================================
     FILTER & PENCARIAN
========================================================= -->

<form method="get"
      class="row mb-3">

  <div class="col-md-3 col-sm-6 mb-2">

    <select name="kelas"
            class="form-control form-control-sm">

      <option value="">Semua Kelas</option>

      <?php foreach ($kelas as $k): ?>

        <option value="<?= $k->id ?>"
          <?= ($kelas_id == $k->id ? 'selected' : '') ?>>
          <?= $k->nama ?>
        </option>

      <?php endforeach; ?>

    </select>

  </div>


  <div class="col-md-3 col-sm-6 mb-2">

    <input type="text"
           name="search"
           value="<?= $search ?>"
           class="form-control form-control-sm"
           placeholder="Cari nama / NIS / NISN">

  </div>


  <div class="col-md-2 col-sm-6 mb-2">

    <select name="limit"
            class="form-control form-control-sm">

      <option value="10"
        <?= ($limit == 10 ? 'selected' : '') ?>>
        10
      </option>

      <option value="20"
        <?= ($limit == 20 ? 'selected' : '') ?>>
        20
      </option>

      <option value="50"
        <?= ($limit == 50 ? 'selected' : '') ?>>
        50
      </option>

      <option value="100"
        <?= ($limit == 100 ? 'selected' : '') ?>>
        100
      </option>

    </select>

  </div>


  <div class="col-md-2 col-sm-6 mb-2">

    <button class="btn btn-primary btn-sm w-100"
            type="submit">

      <i class="fas fa-filter"></i>
      Terapkan

    </button>

  </div>


  <div class="col-md-2 col-sm-6 mb-2">

    <a href="<?= site_url('siswa') ?>"
       class="btn btn-secondary btn-sm w-100">

      <i class="fas fa-sync-alt"></i>
      Reset

    </a>

  </div>

</form>


<!-- =========================================================
     TABEL DATA SISWA
========================================================= -->

<table class="table table-bordered table-striped table-responsive-sm">

  <thead class="thead-light">

    <tr>

      <th>No</th>
      <th>NIS</th>
      <th>NISN</th>
      <th>Nama</th>
      <th>Tempat Lahir</th>
      <th>JK</th>
      <th>Agama</th>
      <th>Tanggal Lahir</th>
      <th>Kelas</th>
      <th>Tahun Ajaran</th>
      <th>Nomor Ijazah</th>
      <th>Status</th>
      <th width="120">Aksi</th>

    </tr>

  </thead>


  <tbody>

    <?php $no = $start + 1; ?>

    <?php foreach ($siswa as $s): ?>

      <tr>

        <td><?= $no++ ?></td>

        <td><?= $s->nis ?></td>

        <td><?= $s->nisn ?></td>

        <td><?= $s->nama ?></td>

        <td><?= $s->tempat_lahir ?></td>

        <td><?= $s->jk ?></td>

        <td><?= $s->agama ?></td>

        <td><?= $s->tgl_lahir ?></td>

        <td><?= $s->nama_kelas ?></td>

        <td><?= $s->tahun_ajaran ?></td>

        <td><?= $s->skhun ?></td>


        <!-- STATUS -->

        <td>

          <?php if ($s->status == 'aktif'): ?>

            <span class="badge badge-success">
              Aktif
            </span>

          <?php elseif ($s->status == 'mutasi_keluar'): ?>

            <span class="badge badge-danger">
              Mutasi Keluar
            </span>

          <?php elseif ($s->status == 'mutasi_masuk'): ?>

            <span class="badge badge-info">
              Mutasi Masuk
            </span>

          <?php elseif ($s->status == 'lulus'): ?>

            <span class="badge badge-primary">
              Lulus
            </span>

          <?php else: ?>

            <span class="badge badge-secondary">
              Keluar
            </span>

          <?php endif; ?>

        </td>


        <!-- AKSI -->

        <td class="text-center">

          <?php if (!$isViewOnly): ?>

            <a href="<?= site_url('siswa/edit/' . $s->id) ?>"
               class="btn btn-warning btn-sm"
               title="Edit">

              <i class="fas fa-edit"></i>

            </a>

          <?php endif; ?>


          <a href="<?= site_url('siswa/cetak/' . $s->id) ?>"
             class="btn btn-info btn-sm"
             target="_blank"
             title="Cetak Formulir">

            <i class="fas fa-print"></i>

          </a>


          <a href="<?= site_url('idcard/cetak/' . $s->id) ?>"
             class="btn btn-primary btn-sm"
             title="Download ID Card JPG">

            <i class="fas fa-id-card"></i>

          </a>


          <!-- CETAK SURAT PERNYATAAN -->
<?php if ((int) $s->status_verval === 1): ?>

  <a href="<?= site_url('siswa/surat_pernyataan/' . $s->id) ?>"
     class="btn btn-info btn-sm"
     target="_blank"
     title="Cetak Surat Pernyataan">

    <i class="fas fa-envelope"></i>

  </a>

<?php endif; ?>

        </td>

      </tr>

    <?php endforeach; ?>

  </tbody>

</table>


<!-- =========================================================
     PAGINATION
========================================================= -->

<div class="d-flex justify-content-between align-items-center flex-wrap mt-3 px-2">
    <div class="text-muted small mb-2 mb-md-0">
        Menampilkan <strong><?= count($siswa); ?></strong> dari total <strong><?= number_format($total ?? count($siswa)); ?></strong> siswa aktif
    </div>
    <div class="pagination-container m-0">
        <?= $pagination; ?>
    </div>
</div>


<!-- =========================================================
     MODAL TAMBAH SISWA
========================================================= -->

<div class="modal fade"
     id="addModal"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

  <div class="modal-dialog modal-lg"
       role="document">

    <div class="modal-content">

      <form method="post"
            action="<?= site_url('siswa/add') ?>">

        <div class="modal-header">

          <h5 class="modal-title">
            Tambah Siswa
          </h5>

          <button type="button"
                  class="close"
                  data-dismiss="modal">

            <span>&times;</span>

          </button>

        </div>


        <div class="modal-body">

          <input type="hidden"
                 name="<?= $this->security->get_csrf_token_name(); ?>"
                 value="<?= $this->security->get_csrf_hash(); ?>">


          <div class="form-row">

            <div class="form-group col-md-6">

              <label>NIS</label>

              <input type="text"
                     name="nis"
                     class="form-control"
                     required>

            </div>


            <div class="form-group col-md-6">

              <label>NISN</label>

              <input type="text"
                     name="nisn"
                     class="form-control"
                     required>

            </div>

          </div>


          <div class="form-row">

            <div class="form-group col-md-6">

              <label>Nama</label>

              <input type="text"
                     name="nama"
                     class="form-control"
                     required>

            </div>


            <div class="form-group col-md-6">

              <label>Tempat Lahir</label>

              <input type="text"
                     name="tempat_lahir"
                     class="form-control">

            </div>

          </div>


          <div class="form-row">

            <div class="form-group col-md-6">

              <label>Jenis Kelamin</label>

              <select name="jk"
                      class="form-control">

                <option value="L">
                  Laki-laki
                </option>

                <option value="P">
                  Perempuan
                </option>

              </select>

            </div>


            <div class="form-group col-md-6">

              <label>Agama</label>

              <select name="agama"
                      class="form-control">

                <option value="">
                  -- Pilih Agama --
                </option>

                <option value="Islam">
                  Islam
                </option>

                <option value="Kristen">
                  Kristen
                </option>

                <option value="Katolik">
                  Katolik
                </option>

                <option value="Hindu">
                  Hindu
                </option>

                <option value="Budha">
                  Budha
                </option>

                <option value="Konghucu">
                  Konghucu
                </option>

              </select>

            </div>

          </div>


          <div class="form-group">

            <label>Tanggal Lahir</label>

            <input type="date"
                   name="tgl_lahir"
                   class="form-control">

          </div>


          <div class="form-group">

            <label>Alamat</label>

            <textarea name="alamat"
                      class="form-control"
                      rows="2"></textarea>

          </div>


          <div class="form-row">

            <div class="form-group col-md-6">

              <label>Kelas</label>

              <select name="id_kelas"
                      class="form-control">

                <option value="">
                  -- Pilih Kelas --
                </option>

                <?php foreach ($kelas as $k): ?>

                  <option value="<?= $k->id ?>">
                    <?= $k->nama ?>
                  </option>

                <?php endforeach; ?>

              </select>

            </div>


            <div class="form-group col-md-6">

              <label>Tahun Ajaran</label>

              <select name="tahun_id"
                      class="form-control"
                      required>

                <?php foreach ($tahun as $t): ?>

                  <option value="<?= $t->id ?>">
                    <?= $t->tahun ?>
                  </option>

                <?php endforeach; ?>

              </select>

            </div>

          </div>

        </div>


        <div class="modal-footer">

          <button type="submit"
                  class="btn btn-primary">

            Simpan

          </button>

          <button type="button"
                  class="btn btn-secondary"
                  data-dismiss="modal">

            Batal

          </button>

        </div>

      </form>

    </div>

  </div>

</div>


<!-- =========================================================
     MODAL IMPORT EXCEL
========================================================= -->

<div class="modal fade"
     id="importModal"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

  <div class="modal-dialog"
       role="document">

    <div class="modal-content">

      <form method="post"
            action="<?= site_url('siswa/import_excel') ?>"
            enctype="multipart/form-data">

        <div class="modal-header">

          <h5 class="modal-title">
            Import Data Siswa
          </h5>

          <button type="button"
                  class="close"
                  data-dismiss="modal">

            <span>&times;</span>

          </button>

        </div>


        <div class="modal-body">

          <input type="hidden"
                 name="<?= $this->security->get_csrf_token_name(); ?>"
                 value="<?= $this->security->get_csrf_hash(); ?>">


          <div class="form-group">

            <label>Pilih File Excel</label>

            <input type="file"
                   name="file"
                   accept=".xls,.xlsx"
                   class="form-control"
                   required>

          </div>

        </div>


        <div class="modal-footer">

          <button type="submit"
                  class="btn btn-success">

            Import

          </button>

          <button type="button"
                  class="btn btn-secondary"
                  data-dismiss="modal">

            Batal

          </button>

        </div>

      </form>

    </div>

  </div>

</div>


<!-- =========================================================
     MODAL IMPORT FOTO ZIP
========================================================= -->

<?php if (!$isViewOnly): ?>

<div class="modal fade"
     id="importFotoModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="importFotoModalLabel"
     aria-hidden="true">

  <div class="modal-dialog"
       role="document">

    <div class="modal-content">

      <form method="post"
            action="<?= site_url('siswa/import_foto_zip') ?>"
            enctype="multipart/form-data">


        <!-- HEADER -->

        <div class="modal-header">

          <h5 class="modal-title"
              id="importFotoModalLabel">

            <i class="fas fa-images mr-1"></i>
            Import Foto Siswa

          </h5>


          <button type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-label="Close">

            <span aria-hidden="true">
              &times;
            </span>

          </button>

        </div>


        <!-- BODY -->

        <div class="modal-body">

          <input type="hidden"
                 name="<?= $this->security->get_csrf_token_name(); ?>"
                 value="<?= $this->security->get_csrf_hash(); ?>">


          <div class="form-group">

            <label class="font-weight-bold">
              Pilih File ZIP
            </label>

            <input type="file"
                   name="file"
                   accept=".zip"
                   class="form-control"
                   required>

            <small class="form-text text-muted">
              Upload satu file ZIP yang berisi foto siswa.
            </small>

          </div>


          <div class="alert alert-info mb-0">

            <div class="font-weight-bold mb-1">

              <i class="fas fa-info-circle mr-1"></i>
              Format Nama File

            </div>


            <div class="small">

              Nama file foto harus menggunakan
              <strong>NISN siswa</strong>.

            </div>


            <div class="mt-2">

              <code>00012292.jpg</code><br>
              <code>00012293.jpg</code><br>
              <code>00012294.png</code>

            </div>


            <div class="small mt-2">

              Sistem akan mencocokkan nama file
              dengan NISN yang ada di database siswa.

            </div>

          </div>

        </div>


        <!-- FOOTER -->

        <div class="modal-footer">

          <button type="submit"
                  class="btn btn-warning"
                  onclick="return confirm('Import foto dari ZIP sekarang? Foto siswa yang sudah ada dapat diganti jika NISN ditemukan.');">

            <i class="fas fa-upload mr-1"></i>
            Import Foto

          </button>


          <button type="button"
                  class="btn btn-secondary"
                  data-dismiss="modal">

            Batal

          </button>

        </div>


      </form>

    </div>

  </div>

</div>

<?php endif; ?>


<!-- =========================================================
     STYLE
========================================================= -->

<style>

/* =========================================================
   MODAL TAMBAH SISWA
========================================================= */

#addModal label,
#addModal .form-control,
#addModal select,
#addModal textarea {

  color: #212529 !important;
  background-color: #ffffff !important;
  border-color: #ced4da !important;
  font-weight: 500;

}


/* Placeholder */

#addModal input::placeholder,
#addModal textarea::placeholder {

  color: #6c757d !important;
  opacity: 1;

}


/* Header */

#addModal .modal-header {

  background-color: #f8f9fa;
  border-bottom: 1px solid #dee2e6;

}


#addModal .modal-title {

  color: #212529 !important;
  font-weight: 700;

}


/* Tombol Primary */

#addModal .modal-footer .btn-primary {

  background-color: #3b82f6;
  border-color: #3b82f6;
  font-weight: 600;

}


#addModal .modal-footer .btn-primary:hover {

  background-color: #2563eb;
  border-color: #2563eb;

}


/* Tombol Secondary */

#addModal .modal-footer .btn-secondary {

  background-color: #6c757d;
  border-color: #6c757d;
  font-weight: 600;

}


/* =========================================================
   FILTER
========================================================= */

form.row.mb-3 select,
form.row.mb-3 input {

  border-radius: 6px;

}


/* =========================================================
   PAGINATION
========================================================= */

.pagination .page-item .page-link {

  color: #007bff;
  border: 1px solid #dee2e6;
  margin: 0 2px;
  border-radius: 6px;
  transition: all 0.2s ease-in-out;

}


.pagination .page-item.active .page-link {

  background-color: #007bff;
  border-color: #007bff;
  color: #fff;

}


.pagination .page-item .page-link:hover {

  background-color: #007bff;
  color: #fff;
  border-color: #007bff;

}


/* =========================================================
   DARK MODE PAGINATION
========================================================= */

body.dark-mode .pagination .page-item .page-link {

  background-color: #1f1f1f;
  color: #ccc;
  border-color: #333;

}


body.dark-mode .pagination .page-item.active .page-link {

  background-color: #0d6efd;
  color: #fff;

}

</style>