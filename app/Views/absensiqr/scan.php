<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Scanner Absensi QR Siswa - SimSGTK</title>

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- HTML5 QR Code Library -->
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        :root {
            --bg-main: #0b0f19;
            --bg-card: rgba(18, 24, 38, 0.85);
            --bg-card-border: rgba(255, 255, 255, 0.08);
            --primary: #3b82f6;
            --primary-glow: rgba(59, 130, 246, 0.35);
            --success: #10b981;
            --success-glow: rgba(16, 185, 129, 0.4);
            --warning: #f59e0b;
            --warning-glow: rgba(245, 158, 11, 0.4);
            --danger: #ef4444;
            --danger-glow: rgba(239, 68, 68, 0.4);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(59, 130, 246, 0.12) 0%, transparent 40%),
                radial-gradient(circle at 85% 85%, rgba(16, 185, 129, 0.08) 0%, transparent 40%);
        }

        /* TOP NAVIGATION / HEADER */
        .header {
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(11, 15, 25, 0.75);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--bg-card-border);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-logo {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #2563eb, #10b981);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
            box-shadow: 0 4px 14px var(--primary-glow);
        }

        .header-title h1 {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .header-title p {
            font-size: 12px;
            color: var(--text-muted);
        }

        .header-clock {
            text-align: right;
        }

        .clock-time {
            font-size: 20px;
            font-weight: 800;
            color: #38bdf8;
            letter-spacing: 0.5px;
            font-variant-numeric: tabular-nums;
        }

        .clock-date {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* MAIN CONTENT LAYOUT */
        .main-container {
            flex: 1;
            max-width: 1440px;
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 20px;
        }

        @media (max-width: 1024px) {
            .main-container {
                grid-template-columns: 1fr;
            }
        }

        /* LEFT COLUMN: SCANNER */
        .scanner-section {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* SCHEDULE & STATUS BAR */
        .schedule-bar {
            background: var(--bg-card);
            border: 1px solid var(--bg-card-border);
            border-radius: 16px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }

        .schedule-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .schedule-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .icon-masuk { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }
        .icon-pulang { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
        .icon-hari { background: rgba(16, 185, 129, 0.15); color: #34d399; }

        .schedule-text span {
            display: block;
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 600;
        }

        .schedule-text strong {
            font-size: 15px;
            color: #fff;
        }

        /* CAMERA VIEWFINDER CARD */
        .camera-card {
            background: var(--bg-card);
            border: 1px solid var(--bg-card-border);
            border-radius: 20px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.4);
            overflow: hidden;
        }

        /* VIEW CONTROLS (Camera selector, torch, speech toggle) */
        .scanner-controls {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .camera-select-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--bg-card-border);
            border-radius: 10px;
            padding: 6px 12px;
            flex: 1;
            min-width: 200px;
        }

        .camera-select-wrapper select {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 13px;
            width: 100%;
            outline: none;
            cursor: pointer;
        }

        .camera-select-wrapper select option {
            background: #1e293b;
            color: #fff;
        }

        .btn-toggle-tool {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--bg-card-border);
            color: #fff;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-toggle-tool:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        .btn-toggle-tool.active {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 0 12px var(--primary-glow);
        }

        /* SCANNER WRAPPER & LASER */
        .reader-wrapper {
            width: 100%;
            max-width: 520px;
            aspect-ratio: 1 / 1;
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            background: #000;
            border: 2px solid rgba(59, 130, 246, 0.3);
            box-shadow: inset 0 0 30px rgba(0, 0, 0, 0.8), 0 0 20px rgba(59, 130, 246, 0.15);
        }

        #reader {
            width: 100% !important;
            height: 100% !important;
            border: none !important;
        }

        #reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            border-radius: 16px;
        }

        /* OVERLAY RETICLE / SCAN GUIDES */
        .scan-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 5;
        }

        .reticle-box {
            width: 78%;
            height: 78%;
            position: relative;
            border: 2px dashed rgba(255, 255, 255, 0.25);
            border-radius: 16px;
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.45);
        }

        .corner {
            position: absolute;
            width: 26px;
            height: 26px;
            border-color: #38bdf8;
            border-style: solid;
        }

        .corner-tl { top: -2px; left: -2px; border-width: 4px 0 0 4px; border-top-left-radius: 12px; }
        .corner-tr { top: -2px; right: -2px; border-width: 4px 4px 0 0; border-top-right-radius: 12px; }
        .corner-bl { bottom: -2px; left: -2px; border-width: 0 0 4px 4px; border-bottom-left-radius: 12px; }
        .corner-br { bottom: -2px; right: -2px; border-width: 0 4px 4px 0; border-bottom-right-radius: 12px; }

        /* ANIMATED SCANNING LASER BEAM */
        .laser-line {
            position: absolute;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #38bdf8 50%, transparent);
            box-shadow: 0 0 14px #38bdf8, 0 0 24px #0284c7;
            top: 0;
            left: 0;
            animation: scanLaser 2s infinite ease-in-out;
        }

        @keyframes scanLaser {
            0% { top: 6%; opacity: 0.2; }
            50% { top: 92%; opacity: 1; }
            100% { top: 6%; opacity: 0.2; }
        }

        .scan-hint {
            position: absolute;
            bottom: 14px;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(6px);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: #bae6fd;
            border: 1px solid rgba(56, 189, 248, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .scan-pulse-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: blink 1.2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.3; transform: scale(0.8); }
        }

        /* POPUP NOTIFIKASI HASIL SCAN CEPAT */
        .scan-result-card {
            width: 100%;
            max-width: 520px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(16px);
            border: 2px solid var(--success);
            box-shadow: 0 0 30px var(--success-glow);
            border-radius: 18px;
            padding: 16px 20px;
            margin-top: 14px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            transform: translateY(0);
            opacity: 1;
        }

        .scan-result-card.hidden {
            display: none;
            opacity: 0;
            transform: translateY(10px);
        }

        .scan-result-card.type-warning {
            border-color: var(--warning);
            box-shadow: 0 0 30px var(--warning-glow);
        }

        .scan-result-card.type-error {
            border-color: var(--danger);
            box-shadow: 0 0 30px var(--danger-glow);
        }

        .result-avatar {
            width: 64px;
            height: 64px;
            border-radius: 14px;
            background: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #94a3b8;
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .result-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .result-info {
            flex: 1;
            min-width: 0;
        }

        .result-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 4px;
        }

        .result-status-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-masuk-tepat { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); }
        .badge-masuk-telat { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.4); }
        .badge-pulang { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.4); }
        .badge-error { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.4); }

        .result-time {
            font-size: 13px;
            font-weight: 700;
            color: #38bdf8;
            font-variant-numeric: tabular-nums;
        }

        .result-name {
            font-size: 18px;
            font-weight: 800;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 2px;
        }

        .result-meta {
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .result-meta span {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .result-countdown {
            width: 100%;
            height: 3px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }

        .result-countdown-bar {
            height: 100%;
            background: #10b981;
            width: 100%;
            transition: width 2.5s linear;
        }

        /* RIGHT COLUMN: STATS & LIVE FEED */
        .sidebar-section {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* STATS GRID */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--bg-card-border);
            border-radius: 16px;
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .stat-total .stat-icon { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }
        .stat-tepat .stat-icon { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        .stat-telat .stat-icon { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
        .stat-pulang .stat-icon { background: rgba(168, 85, 247, 0.15); color: #c084fc; }

        .stat-val {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
        }

        .stat-lbl {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
        }

        /* RECENT SCANS LIVE FEED */
        .feed-card {
            background: var(--bg-card);
            border: 1px solid var(--bg-card-border);
            border-radius: 20px;
            padding: 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            max-height: 480px;
        }

        .feed-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--bg-card-border);
        }

        .feed-title {
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #f1f5f9;
        }

        .feed-badge-live {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .feed-list {
            list-style: none;
            overflow-y: auto;
            flex: 1;
            padding-right: 4px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Custom Scrollbar */
        .feed-list::-webkit-scrollbar {
            width: 5px;
        }
        .feed-list::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }
        .feed-list::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
        }

        .feed-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.04);
            border-radius: 12px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            animation: slideIn 0.3s ease-out;
            transition: all 0.2s ease;
        }

        .feed-item:hover {
            background: rgba(255, 255, 255, 0.06);
            transform: translateX(2px);
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .feed-item-info {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .feed-item-avatar {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #1e293b;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .feed-item-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .feed-item-text {
            min-width: 0;
        }

        .feed-item-name {
            font-size: 13px;
            font-weight: 700;
            color: #f1f5f9;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .feed-item-kelas {
            font-size: 11px;
            color: var(--text-muted);
        }

        .feed-item-right {
            text-align: right;
            flex-shrink: 0;
        }

        .feed-item-time {
            font-size: 12px;
            font-weight: 700;
            color: #38bdf8;
            font-variant-numeric: tabular-nums;
        }

        .feed-item-status {
            font-size: 10px;
            font-weight: 600;
        }

        .status-tepat { color: #34d399; }
        .status-telat { color: #fbbf24; }
        .status-pulang { color: #60a5fa; }

        .feed-empty {
            text-align: center;
            padding: 30px 10px;
            color: var(--text-muted);
            font-size: 13px;
        }

        /* LIBUR VIEW FULL */
        .holiday-card {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(239, 68, 68, 0.15));
            border: 2px solid rgba(245, 158, 11, 0.4);
            border-radius: 20px;
            padding: 40px 20px;
            text-align: center;
            grid-column: 1 / -1;
            margin: 40px auto;
            max-width: 600px;
        }

        .holiday-icon {
            font-size: 48px;
            color: #fbbf24;
            margin-bottom: 16px;
        }

        .holiday-title {
            font-size: 26px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 8px;
        }

        .holiday-desc {
            font-size: 15px;
            color: #fde68a;
        }
    </style>
</head>
<body>

    <!-- TOP HEADER -->
    <header class="header">
        <div class="header-brand">
            <div class="header-logo">
                <i class="fas fa-qrcode"></i>
            </div>
            <div class="header-title">
                <h1>ABSENSI QR SISWA</h1>
                <p>High-Speed Kiosk Scanner • SimSGTK</p>
            </div>
        </div>

        <div class="header-clock">
            <div class="clock-time" id="liveClock">00:00:00 WIB</div>
            <div class="clock-date" id="liveDate">Memuat tanggal...</div>
        </div>
    </header>

    <?php if (isset($libur) && $libur == true): ?>
        <!-- TAMPILAN JIKA HARI INI LIBUR -->
        <div class="holiday-card">
            <div class="holiday-icon"><i class="fas fa-calendar-times"></i></div>
            <div class="holiday-title">HARI INI LIBUR</div>
            <div class="holiday-desc">
                Hari <strong><?= $hari_nama ?></strong> tidak ada jadwal absensi aktif.<br>
                <?= !empty($keterangan_libur) ? htmlspecialchars($keterangan_libur) : 'Selamat beristirahat!' ?>
            </div>
            <div style="margin-top:24px;">
                <a href="<?= site_url('AbsensiQRAdmin') ?>" style="display:inline-block; padding:10px 20px; background:#2563eb; color:#fff; text-decoration:none; border-radius:10px; font-weight:700; font-size:14px;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard Admin
                </a>
            </div>
        </div>
    <?php else: ?>

        <!-- MAIN CONTENT FOR ACTIVE ATTENDANCE -->
        <main class="main-container">

            <!-- LEFT: SCANNER AREA -->
            <section class="scanner-section">
                <!-- SCHEDULE STRIP -->
                <div class="schedule-bar">
                    <div class="schedule-item">
                        <div class="schedule-icon icon-hari"><i class="fas fa-calendar-day"></i></div>
                        <div class="schedule-text">
                            <span>Hari Ini</span>
                            <strong><?= $hari_nama ?></strong>
                        </div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-icon icon-masuk"><i class="fas fa-sign-in-alt"></i></div>
                        <div class="schedule-text">
                            <span>Jam Masuk</span>
                            <strong><?= $jam_masuk ?> WIB</strong>
                        </div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-icon icon-pulang"><i class="fas fa-sign-out-alt"></i></div>
                        <div class="schedule-text">
                            <span>Jam Pulang</span>
                            <strong><?= $jam_pulang ?> WIB</strong>
                        </div>
                    </div>
                </div>

                <!-- CAMERA CARD -->
                <div class="camera-card">
                    <!-- TOOLBAR (Camera Switch, Torch, TTS Toggle, Sound) -->
                    <div class="scanner-controls">
                        <div class="camera-select-wrapper">
                            <i class="fas fa-video text-muted"></i>
                            <select id="cameraSelect">
                                <option value="">Mencari kamera...</option>
                            </select>
                        </div>

                        <button id="btnTorch" class="btn-toggle-tool" style="display:none;" title="Nyalakan Lampu Flash">
                            <i class="fas fa-bolt"></i> Flash
                        </button>

                        <button id="btnVoice" class="btn-toggle-tool active" title="Ucapkan Nama Siswa (Voice TTS)">
                            <i class="fas fa-volume-up"></i> Suara
                        </button>

                        <a href="<?= site_url('AbsensiQRAdmin') ?>" class="btn-toggle-tool" title="Menu Admin">
                            <i class="fas fa-cog"></i> Admin
                        </a>
                    </div>

                    <!-- CAMERA VIEWFINDER WITH RETICLE & LASER -->
                    <div class="reader-wrapper">
                        <div id="reader"></div>

                        <div class="scan-overlay">
                            <div class="reticle-box">
                                <span class="corner corner-tl"></span>
                                <span class="corner corner-tr"></span>
                                <span class="corner corner-bl"></span>
                                <span class="corner corner-br"></span>
                                <div class="laser-line"></div>
                            </div>
                            <div class="scan-hint">
                                <span class="scan-pulse-dot"></span>
                                <span id="scannerStatusText">Arahkan QR Siswa ke Kotak</span>
                            </div>
                        </div>
                    </div>

                    <!-- FLOATING POPUP NOTIFIKASI HASIL SCAN INSTAN -->
                    <div id="scanResultCard" class="scan-result-card hidden">
                        <div class="result-avatar" id="resultAvatar">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="result-info">
                            <div class="result-header">
                                <span class="result-status-badge badge-masuk-tepat" id="resultBadge">HADIR TEPAT</span>
                                <span class="result-time" id="resultTime">00:00:00</span>
                            </div>
                            <div class="result-name" id="resultName">Nama Siswa</div>
                            <div class="result-meta">
                                <span><i class="fas fa-id-card"></i> NIS: <strong id="resultNis">-</strong></span>
                                <span><i class="fas fa-school"></i> Kelas: <strong id="resultKelas">-</strong></span>
                            </div>
                            <div class="result-countdown">
                                <div class="result-countdown-bar" id="resultBar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- RIGHT: DASHBOARD STATS & RECENT SCANS LIVE FEED -->
            <aside class="sidebar-section">
                <!-- STATS CARDS -->
                <div class="stats-grid">
                    <div class="stat-card stat-total">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div>
                            <div class="stat-val" id="statTotal"><?= $stats['total'] ?? 0 ?></div>
                            <div class="stat-lbl">Total Hadir</div>
                        </div>
                    </div>
                    <div class="stat-card stat-tepat">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <div class="stat-val" id="statTepat"><?= $stats['tepat'] ?? 0 ?></div>
                            <div class="stat-lbl">Tepat Waktu</div>
                        </div>
                    </div>
                    <div class="stat-card stat-telat">
                        <div class="stat-icon"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="stat-val" id="statTelat"><?= $stats['telat'] ?? 0 ?></div>
                            <div class="stat-lbl">Terlambat</div>
                        </div>
                    </div>
                    <div class="stat-card stat-pulang">
                        <div class="stat-icon"><i class="fas fa-door-open"></i></div>
                        <div>
                            <div class="stat-val" id="statPulang"><?= $stats['pulang'] ?? 0 ?></div>
                            <div class="stat-lbl">Pulang</div>
                        </div>
                    </div>
                </div>

                <!-- LIVE FEED OF RECENT ATTENDEES -->
                <div class="feed-card">
                    <div class="feed-header">
                        <div class="feed-title">
                            <i class="fas fa-history text-primary"></i>
                            Aktivitas Terkini
                        </div>
                        <span class="feed-badge-live">Live Sync</span>
                    </div>

                    <ul class="feed-list" id="recentFeedList">
                        <?php if (!empty($recent_scans)): ?>
                            <?php foreach ($recent_scans as $item): ?>
                                <li class="feed-item">
                                    <div class="feed-item-info">
                                        <div class="feed-item-avatar">
                                            <?php if (!empty($item->foto) && file_exists(FCPATH . 'uploads/siswa/' . $item->foto)): ?>
                                                <img src="<?= base_url('uploads/siswa/' . $item->foto) ?>" alt="Foto">
                                            <?php else: ?>
                                                <i class="fas fa-user"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="feed-item-text">
                                            <div class="feed-item-name"><?= htmlspecialchars($item->nama_siswa ?? '-') ?></div>
                                            <div class="feed-item-kelas"><?= htmlspecialchars($item->nama_kelas ?? '-') ?> • <?= $item->nis ?></div>
                                        </div>
                                    </div>
                                    <div class="feed-item-right">
                                        <div class="feed-item-time"><?= $item->jam_masuk ?></div>
                                        <div class="feed-item-status <?= $item->status === 'Tepat' ? 'status-tepat' : 'status-telat' ?>">
                                            <?= $item->status ?>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="feed-empty" id="feedEmptyNotice">
                                <i class="fas fa-inbox fa-2x mb-2" style="opacity:0.4;"></i>
                                <p>Belum ada siswa yang melakukan absensi hari ini.</p>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </aside>

        </main>
    <?php endif; ?>

    <!-- AUDIO SOUNDS -->
    <audio id="sndSuccess" src="<?= base_url('assets/sound/success.mp3') ?>" preload="auto"></audio>
    <audio id="sndError" src="<?= base_url('assets/sound/error.mp3') ?>" preload="auto"></audio>

    <!-- LOGIC JAVASCRIPT SCANNER -->
    <script>
    (function() {
        // 1. LIVE DIGITAL CLOCK
        function updateLiveClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            const clockEl = document.getElementById('liveClock');
            if (clockEl) {
                clockEl.textContent = `${hours}:${minutes}:${seconds} WIB`;
            }

            const dateEl = document.getElementById('liveDate');
            if (dateEl) {
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                dateEl.textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
            }
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        <?php if (!isset($libur) || $libur == false): ?>

        // 2. AUDIO SYNTHESIZER (0ms LATENCY BEEP FALLBACK)
        let audioCtx = null;
        function playChime(type) {
            try {
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }

                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);

                if (type === 'success') {
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(880, audioCtx.currentTime); // A5
                    osc.frequency.exponentialRampToValueAtTime(1760, audioCtx.currentTime + 0.12); // A6
                    gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.2);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.2);
                } else if (type === 'warning') {
                    osc.type = 'triangle';
                    osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
                    osc.frequency.setValueAtTime(440, audioCtx.currentTime + 0.1);
                    gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.25);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.25);
                } else {
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(220, audioCtx.currentTime);
                    osc.frequency.setValueAtTime(164.81, audioCtx.currentTime + 0.12);
                    gain.gain.setValueAtTime(0.35, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.3);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.3);
                }
            } catch (e) {
                // Fallback to MP3 audio tags
                const audio = document.getElementById(type === 'success' ? 'sndSuccess' : 'sndError');
                if (audio) {
                    audio.currentTime = 0;
                    audio.play().catch(() => {});
                }
            }
        }

        // 3. TEXT-TO-SPEECH (TTS) VOICE ENGINE
        let voiceEnabled = true;
        const btnVoice = document.getElementById('btnVoice');
        if (btnVoice) {
            btnVoice.addEventListener('click', function() {
                voiceEnabled = !voiceEnabled;
                btnVoice.classList.toggle('active', voiceEnabled);
                btnVoice.innerHTML = voiceEnabled ? '<i class="fas fa-volume-up"></i> Suara' : '<i class="fas fa-volume-mute"></i> Bisu';
            });
        }

        function speakText(text) {
            if (!voiceEnabled || !('speechSynthesis' in window)) return;
            try {
                window.speechSynthesis.cancel(); // Stop any pending speech
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'id-ID';
                utterance.rate = 1.15; // Slightly faster for quick turnaround
                utterance.pitch = 1.0;
                window.speechSynthesis.speak(utterance);
            } catch (e) {
                console.error('Speech synthesis error:', e);
            }
        }

        // 4. SCANNER STATE & RAPID DEBOUNCE
        let html5QrCode = null;
        let isProcessing = false;
        let lastScannedToken = "";
        let lastScannedTimestamp = 0;
        let hideResultTimer = null;
        let currentCameraId = null;

        const scanResultCard = document.getElementById('scanResultCard');
        const scannerStatusText = document.getElementById('scannerStatusText');
        const cameraSelect = document.getElementById('cameraSelect');

        // 5. PROCESS ATTENDANCE SCAN VIA AJAX
        async function processScan(token, sumber = 'scan_qr') {
            const now = Date.now();

            // Cegah duplicate scan token yang sama dalam 3.5 detik
            if (token === lastScannedToken && (now - lastScannedTimestamp) < 3500) {
                return;
            }

            // Jika sedang memproses request lain, tahan sebentar (min 400ms cooldown)
            if (isProcessing) {
                return;
            }

            isProcessing = true;
            lastScannedToken = token;
            lastScannedTimestamp = now;

            if (scannerStatusText) {
                scannerStatusText.textContent = "⚡ Memverifikasi QR Siswa...";
            }

            try {
                const formData = new FormData();
                formData.append('token', token);
                formData.append('sumber', sumber);

                const response = await fetch("<?= site_url('AbsensiQR/proses_scan') ?>", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const res = await response.json();
                handleScanResult(res);

            } catch (err) {
                console.error("Gagal mengirim scan:", err);
                playChime('error');
                showNotificationCard({
                    status: 'error',
                    nama: 'Koneksi Terputus',
                    nis: '-',
                    kelas: '-',
                    jam: new Date().toLocaleTimeString(),
                    status_absen: 'Gagal',
                    type: 'error',
                    message: 'Gagal menghubungi server. Periksa jaringan.'
                });
            } finally {
                // Buka kunci pemindaian siswa berikutnya setelah 500ms
                setTimeout(() => {
                    isProcessing = false;
                    if (scannerStatusText) {
                        scannerStatusText.textContent = "Arahkan QR Siswa ke Kotak";
                    }
                }, 500);
            }
        }

        // 6. TAMPILKAN HASIL SCAN & UPDATE UI
        function handleScanResult(res) {
            // Mainkan suara audio & TTS
            if (res.status === 'success') {
                playChime('success');
                if (res.type === 'masuk') {
                    speakText(`${res.nama}, Hadir`);
                } else if (res.type === 'pulang') {
                    speakText(`${res.nama}, Pulang`);
                }
            } else if (res.status === 'warning') {
                playChime('warning');
                speakText(res.message || `${res.nama}, Perhatian`);
            } else {
                playChime('error');
                speakText('QR tidak terdaftar');
            }

            // Update Notification Card
            showNotificationCard(res);

            // Update Live Counters
            if (res.stats) {
                updateStatsUI(res.stats);
            }

            // Tambahkan ke Live Feed
            if (res.status === 'success') {
                addToLiveFeed(res);
            }
        }

        // 7. RENDER NOTIFICATION POPUP CARD
        function showNotificationCard(res) {
            if (!scanResultCard) return;

            clearTimeout(hideResultTimer);

            // Reset class
            scanResultCard.className = 'scan-result-card';
            if (res.status === 'warning') {
                scanResultCard.classList.add('type-warning');
            } else if (res.status === 'error') {
                scanResultCard.classList.add('type-error');
            }

            // Elements
            const avatarEl = document.getElementById('resultAvatar');
            const badgeEl  = document.getElementById('resultBadge');
            const timeEl   = document.getElementById('resultTime');
            const nameEl   = document.getElementById('resultName');
            const nisEl    = document.getElementById('resultNis');
            const kelasEl  = document.getElementById('resultKelas');
            const barEl    = document.getElementById('resultBar');

            nameEl.textContent = res.nama || 'Siswa Tidak Dikenal';
            nisEl.textContent = res.nis || '-';
            kelasEl.textContent = res.kelas || '-';
            timeEl.textContent = res.jam ? `${res.jam} WIB` : '';

            // Badge text & class
            if (res.type === 'masuk') {
                badgeEl.className = 'result-status-badge ' + (res.status_absen === 'Tepat' ? 'badge-masuk-tepat' : 'badge-masuk-telat');
                badgeEl.textContent = res.status_absen === 'Tepat' ? 'HADIR TEPAT' : 'TERLAMBAT';
            } else if (res.type === 'pulang') {
                badgeEl.className = 'result-status-badge badge-pulang';
                badgeEl.textContent = 'ABSEN PULANG';
            } else if (res.type === 'sudah_pulang') {
                badgeEl.className = 'result-status-badge badge-masuk-telat';
                badgeEl.textContent = 'SUDAH PULANG';
            } else if (res.type === 'belum_waktu') {
                badgeEl.className = 'result-status-badge badge-masuk-telat';
                badgeEl.textContent = 'BELUM WAKTU';
            } else {
                badgeEl.className = 'result-status-badge badge-error';
                badgeEl.textContent = 'TIDAK TERDAFTAR';
            }

            // Avatar Foto
            if (res.foto) {
                avatarEl.innerHTML = `<img src="${res.foto}" alt="Foto">`;
            } else {
                avatarEl.innerHTML = `<i class="fas fa-user-graduate"></i>`;
            }

            // Animate progress bar
            if (barEl) {
                barEl.style.transition = 'none';
                barEl.style.width = '100%';
                setTimeout(() => {
                    barEl.style.transition = 'width 2.6s linear';
                    barEl.style.width = '0%';
                }, 20);
            }

            // Auto-hide popup banner after 2.8s
            hideResultTimer = setTimeout(() => {
                scanResultCard.classList.add('hidden');
            }, 2800);
        }

        // 8. UPDATE STATISTIK UI
        function updateStatsUI(stats) {
            const elTotal = document.getElementById('statTotal');
            const elTepat = document.getElementById('statTepat');
            const elTelat = document.getElementById('statTelat');
            const elPulang = document.getElementById('statPulang');

            if (elTotal) elTotal.textContent = stats.total ?? 0;
            if (elTepat) elTepat.textContent = stats.tepat ?? 0;
            if (elTelat) elTelat.textContent = stats.telat ?? 0;
            if (elPulang) elPulang.textContent = stats.pulang ?? 0;
        }

        // 9. INSERT BARU KE FEED AKTIVITAS TERKINI
        function addToLiveFeed(res) {
            const feedList = document.getElementById('recentFeedList');
            const emptyNotice = document.getElementById('feedEmptyNotice');
            if (emptyNotice) {
                emptyNotice.remove();
            }

            if (!feedList) return;

            const li = document.createElement('li');
            li.className = 'feed-item';

            const avatarHtml = res.foto ? `<img src="${res.foto}" alt="Foto">` : `<i class="fas fa-user"></i>`;
            const statusClass = res.status_absen === 'Tepat' ? 'status-tepat' : 'status-telat';

            li.innerHTML = `
                <div class="feed-item-info">
                    <div class="feed-item-avatar">${avatarHtml}</div>
                    <div class="feed-item-text">
                        <div class="feed-item-name">${escapeHtml(res.nama)}</div>
                        <div class="feed-item-kelas">${escapeHtml(res.kelas)} • ${escapeHtml(res.nis)}</div>
                    </div>
                </div>
                <div class="feed-item-right">
                    <div class="feed-item-time">${res.jam}</div>
                    <div class="feed-item-status ${statusClass}">${res.status_absen || 'Hadir'}</div>
                </div>
            `;

            feedList.insertBefore(li, feedList.firstChild);

            // Batasi feed maksimal 10 baris
            while (feedList.children.length > 10) {
                feedList.removeChild(feedList.lastChild);
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            return text.replace(/[&<>"']/g, function(m) {
                return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m];
            });
        }

        // 10. DUKUNGAN HARDWARE USB 2D BARCODE / QR SCANNER GUN
        let barcodeBuffer = "";
        let barcodeLastKeyTime = 0;

        window.addEventListener('keydown', function(e) {
            // Abaikan input jika user sedang fokus mengetik di form field
            if (['INPUT', 'SELECT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
                return;
            }

            const now = Date.now();
            if (now - barcodeLastKeyTime > 150) {
                barcodeBuffer = ""; // Timeout: bukan ketikan cepat scanner
            }
            barcodeLastKeyTime = now;

            if (e.key === 'Enter') {
                if (barcodeBuffer.length >= 3) {
                    processScan(barcodeBuffer.trim(), 'scan_gun');
                    barcodeBuffer = "";
                }
            } else if (e.key.length === 1) {
                barcodeBuffer += e.key;
            }
        });

        // 11. INISIALISASI KAMERA DENGAN RESOLUSI TINGGI & BARCODE DETECTOR NATIVE
        async function startCameraScanner(cameraId) {
            if (html5QrCode) {
                try {
                    await html5QrCode.stop();
                } catch(e) {}
            }

            html5QrCode = new Html5Qrcode("reader");

            // Konfigurasi sensitivitas tinggi untuk 1000 siswa mengantre
            const config = {
                fps: 25, // 25 frames per detik untuk respons cepat
                qrbox: function(width, height) {
                    const minEdge = Math.min(width, height);
                    const boxSize = Math.floor(minEdge * 0.84);
                    return { width: Math.max(200, boxSize), height: Math.max(200, boxSize) };
                },
                aspectRatio: 1.0,
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true // Menggunakan Web API C++ bawaan browser Chromium untuk deteksi 10x lebih sensitif & cepat
                },
                videoConstraints: {
                    facingMode: "environment",
                    width: { ideal: 1280, min: 640 },
                    height: { ideal: 720, min: 480 }
                }
            };

            const targetSource = cameraId ? { deviceId: { exact: cameraId } } : { facingMode: "environment" };

            html5QrCode.start(
                targetSource,
                config,
                function onQrCodeSuccess(decodedText) {
                    processScan(decodedText, 'scan_qr');
                },
                function onQrCodeProgress() {
                    // Frame scan progress - normal pass
                }
            ).then(() => {
                currentCameraId = cameraId;
                if (scannerStatusText) {
                    scannerStatusText.textContent = "Kamera Aktif • Siap Scan";
                }
            }).catch(err => {
                console.error("Gagal mengaktifkan kamera:", err);
                if (scannerStatusText) {
                    scannerStatusText.textContent = "Kamera Gagal Dimuat. Periksa Izin.";
                }
            });
        }

        // 12. ENUMERASI PERANGKAT KAMERA
        async function initCameras() {
            try {
                const devices = await Html5Qrcode.getCameras();
                if (devices && devices.length > 0) {
                    cameraSelect.innerHTML = '';
                    let defaultCamId = devices[0].id;

                    devices.forEach((cam, idx) => {
                        const opt = document.createElement('option');
                        opt.value = cam.id;
                        opt.textContent = cam.label || `Kamera ${idx + 1}`;
                        // Prioritaskan kamera belakang / external USB scanner
                        if (/back|rear|environment|belakang|external/i.test(cam.label)) {
                            opt.selected = true;
                            defaultCamId = cam.id;
                        }
                        cameraSelect.appendChild(opt);
                    });

                    cameraSelect.addEventListener('change', function() {
                        if (this.value) {
                            startCameraScanner(this.value);
                        }
                    });

                    startCameraScanner(defaultCamId);
                } else {
                    cameraSelect.innerHTML = '<option value="">Tidak ada kamera terdeteksi</option>';
                    startCameraScanner(null);
                }
            } catch (e) {
                console.error("Error getCameras:", e);
                cameraSelect.innerHTML = '<option value="">Kamera Default</option>';
                startCameraScanner(null);
            }
        }

        // Jalankan inisialisasi kamera setelah DOM siap
        document.addEventListener('DOMContentLoaded', initCameras);

        // Auto-refresh feed & stats setiap 30 detik untuk sinkronisasi latar belakang
        setInterval(async () => {
            try {
                const res = await fetch("<?= site_url('AbsensiQR/recent_scans') ?>");
                const json = await res.json();
                if (json.status === 'success' && json.stats) {
                    updateStatsUI(json.stats);
                }
            } catch (e) {}
        }, 30000);

        <?php endif; ?>
    })();
    </script>
</body>
</html>
