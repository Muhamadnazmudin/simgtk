
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
date_default_timezone_set('Asia/Jakarta');

$hari_ini = date('Y-m-d');
$jadwal_hari_ini = null;

foreach ($jadwal as $item) {
    if ($item['tanggal'] === $hari_ini) {
        $jadwal_hari_ini = $item;
        break;
    }
}

$bulan_pendek = array(
    1 => 'JAN', 2 => 'FEB', 3 => 'MAR',
    4 => 'APR', 5 => 'MEI', 6 => 'JUN',
    7 => 'JUL', 8 => 'AGU', 9 => 'SEP',
    10 => 'OKT', 11 => 'NOV', 12 => 'DES'
);

$hari_nama = array(
    'Sunday' => 'Minggu',
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu'
);

$nama_hari_ini = isset($hari_nama[date('l')])
    ? $hari_nama[date('l')]
    : date('l');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= html_escape(isset($title) ? $title : 'Jadwal Salat Dzuhur'); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        :root {
            --green: #087f5b;
            --green-dark: #075c46;
            --green-light: #d8f3e7;
            --orange: #ff9f43;
            --cream: #fffaf0;
            --ink: #183b35;
            --muted: #71817c;
            --paper: #f5f7f2;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--paper);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
        }

        a {
            text-decoration: none;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e9eee7;
            position: relative;
            z-index: 5;
        }

        .topbar-inner {
            min-height: 76px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .school-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--ink);
        }

        .brand-mark {
            width: 45px;
            height: 45px;
            border-radius: 15px 15px 15px 5px;
            background: var(--green);
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 20px;
            box-shadow: 4px 4px 0 #d5eee2;
        }

        .brand-name {
            font-family: 'Manrope', sans-serif;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: -.4px;
        }

        .brand-caption {
            color: #84918b;
            font-size: 11px;
            margin-top: 3px;
        }

        .home-link {
            color: var(--green);
            font-size: 12px;
            font-weight: 800;
            padding: 11px 15px;
            border: 1px solid #dce9df;
            border-radius: 10px;
            background: #fff;
            transition: .2s;
        }

        .home-link:hover {
            background: var(--green);
            color: #fff;
            border-color: var(--green);
        }

        /* HERO */

        .hero {
            background: var(--green-dark);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            opacity: .12;
            background-image:
                linear-gradient(45deg, transparent 46%, #fff 47%, #fff 49%, transparent 50%),
                linear-gradient(-45deg, transparent 46%, #fff 47%, #fff 49%, transparent 50%);
            background-size: 38px 38px;
            mask-image: linear-gradient(to right, transparent, #000);
            pointer-events: none;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 50%;
            right: -95px;
            top: -185px;
            box-shadow:
                0 0 0 28px rgba(255,255,255,.035),
                0 0 0 58px rgba(255,255,255,.025);
        }

        .hero-inner {
            position: relative;
            z-index: 1;
            padding-top: 49px;
            padding-bottom: 103px;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 220px;
            align-items: center;
            gap: 40px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: #b9f2d6;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.6px;
        }

        .eyebrow::before {
            content: "";
            width: 25px;
            height: 3px;
            background: var(--orange);
            border-radius: 3px;
        }

        .hero h1 {
            font-family: 'Manrope', sans-serif;
            font-size: clamp(35px, 5vw, 57px);
            font-weight: 800;
            letter-spacing: -2.8px;
            line-height: 1.1;
            margin: 19px 0 16px;
        }

        .hero h1 span {
            color: #ffbd73;
        }

        .hero-description {
            color: rgba(255,255,255,.75);
            font-size: 13px;
            line-height: 1.9;
            max-width: 510px;
            margin-bottom: 20px;
        }

        .hero-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .meta-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            border: 1px solid rgba(255,255,255,.17);
            border-radius: 9px;
            background: rgba(255,255,255,.08);
            color: #f2fff8;
            font-size: 11px;
            font-weight: 600;
        }

        .hero-art {
            position: relative;
            width: 200px;
            height: 200px;
            margin: auto;
            display: grid;
            place-items: center;
        }

        .art-ring {
            position: absolute;
            inset: 7px;
            border: 1px dashed rgba(255,255,255,.3);
            border-radius: 50%;
        }

        .art-ring::before,
        .art-ring::after {
            content: "";
            position: absolute;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--orange);
        }

        .art-ring::before {
            top: 23px;
            right: 24px;
        }

        .art-ring::after {
            bottom: 25px;
            left: 15px;
            background: #a7e9ca;
        }

        .art-center {
            width: 132px;
            height: 132px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #f8c77f;
            color: var(--green-dark);
            border: 8px solid rgba(255,255,255,.13);
            box-shadow: 0 15px 35px rgba(0,0,0,.13);
            font-size: 51px;
        }

        .art-spark {
            position: absolute;
            color: #ffca82;
            font-size: 19px;
        }

        .spark-one {
            top: 22px;
            left: 24px;
        }

        .spark-two {
            bottom: 28px;
            right: 15px;
            color: #c4f4dc;
            font-size: 13px;
        }

        /* MAIN */

        .main-content {
            position: relative;
            margin-top: -57px;
            padding-bottom: 48px;
            z-index: 2;
        }

        .today-panel {
            display: grid;
            grid-template-columns: 1fr 240px;
            background: white;
            border-radius: 19px;
            box-shadow: 0 13px 36px rgba(27,61,44,.09);
            overflow: hidden;
        }

        .today-main {
            padding: 28px 31px;
            position: relative;
        }

        .today-main::after {
            content: "";
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 16px solid #f0f8f2;
            right: -38px;
            bottom: -48px;
            pointer-events: none;
        }

        .today-kicker {
            color: var(--green);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .today-date {
            font-size: 12px;
            color: var(--muted);
            margin-top: 9px;
        }

        .today-clock {
            font-family: 'Manrope', sans-serif;
            font-size: clamp(42px, 6vw, 61px);
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: -3px;
            color: var(--green-dark);
            margin: 8px 0 4px;
        }

        .today-clock .colon {
            color: var(--orange);
        }

        .today-foot {
            color: var(--muted);
            font-size: 11px;
        }

        .today-side {
            background: var(--cream);
            border-left: 1px solid #f2ead8;
            padding: 25px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
        }

        .side-icon {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            background: #ffe4b8;
            color: #9a5416;
            border-radius: 13px;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .side-title {
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            font-weight: 800;
        }

        .side-description {
            font-size: 11px;
            color: #8c7960;
            line-height: 1.7;
            margin-top: 6px;
        }

        .section-title-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin: 36px 0 19px;
        }

        .section-overline {
            color: #a76a2a;
            text-transform: uppercase;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.8px;
            margin-bottom: 7px;
        }

        .section-title {
            font-family: 'Manrope', sans-serif;
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -1px;
            margin: 0;
            color: var(--ink);
        }

        .section-description {
            color: var(--muted);
            font-size: 12px;
            margin-top: 7px;
        }

        .week-count {
            background: #ffead0;
            color: #99551c;
            border-radius: 9px;
            padding: 9px 12px;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        /* SCHEDULE */

        .schedule-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px;
        }

        .schedule-item {
            position: relative;
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 14px;
            background: white;
            padding: 17px;
            border: 1px solid #e8ede5;
            border-radius: 14px;
            transition: transform .18s, box-shadow .18s;
            overflow: hidden;
        }

        .schedule-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 9px 24px rgba(30,70,47,.08);
        }

        .schedule-item.is-today {
            border-color: var(--green);
            background: #f4fff8;
        }

        .schedule-item.is-today::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 4px;
            background: var(--green);
        }

        .schedule-item.is-friday:not(.is-today) {
            background: #fffbf4;
        }

        .date-tile {
            flex: 0 0 51px;
            width: 51px;
            height: 56px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            color: #50645a;
            background: #edf2eb;
        }

        .is-today .date-tile {
            color: white;
            background: var(--green);
        }

        .is-friday .date-tile {
            background: #ffe5c1;
            color: #945119;
        }

        .is-today.is-friday .date-tile {
            background: var(--green);
            color: white;
        }

        .date-number {
            font-family: 'Manrope', sans-serif;
            font-size: 22px;
            line-height: 1.1;
            font-weight: 800;
        }

        .date-month {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .5px;
            margin-top: 3px;
        }

        .schedule-detail {
            flex: 1;
            min-width: 0;
        }

        .schedule-day {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
            font-family: 'Manrope', sans-serif;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .schedule-full-date {
            color: var(--muted);
            font-size: 11px;
        }

        .today-tag {
            background: #c9f2dc;
            color: #08714e;
            border-radius: 5px;
            padding: 4px 6px;
            font-family: 'DM Sans', sans-serif;
            font-size: 9px;
            font-weight: 800;
        }

        .friday-tag {
            background: #ffebd0;
            color: #96541c;
            border-radius: 5px;
            padding: 4px 6px;
            font-family: 'DM Sans', sans-serif;
            font-size: 9px;
            font-weight: 800;
        }

        .schedule-time {
            flex: 0 0 auto;
            text-align: right;
            padding-left: 4px;
        }

        .time-value {
            font-family: 'Manrope', sans-serif;
            color: var(--green-dark);
            font-size: 19px;
            font-weight: 800;
            white-space: nowrap;
        }

        .time-caption {
            color: #84918b;
            font-size: 10px;
            margin-top: 2px;
        }

        .not-ready {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            background: #fff0cc;
            color: #93510d;
            border-radius: 8px;
            padding: 8px 9px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        /* FOOTNOTE */

        .notice {
            display: flex;
            gap: 13px;
            align-items: flex-start;
            margin-top: 22px;
            padding: 18px 20px;
            background: #eaf4ff;
            border: 1px solid #d7e8f8;
            border-radius: 13px;
        }

        .notice-symbol {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            color: #2c69a0;
            background: white;
        }

        .notice-title {
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 4px;
            color: #234d70;
        }

        .notice-text {
            color: #526f87;
            font-size: 11px;
            line-height: 1.8;
            margin: 0;
        }

        .footer {
            background: #fff;
            border-top: 1px solid #e7ece4;
        }

        .footer-inner {
            min-height: 95px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .footer-school {
            font-family: 'Manrope', sans-serif;
            font-size: 12px;
            font-weight: 800;
        }

        .footer-caption {
            color: #839088;
            font-size: 10px;
            line-height: 1.8;
            margin-top: 4px;
        }

        .footer-right {
            text-align: right;
        }

        .footer-dot {
            display: inline-block;
            width: 7px;
            height: 7px;
            background: #13a875;
            border-radius: 50%;
            margin-right: 5px;
        }

        @media (max-width: 767px) {
            .topbar-inner {
                min-height: 68px;
            }

            .brand-mark {
                width: 40px;
                height: 40px;
            }

            .brand-name {
                font-size: 12px;
            }

            .hero-inner {
                padding-top: 38px;
                padding-bottom: 86px;
            }

            .hero-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .hero h1 {
                font-size: 43px;
                letter-spacing: -2px;
            }

            .hero-art {
                display: none;
            }

            .main-content {
                margin-top: -48px;
            }

            .today-panel {
                grid-template-columns: 1fr;
            }

            .today-main {
                padding: 25px 22px;
            }

            .today-side {
                padding: 15px 22px;
                flex-direction: row;
                align-items: center;
                gap: 12px;
                border-left: 0;
                border-top: 1px solid #f2ead8;
            }

            .side-icon {
                margin: 0;
                flex: 0 0 39px;
                width: 39px;
                height: 39px;
            }

            .side-description {
                margin-top: 3px;
            }

            .section-title-row {
                align-items: flex-start;
                margin-top: 30px;
            }

            .section-title {
                font-size: 22px;
            }

            .schedule-list {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .schedule-item {
                padding: 15px;
                gap: 12px;
            }

            .date-tile {
                flex-basis: 48px;
                width: 48px;
                height: 53px;
            }

            .time-value {
                font-size: 18px;
            }

            .footer-inner {
                padding-top: 20px;
                padding-bottom: 20px;
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
            }

            .footer-right {
                text-align: left;
            }
        }

        @media (max-width: 380px) {
            .home-link {
                padding: 10px;
                font-size: 11px;
            }

            .brand-caption {
                font-size: 10px;
            }

            .hero h1 {
                font-size: 37px;
            }

            .schedule-item {
                padding: 12px;
                gap: 9px;
            }

            .date-tile {
                flex-basis: 43px;
                width: 43px;
                height: 49px;
            }

            .date-number {
                font-size: 19px;
            }

            .schedule-day {
                font-size: 12px;
            }

            .schedule-full-date {
                font-size: 10px;
            }

            .time-value {
                font-size: 16px;
            }

            .not-ready {
                padding: 7px;
                font-size: 9px;
            }
        }
    </style>
</head>

<body>

<header class="topbar">
    <div class="container">
        <div class="topbar-inner">

            <a href="<?= site_url(); ?>" class="school-brand">
                <div class="brand-mark">
                    <i class="fa-solid fa-mosque"></i>
                </div>

                <div>
                    <div class="brand-name">SMK NEGERI 1 CILIMUS</div>
                    <div class="brand-caption">Portal Informasi Sekolah</div>
                </div>
            </a>

            <a href="<?= site_url(); ?>" class="home-link">
                <i class="fa-solid fa-arrow-left me-1"></i>
                Beranda
            </a>

        </div>
    </div>
</header>

<section class="hero">
    <div class="container hero-inner">
        <div class="hero-grid">

            <div>
                <div class="eyebrow">Ruang Ibadah Sekolah</div>

                <h1>
                    Waktunya<br>
                    <span>Salat Dzuhur.</span>
                </h1>

                <p class="hero-description">
                    Luangkan waktu sejenak dari aktivitas belajar.
                    Mari menunaikan ibadah tepat waktu dan menjaga
                    kebiasaan baik di lingkungan sekolah.
                </p>

                <div class="hero-meta">
                    <div class="meta-pill">
                        <i class="fa-regular fa-calendar"></i>
                        <?= html_escape($nama_hari_ini); ?>,
                        <?= date('d'); ?>
                        <?= $bulan_pendek[(int) date('n')]; ?>
                        <?= date('Y'); ?>
                    </div>

                    <div class="meta-pill">
                        <i class="fa-solid fa-location-dot"></i>
                        Cilimus, Kuningan
                    </div>
                </div>
            </div>

            <div class="hero-art" aria-hidden="true">
                <div class="art-ring"></div>
                <i class="fa-solid fa-star art-spark spark-one"></i>
                <i class="fa-solid fa-sparkles art-spark spark-two"></i>
                <div class="art-center">
                    <i class="fa-solid fa-mosque"></i>
                </div>
            </div>

        </div>
    </div>
</section>

<main class="container main-content">

    <section class="today-panel">

        <div class="today-main">
            <div class="today-kicker">
                <i class="fa-solid fa-sun me-1"></i>
                Jadwal Hari Ini
            </div>

            <div class="today-date">
                <?php if ($jadwal_hari_ini): ?>
                    <?= html_escape($jadwal_hari_ini['hari']); ?>,
                    <?= html_escape($jadwal_hari_ini['tanggal_id']); ?>
                <?php else: ?>
                    <?= html_escape($nama_hari_ini); ?>,
                    <?= date('d'); ?>
                    <?= $bulan_pendek[(int) date('n')]; ?>
                    <?= date('Y'); ?>
                <?php endif; ?>
            </div>

            <div class="today-clock">
                <?php if ($jadwal_hari_ini && !empty($jadwal_hari_ini['jam_dzuhur'])): ?>
                    <?php
                    $jam = explode(':', $jadwal_hari_ini['jam_dzuhur']);
                    ?>
                    <?= html_escape($jam[0]); ?><span class="colon">:</span><?= html_escape(isset($jam[1]) ? $jam[1] : '00'); ?>
                <?php else: ?>
                    <span style="font-size:25px;letter-spacing:-1px">
                        Belum tersedia
                    </span>
                <?php endif; ?>
            </div>

            <div class="today-foot">
                <i class="fa-regular fa-clock me-1"></i>
                Waktu Indonesia Barat (WIB)
            </div>
        </div>

        <div class="today-side">
            <div class="side-icon">
                <i class="fa-solid fa-heart"></i>
            </div>

            <div>
                <div class="side-title">Berhenti sejenak, beribadah.</div>
                <div class="side-description">
                    Isi waktu dengan kebaikan dan kembali belajar dengan semangat.
                </div>
            </div>
        </div>

    </section>

    <section>
        <div class="section-title-row">
            <div>
                <div class="section-overline">Agenda pekan ini</div>
                <h2 class="section-title">Jadwal 7 Hari</h2>
                <div class="section-description">
                    Waktu Dzuhur untuk hari ini dan enam hari berikutnya.
                </div>
            </div>

            <div class="week-count">
                <i class="fa-regular fa-calendar-days me-1"></i>
                7 HARI
            </div>
        </div>

        <div class="schedule-list">

            <?php foreach ($jadwal as $item): ?>
                <?php
                $is_today = ($item['tanggal'] === $hari_ini);
                $timestamp = strtotime($item['tanggal']);
                $hari_en = date('l', $timestamp);

                $tanggal_angka = date('j', $timestamp);
                $bulan_angka = (int) date('n', $timestamp);

                $is_friday = ($hari_en === 'Friday');
                ?>

                <article class="schedule-item
                    <?= $is_today ? 'is-today' : ''; ?>
                    <?= $is_friday ? 'is-friday' : ''; ?>">

                    <div class="date-tile">
                        <div class="date-number">
                            <?= $tanggal_angka; ?>
                        </div>
                        <div class="date-month">
                            <?= $bulan_pendek[$bulan_angka]; ?>
                        </div>
                    </div>

                    <div class="schedule-detail">
                        <div class="schedule-day">
                            <?= html_escape($item['hari']); ?>

                            <?php if ($is_today): ?>
                                <span class="today-tag">HARI INI</span>
                            <?php elseif ($is_friday): ?>
                                <span class="friday-tag">JUMAT</span>
                            <?php endif; ?>
                        </div>

                        <div class="schedule-full-date">
                            <?= html_escape($item['tanggal_id']); ?>
                        </div>
                    </div>

                    <div class="schedule-time">
                        <?php if (!empty($item['tersedia']) && !empty($item['jam_dzuhur'])): ?>
                            <?php $jam_item = explode(':', $item['jam_dzuhur']); ?>

                            <div class="time-value">
                                <?= html_escape($jam_item[0]); ?>:<?= html_escape(isset($jam_item[1]) ? $jam_item[1] : '00'); ?>
                            </div>

                            <div class="time-caption">WIB</div>
                        <?php else: ?>
                            <div class="not-ready">
                                <i class="fa-solid fa-circle-minus"></i>
                                Belum tersedia
                            </div>
                        <?php endif; ?>
                    </div>

                </article>
            <?php endforeach; ?>

        </div>
    </section>

    <aside class="notice">
        <div class="notice-symbol">
            <i class="fa-solid fa-circle-info"></i>
        </div>

        <div>
            <div class="notice-title">Tentang jadwal ini</div>
            <p class="notice-text">
                Waktu yang ditampilkan mengikuti jadwal yang telah dimasukkan
                oleh administrator sekolah. Jika waktu belum tersedia,
                silakan hubungi petugas sekolah untuk mendapatkan informasi
                terbaru.
            </p>
        </div>
    </aside>

</main>

<footer class="footer">
    <div class="container">
        <div class="footer-inner">

            <div>
                <div class="footer-school">
                    SMK Negeri 1 Cilimus
                </div>
                <div class="footer-caption">
                    Kabupaten Kuningan, Jawa Barat<br>
                    Informasi jadwal salat Dzuhur sekolah.
                </div>
            </div>

            <div class="footer-right">
                <div class="footer-school">
                    <span class="footer-dot"></span>
                    Portal Publik
                </div>
                <div class="footer-caption">
                    &copy; <?= date('Y'); ?> SMKN 1 Cilimus
                </div>
            </div>

        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
