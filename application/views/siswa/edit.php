<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>

            <h1 class="h3 mb-0 text-gray-800">

                <i class="fas fa-user-edit text-primary"></i>

                Edit Data Siswa

            </h1>

            <small class="text-muted">

                Perbarui biodata peserta didik.

            </small>

        </div>

        <a href="<?= site_url('siswa'); ?>" class="btn btn-secondary shadow-sm">

            <i class="fas fa-arrow-left"></i>

            Kembali

        </a>

    </div>

    <?php if(validation_errors()): ?>

        <div class="alert alert-danger">

            <strong>
                <i class="fas fa-exclamation-circle"></i>
                Terjadi Kesalahan
            </strong>

            <hr>

            <?= validation_errors(); ?>

        </div>

    <?php endif; ?>


    <?= form_open_multipart(
        'siswa/edit/'.$siswa->id,
        [
            'id'=>'formEditSiswa',
            'autocomplete'=>'off'
        ]
    ); ?>


    <input
        type="hidden"
        name="<?= $this->security->get_csrf_token_name(); ?>"
        value="<?= $this->security->get_csrf_hash(); ?>">

        <?php

$agama = [
    'Islam',
    'Kristen',
    'Katolik',
    'Hindu',
    'Budha',
    'Konghucu'
];

$jenis_tinggal = [
    'Bersama Orang Tua',
    'Wali',
    'Kos',
    'Asrama',
    'Panti Asuhan',
    'Lainnya'
];

$transportasi = [
    'Jalan Kaki',
    'Sepeda',
    'Sepeda Motor',
    'Mobil Pribadi',
    'Angkutan Umum',
    'Antar Jemput',
    'Lainnya'
];

$pendidikan = [
    'Tidak Sekolah',
    'Putus SD',
    'SD/Sederajat',
    'SMP/Sederajat',
    'SMA/Sederajat',
    'D1',
    'D2',
    'D3',
    'D4/S1',
    'S2',
    'S3'
];

$pekerjaan = [
   "Tidak Bekerja",
    "Sudah Meninggal",
    "Buruh", "Petani", "Nelayan",
    "Pedagang Kecil", "Pedagang Besar",
    "Karyawan Swasta", "Wiraswasta", "Wirausaha",
    "Guru/Dosen",
    "ASN/PNS", "Polisi", "TNI",
    "Perangkat Desa",
    "Pensiunan"
];

$penghasilan = [
    "Tidak Berpenghasilan",
    "Kurang dari Rp 500,000",
    "Rp. 500,000 - Rp. 999,999",
    "Rp. 1,000,000 - Rp. 1,999,999",
    "Rp. 2,000,000 - Rp. 4,999,999",
    "Rp. 5,000,000 - Rp. 20,000,000",
    "Lebih dari Rp 20,000,000"
];

?>

<div class="card shadow mb-4 border-left-primary">

    <div class="card-header bg-primary text-white">

        <h6 class="m-0 font-weight-bold">

            <i class="fas fa-user mr-2"></i>

            A. DATA PRIBADI

        </h6>

    </div>

    <div class="card-body">

        <div class="form-row">

            <div class="form-group col-md-3">

                <label>NIS <span class="text-danger">*</span></label>

                <input
                    type="text"
                    name="nis"
                    class="form-control"
                    required
                    value="<?= set_value('nis',$siswa->nis); ?>">

                <?= form_error('nis','<small class="text-danger">','</small>'); ?>

            </div>

            <div class="form-group col-md-3">

                <label>NISN</label>

                <input
                    type="text"
                    maxlength="10"
                    name="nisn"
                    class="form-control"
                    value="<?= set_value('nisn',$siswa->nisn); ?>">

            </div>

            <div class="form-group col-md-6">

                <label>Nama Lengkap <span class="text-danger">*</span></label>

                <input
                    type="text"
                    name="nama"
                    class="form-control"
                    required
                    value="<?= set_value('nama',$siswa->nama); ?>">

            </div>

        </div>

        <div class="form-row">

            <div class="form-group col-md-4">

                <label>NIK</label>

                <input
                    type="text"
                    maxlength="16"
                    name="nik"
                    class="form-control"
                    value="<?= set_value('nik',$siswa->nik); ?>">

            </div>

            <div class="form-group col-md-4">

                <label>Nomor KK</label>

                <input
                    type="text"
                    maxlength="16"
                    name="nomor_kk"
                    class="form-control"
                    value="<?= set_value('nomor_kk',$siswa->nomor_kk); ?>">

            </div>

            <div class="form-group col-md-4">

                <label>Anak Ke</label>

                <input
                    type="number"
                    min="1"
                    name="anak_keberapa"
                    class="form-control"
                    value="<?= set_value('anak_keberapa',$siswa->anak_keberapa); ?>">

            </div>

        </div>

        <div class="form-row">

            <div class="form-group col-md-4">

                <label>Jenis Kelamin</label>

                <select
                    name="jk"
                    class="form-control">

                    <option value="L" <?= set_select('jk','L',$siswa->jk=='L'); ?>>
                        Laki-laki
                    </option>

                    <option value="P" <?= set_select('jk','P',$siswa->jk=='P'); ?>>
                        Perempuan
                    </option>

                </select>

            </div>

            <div class="form-group col-md-4">

                <label>Agama</label>

                <select
                    name="agama"
                    class="form-control">

                    <?php foreach($agama as $a): ?>

                        <option
                            value="<?= $a ?>"
                            <?= set_select('agama',$a,$siswa->agama==$a); ?>>

                            <?= $a ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group col-md-4">

                <label>Tanggal Lahir</label>

                <input
                    type="date"
                    name="tgl_lahir"
                    class="form-control"
                    value="<?= set_value('tgl_lahir',$siswa->tgl_lahir); ?>">

            </div>

        </div>

        <div class="form-group">

            <label>Tempat Lahir</label>

            <input
                type="text"
                name="tempat_lahir"
                class="form-control"
                value="<?= set_value('tempat_lahir',$siswa->tempat_lahir); ?>">

        </div>

        <hr>

        <h6 class="font-weight-bold text-primary mb-3">

            <i class="fas fa-map-marker-alt"></i>

            Alamat Domisili

        </h6>

        <div class="form-group">

            <label>Jalan</label>

            <input
                type="text"
                name="jalan"
                class="form-control"
                value="<?= set_value('jalan',$siswa->jalan); ?>">

        </div>

        <div class="form-row">

            <div class="form-group col-md-4">

                <label>Dusun</label>

                <input
                    type="text"
                    name="dusun"
                    class="form-control"
                    value="<?= set_value('dusun',$siswa->dusun); ?>">

            </div>

            <div class="form-group col-md-4">

                <label>Desa / Kelurahan</label>

                <input
                    type="text"
                    name="alamat"
                    class="form-control"
                    value="<?= set_value('alamat',$siswa->alamat); ?>">

            </div>

            <div class="form-group col-md-4">

                <label>Kecamatan</label>

                <input
                    type="text"
                    name="kecamatan"
                    class="form-control"
                    value="<?= set_value('kecamatan',$siswa->kecamatan); ?>">

            </div>

        </div>

        <div class="form-row">

            <div class="form-group col-md-2">

                <label>RT</label>

                <input
                    type="text"
                    name="rt"
                    class="form-control"
                    value="<?= set_value('rt',$siswa->rt); ?>">

            </div>

            <div class="form-group col-md-2">

                <label>RW</label>

                <input
                    type="text"
                    name="rw"
                    class="form-control"
                    value="<?= set_value('rw',$siswa->rw); ?>">

            </div>

            <div class="form-group col-md-2">

                <label>Kode Pos</label>

                <input
                    type="text"
                    name="kode_pos"
                    class="form-control"
                    value="<?= set_value('kode_pos',$siswa->kode_pos); ?>">

            </div>

            <div class="form-group col-md-3">

                <label>Jenis Tinggal</label>

                <select
                    name="jenis_tinggal"
                    class="form-control">

                    <option value="">-- Pilih --</option>

                    <?php foreach($jenis_tinggal as $jt): ?>

                        <option
                            value="<?= $jt ?>"
                            <?= set_select('jenis_tinggal',$jt,$siswa->jenis_tinggal==$jt); ?>>

                            <?= $jt ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group col-md-3">

                <label>Transportasi</label>

                <select
                    name="alat_transportasi"
                    class="form-control">

                    <option value="">-- Pilih --</option>

                    <?php foreach($transportasi as $tr): ?>

                        <option
                            value="<?= $tr ?>"
                            <?= set_select('alat_transportasi',$tr,$siswa->alat_transportasi==$tr); ?>>

                            <?= $tr ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

        </div>

        <hr>

        <h6 class="font-weight-bold text-primary mb-3">

            <i class="fas fa-phone"></i>

            Kontak

        </h6>

        <div class="form-row">

            <div class="form-group col-md-4">

                <label>Telepon Rumah</label>

                <input
                    type="text"
                    name="telp"
                    class="form-control"
                    value="<?= set_value('telp',$siswa->telp); ?>">

            </div>

            <div class="form-group col-md-4">

                <label>No. HP</label>

                <input
                    type="text"
                    name="hp"
                    class="form-control"
                    value="<?= set_value('hp',$siswa->hp); ?>">

            </div>

            <div class="form-group col-md-4">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="<?= set_value('email',$siswa->email); ?>">

            </div>

        </div>

    </div>

</div>
<!-- ==========================================
     B. KESEJAHTERAAN PESERTA DIDIK
========================================== -->

<div class="card shadow mb-4 border-left-success">

    <div class="card-header bg-success text-white">

        <h6 class="m-0 font-weight-bold">

            <i class="fas fa-hand-holding-heart mr-2"></i>

            B. KESEJAHTERAAN PESERTA DIDIK

        </h6>

    </div>

    <div class="card-body">

        <div class="form-row">

            <div class="form-group col-md-6">

                <label>Status Desil</label>

                <select
                    name="desil"
                    class="form-control">

                    <option value="">-- Belum Diisi --</option>

                    <?php for($i=1;$i<=10;$i++): ?>

                        <option
                            value="<?= $i ?>"
                            <?= set_select('desil',$i,$siswa->desil==$i); ?>>

                            Desil <?= $i ?>

                        </option>

                    <?php endfor; ?>

                </select>

                <small class="text-muted">

                    Berdasarkan Data DTSEN / Kemensos.

                </small>

            </div>

            <div class="form-group col-md-3">

                <label>Penerima KPS</label>

                <select
                    name="penerima_kps"
                    class="form-control">

                    <option value="">-- Pilih --</option>

                    <option value="1"
                        <?= set_select('penerima_kps','1',$siswa->penerima_kps==1); ?>>

                        Ya

                    </option>

                    <option value="0"
                        <?= set_select('penerima_kps','0',$siswa->penerima_kps==0); ?>>

                        Tidak

                    </option>

                </select>

            </div>

            <div class="form-group col-md-3">

                <label>Nomor KPS</label>

                <input
                    type="text"
                    name="no_kps"
                    class="form-control"
                    value="<?= set_value('no_kps',$siswa->no_kps); ?>">

            </div>

        </div>

    </div>

</div>
<!-- ==========================================
     C. DATA PERIODIK
========================================== -->

<div class="card shadow mb-4 border-left-info">

    <div class="card-header bg-info text-white">

        <h6 class="m-0 font-weight-bold">

            <i class="fas fa-ruler-combined mr-2"></i>

            C. DATA PERIODIK

        </h6>

    </div>

    <div class="card-body">

        <div class="form-row">

            <div class="form-group col-md-4">

                <label>Tinggi Badan (cm)</label>

                <input
                    type="number"
                    min="0"
                    name="tinggi_badan"
                    class="form-control"
                    value="<?= set_value('tinggi_badan',$siswa->tinggi_badan); ?>">

            </div>

            <div class="form-group col-md-4">

                <label>Berat Badan (kg)</label>

                <input
                    type="number"
                    min="0"
                    name="berat_badan"
                    class="form-control"
                    value="<?= set_value('berat_badan',$siswa->berat_badan); ?>">

            </div>

            <div class="form-group col-md-4">

                <label>Jumlah Saudara Kandung</label>

                <input
                    type="number"
                    min="0"
                    name="jumlah_saudara_kandung"
                    class="form-control"
                    value="<?= set_value('jumlah_saudara_kandung',$siswa->jumlah_saudara_kandung); ?>">

            </div>

        </div>

        <div class="form-row">

            <div class="form-group col-md-6">

                <label>Hobi</label>

                <input
                    type="text"
                    name="hobi"
                    class="form-control"
                    value="<?= set_value('hobi',$siswa->hobi); ?>">

            </div>

            <div class="form-group col-md-6">

                <label>Cita-cita</label>

                <input
                    type="text"
                    name="cita_cita"
                    class="form-control"
                    value="<?= set_value('cita_cita',$siswa->cita_cita); ?>">

            </div>

        </div>

    </div>

</div>
<!-- ==========================================
     D. DATA PENDIDIKAN
========================================== -->

<div class="card shadow mb-4 border-left-warning">

    <div class="card-header bg-warning">

        <h6 class="m-0 font-weight-bold text-dark">

            <i class="fas fa-school mr-2"></i>

            D. DATA PENDIDIKAN

        </h6>

    </div>

    <div class="card-body">

        <div class="form-row">

            <div class="form-group col-md-6">

                <label>Sekolah Asal</label>

                <input
                    type="text"
                    name="sekolah_asal"
                    class="form-control"
                    value="<?= set_value('sekolah_asal',$siswa->sekolah_asal); ?>">

            </div>

            <div class="form-group col-md-6">

                <label>No. Ijazah / SKHUN</label>

                <input
                    type="text"
                    name="skhun"
                    class="form-control"
                    value="<?= set_value('skhun',$siswa->skhun); ?>">

            </div>

        </div>

    </div>

</div>
<!-- ==========================================
     E. DATA AYAH KANDUNG
========================================== -->

<div class="card shadow mb-4 border-left-secondary">

    <div class="card-header bg-secondary text-white">

        <h6 class="m-0 font-weight-bold">

            <i class="fas fa-male mr-2"></i>

            E. DATA AYAH KANDUNG

        </h6>

    </div>

    <div class="card-body">

        <div class="form-row">

            <div class="form-group col-md-6">

                <label>Nama Ayah</label>

                <input
                    type="text"
                    name="nama_ayah"
                    class="form-control"
                    value="<?= set_value('nama_ayah',$siswa->nama_ayah); ?>">

            </div>

            <div class="form-group col-md-6">

                <label>NIK Ayah</label>

                <input
                    type="text"
                    name="nik_ayah"
                    maxlength="16"
                    class="form-control"
                    value="<?= set_value('nik_ayah',$siswa->nik_ayah); ?>">

            </div>

        </div>

        <div class="form-row">

            <div class="form-group col-md-4">

                <label>Tahun Lahir</label>

                <input
                    type="number"
                    name="tahun_lahir_ayah"
                    class="form-control"
                    value="<?= set_value('tahun_lahir_ayah',$siswa->tahun_lahir_ayah); ?>">

            </div>

            <div class="form-group col-md-4">

                <label>Pendidikan</label>

                <select
                    name="pendidikan_ayah"
                    class="form-control">

                    <option value="">-- Pilih --</option>

                    <?php foreach($pendidikan as $v): ?>

                        <option
                            value="<?= $v ?>"
                            <?= set_select('pendidikan_ayah',$v,$siswa->pendidikan_ayah==$v); ?>>

                            <?= $v ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group col-md-4">

                <label>Pekerjaan</label>

                <select
                    name="pekerjaan_ayah"
                    class="form-control">

                    <option value="">-- Pilih --</option>

                    <?php foreach($pekerjaan as $v): ?>

                        <option
                            value="<?= $v ?>"
                            <?= set_select('pekerjaan_ayah',$v,$siswa->pekerjaan_ayah==$v); ?>>

                            <?= $v ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

        </div>

        <div class="form-group">

            <label>Penghasilan Bulanan</label>

            <select
                name="penghasilan_ayah"
                class="form-control">

                <option value="">-- Pilih --</option>

                <?php foreach($penghasilan as $v): ?>

                    <option
                        value="<?= $v ?>"
                        <?= set_select('penghasilan_ayah',$v,$siswa->penghasilan_ayah==$v); ?>>

                        <?= $v ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

    </div>

</div>
<!-- ==========================================
     F. DATA IBU KANDUNG
========================================== -->

<div class="card shadow mb-4 border-left-danger">

    <div class="card-header bg-danger text-white">

        <h6 class="m-0 font-weight-bold">

            <i class="fas fa-female mr-2"></i>

            F. DATA IBU KANDUNG

        </h6>

    </div>

    <div class="card-body">

        <div class="form-row">

            <div class="form-group col-md-6">

                <label>Nama Ibu</label>

                <input
                    type="text"
                    name="nama_ibu"
                    class="form-control"
                    value="<?= set_value('nama_ibu',$siswa->nama_ibu); ?>">

            </div>

            <div class="form-group col-md-6">

                <label>NIK Ibu</label>

                <input
                    type="text"
                    maxlength="16"
                    name="nik_ibu"
                    class="form-control"
                    value="<?= set_value('nik_ibu',$siswa->nik_ibu); ?>">

            </div>

        </div>

        <div class="form-row">

            <div class="form-group col-md-4">

                <label>Tahun Lahir</label>

                <input
                    type="number"
                    name="tahun_lahir_ibu"
                    class="form-control"
                    value="<?= set_value('tahun_lahir_ibu',$siswa->tahun_lahir_ibu); ?>">

            </div>

            <div class="form-group col-md-4">

                <label>Pendidikan</label>

                <select
                    name="pendidikan_ibu"
                    class="form-control">

                    <option value="">-- Pilih --</option>

                    <?php foreach($pendidikan as $v): ?>

                        <option
                            value="<?= $v ?>"
                            <?= set_select('pendidikan_ibu',$v,$siswa->pendidikan_ibu==$v); ?>>

                            <?= $v ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group col-md-4">

                <label>Pekerjaan</label>

                <select
                    name="pekerjaan_ibu"
                    class="form-control">

                    <option value="">-- Pilih --</option>

                    <?php foreach($pekerjaan as $v): ?>

                        <option
                            value="<?= $v ?>"
                            <?= set_select('pekerjaan_ibu',$v,$siswa->pekerjaan_ibu==$v); ?>>

                            <?= $v ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

        </div>

        <div class="form-group">

            <label>Penghasilan Bulanan</label>

            <select
                name="penghasilan_ibu"
                class="form-control">

                <option value="">-- Pilih --</option>

                <?php foreach($penghasilan as $v): ?>

                    <option
                        value="<?= $v ?>"
                        <?= set_select('penghasilan_ibu',$v,$siswa->penghasilan_ibu==$v); ?>>

                        <?= $v ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

    </div>

</div>
<!-- ==========================================
     G. DATA WALI
========================================== -->

<div class="card shadow mb-4 border-left-dark">

    <div class="card-header bg-dark text-white">

        <h6 class="m-0 font-weight-bold">

            <i class="fas fa-user-friends mr-2"></i>

            G. DATA WALI

        </h6>

    </div>

    <div class="card-body">

        <div class="form-group">

            <label>Memiliki Wali?</label>

            <div>

                <div class="custom-control custom-radio custom-control-inline">

                    <input
                        type="radio"
                        id="wali_tidak"
                        class="custom-control-input"
                        name="punya_wali"
                        value="0"
                        <?= empty($siswa->nama_wali)?'checked':''; ?>>

                    <label class="custom-control-label" for="wali_tidak">
                        Tidak
                    </label>

                </div>

                <div class="custom-control custom-radio custom-control-inline">

                    <input
                        type="radio"
                        id="wali_ya"
                        class="custom-control-input"
                        name="punya_wali"
                        value="1"
                        <?= !empty($siswa->nama_wali)?'checked':''; ?>>

                    <label class="custom-control-label" for="wali_ya">
                        Ya
                    </label>

                </div>

            </div>

        </div>

        <div id="formWali" <?= empty($siswa->nama_wali)?'style="display:none"':''; ?>>

            <div class="form-row">

                <div class="form-group col-md-6">

                    <label>Nama Wali</label>

                    <input
                        type="text"
                        name="nama_wali"
                        class="form-control"
                        value="<?= set_value('nama_wali',$siswa->nama_wali); ?>">

                </div>

                <div class="form-group col-md-6">

                    <label>NIK Wali</label>

                    <input
                        type="text"
                        name="nik_wali"
                        maxlength="16"
                        class="form-control"
                        value="<?= set_value('nik_wali',$siswa->nik_wali); ?>">

                </div>

            </div>

            <div class="form-row">

                <div class="form-group col-md-4">

                    <label>Tahun Lahir</label>

                    <input
                        type="number"
                        name="tahun_lahir_wali"
                        class="form-control"
                        value="<?= set_value('tahun_lahir_wali',$siswa->tahun_lahir_wali); ?>">

                </div>

                <div class="form-group col-md-4">

                    <label>Pendidikan</label>

                    <select name="pendidikan_wali" class="form-control">

                        <option value="">-- Pilih --</option>

                        <?php foreach($pendidikan as $v): ?>

                            <option
                                value="<?= $v ?>"
                                <?= set_select('pendidikan_wali',$v,$siswa->pendidikan_wali==$v); ?>>

                                <?= $v ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="form-group col-md-4">

                    <label>Pekerjaan</label>

                    <select name="pekerjaan_wali" class="form-control">

                        <option value="">-- Pilih --</option>

                        <?php foreach($pekerjaan as $v): ?>

                            <option
                                value="<?= $v ?>"
                                <?= set_select('pekerjaan_wali',$v,$siswa->pekerjaan_wali==$v); ?>>

                                <?= $v ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </div>

            <div class="form-group">

                <label>Penghasilan Bulanan</label>

                <select name="penghasilan_wali" class="form-control">

                    <option value="">-- Pilih --</option>

                    <?php foreach($penghasilan as $v): ?>

                        <option
                            value="<?= $v ?>"
                            <?= set_select('penghasilan_wali',$v,$siswa->penghasilan_wali==$v); ?>>

                            <?= $v ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

        </div>

    </div>

</div>
<!-- ==========================================
     H. DATA AKADEMIK
========================================== -->

<div class="card shadow mb-4 border-left-primary">

    <div class="card-header bg-primary text-white">

        <h6 class="m-0 font-weight-bold">

            <i class="fas fa-school mr-2"></i>

            H. DATA AKADEMIK

        </h6>

    </div>

    <div class="card-body">

        <div class="form-row">

            <div class="form-group col-md-4">

                <label>Kelas</label>

                <select
                    name="id_kelas"
                    class="form-control">

                    <option value="">-- Pilih Kelas --</option>

                    <?php foreach($kelas as $k): ?>

                        <option
                            value="<?= $k->id; ?>"
                            <?= set_select('id_kelas',$k->id,$siswa->id_kelas==$k->id); ?>>

                            <?= $k->nama; ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group col-md-4">

                <label>Tahun Ajaran</label>

                <select
                    name="tahun_id"
                    class="form-control">

                    <?php foreach($tahun as $t): ?>

                        <option
                            value="<?= $t->id; ?>"
                            <?= set_select('tahun_id',$t->id,$siswa->tahun_id==$t->id); ?>>

                            <?= $t->tahun; ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group col-md-4">

                <label>Status</label>

                <select
                    name="status"
                    class="form-control">

                    <option value="aktif" <?= set_select('status','aktif',$siswa->status=='aktif'); ?>>
                        Aktif
                    </option>

                    <option value="mutasi_masuk" <?= set_select('status','mutasi_masuk',$siswa->status=='mutasi_masuk'); ?>>
                        Mutasi Masuk
                    </option>

                    <option value="mutasi_keluar" <?= set_select('status','mutasi_keluar',$siswa->status=='mutasi_keluar'); ?>>
                        Mutasi Keluar
                    </option>

                    <option value="lulus" <?= set_select('status','lulus',$siswa->status=='lulus'); ?>>
                        Lulus
                    </option>

                    <option value="keluar" <?= set_select('status','keluar',$siswa->status=='keluar'); ?>>
                        Keluar
                    </option>

                </select>

            </div>

        </div>

    </div>

</div>
<!-- ==========================================
     I. FOTO SISWA
========================================== -->

<div class="card shadow mb-4 border-left-success">

    <div class="card-header bg-success text-white">

        <h6 class="m-0 font-weight-bold">

            <i class="fas fa-camera mr-2"></i>

            I. FOTO SISWA

        </h6>

    </div>

    <div class="card-body text-center">

        <?php if(!empty($siswa->foto) && file_exists(FCPATH.'uploads/foto/'.$siswa->foto)): ?>

            <img
                id="previewFoto"
                src="<?= base_url('uploads/foto/'.$siswa->foto); ?>"
                class="img-thumbnail mb-3"
                style="width:170px;height:220px;object-fit:cover;">

        <?php else: ?>

            <img
                id="previewFoto"
                src="<?= base_url('assets/img/no-image.png'); ?>"
                class="img-thumbnail mb-3"
                style="width:170px;height:220px;object-fit:cover;">

        <?php endif; ?>

        <div>

            <input
                type="file"
                id="foto"
                name="foto"
                accept=".jpg,.jpeg,.png"
                class="form-control-file">

            <small class="text-muted">

                Format JPG / JPEG / PNG (Maks. 2 MB)

            </small>

        </div>

    </div>

</div>
<!-- ==========================================
     J. KONTAK ORANG TUA
========================================== -->

<div class="card shadow mb-4 border-left-info">

    <div class="card-header bg-info text-white">

        <h6 class="m-0 font-weight-bold">

            <i class="fab fa-whatsapp mr-2"></i>

            J. KONTAK ORANG TUA / WALI

        </h6>

    </div>

    <div class="card-body">

        <div class="form-group">

            <label>No. HP Orang Tua / Wali</label>

            <input
                type="text"
                name="no_hp_ortu"
                class="form-control"
                value="<?= set_value('no_hp_ortu',$siswa->no_hp_ortu); ?>">

        </div>

    </div>

</div>
<div class="text-right mb-5">

    <a
        href="<?= site_url('siswa'); ?>"
        class="btn btn-secondary">

        <i class="fas fa-arrow-left"></i>

        Kembali

    </a>

    <button
        type="submit"
        class="btn btn-primary">

        <i class="fas fa-save"></i>

        Simpan Perubahan

    </button>

</div>
<script>

document.addEventListener('DOMContentLoaded',function(){

    const waliYa=document.getElementById('wali_ya');
    const waliTidak=document.getElementById('wali_tidak');
    const formWali=document.getElementById('formWali');

    function toggleWali(){

        if(waliYa.checked){

            formWali.style.display='block';

        }else{

            formWali.style.display='none';

            formWali.querySelectorAll('input').forEach(function(el){

                if(el.type!='radio'){

                    el.value='';

                }

            });

            formWali.querySelectorAll('select').forEach(function(el){

                el.selectedIndex=0;

            });

        }

    }

    waliYa.addEventListener('change',toggleWali);
    waliTidak.addEventListener('change',toggleWali);

    toggleWali();


    document.getElementById('foto').addEventListener('change',function(e){

        if(e.target.files.length){

            document.getElementById('previewFoto').src=
                URL.createObjectURL(e.target.files[0]);

        }

    });

});

</script>
<?= form_close(); ?>

</div>