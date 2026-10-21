<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$is_edit = isset($tendik) && $tendik;

function tendik_value($field, $tendik = null)
{
    if ($tendik && isset($tendik->$field)) {
        return $tendik->$field;
    }

    return '';
}
?>


<!-- =========================================================
     IDENTITAS UTAMA
========================================================== -->

<div class="card shadow mb-4">

    <div class="card-header py-3">

        <h6 class="m-0 font-weight-bold text-primary">

            <i class="fas fa-user mr-1"></i>
            Identitas Tendik

        </h6>

    </div>


    <div class="card-body">

        <div class="row">

            <!-- NAMA -->

            <div class="col-md-8">

                <div class="form-group">

                    <label>
                        Nama <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="nama"
                           class="form-control"
                           value="<?= html_escape(tendik_value('nama', $tendik ?? null)) ?>"
                           required>

                </div>

            </div>


            <!-- NUPTK -->

            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        NUPTK <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="nuptk"
                           class="form-control"
                           value="<?= html_escape(tendik_value('nuptk', $tendik ?? null)) ?>"
                           required>

                </div>

            </div>


            <!-- JK -->

            <div class="col-md-3">

                <div class="form-group">

                    <label>
                        Jenis Kelamin
                    </label>

                    <select name="jk"
                            class="form-control">

                        <option value="">
                            - Pilih -
                        </option>

                        <option value="L"
                            <?= tendik_value('jk', $tendik ?? null) == 'L' ? 'selected' : '' ?>>
                            Laki-laki
                        </option>

                        <option value="P"
                            <?= tendik_value('jk', $tendik ?? null) == 'P' ? 'selected' : '' ?>>
                            Perempuan
                        </option>

                    </select>

                </div>

            </div>


            <!-- TEMPAT LAHIR -->

            <div class="col-md-5">

                <div class="form-group">

                    <label>
                        Tempat Lahir
                    </label>

                    <input type="text"
                           name="tempat_lahir"
                           class="form-control"
                           value="<?= html_escape(tendik_value('tempat_lahir', $tendik ?? null)) ?>">

                </div>

            </div>


            <!-- TANGGAL LAHIR -->

            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        Tanggal Lahir
                    </label>

                    <input type="date"
                           name="tanggal_lahir"
                           class="form-control"
                           value="<?= html_escape(tendik_value('tanggal_lahir', $tendik ?? null)) ?>">

                </div>

            </div>


            <!-- NIP -->

            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        NIP
                    </label>

                    <input type="text"
                           name="nip"
                           class="form-control"
                           value="<?= html_escape(tendik_value('nip', $tendik ?? null)) ?>">

                </div>

            </div>


            <!-- STATUS KEPEGAWAIAN -->

            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        Status Kepegawaian
                    </label>

                    <input type="text"
                           name="status_kepegawaian"
                           class="form-control"
                           value="<?= html_escape(tendik_value('status_kepegawaian', $tendik ?? null)) ?>">

                </div>

            </div>


            <!-- JENIS PTK -->

            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        Jenis PTK
                    </label>

                    <input type="text"
                           name="jenis_ptk"
                           class="form-control"
                           value="<?= html_escape(tendik_value('jenis_ptk', $tendik ?? null)) ?>">

                </div>

            </div>


            <!-- AGAMA -->

            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        Agama
                    </label>

                    <input type="text"
                           name="agama"
                           class="form-control"
                           value="<?= html_escape(tendik_value('agama', $tendik ?? null)) ?>">

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     ALAMAT
========================================================== -->

<div class="card shadow mb-4">

    <div class="card-header py-3">

        <h6 class="m-0 font-weight-bold text-primary">

            <i class="fas fa-map-marker-alt mr-1"></i>
            Alamat & Kontak

        </h6>

    </div>


    <div class="card-body">

        <div class="row">

            <div class="col-md-8">

                <div class="form-group">

                    <label>
                        Alamat Jalan
                    </label>

                    <input type="text"
                           name="alamat_jalan"
                           class="form-control"
                           value="<?= html_escape(tendik_value('alamat_jalan', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-2">

                <div class="form-group">

                    <label>
                        RT
                    </label>

                    <input type="text"
                           name="rt"
                           class="form-control"
                           value="<?= html_escape(tendik_value('rt', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-2">

                <div class="form-group">

                    <label>
                        RW
                    </label>

                    <input type="text"
                           name="rw"
                           class="form-control"
                           value="<?= html_escape(tendik_value('rw', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        Nama Dusun
                    </label>

                    <input type="text"
                           name="nama_dusun"
                           class="form-control"
                           value="<?= html_escape(tendik_value('nama_dusun', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        Desa/Kelurahan
                    </label>

                    <input type="text"
                           name="desa_kelurahan"
                           class="form-control"
                           value="<?= html_escape(tendik_value('desa_kelurahan', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        Kecamatan
                    </label>

                    <input type="text"
                           name="kecamatan"
                           class="form-control"
                           value="<?= html_escape(tendik_value('kecamatan', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-3">

                <div class="form-group">

                    <label>
                        Kode Pos
                    </label>

                    <input type="text"
                           name="kode_pos"
                           class="form-control"
                           value="<?= html_escape(tendik_value('kode_pos', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        HP
                    </label>

                    <input type="text"
                           name="hp"
                           class="form-control"
                           value="<?= html_escape(tendik_value('hp', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-5">

                <div class="form-group">

                    <label>
                        Email
                    </label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           value="<?= html_escape(tendik_value('email', $tendik ?? null)) ?>">

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     KEPEGAWAIAN
========================================================== -->

<div class="card shadow mb-4">

    <div class="card-header py-3">

        <h6 class="m-0 font-weight-bold text-primary">

            <i class="fas fa-briefcase mr-1"></i>
            Data Kepegawaian

        </h6>

    </div>


    <div class="card-body">

        <div class="row">

            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Tugas Tambahan
                    </label>

                    <input type="text"
                           name="tugas_tambahan"
                           class="form-control"
                           value="<?= html_escape(tendik_value('tugas_tambahan', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        SK CPNS
                    </label>

                    <input type="text"
                           name="sk_cpns"
                           class="form-control"
                           value="<?= html_escape(tendik_value('sk_cpns', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        Tanggal CPNS
                    </label>

                    <input type="date"
                           name="tanggal_cpns"
                           class="form-control"
                           value="<?= html_escape(tendik_value('tanggal_cpns', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        SK Pengangkatan
                    </label>

                    <input type="text"
                           name="sk_pengangkatan"
                           class="form-control"
                           value="<?= html_escape(tendik_value('sk_pengangkatan', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        TMT Pengangkatan
                    </label>

                    <input type="date"
                           name="tmt_pengangkatan"
                           class="form-control"
                           value="<?= html_escape(tendik_value('tmt_pengangkatan', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Lembaga Pengangkatan
                    </label>

                    <input type="text"
                           name="lembaga_pengangkatan"
                           class="form-control"
                           value="<?= html_escape(tendik_value('lembaga_pengangkatan', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-3">

                <div class="form-group">

                    <label>
                        Pangkat/Golongan
                    </label>

                    <input type="text"
                           name="pangkat_golongan"
                           class="form-control"
                           value="<?= html_escape(tendik_value('pangkat_golongan', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-3">

                <div class="form-group">

                    <label>
                        Sumber Gaji
                    </label>

                    <input type="text"
                           name="sumber_gaji"
                           class="form-control"
                           value="<?= html_escape(tendik_value('sumber_gaji', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        TMT PNS
                    </label>

                    <input type="date"
                           name="tmt_pns"
                           class="form-control"
                           value="<?= html_escape(tendik_value('tmt_pns', $tendik ?? null)) ?>">

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     DATA KELUARGA
========================================================== -->

<div class="card shadow mb-4">

    <div class="card-header py-3">

        <h6 class="m-0 font-weight-bold text-primary">

            <i class="fas fa-users mr-1"></i>
            Data Keluarga

        </h6>

    </div>


    <div class="card-body">

        <div class="row">

            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Nama Ibu Kandung
                    </label>

                    <input type="text"
                           name="nama_ibu_kandung"
                           class="form-control"
                           value="<?= html_escape(tendik_value('nama_ibu_kandung', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Status Perkawinan
                    </label>

                    <input type="text"
                           name="status_perkawinan"
                           class="form-control"
                           value="<?= html_escape(tendik_value('status_perkawinan', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Nama Suami/Istri
                    </label>

                    <input type="text"
                           name="nama_suami_istri"
                           class="form-control"
                           value="<?= html_escape(tendik_value('nama_suami_istri', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        NIP Suami/Istri
                    </label>

                    <input type="text"
                           name="nip_suami_istri"
                           class="form-control"
                           value="<?= html_escape(tendik_value('nip_suami_istri', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-12">

                <div class="form-group">

                    <label>
                        Pekerjaan Suami/Istri
                    </label>

                    <input type="text"
                           name="pekerjaan_suami_istri"
                           class="form-control"
                           value="<?= html_escape(tendik_value('pekerjaan_suami_istri', $tendik ?? null)) ?>">

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     KOMPETENSI
========================================================== -->

<div class="card shadow mb-4">

    <div class="card-header py-3">

        <h6 class="m-0 font-weight-bold text-primary">

            <i class="fas fa-certificate mr-1"></i>
            Kompetensi & Kepengawasan

        </h6>

    </div>


    <div class="card-body">

        <div class="row">

            <?php
            $yes_no_fields = [

                'sudah_lisensi_kepala_sekolah'
                    => 'Sudah Lisensi Kepala Sekolah',

                'pernah_diklat_kepengawasan'
                    => 'Pernah Diklat Kepengawasan',

                'keahlian_braile'
                    => 'Keahlian Braile',

                'keahlian_bahasa_isyarat'
                    => 'Keahlian Bahasa Isyarat'
            ];
            ?>


            <?php foreach ($yes_no_fields as $field => $label): ?>

                <div class="col-md-6">

                    <div class="form-group">

                        <label>
                            <?= $label ?>
                        </label>

                        <select name="<?= $field ?>"
                                class="form-control">

                            <option value="">
                                - Belum Diisi -
                            </option>

                            <option value="Ya"
                                <?= tendik_value($field, $tendik ?? null) == 'Ya'
                                    ? 'selected'
                                    : '' ?>>

                                Ya

                            </option>

                            <option value="Tidak"
                                <?= tendik_value($field, $tendik ?? null) == 'Tidak'
                                    ? 'selected'
                                    : '' ?>>

                                Tidak

                            </option>

                        </select>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</div>


<!-- =========================================================
     DATA IDENTITAS TAMBAHAN
========================================================== -->

<div class="card shadow mb-4">

    <div class="card-header py-3">

        <h6 class="m-0 font-weight-bold text-primary">

            <i class="fas fa-id-card mr-1"></i>
            Identitas & Administrasi

        </h6>

    </div>


    <div class="card-body">

        <div class="row">

            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        NPWP
                    </label>

                    <input type="text"
                           name="npwp"
                           class="form-control"
                           value="<?= html_escape(tendik_value('npwp', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        NIK
                    </label>

                    <input type="text"
                           name="nik"
                           class="form-control"
                           value="<?= html_escape(tendik_value('nik', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        Nomor KK
                    </label>

                    <input type="text"
                           name="nomor_kk"
                           class="form-control"
                           value="<?= html_escape(tendik_value('nomor_kk', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        Karis/Karsu
                    </label>

                    <input type="text"
                           name="karis_karsu"
                           class="form-control"
                           value="<?= html_escape(tendik_value('karis_karsu', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        Kewarganegaraan
                    </label>

                    <input type="text"
                           name="kewarganegaraan"
                           class="form-control"
                           value="<?= html_escape(tendik_value('kewarganegaraan', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-4">

                <div class="form-group">

                    <label>
                        NUKS
                    </label>

                    <input type="text"
                           name="nuks"
                           class="form-control"
                           value="<?= html_escape(tendik_value('nuks', $tendik ?? null)) ?>">

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     KOORDINAT
========================================================== -->

<div class="card shadow mb-4">

    <div class="card-header py-3">

        <h6 class="m-0 font-weight-bold text-primary">

            <i class="fas fa-map mr-1"></i>
            Koordinat

        </h6>

    </div>


    <div class="card-body">

        <div class="row">

            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Lintang
                    </label>

                    <input type="text"
                           name="lintang"
                           class="form-control"
                           value="<?= html_escape(tendik_value('lintang', $tendik ?? null)) ?>">

                </div>

            </div>


            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Bujur
                    </label>

                    <input type="text"
                           name="bujur"
                           class="form-control"
                           value="<?= html_escape(tendik_value('bujur', $tendik ?? null)) ?>">

                </div>

            </div>

        </div>

    </div>

</div>