<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

$tka_terkunci = !empty($tka_terkunci);
$pilihan      = isset($pilihan) ? $pilihan : null;
$mapel        = isset($mapel) ? $mapel : [];
?>

<style>

/* =========================================================
   TKA PAGE
========================================================= */

.tka-page {
    padding: 20px 0 40px;
    color: #1f2937;
}

/* =========================================================
   MAIN CARD
========================================================= */

.tka-card {
    border: 0;
    border-radius: 24px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 12px 35px rgba(15, 23, 42, .08);
}

/* =========================================================
   HEADER
========================================================= */

.tka-header {
    position: relative;
    overflow: hidden;
    padding: 30px 32px;
    background: linear-gradient(
        135deg,
        #4f9cf9 0%,
        #3b82f6 100%
    );
    color: #ffffff;
}

.tka-header::before {
    content: "";
    position: absolute;
    width: 180px;
    height: 180px;
    right: -60px;
    top: -80px;
    border-radius: 50%;
    background: rgba(255,255,255,.10);
}

.tka-header::after {
    content: "";
    position: absolute;
    width: 120px;
    height: 120px;
    right: 90px;
    bottom: -70px;
    border-radius: 50%;
    background: rgba(255,255,255,.07);
}

.tka-header-content {
    position: relative;
    z-index: 2;
}

.tka-icon {
    width: 52px;
    height: 52px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
    background: rgba(255,255,255,.16);
    font-size: 21px;
    margin-bottom: 15px;
}

.tka-title {
    margin: 0;
    font-size: 1.45rem;
    font-weight: 700;
    color: #ffffff;
}

.tka-description {
    margin: 8px 0 0;
    max-width: 700px;
    color: rgba(255,255,255,.88);
    font-size: .92rem;
    line-height: 1.6;
}

/* =========================================================
   BODY
========================================================= */

.tka-body {
    padding: 30px 32px 34px;
}

/* =========================================================
   STUDENT INFO
========================================================= */

.tka-student-info {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr;
    gap: 12px;
    margin-bottom: 28px;
}

.tka-info-item {
    padding: 16px 18px;
    border: 1px solid #e5eaf2;
    border-radius: 15px;
    background: #f8fafc;
}

.tka-info-label {
    display: block;
    margin-bottom: 5px;
    color: #6b7280;
    font-size: .76rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.tka-info-value {
    display: block;
    color: #1f2937;
    font-size: .94rem;
    font-weight: 700;
}

/* =========================================================
   SECTION TITLE
========================================================= */

.tka-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    color: #1f2937;
    font-size: 1.05rem;
    font-weight: 700;
}

.tka-section-title i {
    color: #3b82f6;
}

.tka-section-description {
    margin-bottom: 22px;
    color: #6b7280;
    font-size: .87rem;
    line-height: 1.6;
}

/* =========================================================
   ALERT
========================================================= */

.tka-notice {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 24px;
    padding: 15px 17px;
    border-radius: 14px;
    background: #eef6ff;
    border: 1px solid #d7eaff;
    color: #315b86;
    font-size: .87rem;
    line-height: 1.55;
}

.tka-notice-icon {
    flex: 0 0 auto;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #dcecff;
    color: #3b82f6;
}

/* =========================================================
   FORM
========================================================= */

.tka-form-group {
    margin-bottom: 21px;
}

.tka-label {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 8px;
    color: #374151 !important;
    font-size: .88rem;
    font-weight: 700;
}

.tka-label-number {
    width: 24px;
    height: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    background: #eaf3ff;
    color: #3b82f6;
    font-size: .75rem;
    font-weight: 700;
}

.tka-select {
    width: 100%;
    min-height: 50px;
    padding: 10px 14px;
    border-radius: 13px !important;
    border: 1px solid #dce3ed !important;
    background: #ffffff !important;
    color: #1f2937 !important;
    font-size: .9rem;
    box-shadow: none !important;
    transition: all .2s ease;
}

.tka-select:hover {
    border-color: #a9c8ef !important;
}

.tka-select:focus {
    border-color: #4f9cf9 !important;
    box-shadow: 0 0 0 3px rgba(79,156,249,.12) !important;
}

/* =========================================================
   SAVE BUTTON
========================================================= */

.tka-actions {
    margin-top: 26px;
}

.tka-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 48px;
    padding: 11px 22px;
    border: 0;
    border-radius: 13px;
    background: #4f9cf9;
    color: #ffffff !important;
    font-size: .88rem;
    font-weight: 700;
    box-shadow: 0 6px 15px rgba(79,156,249,.20);
    transition: all .2s ease;
}

.tka-submit:hover {
    background: #3b82f6;
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 8px 18px rgba(79,156,249,.25);
}

.tka-submit:active {
    transform: translateY(0);
}

/* =========================================================
   LOCKED STATE
========================================================= */

.tka-locked {
    margin-top: 4px;
}

.tka-locked-banner {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-bottom: 22px;
    padding: 17px 18px;
    border-radius: 15px;
    background: #ecfdf5;
    border: 1px solid #ccefe0;
}

.tka-locked-icon {
    flex: 0 0 auto;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    background: #d1fae5;
    color: #059669;
}

.tka-locked-title {
    margin: 0 0 3px;
    color: #047857;
    font-size: .92rem;
    font-weight: 700;
}

.tka-locked-text {
    margin: 0;
    color: #3f7665;
    font-size: .82rem;
    line-height: 1.5;
}

/* =========================================================
   CHOICE CARD
========================================================= */

.tka-choice-box {
    padding: 21px;
    border-radius: 18px;
    background: #f8fafc;
    border: 1px solid #e6ebf2;
}

.tka-choice-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 14px;
}

.tka-choice-title {
    display: flex;
    align-items: center;
    gap: 9px;
    color: #1f2937;
    font-size: .94rem;
    font-weight: 700;
}

.tka-choice-title i {
    color: #059669;
}

.tka-locked-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 9px;
    border-radius: 8px;
    background: #dcfce7;
    color: #15803d;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .3px;
}

.tka-choice-item {
    display: flex;
    align-items: center;
    gap: 14px;
    min-height: 65px;
    margin-top: 10px;
    padding: 11px 14px;
    border-radius: 13px;
    background: #ffffff;
    border: 1px solid #e5eaf1;
}

.tka-choice-number {
    flex: 0 0 auto;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    background: #eaf3ff;
    color: #3b82f6;
    font-size: .85rem;
    font-weight: 700;
}

.tka-choice-mapel {
    color: #1f2937 !important;
    font-size: .9rem;
    font-weight: 600;
}

/* =========================================================
   ADMIN NOTE
========================================================= */

.tka-admin-note {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 16px;
    color: #6b7280;
    font-size: .78rem;
}

.tka-admin-note i {
    color: #9ca3af;
}

/* =========================================================
   FLASH MESSAGE
========================================================= */

.tka-alert {
    margin-bottom: 20px;
    border: 0;
    border-radius: 13px;
    font-size: .87rem;
}

.tka-alert .close {
    outline: none;
}

/* =========================================================
   DARK MODE
========================================================= */

body.dark-mode .tka-page {
    color: #e5e7eb;
}

body.dark-mode .tka-card {
    background: #202333 !important;
    box-shadow: 0 12px 35px rgba(0,0,0,.22);
}

body.dark-mode .tka-body {
    background: #202333 !important;
    color: #e5e7eb;
}

/* student info */

body.dark-mode .tka-info-item {
    background: #292d40 !important;
    border-color: #3b4054 !important;
}

body.dark-mode .tka-info-label {
    color: #9ca7ba !important;
}

body.dark-mode .tka-info-value {
    color: #f3f4f6 !important;
}

/* title */

body.dark-mode .tka-section-title {
    color: #f3f4f6 !important;
}

body.dark-mode .tka-section-title i {
    color: #75adff;
}

body.dark-mode .tka-section-description {
    color: #aeb6c7 !important;
}

/* notice */

body.dark-mode .tka-notice {
    background: #263650 !important;
    border-color: #354b6b !important;
    color: #c9dcf7 !important;
}

body.dark-mode .tka-notice-icon {
    background: #304a70 !important;
    color: #7db4ff !important;
}

/* form */

body.dark-mode .tka-label {
    color: #f1f5f9 !important;
}

body.dark-mode .tka-label-number {
    background: #293f63 !important;
    color: #7eb4ff !important;
}

body.dark-mode .tka-select {
    background: #292d40 !important;
    border-color: #474d62 !important;
    color: #f8fafc !important;
}

body.dark-mode .tka-select:hover {
    border-color: #63718c !important;
}

body.dark-mode .tka-select:focus {
    background: #292d40 !important;
    border-color: #6ea8fe !important;
    color: #ffffff !important;
}

body.dark-mode .tka-select option {
    background: #292d40 !important;
    color: #ffffff !important;
}

/* locked */

body.dark-mode .tka-locked-banner {
    background: #19392f !important;
    border-color: #285b4b !important;
}

body.dark-mode .tka-locked-icon {
    background: #21523f !important;
    color: #67e8b0 !important;
}

body.dark-mode .tka-locked-title {
    color: #6ee7b7 !important;
}

body.dark-mode .tka-locked-text {
    color: #a7dccc !important;
}

/* choice */

body.dark-mode .tka-choice-box {
    background: #292d40 !important;
    border-color: #3c4255 !important;
}

body.dark-mode .tka-choice-title {
    color: #f3f4f6 !important;
}

body.dark-mode .tka-choice-title i {
    color: #6ee7b7 !important;
}

body.dark-mode .tka-locked-badge {
    background: #214936 !important;
    color: #72e4ae !important;
}

body.dark-mode .tka-choice-item {
    background: #202333 !important;
    border-color: #3c4255 !important;
}

body.dark-mode .tka-choice-number {
    background: #293f63 !important;
    color: #7eb4ff !important;
}

body.dark-mode .tka-choice-mapel {
    color: #f1f5f9 !important;
}

body.dark-mode .tka-admin-note {
    color: #9ca7ba !important;
}

body.dark-mode .tka-admin-note i {
    color: #788398 !important;
}

/* alerts */

body.dark-mode .tka-alert.alert-success {
    background: #19392f !important;
    color: #b8f5d6 !important;
    border-color: #285b4b !important;
}

body.dark-mode .tka-alert.alert-danger {
    background: #47252c !important;
    color: #ffd0d5 !important;
    border-color: #70363f !important;
}

body.dark-mode .tka-alert.alert-info {
    background: #263650 !important;
    color: #c9dcf7 !important;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    .tka-page {
        padding: 12px 0 30px;
    }

    .tka-card {
        border-radius: 18px;
    }

    .tka-header {
        padding: 24px 21px;
    }

    .tka-title {
        font-size: 1.2rem;
    }

    .tka-description {
        font-size: .84rem;
    }

    .tka-body {
        padding: 22px 18px 26px;
    }

    .tka-student-info {
        grid-template-columns: 1fr;
        gap: 9px;
        margin-bottom: 23px;
    }

    .tka-info-item {
        padding: 13px 15px;
    }

    .tka-choice-box {
        padding: 16px;
    }

    .tka-choice-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .tka-choice-item {
        min-height: 58px;
        padding: 10px 12px;
    }

    .tka-choice-number {
        width: 33px;
        height: 33px;
    }

    .tka-choice-mapel {
        font-size: .84rem;
    }

    .tka-submit {
        width: 100%;
    }
}

</style>


<div class="container-fluid tka-page">

    <!-- =====================================================
         FLASH MESSAGE
    ====================================================== -->

    <?php if ($this->session->flashdata('success')): ?>

        <div class="alert alert-success tka-alert">
            <i class="fas fa-check-circle mr-2"></i>
            <?= html_escape($this->session->flashdata('success')); ?>
        </div>

    <?php endif; ?>


    <?php if ($this->session->flashdata('error')): ?>

        <div class="alert alert-danger tka-alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?= html_escape($this->session->flashdata('error')); ?>
        </div>

    <?php endif; ?>


    <!-- =====================================================
         MAIN CARD
    ====================================================== -->

    <div class="card tka-card">

        <!-- HEADER -->

        <div class="tka-header">

            <div class="tka-header-content">

                <div class="tka-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>

                <h1 class="tka-title">
                    Pengisian TKA
                </h1>

                <p class="tka-description">
                    Pilih dua mata pelajaran yang akan digunakan
                    sebagai mata pelajaran pilihan TKA.
                </p>

            </div>

        </div>


        <!-- BODY -->

        <div class="tka-body">


            <!-- =================================================
                 DATA SISWA
            ================================================== -->

            <div class="tka-student-info">

                <div class="tka-info-item">

                    <span class="tka-info-label">
                        Nama Siswa
                    </span>

                    <span class="tka-info-value">
                        <?= html_escape($siswa->nama); ?>
                    </span>

                </div>


                <div class="tka-info-item">

                    <span class="tka-info-label">
                        Kelas
                    </span>

                    <span class="tka-info-value">
                        <?= html_escape($siswa->nama_kelas); ?>
                    </span>

                </div>


                <div class="tka-info-item">

                    <span class="tka-info-label">
                        Tahun Ajaran
                    </span>

                    <span class="tka-info-value">
                        <?= html_escape($siswa->tahun_ajaran); ?>
                    </span>

                </div>

            </div>


            <?php if (!$tka_terkunci): ?>


                <!-- =============================================
                     FORM BELUM DIISI
                ============================================== -->

                <div class="tka-section-title">

                    <i class="fas fa-list-check"></i>

                    Pilih Mata Pelajaran

                </div>

                <div class="tka-section-description">

                    Silakan pilih <strong>2 mata pelajaran</strong>.
                    Pastikan pilihan sudah benar sebelum menyimpan.
                    Setelah disimpan, pilihan akan dikunci dan hanya
                    dapat diubah oleh admin.

                </div>


                <!-- NOTICE -->

                <div class="tka-notice">

                    <div class="tka-notice-icon">
                        <i class="fas fa-info"></i>
                    </div>

                    <div>
                        <strong>Perhatian</strong><br>

                        Pilihan TKA hanya dapat disimpan satu kali.
                        Setelah klik <strong>Simpan & Kunci Pilihan</strong>,
                        siswa tidak dapat mengubah pilihan tersebut.
                    </div>

                </div>


                <!-- FORM -->

                <form
                    action="<?= site_url('Tka/simpan'); ?>"
                    method="post"
                >

                    <!-- CSRF -->

                    <input
                        type="hidden"
                        name="<?= $this->security->get_csrf_token_name(); ?>"
                        value="<?= $this->security->get_csrf_hash(); ?>"
                    >


                    <!-- PILIHAN 1 -->

                    <div class="tka-form-group">

                        <label class="tka-label">

                            <span class="tka-label-number">
                                1
                            </span>

                            Mata Pelajaran Pilihan 1

                        </label>

                        <select
                            name="mapel_1_id"
                            class="form-control tka-select"
                            required
                        >

                            <option value="">
                                -- Pilih Mata Pelajaran --
                            </option>

                            <?php foreach ($mapel as $m): ?>

                                <option value="<?= (int) $m->id; ?>">

                                    <?= html_escape($m->nama_mapel); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <!-- PILIHAN 2 -->

                    <div class="tka-form-group">

                        <label class="tka-label">

                            <span class="tka-label-number">
                                2
                            </span>

                            Mata Pelajaran Pilihan 2

                        </label>

                        <select
                            name="mapel_2_id"
                            class="form-control tka-select"
                            required
                        >

                            <option value="">
                                -- Pilih Mata Pelajaran --
                            </option>

                            <?php foreach ($mapel as $m): ?>

                                <option value="<?= (int) $m->id; ?>">

                                    <?= html_escape($m->nama_mapel); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <!-- BUTTON -->

                    <div class="tka-actions">

                        <button
                            type="submit"
                            class="btn tka-submit"
                        >

                            <i class="fas fa-lock"></i>

                            Simpan & Kunci Pilihan

                        </button>

                    </div>

                </form>


            <?php else: ?>


                <!-- =============================================
                     SUDAH TERKUNCI
                ============================================== -->

                <div class="tka-locked">


                    <!-- LOCKED MESSAGE -->

                    <div class="tka-locked-banner">

                        <div class="tka-locked-icon">

                            <i class="fas fa-lock"></i>

                        </div>

                        <div>

                            <p class="tka-locked-title">
                                Pilihan TKA Sudah Terkunci
                            </p>

                            <p class="tka-locked-text">

                                Pilihan mata pelajaran Anda sudah
                                berhasil disimpan. Perubahan hanya
                                dapat dilakukan oleh admin.

                            </p>

                        </div>

                    </div>


                    <!-- CHOICES -->

                    <div class="tka-choice-box">

                        <div class="tka-choice-heading">

                            <div class="tka-choice-title">

                                <i class="fas fa-check-circle"></i>

                                Pilihan TKA Anda

                            </div>

                            <span class="tka-locked-badge">

                                <i class="fas fa-lock"></i>

                                TERKUNCI

                            </span>

                        </div>


                        <!-- CHOICE 1 -->

                        <div class="tka-choice-item">

                            <div class="tka-choice-number">
                                1
                            </div>

                            <div class="tka-choice-mapel">

                                <?= html_escape(
                                    $pilihan->mapel_1
                                ); ?>

                            </div>

                        </div>


                        <!-- CHOICE 2 -->

                        <div class="tka-choice-item">

                            <div class="tka-choice-number">
                                2
                            </div>

                            <div class="tka-choice-mapel">

                                <?= html_escape(
                                    $pilihan->mapel_2
                                ); ?>

                            </div>

                        </div>


                        <div class="tka-admin-note">

                            <i class="fas fa-user-shield"></i>

                            Jika terdapat kesalahan pilihan,
                            silakan hubungi admin sekolah.

                        </div>

                    </div>

                </div>


            <?php endif; ?>

        </div>

    </div>

</div>