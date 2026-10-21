<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card shadow border-0 rounded-4">

                <div class="card-header bg-primary text-white text-center p-4">
                    <h3 class="mb-1">
                        <i class="fas fa-id-card"></i>
                        Cari NISN
                    </h3>
                    <small>
                        Isi data sesuai akta kelahiran
                    </small>
                </div>

                <div class="card-body p-4">

                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= $this->session->flashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST"
      action="<?= site_url('cari_nisn/proses') ?>">

    <input type="hidden"
           name="<?= $this->security->get_csrf_token_name(); ?>"
           value="<?= $this->security->get_csrf_hash(); ?>">

    <div class="mb-3">
        <label class="form-label">
            Nama Lengkap
        </label>
        <input type="text"
               name="nama"
               class="form-control"
               placeholder="Contoh: Muhamad Nazmudin"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">
            Tempat Lahir
        </label>
        <input type="text"
               name="tempat_lahir"
               class="form-control"
               placeholder="Contoh: Kuningan"
               required>
    </div>

    <div class="mb-3">
    <label class="form-label">
        Tanggal Lahir
    </label>

    <input type="text"
           name="tgl_lahir"
           id="tgl_lahir"
           class="form-control"
           placeholder="DD-MM-YYYY"
           maxlength="10"
           autocomplete="off"
           inputmode="numeric"
           required>

    <small class="text-muted">
        Contoh: 15-09-2008
    </small>
</div>

    <button type="submit"
            class="btn btn-primary w-100 rounded-3">
        <i class="fas fa-search"></i>
        Cari NISN
    </button>

</form>

                    <?php
                    $hasil = $this->session->flashdata('hasil');
                    if ($hasil):
                    ?>

                    <div class="alert alert-success mt-4">
                        <h5 class="mb-3">
                            ✅ Data Ditemukan
                        </h5>

                        <table class="table table-borderless mb-0">
                            <tr>
                                <td width="130">Nama</td>
                                <td>: <?= $hasil['nama'] ?></td>
                            </tr>
                            <tr>
                                <td>Kelas</td>
                                <td>: <?= $hasil['kelas'] ?></td>
                            </tr>
                            <tr>
                                <td>Lahir</td>
                                <td>:
                                    <?= $hasil['tempat_lahir'] ?>,
                                    <?= date('d-m-Y', strtotime($hasil['tgl_lahir'])) ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>NISN</strong></td>
                                <td>
                                    : <strong class="text-primary fs-5">
                                        <?= $hasil['nisn'] ?>
                                    </strong>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>

</div>
<script>
const tglInput = document.getElementById('tgl_lahir');

tglInput.addEventListener('input', function(e) {

    // ambil angka saja
    let angka = this.value.replace(/\D/g, '');

    // maksimal 8 digit
    angka = angka.substring(0, 8);

    let hasil = '';

    // DD
    if (angka.length > 0) {
        hasil += angka.substring(0, 2);
    }

    // tetap munculkan "-"
    if (angka.length >= 2) {
        hasil += '-';
    }

    // MM
    if (angka.length > 2) {
        hasil += angka.substring(2, 4);
    }

    if (angka.length >= 4) {
        hasil += '-';
    }

    // YYYY
    if (angka.length > 4) {
        hasil += angka.substring(4, 8);
    }

    this.value = hasil;
});
</script>
</body>
</html>