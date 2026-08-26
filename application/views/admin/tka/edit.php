<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| DEFAULT DATA
|--------------------------------------------------------------------------
*/

$tka   = isset($tka) ? $tka : null;
$mapel = isset($mapel) ? $mapel : [];

/*
|--------------------------------------------------------------------------
| VALIDASI DATA
|--------------------------------------------------------------------------
*/

if (!$tka) {
    echo '
        <div class="container-fluid py-4">
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle mr-2"></i>
                Data TKA tidak ditemukan.
            </div>
        </div>
    ';
    return;
}

/*
|--------------------------------------------------------------------------
| DATA
|--------------------------------------------------------------------------
*/

$tka_id       = isset($tka->id) ? (int) $tka->id : 0;
$siswa_id     = isset($tka->siswa_id) ? (int) $tka->siswa_id : 0;

$nama         = isset($tka->nama)
    ? $tka->nama
    : '-';

$nisn         = isset($tka->nisn)
    ? $tka->nisn
    : '-';

$nama_kelas   = isset($tka->nama_kelas)
    ? $tka->nama_kelas
    : '-';

$tahun_ajaran = isset($tka->tahun_ajaran)
    ? $tka->tahun_ajaran
    : '-';

$mapel_1_id   = isset($tka->mapel_1_id)
    ? (int) $tka->mapel_1_id
    : 0;

$mapel_2_id   = isset($tka->mapel_2_id)
    ? (int) $tka->mapel_2_id
    : 0;

$mapel_1      = isset($tka->mapel_1)
    ? $tka->mapel_1
    : 'Belum tersedia';

$mapel_2      = isset($tka->mapel_2)
    ? $tka->mapel_2
    : '';

$kode_mapel_1 = isset($tka->kode_mapel_1)
    ? strtoupper(trim($tka->kode_mapel_1))
    : 'PK';

?>

<style>

/* =========================================================
   TKA ADMIN EDIT
========================================================= */

.tka-edit-page {
    padding: 18px 0 40px;
    color: #1f2937;
}


/* =========================================================
   MAIN CARD
========================================================= */

.tka-edit-card {
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

.tka-edit-header {
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

.tka-edit-header::before {
    content: "";

    position: absolute;

    width: 190px;
    height: 190px;

    top: -100px;
    right: -55px;

    border-radius: 50%;

    background: rgba(255,255,255,.10);
}

.tka-edit-header::after {
    content: "";

    position: absolute;

    width: 120px;
    height: 120px;

    right: 110px;
    bottom: -80px;

    border-radius: 50%;

    background: rgba(255,255,255,.07);
}

.tka-edit-header-content {
    position: relative;
    z-index: 2;

    display: flex;
    align-items: center;

    gap: 15px;
}

.tka-edit-header-icon {
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

.tka-edit-header-title {
    margin: 0;

    color: #ffffff;

    font-size: 1.28rem;
    font-weight: 700;
}

.tka-edit-header-description {
    margin: 6px 0 0;

    color: rgba(255,255,255,.88);

    font-size: .83rem;
    line-height: 1.5;
}


/* =========================================================
   BODY
========================================================= */

.tka-edit-body {
    padding: 28px 30px 32px;
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
   SECTION TITLE
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
   LOCKED NOTICE
========================================================= */

.tka-warning {
    display: flex;
    align-items: flex-start;

    gap: 12px;

    margin-bottom: 22px;
    padding: 15px 17px;

    border: 1px solid #fed7aa;
    border-radius: 14px;

    background: #fff7ed;

    color: #9a3412;

    font-size: .80rem;
    line-height: 1.55;
}

.tka-warning-icon {
    width: 32px;
    height: 32px;

    flex: 0 0 auto;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #ffedd5;

    color: #ea580c;
}

.tka-warning-content strong {
    display: block;

    margin-bottom: 2px;

    font-weight: 700;
}


/* =========================================================
   MAPEL FORM CARD
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

.tka-edit-label {
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

.tka-edit-select {
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

.tka-edit-select:hover {
    border-color: #b9cbea !important;
}

.tka-edit-select:focus {
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

.tka-edit-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 10px;

    margin-top: 24px;
    padding-top: 20px;

    border-top: 1px solid #edf0f5;
}

.tka-edit-action-left,
.tka-edit-action-right {
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
            #4f9cf9,
            #3b82f6
        );

    color: #ffffff !important;

    box-shadow:
        0 6px 15px rgba(59,130,246,.18);
}

.tka-save-btn:hover {
    transform: translateY(-1px);

    box-shadow:
        0 8px 18px rgba(59,130,246,.24);
}


/* =========================================================
   CONFIRM MODAL
========================================================= */

.tka-confirm-overlay {
    position: fixed;

    inset: 0;

    z-index: 99999;

    display: none;
    align-items: center;
    justify-content: center;

    padding: 20px;

    background: rgba(15,23,42,.60);

    backdrop-filter: blur(4px);
}

.tka-confirm-overlay.show {
    display: flex;
}

.tka-confirm-box {
    width: 100%;
    max-width: 470px;

    padding: 26px;

    border-radius: 21px;

    background: #ffffff;

    box-shadow:
        0 20px 60px rgba(0,0,0,.20);

    animation:
        tkaConfirmIn .18s ease-out;
}

@keyframes tkaConfirmIn {

    from {
        opacity: 0;
        transform:
            translateY(12px)
            scale(.97);
    }

    to {
        opacity: 1;
        transform:
            translateY(0)
            scale(1);
    }

}

.tka-confirm-icon {
    width: 55px;
    height: 55px;

    margin: 0 auto 15px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 16px;

    background: #eaf3ff;

    color: #3b82f6;

    font-size: 22px;
}

.tka-confirm-title {
    margin: 0;

    text-align: center;

    color: #1f2937;

    font-size: 1.08rem;
    font-weight: 700;
}

.tka-confirm-text {
    margin: 8px 0 17px;

    text-align: center;

    color: #6b7280;

    font-size: .80rem;
    line-height: 1.6;
}

.tka-confirm-item {
    padding: 12px 14px;

    margin-bottom: 8px;

    border: 1px solid #e5eaf1;
    border-radius: 11px;

    background: #f8fafc;

    font-size: .80rem;
}

.tka-confirm-label {
    display: block;

    margin-bottom: 3px;

    color: #94a3b8;

    font-size: .63rem;
    font-weight: 700;

    text-transform: uppercase;
}

.tka-confirm-value {
    color: #1f2937;

    font-weight: 700;
}

.tka-confirm-warning {
    margin-top: 13px;

    padding: 11px 13px;

    border: 1px solid #fed7aa;
    border-radius: 10px;

    background: #fff7ed;

    color: #9a3412;

    font-size: .72rem;
    line-height: 1.5;
}

.tka-confirm-actions {
    display: flex;

    gap: 9px;

    margin-top: 19px;
}

.tka-confirm-cancel,
.tka-confirm-save {
    flex: 1;

    min-height: 43px;

    border-radius: 10px;

    font-size: .77rem;
    font-weight: 700;

    transition: .2s ease;
}

.tka-confirm-cancel {
    border: 1px solid #dce3ed;

    background: #ffffff;

    color: #475569;
}

.tka-confirm-cancel:hover {
    background: #f8fafc;
}

.tka-confirm-save {
    border: 0;

    background: #3b82f6;

    color: #ffffff;
}

.tka-confirm-save:hover {
    background: #2563eb;
}


/* =========================================================
   DARK MODE
========================================================= */

body.dark-mode .tka-edit-page {
    color: #e5e7eb;
}

body.dark-mode .tka-edit-card {
    background: #202333 !important;

    box-shadow:
        0 12px 35px rgba(0,0,0,.22);
}

body.dark-mode .tka-edit-body {
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

body.dark-mode .tka-section-title {
    color: #f3f4f6;
}

body.dark-mode .tka-section-description {
    color: #aeb6c7;
}

body.dark-mode .tka-warning {
    background: #402c1f;
    border-color: #68442d;
    color: #fed7aa;
}

body.dark-mode .tka-warning-icon {
    background: #553821;
    color: #ffb77d;
}

body.dark-mode .tka-mapel-card {
    background: #202333;
    border-color: #3c4255;
}

body.dark-mode .tka-edit-label {
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

body.dark-mode .tka-edit-select {
    background: #292d40 !important;
    border-color: #454b61 !important;
    color: #f3f4f6 !important;
}

body.dark-mode .tka-edit-select option {
    background: #292d40;
    color: #ffffff;
}

body.dark-mode .tka-select-help {
    color: #7f899d;
}

body.dark-mode .tka-edit-actions {
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

body.dark-mode .tka-confirm-box {
    background: #202333;
}

body.dark-mode .tka-confirm-title {
    color: #f3f4f6;
}

body.dark-mode .tka-confirm-text {
    color: #aeb6c7;
}

body.dark-mode .tka-confirm-item {
    background: #292d40;
    border-color: #3c4255;
}

body.dark-mode .tka-confirm-label {
    color: #7f899d;
}

body.dark-mode .tka-confirm-value {
    color: #f3f4f6;
}

body.dark-mode .tka-confirm-warning {
    background: #402c1f;
    border-color: #68442d;
    color: #fed7aa;
}

body.dark-mode .tka-confirm-cancel {
    background: #292d40;
    border-color: #4a5064;
    color: #d1d5db;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    .tka-edit-page {
        padding: 12px 0 30px;
    }

    .tka-edit-card {
        border-radius: 18px;
    }

    .tka-edit-header {
        padding: 23px 20px;
    }

    .tka-edit-header-icon {
        width: 46px;
        height: 46px;

        border-radius: 13px;

        font-size: 18px;
    }

    .tka-edit-header-title {
        font-size: 1.12rem;
    }

    .tka-edit-header-description {
        font-size: .77rem;
    }

    .tka-edit-body {
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

    .tka-warning {
        padding: 13px 14px;
    }

    .tka-edit-actions {
        flex-direction: column-reverse;

        align-items: stretch;
    }

    .tka-edit-action-left,
    .tka-edit-action-right {
        width: 100%;
    }

    .tka-back-btn,
    .tka-save-btn {
        width: 100%;
    }

    .tka-confirm-box {
        padding: 22px 17px;

        border-radius: 18px;
    }

    .tka-confirm-actions {
        flex-direction: column-reverse;
    }

    .tka-confirm-cancel,
    .tka-confirm-save {
        width: 100%;
    }
}

</style>


<div class="container-fluid tka-edit-page">

    <div class="card tka-edit-card">


        <!-- =====================================================
             HEADER
        ====================================================== -->

        <div class="tka-edit-header">

            <div class="tka-edit-header-content">

                <div class="tka-edit-header-icon">

                    <i class="fas fa-user-edit"></i>

                </div>


                <div>

                    <h4 class="tka-edit-header-title">
                        Edit Pilihan TKA
                    </h4>

                    <p class="tka-edit-header-description">
                        Admin dapat memperbarui pilihan mata pelajaran
                        TKA siswa yang sudah tersimpan.
                    </p>

                </div>

            </div>

        </div>


        <!-- =====================================================
             BODY
        ====================================================== -->

        <div class="tka-edit-body">


            <!-- =================================================
                 DATA SISWA
            ================================================== -->

            <div class="tka-student-info">


                <div class="tka-student-item">

                    <span class="tka-student-label">
                        Nama Siswa
                    </span>

                    <span class="tka-student-value">

                        <?= html_escape($nama); ?>

                    </span>

                    <?php if ($nisn !== '-'): ?>

                        <span class="tka-student-sub">

                            NISN:
                            <?= html_escape($nisn); ?>

                        </span>

                    <?php endif; ?>

                </div>


                <div class="tka-student-item">

                    <span class="tka-student-label">
                        Kelas
                    </span>

                    <span class="tka-student-value">

                        <?= html_escape($nama_kelas); ?>

                    </span>

                </div>


                <div class="tka-student-item">

                    <span class="tka-student-label">
                        Tahun Ajaran
                    </span>

                    <span class="tka-student-value">

                        <?= html_escape($tahun_ajaran); ?>

                    </span>

                </div>


            </div>


            <!-- =================================================
                 WARNING
            ================================================== -->

            <div class="tka-warning">

                <div class="tka-warning-icon">

                    <i class="fas fa-lock"></i>

                </div>


                <div class="tka-warning-content">

                    <strong>
                        Data TKA sudah tersimpan
                    </strong>

                    Perubahan dari halaman admin akan langsung
                    mengganti pilihan TKA siswa yang tersimpan.
                    Pastikan pilihan mata pelajaran sudah benar
                    sebelum menyimpan.

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

                    Pilihan pertama merupakan mata pelajaran
                    <strong>Program Keahlian</strong> dan ditentukan
                    otomatis dari data TKA. Admin hanya mengubah
                    <strong>Pilihan 2</strong>.

                </div>


                <!-- =================================================
                     FORM
                ================================================== -->

                <form
                    id="tkaEditForm"
                    method="post"
                    action="<?= site_url('Tka_admin/update'); ?>"
                >


                    <!-- CSRF -->

                    <input
                        type="hidden"
                        name="<?= $this->security->get_csrf_token_name(); ?>"
                        value="<?= $this->security->get_csrf_hash(); ?>"
                    >


                    <!-- TKA ID -->

                    <input
                        type="hidden"
                        name="id"
                        value="<?= $tka_id; ?>"
                    >


                    <!-- =================================================
                         MAPEL CARD
                    ================================================== -->

                    <div class="tka-mapel-card">


                        <!-- =================================================
                             PILIHAN 1
                        ================================================== -->

                        <div class="tka-mapel-group">

                            <label class="tka-edit-label">

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
                                            $mapel_1
                                        ); ?>

                                        <span class="tka-auto-badge">

                                            <i class="fas fa-lock"></i>

                                            Terkunci

                                        </span>

                                    </span>

                                </div>


                            </div>


                            <!--
                            Tetap dikirim ke controller.
                            Admin tidak perlu mengubahnya.
                            -->

                            <input
                                type="hidden"
                                name="mapel_1_id"
                                value="<?= $mapel_1_id; ?>"
                            >

                        </div>


                        <!-- =================================================
                             PILIHAN 2
                        ================================================== -->

                        <div class="tka-mapel-group">

                            <label
                                for="mapel_2_id"
                                class="tka-edit-label"
                            >

                                <span class="tka-label-number">
                                    2
                                </span>

                                Mata Pelajaran Pilihan 2

                            </label>


                            <select
                                name="mapel_2_id"
                                id="mapel_2_id"
                                class="form-control tka-edit-select"
                                required
                            >

                                <option value="">
                                    -- Pilih Mata Pelajaran --
                                </option>


                                <?php foreach (
                                    $mapel
                                    as $m
                                ): ?>

                                    <?php
                                    $option_id = (int) $m->id;

                                    /*
                                     * Jangan tampilkan
                                     * mapel yang sama dengan
                                     * pilihan 1.
                                     */
                                    if (
                                        $option_id ===
                                        $mapel_1_id
                                    ) {
                                        continue;
                                    }
                                    ?>


                                    <option
                                        value="<?= $option_id; ?>"
                                        <?= (
                                            $mapel_2_id ===
                                            $option_id
                                        )
                                            ? 'selected'
                                            : ''; ?>
                                    >

                                        <?= html_escape(
                                            $m->nama_mapel
                                        ); ?>

                                    </option>


                                <?php endforeach; ?>

                            </select>


                            <div class="tka-select-help">

                                <i class="fas fa-circle-info mr-1"></i>

                                Pilihan 2 tidak boleh sama dengan
                                mata pelajaran Program Keahlian.

                            </div>

                        </div>


                    </div>


                    <!-- =================================================
                         ACTION
                    ================================================== -->

                    <div class="tka-edit-actions">


                        <div class="tka-edit-action-left">

                            <a
                                href="<?= site_url('Tka_admin'); ?>"
                                class="btn tka-back-btn"
                            >

                                <i class="fas fa-arrow-left"></i>

                                Kembali

                            </a>

                        </div>


                        <div class="tka-edit-action-right">

                            <button
                                type="submit"
                                id="tkaOpenConfirm"
                                class="btn tka-save-btn"
                            >

                                <i class="fas fa-save"></i>

                                Simpan Perubahan

                            </button>

                        </div>


                    </div>


                </form>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     CONFIRM MODAL
========================================================= -->

<div
    id="tkaConfirmOverlay"
    class="tka-confirm-overlay"
>

    <div
        class="tka-confirm-box"
        role="dialog"
        aria-modal="true"
        aria-labelledby="tkaConfirmTitle"
    >


        <div class="tka-confirm-icon">

            <i class="fas fa-circle-question"></i>

        </div>


        <h5
            id="tkaConfirmTitle"
            class="tka-confirm-title"
        >

            Simpan perubahan TKA?

        </h5>


        <p class="tka-confirm-text">

            Periksa kembali mata pelajaran pilihan siswa
            sebelum melanjutkan.

        </p>


        <!-- PILIHAN 1 -->

        <div class="tka-confirm-item">

            <span class="tka-confirm-label">
                Pilihan 1 · Program Keahlian
            </span>

            <span
                id="confirmMapel1"
                class="tka-confirm-value"
            >

                <?= html_escape($mapel_1); ?>

            </span>

        </div>


        <!-- PILIHAN 2 -->

        <div class="tka-confirm-item">

            <span class="tka-confirm-label">
                Pilihan 2 · Pilihan Siswa
            </span>

            <span
                id="confirmMapel2"
                class="tka-confirm-value"
            >

                <?= html_escape(
                    $mapel_2 !== ''
                        ? $mapel_2
                        : '-'
                ); ?>

            </span>

        </div>


        <div class="tka-confirm-warning">

            <i class="fas fa-triangle-exclamation mr-1"></i>

            Perubahan ini akan mengganti data TKA yang
            tersimpan untuk siswa tersebut.

        </div>


        <!-- ACTION -->

        <div class="tka-confirm-actions">

            <button
                type="button"
                id="tkaCancelConfirm"
                class="tka-confirm-cancel"
            >

                <i class="fas fa-arrow-left mr-1"></i>

                Batal

            </button>


            <button
                type="button"
                id="tkaSaveConfirm"
                class="tka-confirm-save"
            >

                <i class="fas fa-save mr-1"></i>

                Ya, Simpan

            </button>

        </div>


    </div>

</div>


<script>

(function () {

    'use strict';


    /*
    |--------------------------------------------------------------------------
    | ELEMENT
    |--------------------------------------------------------------------------
    */

    var form = document.getElementById(
        'tkaEditForm'
    );

    var openConfirm = document.getElementById(
        'tkaOpenConfirm'
    );

    var overlay = document.getElementById(
        'tkaConfirmOverlay'
    );

    var cancelConfirm = document.getElementById(
        'tkaCancelConfirm'
    );

    var saveConfirm = document.getElementById(
        'tkaSaveConfirm'
    );

    var mapel2 = document.getElementById(
        'mapel_2_id'
    );

    var confirmMapel2 = document.getElementById(
        'confirmMapel2'
    );


    /*
    |--------------------------------------------------------------------------
    | CEK ELEMEN
    |--------------------------------------------------------------------------
    */

    if (
        !form ||
        !openConfirm ||
        !overlay ||
        !cancelConfirm ||
        !saveConfirm ||
        !mapel2
    ) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | BUKA MODAL
    |--------------------------------------------------------------------------
    */

    form.addEventListener(
        'submit',
        function (event) {

            event.preventDefault();


            /*
             * Validasi HTML5
             */

            if (!form.checkValidity()) {

                form.reportValidity();

                return;
            }


            /*
             * Ambil pilihan
             */

            var selectedValue =
                mapel2.value;


            /*
             * Pastikan tidak kosong
             */

            if (!selectedValue) {

                alert(
                    'Silakan pilih mata pelajaran pilihan kedua.'
                );

                mapel2.focus();

                return;
            }


            /*
             * Ambil nama mapel
             */

            var selectedOption =
                mapel2.options[
                    mapel2.selectedIndex
                ];


            if (
                selectedOption &&
                confirmMapel2
            ) {

                confirmMapel2.textContent =
                    selectedOption.text.trim();

            }


            /*
             * Tampilkan modal
             */

            overlay.classList.add(
                'show'
            );

            document.body.style.overflow =
                'hidden';

        }
    );


    /*
    |--------------------------------------------------------------------------
    | BATAL
    |--------------------------------------------------------------------------
    */

    cancelConfirm.addEventListener(
        'click',
        closeModal
    );


    /*
    |--------------------------------------------------------------------------
    | SIMPAN
    |--------------------------------------------------------------------------
    */

    saveConfirm.addEventListener(
        'click',
        function () {

            /*
             * Disable tombol
             */

            saveConfirm.disabled = true;

            openConfirm.disabled = true;


            saveConfirm.innerHTML =
                '<i class="fas fa-spinner fa-spin mr-1"></i>' +
                ' Menyimpan...';


            /*
             * Submit native.
             *
             * Tidak memanggil event submit
             * lagi sehingga tidak membuka
             * modal berulang.
             */

            HTMLFormElement.prototype.submit.call(
                form
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | KLIK LUAR
    |--------------------------------------------------------------------------
    */

    overlay.addEventListener(
        'click',
        function (event) {

            if (
                event.target ===
                overlay
            ) {

                closeModal();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ESC
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                overlay.classList.contains('show')
            ) {

                closeModal();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CLOSE
    |--------------------------------------------------------------------------
    */

    function closeModal()
    {
        overlay.classList.remove(
            'show'
        );

        document.body.style.overflow =
            '';

    }


})();

</script>