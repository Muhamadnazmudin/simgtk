<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$foto = !empty($siswa->foto)
    ? base_url('uploads/foto/'.$siswa->foto)
    : base_url('assets/img/user.png');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?= $title ?></title>

<style>

body {
    margin:0;
    font-family: Arial, sans-serif;
    background:#f2f4f8;
}

.wrapper {
    display:flex;
    justify-content:center;
    padding:30px;
}

.idcard {
    width: 350px;      /* preview web */
    height: 560px;     /* rasio mendekati 86:54 */
    background:#ffffff;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
    position:relative;
}


/* HEADER */
.idcard-header {
    background:linear-gradient(135deg,#4e73df,#224abe);
    color:#fff;
    text-align:center;
    padding:20px 10px 50px 10px;
}

.idcard-header h4 {
    margin:0;
    font-size:16px;
    letter-spacing:.5px;
}

.idcard-header small {
    font-size:13px;
    opacity:.9;
}

/* FOTO */
.photo {
    text-align:center;
    margin-top:-40px;
}

.photo img {
    width:110px;
    height:130px;
    object-fit:cover;
    border-radius:15px;
    border:3px solid #fff;
    background:#fff;
}

/* NAMA */
.name {
    text-align:center;
    font-weight:700;
    font-size:18px;
    margin-top:15px;
    color:#2c3e50;
}

.role {
    text-align:center;
    font-size:13px;
    color:#6c757d;
    margin-top:3px;
}

/* GARIS */
.divider {
    margin:15px 25px;
    height:1px;
    background:#e0e0e0;
}

/* DETAIL TABLE */
.detail {
    padding:0 30px;
    font-size:14px;
}

.detail-row {
    display:flex;
    justify-content:space-between;
    margin-bottom:8px;
}

.detail-label {
    color:#6c757d;
}

.detail-value {
    font-weight:600;
    color:#2c3e50;
}

/* QR SECTION */
.qr-section {
    position:absolute;
    bottom:0;
    left:0;
    right:0;
    background:#f1f3f8;
    text-align:center;
    padding:15px 0;
}

.qr-section img {
    width:90px;
}

.qr-section small {
    display:block;
    margin-top:5px;
    font-size:12px;
    color:#6c757d;
}

/* PRINT REAL SIZE */
@media print {

    @page {
        size: 54mm 86mm;
        margin: 0;
    }

    body {
        margin: 0;
        background: none;
    }

    .wrapper {
        padding: 0;
    }

    .idcard {
        width: 54mm;
        height: 86mm;
        border-radius: 3mm; /* sudut kartu asli */
        box-shadow: none;
        border: none;
    }
}


</style>
</head>

<body>

<div class="wrapper">

    <div class="idcard">

        <div class="idcard-header">
            <h4>PERPUSTAKAAN DIGITAL SEKOLAH</h4>
            <small>ID CARD SISWA</small>
        </div>

        <div class="photo">
            <img src="<?= $foto ?>">
        </div>

        <div class="name">
            <?= htmlspecialchars($siswa->nama ?? '') ?>
        </div>

        <div class="role">
            SISWA
        </div>

        <div class="divider"></div>

        <div class="detail">

            <div class="detail-row">
                <div class="detail-label">NIS</div>
                <div class="detail-value"><?= $siswa->nis ?? '' ?></div>
            </div>
            <div class="detail-row">
    <div class="detail-label">NISN</div>
    <div class="detail-value"><?= $siswa->nisn ?? '' ?></div>
</div>

            <div class="detail-row">
                <div class="detail-label">Kelas</div>
                <div class="detail-value"><?= $siswa->nama_kelas ?? '' ?></div>
            </div>

            <?php if (!empty($siswa->nama_jurusan)): ?>
            <div class="detail-row">
                <div class="detail-label">Jurusan</div>
                <div class="detail-value"><?= $siswa->nama_jurusan ?></div>
            </div>
            <?php endif; ?>

        </div>

        <?php if (!empty($qr_path)): ?>
        <div class="qr-section">
            <img src="<?= base_url($qr_path) ?>">
            <small>Scan saat kunjungan</small>
        </div>
        <?php endif; ?>

    </div>

</div>

</body>
</html>
