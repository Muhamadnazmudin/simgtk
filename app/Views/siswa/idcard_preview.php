<style>

/* =========================================================
   ID CARD PREVIEW PAGE
   ========================================================= */

.idcard-page {
    width: 100%;
    padding: 10px 0 35px;
}


/* =========================================================
   PANEL
   ========================================================= */

.idcard-panel {
    border: 0 !important;
    border-radius: 18px !important;

    background: #fff;

    box-shadow:
        0 8px 28px rgba(0, 0, 0, .07) !important;

    overflow: hidden;
}


/* =========================================================
   HEADER
   ========================================================= */

.idcard-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 17px 24px;

    border-bottom: 1px solid #edf0f5;
}


.idcard-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
}


.idcard-title-icon {
    width: 42px;
    height: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background:
        linear-gradient(
            135deg,
            #2563eb,
            #4f46e5
        );

    color: #fff;

    font-size: 17px;
}


.idcard-title-text h4 {
    margin: 0;

    color: #374151;

    font-size: 18px;
    font-weight: 700;
}


.idcard-title-text span {
    display: block;

    margin-top: 3px;

    color: #9ca3af;

    font-size: 10px;
}


.idcard-badge {
    padding: 6px 11px;

    border-radius: 20px;

    background: #eff6ff;

    color: #2563eb;

    font-size: 10px;

    font-weight: 700;
}


/* =========================================================
   BODY
   ========================================================= */

.idcard-panel-body {
    padding: 30px 35px 32px;

    text-align: center;
}


/* =========================================================
   PREVIEW AREA
   ========================================================= */

.idcard-preview {

    width: 100%;

    display: flex;

    align-items: center;
    justify-content: center;

    padding: 5px;

    overflow: hidden;
}


/* =========================================================
   ID CARD IMAGE
   ========================================================= */

.idcard-image {

    display: block;

    /*
     * LEBIH BESAR DI DESKTOP
     */
    width: min(85vw, 1012px);

    max-width: 1012px;

    height: auto;

    border-radius: 20px;

    box-shadow:
        0 15px 40px rgba(0, 0, 0, .18);

    transition:
        transform .2s ease,
        box-shadow .2s ease;
}


.idcard-image:hover {

    transform: translateY(-2px);

    box-shadow:
        0 20px 45px rgba(0, 0, 0, .22);
}


/* =========================================================
   INFO
   ========================================================= */

.idcard-info {

    display: flex;

    align-items: center;
    justify-content: center;

    gap: 6px;

    margin-top: 15px;

    color: #9ca3af;

    font-size: 10px;
}


.idcard-info i {
    color: #2563eb;
}


/* =========================================================
   BUTTON
   ========================================================= */

.idcard-actions {

    display: flex;

    justify-content: center;

    margin-top: 18px;
}


.download-btn {

    display: inline-flex;

    align-items: center;
    justify-content: center;

    gap: 9px;

    width: min(85vw, 400px);

    padding: 12px 20px;

    border: 0;

    border-radius: 11px;

    background:
        linear-gradient(
            135deg,
            #2563eb,
            #4f46e5
        );

    color: #fff !important;

    font-size: 14px;

    font-weight: 700;

    box-shadow:
        0 8px 20px rgba(37, 99, 235, .22);

    transition: all .2s ease;
}


.download-btn:hover {

    transform: translateY(-2px);

    box-shadow:
        0 12px 26px rgba(37, 99, 235, .30);
}


/* =========================================================
   DARK MODE
   ========================================================= */

body.dark-mode .idcard-panel {

    background: #1f2937;

}


body.dark-mode .idcard-panel-header {

    border-color: #374151;

}


body.dark-mode .idcard-title-text h4 {

    color: #f9fafb;

}


body.dark-mode .idcard-title-text span {

    color: #9ca3af;

}


body.dark-mode .idcard-badge {

    background: rgba(37, 99, 235, .15);

    color: #60a5fa;

}


/* =========================================================
   TABLET
   ========================================================= */

@media (max-width: 992px) {

    .idcard-panel-body {

        padding: 25px 20px 28px;

    }


    .idcard-image {

        width: min(92vw, 900px);

    }

}


/* =========================================================
   MOBILE
   ========================================================= */

@media (max-width: 576px) {

    .idcard-panel-header {

        padding: 14px 15px;

    }


    .idcard-title-icon {

        width: 38px;
        height: 38px;

    }


    .idcard-title-text h4 {

        font-size: 14px;

    }


    .idcard-title-text span {

        font-size: 9px;

    }


    .idcard-badge {

        display: none;

    }


    .idcard-panel-body {

        padding: 18px 10px 22px;

    }


    .idcard-preview {

        padding: 0;

    }


    .idcard-image {

        /*
         * Di HP tetap memenuhi lebar layar
         */
        width: 100%;

        max-width: 100%;

        border-radius: 13px;

    }


    .download-btn {

        width: 100%;

        max-width: 100%;

    }

}

</style>


<!-- =========================================================
     ID CARD SISWA
     ========================================================= -->

<div class="idcard-page">

    <div class="card idcard-panel">


        <!-- HEADER -->

        <div class="idcard-panel-header">

            <div class="idcard-title-wrap">

                <div class="idcard-title-icon">

                    <i class="fas fa-id-card"></i>

                </div>


                <div class="idcard-title-text">

                    <h4>
                        ID Card Siswa
                    </h4>

                    <span>
                        Kartu identitas peserta didik
                    </span>

                </div>

            </div>


            <div class="idcard-badge">

                <i class="fas fa-check-circle mr-1"></i>

                SISWA AKTIF

            </div>

        </div>


        <!-- BODY -->

        <div class="idcard-panel-body">


            <!-- PREVIEW -->

            <div class="idcard-preview">

                <img
                    src="data:image/png;base64,<?= $idcard_base64 ?>"
                    class="idcard-image"
                    alt="ID Card Siswa">

            </div>


            <!-- INFO -->

            <div class="idcard-info">

                <i class="fas fa-eye"></i>

                <span>
                    Preview ID Card siswa
                </span>

            </div>


            <!-- DOWNLOAD -->

            <div class="idcard-actions">

                <a
                    href="<?= site_url('idcard/cetak/'.$siswa->id) ?>"
                    class="download-btn"
                    target="_blank">

                    <i class="fas fa-download"></i>

                    Download ID Card

                </a>

            </div>


        </div>

    </div>

</div>