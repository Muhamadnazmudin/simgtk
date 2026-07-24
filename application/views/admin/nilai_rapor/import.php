<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 text-gray-800">
            <i class="fas fa-file-excel text-success"></i>
            Import Nilai Rapor
        </h1>

        <a href="<?= site_url('Nilairapor_admin/download_template_lebar') ?>"
            class="btn btn-success shadow-sm">
            <i class="fas fa-download"></i>
            Download Template
        </a>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-times-circle"></i>
            <?= $this->session->flashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i>
            <?= $this->session->flashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="row">

        <div class="col-lg-8">

            <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        Upload File Excel
                    </h6>
                </div>

                <div class="card-body">

                    <form method="post"
                        action="<?= site_url('Nilairapor_admin/import_proses_lebar') ?>"
                        enctype="multipart/form-data">

                        <input type="hidden"
                            name="<?= $this->security->get_csrf_token_name(); ?>"
                            value="<?= $this->security->get_csrf_hash(); ?>">

                        <div class="form-group">

                            <label>
                                <strong>File Excel</strong>
                            </label>

                            <input
                                type="file"
                                name="file"
                                class="form-control"
                                accept=".xlsx"
                                required>

                            <small class="text-muted">
                                Hanya mendukung file
                                <strong>.xlsx</strong>
                            </small>

                        </div>

                        <button class="btn btn-success">
                            <i class="fas fa-upload"></i>
                            Import Nilai
                        </button>

                    </form>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card border-left-info shadow">

                <div class="card-body">

                    <h6 class="font-weight-bold text-info mb-3">
                        <i class="fas fa-info-circle"></i>
                        Petunjuk Import
                    </h6>

                    <ol class="pl-3 mb-0">

                        <li>
                            Download template Excel terlebih dahulu.
                        </li>

                        <li>
                            Jangan mengubah struktur header template.
                        </li>

                        <li>
                            Kolom <strong>NISN</strong> digunakan sebagai acuan pencarian siswa.
                        </li>

                        <li>
                            Kolom <strong>Nama Siswa</strong> hanya sebagai referensi.
                        </li>

                        <li>
                            Nilai kosong akan dilewati dan tidak menghapus nilai yang sudah ada.
                        </li>

                        <li>
                            Jika nilai sudah ada, maka otomatis diperbarui.
                        </li>

                        <li>
                            Jika NISN tidak ditemukan, data siswa tersebut akan dilewati.
                        </li>

                    </ol>

                </div>

            </div>

        </div>

    </div>

</div>