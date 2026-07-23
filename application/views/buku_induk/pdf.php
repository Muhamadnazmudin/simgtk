<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

body{
    font-family: dejavusans;
    font-size:10px;
    color:#000;
}

table{
    width:100%;
    border-collapse:collapse;
}

td{
    vertical-align:top;
}

.judul{
    font-size:16px;
    font-weight:bold;
    text-align:center;
}

.subjudul{
    font-size:11px;
    text-align:center;
}

.kotak{
    border:1px solid #000;
}

.header{
    border-bottom:2px solid #000;
}

.label{
    width:38%;
    font-weight:bold;
}

.isi{
    width:62%;
}

</style>

</head>

<body>

<!-- ============================= -->
<!-- HEADER SEKOLAH -->
<!-- ============================= -->

<table cellpadding="2">

<tr>

<td width="15%" align="center">

<?php
$logo = FCPATH.'assets/img/logo.png';

if(file_exists($logo)){
?>
<img src="<?= $logo ?>" width="60">
<?php } ?>

</td>

<td width="70%" align="center">

<div style="font-size:14px;font-weight:bold;">
PEMERINTAH KABUPATEN KUNINGAN
</div>

<div style="font-size:15px;font-weight:bold;">
SMK NEGERI 1 CILIMUS
</div>

<div style="font-size:10px;">
Jl. Raya Cilimus No.123 Kabupaten Kuningan
</div>

<div style="font-size:10px;">
Telp. (0232) XXXXXXX
</div>

</td>

<td width="15%"></td>

</tr>

</table>

<hr>

<br>

<div class="judul">
BUKU INDUK PESERTA DIDIK
</div>

<br><br>

<!-- ============================= -->
<!-- FOTO + IDENTITAS -->
<!-- ============================= -->

<table cellpadding="4">

<tr>

<td width="75%">

<table border="1" cellpadding="5">

<tr bgcolor="#EEEEEE">
<td colspan="2">
<b>IDENTITAS PESERTA DIDIK</b>
</td>
</tr>

<tr>
<td class="label">Nama Lengkap</td>
<td class="isi"><?= $siswa->nama ?></td>
</tr>

<tr>
<td class="label">NIS</td>
<td><?= $siswa->nis ?></td>
</tr>

<tr>
<td class="label">NISN</td>
<td><?= $siswa->nisn ?></td>
</tr>

<tr>
<td class="label">Jenis Kelamin</td>
<td><?= $siswa->jk ?></td>
</tr>

<tr>
<td class="label">Tempat, Tanggal Lahir</td>
<td>

<?= $siswa->tempat_lahir ?>

<?php
if(!empty($siswa->tgl_lahir)){
    echo ", ".date('d-m-Y',strtotime($siswa->tgl_lahir));
}
?>

</td>
</tr>

<tr>
<td class="label">Agama</td>
<td><?= $siswa->agama ?></td>
</tr>

<tr>
<td class="label">Nomor KK</td>
<td><?= $siswa->nomor_kk ?></td>
</tr>

<tr>
<td class="label">NIK</td>
<td><?= $siswa->nik ?></td>
</tr>

<tr>
<td class="label">Anak Ke</td>
<td><?= $siswa->anak_keberapa ?></td>
</tr>

<tr>
<td class="label">Alamat</td>
<td>

<?= $siswa->alamat ?>

<?php
if(!empty($siswa->rt)){
    echo " RT ".$siswa->rt;
}

if(!empty($siswa->rw)){
    echo " RW ".$siswa->rw;
}
?>

</td>

</tr>

<tr>
<td class="label">Dusun</td>
<td><?= $siswa->dusun ?></td>
</tr>

<tr>
<td class="label">Kecamatan</td>
<td><?= $siswa->kecamatan ?></td>
</tr>

<tr>
<td class="label">Kode Pos</td>
<td><?= $siswa->kode_pos ?></td>
</tr>

<tr>
<td class="label">No. HP</td>
<td><?= $siswa->hp ?></td>
</tr>

<tr>
<td class="label">Email</td>
<td><?= $siswa->email ?></td>
</tr>

<tr>
<td class="label">Kelas Saat Ini</td>
<td><?= $siswa->nama_kelas ?></td>
</tr>

<tr>
<td class="label">Status</td>
<td><?= ucfirst($siswa->status) ?></td>
</tr>

</table>

</td>

<td width="25%" align="center">

<table border="1" cellpadding="2">

<tr>

<td align="center" height="150">

<?php

$foto = FCPATH.'uploads/siswa/'.$siswa->foto;

if(
    !empty($siswa->foto) &&
    file_exists($foto)
){
?>

<img src="<?= $foto ?>" width="110">

<?php
}else{
?>

<br><br><br><br><br>

<b>
FOTO<br>
3 x 4
</b>

<br><br><br><br><br>

<?php } ?>

</td>

</tr>

</table>

</td>

</tr>

</table>

<!-- ===================================================== -->
<!-- PART 2 : DATA ORANG TUA, WALI, PENDIDIKAN, PERIODIK -->
<!-- ===================================================== -->

<br pagebreak="true"/>

<table cellpadding="5">
    <tr>
        <td style="background-color:#d9edf7;border:1px solid #666;">
            <b>II. DATA ORANG TUA / WALI</b>
        </td>
    </tr>
</table>

<br>

<!-- ================= AYAH ================= -->

<table border="1" cellpadding="5">

<tr bgcolor="#EFEFEF">
    <td colspan="2"><b>DATA AYAH</b></td>
</tr>

<tr>
    <td width="35%"><b>Nama Ayah</b></td>
    <td width="65%"><?= $siswa->nama_ayah ?: '-' ?></td>
</tr>

<tr>
    <td><b>NIK</b></td>
    <td><?= $siswa->nik_ayah ?: '-' ?></td>
</tr>

<tr>
    <td><b>Tahun Lahir</b></td>
    <td><?= $siswa->tahun_lahir_ayah ?: '-' ?></td>
</tr>

<tr>
    <td><b>Pekerjaan</b></td>
    <td><?= $siswa->pekerjaan_ayah ?: '-' ?></td>
</tr>

<tr>
    <td><b>Penghasilan</b></td>
    <td><?= $siswa->penghasilan_ayah ?: '-' ?></td>
</tr>

</table>

<br>

<!-- ================= IBU ================= -->

<table border="1" cellpadding="5">

<tr bgcolor="#EFEFEF">
    <td colspan="2"><b>DATA IBU</b></td>
</tr>

<tr>
    <td width="35%"><b>Nama Ibu</b></td>
    <td width="65%"><?= $siswa->nama_ibu ?: '-' ?></td>
</tr>

<tr>
    <td><b>NIK</b></td>
    <td><?= $siswa->nik_ibu ?: '-' ?></td>
</tr>

<tr>
    <td><b>Tahun Lahir</b></td>
    <td><?= $siswa->tahun_lahir_ibu ?: '-' ?></td>
</tr>

<tr>
    <td><b>Pendidikan</b></td>
    <td><?= $siswa->pendidikan_ibu ?: '-' ?></td>
</tr>

<tr>
    <td><b>Pekerjaan</b></td>
    <td><?= $siswa->pekerjaan_ibu ?: '-' ?></td>
</tr>

<tr>
    <td><b>Penghasilan</b></td>
    <td><?= $siswa->penghasilan_ibu ?: '-' ?></td>
</tr>

</table>

<br>

<!-- ================= WALI ================= -->

<table border="1" cellpadding="5">

<tr bgcolor="#EFEFEF">
    <td colspan="2"><b>DATA WALI</b></td>
</tr>

<tr>
    <td width="35%"><b>Nama Wali</b></td>
    <td width="65%"><?= $siswa->nama_wali ?: '-' ?></td>
</tr>

<tr>
    <td><b>NIK</b></td>
    <td><?= $siswa->nik_wali ?: '-' ?></td>
</tr>

<tr>
    <td><b>Tahun Lahir</b></td>
    <td><?= $siswa->tahun_lahir_wali ?: '-' ?></td>
</tr>

<tr>
    <td><b>Pendidikan</b></td>
    <td><?= $siswa->pendidikan_wali ?: '-' ?></td>
</tr>

<tr>
    <td><b>Pekerjaan</b></td>
    <td><?= $siswa->pekerjaan_wali ?: '-' ?></td>
</tr>

<tr>
    <td><b>Penghasilan</b></td>
    <td><?= $siswa->penghasilan_wali ?: '-' ?></td>
</tr>

</table>

<br><br>

<table cellpadding="5">
    <tr>
        <td style="background-color:#d9edf7;border:1px solid #666;">
            <b>III. DATA PENDIDIKAN</b>
        </td>
    </tr>
</table>

<br>

<table border="1" cellpadding="5">

<tr>
    <td width="35%"><b>Sekolah Asal</b></td>
    <td width="65%"><?= $siswa->sekolah_asal ?: '-' ?></td>
</tr>

<tr>
    <td><b>Nomor SKHUN</b></td>
    <td><?= $siswa->skhun ?: '-' ?></td>
</tr>

<tr>
    <td><b>Kelas Saat Ini</b></td>
    <td><?= $siswa->nama_kelas ?: '-' ?></td>
</tr>

<tr>
    <td><b>Status</b></td>
    <td><?= ucfirst($siswa->status) ?></td>
</tr>

</table>

<br><br>

<table cellpadding="5">
    <tr>
        <td style="background-color:#d9edf7;border:1px solid #666;">
            <b>IV. DATA PERIODIK</b>
        </td>
    </tr>
</table>

<br>

<table border="1" cellpadding="5">

<tr>
    <td width="35%"><b>Tinggi Badan</b></td>
    <td width="65%"><?= $siswa->tinggi_badan ?: '-' ?> cm</td>
</tr>

<tr>
    <td><b>Berat Badan</b></td>
    <td><?= $siswa->berat_badan ?: '-' ?> kg</td>
</tr>

<tr>
    <td><b>Hobi</b></td>
    <td><?= $siswa->hobi ?: '-' ?></td>
</tr>

<tr>
    <td><b>Cita-cita</b></td>
    <td><?= $siswa->cita_cita ?: '-' ?></td>
</tr>

<tr>
    <td><b>Penerima KPS</b></td>
    <td><?= $siswa->penerima_kps ?: '-' ?></td>
</tr>

<tr>
    <td><b>Nomor KPS</b></td>
    <td><?= $siswa->no_kps ?: '-' ?></td>
</tr>

<tr>
    <td><b>Jenis Tinggal</b></td>
    <td><?= $siswa->jenis_tinggal ?: '-' ?></td>
</tr>

<tr>
    <td><b>Alat Transportasi</b></td>
    <td><?= $siswa->alat_transportasi ?: '-' ?></td>
</tr>

<tr>
    <td><b>Telepon</b></td>
    <td><?= $siswa->telp ?: '-' ?></td>
</tr>

<tr>
    <td><b>Handphone</b></td>
    <td><?= $siswa->hp ?: '-' ?></td>
</tr>

</table>
<!-- ===================================================== -->
<!-- PART 3 : NILAI RAPOR -->
<!-- ===================================================== -->

<br pagebreak="true"/>

<table cellpadding="5">
<tr>
<td style="background-color:#d9edf7;border:1px solid #666;">
<b>V. NILAI RAPOR PESERTA DIDIK</b>
</td>
</tr>
</table>

<br>

<table border="1" cellpadding="4">

<tr bgcolor="#EFEFEF">

<td width="7%" align="center"><b>No</b></td>

<td width="43%" align="center">
<b>Mata Pelajaran</b>
</td>

<td width="8%" align="center"><b>S1</b></td>

<td width="8%" align="center"><b>S2</b></td>

<td width="8%" align="center"><b>S3</b></td>

<td width="8%" align="center"><b>S4</b></td>

<td width="8%" align="center"><b>S5</b></td>

<td width="10%" align="center"><b>S6</b></td>

</tr>

<?php

$no=1;

if(!empty($rapor)):

foreach($rapor as $r):

?>

<tr>

<td align="center">
<?= $no++ ?>
</td>

<td>
<?= $r->nama_mapel ?>
</td>

<td align="center">
<?= ($r->smt1!='') ? $r->smt1 : '-' ?>
</td>

<td align="center">
<?= ($r->smt2!='') ? $r->smt2 : '-' ?>
</td>

<td align="center">
<?= ($r->smt3!='') ? $r->smt3 : '-' ?>
</td>

<td align="center">
<?= ($r->smt4!='') ? $r->smt4 : '-' ?>
</td>

<td align="center">
<?= ($r->smt5!='') ? $r->smt5 : '-' ?>
</td>

<td align="center">
<?= (isset($r->smt6) && $r->smt6!='') ? $r->smt6 : '-' ?>
</td>

</tr>

<?php

endforeach;

else:

?>

<tr>

<td colspan="8" align="center">

Belum ada data nilai rapor.

</td>

</tr>

<?php endif; ?>

</table>

<br><br>

<table border="0" cellpadding="4">

<tr>

<td width="25%">
Jumlah Mata Pelajaran
</td>

<td width="5%">
:
</td>

<td width="70%">
<?= !empty($rapor) ? count($rapor) : 0 ?>
</td>

</tr>

<tr>

<td>
Keterangan
</td>

<td>
:
</td>

<td>

Nilai ditampilkan berdasarkan rekap nilai rapor setiap semester.

</td>

</tr>

</table>
<!-- ===================================================== -->
<!-- PART 4 : KEHADIRAN - MUTASI - TIMELINE -->
<!-- ===================================================== -->

<br pagebreak="true"/>

<table cellpadding="5">
<tr>
<td style="background-color:#d9edf7;border:1px solid #666;">
<b>VI. REKAP KEHADIRAN PESERTA DIDIK</b>
</td>
</tr>
</table>

<br>

<table border="1" cellpadding="4">

<tr bgcolor="#EFEFEF">

<td width="25%" align="center"><b>Tahun Ajaran</b></td>

<td width="15%" align="center"><b>Semester</b></td>

<td width="12%" align="center"><b>Hadir</b></td>

<td width="12%" align="center"><b>Sakit</b></td>

<td width="12%" align="center"><b>Izin</b></td>

<td width="12%" align="center"><b>Alpha</b></td>

<td width="12%" align="center"><b>Terlambat</b></td>

</tr>

<?php if(!empty($kehadiran)): ?>

<?php foreach($kehadiran as $k): ?>

<tr>

<td align="center">
<?= $k->tahun_ajaran ?>
</td>

<td align="center">
<?= $k->semester ?>
</td>

<td align="center">
<?= $k->hadir ?>
</td>

<td align="center">
<?= $k->sakit ?>
</td>

<td align="center">
<?= $k->izin ?>
</td>

<td align="center">
<?= $k->alpha ?>
</td>

<td align="center">
<?= $k->terlambat ?>
</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="7" align="center">

Belum ada data kehadiran.

</td>

</tr>

<?php endif; ?>

</table>

<br><br>

<table cellpadding="5">
<tr>
<td style="background-color:#d9edf7;border:1px solid #666;">
<b>VII. RIWAYAT MUTASI</b>
</td>
</tr>
</table>

<br>

<table border="1" cellpadding="4">

<tr bgcolor="#EFEFEF">

<td width="12%" align="center"><b>No</b></td>

<td width="22%" align="center"><b>Tanggal</b></td>

<td width="33%" align="center"><b>Jenis Mutasi</b></td>

<td width="33%" align="center"><b>Keterangan</b></td>

</tr>

<?php

$no=1;

if(!empty($mutasi)):

foreach($mutasi as $m):

?>

<tr>

<td align="center">
<?= $no++ ?>
</td>

<td align="center">
<?= date('d-m-Y',strtotime($m->tanggal)); ?>
</td>

<td>
<?= $m->jenis_mutasi ?>
</td>

<td>
<?= $m->keterangan ?>
</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="4" align="center">

Tidak ada riwayat mutasi.

</td>

</tr>

<?php endif; ?>

</table>

<br><br>

<table cellpadding="5">
<tr>
<td style="background-color:#d9edf7;border:1px solid #666;">
<b>VIII. TIMELINE PESERTA DIDIK</b>
</td>
</tr>
</table>

<br>

<table border="1" cellpadding="4">

<tr bgcolor="#EFEFEF">

<td width="10%" align="center"><b>No</b></td>

<td width="25%" align="center"><b>Tanggal</b></td>

<td width="65%" align="center"><b>Kegiatan</b></td>

</tr>

<?php

$no=1;

if(!empty($timeline)):

foreach($timeline as $t):

?>

<tr>

<td align="center">
<?= $no++ ?>
</td>

<td align="center">
<?= date('d-m-Y',strtotime($t->tanggal)); ?>
</td>

<td>

<b><?= $t->judul ?></b>

<?php

if(!empty($t->keterangan))
{
    echo "<br>".$t->keterangan;
}

?>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="3" align="center">

Belum ada timeline siswa.

</td>

</tr>

<?php endif; ?>

</table>
<!-- ===================================================== -->
<!-- PART 5 : PENUTUP -->
<!-- ===================================================== -->

<br pagebreak="true"/>

<table cellpadding="5">
<tr>
<td style="background-color:#d9edf7;border:1px solid #666;">
<b>IX. CATATAN</b>
</td>
</tr>
</table>

<br>

<table border="1" cellpadding="6">

<tr>

<td height="180">

Catatan penting mengenai peserta didik dapat dituliskan pada halaman ini.

<br><br><br><br><br><br><br><br><br><br><br>

............................................................................................................................

<br><br>

............................................................................................................................

<br><br>

............................................................................................................................

<br><br>

............................................................................................................................

</td>

</tr>

</table>

<br><br>

<table cellpadding="5">
<tr>
<td style="background-color:#d9edf7;border:1px solid #666;">
<b>X. PENGESAHAN</b>
</td>
</tr>
</table>

<br><br>

<table cellpadding="5">

<tr>

<td width="50%" align="center">

Mengetahui,

<br>

Kepala Sekolah

<br><br><br><br><br><br><br>

<u><b>.............................................</b></u>

<br>

NIP. ....................................

</td>

<td width="50%" align="center">

<?= tgl_indo_teks(date('Y-m-d')); ?>

<br>

Wali Kelas

<br><br><br><br><br><br><br>

<u><b>.............................................</b></u>

<br>

NIP. ....................................

</td>

</tr>

</table>

<br><br><br>

<hr>

<table cellpadding="3">

<tr>

<td width="70%">

<b>SMK Negeri 1 Cilimus</b>

</td>

<td width="30%" align="right">

Dicetak :

<?= date('d-m-Y H:i'); ?>

</td>

</tr>

<tr>

<td colspan="2" align="center">

<i>
Dokumen ini dihasilkan secara otomatis oleh Sistem Informasi Akademik SMK Negeri 1 Cilimus.
</i>

</td>

</tr>

</table>

</body>
</html>