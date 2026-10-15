
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$sesi_id = isset($sesi->id) ? (int) $sesi->id : 0;
$csrf_name = $this->security->get_csrf_token_name();
$csrf_hash = $this->security->get_csrf_hash();

$jenis_solat = isset($sesi->jenis_solat)
    ? ucfirst($sesi->jenis_solat) : 'Dzuhur';

$tanggal_sesi = isset($sesi->tanggal)
    ? $sesi->tanggal : date('Y-m-d');

$jam_mulai = isset($sesi->jam_mulai)
    ? $sesi->jam_mulai : '-';

$jam_selesai = isset($sesi->jam_selesai)
    ? $sesi->jam_selesai : '-';

$status_sesi = isset($sesi->status)
    ? $sesi->status : 'tutup';
?>

<style>
    :root {
        --sc-bg: #11131d;
        --sc-panel: #191d2b;
        --sc-panel2: #202538;
        --sc-border: #343b50;
        --sc-text: #e8edf7;
        --sc-muted: #929bb1;
        --sc-green: #4ade80;
        --sc-red: #fb7185;
        --sc-blue: #60a5fa;
        --sc-purple: #c084fc;
    }

    .solat-scanner {
        color: var(--sc-text);
        font-family: Inter, "Segoe UI", Arial, sans-serif;
        padding: 16px;
        min-height: 80vh;
    }

    .solat-scanner * {
        box-sizing: border-box;
    }

    .solat-scanner .sc-shell {
        width: 100%;
        max-width: 1700px;
        margin: 0 auto;
    }

    .solat-scanner .sc-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 18px;
    }

    .solat-scanner .sc-brand {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .solat-scanner .sc-brand-icon {
        display: flex;
        width: 48px;
        height: 48px;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        color: var(--sc-green);
        background: rgba(74, 222, 128, .10);
        border: 1px solid rgba(74, 222, 128, .2);
        font-size: 22px;
    }

    .solat-scanner .sc-title {
        margin: 0;
        color: var(--sc-text);
        font-size: 20px;
        font-weight: 800;
        letter-spacing: .5px;
    }

    .solat-scanner .sc-subtitle {
        color: var(--sc-muted);
        font-size: 12px;
        margin-top: 5px;
    }

    .solat-scanner .sc-toolbar {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 7px;
    }

    .solat-scanner .sc-btn {
        min-height: 38px;
        padding: 8px 12px;
        color: var(--sc-text);
        background: var(--sc-panel2);
        border: 1px solid var(--sc-border);
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s;
    }

    .solat-scanner .sc-btn:hover {
        filter: brightness(1.2);
        transform: translateY(-1px);
    }

    .solat-scanner .sc-btn-primary {
        color: #08140d;
        background: var(--sc-green);
        border-color: var(--sc-green);
    }

    .solat-scanner .sc-btn-danger {
        color: #fff;
        background: #be3455;
        border-color: #be3455;
    }

    .solat-scanner .sc-btn:disabled {
        opacity: .45;
        cursor: not-allowed;
        transform: none;
    }

    .solat-scanner .sc-session {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }

    .solat-scanner .sc-session-item {
        min-width: 0;
        padding: 14px 16px;
        background: var(--sc-panel);
        border: 1px solid var(--sc-border);
        border-radius: 12px;
    }

    .solat-scanner .sc-session-label {
        color: var(--sc-muted);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .7px;
        margin-bottom: 7px;
    }

    .solat-scanner .sc-session-value {
        font-size: 14px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .solat-scanner .sc-status-open {
        color: var(--sc-green);
    }

    .solat-scanner .sc-status-closed {
        color: var(--sc-red);
    }

    .solat-scanner .sc-method-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }

    .solat-scanner .sc-card {
        min-width: 0;
        overflow: hidden;
        background: var(--sc-panel);
        border: 1px solid var(--sc-border);
        border-radius: 15px;
    }

    .solat-scanner .sc-card-head {
        padding: 16px 18px;
        border-bottom: 1px solid var(--sc-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .solat-scanner .sc-method-title {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
    }

    .solat-scanner .sc-method-icon {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .solat-scanner .sc-qr-icon {
        color: var(--sc-blue);
        background: rgba(96, 165, 250, .12);
    }

    .solat-scanner .sc-rfid-icon {
        color: var(--sc-purple);
        background: rgba(192, 132, 252, .12);
    }

    .solat-scanner .sc-method-name {
        margin: 0;
        font-size: 15px;
        font-weight: 800;
        color: var(--sc-text);
    }

    .solat-scanner .sc-method-desc {
        margin-top: 4px;
        font-size: 11px;
        color: var(--sc-muted);
    }

    .solat-scanner .sc-live {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--sc-green);
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .solat-scanner .sc-live-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--sc-green);
        box-shadow: 0 0 10px rgba(74, 222, 128, .65);
    }

    .solat-scanner .sc-card-body {
        padding: 16px;
    }

    .solat-scanner .sc-camera {
        min-height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #080b13;
        border: 1px solid #2b3447;
        border-radius: 12px;
    }

    .solat-scanner #reader {
        width: 100%;
        max-width: 100%;
        border: 0 !important;
        color: white;
    }

    .solat-scanner #reader video {
        width: 100% !important;
        max-width: 100% !important;
        object-fit: cover;
        border-radius: 10px;
    }

    .solat-scanner #reader img {
        max-width: 100%;
    }

    .solat-scanner #reader__dashboard_section_csr button,
    .solat-scanner #reader button {
        color: #fff !important;
        background: #26334b !important;
        border: 1px solid #43516a !important;
        border-radius: 7px !important;
        padding: 7px 10px !important;
        margin: 4px !important;
    }

    .solat-scanner #reader select {
        max-width: 100%;
        padding: 6px;
        color: #111827;
        border-radius: 6px;
    }

    .solat-scanner .sc-camera-placeholder {
        padding: 25px;
        text-align: center;
        color: var(--sc-muted);
    }

    .solat-scanner .sc-camera-placeholder i {
        display: block;
        margin-bottom: 13px;
        color: var(--sc-blue);
        font-size: 45px;
    }

    .solat-scanner .sc-rfid-zone {
        min-height: 280px;
        padding: 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        background:
            radial-gradient(
                circle at center,
                rgba(192, 132, 252, .10),
                transparent 65%
            ),
            #080b13;
        border: 1px solid #2b3447;
        border-radius: 12px;
    }

    .solat-scanner .sc-rfid-symbol {
        width: 95px;
        height: 95px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(192, 132, 252, .35);
        border-radius: 50%;
        background: rgba(192, 132, 252, .08);
        color: var(--sc-purple);
        font-size: 40px;
        margin-bottom: 17px;
        animation: rfidPulse 2s infinite;
    }

    @keyframes rfidPulse {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(192, 132, 252, .15);
        }
        50% {
            box-shadow: 0 0 0 14px rgba(192, 132, 252, 0);
        }
    }

    .solat-scanner .sc-rfid-title {
        color: var(--sc-text);
        font-size: 17px;
        font-weight: 800;
    }

    .solat-scanner .sc-rfid-desc {
        max-width: 360px;
        margin: 8px auto 16px;
        color: var(--sc-muted);
        font-size: 12px;
        line-height: 1.7;
    }

    .solat-scanner .sc-input {
        width: 100%;
        min-height: 44px;
        padding: 11px 13px;
        color: var(--sc-text);
        background: #141a29;
        border: 1px solid #414a62;
        border-radius: 9px;
        outline: none;
    }

    .solat-scanner .sc-input:focus {
        border-color: var(--sc-purple);
        box-shadow: 0 0 0 3px rgba(192, 132, 252, .12);
    }

    .solat-scanner .sc-rfid-hint {
        margin-top: 10px;
        font-size: 10px;
        color: var(--sc-muted);
    }

    .solat-scanner .sc-log-card {
        background: #080b12;
        border: 1px solid #293a36;
        border-radius: 14px;
        overflow: hidden;
    }

    .solat-scanner .sc-log-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 15px 18px;
        background: #111820;
        border-bottom: 1px solid #26352f;
    }

    .solat-scanner .sc-log-title {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--sc-green);
        font-size: 13px;
        font-weight: 800;
        letter-spacing: .5px;
    }

    .solat-scanner .sc-terminal-dots {
        display: flex;
        gap: 5px;
    }

    .solat-scanner .sc-terminal-dots span {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #475569;
    }

    .solat-scanner .sc-terminal-dots span:first-child {
        background: #fb7185;
    }

    .solat-scanner .sc-terminal-dots span:nth-child(2) {
        background: #fbbf24;
    }

    .solat-scanner .sc-terminal-dots span:nth-child(3) {
        background: #4ade80;
    }

    .solat-scanner .sc-log-count {
        padding: 5px 9px;
        color: var(--sc-green);
        background: rgba(74, 222, 128, .08);
        border: 1px solid rgba(74, 222, 128, .2);
        border-radius: 7px;
        font-size: 11px;
        font-weight: 800;
    }

    .solat-scanner .sc-log-body {
        padding: 16px 18px;
        max-height: 300px;
        overflow-y: auto;
        font-family: Consolas, "Courier New", monospace;
        font-size: 12px !important;
        line-height: 1.5 !important;
        font-weight: 400;
        text-transform: none;
    }

    /* Isolasi isi live log dari CSS global tema/admin yang mungkin
       memberi ukuran font besar pada class seperti .error atau elemen div. */
    .solat-scanner .sc-log-body *,
    .solat-scanner .sc-log-entry,
    .solat-scanner .sc-log-line,
    .solat-scanner .sc-log-meta,
    .solat-scanner .sc-log-empty,
    .solat-scanner .sc-log-empty div {
        font-family: Consolas, "Courier New", monospace !important;
        font-size: 12px !important;
        line-height: 1.5 !important;
        letter-spacing: normal;
        text-transform: none;
        text-shadow: none;
    }

    .solat-scanner .sc-log-entry {
        display: block;
        width: 100%;
        min-width: 0;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .solat-scanner .sc-log-entry .sc-log-line,
    .solat-scanner .sc-log-entry .sc-log-meta {
        display: block;
        max-width: 100%;
    }

    .solat-scanner .sc-log-entry .sc-log-meta br {
        display: block;
        content: "";
        margin-bottom: 2px;
    }

    .solat-scanner .sc-log-empty {
        color: #7d899d;
        padding: 12px 0;
        line-height: 1.9;
    }

    .solat-scanner .sc-log-entry {
        padding: 11px 12px;
        margin-bottom: 9px;
        border-left: 3px solid var(--sc-green);
        border-radius: 0 8px 8px 0;
        background: rgba(74, 222, 128, .055);
        color: var(--sc-green);
        animation: logAppear .25s ease-out;
    }

    .solat-scanner .sc-log-entry:last-child {
        margin-bottom: 0;
    }

    .solat-scanner .sc-log-entry.error {
        color: var(--sc-red);
        border-left-color: var(--sc-red);
        background: rgba(251, 113, 133, .06);
    }

    .solat-scanner .sc-log-entry.warning {
        color: #fbbf24;
        border-left-color: #fbbf24;
        background: rgba(251, 191, 36, .06);
    }

    .solat-scanner .sc-log-line {
        margin-bottom: 5px;
        overflow-wrap: anywhere;
    }

    .solat-scanner .sc-log-name {
        font-weight: 800;
        font-size: 14px;
    }

    .solat-scanner .sc-log-meta {
        opacity: .88;
        line-height: 1.8;
        overflow-wrap: anywhere;
    }

    .solat-scanner .sc-footer {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 14px;
        color: var(--sc-muted);
        font-size: 10px;
    }

    .solat-scanner .sc-toast {
        position: fixed;
        z-index: 999999;
        right: 20px;
        bottom: 20px;
        max-width: calc(100vw - 40px);
        padding: 13px 17px;
        color: #fff;
        background: #202538;
        border: 1px solid var(--sc-border);
        border-radius: 10px;
        box-shadow: 0 10px 35px rgba(0, 0, 0, .3);
        font-size: 13px;
        display: none;
    }

    .solat-scanner .sc-toast.success {
        border-color: #15803d;
    }

    .solat-scanner .sc-toast.error {
        border-color: #be3455;
    }

    /* Mode layar penuh */
    .solat-scanner:fullscreen {
        width: 100%;
        height: 100%;
        min-height: 100%;
        overflow-y: auto;
        padding: 16px 22px;
        background: var(--sc-bg);
    }

    .solat-scanner:fullscreen .sc-shell {
        max-width: 1900px;
    }

    .solat-scanner:fullscreen .sc-method-grid {
        gap: 20px;
    }

    .solat-scanner:fullscreen .sc-camera,
    .solat-scanner:fullscreen .sc-rfid-zone {
        min-height: 320px;
    }

    .solat-scanner:fullscreen .sc-log-body {
        max-height: 35vh;
    }

    .solat-scanner:fullscreen .sc-topbar {
        margin-bottom: 12px;
    }

    .solat-scanner:fullscreen .sc-session {
        margin-bottom: 12px;
    }

    @media (min-width: 1200px) {
        .solat-scanner:fullscreen .sc-camera,
        .solat-scanner:fullscreen .sc-rfid-zone {
            min-height: 370px;
        }
    }

    @media (max-width: 900px) {
        .solat-scanner .sc-session {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .solat-scanner .sc-method-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575px) {
        .solat-scanner {
            padding: 10px;
        }

        .solat-scanner .sc-title {
            font-size: 17px;
        }

        .solat-scanner .sc-toolbar {
            width: 100%;
            justify-content: flex-start;
        }

        .solat-scanner .sc-btn {
            flex: 1;
        }

        .solat-scanner .sc-session-item {
            padding: 11px;
        }

        .solat-scanner .sc-camera,
        .solat-scanner .sc-rfid-zone {
            min-height: 240px;
        }

        .solat-scanner .sc-card-body {
            padding: 10px;
        }

        .solat-scanner:fullscreen {
            padding: 10px;
        }
    }
</style>

<div class="solat-scanner" id="scanPage">
    <div class="sc-shell">

        <div class="sc-topbar">
            <div class="sc-brand">
                <div class="sc-brand-icon">
                    <i class="fas fa-mosque"></i>
                </div>

                <div>
                    <h1 class="sc-title">DZUHUR ATTENDANCE</h1>
                    <div class="sc-subtitle">
                        SCHOOL ATTENDANCE SYSTEM / SCANNER TERMINAL
                    </div>
                </div>
            </div>

            <div class="sc-toolbar">
                <button type="button" class="sc-btn"
                        onclick="ubahZoom(-0.1)">
                    <i class="fas fa-search-minus"></i>
                </button>

                <span id="zoomLabel"
                      style="font-size:12px;min-width:42px;text-align:center">
                    100%
                </span>

                <button type="button" class="sc-btn"
                        onclick="ubahZoom(0.1)">
                    <i class="fas fa-search-plus"></i>
                </button>

                <button type="button" class="sc-btn"
                        onclick="resetZoom()">
                    <i class="fas fa-undo"></i>
                    Reset
                </button>

                <button type="button" class="sc-btn sc-btn-primary"
                        onclick="toggleFullscreen()">
                    <i class="fas fa-expand"></i>
                    <span id="fullscreenLabel">Layar Penuh</span>
                </button>

                <a href="<?= base_url('absensi_solat'); ?>"
                   class="sc-btn"
                   style="text-decoration:none">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>

        <div class="sc-session">
            <div class="sc-session-item">
                <div class="sc-session-label">Sesi</div>
                <div class="sc-session-value">
                    <?= html_escape($jenis_solat); ?>
                </div>
            </div>

            <div class="sc-session-item">
                <div class="sc-session-label">Tanggal</div>
                <div class="sc-session-value">
                    <?= html_escape($tanggal_sesi); ?>
                </div>
            </div>

            <div class="sc-session-item">
                <div class="sc-session-label">Waktu Sesi</div>
                <div class="sc-session-value">
                    <?= html_escape($jam_mulai); ?>
                    -
                    <?= html_escape($jam_selesai); ?>
                </div>
            </div>

            <div class="sc-session-item">
                <div class="sc-session-label">Status Sesi</div>
                <div class="sc-session-value <?= $status_sesi === 'buka'
                    ? 'sc-status-open' : 'sc-status-closed'; ?>">
                    <i class="fas <?= $status_sesi === 'buka'
                        ? 'fa-circle-check' : 'fa-lock'; ?>"></i>
                    <?= strtoupper(html_escape($status_sesi)); ?>
                </div>
            </div>
        </div>

        <form id="form-scan" autocomplete="off">
            <input type="hidden" id="sesi-id" name="sesi_id"
                   value="<?= $sesi_id; ?>">

            <input type="hidden" id="token" name="token" value="">
            <input type="hidden" id="metode" name="metode" value="qrcode">

            <input type="hidden" id="csrf-token"
                   value="<?= html_escape($csrf_hash); ?>">
        </form>

        <div class="sc-method-grid">

            <!-- SISI KIRI: QR CODE -->
            <section class="sc-card">
                <div class="sc-card-head">
                    <div class="sc-method-title">
                        <div class="sc-method-icon sc-qr-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>

                        <div>
                            <h2 class="sc-method-name">QR CODE SCANNER</h2>
                            <div class="sc-method-desc">
                                Baca QR Code yang berisi NISN siswa
                            </div>
                        </div>
                    </div>

                    <div class="sc-live">
                        <span class="sc-live-dot"></span>
                        CAMERA
                    </div>
                </div>

                <div class="sc-card-body">
                    <div class="sc-camera">
                        <div id="reader">
                            <div class="sc-camera-placeholder">
                                <i class="fas fa-camera"></i>
                                <div>Kamera scanner akan ditampilkan di sini.</div>
                            </div>
                        </div>
                    </div>

                    <div class="sc-subtitle mt-3">
                        <i class="fas fa-info-circle mr-1"></i>
                        Izinkan akses kamera jika browser memintanya.
                    </div>
                </div>
            </section>

            <!-- SISI KANAN: RFID -->
            <section class="sc-card">
                <div class="sc-card-head">
                    <div class="sc-method-title">
                        <div class="sc-method-icon sc-rfid-icon">
                            <i class="fas fa-id-card"></i>
                        </div>

                        <div>
                            <h2 class="sc-method-name">RFID CARD READER</h2>
                            <div class="sc-method-desc">
                                Tempelkan kartu RFID siswa
                            </div>
                        </div>
                    </div>

                    <div class="sc-live">
                        <span class="sc-live-dot"></span>
                        RFID
                    </div>
                </div>

                <div class="sc-card-body">
                    <div class="sc-rfid-zone">
                        <div class="sc-rfid-symbol">
                            <i class="fas fa-address-card"></i>
                        </div>

                        <div class="sc-rfid-title">
                            TAP YOUR STUDENT CARD
                        </div>

                        <div class="sc-rfid-desc">
                            Tempelkan kartu pada pembaca RFID.
                            UID kartu akan dikirim otomatis ketika pembaca
                            mengirim tombol Enter.
                        </div>

                        <input type="text"
                               id="rfidInput"
                               class="sc-input"
                               placeholder="Menunggu UID kartu RFID..."
                               autocomplete="off"
                               autocapitalize="off"
                               spellcheck="false">

                        <div class="sc-rfid-hint">
                            <i class="fas fa-keyboard mr-1"></i>
                            Pastikan pembaca RFID terhubung ke komputer.
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <!-- LIVE LOG -->
        <section class="sc-log-card">
            <div class="sc-log-head">
                <div class="sc-log-title">
                    <div class="sc-terminal-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>

                    <i class="fas fa-terminal"></i>
                    ABSENSI TERMINAL / LIVE LOG
                </div>

                <div class="sc-log-count">
                    <i class="fas fa-check-circle mr-1"></i>
                    <span id="jumlah-hadir">0</span> BERHASIL
                </div>
            </div>

            <div class="sc-log-body" id="hasil" aria-live="polite">
                <div class="sc-log-empty">
                    <div>$ dzuhur-attendance --start</div>
                    <div>$ scanner initialized...</div>
                    <div>$ menunggu QR Code atau kartu RFID...</div>
                    <div class="mt-2">_</div>
                </div>
            </div>
        </section>

        <div class="sc-footer">
            <span>
                <i class="fas fa-shield-alt mr-1"></i>
                Absensi Salat Dzuhur
            </span>

            <span>
                <i class="fas fa-bolt mr-1"></i>
                QR CODE + RFID
            </span>
        </div>

    </div>
</div>

<div class="sc-toast" id="scanToast" role="status"></div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
(function () {
    'use strict';

    const page = document.getElementById('scanPage');
    const form = document.getElementById('form-scan');
    const tokenInput = document.getElementById('token');
    const metodeInput = document.getElementById('metode');
    const rfidInput = document.getElementById('rfidInput');
    const hasil = document.getElementById('hasil');
    const jumlahHadir = document.getElementById('jumlah-hadir');
    const csrfInput = document.getElementById('csrf-token');
    const toast = document.getElementById('scanToast');

    const sesiId = document.getElementById('sesi-id').value;
    const csrfName = <?= json_encode($csrf_name); ?>;
    const csrfInitial = <?= json_encode($csrf_hash); ?>;
    const sesiStatus = <?= json_encode($status_sesi); ?>;

    let csrfHash = csrfInitial;
    let zoom = 1;
    let scanner = null;
    let scannerAktif = false;
    let requestBerjalan = false;
    let jumlahBerhasil = 0;
    let toastTimer = null;
    let lastScanKey = '';
    let lastScanTime = 0;

    const SCAN_URL = <?= json_encode(
        base_url('absensi_solat/proses_scan')
    ); ?>;

    const csrfCookieName = <?= json_encode(
        $this->config->item('csrf_cookie_name')
            ? $this->config->item('csrf_cookie_name')
            : 'csrf_cookie_name'
    ); ?>;

    function escapeHtml(value) {
        return String(value == null ? '' : value).replace(
            /[&<>"']/g,
            function (char) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                }[char];
            }
        );
    }

    function waktuSekarang() {
        return new Date().toLocaleTimeString('id-ID', {
            hour12: false
        });
    }

    function tampilToast(pesan, jenis) {
        toast.textContent = pesan;
        toast.className = 'sc-toast ' + (jenis || '');
        toast.style.display = 'block';

        clearTimeout(toastTimer);

        toastTimer = setTimeout(function () {
            toast.style.display = 'none';
        }, 3500);
    }

    function tambahLog(data, jenis) {
        const empty = hasil.querySelector('.sc-log-empty');

        if (empty) {
            empty.remove();
        }

        const entry = document.createElement('div');
        entry.className = 'sc-log-entry' + (jenis ? ' ' + jenis : '');

        const baris = document.createElement('div');
        baris.className = 'sc-log-line';

        const detail = document.createElement('div');
        detail.className = 'sc-log-meta';

        if (data.success) {
            const nama = escapeHtml(data.nama || 'Siswa');
            const nis = escapeHtml(data.nis || '-');
            const nisn = escapeHtml(data.nisn || '-');
            const kelas = escapeHtml(data.kelas || data.nama_kelas || '-');
            const status = escapeHtml(
                String(data.status || 'hadir').toUpperCase()
            );
            const metode = escapeHtml(
                String(data.metode || '-').toUpperCase()
            );
            const waktu = escapeHtml(data.waktu || waktuSekarang());

            baris.innerHTML =
                '<span style="opacity:.7">[' + waktu + ']</span> ' +
                '<span style="font-weight:800">+ ' + nama + '</span>';

            detail.innerHTML =
                '&nbsp;&nbsp;NIS: ' + nis + '<br>' +
                '&nbsp;&nbsp;NISN: ' + nisn + '<br>' +
                '&nbsp;&nbsp;KELAS: ' + kelas + '<br>' +
                '&nbsp;&nbsp;STATUS: ' + status + '<br>' +
                '&nbsp;&nbsp;METHOD: ' + metode;

            jumlahBerhasil++;
            jumlahHadir.textContent = jumlahBerhasil;
        } else {
            const nama = escapeHtml(data.nama || '');
            const pesan = escapeHtml(data.message || 'Absensi gagal.');

            baris.textContent =
                '[' + waktuSekarang() + '] ' +
                (data.duplicate ? '! ' : 'x ') +
                (nama ? nama + ': ' : '') + pesan;

            detail.textContent = data.duplicate
                ? 'Siswa sudah tercatat pada sesi ini.'
                : 'Periksa data siswa, status sesi, dan koneksi server.';
        }

        entry.appendChild(baris);
        entry.appendChild(detail);

        hasil.prepend(entry);

        while (hasil.children.length > 100) {
            hasil.removeChild(hasil.lastElementChild);
        }
    }

    function setScannerButtons() {
        const tombol = document.querySelectorAll(
            '#reader button, #reader select'
        );

        tombol.forEach(function (element) {
            element.style.maxWidth = '100%';
        });
    }

    async function prosesScan(token, metode) {
        token = String(token || '').trim();

        if (!token) {
            return;
        }

        if (sesiStatus !== 'buka') {
            tampilToast('Sesi absensi sedang ditutup.', 'error');
            return;
        }

        if (requestBerjalan) {
            return;
        }

        // Mencegah QR yang sama terkirim berkali-kali dalam waktu singkat.
        const scanKey = metode + ':' + token;
        const sekarang = Date.now();

        if (
            scanKey === lastScanKey &&
            sekarang - lastScanTime < 2500
        ) {
            return;
        }

        lastScanKey = scanKey;
        lastScanTime = sekarang;
        requestBerjalan = true;

        tokenInput.value = token;
        metodeInput.value = metode;

        const payload = new URLSearchParams();
        payload.append('sesi_id', sesiId);
        payload.append('token', token);
        payload.append('metode', metode);
        payload.append(csrfName, csrfHash);

        try {
            const response = await fetch(SCAN_URL, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type':
                        'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: payload.toString()
            });

            const responseText = await response.text();

            let data;

            try {
                data = JSON.parse(responseText);
            } catch (error) {
                console.error('Respons asli server:', responseText);

                tambahLog({
                    success: false,
                    message: 'Respons server bukan JSON. HTTP ' +
                        response.status
                }, 'error');

                tampilToast(
                    'Respons server tidak valid. Periksa log PHP.',
                    'error'
                );

                return;
            }

            if (data.csrf_test_name) {
                csrfHash = data.csrf_test_name;
                csrfInput.value = csrfHash;
            }

            if (response.ok && data.success) {
                tambahLog(data, '');

                tampilToast(
                    'Absensi berhasil: ' + (data.nama || 'Siswa'),
                    'success'
                );
            } else {
                tambahLog(data, data.duplicate ? 'warning' : 'error');

                tampilToast(
                    data.message || 'Absensi gagal.',
                    data.duplicate ? '' : 'error'
                );
            }
        } catch (error) {
            console.error('Kesalahan scan:', error);

            tambahLog({
                success: false,
                message: 'Tidak dapat menghubungi server.'
            }, 'error');

            tampilToast('Koneksi ke server gagal.', 'error');
        } finally {
            requestBerjalan = false;

            tokenInput.value = '';

            if (metode === 'rfid') {
                rfidInput.value = '';
                rfidInput.focus();
            }
        }
    }

    // Dipanggil oleh kamera QR.
    window.onQrScanSuccess = function (decodedText) {
        prosesScan(decodedText, 'qrcode');
    };

    // RFID reader berperilaku seperti keyboard dan mengirim Enter.
    rfidInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();

            const uid = rfidInput.value.trim();

            if (uid) {
                prosesScan(uid, 'rfid');
            }
        }
    });

    // Mempertahankan fokus RFID, kecuali saat pengguna sedang
    // menggunakan kontrol kamera atau toolbar.
    document.addEventListener('click', function (event) {
        if (
            event.target.closest('#rfidInput') ||
            event.target.closest('#reader') ||
            event.target.closest('.sc-toolbar')
        ) {
            return;
        }

        rfidInput.focus({ preventScroll: true });
    });

    async function mulaiKamera() {
        if (sesiStatus !== 'buka') {
            tambahLog({
                success: false,
                message: 'Sesi ditutup. Scanner tidak diaktifkan.'
            }, 'warning');

            return;
        }

        if (typeof Html5Qrcode === 'undefined') {
            tambahLog({
                success: false,
                message: 'Library QR tidak berhasil dimuat.'
            }, 'error');

            return;
        }

        try {
            scanner = new Html5Qrcode('reader');

            await scanner.start(
                { facingMode: 'environment' },
                {
                    fps: 12,
                    qrbox: function (width, height) {
                        const sisi = Math.floor(
                            Math.min(width, height) * 0.7
                        );

                        return {
                            width: Math.max(120, sisi),
                            height: Math.max(120, sisi)
                        };
                    },
                    aspectRatio: 1
                },
                function (decodedText) {
                    window.onQrScanSuccess(decodedText);
                },
                function () {
                    // Error pembacaan per frame adalah normal.
                }
            );

            scannerAktif = true;
            setScannerButtons();
        } catch (error) {
            console.error('Kamera gagal dimulai:', error);

            tambahLog({
                success: false,
                message: 'Kamera gagal diaktifkan. Periksa izin kamera.'
            }, 'error');

            tampilToast(
                'Kamera tidak dapat diakses. Pastikan izin kamera aktif.',
                'error'
            );
        }
    }

    window.toggleFullscreen = async function () {
        try {
            if (!document.fullscreenElement) {
                await page.requestFullscreen();
            } else {
                await document.exitFullscreen();
            }
        } catch (error) {
            tampilToast(
                'Browser tidak mengizinkan mode layar penuh.',
                'error'
            );
        }
    };

    document.addEventListener('fullscreenchange', function () {
        const aktif = document.fullscreenElement === page;
        const label = document.getElementById('fullscreenLabel');

        label.textContent = aktif
            ? 'Keluar Fullscreen'
            : 'Layar Penuh';

        if (scannerAktif && scanner) {
            // Kamera tetap berjalan; hanya ukuran viewport yang berubah.
            setTimeout(setScannerButtons, 250);
        }
    });

    window.ubahZoom = function (perubahan) {
        zoom = Math.max(0.7, Math.min(1.4, zoom + perubahan));
        zoom = Math.round(zoom * 10) / 10;

        // Mengubah ukuran seluruh konten bisa memengaruhi video kamera.
        // Zoom browser melalui transform hanya diterapkan ke layout.
        page.style.zoom = zoom;
        document.getElementById('zoomLabel').textContent =
            Math.round(zoom * 100) + '%';
    };

    window.resetZoom = function () {
        zoom = 1;
        page.style.zoom = '1';
        document.getElementById('zoomLabel').textContent = '100%';
    };

    // Mulai kamera otomatis saat halaman dibuka.
    mulaiKamera();

    // RFID reader biasanya mengetik UID ke field yang sedang fokus.
    setTimeout(function () {
        rfidInput.focus({ preventScroll: true });
    }, 700);

})();
</script>
