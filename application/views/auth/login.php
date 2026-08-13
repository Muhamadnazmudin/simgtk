<!doctype html>
<html lang="id">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login - SimSGTK</title>

    <link rel="icon"
          type="image/png"
          href="<?= base_url('assets/img/logobonti.png'); ?>">

    <link rel="stylesheet"
          href="<?= base_url('assets/sbadmin2/vendor/bootstrap/css/bootstrap.min.css') ?>">

    <link rel="stylesheet"
          href="<?= base_url('assets/sbadmin2/vendor/fontawesome-free/css/all.min.css') ?>">


    <style>

        /* =========================================================
           BASE
        ========================================================= */

        * {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            margin: 0;

            min-height: 100vh;

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #edf6ff 0%,
                    #f7fbff 45%,
                    #ffffff 100%
                );

            color: #1f2937;
        }


        /* =========================================================
           PAGE
        ========================================================= */

        .login-page {
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px;
        }


        .login-wrapper {
            width: 100%;
            max-width: 1050px;

            display: grid;

            grid-template-columns: 44% 56%;

            background: #fff;

            border-radius: 24px;

            overflow: hidden;

            box-shadow:
                0 20px 60px rgba(42, 91, 140, .13);

            border: 1px solid rgba(78, 115, 223, .08);

            animation: pageIn .5s ease;
        }


        /* =========================================================
           LEFT PANEL
        ========================================================= */

        .login-info {
            position: relative;

            overflow: hidden;

            padding: 50px 45px;

            background:
                linear-gradient(
                    145deg,
                    #dff0ff 0%,
                    #c9e6ff 55%,
                    #b8dcfa 100%
                );

            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }


        /* Dekorasi */

        .login-info::before {
            content: "";

            position: absolute;

            width: 300px;
            height: 300px;

            border-radius: 50%;

            top: -160px;
            left: -120px;

            background: rgba(255,255,255,.45);
        }


        .login-info::after {
            content: "";

            position: absolute;

            width: 260px;
            height: 260px;

            border-radius: 50%;

            right: -130px;
            bottom: -140px;

            background: rgba(255,255,255,.35);
        }


        .info-content,
        .info-footer {
            position: relative;
            z-index: 2;
        }


        /* =========================================================
           LOGO
        ========================================================= */

        .logo-box {
            width: 70px;
            height: 70px;

            border-radius: 18px;

            background: #fff;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 25px;

            box-shadow:
                0 8px 22px rgba(47, 111, 180, .12);
        }


        .logo-box img {
            max-width: 50px;
            max-height: 50px;

            object-fit: contain;
        }


        .logo-fallback {
            font-size: 28px;
            color: #2f80ed;
        }


        /* =========================================================
           BRAND
        ========================================================= */

        .brand-name {
            font-size: 2rem;

            font-weight: 800;

            color: #12345b;

            margin-bottom: 8px;
        }


        .brand-subtitle {
            font-size: .95rem;

            color: #35628d;

            margin-bottom: 24px;
        }


        .brand-line {
            width: 55px;
            height: 4px;

            border-radius: 5px;

            background: #2f80ed;

            margin-bottom: 25px;
        }


        .brand-description {
            max-width: 390px;

            font-size: .88rem;

            line-height: 1.75;

            color: #42627e;

            margin-bottom: 30px;
        }


        /* =========================================================
           FEATURES
        ========================================================= */

        .feature-item {
            display: flex;
            align-items: center;

            margin-bottom: 15px;
        }


        .feature-icon {
            width: 40px;
            height: 40px;

            flex-shrink: 0;

            border-radius: 11px;

            background: rgba(255,255,255,.85);

            display: flex;
            align-items: center;
            justify-content: center;

            color: #2f80ed;

            margin-right: 12px;

            box-shadow:
                0 4px 10px rgba(47,128,237,.08);
        }


        .feature-text strong {
            display: block;

            color: #163b62;

            font-size: .82rem;

            margin-bottom: 2px;
        }


        .feature-text span {
            display: block;

            color: #5c7893;

            font-size: .73rem;
        }


        .info-footer {
            color: #66809a;

            font-size: .72rem;
        }


        /* =========================================================
           RIGHT PANEL
        ========================================================= */

        .login-form-area {
            padding: 50px 60px;

            display: flex;
            align-items: center;
        }


        .login-form {
            width: 100%;
            max-width: 410px;

            margin: auto;
        }


        /* =========================================================
           HEADER
        ========================================================= */

        .login-header {
            margin-bottom: 30px;
        }


        .login-icon {
            width: 54px;
            height: 54px;

            border-radius: 15px;

            background: #e8f3ff;

            color: #2f80ed;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 21px;

            margin-bottom: 18px;
        }


        .login-header h3 {
            margin: 0 0 7px;

            font-size: 1.65rem;

            font-weight: 800;

            color: #172b4d;
        }


        .login-header p {
            margin: 0;

            font-size: .86rem;

            color: #8190a5;

            line-height: 1.6;
        }


        /* =========================================================
           ERROR
        ========================================================= */

        .login-alert {
            border: 0;

            border-radius: 10px;

            background: #fff1f1;

            color: #c0392b;

            font-size: .82rem;

            padding: 12px 14px;

            margin-bottom: 22px;
        }


        .login-alert i {
            margin-right: 7px;
        }


        /* =========================================================
           FORM
        ========================================================= */

        .form-group-modern {
            margin-bottom: 20px;
        }


        .form-label-modern {
            display: block;

            margin-bottom: 8px;

            font-size: .78rem;

            font-weight: 700;

            color: #344054;
        }


        .input-wrapper {
            position: relative;
        }


        .input-icon {
            position: absolute;

            left: 16px;
            top: 50%;

            transform: translateY(-50%);

            color: #98a2b3;

            font-size: 15px;

            z-index: 2;
        }


        .form-control-modern {
            width: 100%;

            height: 52px;

            border: 1px solid #dfe6ee;

            border-radius: 11px;

            background: #f9fbfd;

            color: #344054;

            font-size: .9rem;

            padding-left: 45px;
            padding-right: 45px;

            outline: none;

            transition:
                border-color .2s ease,
                background .2s ease,
                box-shadow .2s ease;
        }


        .form-control-modern::placeholder {
            color: #aab4c2;
        }


        .form-control-modern:hover {
            background: #fff;

            border-color: #cbd5e1;
        }


        .form-control-modern:focus {
            background: #fff;

            border-color: #5ba3f5;

            box-shadow:
                0 0 0 4px rgba(47,128,237,.10);

            outline: none;
        }


        .input-wrapper:focus-within .input-icon {
            color: #2f80ed;
        }


        /* =========================================================
           SELECT
        ========================================================= */

        select.form-control-modern {

            /* Jangan custom arrow.
               Biarkan browser menangani dropdown native. */

            appearance: auto;
            -webkit-appearance: auto;

            cursor: pointer;

            padding-right: 40px;
        }


        select.form-control-modern option {
            background: #fff;

            color: #344054;
        }


        /* =========================================================
           PASSWORD
        ========================================================= */

        .toggle-password {
            position: absolute;

            right: 16px;
            top: 50%;

            transform: translateY(-50%);

            color: #98a2b3;

            cursor: pointer;

            z-index: 3;

            transition: color .2s ease;
        }


        .toggle-password:hover {
            color: #2f80ed;
        }


        /* =========================================================
           YEAR INFO
        ========================================================= */

        .year-info {
            display: flex;
            align-items: center;

            margin-top: 8px;

            padding: 9px 11px;

            border-radius: 8px;

            background: #f1f8ff;

            color: #5680a8;

            font-size: .72rem;
        }


        .year-info i {
            color: #2f80ed;

            margin-right: 6px;
        }


        /* =========================================================
           BUTTON
        ========================================================= */

        .btn-login {
            width: 100%;

            height: 52px;

            border: 0;

            border-radius: 11px;

            margin-top: 8px;

            background:
                linear-gradient(
                    135deg,
                    #4da3ff,
                    #2f80ed
                );

            color: #fff;

            font-size: .9rem;

            font-weight: 700;

            box-shadow:
                0 8px 18px rgba(47,128,237,.20);

            transition:
                transform .2s ease,
                box-shadow .2s ease;
        }


        .btn-login:hover {
            color: #fff;

            transform: translateY(-2px);

            box-shadow:
                0 12px 25px rgba(47,128,237,.28);
        }


        .btn-login:active {
            transform: translateY(0);
        }


        .btn-login i {
            margin-right: 7px;
        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .login-footer {
            text-align: center;

            margin-top: 27px;

            color: #a1adbb;

            font-size: .72rem;

            line-height: 1.6;
        }


        .login-footer strong {
            color: #68778a;
        }


        /* =========================================================
           ANIMATION
        ========================================================= */

        @keyframes pageIn {

            from {
                opacity: 0;

                transform:
                    translateY(15px)
                    scale(.985);
            }

            to {
                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);
            }

        }


        /* =========================================================
           TABLET
        ========================================================= */

        @media (max-width: 991.98px) {

            .login-wrapper {
                grid-template-columns: 1fr;

                max-width: 520px;
            }


            .login-info {
                min-height: 300px;

                padding: 38px;
            }


            .feature-item {
                display: none;
            }


            .login-form-area {
                padding: 42px 38px;
            }

        }


        /* =========================================================
           MOBILE
        ========================================================= */

        @media (max-width: 575.98px) {

            .login-page {
                padding: 15px;
            }


            .login-wrapper {
                border-radius: 18px;
            }


            .login-info {
                min-height: 235px;

                padding: 28px 25px;
            }


            .logo-box {
                width: 55px;
                height: 55px;

                border-radius: 14px;

                margin-bottom: 18px;
            }


            .logo-box img {
                max-width: 40px;
                max-height: 40px;
            }


            .brand-name {
                font-size: 1.4rem;
            }


            .brand-subtitle {
                font-size: .8rem;

                margin-bottom: 15px;
            }


            .brand-line {
                margin-bottom: 15px;
            }


            .brand-description {
                font-size: .78rem;

                margin-bottom: 0;
            }


            .login-form-area {
                padding: 35px 25px;
            }


            .login-header h3 {
                font-size: 1.4rem;
            }

        }

    </style>

</head>


<body>


<div class="login-page">

    <div class="login-wrapper">


        <!-- =====================================================
             PANEL INFORMASI
        ====================================================== -->

        <div class="login-info">

            <div class="info-content">


                <!-- LOGO -->

                <div class="logo-box">

                    <?php if (file_exists(FCPATH . 'assets/img/logobonti.png')): ?>

                        <img
                            src="<?= base_url('assets/img/logobonti.png') ?>"
                            alt="Logo SimSGTK"
                        >

                    <?php else: ?>

                        <i class="fas fa-exchange-alt logo-fallback"></i>

                    <?php endif; ?>

                </div>


                <!-- BRAND -->

                <div class="brand-name">
                    SimSGTK
                </div>


                <div class="brand-subtitle">
                    Sistem Informasi Siswa dan GTK
                </div>


                <div class="brand-line"></div>


                <p class="brand-description">
                    Platform terintegrasi untuk pengelolaan
                    data siswa, guru, wali kelas, dan
                    administrasi sekolah secara efektif
                    dan efisien.
                </p>


                <!-- FEATURES -->

                <div>


                    <div class="feature-item">

                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>

                        <div class="feature-text">

                            <strong>
                                Aman & Terpercaya
                            </strong>

                            <span>
                                Sistem pengelolaan data sekolah
                            </span>

                        </div>

                    </div>


                    <div class="feature-item">

                        <div class="feature-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>

                        <div class="feature-text">

                            <strong>
                                Manajemen Terintegrasi
                            </strong>

                            <span>
                                Kelola data dalam satu platform
                            </span>

                        </div>

                    </div>


                    <div class="feature-item">

                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>

                        <div class="feature-text">

                            <strong>
                                Cepat & Efisien
                            </strong>

                            <span>
                                Akses informasi lebih mudah
                            </span>

                        </div>

                    </div>


                </div>

            </div>


            <div class="info-footer">

                Sistem Informasi Siswa dan GTK &bull; V.3

            </div>

        </div>


        <!-- =====================================================
             FORM LOGIN
        ====================================================== -->

        <div class="login-form-area">

            <div class="login-form">


                <!-- HEADER -->

                <div class="login-header">

                    <div class="login-icon">

                        <i class="fas fa-lock"></i>

                    </div>


                    <h3>
                        Selamat Datang 👋
                    </h3>


                    <p>
                        Masuk ke sistem menggunakan akun
                        Admin atau Wali Kelas Anda.
                    </p>

                </div>


                <!-- ERROR -->

                <?php if ($this->session->flashdata('error')): ?>

                    <div class="login-alert">

                        <i class="fas fa-exclamation-circle"></i>

                        <?= htmlspecialchars(
                            $this->session->flashdata('error')
                        ) ?>

                    </div>

                <?php endif; ?>


                <!-- FORM -->

                <?= form_open('auth/login') ?>


                    <!-- CSRF -->

                    <input
                        type="hidden"
                        name="<?= $this->security->get_csrf_token_name(); ?>"
                        value="<?= $this->security->get_csrf_hash(); ?>"
                    >


                    <!-- USERNAME -->

                    <div class="form-group-modern">

                        <label
                            for="username"
                            class="form-label-modern"
                        >
                            Username
                        </label>


                        <div class="input-wrapper">

                            <i class="fas fa-user input-icon"></i>


                            <input
                                type="text"
                                name="username"
                                id="username"
                                class="form-control-modern"
                                placeholder="Masukkan username"
                                autocomplete="username"
                                required
                                autofocus
                            >

                        </div>

                    </div>


                    <!-- PASSWORD -->

                    <div class="form-group-modern">

                        <label
                            for="password"
                            class="form-label-modern"
                        >
                            Password
                        </label>


                        <div class="input-wrapper">

                            <i class="fas fa-lock input-icon"></i>


                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control-modern"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                required
                            >


                            <i
                                class="fas fa-eye toggle-password"
                                id="togglePassword"
                                title="Tampilkan password"
                            ></i>

                        </div>

                    </div>


                    <!-- TAHUN AJARAN -->

                    <div class="form-group-modern">

                        <label
                            for="tahun_id"
                            class="form-label-modern"
                        >
                            Tahun Ajaran
                        </label>


                        <div class="input-wrapper">

                            <i class="fas fa-calendar-alt input-icon"></i>


                            <select
                                name="tahun_id"
                                id="tahun_id"
                                class="form-control-modern"
                                required
                            >

                                <option value="">
                                    -- Pilih Tahun Ajaran --
                                </option>


                                <?php foreach ($tahun as $t): ?>

                                    <option
                                        value="<?= $t->id ?>"
                                        <?= !empty($t->aktif)
                                            ? 'selected'
                                            : '' ?>
                                    >

                                        <?= htmlspecialchars($t->tahun) ?>

                                        <?= !empty($t->aktif)
                                            ? ' (Aktif)'
                                            : '' ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <div class="year-info">

                            <i class="fas fa-info-circle"></i>

                            Tahun aktif otomatis dipilih.

                        </div>

                    </div>


                    <!-- LOGIN BUTTON -->

                    <button
                        type="submit"
                        class="btn btn-login"
                    >

                        <i class="fas fa-sign-in-alt"></i>

                        Masuk ke Sistem

                    </button>


                <?= form_close() ?>


                <!-- FOOTER -->

                <div class="login-footer">

                    © <?= date('Y') ?>

                    <strong>SimSGTK</strong>

                    &mdash;

                    Sistem Informasi Siswa dan GTK-V.3

                </div>


            </div>

        </div>


    </div>

</div>


<!-- =========================================================
     JS
========================================================= -->

<script src="<?= base_url('assets/sbadmin2/vendor/jquery/jquery.min.js') ?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>


<script>

    /* =========================================================
       PASSWORD TOGGLE
    ========================================================= */

    const togglePassword =
        document.getElementById('togglePassword');

    const password =
        document.getElementById('password');


    if (togglePassword && password) {

        togglePassword.addEventListener(
            'click',
            function () {

                const isPassword =
                    password.type === 'password';


                password.type =
                    isPassword
                        ? 'text'
                        : 'password';


                this.classList.toggle(
                    'fa-eye',
                    !isPassword
                );


                this.classList.toggle(
                    'fa-eye-slash',
                    isPassword
                );


                this.setAttribute(
                    'title',
                    isPassword
                        ? 'Sembunyikan password'
                        : 'Tampilkan password'
                );

            }
        );

    }

</script>


</body>
</html>