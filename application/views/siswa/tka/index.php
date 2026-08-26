<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/*
|--------------------------------------------------------------------------
| DATA VIEW
|--------------------------------------------------------------------------
*/

$tka_terkunci = !empty($tka_terkunci);

$pilihan = isset($pilihan)
    ? $pilihan
    : null;

$mapel = isset($mapel)
    ? $mapel
    : [];

$mapel_program = isset($mapel_program)
    ? $mapel_program
    : null;


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
    padding: 18px 0 45px;
    color: #1f2937;
}


/* =========================================================
   MAIN CARD
========================================================= */

.tka-card {
    overflow: hidden;

    border: 0;
    border-radius: 24px;

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

    padding: 30px 32px;

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

    width: 210px;
    height: 210px;

    top: -110px;
    right: -60px;

    border-radius: 50%;

    background: rgba(255,255,255,.10);
}

.tka-header::after {
    content: "";

    position: absolute;

    width: 140px;
    height: 140px;

    right: 100px;
    bottom: -95px;

    border-radius: 50%;

    background: rgba(255,255,255,.07);
}

.tka-header-content {
    position: relative;
    z-index: 2;
}

.tka-header-top {
    display: flex;
    align-items: center;
    gap: 15px;

    margin-bottom: 14px;
}

.tka-icon {
    width: 54px;
    height: 54px;

    flex: 0 0 auto;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 16px;

    background: rgba(255,255,255,.16);

    font-size: 21px;
}

.tka-title {
    margin: 0;

    color: #ffffff;

    font-size: 1.45rem;
    font-weight: 700;
    line-height: 1.25;
}

.tka-subtitle {
    margin: 0;

    color: rgba(255,255,255,.90);

    font-size: .88rem;
    line-height: 1.6;
}


/* =========================================================
   BODY
========================================================= */

.tka-body {
    padding: 30px 32px 35px;
}


/* =========================================================
   FLASH MESSAGE
========================================================= */

.tka-alert {
    margin-bottom: 20px;

    border: 0;
    border-radius: 13px;

    font-size: .86rem;
}

.tka-alert.alert-success {
    background: #ecfdf5;
    color: #047857;
}

.tka-alert.alert-danger {
    background: #fef2f2;
    color: #b91c1c;
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

    margin-bottom: 30px;
}

.tka-info-item {
    min-width: 0;

    padding: 16px 18px;

    border: 1px solid #e5eaf2;
    border-radius: 15px;

    background: #f8fafc;
}

.tka-info-label {
    display: block;

    margin-bottom: 5px;

    color: #6b7280;

    font-size: .72rem;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .35px;
}

.tka-info-value {
    display: block;

    overflow: hidden;

    color: #1f2937;

    font-size: .92rem;
    font-weight: 700;

    text-overflow: ellipsis;
    white-space: nowrap;
}


/* =========================================================
   SECTION HEADER
========================================================= */

.tka-section-header {
    margin-bottom: 21px;
}

.tka-section-title {
    display: flex;
    align-items: center;
    gap: 9px;

    margin: 0 0 6px;

    color: #1f2937;

    font-size: 1.05rem;
    font-weight: 700;
}

.tka-section-title i {
    color: #3b82f6;
}

.tka-section-description {
    margin: 0;

    color: #6b7280;

    font-size: .85rem;
    line-height: 1.6;
}


/* =========================================================
   INFORMATION NOTICE
========================================================= */

.tka-notice {
    display: flex;
    align-items: flex-start;
    gap: 12px;

    margin-bottom: 24px;
    padding: 15px 17px;

    border: 1px solid #d7eaff;
    border-radius: 14px;

    background: #eef6ff;

    color: #315b86;

    font-size: .83rem;
    line-height: 1.6;
}

.tka-notice-icon {
    width: 33px;
    height: 33px;

    flex: 0 0 auto;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #dcecff;

    color: #3b82f6;
}

.tka-notice strong {
    color: #28598b;
}


/* =========================================================
   FORM
========================================================= */

.tka-form-group {
    margin-bottom: 22px;
}

.tka-label {
    display: flex;
    align-items: center;
    gap: 8px;

    margin-bottom: 9px;

    color: #374151 !important;

    font-size: .86rem;
    font-weight: 700;
}

.tka-label-number {
    width: 25px;
    height: 25px;

    flex: 0 0 auto;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    background: #eaf3ff;
    color: #3b82f6;

    font-size: .74rem;
    font-weight: 700;
}


/* =========================================================
   FIXED PROGRAM KEAHLIAN
========================================================= */

.tka-fixed-mapel {
    display: flex;
    align-items: center;
    gap: 13px;

    min-height: 66px;

    padding: 12px 15px;

    border: 1px solid #d7e6f8;
    border-radius: 14px;

    background:
        linear-gradient(
            135deg,
            #f6faff,
            #eef6ff
        );
}

.tka-fixed-icon {
    width: 40px;
    height: 40px;

    flex: 0 0 auto;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #e1efff;

    color: #3b82f6;

    font-size: .92rem;
}

.tka-fixed-content {
    flex: 1;
    min-width: 0;
}

.tka-fixed-label {
    display: block;

    margin-bottom: 2px;

    color: #718096;

    font-size: .66rem;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .35px;
}

.tka-fixed-name {
    display: block;

    overflow: hidden;

    color: #1f2937;

    font-size: .91rem;
    font-weight: 700;

    text-overflow: ellipsis;
    white-space: nowrap;
}

.tka-fixed-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;

    flex: 0 0 auto;

    padding: 6px 9px;

    border-radius: 8px;

    background: #e6f0ff;
    color: #3578d4;

    font-size: .64rem;
    font-weight: 700;

    white-space: nowrap;
}


/* =========================================================
   SELECT
========================================================= */

.tka-select {
    width: 100%;

    min-height: 50px;

    padding: 10px 14px;

    border: 1px solid #dce3ed !important;
    border-radius: 13px !important;

    background: #ffffff !important;
    color: #1f2937 !important;

    font-size: .88rem;

    box-shadow: none !important;

    transition:
        border-color .2s ease,
        box-shadow .2s ease;
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
   SELECT HELP
========================================================= */

.tka-help {
    display: flex;
    align-items: center;
    gap: 6px;

    margin-top: 7px;

    color: #8a94a6;

    font-size: .72rem;
}

.tka-help i {
    color: #94a3b8;
}


/* =========================================================
   ACTION
========================================================= */

.tka-actions {
    display: flex;
    justify-content: flex-start;

    margin-top: 27px;
}

.tka-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;

    min-height: 48px;

    padding: 11px 23px;

    border: 0;
    border-radius: 13px;

    background:
        linear-gradient(
            135deg,
            #4f9cf9,
            #3b82f6
        );

    color: #ffffff !important;

    font-size: .86rem;
    font-weight: 700;

    box-shadow:
        0 7px 17px rgba(59,130,246,.20);

    transition:
        transform .2s ease,
        box-shadow .2s ease;
}

.tka-submit:hover {
    color: #ffffff !important;

    transform: translateY(-1px);

    box-shadow:
        0 9px 20px rgba(59,130,246,.27);
}

.tka-submit:active {
    transform: translateY(0);
}

.tka-submit:disabled {
    opacity: .75;
    cursor: not-allowed;
    transform: none;
}


/* =========================================================
   LOCKED STATE
========================================================= */

.tka-locked-banner {
    display: flex;
    align-items: flex-start;
    gap: 13px;

    margin-bottom: 20px;
    padding: 17px 18px;

    border: 1px solid #ccefe0;
    border-radius: 15px;

    background: #ecfdf5;
}

.tka-locked-icon {
    width: 39px;
    height: 39px;

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

    font-size: .91rem;
    font-weight: 700;
}

.tka-locked-text {
    margin: 0;

    color: #3f7665;

    font-size: .81rem;
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

    margin-bottom: 13px;
}

.tka-choice-title {
    display: flex;
    align-items: center;
    gap: 8px;

    color: #1f2937;

    font-size: .93rem;
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

    font-size: .65rem;
    font-weight: 700;

    letter-spacing: .2px;
}

.tka-choice-item {
    display: flex;
    align-items: center;
    gap: 13px;

    min-height: 64px;

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

    font-size: .84rem;
    font-weight: 700;
}

.tka-choice-content {
    min-width: 0;
}

.tka-choice-label {
    display: block;

    margin-bottom: 2px;

    color: #8a94a6;

    font-size: .67rem;
    font-weight: 600;

    text-transform: uppercase;
    letter-spacing: .25px;
}

.tka-choice-mapel {
    display: block;

    overflow: hidden;

    color: #1f2937 !important;

    font-size: .89rem;
    font-weight: 700;

    text-overflow: ellipsis;
    white-space: nowrap;
}


/* =========================================================
   CONTACT PERSON
========================================================= */

.tka-contact {
    display: flex;
    align-items: center;
    gap: 13px;

    margin-top: 18px;
    padding: 15px 17px;

    border: 1px solid #e3e9f1;
    border-radius: 14px;

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

    background: #e8f8ee;

    color: #25d366;

    font-size: 18px;
}

.tka-contact-text {
    flex: 1;

    color: #6b7280;

    font-size: .78rem;
    line-height: 1.55;
}

.tka-contact-title {
    display: block;

    margin-bottom: 2px;

    color: #374151;

    font-size: .79rem;
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

    background: rgba(15,23,42,.62);

    backdrop-filter: blur(5px);
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
        0 25px 70px rgba(0,0,0,.22);

    animation:
        tkaConfirmIn .18s ease-out;
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

    font-size: 23px;
}

.tka-confirm-title {
    margin: 0;

    color: #1f2937;

    text-align: center;

    font-size: 1.12rem;
    font-weight: 700;
}

.tka-confirm-text {
    margin: 9px 0 18px;

    color: #6b7280;

    text-align: center;

    font-size: .84rem;
    line-height: 1.6;
}

.tka-confirm-choice {
    margin-bottom: 8px;
    padding: 13px 15px;

    border: 1px solid #e5eaf2;
    border-radius: 12px;

    background: #f8fafc;

    color: #374151;

    font-size: .83rem;
}

.tka-confirm-choice strong {
    display: block;

    margin-bottom: 3px;

    color: #3b82f6;

    font-size: .72rem;

    text-transform: uppercase;
    letter-spacing: .2px;
}

.tka-confirm-choice-value {
    color: #1f2937;

    font-size: .86rem;
    font-weight: 700;
}

.tka-confirm-warning {
    margin-top: 14px;
    padding: 12px 14px;

    border: 1px solid #fed7aa;
    border-radius: 11px;

    background: #fff7ed;

    color: #9a3412;

    font-size: .76rem;
    line-height: 1.55;
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

    font-size: .82rem;
    font-weight: 700;

    cursor: pointer;

    transition:
        background .2s ease,
        transform .2s ease;
}

.tka-confirm-cancel {
    border: 1px solid #dbe2ea;

    background: #ffffff;

    color: #4b5563;
}

.tka-confirm-cancel:hover {
    background: #f3f4f6;
}

.tka-confirm-save {
    border: 0;

    background: #3b82f6;

    color: #ffffff;
}

.tka-confirm-save:hover {
    background: #2563eb;
}

.tka-confirm-save:disabled {
    opacity: .75;
    cursor: not-allowed;
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


/* student */

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


/* section */

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

body.dark-mode .tka-notice strong {
    color: #dceaff;
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


/* fixed mapel */

body.dark-mode .tka-fixed-mapel {
    background:
        linear-gradient(
            135deg,
            #263650,
            #293f63
        ) !important;

    border-color: #405a80 !important;
}

body.dark-mode .tka-fixed-icon {
    background: #35547e !important;
    color: #7eb4ff !important;
}

body.dark-mode .tka-fixed-label {
    color: #aeb6c7 !important;
}

body.dark-mode .tka-fixed-name {
    color: #f3f4f6 !important;
}

body.dark-mode .tka-fixed-badge {
    background: #35547e !important;
    color: #8bbcff !important;
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


/* choices */

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

body.dark-mode .tka-choice-label {
    color: #929caf !important;
}

body.dark-mode .tka-choice-mapel {
    color: #f1f5f9 !important;
}

body.dark-mode .tka-locked-badge {
    background: #214936 !important;
    color: #72e4ae !important;
}


/* contact */

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

body.dark-mode .tka-contact-link {
    color: #68e39a !important;
}


/* modal */

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

body.dark-mode .tka-confirm-choice-value {
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


/* alerts */

body.dark-mode .tka-alert.alert-success {
    background: #19392f !important;
    color: #b8f5d6 !important;
}

body.dark-mode .tka-alert.alert-danger {
    background: #47252c !important;
    color: #ffd0d5 !important;
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

    .tka-header-top {
        align-items: flex-start;
    }

    .tka-icon {
        width: 47px;
        height: 47px;

        border-radius: 13px;

        font-size: 18px;
    }

    .tka-title {
        font-size: 1.18rem;
    }

    .tka-subtitle {
        font-size: .82rem;
    }

    .tka-body {
        padding: 22px 18px 27px;
    }

    .tka-student-info {
        grid-template-columns: 1fr;

        gap: 9px;

        margin-bottom: 23px;
    }

    .tka-info-item {
        padding: 13px 15px;
    }

    .tka-notice {
        padding: 13px 14px;
    }

    .tka-fixed-mapel {
        align-items: flex-start;
    }

    .tka-fixed-badge {
        margin-top: 1px;
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

    .tka-contact {
        align-items: flex-start;
    }

    .tka-actions {
        margin-top: 23px;
    }

    .tka-submit {
        width: 100%;
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


@media (max-width: 430px) {

    .tka-header-top {
        gap: 11px;
    }

    .tka-fixed-badge {
        display: none;
    }

    .tka-fixed-mapel {
        padding: 11px 12px;
    }

    .tka-contact {
        padding: 13px;
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

                <div class="tka-header-top">

                    <div class="tka-icon">

                        <i class="fas fa-graduation-cap"></i>

                    </div>


                    <div>

                        <h1 class="tka-title">
                            Pengisian TKA
                        </h1>

                        <p class="tka-subtitle">
                            Lengkapi pilihan mata pelajaran TKA Anda
                            dengan teliti sebelum disimpan.
                        </p>

                    </div>

                </div>

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

                        <?= html_escape(
                            $siswa->nama
                        ); ?>

                    </span>

                </div>


                <div class="tka-info-item">

                    <span class="tka-info-label">
                        Kelas
                    </span>

                    <span class="tka-info-value">

                        <?= html_escape(
                            $siswa->nama_kelas
                        ); ?>

                    </span>

                </div>


                <div class="tka-info-item">

                    <span class="tka-info-label">
                        Tahun Ajaran
                    </span>

                    <span class="tka-info-value">

                        <?= html_escape(
                            $siswa->tahun_ajaran
                        ); ?>

                    </span>

                </div>


            </div>


            <?php if (!$tka_terkunci): ?>


                <!-- =============================================
                     FORM BELUM DIISI
                ============================================== -->


                <div class="tka-section-header">

                    <h2 class="tka-section-title">

                        <i class="fas fa-list-check"></i>

                        Pilihan Mata Pelajaran

                    </h2>

                    <p class="tka-section-description">

                        Pilihan pertama sudah ditentukan otomatis
                        berdasarkan mata pelajaran <strong>Program
                        Keahlian</strong>. Anda hanya perlu memilih
                        satu mata pelajaran untuk pilihan kedua.

                    </p>

                </div>


                <!-- =============================================
                     NOTICE
                ============================================== -->

                <div class="tka-notice">

                    <div class="tka-notice-icon">

                        <i class="fas fa-info"></i>

                    </div>


                    <div>

                        <strong>Perhatikan sebelum menyimpan.</strong>

                        <br>

                        Pastikan pilihan kedua sudah sesuai.
                        Setelah pilihan TKA disimpan, data akan
                        <strong>dikunci</strong> dan tidak dapat
                        diubah oleh siswa.

                    </div>

                </div>


                <?php if (!$mapel_program): ?>


                    <!-- =========================================
                         PROGRAM KEAHLIAN BELUM TERSEDIA
                    ========================================== -->

                    <div class="alert alert-danger tka-alert">

                        <i class="fas fa-triangle-exclamation mr-2"></i>

                        Mata pelajaran <strong>Program Keahlian</strong>
                        belum tersedia. Silakan hubungi admin sekolah.

                    </div>


                    <!-- CONTACT -->

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


                <?php else: ?>


                    <!-- =========================================
                         FORM
                    ========================================== -->

                    <form
                        id="tkaForm"
                        action="<?= site_url('Tka/simpan'); ?>"
                        method="post"
                        novalidate
                    >


                        <!-- CSRF -->

                        <input
                            type="hidden"
                            name="<?= $this->security->get_csrf_token_name(); ?>"
                            value="<?= $this->security->get_csrf_hash(); ?>"
                        >


                        <!-- =====================================
                             PILIHAN 1
                        ====================================== -->

                        <div class="tka-form-group">

                            <label class="tka-label">

                                <span class="tka-label-number">
                                    1
                                </span>

                                Mata Pelajaran Program Keahlian

                            </label>


                            <div class="tka-fixed-mapel">


                                <div class="tka-fixed-icon">

                                    <i class="fas fa-graduation-cap"></i>

                                </div>


                                <div class="tka-fixed-content">

                                    <span class="tka-fixed-label">
                                        Pilihan otomatis
                                    </span>

                                    <span class="tka-fixed-name">

                                        <?= html_escape(
                                            $mapel_program->nama_mapel
                                        ); ?>

                                    </span>

                                </div>


                                <div class="tka-fixed-badge">

                                    <i class="fas fa-lock"></i>

                                    OTOMATIS

                                </div>


                            </div>

                        </div>


                        <!-- =====================================
                             PILIHAN 2
                        ====================================== -->

                        <div class="tka-form-group">

                            <label
                                for="mapel_2_id"
                                class="tka-label"
                            >

                                <span class="tka-label-number">
                                    2
                                </span>

                                Mata Pelajaran Pilihan Anda

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

                                    <?php
                                    /*
                                     * Pengaman tambahan:
                                     * Program Keahlian tidak boleh
                                     * tampil sebagai pilihan kedua.
                                     */

                                    if (
                                        isset($m->kode) &&
                                        strtoupper(
                                            trim($m->kode)
                                        ) === 'PK'
                                    ) {
                                        continue;
                                    }
                                    ?>


                                    <option
                                        value="<?= (int) $m->id; ?>"
                                    >

                                        <?= html_escape(
                                            $m->nama_mapel
                                        ); ?>

                                    </option>


                                <?php endforeach; ?>

                            </select>


                            <div class="tka-help">

                                <i class="fas fa-circle-info"></i>

                                Pilih satu mata pelajaran yang
                                berbeda dari Program Keahlian.

                            </div>

                        </div>


                        <!-- =====================================
                             ACTION
                        ====================================== -->

                        <div class="tka-actions">

                            <button
                                type="submit"
                                id="tkaSubmitButton"
                                class="btn tka-submit"
                            >

                                <i class="fas fa-lock"></i>

                                Simpan & Kunci Pilihan

                            </button>

                        </div>


                    </form>


                <?php endif; ?>


            <?php else: ?>


                <!-- =============================================
                     DATA SUDAH TERKUNCI
                ============================================== -->


                <div class="tka-locked-banner">


                    <div class="tka-locked-icon">

                        <i class="fas fa-lock"></i>

                    </div>


                    <div>

                        <p class="tka-locked-title">
                            Pilihan TKA Sudah Terkunci
                        </p>

                        <p class="tka-locked-text">

                            Pilihan mata pelajaran Anda telah
                            berhasil disimpan. Data tidak dapat
                            diubah kembali oleh siswa.

                        </p>

                    </div>


                </div>


                <!-- =============================================
                     CHOICES
                ============================================== -->

                <div class="tka-choice-box">


                    <div class="tka-choice-heading">


                        <div class="tka-choice-title">

                            <i class="fas fa-circle-check"></i>

                            Pilihan TKA Anda

                        </div>


                        <span class="tka-locked-badge">

                            <i class="fas fa-lock"></i>

                            TERKUNCI

                        </span>


                    </div>


                    <!-- =========================================
                         PILIHAN 1
                    ========================================== -->

                    <div class="tka-choice-item">


                        <div class="tka-choice-number">
                            1
                        </div>


                        <div class="tka-choice-content">

                            <span class="tka-choice-label">
                                Program Keahlian
                            </span>

                            <span class="tka-choice-mapel">

                                <?= html_escape(
                                    $pilihan->mapel_1
                                ); ?>

                            </span>

                        </div>


                    </div>


                    <!-- =========================================
                         PILIHAN 2
                    ========================================== -->

                    <div class="tka-choice-item">


                        <div class="tka-choice-number">
                            2
                        </div>


                        <div class="tka-choice-content">

                            <span class="tka-choice-label">
                                Pilihan Siswa
                            </span>

                            <span class="tka-choice-mapel">

                                <?= html_escape(
                                    $pilihan->mapel_2
                                ); ?>

                            </span>

                        </div>


                    </div>


                    <!-- =========================================
                         CONTACT PERSON
                    ========================================== -->

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


            <?php endif; ?>


        </div>

    </div>

</div>


<!-- =========================================================
     MODAL KONFIRMASI
========================================================= -->

<?php if (!$tka_terkunci && $mapel_program): ?>


    <div
        id="tkaConfirmOverlay"
        class="tka-confirm-overlay"
        aria-hidden="true"
    >


        <div
            class="tka-confirm-box"
            role="dialog"
            aria-modal="true"
            aria-labelledby="tkaConfirmTitle"
        >


            <!-- ICON -->

            <div class="tka-confirm-icon">

                <i class="fas fa-circle-question"></i>

            </div>


            <!-- TITLE -->

            <h5
                id="tkaConfirmTitle"
                class="tka-confirm-title"
            >
                Sudah yakin dengan pilihan Anda?
            </h5>


            <!-- DESCRIPTION -->

            <p class="tka-confirm-text">

                Silakan periksa kembali pilihan mata pelajaran
                Anda. Setelah disimpan, pilihan akan
                <strong>terkunci</strong> dan tidak dapat
                diubah oleh siswa.

            </p>


            <!-- PILIHAN 1 -->

            <div class="tka-confirm-choice">

                <strong>
                    Pilihan 1 · Program Keahlian
                </strong>

                <div class="tka-confirm-choice-value">

                    <?= html_escape(
                        $mapel_program->nama_mapel
                    ); ?>

                </div>

            </div>


            <!-- PILIHAN 2 -->

            <div class="tka-confirm-choice">

                <strong>
                    Pilihan 2 · Pilihan Anda
                </strong>

                <div
                    id="confirmMapel2"
                    class="tka-confirm-choice-value"
                >
                    -
                </div>

            </div>


            <!-- WARNING -->

            <div class="tka-confirm-warning">

                <i class="fas fa-triangle-exclamation mr-1"></i>

                Pastikan pilihan kedua sudah benar.
                Jika memilih <strong>Batal</strong>,
                Anda masih dapat mengganti pilihan.

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


    /*
    |--------------------------------------------------------------------------
    | ELEMENT
    |--------------------------------------------------------------------------
    */

    var form = document.getElementById(
        'tkaForm'
    );

    var overlay = document.getElementById(
        'tkaConfirmOverlay'
    );

    var cancelButton = document.getElementById(
        'tkaCancelConfirm'
    );

    var saveButton = document.getElementById(
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
        !overlay ||
        !cancelButton ||
        !saveButton ||
        !mapel2 ||
        !confirmMapel2
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
             * HTML5 validation.
             */

            if (!form.checkValidity()) {

                event.preventDefault();

                form.reportValidity();

                return;
            }


            event.preventDefault();


            /*
             * Ambil nama mapel pilihan 2.
             */

            var selectedOption =
                mapel2.options[
                    mapel2.selectedIndex
                ];


            if (
                !selectedOption ||
                !mapel2.value
            ) {

                mapel2.focus();

                return;
            }


            confirmMapel2.textContent =
                selectedOption.text.trim();


            /*
             * Tampilkan modal.
             */

            overlay.classList.add('show');

            overlay.setAttribute(
                'aria-hidden',
                'false'
            );

            document.body.style.overflow = 'hidden';


            /*
             * Fokus tombol batal.
             */

            setTimeout(
                function () {

                    cancelButton.focus();

                },
                50
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | TUTUP MODAL
    |--------------------------------------------------------------------------
    */

    function closeModal()
    {
        overlay.classList.remove('show');

        overlay.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.style.overflow = '';
    }


    /*
    |--------------------------------------------------------------------------
    | BATAL
    |--------------------------------------------------------------------------
    */

    cancelButton.addEventListener(
        'click',
        function () {

            closeModal();

            mapel2.focus();

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
             * Cegah double submit.
             */

            saveButton.disabled = true;

            cancelButton.disabled = true;


            saveButton.innerHTML =
                '<i class="fas fa-spinner fa-spin mr-1"></i>' +
                ' Menyimpan...';


            /*
             * Submit form asli.
             */

            form.submit();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | KLIK AREA LUAR
    |--------------------------------------------------------------------------
    */

    overlay.addEventListener(
        'click',
        function (event) {

            if (
                event.target === overlay
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

})();

</script>