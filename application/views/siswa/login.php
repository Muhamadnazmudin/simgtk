<!doctype html>
<html lang="id">
<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login Siswa - SimSGTK</title>

    <!-- Bootstrap -->
    <link
        href="<?= base_url('assets/sbadmin2/vendor/bootstrap/css/bootstrap.min.css') ?>"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link
        href="<?= base_url('assets/sbadmin2/vendor/fontawesome-free/css/all.min.css') ?>"
        rel="stylesheet">


    <style>

        /* =====================================================
           GLOBAL
        ===================================================== */

        * {
            box-sizing: border-box;
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

            background: #f5f7fb;

            color: #343a40;
        }


        /* =====================================================
           LOGIN WRAPPER
        ===================================================== */

        .login-page {
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px;
        }


        .login-container {
            width: 100%;
            max-width: 1050px;

            min-height: 620px;

            display: grid;
            grid-template-columns: 1.05fr .95fr;

            background: #fff;

            border-radius: 24px;

            overflow: hidden;

            box-shadow:
                0 25px 60px rgba(31, 45, 61, .12);
        }


        /* =====================================================
           LEFT PANEL
        ===================================================== */

        .login-brand {
            position: relative;

            overflow: hidden;

            padding: 55px;

            display: flex;
            flex-direction: column;
            justify-content: space-between;

            background:
                linear-gradient(
                    145deg,
                    #4e73df 0%,
                    #224abe 55%,
                    #183a9e 100%
                );

            color: #fff;
        }


        /* Decorative circles */

        .login-brand::before {
            content: "";

            position: absolute;

            width: 330px;
            height: 330px;

            border-radius: 50%;

            top: -150px;
            right: -120px;

            background: rgba(255,255,255,.08);
        }


        .login-brand::after {
            content: "";

            position: absolute;

            width: 280px;
            height: 280px;

            border-radius: 50%;

            bottom: -150px;
            left: -120px;

            background: rgba(255,255,255,.07);
        }


        .brand-content {
            position: relative;
            z-index: 2;
        }


        /* =====================================================
           BRAND LOGO
        ===================================================== */

        .brand-icon {
            width: 68px;
            height: 68px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 18px;

            background: rgba(255,255,255,.15);

            border: 1px solid rgba(255,255,255,.2);

            font-size: 28px;

            margin-bottom: 28px;

            box-shadow:
                0 10px 25px rgba(0,0,0,.08);
        }


        .brand-title {
            font-size: 2rem;

            font-weight: 800;

            line-height: 1.2;

            margin-bottom: 15px;
        }


        .brand-description {
            max-width: 410px;

            font-size: .95rem;

            line-height: 1.7;

            opacity: .85;

            margin-bottom: 0;
        }


        /* =====================================================
           BRAND FEATURES
        ===================================================== */

        .brand-features {
            position: relative;
            z-index: 2;

            margin-top: 40px;
        }


        .brand-feature {
            display: flex;
            align-items: center;

            margin-bottom: 15px;

            font-size: .88rem;

            opacity: .9;
        }


        .brand-feature-icon {
            width: 34px;
            height: 34px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-right: 11px;

            border-radius: 9px;

            background: rgba(255,255,255,.13);

            font-size: 13px;
        }


        .brand-footer {
            position: relative;
            z-index: 2;

            font-size: .78rem;

            opacity: .65;
        }


        /* =====================================================
           RIGHT LOGIN PANEL
        ===================================================== */

        .login-form-panel {
            padding: 55px 60px;

            display: flex;
            align-items: center;
        }


        .login-form {
            width: 100%;
            max-width: 390px;

            margin: auto;
        }


        /* =====================================================
           LOGIN HEADER
        ===================================================== */

        .login-heading {
            margin-bottom: 32px;
        }


        .login-heading h3 {
            font-size: 1.65rem;

            font-weight: 800;

            color: #343a40;

            margin-bottom: 7px;
        }


        .login-heading p {
            color: #858796;

            font-size: .88rem;

            margin: 0;
        }


        /* =====================================================
           FORM
        ===================================================== */

        .form-group-modern {
            margin-bottom: 22px;
        }


        .form-label-modern {
            display: block;

            font-size: .78rem;

            font-weight: 700;

            color: #495057;

            margin-bottom: 8px;

            letter-spacing: .2px;
        }


        .input-wrapper {
            position: relative;
        }


        .input-icon {
            position: absolute;

            left: 16px;
            top: 50%;

            transform: translateY(-50%);

            color: #adb5bd;

            font-size: 15px;

            z-index: 3;

            transition: color .2s ease;
        }


        .form-control-modern {
            width: 100%;

            height: 52px;

            border: 1px solid #e1e5eb;

            border-radius: 11px;

            background: #f8f9fc;

            color: #343a40;

            padding: 0 45px;

            font-size: .92rem;

            outline: none;

            transition:
                border-color .2s ease,
                box-shadow .2s ease,
                background .2s ease;
        }


        .form-control-modern::placeholder {
            color: #adb5bd;
        }


        .form-control-modern:hover {
            background: #fff;

            border-color: #cdd3dc;
        }


        .form-control-modern:focus {
            background: #fff;

            border-color: #4e73df;

            box-shadow:
                0 0 0 4px rgba(78,115,223,.10);
        }


        .input-wrapper:focus-within .input-icon {
            color: #4e73df;
        }


        /* =====================================================
           PASSWORD
        ===================================================== */

        .toggle-password {
            position: absolute;

            right: 16px;
            top: 50%;

            transform: translateY(-50%);

            color: #adb5bd;

            cursor: pointer;

            z-index: 4;

            transition: color .2s ease;
        }


        .toggle-password:hover {
            color: #4e73df;
        }


        /* =====================================================
           LOGIN BUTTON
        ===================================================== */

        .btn-login {
            width: 100%;

            height: 52px;

            border: 0;

            border-radius: 11px;

            background:
                linear-gradient(
                    135deg,
                    #4e73df,
                    #224abe
                );

            color: #fff;

            font-size: .92rem;

            font-weight: 700;

            letter-spacing: .2px;

            box-shadow:
                0 8px 18px rgba(78,115,223,.22);

            transition:
                transform .2s ease,
                box-shadow .2s ease;
        }


        .btn-login:hover {
            color: #fff;

            transform: translateY(-2px);

            box-shadow:
                0 12px 25px rgba(78,115,223,.30);
        }


        .btn-login:active {
            transform: translateY(0);
        }


        .btn-login i {
            margin-right: 7px;
        }


        /* =====================================================
           ERROR
        ===================================================== */

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


        /* =====================================================
           FOOTER
        ===================================================== */

        .login-footer {
            text-align: center;

            margin-top: 28px;

            font-size: .75rem;

            color: #adb5bd;
        }


        .login-footer strong {
            color: #6c757d;
        }


        /* =====================================================
           ANIMATION
        ===================================================== */

        .login-container {
            animation: loginAppear .55s ease-out;
        }


        @keyframes loginAppear {

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


        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 991.98px) {

            .login-container {
                grid-template-columns: 1fr;

                max-width: 500px;

                min-height: auto;
            }


            .login-brand {
                padding: 35px;

                min-height: 280px;
            }


            .brand-title {
                font-size: 1.6rem;
            }


            .brand-features {
                display: none;
            }


            .brand-footer {
                margin-top: 35px;
            }


            .login-form-panel {
                padding: 40px 35px;
            }

        }


        @media (max-width: 575.98px) {

            .login-page {
                padding: 15px;
            }


            .login-container {
                border-radius: 18px;
            }


            .login-brand {
                padding: 30px 25px;

                min-height: 230px;
            }


            .brand-icon {
                width: 55px;
                height: 55px;

                border-radius: 14px;

                font-size: 22px;

                margin-bottom: 20px;
            }


            .brand-title {
                font-size: 1.35rem;
            }


            .brand-description {
                font-size: .82rem;

                line-height: 1.6;
            }


            .login-form-panel {
                padding: 35px 25px;
            }


            .login-heading h3 {
                font-size: 1.4rem;
            }

        }

    </style>

</head>


<body>


<div class="login-page">


    <div class="login-container">


        <!-- =================================================
             BRAND PANEL
        ================================================== -->

        <div class="login-brand">

            <div class="brand-content">

                <div class="brand-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>


                <div class="brand-title">
                    Portal Siswa
                </div>


                <p class="brand-description">
                    Akses berbagai informasi akademik dan layanan
                    siswa melalui sistem informasi sekolah.
                </p>


                <div class="brand-features">

                    <div class="brand-feature">

                        <div class="brand-feature-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>

                        Jadwal pembelajaran

                    </div>


                    <div class="brand-feature">

                        <div class="brand-feature-icon">
                            <i class="fas fa-book"></i>
                        </div>

                        Buku induk dan nilai

                    </div>


                    <div class="brand-feature">

                        <div class="brand-feature-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>

                        Tutorial pembelajaran

                    </div>

                </div>

            </div>


            <div class="brand-footer">

                Sistem Informasi Siswa & GTK

            </div>

        </div>


        <!-- =================================================
             LOGIN FORM
        ================================================== -->

        <div class="login-form-panel">

            <div class="login-form">


                <!-- HEADER -->

                <div class="login-heading">

                    <h3>
                        Selamat Datang 👋
                    </h3>

                    <p>
                        Silakan masuk menggunakan akun siswa Anda.
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

                <form
                    method="post"
                    action="<?= site_url('SiswaAuth/cek_login') ?>"
                >

                    <!-- CSRF -->

                    <input
                        type="hidden"
                        name="<?= $this->security->get_csrf_token_name(); ?>"
                        value="<?= $this->security->get_csrf_hash(); ?>"
                    >


                    <!-- NISN -->

                    <div class="form-group-modern">

                        <label
                            for="nisn"
                            class="form-label-modern"
                        >
                            NISN
                        </label>


                        <div class="input-wrapper">

                            <i class="fas fa-id-card input-icon"></i>


                            <input
                                type="text"
                                name="nisn"
                                id="nisn"
                                class="form-control-modern"
                                placeholder="Masukkan NISN Anda"
                                autocomplete="username"
                                inputmode="numeric"
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
                                placeholder="Masukkan nisn"
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


                    <!-- LOGIN -->

                    <button
                        type="submit"
                        class="btn btn-login"
                    >

                        <i class="fas fa-sign-in-alt"></i>

                        Masuk ke Portal

                    </button>

                </form>


                <!-- FOOTER -->

                <div class="login-footer">

                    © <?= date('Y') ?>

                    <strong>SimSGTK</strong>

                    &mdash;

                    Sistem Informasi Siswa dan GTK

                </div>


            </div>

        </div>


    </div>

</div>


<!-- =====================================================
     JAVASCRIPT
====================================================== -->

<script src="<?= base_url('assets/sbadmin2/vendor/jquery/jquery.min.js') ?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>


<script>

    /* =====================================================
       TOGGLE PASSWORD
    ===================================================== */

    document
        .getElementById('togglePassword')
        .addEventListener('click', function () {

            const input =
                document.getElementById('password');

            const hidden =
                input.type === 'password';


            input.type =
                hidden ? 'text' : 'password';


            this.classList.toggle(
                'fa-eye',
                !hidden
            );

            this.classList.toggle(
                'fa-eye-slash',
                hidden
            );


            this.setAttribute(
                'title',
                hidden
                    ? 'Sembunyikan password'
                    : 'Tampilkan password'
            );

        });


    /* =====================================================
       NISN - HANYA ANGKA
       Tetap mengizinkan paste.
    ===================================================== */

    document
        .getElementById('nisn')
        .addEventListener('input', function () {

            this.value =
                this.value.replace(/\D/g, '');

        });

</script>


</body>
</html>