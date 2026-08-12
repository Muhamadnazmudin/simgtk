<style>

/* =========================================================
   KARTU SISWA - KTP STYLE
   ========================================================= */

.kartu-page {
    padding: 25px 15px 50px;
}


/* =========================================================
   BUTTON
   ========================================================= */

.btn-cetak-kartu {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    padding: 9px 16px;

    border: 0;
    border-radius: 9px;

    background: linear-gradient(
        135deg,
        #2563eb,
        #4f46e5
    );

    color: #fff;

    font-size: 13px;
    font-weight: 600;

    box-shadow:
        0 6px 16px rgba(37, 99, 235, .25);

    transition: .2s ease;
}

.btn-cetak-kartu:hover {
    color: #fff;
    transform: translateY(-1px);
}


/* =========================================================
   WRAPPER
   ========================================================= */

.kartu-wrapper {
    display: flex;
    justify-content: center;

    margin-top: 22px;
}


/* =========================================================
   KARTU
   85.60 x 53.98 mm
   Rasio 1.586
   ========================================================= */

#kartu-container {

    position: relative;

    width: 428px;
    height: 270px;

    overflow: hidden;

    border-radius: 16px;

    background:
        linear-gradient(
            135deg,
            #0759d7 0%,
            #1479e8 52%,
            #4db1f7 100%
        );

    color: #fff;

    font-family:
        "Segoe UI",
        Arial,
        sans-serif;

    box-shadow:
        0 16px 40px rgba(0, 0, 0, .20);

}


/* =========================================================
   BACKGROUND DECORATION
   ========================================================= */

.kartu-decoration-circle {

    position: absolute;

    width: 235px;
    height: 235px;

    right: -115px;
    top: -120px;

    border-radius: 50%;

    background:
        rgba(255,255,255,.09);

    pointer-events: none;
}


.kartu-decoration-circle-2 {

    position: absolute;

    width: 190px;
    height: 190px;

    left: -135px;
    bottom: -135px;

    border-radius: 50%;

    background:
        rgba(255,255,255,.07);

    pointer-events: none;
}


/* pola kecil */

.kartu-pattern {

    position: absolute;

    right: -10px;
    bottom: 22px;

    width: 145px;
    height: 90px;

    opacity: .07;

    background-image:
        linear-gradient(
            45deg,
            #fff 25%,
            transparent 25%
        ),
        linear-gradient(
            -45deg,
            #fff 25%,
            transparent 25%
        );

    background-size: 20px 20px;

    transform: rotate(-15deg);

    pointer-events: none;
}


/* =========================================================
   MAIN CONTENT
   ========================================================= */

.kartu-inner {

    position: relative;

    z-index: 5;

    width: 100%;
    height: 100%;

    display: flex;
    flex-direction: column;

}


/* =========================================================
   HEADER SEKOLAH
   ========================================================= */

.kartu-header {

    height: 53px;

    flex: 0 0 53px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 10px 17px;

    border-bottom:
        1px solid rgba(255,255,255,.18);

}


/* sekolah */

.kartu-school {

    display: flex;

    align-items: center;

    gap: 9px;

    min-width: 0;
}


.kartu-school-logo {

    width: 34px;
    height: 34px;

    flex: 0 0 34px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background:
        rgba(255,255,255,.16);

    border:
        1px solid rgba(255,255,255,.25);

    font-size: 15px;
}


.kartu-school-text {

    min-width: 0;

    line-height: 1.15;
}


.kartu-school-title {

    font-size: 10px;

    font-weight: 800;

    letter-spacing: .55px;

    white-space: nowrap;
}


.kartu-school-subtitle {

    margin-top: 3px;

    font-size: 6.5px;

    opacity: .72;

    letter-spacing: .25px;

    white-space: nowrap;
}


/* badge */

.kartu-status {

    flex: 0 0 auto;

    padding: 5px 9px;

    border-radius: 20px;

    background:
        rgba(255,255,255,.14);

    border:
        1px solid rgba(255,255,255,.25);

    font-size: 6.5px;

    font-weight: 700;

    letter-spacing: .4px;
}


/* =========================================================
   TITLE
   ========================================================= */

.kartu-heading {

    flex: 0 0 34px;

    height: 34px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 17px;

}


.kartu-heading-title {

    font-size: 15px;

    font-weight: 900;

    letter-spacing: 1.2px;

    line-height: 1;
}


.kartu-heading-class {

    font-size: 8px;

    font-weight: 600;

    opacity: .78;

    padding: 4px 8px;

    border-radius: 6px;

    background:
        rgba(255,255,255,.10);
}


/* =========================================================
   BODY
   ========================================================= */

.kartu-body {

    flex: 1;

    display: grid;

    grid-template-columns: 68px minmax(0, 1fr) 74px;

    column-gap: 13px;

    align-items: start;

    padding: 4px 17px 0;

}


/* =========================================================
   FOTO
   ========================================================= */

.kartu-photo {

    width: 68px;
    height: 82px;

}


.kartu-photo img {

    display: block;

    width: 68px;
    height: 82px;

    object-fit: cover;

    border-radius: 9px;

    border:
        2px solid rgba(255,255,255,.95);

    background: #fff;

    box-shadow:
        0 5px 12px rgba(0,0,0,.18);

}


/* =========================================================
   DATA
   ========================================================= */

.kartu-data {

    min-width: 0;

    padding-top: 1px;

}


.kartu-name {

    margin-bottom: 7px;

    font-size: 13px;

    font-weight: 800;

    line-height: 1.15;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;
}


.kartu-info {

    width: 100%;

    border-collapse: collapse;

}


.kartu-info td {

    padding: 2px 0;

    font-size: 8.5px;

    line-height: 1.25;

    vertical-align: top;

}


.kartu-info .label {

    width: 34px;

    color:
        rgba(255,255,255,.68);

    font-weight: 600;
}


.kartu-info .separator {

    width: 7px;

    color:
        rgba(255,255,255,.55);
}


.kartu-info .value {

    color: #fff;

    font-weight: 600;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;
}


/* =========================================================
   QR
   ========================================================= */

.kartu-qr {

    display: flex;

    flex-direction: column;

    align-items: center;

}


.kartu-qr-box {

    width: 68px;
    height: 68px;

    padding: 5px;

    border-radius: 9px;

    background: #fff;

    box-shadow:
        0 5px 12px rgba(0,0,0,.18);
}


.kartu-qr-box img {

    display: block;

    width: 58px;
    height: 58px;

    object-fit: contain;
}


.kartu-qr-text {

    margin-top: 4px;

    text-align: center;

    font-size: 6.5px;

    font-weight: 600;

    line-height: 1.2;

    white-space: nowrap;
}


/* =========================================================
   FOOTER
   ========================================================= */

.kartu-footer {

    flex: 0 0 28px;

    height: 28px;

    margin: 0 17px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    border-top:
        1px solid rgba(255,255,255,.18);
}


.kartu-footer-left {

    font-size: 6px;

    opacity: .58;

    letter-spacing: .2px;
}


.kartu-footer-right {

    font-size: 6px;

    font-weight: 700;

    opacity: .65;

    letter-spacing: .4px;
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 480px) {

    .kartu-page {

        padding-left: 10px;
        padding-right: 10px;

    }


    #kartu-container {

        width: 100%;

        max-width: 428px;

        height: auto;

        aspect-ratio: 85.6 / 53.98;

    }

}


/* =========================================================
   PRINT
   ========================================================= */

@media print {

    @page {

        size: 85.60mm 53.98mm;

        margin: 0;

    }


    html,
    body {

        width: 85.60mm !important;
        height: 53.98mm !important;

        margin: 0 !important;
        padding: 0 !important;

        background: #fff !important;

    }


    body * {

        visibility: hidden !important;

    }


    #print-area,
    #print-area * {

        visibility: visible !important;

    }


    #print-area {

        position: absolute;

        top: 0;
        left: 0;

        width: 85.60mm;
        height: 53.98mm;

        margin: 0;
        padding: 0;

    }


    #kartu-container {

        width: 85.60mm;
        height: 53.98mm;

        margin: 0;

        border-radius: 0;

        box-shadow: none !important;

        -webkit-print-color-adjust: exact !important;

        print-color-adjust: exact !important;

    }


    .btn-cetak-kartu {

        display: none !important;

    }

}

</style>


<!-- =========================================================
     PAGE
     ========================================================= -->

<div class="kartu-page">


    <!-- =====================================================
         BUTTON
         ===================================================== -->

    <div class="text-center mb-3">

        <button
            type="button"
            onclick="window.print()"
            class="btn-cetak-kartu">

            <i class="fas fa-print"></i>

            Cetak Kartu

        </button>

    </div>


    <!-- =====================================================
         CARD
         ===================================================== -->

    <div class="kartu-wrapper">

        <div id="print-area">

            <div id="kartu-container">


                <!-- BACKGROUND -->

                <div class="kartu-decoration-circle"></div>

                <div class="kartu-decoration-circle-2"></div>

                <div class="kartu-pattern"></div>


                <!-- =================================================
                     INNER
                     ================================================= -->

                <div class="kartu-inner">


                    <!-- ================= HEADER ================= -->

                    <div class="kartu-header">


                        <!-- SEKOLAH -->

                        <div class="kartu-school">

                            <div class="kartu-school-logo">

                                <i class="fas fa-school"></i>

                            </div>


                            <div class="kartu-school-text">

                                <div class="kartu-school-title">

                                    KARTU IDENTITAS SISWA

                                </div>


                                <div class="kartu-school-subtitle">

                                    SISTEM INFORMASI AKADEMIK SEKOLAH <br>
                                        SMK Negeri 1 Cilimus

                                </div>

                            </div>

                        </div>


                        <!-- STATUS -->

                        <div class="kartu-status">

                            SISWA AKTIF

                        </div>


                    </div>


                    <!-- ================= TITLE ================= -->

                    <div class="kartu-heading">


                        <div class="kartu-heading-title">

                            KARTU SISWA

                        </div>


                        <div class="kartu-heading-class">

                            <?= html_escape(
                                $siswa->nama_kelas
                            ) ?>

                        </div>


                    </div>


                    <!-- ================= BODY ================= -->

                    <div class="kartu-body">


                        <!-- FOTO -->

                        <div class="kartu-photo">

                            <?php if (
                                !empty($siswa->foto) &&
                                file_exists(
                                    FCPATH .
                                    'uploads/siswa/' .
                                    $siswa->foto
                                )
                            ): ?>

                                <img
                                    src="<?= base_url(
                                        'uploads/siswa/' .
                                        $siswa->foto
                                    ) ?>"
                                    alt="Foto Siswa">

                            <?php else: ?>

                                <img
                                    src="https://ui-avatars.com/api/?name=<?= urlencode(
                                        $siswa->nama
                                    ) ?>&size=200&background=0D6EFD&color=fff"
                                    alt="Foto Siswa">

                            <?php endif; ?>

                        </div>


                        <!-- DATA SISWA -->

                        <div class="kartu-data">


                            <div class="kartu-name">

                                <?= html_escape(
                                    $siswa->nama
                                ) ?>

                            </div>


                            <table class="kartu-info">


                                <tr>

                                    <td class="label">
                                        NISN
                                    </td>

                                    <td class="separator">
                                        :
                                    </td>

                                    <td class="value">
                                        <?= html_escape(
                                            $siswa->nisn
                                        ) ?>
                                    </td>

                                </tr>


                                <tr>

                                    <td class="label">
                                        NIS
                                    </td>

                                    <td class="separator">
                                        :
                                    </td>

                                    <td class="value">
                                        <?= html_escape(
                                            $siswa->nis
                                        ) ?>
                                    </td>

                                </tr>


                                <tr>

                                    <td class="label">
                                        Kelas
                                    </td>

                                    <td class="separator">
                                        :
                                    </td>

                                    <td class="value">
                                        <?= html_escape(
                                            $siswa->nama_kelas
                                        ) ?>
                                    </td>

                                </tr>


                            </table>


                        </div>


                        <!-- QR -->

                        <div class="kartu-qr">


                            <div class="kartu-qr-box">

                                <img
                                    src="<?= $qr_file ?>"
                                    alt="QR Code">

                            </div>


                            <div class="kartu-qr-text">

                                <i class="fas fa-qrcode"></i>

                                Scan Izin Keluar

                            </div>


                        </div>


                    </div>


                    <!-- ================= FOOTER ================= -->

                    <div class="kartu-footer">


                        <div class="kartu-footer-left">

                            Kartu identitas resmi peserta didik

                        </div>


                        <div class="kartu-footer-right">

                            ID SISWA

                        </div>


                    </div>


                </div>


            </div>

        </div>

    </div>

</div>