<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('v')) {
    function v($value, $default='-')
    {
        return ($value === '' || $value === null) ? $default : htmlspecialchars($value);
    }
}

// ========================================
// Logo Sekolah
// ========================================

$logo = '';

if (!empty($sekolah->logo)) {

    $file = FCPATH . 'uploads/logo/' . $sekolah->logo;

    if (is_file($file)) {
        $logo = $file;
    }

}

// fallback
if ($logo == '') {

    $default = FCPATH . 'uploads/logo/logo.png';

    if (is_file($default)) {
        $logo = $default;
    }

}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Biodata Peserta Didik</title>
<style>
body{
    font-family: dejavusans, sans-serif;
    font-size: 9.5pt;
    color: #222222;
    line-height: 1.5;
    margin: 0;
    padding: 0;
}

table{
    border-collapse: collapse;
    width: 100%;
}

td{
    vertical-align: middle;
}

/*====================================================
KOP SURAT
====================================================*/
.kop td{
    padding: 4px;
}

.logo{
    width: 90px;
    text-align: center;
}

.logo img{
    width: 75px;
}

.nama-sekolah{
    text-align: center;
}

.nama-sekolah h1{
    font-size: 16pt;
    margin: 0;
    color: #0b3c5d;
    text-transform: uppercase;
    font-weight: bold;
}

.nama-sekolah h2{
    font-size: 11pt;
    margin: 3px 0;
    color: #328cc1;
}

.nama-sekolah p{
    font-size: 8.5pt;
    margin: 0;
    color: #555555;
}

.garis{
    border-top: 2.5px solid #0b3c5d;
    border-bottom: 1px solid #cbd5e0;
    height: 2px;
    margin-top: 10px;
    margin-bottom: 15px;
}

/*====================================================
JUDUL UTAMA
====================================================*/
.judul{
    background-color: #0b3c5d;
    color: #ffffff;
    text-align: center;
    font-size: 12pt;
    font-weight: bold;
    padding: 8px;
    margin-bottom: 15px;
    border-radius: 4px;
}

/*====================================================
IDENTITY BOX (RINGKASAN)
====================================================*/
.identity{
    border: 1px solid #b0bec5;
    margin-bottom: 15px;
    border-radius: 4px;
    overflow: hidden;
}

.identity-title{
    background-color: #1d3557;
    color: #ffffff;
    font-size: 10.5pt;
    font-weight: bold;
    padding: 7px 10px;
}

.identity td{
    border: 1px solid #cfd8dc;
    padding: 6px 8px;
}

.identity-label{
    background-color: #eef2f5;
    font-weight: bold;
    color: #000000;
    width: 18%;
}

.identity-value{
    width: 32%;
    color: #111111;
}

/*====================================================
SECTION DATA DETAIL (POIN A, B, C, D, E, F, G)
====================================================*/
.section{
    margin-top: 14px;
    margin-bottom: 16px;
    /* Mencegah section terpotong/menggantung di akhir halaman */
    page-break-inside: avoid;
    break-inside: avoid;
}

.section-header{
    background-color: #2b4c7e;
    color: #ffffff;
    font-size: 11pt;
    font-weight: bold;
    padding: 7px 10px;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    
}

.data{
    border: 1px solid #b0bec5;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
}

.data td{
    border: 1px solid #cfd8dc;
    padding: 5.5px 8px;
}

/* Efek zebra striping pada baris tabel */
.data tr:nth-child(even) {
    background-color: #f7f9fb;
}

.data tr:nth-child(odd) {
    background-color: #ffffff;
}

.label{
    width: 27%;
    background-color: #eef2f5;
    font-weight: bold;
    color: #000000;
}

.titik{
    width: 3%;
    text-align: center;
    color: #333333;
    background-color: #eef2f5;
}

.value{
    width: 70%;
    color: #111111;
}

.sub-section-title-ayah {
    background-color: #0d47a1;
    color: #ffffff;
    font-weight: bold;
    padding: 6px 10px;
    text-transform: uppercase;
    font-size: 9.5pt;
    
}

.sub-section-title-ibu {
    background-color: #c2185b;
    color: #ffffff;
    font-weight: bold;
    padding: 6px 10px;
    text-transform: uppercase;
    font-size: 9.5pt;
    
}

/*====================================================
TANDA TANGAN & FOOTER
====================================================*/
.ttd-table {
    margin-top: 25px;
    page-break-inside: avoid;
    break-inside: avoid;
}

.footer{
    margin-top: 20px;
    font-size: 7.5pt;
    color: #555555;
    border-top: 1px solid #cfd8dc;
    padding-top: 5px;
}
</style>
</head>
<body>

<!-- KOP SURAT -->
<table class="kop">

<tr>

<td class="logo" width="15%">

<?php if($logo != ''): ?>

<img src="<?= $logo ?>" width="80">

<?php endif; ?>

</td>

<td width="85%" class="nama-sekolah">

<h1>SMK NEGERI 1 CILIMUS</h1>

<h2>DATA LENGKAP PESERTA DIDIK</h2>

<p>
Jl. Raya Cilimus No.123 Kabupaten Kuningan, Jawa Barat
</p>

<p>
Telp. (0232) 123456 &nbsp; | &nbsp;
Email : info@smkn1cilimus.sch.id
</p>

</td>

</tr>

</table>

<div class="garis"></div>

<div class="judul">
BIODATA PESERTA DIDIK
</div>

<!-- RINGKASAN IDENTITAS -->
<table class="identity">
<tr>
<td colspan="4" class="identity-title">RINGKASAN IDENTITAS</td>
</tr>
<tr>
<td class="identity-label">Nama</td>
<td class="identity-value"><?= v($siswa->nama) ?></td>
<td class="identity-label">NIS</td>
<td class="identity-value"><?= v($siswa->nis) ?></td>
</tr>
<tr>
<td class="identity-label">NISN</td>
<td><?= v($siswa->nisn) ?></td>
<td class="identity-label">Kelas</td>
<td><?= v($siswa->nama_kelas) ?></td>
</tr>
<tr>
<td class="identity-label">Tahun Ajaran</td>
<td><?= v($siswa->tahun_ajaran) ?></td>
<td class="identity-label">Status</td>
<td><?= ucfirst(v($siswa->status)) ?></td>
</tr>
</table>

<!-- A. DATA PRIBADI -->
<div class="section">
<div class="section-header">A. DATA PRIBADI</div>
<table class="data">
<tr><td class="label">Nama Lengkap</td><td class="titik">:</td><td class="value"><?= v($siswa->nama) ?></td></tr>
<tr><td class="label">NIS</td><td class="titik">:</td><td class="value"><?= v($siswa->nis) ?></td></tr>
<tr><td class="label">NISN</td><td class="titik">:</td><td class="value"><?= v($siswa->nisn) ?></td></tr>
<tr><td class="label">Jenis Kelamin</td><td class="titik">:</td><td class="value"><?= v($siswa->jk) ?></td></tr>
<tr><td class="label">Tempat Lahir</td><td class="titik">:</td><td class="value"><?= v($siswa->tempat_lahir) ?></td></tr>
<tr><td class="label">Tanggal Lahir</td><td class="titik">:</td><td class="value"><?= v($siswa->tgl_lahir) ?></td></tr>
<tr><td class="label">Agama</td><td class="titik">:</td><td class="value"><?= v($siswa->agama) ?></td></tr>
<tr><td class="label">NIK</td><td class="titik">:</td><td class="value"><?= v($siswa->nik) ?></td></tr>
<tr><td class="label">Nomor KK</td><td class="titik">:</td><td class="value"><?= v($siswa->nomor_kk) ?></td></tr>
<tr><td class="label">Anak Ke</td><td class="titik">:</td><td class="value"><?= v($siswa->anak_keberapa) ?></td></tr>
</table>
</div>

<!-- B. DATA ALAMAT -->
<div class="section">
<div class="section-header">B. DATA ALAMAT</div>
<table class="data">
<tr><td class="label">Alamat</td><td class="titik">:</td><td class="value"><?= v($siswa->alamat) ?></td></tr>
<tr><td class="label">Dusun</td><td class="titik">:</td><td class="value"><?= v($siswa->dusun) ?></td></tr>
<tr><td class="label">RT / RW</td><td class="titik">:</td><td class="value"><?= v($siswa->rt) ?> / <?= v($siswa->rw) ?></td></tr>
<tr><td class="label">Kelurahan / Desa</td><td class="titik">:</td><td class="value"><?= v($siswa->alamat) ?></td></tr>
<tr><td class="label">Kecamatan</td><td class="titik">:</td><td class="value"><?= v($siswa->kecamatan) ?></td></tr>
<tr><td class="label">Kode Pos</td><td class="titik">:</td><td class="value"><?= v($siswa->kode_pos) ?></td></tr>
<tr><td class="label">Jenis Tinggal</td><td class="titik">:</td><td class="value"><?= v($siswa->jenis_tinggal) ?></td></tr>
<tr><td class="label">Transportasi</td><td class="titik">:</td><td class="value"><?= v($siswa->alat_transportasi) ?></td></tr>
<tr><td class="label">Telepon Rumah</td><td class="titik">:</td><td class="value"><?= v($siswa->telp) ?></td></tr>
<tr><td class="label">Nomor HP</td><td class="titik">:</td><td class="value"><?= v($siswa->hp) ?></td></tr>
<tr><td class="label">Email</td><td class="titik">:</td><td class="value"><?= v($siswa->email) ?></td></tr>
</table>
</div>

<!-- C. DATA KESEJAHTERAAN -->
<div class="section">
<div class="section-header">C. DATA KESEJAHTERAAN</div>
<table class="data">
<tr><td class="label">Desil</td><td class="titik">:</td><td class="value"><?= v($siswa->desil) ?></td></tr>
<tr><td class="label">Penerima KPS</td><td class="titik">:</td><td class="value"><?= v($siswa->penerima_kps) ?></td></tr>
<tr><td class="label">Nomor KPS</td><td class="titik">:</td><td class="value"><?= v($siswa->no_kps) ?></td></tr>
</table>
</div>

<!-- D. DATA PERIODIK DAN PENDIDIKAN -->
<div class="section">
<div class="section-header">D. DATA PERIODIK DAN PENDIDIKAN</div>
<table class="data">
<tr><td class="label">Tinggi Badan</td><td class="titik">:</td><td class="value"><?= v($siswa->tinggi_badan) ?> cm</td></tr>
<tr><td class="label">Berat Badan</td><td class="titik">:</td><td class="value"><?= v($siswa->berat_badan) ?> kg</td></tr>
<tr><td class="label">Hobi</td><td class="titik">:</td><td class="value"><?= v($siswa->hobi) ?></td></tr>
<tr><td class="label">Cita-cita</td><td class="titik">:</td><td class="value"><?= v($siswa->cita_cita) ?></td></tr>
<tr><td class="label">Sekolah Asal</td><td class="titik">:</td><td class="value"><?= v($siswa->sekolah_asal) ?></td></tr>
<tr><td class="label">Nomor Ijazah</td><td class="titik">:</td><td class="value"><?= v($siswa->skhun) ?></td></tr>
</table>
</div>

<!-- E. DATA ORANG TUA -->
<div class="section">
<div class="section-header">E. DATA ORANG TUA</div>
<table class="data">
<tr><td colspan="3" class="sub-section-title-ayah">DATA AYAH</td></tr>
<tr><td class="label">Nama Ayah</td><td class="titik">:</td><td class="value"><?= v($siswa->nama_ayah) ?></td></tr>
<tr><td class="label">NIK Ayah</td><td class="titik">:</td><td class="value"><?= v($siswa->nik_ayah) ?></td></tr>
<tr><td class="label">Tahun Lahir Ayah</td><td class="titik">:</td><td class="value"><?= v($siswa->tahun_lahir_ayah) ?></td></tr>
<tr><td class="label">Pendidikan Ayah</td><td class="titik">:</td><td class="value"><?= v($siswa->pendidikan_ayah) ?></td></tr>
<tr><td class="label">Pekerjaan Ayah</td><td class="titik">:</td><td class="value"><?= v($siswa->pekerjaan_ayah) ?></td></tr>
<tr><td class="label">Penghasilan Ayah</td><td class="titik">:</td><td class="value"><?= v($siswa->penghasilan_ayah) ?></td></tr>

<tr><td colspan="3" class="sub-section-title-ibu">DATA IBU</td></tr>
<tr><td class="label">Nama Ibu</td><td class="titik">:</td><td class="value"><?= v($siswa->nama_ibu) ?></td></tr>
<tr><td class="label">NIK Ibu</td><td class="titik">:</td><td class="value"><?= v($siswa->nik_ibu) ?></td></tr>
<tr><td class="label">Tahun Lahir Ibu</td><td class="titik">:</td><td class="value"><?= v($siswa->tahun_lahir_ibu) ?></td></tr>
<tr><td class="label">Pendidikan Ibu</td><td class="titik">:</td><td class="value"><?= v($siswa->pendidikan_ibu) ?></td></tr>
<tr><td class="label">Pekerjaan Ibu</td><td class="titik">:</td><td class="value"><?= v($siswa->pekerjaan_ibu) ?></td></tr>
<tr><td class="label">Penghasilan Ibu</td><td class="titik">:</td><td class="value"><?= v($siswa->penghasilan_ibu) ?></td></tr>
</table>
</div>

<!-- F. DATA WALI -->
<div class="section">
<div class="section-header">F. DATA WALI</div>
<table class="data">
<tr><td class="label">Nama Wali</td><td class="titik">:</td><td class="value"><?= v($siswa->nama_wali) ?></td></tr>
<tr><td class="label">NIK Wali</td><td class="titik">:</td><td class="value"><?= v($siswa->nik_wali) ?></td></tr>
<tr><td class="label">Tahun Lahir Wali</td><td class="titik">:</td><td class="value"><?= v($siswa->tahun_lahir_wali) ?></td></tr>
<tr><td class="label">Pendidikan Wali</td><td class="titik">:</td><td class="value"><?= v($siswa->pendidikan_wali) ?></td></tr>
<tr><td class="label">Pekerjaan Wali</td><td class="titik">:</td><td class="value"><?= v($siswa->pekerjaan_wali) ?></td></tr>
<tr><td class="label">Penghasilan Wali</td><td class="titik">:</td><td class="value"><?= v($siswa->penghasilan_wali) ?></td></tr>
</table>
</div>

<!-- G. DATA AKADEMIK -->
<div class="section">
<div class="section-header">G. DATA AKADEMIK</div>
<table class="data">
<tr><td class="label">Kelas / Rombel</td><td class="titik">:</td><td class="value"><?= v($siswa->nama_kelas) ?></td></tr>
<tr><td class="label">Tahun Ajaran</td><td class="titik">:</td><td class="value"><?= v($siswa->tahun_ajaran) ?></td></tr>
<tr><td class="label">Status Peserta Didik</td><td class="titik">:</td><td class="value"><?= ucfirst(v($siswa->status)) ?></td></tr>
</table>
</div>

<!-- TANDA TANGAN -->
<table class="ttd-table" width="100%">
<tr>
<td width="50%" align="center">
Mengetahui, <br>
<b>Kepala Sekolah</b>
<br><br><br><br><br>
<u><b>_________________________</b></u>
</td>
<td width="50%" align="center">
Kuningan, <?= date('d F Y') ?> <br>
<b>Orang Tua / Wali</b>
<br><br><br><br><br>
<u><b>_________________________</b></u>
</td>
</tr>
</table>

<!-- FOOTER -->
<table class="footer">
<tr>
<td align="left" width="50%">Dicetak oleh Sistem Informasi Mutasi Peserta Didik</td>
<td align="right" width="50%">Tanggal Cetak : <?= date('d-m-Y H:i') ?></td>
</tr>
</table>

</body>
</html>