<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| DATA
|--------------------------------------------------------------------------
*/

$tka_terkunci = !empty($tka_terkunci);
$pilihan      = isset($pilihan) ? $pilihan : null;
$mapel        = isset($mapel) ? $mapel : [];


/*
|--------------------------------------------------------------------------
| WHATSAPP PAK RIKI
|--------------------------------------------------------------------------
*/

$wa_riki = '6289630267870';

$wa_message = urlencode(
    'Halo Pak Riki, saya mengalami kendala pada pengisian TKA.'
);

$wa_url = 'https://wa.me/' . $wa_riki . '?text=' . $wa_message;
?>

<style>

/* =========================================================
   TKA PAGE
========================================================= */

.tka-page {
    padding: 18px 0 40px;
    color: #1f2937;
}


/* =========================================================
   MAIN CARD
========================================================= */

.tka-card {
    border: 0;
    border-radius: 22px;
    overflow: hidden;
    background: #ffffff;

    box-shadow:
        0 12px 35px rgba(15, 23, 42, .08);
}


/* =========================================================
   HEADER
========================================================= */

.tka-header {
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

.tka-header::before {
    content: "";

    position: absolute;

    width: 190px;
    height: 190px;

    right: -65px;
    top: -90px;

    border-radius: 50%;

    background: rgba(255,255,255,.10);
}

.tka-header::after {
    content: "";

    position: absolute;

    width: 120px;
    height: 120px;

    right: 90px;
    bottom: -75px;

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

    margin-bottom: 14px;

    border-radius: 15px;

    background: rgba(255,255,255,.16);

    font-size: 21px;
}

.tka-title {
    margin: 0;

    color: #ffffff;

    font-size: 1.45rem;
    font-weight: 700;
}

.tka-description {
    max-width: 720px;

    margin: 8px 0 0;

    color: rgba(255,255,255,.90);

    font-size: .90rem;
    line-height: 1.6;
}


/* =========================================================
   BODY
========================================================= */

.tka-body {
    padding: 28px 30px 32px;
}


/* =========================================================
   STUDENT INFO
========================================================= */

.tka-student-info {
    display: grid;

    grid-template-columns:
        1.5fr
        1fr
        1fr;

    gap: 12px;

    margin-bottom: 27px;
}

.tka-info-item {
    padding: 15px 17px;

    border: 1px solid #e5eaf2;
    border-radius: 14px;

    background: #f8fafc;
}

.tka-info-label {
    display: block;

    margin-bottom: 5px;

    color: #6b7280;

    font-size: .74rem;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .3px;
}

.tka-info-value {
    display: block;

    color: #1f2937;

    font-size: .93rem;
    font-weight: 700;
}


/* =========================================================
   SECTION
========================================================= */

.tka-section-title {
    display: flex;
    align-items: center;
    gap: 9px;

    margin-bottom: 7px;

    color: #1f2937;

    font-size: 1.04rem;
    font-weight: 700;
}

.tka-section-title i {
    color: #3b82f6;
}

.tka-section-description {
    margin-bottom: 20px;

    color: #6b7280;

    font-size: .86rem;
    line-height: 1.6;
}


/* =========================================================
   NOTICE
========================================================= */

.tka-notice {
    display: flex;
    align-items: flex-start;
    gap: 12px;

    margin-bottom: 23px;
    padding: 15px 17px;

    border: 1px solid #d7eaff;
    border-radius: 14px;

    background: #eef6ff;

    color: #315b86;

    font-size: .86rem;
    line-height: 1.55;
}

.tka-notice-icon {
    width: 32px;
    height: 32px;

    flex: 0 0 auto;

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
    margin-bottom: 20px;
}

.tka-label {
    display: flex;
    align-items: center;
    gap: 7px;

    margin-bottom: 8px;

    color: #374151 !important;

    font-size: .87rem;
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

    border: 1px solid #dce3ed !important;
    border-radius: 13px !important;

    background: #ffffff !important;
    color: #1f2937 !important;

    font-size: .90rem;

    box-shadow: none !important;

    transition: .2s ease;
}

.tka-select:hover {
    border-color: #a9c8ef !important;
}

.tka-select:focus {
    border-color: #4f9cf9 !important;

    box-shadow:
        0 0 0 3px rgba(79,156,249,.12) !important;
}


/* =========================================================
   ACTION
========================================================= */

.tka-actions {
    display: flex;
    justify-content: flex-start;

    margin-top: 25px;
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

    box-shadow:
        0 6px 15px rgba(79,156,249,.20);

    transition: .2s ease;
}

.tka-submit:hover {
    background: #3b82f6;

    transform: translateY(-1px);

    box-shadow:
        0 8px 18px rgba(79,156,249,.25);
}


/* =========================================================
   LOCKED
========================================================= */

.tka-locked-banner {
    display: flex;
    align-items: flex-start;
    gap: 13px;

    margin-bottom: 22px;
    padding: 17px 18px;

    border: 1px solid #ccefe0;
    border-radius: 15px;

    background: #ecfdf5;
}

.tka-locked-icon {
    width: 38px;
    height: 38px;

    flex: 0 0 auto;

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
   CHOICE BOX
========================================================= */

.tka-choice-box {
    padding: 21px;

    border: 1px solid #e6ebf2;
    border-radius: 18px;

    background: #f8fafc;
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
}

.tka-choice-item {
    display: flex;
    align-items: center;
    gap: 14px;

    min-height: 65px;

    margin-top: 10px;
    padding: 11px 14px;

    border: 1px solid #e5eaf1;
    border-radius: 13px;

    background: #ffffff;
}

.tka-choice-number {
    width: 36px;
    height: 36px;

    flex: 0 0 auto;

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

    font-size: .90rem;
    font-weight: 600;
}


/* =========================================================
   CONTACT PERSON
========================================================= */

.tka-contact {
    display: flex;
    align-items: center;
    gap: 13px;

    margin-top: 22px;
    padding: 15px 17px;

    border: 1px solid #e5eaf2;
    border-radius: 15px;

    background: #ffffff;
}

.tka-contact-icon {
    width: 40px;
    height: 40px;

    flex: 0 0 auto;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #e8f5e9;

    color: #25d366;

    font-size: 18px;
}

.tka-contact-text {
    flex: 1;

    color: #6b7280;

    font-size: .78rem;
    line-height: 1.5;
}

.tka-contact-title {
    display: block;

    margin-bottom: 2px;

    color: #374151;

    font-size: .80rem;
    font-weight: 700;
}

.tka-contact-link {
    color: #16803c !important;

    font-weight: 700;

    text-decoration: none !important;
}

.tka-contact-link:hover {
    text-decoration: underline !important;
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

    padding: 27px;

    border-radius: 22px;

    background: #ffffff;

    box-shadow:
        0 20px 60px rgba(0,0,0,.20);

    animation: tkaConfirmIn .18s ease-out;
}

@keyframes tkaConfirmIn {

    from {
        opacity: 0;
        transform: translateY(12px) scale(.97);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

}

.tka-confirm-icon {
    width: 58px;
    height: 58px;

    margin: 0 auto 16px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 17px;

    background: #eaf3ff;

    color: #3b82f6;

    font-size: 24px;
}

.tka-confirm-title {
    margin: 0;

    text-align: center;

    color: #1f2937;

    font-size: 1.12rem;
    font-weight: 700;
}

.tka-confirm-text {
    margin: 9px 0 17px;

    text-align: center;

    color: #6b7280;

    font-size: .85rem;
    line-height: 1.6;
}

.tka-confirm-choice {
    padding: 13px 15px;

    margin-bottom: 8px;

    border-radius: 12px;

    background: #f8fafc;

    border: 1px solid #e5eaf2;

    font-size: .85rem;
}

.tka-confirm-choice strong {
    color: #3b82f6;
}

.tka-confirm-warning {
    margin-top: 14px;

    padding: 12px 14px;

    border-radius: 11px;

    background: #fff7ed;

    border: 1px solid #fed7aa;

    color: #9a3412;

    font-size: .78rem;

    line-height: 1.5;
}

.tka-confirm-actions {
    display: flex;

    gap: 10px;

    margin-top: 20px;
}

.tka-confirm-cancel,
.tka-confirm-save {
    flex: 1;

    min-height: 45px;

    border-radius: 11px;

    font-size: .84rem;
    font-weight: 700;

    transition: .2s ease;
}

.tka-confirm-cancel {
    border: 1px solid #dbe2ea;

    background: #ffffff;

    color: #4b5563;
}

.tka-confirm-save {
    border: 0;

    background: #3b82f6;

    color: #ffffff;
}

.tka-confirm-save:hover {
    background: #2563eb;
}

.tka-confirm-cancel:hover {
    background: #f3f4f6;
}


/* =========================================================
   DARK MODE
========================================================= */

body.dark-mode .tka-page {
    color: #e5e7eb;
}

body.dark-mode .tka-card,
body.dark-mode .tka-body {
    background: #202333 !important;
}

body.dark-mode .tka-card {
    box-shadow:
        0 12px 35px rgba(0,0,0,.22);
}

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

body.dark-mode .tka-section-title {
    color: #f3f4f6 !important;
}

body.dark-mode .tka-section-title i {
    color: #75adff;
}

body.dark-mode .tka-section-description {
    color: #aeb6c7 !important;
}

body.dark-mode .tka-notice {
    background: #263650 !important;
    border-color: #354b6b !important;
    color: #c9dcf7 !important;
}

body.dark-mode .tka-notice-icon {
    background: #304a70 !important;
    color: #7db4ff !important;
}

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

body.dark-mode .tka-select option {
    background: #292d40 !important;
    color: #ffffff !important;
}

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

body.dark-mode .tka-choice-box {
    background: #292d40 !important;
    border-color: #3c4255 !important;
}

body.dark-mode .tka-choice-title {
    color: #f3f4f6 !important;
}

body.dark-mode .tka-choice-item {
    background: #202333 !important;
    border-color: #3c4255 !important;
}

body.dark-mode .tka-choice-mapel {
    color: #f1f5f9 !important;
}

body.dark-mode .tka-contact {
    background: #292d40 !important;
    border-color: #3c4255 !important;
}

body.dark-mode .tka-contact-title {
    color: #f3f4f6 !important;
}

body.dark-mode .tka-contact-text {
    color: #aeb6c7 !important;
}

body.dark-mode .tka-confirm-box {
    background: #202333 !important;
}

body.dark-mode .tka-confirm-title {
    color: #f3f4f6;
}

body.dark-mode .tka-confirm-text {
    color: #aeb6c7;
}

body.dark-mode .tka-confirm-choice {
    background: #292d40;
    border-color: #3c4255;
    color: #e5e7eb;
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

    .tka-page {
        padding: 12px 0 30px;
    }

    .tka-card {
        border-radius: 18px;
    }

    .tka-header {
        padding: 23px 20px;
    }

    .tka-title {
        font-size: 1.2rem;
    }

    .tka-description {
        font-size: .84rem;
    }

    .tka-body {
        padding: 21px 17px 25px;
    }

    .tka-student-info {
        grid-template-columns: 1fr;
        gap: 9px;
        margin-bottom: 22px;
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

    .tka-submit {
        width: 100%;
    }

    .tka-contact {
        align-items: flex-start;
    }

    .tka-confirm-box {
        padding: 23px 18px;
        border-radius: 19px;
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


<div class="container-fluid tka-page">


    <!-- =====================================================
         FLASH MESSAGE
    ====================================================== -->

    <?php if ($this->session->flashdata('success')): ?>

        <div class="alert alert-success tka-alert">

            <i class="fas fa-check-circle mr-2"></i>

            <?= html_escape(
                $this->session->flashdata('success')
            ); ?>

        </div>

    <?php endif; ?>


    <?php if ($this->session->flashdata('error')): ?>

        <div class="alert alert-danger tka-alert">

            <i class="fas fa-exclamation-circle mr-2"></i>

            <?= html_escape(
                $this->session->flashdata('error')
            ); ?>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         MAIN CARD
    ====================================================== -->

    <div class="card tka-card">


        <!-- =================================================
             HEADER
        ================================================== -->

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


        <!-- =================================================
             BODY
        ================================================== -->

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
                     FORM
                ============================================== -->

                <div class="tka-section-title">

                    <i class="fas fa-list-check"></i>

                    Pilih Mata Pelajaran

                </div>


                <div class="tka-section-description">

                    Silakan pilih
                    <strong>2 mata pelajaran</strong>.
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
                        Setelah klik
                        <strong>Simpan & Kunci Pilihan</strong>,
                        pilihan tidak dapat diubah oleh siswa.

                    </div>

                </div>


                <!-- FORM -->

                <form
                    id="tkaForm"
                    action="<?= site_url('Tka/simpan'); ?>"
                    method="post"
                >

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
        id="mapel_1_id"
        class="form-control tka-select"
        required
    >

        <option value="">
            -- Pilih Mata Pelajaran --
        </option>

        <?php foreach ($mapel as $m): ?>

            <option
                value="<?= (int) $m->id; ?>"
            >
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
        id="mapel_2_id"
        class="form-control tka-select"
        required
    >

        <option value="">
            -- Pilih Mata Pelajaran --
        </option>

        <?php foreach ($mapel as $m): ?>

            <option
                value="<?= (int) $m->id; ?>"
            >
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
                     LOCKED
                ============================================== -->

                <div class="tka-locked">


                    <!-- LOCKED BANNER -->

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


                        <!-- CONTACT PERSON -->

                        <div class="tka-contact">

                            <div class="tka-contact-icon">

                                <i class="fab fa-whatsapp"></i>

                            </div>


                            <div class="tka-contact-text">

                                <span class="tka-contact-title">
                                    Mengalami kendala?
                                </span>

                                Silakan hubungi

                                <a
                                    href="<?= $wa_url; ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="tka-contact-link"
                                >
                                    Pak Riki
                                </a>

                                melalui WhatsApp.

                            </div>

                        </div>

                    </div>

                </div>


            <?php endif; ?>


        </div>

    </div>

</div>


<!-- =========================================================
     KONFIRMASI SIMPAN
========================================================= -->

<?php if (!$tka_terkunci): ?>

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
            Sudah yakin dengan pilihan Anda?
        </h5>


        <p class="tka-confirm-text">

            Periksa kembali pilihan mata pelajaran Anda.
            Setelah disimpan, pilihan akan
            <strong>terkunci</strong> dan tidak dapat
            diubah oleh siswa.

        </p>


        <div class="tka-confirm-choice">

            <strong>Pilihan 1</strong>

            <div id="confirmMapel1">
                -
            </div>

        </div>


        <div class="tka-confirm-choice">

            <strong>Pilihan 2</strong>

            <div id="confirmMapel2">
                -
            </div>

        </div>


        <div class="tka-confirm-warning">

            <i class="fas fa-triangle-exclamation mr-1"></i>

            Pastikan kedua mata pelajaran sudah benar.
            Jika Anda membatalkan, Anda akan kembali
            ke form dan masih dapat mengganti pilihan.

        </div>


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

                <i class="fas fa-lock mr-1"></i>

                Ya, Simpan & Kunci

            </button>

        </div>

    </div>

</div>

<?php endif; ?>


<script>

(function () {

    'use strict';


    var form = document.getElementById('tkaForm');

    var overlay = document.getElementById(
        'tkaConfirmOverlay'
    );

    var cancelButton = document.getElementById(
        'tkaCancelConfirm'
    );

    var saveButton = document.getElementById(
        'tkaSaveConfirm'
    );

    var mapel1 = document.getElementById(
        'mapel_1_id'
    );

    var mapel2 = document.getElementById(
        'mapel_2_id'
    );

    var confirmMapel1 = document.getElementById(
        'confirmMapel1'
    );

    var confirmMapel2 = document.getElementById(
        'confirmMapel2'
    );


    /*
    |--------------------------------------------------------------------------
    | PASTIKAN ELEMEN ADA
    |--------------------------------------------------------------------------
    */

    if (
        !form ||
        !overlay ||
        !cancelButton ||
        !saveButton ||
        !mapel1 ||
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

            /*
             * Biarkan HTML5 validation berjalan.
             */

            if (!form.checkValidity()) {

                return;
            }


            event.preventDefault();


            /*
             * Pastikan dua pilihan tidak sama.
             */

            if (
                mapel1.value &&
                mapel2.value &&
                mapel1.value === mapel2.value
            ) {

                alert(
                    'Mata pelajaran pilihan 1 dan pilihan 2 tidak boleh sama.'
                );

                mapel2.focus();

                return;
            }


            /*
             * Tampilkan nama mapel
             */

            confirmMapel1.textContent =
                mapel1.options[
                    mapel1.selectedIndex
                ].text;


            confirmMapel2.textContent =
                mapel2.options[
                    mapel2.selectedIndex
                ].text;


            /*
             * Tampilkan modal
             */

            overlay.classList.add('show');

            document.body.style.overflow = 'hidden';

        }
    );


    /*
    |--------------------------------------------------------------------------
    | BATAL
    |--------------------------------------------------------------------------
    */

    cancelButton.addEventListener(
        'click',
        function () {

            overlay.classList.remove('show');

            document.body.style.overflow = '';

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SIMPAN
    |--------------------------------------------------------------------------
    */

    saveButton.addEventListener(
        'click',
        function () {

            /*
             * Disable tombol agar tidak double submit.
             */

            saveButton.disabled = true;

            saveButton.innerHTML =
                '<i class="fas fa-spinner fa-spin mr-1"></i>' +
                ' Menyimpan...';


            /*
             * Submit asli
             */

            form.submit();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | KLIK AREA LUAR MODAL
    |--------------------------------------------------------------------------
    */

    overlay.addEventListener(
        'click',
        function (event) {

            if (event.target === overlay) {

                overlay.classList.remove('show');

                document.body.style.overflow = '';

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

                overlay.classList.remove('show');

                document.body.style.overflow = '';

            }

        }
    );

})();




</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const pilihan1 = document.getElementById('mapel_1_id');
    const pilihan2 = document.getElementById('mapel_2_id');

    if (!pilihan1 || !pilihan2) {
        return;
    }


    function updatePilihan2() {

        const selectedValue = pilihan1.value;


        /*
        |--------------------------------------------------------------------------
        | Tampilkan kembali semua pilihan
        |--------------------------------------------------------------------------
        */

        Array.from(pilihan2.options).forEach(function (option) {

            option.hidden = false;

        });


        /*
        |--------------------------------------------------------------------------
        | Jika pilihan 1 sudah dipilih,
        | sembunyikan pilihan yang sama di pilihan 2
        |--------------------------------------------------------------------------
        */

        if (selectedValue !== '') {

            const optionToHide =
                pilihan2.querySelector(
                    'option[value="' + selectedValue + '"]'
                );

            if (optionToHide) {

                optionToHide.hidden = true;

            }


            /*
            |--------------------------------------------------------------------------
            | Kalau pilihan 2 kebetulan sama,
            | kosongkan kembali
            |--------------------------------------------------------------------------
            */

            if (pilihan2.value === selectedValue) {

                pilihan2.value = '';

            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Saat pilihan 1 berubah
    |--------------------------------------------------------------------------
    */

    pilihan1.addEventListener(
        'change',
        updatePilihan2
    );


    /*
    |--------------------------------------------------------------------------
    | Jalankan pertama kali
    |--------------------------------------------------------------------------
    */

    updatePilihan2();

});
</script>