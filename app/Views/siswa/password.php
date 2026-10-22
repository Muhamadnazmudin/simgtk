<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ubah Password</h1>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-1"></i> <?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> <?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-key mr-1"></i> Form Ubah Password Siswa</h6>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= site_url('SiswaDashboard/save_password') ?>">
                        <?= csrf_field() ?>
                        <div class="form-group mb-3">
                            <label for="old" class="font-weight-bold">Password Lama (Default NISN):</label>
                            <input type="password" name="old" id="old" class="form-control" required placeholder="Masukkan password lama">
                        </div>
                        <div class="form-group mb-3">
                            <label for="new" class="font-weight-bold">Password Baru:</label>
                            <input type="password" name="new" id="new" class="form-control" minlength="4" required placeholder="Masukkan password baru">
                        </div>
                        <button type="submit" class="btn btn-primary shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Password Baru
                        </button>
                        <a href="<?= site_url('SiswaDashboard') ?>" class="btn btn-secondary shadow-sm ml-2">
                            Batal
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
