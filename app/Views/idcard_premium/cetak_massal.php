<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'libraries/phpqrcode/qrlib.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?= $title ?></title>

<style>

/* ===== A4 SETTING ===== */
@page {
    size: A4;
    margin: 10mm;
}

body {
    margin:0;
    font-family: Arial, sans-serif;
}

/* GRID A4 3x3 */
.sheet {
    display:grid;
    grid-template-columns: repeat(3, 54mm);
    grid-auto-rows: 86mm;
    gap:6mm;
    justify-content:center;
}

/* ===== KARTU SAMA PERSIS SEPERTI PREVIEW ===== */

.idcard {
    width:54mm;
    height:86mm;
    background:#ffffff;
    border-radius:3mm;
    overflow:hidden;
    border:1px solid #ddd;

    display:flex;
    flex-direction:column;
    justify-content:space-between;

    padding-bottom: 3mm; /* ruang aman bawah */
}



/* HEADER */
.idcard-header {
    background:linear-gradient(135deg,#4e73df,#224abe);
    color:#fff;
    text-align:center;
    padding:6mm 2mm 12mm 2mm;
    font-size:8pt;
}

/* FOTO */
.photo {
    text-align:center;
    margin-top:-8mm;
}

.photo img {
    width:22mm;
    height:26mm;
    object-fit:cover;
    border-radius:3mm;
    border:2px solid #fff;
    background:#fff;
}

/* NAMA */
.name {
    text-align:center;
    font-weight:700;
    font-size:9pt;
    margin-top:2mm;
    color:#2c3e50;
}

/* ROLE */
.role {
    text-align:center;
    font-size:7pt;
    color:#6c757d;
}

/* GARIS */
.divider {
    margin:3mm 5mm;
    height:1px;
    background:#e0e0e0;
}

/* DETAIL */
.detail {
    padding:0 5mm;
    font-size:7pt;
}

.detail-row {
    display:flex;
    justify-content:space-between;
    margin-bottom:1.5mm;
}

.detail-label {
    color:#6c757d;
}

.detail-value {
    font-weight:600;
    color:#2c3e50;
}

/* QR */
.qr-section {
    background:#f1f3f8;
    text-align:center;
    padding:2mm 0 4mm 0; /* bawah lebih longgar */
    margin-top:0.2mm;      /* naik sedikit */
}


.qr-section img {
    width:16mm;
}

.qr-section small {
    display:block;
    font-size:6pt;
    color:#6c757d;
}

</style>
</head>
<body>

<div class="sheet">

<?php foreach($siswa as $s): ?>

<?php
$qrFolder = FCPATH.'assets/qrcode/';
if (!is_dir($qrFolder)) mkdir($qrFolder, 0755, true);

$qrFile = $qrFolder.$s->nis.'.png';

if (!file_exists($qrFile)) {
    QRcode::png($s->nis, $qrFile, QR_ECLEVEL_H, 4, 2);
}

$foto = !empty($s->foto)
    ? base_url('uploads/foto/'.$s->foto)
    : base_url('assets/img/user.png');
?>

<div class="idcard">

    <div class="idcard-header">
        PERPUSTAKAAN DIGITAL SEKOLAH<br>
        ID CARD SISWA
    </div>

    <div class="photo">
        <img src="<?= $foto ?>">
    </div>

    <div class="name">
        <?= htmlspecialchars($s->nama) ?>
    </div>

    <div class="role">
        SISWA
    </div>

    <div class="divider"></div>

    <div class="detail">

    <div class="detail-row">
        <div class="detail-label">NIS</div>
        <div class="detail-value"><?= $s->nis ?? '-' ?></div>
    </div>

    <div class="detail-row">
        <div class="detail-label">NISN</div>
        <div class="detail-value"><?= $s->nisn ?? '-' ?></div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Kelas</div>
        <div class="detail-value"><?= $s->nama_kelas ?? '-' ?></div>
    </div>

</div>


    <div class="qr-section">
        <img src="<?= base_url('assets/qrcodes/'.$s->nisn.'.png') ?>">
        <small>Scan saat kunjungan</small>
    </div>

</div>

<?php endforeach; ?>

</div>

<script>
window.print();
</script>

</body>
</html>
