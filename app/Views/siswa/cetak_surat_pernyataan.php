<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
body {
    font-family: "Times New Roman", serif;
    font-size: 11pt;
    line-height: 1.4;
}

.container {
    width: 165mm;
    margin: 0 auto;
}

/* JUDUL */
.title {
    text-align: center;
    font-weight: bold;
    font-size: 13pt;
    margin-bottom: 16px;
}

/* PARAGRAF */
p {
    margin: 6px 0;
}

/* TABEL DATA */
.table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    margin-bottom: 14px;
}

.table td {
    padding: 3px 4px;
    vertical-align: top;
}

.label {
    width: 36%;
    white-space: nowrap;
}

.colon {
    width: 4%;
    text-align: center;
}

.value {
    width: 60%;
}

/* ISI SURAT */
.paragraph {
    text-align: justify;
    margin-top: 10px;
}

/* TANDA TANGAN */
.signature {
    margin-top: 30px;
}

.ttd-table {
    width: 100%;
    margin-top: 10px;
}

.ttd-table td {
    text-align: center;
    vertical-align: bottom;
}

.nama-ttd {
    padding-top: 40px;
}

@media print {
    .no-print {
        display: none !important;
    }
    body {
        margin: 0;
        padding: 0;
    }
}
</style>
<title>Surat Pernyataan - <?= esc($siswa->nama) ?></title>
</head>

<body>

<div class="no-print" style="margin: 15px auto; max-width: 165mm; text-align: center; background: #f8f9fa; padding: 12px; border-radius: 6px; border: 1px solid #dee2e6; box-shadow: 0 2px 4px rgba(0,0,0,0.06); font-family: sans-serif;">
    <button onclick="window.print()" style="cursor: pointer; background: #0d6efd; color: #fff; border: none; padding: 8px 20px; border-radius: 4px; font-weight: 600; font-size: 14px; margin-right: 10px;">
        🖨️ Cetak / Simpan PDF
    </button>
    <button onclick="window.close()" style="cursor: pointer; background: #6c757d; color: #fff; border: none; padding: 8px 16px; border-radius: 4px; font-size: 14px;">
        ✖ Tutup
    </button>
</div>

<div class="container">

<div class="title">
SURAT PERNYATAAN ORANG TUA / WALI ATAS KEBENARAN DATA SISWA KELAS 12
</div>

<p>Yang bertandatangan di bawah ini :</p>

<table class="table">
<tr>
    <td class="label">Nama</td><td class="colon">:</td><td class="value"></td>
</tr>
<tr>
    <td class="label">Tempat / Tanggal Lahir</td><td class="colon">:</td><td class="value"></td>
</tr>
<tr>
    <td class="label">Alamat</td><td class="colon">:</td><td class="value"></td>
</tr>
</table>

<p>Adalah orang tua dari :</p>

<table class="table">
<tr>
    <td class="label">NISN</td><td class="colon">:</td><td class="value"><?= $siswa->nisn ?></td>
</tr>
<tr>
    <td class="label">NIK</td><td class="colon">:</td><td class="value"><?= $siswa->nik ?></td>
</tr>
<tr>
    <td class="label">Nama Lengkap</td><td class="colon">:</td><td class="value"><?= $siswa->nama ?></td>
</tr>
<tr>
    <td class="label">Tempat / Tanggal Lahir</td>
    <td class="colon">:</td>
    <td class="value"><?= $siswa->tempat_lahir ?>, <?= tgl_indo_teks($siswa->tgl_lahir) ?></td>
</tr>
<tr>
    <td class="label">Jenis Kelamin</td>
    <td class="colon">:</td>
    <td class="value"><?= ($siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan') ?></td>
</tr>
<tr>
    <td class="label">Nama Ibu Kandung</td><td class="colon">:</td><td class="value"><?= $siswa->nama_ibu ?></td>
</tr>
<tr>
    <td class="label">Nama Ayah Kandung</td><td class="colon">:</td><td class="value"><?= $siswa->nama_ayah ?></td>
</tr>
</table>

<div class="paragraph">
Dengan ini saya menyatakan bahwa data putra/i kami yang tertera di atas adalah benar dan sesuai
dengan data kependudukan terbaru, data Ijazah SD/MI dan Ijazah SMP/MTs, sehingga kami menyetujui
data putra/i kami untuk diproses penerbitan e-Ijazah jenjang kelas 12 di
<strong>SMK Negeri 1 Cilimus</strong> Tahun <?= $siswa->tahun_ajaran ?>.
</div>

<div class="paragraph">
Jika di kemudian hari terdapat kendala atau permasalahan e-Ijazah kelas 12, maka hal tersebut
menjadi tanggung jawab kami sebagai orang tua dari putra/i kami, dengan catatan pihak sekolah
telah melakukan pembaruan data sesuai dengan regulasi Pemerintah.
</div>

<div class="paragraph">
Demikian surat pernyataan ini kami buat sebagai dasar sekolah melakukan proses validasi data siswa kelas 12.
</div>

<div class="signature">
<p style="text-align:right;">................................., ......................... 20......</p>

<table class="ttd-table">
<tr>
    <td width="50%">Siswa</td>
    <td width="50%">Orang Tua / Wali</td>
</tr>
<tr>
    <td class="nama-ttd"><br><br><br><br><br><br><?= $siswa->nama ?></td>
    <td class="nama-ttd">
        <div style="margin-bottom:8px;">Materai</div><br><br><br>
        ________________________
    </td>
</tr>
</table>
</div>

</div>

<script>
    window.addEventListener('DOMContentLoaded', function() {
        window.print();
    });
</script>

</body>
</html>

