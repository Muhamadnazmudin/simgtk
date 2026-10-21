<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| DEFAULT DATA
|--------------------------------------------------------------------------
*/

$siswa = isset($siswa)
    ? $siswa
    : null;

$mapel_program = isset($mapel_program)
    ? $mapel_program
    : null;

$mapel_pilihan = isset($mapel_pilihan)
    ? $mapel_pilihan
    : [];


/*
|--------------------------------------------------------------------------
| VALIDASI DATA
|--------------------------------------------------------------------------
*/

if (!$siswa) {

    echo '
        <div class="container-fluid tka-input-page">

            <div class="tka-input-alert tka-input-alert-danger">

                <div class="tka-input-alert-icon">
                    <i class="fas fa-circle-exclamation"></i>
                </div>

                <div>
                    <strong>Data siswa tidak ditemukan</strong>

                    <span>
                        Siswa yang akan diinputkan data TKA
                        tidak dapat ditemukan.
                    </span>
                </div>

            </div>

        </div>
    ';

    return;
}


if (!$mapel_program) {

    echo '
        <div class="container-fluid tka-input-page">

            <div class="tka-input-alert tka-input-alert-danger">

                <div class="tka-input-alert-icon">
                    <i class="fas fa-circle-exclamation"></i>
                </div>

                <div>
                    <strong>Program Keahlian belum tersedia</strong>

                    <span>
                        Mata pelajaran dengan kode Program Keahlian
                        belum tersedia atau belum diaktifkan.
                    </span>
                </div>

            </div>

        </div>
    ';

    return;
}


/*
|--------------------------------------------------------------------------
| DATA SISWA
|--------------------------------------------------------------------------
*/

$siswa_id = isset($siswa->id)
    ? (int) $siswa->id
    : 0;

$tahun_id = isset($siswa->tahun_id)
    ? (int) $siswa->tahun_id
    : 0;

$nama = isset($siswa->nama)
    ? $siswa->nama
    : '-';

$nisn = isset($siswa->nisn)
    ? $siswa->nisn
    : '';

$nama_kelas = isset($siswa->nama_kelas)
    ? $siswa->nama_kelas
    : '-';

$tahun_ajaran = isset($siswa->tahun_ajaran)
    ? $siswa->tahun_ajaran
    : '-';


/*
|--------------------------------------------------------------------------
| MAPEL PROGRAM KEAHLIAN
|--------------------------------------------------------------------------
*/

$mapel_1_id = isset($mapel_program->id)
    ? (int) $mapel_program->id
    : 0;

$mapel_1_nama = isset($mapel_program->nama_mapel)
    ? $mapel_program->nama_mapel
    : 'Belum tersedia';

?>

<style>

/* =========================================================
   TKA ADMIN INPUT
========================================================= */

.tka-input-page {
    padding: 18px 0 40px;
    color: #1f2937;
}


/* =========================================================
   MAIN CARD
========================================================= */

.tka-input-card {
    width: 100%;
    max-width: 900px;

    margin: 0 auto;

    overflow: hidden;

    border: 0;
    border-radius: 22px;

    background: #ffffff;

    box-shadow:
        0 12px 35px rgba(15,23,42,.07);
}


/* =========================================================
   HEADER
========================================================= */

.tka-input-header {
    position: relative;
    overflow: hidden;

    padding: 28px 30px;

    background:
        linear-gradient(
            135deg,
            #4f9cf9 0%,
            #3b82f6 100%
        );

    color: #ffffff;
}

.tka-input-header::before {
    content: "";

    position: absolute;

    width: 190px;
    height: 190px;

    top: -100px;
    right: -55px;

    border-radius: 50%;

    background: rgba(255,255,255,.10);
}

.tka-input-header::after {
    content: "";

    position: absolute;

    width: 120px;
    height: 120px;

    right: 110px;
    bottom: -80px;

    border-radius: 50%;

    background: rgba(255,255,255,.07);
}

.tka-input-header-content {
    position: relative;
    z-index: 2;

    display: flex;
    align-items: center;

    gap: 15px;
}

.tka-input-header-icon {
    width: 52px;
    height: 52px;

    flex: 0 0 auto;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 15px;

    background: rgba(255,255,255,.16);

    font-size: 20px;
}

.tka-input-header-title {
    margin: 0;

    color: #ffffff;

    font-size: 1.28rem;
    font-weight: 700;
}

.tka-input-header-description {
    margin: 6px 0 0;

    color: rgba(255,255,255,.88);

    font-size: .83rem;
    line-height: 1.5;
}


/* =========================================================
   BODY
========================================================= */

.tka-input-body {
    padding: 28px 30px 32px;
}


/* =========================================================
   ALERT
========================================================= */

.tka-input-alert {
    display: flex;
    align-items: flex-start;

    gap: 12px;

    max-width: 900px;

    margin: 0 auto;

    padding: 16px 18px;

    border-radius: 14px;

    font-size: .80rem;
    line-height: 1.5;
}

.tka-input-alert-icon {
    width: 34px;
    height: 34px;

    flex: 0 0 auto;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    font-size: 15px;
}

.tka-input-alert strong {
    display: block;

    margin-bottom: 2px;

    font-size: .82rem;
}

.tka-input-alert span {
    display: block;
}

.tka-input-alert-danger {
    border: 1px solid #fecaca;

    background: #fef2f2;

    color: #991b1b;
}

.tka-input-alert-danger .tka-input-alert-icon {
    background: #fee2e2;

    color: #dc2626;
}


/* =========================================================
   STUDENT INFORMATION
========================================================= */

.tka-student-info {
    display: grid;

    grid-template-columns:
        1.5fr
        1fr
        1fr;

    gap: 12px;

    margin-bottom: 25px;
}

.tka-student-item {
    padding: 15px 16px;

    border: 1px solid #e5eaf1;
    border-radius: 14px;

    background: #f8fafc;
}

.tka-student-label {
    display: block;

    margin-bottom: 5px;

    color: #6b7280;

    font-size: .69rem;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .3px;
}

.tka-student-value {
    display: block;

    color: #1f2937;

    font-size: .86rem;
    font-weight: 700;

    line-height: 1.4;
}

.tka-student-sub {
    display: block;

    margin-top: 3px;

    color: #94a3b8;

    font-size: .68rem;
}


/* =========================================================
   INFO
========================================================= */

.tka-input-info {
    display: flex;
    align-items: flex-start;

    gap: 12px;

    margin-bottom: 22px;

    padding: 15px 17px;

    border: 1px solid #dbeafe;
    border-radius: 14px;

    background: #eff6ff;

    color: #1e40af;

    font-size: .80rem;
    line-height: 1.55;
}

.tka-input-info-icon {
    width: 32px;
    height: 32px;

    flex: 0 0 auto;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #dbeafe;

    color: #2563eb;
}

.tka-input-info-content strong {
    display: inline;

    font-weight: 700;
}


/* =========================================================
   SECTION
========================================================= */

.tka-section {
    margin-top: 5px;
}

.tka-section-title {
    display: flex;
    align-items: center;

    gap: 9px;

    margin-bottom: 6px;

    color: #1f2937;

    font-size: .98rem;
    font-weight: 700;
}

.tka-section-title i {
    color: #3b82f6;
}

.tka-section-description {
    margin-bottom: 20px;

    color: #6b7280;

    font-size: .79rem;
    line-height: 1.6;
}


/* =========================================================
   MAPEL CARD
========================================================= */

.tka-mapel-card {
    padding: 20px;

    border: 1px solid #e5eaf1;
    border-radius: 17px;

    background: #ffffff;
}

.tka-mapel-group {
    margin-bottom: 21px;
}

.tka-mapel-group:last-child {
    margin-bottom: 0;
}


/* =========================================================
   LABEL
========================================================= */

.tka-input-label {
    display: flex;
    align-items: center;

    gap: 8px;

    margin-bottom: 8px;

    color: #374151;

    font-size: .82rem;
    font-weight: 700;
}

.tka-label-number {
    width: 23px;
    height: 23px;

    flex: 0 0 auto;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 7px;

    background: #eaf3ff;

    color: #3b82f6;

    font-size: .69rem;
    font-weight: 700;
}


/* =========================================================
   AUTOMATIC MAPEL
========================================================= */

.tka-auto-mapel {
    display: flex;
    align-items: center;

    gap: 13px;

    min-height: 62px;

    padding: 11px 14px;

    border: 1px solid #dbeafe;
    border-radius: 13px;

    background: #eff6ff;
}

.tka-auto-mapel-icon {
    width: 39px;
    height: 39px;

    flex: 0 0 auto;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #dbeafe;

    color: #3b82f6;

    font-size: 15px;
}

.tka-auto-mapel-content {
    min-width: 0;
}

.tka-auto-mapel-label {
    display: block;

    margin-bottom: 2px;

    color: #64748b;

    font-size: .62rem;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .25px;
}

.tka-auto-mapel-name {
    display: block;

    color: #1e3a8a;

    font-size: .88rem;
    font-weight: 700;

    line-height: 1.4;
}

.tka-auto-badge {
    display: inline-flex;
    align-items: center;

    gap: 4px;

    margin-left: 7px;

    padding: 4px 7px;

    border-radius: 6px;

    background: #dbeafe;

    color: #2563eb;

    font-size: .59rem;
    font-weight: 700;

    vertical-align: middle;
}


/* =========================================================
   SELECT
========================================================= */

.tka-input-select {
    width: 100%;

    min-height: 48px;

    padding: 9px 13px;

    border: 1px solid #dce3ed !important;
    border-radius: 12px !important;

    background: #ffffff !important;
    color: #1f2937 !important;

    font-size: .82rem;

    box-shadow: none !important;

    transition: .2s ease;
}

.tka-input-select:hover {
    border-color: #b9cbea !important;
}

.tka-input-select:focus {
    border-color: #4f9cf9 !important;

    box-shadow:
        0 0 0 3px rgba(79,156,249,.11) !important;
}

.tka-select-help {
    margin-top: 6px;

    color: #94a3b8;

    font-size: .68rem;
}


/* =========================================================
   ACTIONS
========================================================= */

.tka-input-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 10px;

    margin-top: 24px;
    padding-top: 20px;

    border-top: 1px solid #edf0f5;
}

.tka-input-action-left,
.tka-input-action-right {
    display: flex;
    align-items: center;

    gap: 9px;
}

.tka-back-btn,
.tka-save-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 7px;

    min-height: 43px;

    padding: 9px 15px;

    border-radius: 11px;

    font-size: .78rem;
    font-weight: 700;

    transition: .2s ease;
}

.tka-back-btn {
    border: 1px solid #dce3ed;

    background: #ffffff;

    color: #475569 !important;
}

.tka-back-btn:hover {
    background: #f8fafc;

    color: #1f2937 !important;
}

.tka-save-btn {
    border: 0;

    background:
        linear-gradient(
            135deg,
            #16a34a,
            #15803d
        );

    color: #ffffff !important;

    box-shadow:
        0 6px 15px rgba(22,163,74,.16);
}

.tka-save-btn:hover {
    transform: translateY(-1px);

    box-shadow:
        0 8px 18px rgba(22,163,74,.22);

    color: #ffffff !important;
}


/* =========================================================
   DARK MODE
========================================================= */

body.dark-mode .tka-input-page {
    color: #e5e7eb;
}

body.dark-mode .tka-input-card {
    background: #202333 !important;

    box-shadow:
        0 12px 35px rgba(0,0,0,.22);
}

body.dark-mode .tka-input-body {
    background: #202333;
}

body.dark-mode .tka-student-item {
    background: #292d40;
    border-color: #3c4255;
}

body.dark-mode .tka-student-label {
    color: #9ca7ba;
}

body.dark-mode .tka-student-value {
    color: #f3f4f6;
}

body.dark-mode .tka-student-sub {
    color: #7f899d;
}

body.dark-mode .tka-input-info {
    background: #263650;
    border-color: #354b6b;
    color: #bfdbfe;
}

body.dark-mode .tka-input-info-icon {
    background: #304a70;
    color: #7eb4ff;
}

body.dark-mode .tka-section-title {
    color: #f3f4f6;
}

body.dark-mode .tka-section-description {
    color: #aeb6c7;
}

body.dark-mode .tka-mapel-card {
    background: #202333;
    border-color: #3c4255;
}

body.dark-mode .tka-input-label {
    color: #f1f5f9;
}

body.dark-mode .tka-label-number {
    background: #293f63;
    color: #7eb4ff;
}

body.dark-mode .tka-auto-mapel {
    background: #263650;
    border-color: #354b6b;
}

body.dark-mode .tka-auto-mapel-icon {
    background: #304a70;
    color: #7eb4ff;
}

body.dark-mode .tka-auto-mapel-label {
    color: #9ca7ba;
}

body.dark-mode .tka-auto-mapel-name {
    color: #bfdbfe;
}

body.dark-mode .tka-auto-badge {
    background: #304a70;
    color: #8bbcff;
}

body.dark-mode .tka-input-select {
    background: #292d40 !important;
    border-color: #454b61 !important;
    color: #f3f4f6 !important;
}

body.dark-mode .tka-input-select option {
    background: #292d40;
    color: #ffffff;
}

body.dark-mode .tka-select-help {
    color: #7f899d;
}

body.dark-mode .tka-input-actions {
    border-color: #3c4255;
}

body.dark-mode .tka-back-btn {
    background: #292d40;
    border-color: #4a5064;
    color: #d1d5db !important;
}

body.dark-mode .tka-back-btn:hover {
    background: #32374a;
    color: #ffffff !important;
}

body.dark-mode .tka-input-alert-danger {
    background: #402626;
    border-color: #633535;
    color: #fecaca;
}

body.dark-mode .tka-input-alert-danger .tka-input-alert-icon {
    background: #523030;
    color: #ff8a8a;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    .tka-input-page {
        padding: 12px 0 30px;
    }

    .tka-input-card {
        border-radius: 18px;
    }

    .tka-input-header {
        padding: 23px 20px;
    }

    .tka-input-header-icon {
        width: 46px;
        height: 46px;

        border-radius: 13px;

        font-size: 18px;
    }

    .tka-input-header-title {
        font-size: 1.12rem;
    }

    .tka-input-header-description {
        font-size: .77rem;
    }

    .tka-input-body {
        padding: 20px 17px 25px;
    }

    .tka-student-info {
        grid-template-columns: 1fr;

        gap: 9px;

        margin-bottom: 20px;
    }

    .tka-mapel-card {
        padding: 16px;
    }

    .tka-input-info {
        padding: 13px 14px;
    }

    .tka-input-actions {
        flex-direction: column-reverse;

        align-items: stretch;
    }

    .tka-input-action-left,
    .tka-input-action-right {
        width: 100%;
    }

    .tka-back-btn,
    .tka-save-btn {
        width: 100%;
    }

}


@media (max-width: 480px) {

    .tka-input-header-content {
        gap: 11px;
    }

    .tka-auto-mapel {
        align-items: flex-start;
    }

    .tka-auto-badge {
        margin-left: 0;
        margin-top: 5px;
    }

}

</style>


<div class="container-fluid tka-input-page">

    <div class="card tka-input-card">


        <!-- =====================================================
             HEADER
        ====================================================== -->

        <div class="tka-input-header">

            <div class="tka-input-header-content">

                <div class="tka-input-header-icon">

                    <i class="fas fa-user-plus"></i>

                </div>


                <div>

                    <h4 class="tka-input-header-title">
                        Input Pilihan TKA
                    </h4>

                    <p class="tka-input-header-description">
                        Tambahkan data pilihan mata pelajaran
                        TKA untuk siswa yang belum mengisi.
                    </p>

                </div>

            </div>

        </div>


        <!-- =====================================================
             BODY
        ====================================================== -->

        <div class="tka-input-body">


            <!-- =================================================
                 DATA SISWA
            ================================================== -->

            <div class="tka-student-info">


                <!-- NAMA -->

                <div class="tka-student-item">

                    <span class="tka-student-label">
                        Nama Siswa
                    </span>

                    <span class="tka-student-value">

                        <?= html_escape($nama); ?>

                    </span>


                    <?php if ($nisn !== ''): ?>

                        <span class="tka-student-sub">

                            NISN:
                            <?= html_escape($nisn); ?>

                        </span>

                    <?php endif; ?>

                </div>


                <!-- KELAS -->

                <div class="tka-student-item">

                    <span class="tka-student-label">
                        Kelas
                    </span>

                    <span class="tka-student-value">

                        <?= html_escape(
                            $nama_kelas
                        ); ?>

                    </span>

                </div>


                <!-- TAHUN -->

                <div class="tka-student-item">

                    <span class="tka-student-label">
                        Tahun Ajaran
                    </span>

                    <span class="tka-student-value">

                        <?= html_escape(
                            $tahun_ajaran
                        ); ?>

                    </span>

                </div>


            </div>


            <!-- =================================================
                 INFO
            ================================================== -->

            <div class="tka-input-info">

                <div class="tka-input-info-icon">

                    <i class="fas fa-circle-info"></i>

                </div>


                <div class="tka-input-info-content">

                    <strong>Panduan pengisian.</strong>

                    Pilihan 1 otomatis menggunakan
                    <strong>Program Keahlian</strong>
                    siswa.
                    Admin hanya perlu menentukan
                    <strong>Pilihan 2</strong>
                    dari mata pelajaran TKA yang tersedia.

                </div>

            </div>


            <!-- =================================================
                 SECTION
            ================================================== -->

            <div class="tka-section">

                <div class="tka-section-title">

                    <i class="fas fa-list-check"></i>

                    Pengaturan Mata Pelajaran

                </div>


                <div class="tka-section-description">

                    Pastikan pilihan kedua sesuai dengan
                    pilihan yang akan digunakan siswa sebelum
                    data disimpan.

                </div>


                <!-- =================================================
                     FORM
                ================================================== -->

                <form
                    id="tkaInputForm"
                    method="post"
                    action="<?= site_url('Tka_admin/store'); ?>"
                >


                    <!-- CSRF -->

                    <input
                        type="hidden"
                        name="<?= $this->security->get_csrf_token_name(); ?>"
                        value="<?= $this->security->get_csrf_hash(); ?>"
                    >


                    <!-- SISWA -->

                    <input
                        type="hidden"
                        name="siswa_id"
                        value="<?= $siswa_id; ?>"
                    >


                    <!-- TAHUN -->

                    <input
                        type="hidden"
                        name="tahun_id"
                        value="<?= $tahun_id; ?>"
                    >


                    <!-- PILIHAN 1 -->

                    <input
                        type="hidden"
                        name="mapel_1_id"
                        value="<?= $mapel_1_id; ?>"
                    >


                    <!-- =================================================
                         MAPEL CARD
                    ================================================== -->

                    <div class="tka-mapel-card">


                        <!-- =================================================
                             PILIHAN 1
                        ================================================== -->

                        <div class="tka-mapel-group">

                            <label class="tka-input-label">

                                <span class="tka-label-number">
                                    1
                                </span>

                                Mata Pelajaran Pilihan 1

                            </label>


                            <div class="tka-auto-mapel">


                                <div class="tka-auto-mapel-icon">

                                    <i class="fas fa-graduation-cap"></i>

                                </div>


                                <div class="tka-auto-mapel-content">

                                    <span class="tka-auto-mapel-label">

                                        Program Keahlian · Otomatis

                                    </span>


                                    <span class="tka-auto-mapel-name">

                                        <?= html_escape(
                                            $mapel_1_nama
                                        ); ?>


                                        <span class="tka-auto-badge">

                                            <i class="fas fa-lock"></i>

                                            Terkunci

                                        </span>

                                    </span>

                                </div>


                            </div>

                        </div>


                        <!-- =================================================
                             PILIHAN 2
                        ================================================== -->

                        <div class="tka-mapel-group">

                            <label
                                for="mapel_2_id"
                                class="tka-input-label"
                            >

                                <span class="tka-label-number">
                                    2
                                </span>

                                Mata Pelajaran Pilihan 2

                            </label>


                            <select
                                name="mapel_2_id"
                                id="mapel_2_id"
                                class="form-control tka-input-select"
                                required
                            >

                                <option value="">
                                    -- Pilih Mata Pelajaran --
                                </option>


                                <?php foreach (
                                    $mapel_pilihan
                                    as $m
                                ): ?>

                                    <?php
                                    $option_id = (int) $m->id;
                                    ?>


                                    <option
                                        value="<?= $option_id; ?>"
                                    >

                                        <?= html_escape(
                                            $m->nama_mapel
                                        ); ?>

                                    </option>


                                <?php endforeach; ?>


                            </select>


                            <div class="tka-select-help">

                                <i class="fas fa-circle-info mr-1"></i>

                                Program Keahlian tidak ditampilkan
                                sebagai pilihan kedua.

                            </div>

                        </div>


                    </div>


                    <!-- =================================================
                         ACTION
                    ================================================== -->

                    <div class="tka-input-actions">


                        <div class="tka-input-action-left">

                            <a
                                href="<?= site_url('Tka_admin'); ?>"
                                class="btn tka-back-btn"
                            >

                                <i class="fas fa-arrow-left"></i>

                                Kembali

                            </a>

                        </div>


                        <div class="tka-input-action-right">

                            <button
                                type="submit"
                                class="btn tka-save-btn"
                                id="tkaInputSubmit"
                            >

                                <i class="fas fa-save"></i>

                                Simpan Pilihan TKA

                            </button>

                        </div>


                    </div>


                </form>

            </div>

        </div>

    </div>

</div>


<script>

(function () {

    'use strict';


    var form = document.getElementById(
        'tkaInputForm'
    );

    var select = document.getElementById(
        'mapel_2_id'
    );

    var submit = document.getElementById(
        'tkaInputSubmit'
    );


    if (!form || !select || !submit) {
        return;
    }


    form.addEventListener(
        'submit',
        function (event) {

            /*
             * Validasi HTML5
             */

            if (!form.checkValidity()) {

                event.preventDefault();

                form.reportValidity();

                return;
            }


            /*
             * Pastikan pilihan 2 benar-benar dipilih.
             */

            if (!select.value) {

                event.preventDefault();

                alert(
                    'Silakan pilih mata pelajaran pilihan kedua.'
                );

                select.focus();

                return;
            }


            /*
             * Cegah double submit.
             */

            submit.disabled = true;

            submit.innerHTML =
                '<i class="fas fa-spinner fa-spin"></i>' +
                ' Menyimpan...';

        }
    );


})();

</script>