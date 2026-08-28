<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$nama_berkas = !empty($berkas->nama_file_asli)
    ? $berkas->nama_file_asli
    : 'Berkas';

$jenis_berkas = !empty($berkas->jenis_berkas)
    ? $berkas->jenis_berkas
    : '-';
?>

<div class="document-viewer">

    <!-- =========================================================
         TOP BAR
    ========================================================== -->

    <div class="viewer-toolbar">

        <div class="viewer-toolbar-left">

            <a href="javascript:window.close();"
               class="viewer-back"
               title="Tutup">

                <i class="fas fa-arrow-left"></i>

            </a>


            <div class="viewer-file">

                <div class="viewer-file-icon">

                    <?php if ($is_pdf): ?>

                        <i class="fas fa-file-pdf"></i>

                    <?php else: ?>

                        <i class="fas fa-file-image"></i>

                    <?php endif; ?>

                </div>


                <div class="viewer-file-info">

                    <div class="viewer-file-name"
                         title="<?= html_escape($nama_berkas); ?>">

                        <?= html_escape($nama_berkas); ?>

                    </div>


                    <div class="viewer-file-type">

                        <?= html_escape($jenis_berkas); ?>

                    </div>

                </div>

            </div>

        </div>


        <!-- =====================================================
             ACTION
        ====================================================== -->

        <div class="viewer-toolbar-right">

            <?php if ($is_image): ?>

                <!-- ZOOM OUT -->

                <button type="button"
                        class="viewer-btn viewer-btn-icon"
                        id="btnZoomOut"
                        title="Perkecil">

                    <i class="fas fa-minus"></i>

                </button>


                <!-- ZOOM -->

                <button type="button"
                        class="viewer-zoom"
                        id="btnZoomValue"
                        title="Ukuran 100%">

                    100%

                </button>


                <!-- ZOOM IN -->

                <button type="button"
                        class="viewer-btn viewer-btn-icon"
                        id="btnZoomIn"
                        title="Perbesar">

                    <i class="fas fa-plus"></i>

                </button>


                <!-- FIT -->

                <button type="button"
                        class="viewer-btn"
                        id="btnFit"
                        title="Sesuaikan layar">

                    <i class="fas fa-expand"></i>

                    <span>Fit</span>

                </button>


                <span class="viewer-divider"></span>


                <!-- ROTATE LEFT -->

                <button type="button"
                        class="viewer-btn"
                        id="btnRotateLeft"
                        title="Putar kiri 90°">

                    <i class="fas fa-undo-alt"></i>

                    <span>Putar</span>

                </button>


                <!-- ROTATE RIGHT -->

                <button type="button"
                        class="viewer-btn viewer-btn-icon"
                        id="btnRotateRight"
                        title="Putar kanan 90°">

                    <i class="fas fa-redo-alt"></i>

                </button>


                <!-- RESET -->

                <button type="button"
                        class="viewer-btn viewer-btn-icon"
                        id="btnReset"
                        title="Reset">

                    <i class="fas fa-sync-alt"></i>

                </button>


                <!-- SIMPAN -->

                <form method="post"
                      action="<?= site_url(
                          'berkas_siswa/rotate/' .
                          $berkas->id
                      ); ?>"
                      id="rotateSaveForm"
                      class="rotate-save-form">

                    <input type="hidden"
                           name="<?= $this->security->get_csrf_token_name(); ?>"
                           value="<?= $this->security->get_csrf_hash(); ?>">

                    <input type="hidden"
                           name="direction"
                           id="rotateDirection"
                           value="">

                    <button type="submit"
                            class="viewer-btn viewer-btn-primary"
                            id="btnSaveRotate"
                            disabled
                            title="Simpan rotasi">

                        <i class="fas fa-save"></i>

                        <span>Simpan</span>

                    </button>

                </form>

            <?php endif; ?>


            <!-- DOWNLOAD -->

            <a href="<?= html_escape(
                $download_url
            ); ?>"
               class="viewer-btn"
               title="Download">

                <i class="fas fa-download"></i>

                <span>Download</span>

            </a>

        </div>

    </div>


    <!-- =========================================================
         FLASH MESSAGE
    ========================================================== -->

    <?php if ($this->session->flashdata('success')): ?>

        <div class="viewer-alert viewer-alert-success">

            <i class="fas fa-check-circle"></i>

            <span>

                <?= html_escape(
                    $this->session->flashdata('success')
                ); ?>

            </span>

        </div>

    <?php endif; ?>


    <?php if ($this->session->flashdata('error')): ?>

        <div class="viewer-alert viewer-alert-error">

            <i class="fas fa-exclamation-circle"></i>

            <span>

                <?= html_escape(
                    $this->session->flashdata('error')
                ); ?>

            </span>

        </div>

    <?php endif; ?>


    <!-- =========================================================
         VIEWER AREA
    ========================================================== -->

    <div class="viewer-content">


        <?php if ($is_image): ?>

            <!-- =================================================
                 IMAGE VIEWER
            ================================================== -->

            <div class="image-stage"
                 id="imageStage">

                <div class="image-canvas"
                     id="imageCanvas">

                    <img src="<?= html_escape(
                        $file_url
                    ); ?>?v=<?= time(); ?>"
                         alt="<?= html_escape(
                             $nama_berkas
                         ); ?>"
                         id="documentImage"
                         class="document-image"
                         draggable="false">

                </div>

            </div>


            <!-- IMAGE HELP -->

            <div class="image-help">

                <i class="fas fa-info-circle"></i>

                Scroll untuk zoom
                <span>•</span>
                Drag untuk menggeser
                <span>•</span>
                Putar lalu Simpan

            </div>


        <?php elseif ($is_pdf): ?>

            <!-- =================================================
                 PDF
            ================================================== -->

            <iframe src="<?= html_escape(
                $file_url
            ); ?>"
                    class="pdf-viewer"
                    title="Preview <?= html_escape(
                        $nama_berkas
                    ); ?>">

            </iframe>


        <?php else: ?>

            <!-- =================================================
                 UNSUPPORTED
            ================================================== -->

            <div class="viewer-empty">

                <div class="viewer-empty-icon">

                    <i class="fas fa-file-exclamation"></i>

                </div>


                <div class="viewer-empty-title">

                    File tidak dapat ditampilkan

                </div>


                <div class="viewer-empty-text">

                    Silakan download file untuk membukanya.

                </div>


                <a href="<?= html_escape(
                    $download_url
                ); ?>"
                   class="viewer-btn viewer-btn-primary mt-3">

                    <i class="fas fa-download"></i>

                    <span>Download Berkas</span>

                </a>

            </div>

        <?php endif; ?>


    </div>

</div>


<style>

/* ============================================================
   RESET
============================================================ */

html,
body {

    margin: 0 !important;

    padding: 0 !important;

    width: 100%;

    height: 100%;

    overflow: hidden !important;

}


/* ============================================================
   VIEWER
============================================================ */

.document-viewer {

    position: fixed;

    inset: 0;

    z-index: 999999;

    width: 100vw;

    height: 100vh;

    background: #eef0f4;

    display: flex;

    flex-direction: column;

}


/* ============================================================
   TOOLBAR
============================================================ */

.viewer-toolbar {

    flex: 0 0 60px;

    height: 60px;

    background: #fff;

    border-bottom: 1px solid #dfe3eb;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 16px;

    gap: 15px;

    box-shadow:
        0 2px 8px rgba(0,0,0,.08);

    position: relative;

    z-index: 20;

}


.viewer-toolbar-left {

    min-width: 0;

    display: flex;

    align-items: center;

    gap: 12px;

}


.viewer-toolbar-right {

    display: flex;

    align-items: center;

    gap: 5px;

    flex-shrink: 0;

}


/* ============================================================
   BACK
============================================================ */

.viewer-back {

    width: 38px;

    height: 38px;

    flex: 0 0 38px;

    display: flex;

    align-items: center;

    justify-content: center;

    color: #5a5c69;

    text-decoration: none !important;

    border-radius: 7px;

    transition:
        background .15s ease,
        color .15s ease;

}


.viewer-back:hover {

    background: #f1f3f9;

    color: #4e73df;

}


/* ============================================================
   FILE INFO
============================================================ */

.viewer-file {

    min-width: 0;

    display: flex;

    align-items: center;

}


.viewer-file-icon {

    width: 36px;

    height: 36px;

    flex: 0 0 36px;

    border-radius: 7px;

    background: rgba(78,115,223,.10);

    color: #4e73df;

    display: flex;

    align-items: center;

    justify-content: center;

    margin-right: 9px;

}


.viewer-file-info {

    min-width: 0;

}


.viewer-file-name {

    max-width: 450px;

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;

    font-size: .82rem;

    font-weight: 700;

    color: #3a3b45;

}


.viewer-file-type {

    font-size: .68rem;

    color: #858796;

    margin-top: 1px;

}


/* ============================================================
   BUTTON
============================================================ */

.viewer-btn {

    height: 36px;

    min-width: 36px;

    padding: 0 10px;

    border: 1px solid #dfe3eb;

    background: #fff;

    color: #5a5c69;

    border-radius: 6px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 6px;

    font-size: .72rem;

    font-weight: 600;

    cursor: pointer;

    text-decoration: none !important;

    transition:
        background .15s ease,
        border-color .15s ease,
        color .15s ease,
        opacity .15s ease;

}


.viewer-btn:hover {

    background: #f4f6fa;

    border-color: #cbd1dc;

    color: #4e73df;

}


.viewer-btn:disabled {

    opacity: .45;

    cursor: not-allowed;

}


.viewer-btn-icon {

    width: 36px;

    padding: 0;

}


.viewer-btn-primary {

    background: #4e73df;

    border-color: #4e73df;

    color: #fff;

}


.viewer-btn-primary:hover {

    background: #3d5fc4;

    border-color: #3d5fc4;

    color: #fff;

}


.viewer-zoom {

    width: 58px;

    height: 36px;

    padding: 0;

    border: 1px solid #dfe3eb;

    border-radius: 6px;

    background: #fff;

    color: #3a3b45;

    font-size: .72rem;

    font-weight: 700;

    cursor: pointer;

}


.viewer-zoom:hover {

    background: #f4f6fa;

    color: #4e73df;

}


.viewer-divider {

    width: 1px;

    height: 24px;

    background: #dfe3eb;

    margin: 0 5px;

}


.rotate-save-form {

    margin: 0;

    padding: 0;

}


/* ============================================================
   CONTENT
============================================================ */

.viewer-content {

    position: relative;

    flex: 1 1 auto;

    min-height: 0;

    width: 100%;

    height: calc(100vh - 60px);

    overflow: hidden;

}


/* ============================================================
   IMAGE STAGE
============================================================ */

.image-stage {

    position: absolute;

    inset: 0;

    overflow: auto;

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 20px;

    box-sizing: border-box;

    cursor: grab;

    background-color: #eef0f4;

    background-image:
        linear-gradient(
            45deg,
            #e4e7ec 25%,
            transparent 25%
        ),
        linear-gradient(
            -45deg,
            #e4e7ec 25%,
            transparent 25%
        ),
        linear-gradient(
            45deg,
            transparent 75%,
            #e4e7ec 75%
        ),
        linear-gradient(
            -45deg,
            transparent 75%,
            #e4e7ec 75%
        );

    background-size: 24px 24px;

    background-position:
        0 0,
        0 12px,
        12px -12px,
        -12px 0;

}


.image-stage.dragging {

    cursor: grabbing;

}


/* ============================================================
   IMAGE CANVAS
============================================================ */

.image-canvas {

    position: relative;

    display: flex;

    align-items: center;

    justify-content: center;

    flex: 0 0 auto;

}


/* ============================================================
   IMAGE
============================================================ */

.document-image {

    display: block;

    width: auto;

    height: auto;

    max-width: none;

    max-height: none;

    object-fit: contain;

    transform-origin: center center;

    box-shadow:
        0 6px 28px rgba(0,0,0,.22);

    border-radius: 2px;

    user-select: none;

    -webkit-user-select: none;

    -webkit-user-drag: none;

}


/* ============================================================
   HELP
============================================================ */

.image-help {

    position: absolute;

    left: 50%;

    bottom: 14px;

    transform: translateX(-50%);

    z-index: 10;

    background: rgba(30,32,40,.86);

    color: #fff;

    padding: 7px 13px;

    border-radius: 20px;

    font-size: .67rem;

    white-space: nowrap;

    pointer-events: none;

}


.image-help span {

    margin: 0 5px;

    opacity: .55;

}


/* ============================================================
   PDF
============================================================ */

.pdf-viewer {

    display: block;

    width: 100%;

    height: 100%;

    border: 0;

    background: #525659;

}


/* ============================================================
   ALERT
============================================================ */

.viewer-alert {

    position: absolute;

    top: 75px;

    left: 50%;

    transform: translateX(-50%);

    z-index: 50;

    min-width: 280px;

    max-width: 90vw;

    padding: 10px 15px;

    border-radius: 7px;

    box-shadow:
        0 5px 20px rgba(0,0,0,.15);

    display: flex;

    align-items: center;

    gap: 8px;

    font-size: .75rem;

}


.viewer-alert-success {

    background: #dff7ed;

    color: #157a58;

}


.viewer-alert-error {

    background: #fde4e4;

    color: #a32929;

}


/* ============================================================
   EMPTY
============================================================ */

.viewer-empty {

    width: 100%;

    height: 100%;

    display: flex;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    text-align: center;

}


.viewer-empty-icon {

    font-size: 3rem;

    color: #aeb4c0;

    margin-bottom: 12px;

}


.viewer-empty-title {

    font-size: 1rem;

    font-weight: 700;

    color: #5a5c69;

}


.viewer-empty-text {

    font-size: .75rem;

    color: #858796;

    margin-top: 4px;

}


/* ============================================================
   MOBILE
============================================================ */

@media (max-width: 900px) {

    .viewer-toolbar {

        padding: 0 8px;

    }


    .viewer-file-icon {

        display: none;

    }


    .viewer-file-name {

        max-width: 180px;

    }


    .viewer-btn span {

        display: none;

    }


    .viewer-btn {

        width: 36px;

        padding: 0;

    }


    .viewer-btn-primary {

        width: 36px;

    }


    .viewer-divider {

        margin: 0 2px;

    }

}


@media (max-width: 600px) {

    .viewer-toolbar {

        height: 54px;

        flex-basis: 54px;

        padding: 0 6px;

        gap: 5px;

    }


    .viewer-toolbar-right {

        gap: 2px;

    }


    .viewer-file-name {

        max-width: 130px;

        font-size: .73rem;

    }


    .viewer-file-type {

        display: none;

    }


    .viewer-content {

        height: calc(100vh - 54px);

    }


    .image-stage {

        padding: 8px;

    }


    .image-help {

        font-size: .60rem;

        max-width: 90%;

        overflow: hidden;

        text-overflow: ellipsis;

    }

}


/* ============================================================
   DARK MODE
============================================================ */

body.dark-mode .document-viewer {

    background: #1d1e2b;

}


body.dark-mode .viewer-toolbar {

    background: #292a3d;

    border-color: #46485c;

}


body.dark-mode .viewer-back {

    color: #c7cad4;

}


body.dark-mode .viewer-back:hover {

    background: #343548;

    color: #7da2ff;

}


body.dark-mode .viewer-file-name {

    color: #f1f3f5;

}


body.dark-mode .viewer-file-type {

    color: #aeb4c0;

}


body.dark-mode .viewer-btn,
body.dark-mode .viewer-zoom {

    background: #343544;

    border-color: #505267;

    color: #d5d8df;

}


body.dark-mode .viewer-btn:hover,
body.dark-mode .viewer-zoom:hover {

    background: #404256;

    border-color: #61647a;

    color: #7da2ff;

}


body.dark-mode .viewer-btn-primary {

    background: #4e73df;

    border-color: #4e73df;

    color: #fff;

}


body.dark-mode .viewer-divider {

    background: #505267;

}


body.dark-mode .image-stage {

    background-color: #252633;

    background-image:
        linear-gradient(
            45deg,
            #30313f 25%,
            transparent 25%
        ),
        linear-gradient(
            -45deg,
            #30313f 25%,
            transparent 25%
        ),
        linear-gradient(
            45deg,
            transparent 75%,
            #30313f 75%
        ),
        linear-gradient(
            -45deg,
            transparent 75%,
            #30313f 75%
        );

}


body.dark-mode .viewer-empty-title {

    color: #f1f3f5;

}


body.dark-mode .viewer-empty-text {

    color: #aeb4c0;

}

</style>


<script>

(function () {

    'use strict';


    var image =
        document.getElementById(
            'documentImage'
        );

    var stage =
        document.getElementById(
            'imageStage'
        );

    var canvas =
        document.getElementById(
            'imageCanvas'
        );


    if (!image || !stage || !canvas) {

        return;

    }


    var btnZoomIn =
        document.getElementById(
            'btnZoomIn'
        );

    var btnZoomOut =
        document.getElementById(
            'btnZoomOut'
        );

    var btnZoomValue =
        document.getElementById(
            'btnZoomValue'
        );

    var btnFit =
        document.getElementById(
            'btnFit'
        );

    var btnRotateLeft =
        document.getElementById(
            'btnRotateLeft'
        );

    var btnRotateRight =
        document.getElementById(
            'btnRotateRight'
        );

    var btnReset =
        document.getElementById(
            'btnReset'
        );

    var btnSave =
        document.getElementById(
            'btnSaveRotate'
        );

    var rotateForm =
        document.getElementById(
            'rotateSaveForm'
        );

    var rotateDirection =
        document.getElementById(
            'rotateDirection'
        );


    /* =========================================================
       STATE
    ========================================================== */

    var zoom = 1;

    var rotation = 0;

    var originalRotation = 0;

    var isDragging = false;

    var dragStartX = 0;

    var dragStartY = 0;

    var scrollStartLeft = 0;

    var scrollStartTop = 0;


    var MIN_ZOOM = 0.10;

var MAX_ZOOM = 5;

var ZOOM_STEP = 0.01;


    /* =========================================================
       NORMALIZE ROTATION
    ========================================================== */

    function normalizeRotation(value)
    {

        value =
            value % 360;


        if (value < 0) {

            value += 360;

        }


        return value;

    }


    /* =========================================================
       UPDATE ZOOM LABEL
    ========================================================== */

    function updateZoomLabel()
    {

        if (!btnZoomValue) {

            return;

        }


        btnZoomValue.textContent =
            Math.round(
                zoom * 100
            ) + '%';

    }


    /* =========================================================
       UPDATE SAVE
    ========================================================== */

    function updateSave()
    {

        if (!btnSave) {

            return;

        }


        btnSave.disabled =
            rotation === originalRotation;

    }


    /* =========================================================
       UPDATE CANVAS SIZE
    ========================================================== */

    function updateCanvasSize()
    {

        if (
            !image.naturalWidth ||
            !image.naturalHeight
        ) {

            return;

        }


        var width =
            image.naturalWidth *
            zoom;


        var height =
            image.naturalHeight *
            zoom;


        /*
         * Jika diputar 90 / 270,
         * ukuran visual bertukar.
         */

        if (
            rotation === 90 ||
            rotation === 270
        ) {

            var temp =
                width;

            width =
                height;

            height =
                temp;

        }


        canvas.style.width =
            Math.max(
                width,
                stage.clientWidth - 40
            ) + 'px';


        canvas.style.height =
            Math.max(
                height,
                stage.clientHeight - 40
            ) + 'px';

    }


    /* =========================================================
       APPLY
    ========================================================== */

    function applyImage()
    {

        updateCanvasSize();


        image.style.width =
            image.naturalWidth + 'px';


        image.style.height =
            image.naturalHeight + 'px';


        image.style.transform =
            'scale(' +
            zoom +
            ') rotate(' +
            rotation +
            'deg)';


        updateZoomLabel();

        updateSave();

    }


    /* =========================================================
       SET ZOOM
    ========================================================== */

    function setZoom(value)
{

    value =
        Math.max(
            MIN_ZOOM,
            Math.min(
                MAX_ZOOM,
                value
            )
        );


    zoom =
        Math.round(
            value * 100
        ) / 100;


    applyImage();

}


    /* =========================================================
       CALCULATE FIT
    ========================================================== */

    function calculateFit()
    {

        if (
            !image.naturalWidth ||
            !image.naturalHeight
        ) {

            return 1;

        }


        var availableWidth =
            stage.clientWidth - 40;


        var availableHeight =
            stage.clientHeight - 40;


        var imageWidth =
            image.naturalWidth;


        var imageHeight =
            image.naturalHeight;


        /*
         * Rotasi 90 / 270
         */

        if (
            rotation === 90 ||
            rotation === 270
        ) {

            var temp =
                imageWidth;

            imageWidth =
                imageHeight;

            imageHeight =
                temp;

        }


        var widthRatio =
            availableWidth /
            imageWidth;


        var heightRatio =
            availableHeight /
            imageHeight;


        return Math.min(
            widthRatio,
            heightRatio
        );

    }


    /* =========================================================
       FIT SCREEN
    ========================================================== */

    function fitImage()
    {

        zoom =
            calculateFit();


        applyImage();


        centerImage();

    }


    /* =========================================================
       CENTER IMAGE
    ========================================================== */

    function centerImage()
    {

        setTimeout(
            function ()
            {

                var maxScrollLeft =
                    Math.max(
                        0,
                        (
                            stage.scrollWidth -
                            stage.clientWidth
                        ) / 2
                    );


                var maxScrollTop =
                    Math.max(
                        0,
                        (
                            stage.scrollHeight -
                            stage.clientHeight
                        ) / 2
                    );


                stage.scrollLeft =
                    maxScrollLeft;


                stage.scrollTop =
                    maxScrollTop;

            },
            50
        );

    }


    /* =========================================================
       ZOOM IN
    ========================================================== */

    if (btnZoomIn) {

        btnZoomIn.addEventListener(
            'click',
            function ()
            {

                setZoom(
                    zoom + ZOOM_STEP
                );

            }
        );

    }


    /* =========================================================
       ZOOM OUT
    ========================================================== */

    if (btnZoomOut) {

        btnZoomOut.addEventListener(
            'click',
            function ()
            {

                setZoom(
                    zoom - ZOOM_STEP
                );

            }
        );

    }


    /* =========================================================
       ZOOM 100%
    ========================================================== */

    if (btnZoomValue) {

        btnZoomValue.addEventListener(
            'click',
            function ()
            {

                setZoom(1);

            }
        );

    }


    /* =========================================================
       FIT
    ========================================================== */

    if (btnFit) {

        btnFit.addEventListener(
            'click',
            function ()
            {

                fitImage();

            }
        );

    }


    /* =========================================================
       ROTATE LEFT
    ========================================================== */

    if (btnRotateLeft) {

        btnRotateLeft.addEventListener(
            'click',
            function ()
            {

                rotation =
                    normalizeRotation(
                        rotation - 90
                    );


                applyImage();


                /*
                 * Setelah rotasi,
                 * tetap berada di tengah.
                 */

                centerImage();

            }
        );

    }


    /* =========================================================
       ROTATE RIGHT
    ========================================================== */

    if (btnRotateRight) {

        btnRotateRight.addEventListener(
            'click',
            function ()
            {

                rotation =
                    normalizeRotation(
                        rotation + 90
                    );


                applyImage();


                centerImage();

            }
        );

    }


    /* =========================================================
       RESET
    ========================================================== */

    if (btnReset) {

        btnReset.addEventListener(
            'click',
            function ()
            {

                rotation =
                    originalRotation;


                fitImage();

            }
        );

    }


    /* =========================================================
       MOUSE WHEEL
    ========================================================== */

    stage.addEventListener(
        'wheel',
        function (event)
        {

            event.preventDefault();


            if (event.deltaY < 0) {

                setZoom(
                    zoom + ZOOM_STEP
                );

            }

            else {

                setZoom(
                    zoom - ZOOM_STEP
                );

            }

        },
        {
            passive: false
        }
    );


    /* =========================================================
       DRAG START
    ========================================================== */

    stage.addEventListener(
        'mousedown',
        function (event)
        {

            if (
                event.button !== 0
            ) {

                return;

            }


            isDragging = true;


            dragStartX =
                event.clientX;


            dragStartY =
                event.clientY;


            scrollStartLeft =
                stage.scrollLeft;


            scrollStartTop =
                stage.scrollTop;


            stage.classList.add(
                'dragging'
            );


            event.preventDefault();

        }
    );


    /* =========================================================
       DRAG MOVE
    ========================================================== */

    document.addEventListener(
        'mousemove',
        function (event)
        {

            if (!isDragging) {

                return;

            }


            stage.scrollLeft =
                scrollStartLeft -
                (
                    event.clientX -
                    dragStartX
                );


            stage.scrollTop =
                scrollStartTop -
                (
                    event.clientY -
                    dragStartY
                );

        }
    );


    /* =========================================================
       DRAG END
    ========================================================== */

    document.addEventListener(
        'mouseup',
        function ()
        {

            if (!isDragging) {

                return;

            }


            isDragging = false;


            stage.classList.remove(
                'dragging'
            );

        }
    );


    /* =========================================================
       DOUBLE CLICK
    ========================================================== */

    image.addEventListener(
        'dblclick',
        function ()
        {

            if (zoom < 1.05) {

                setZoom(2);

            }

            else {

                fitImage();

            }

        }
    );


    /* =========================================================
       KEYBOARD
    ========================================================== */

    document.addEventListener(
        'keydown',
        function (event)
        {

            if (
                event.target.tagName === 'INPUT' ||
                event.target.tagName === 'TEXTAREA'
            ) {

                return;

            }


            if (
                event.key === '+' ||
                event.key === '='
            ) {

                setZoom(
                    zoom + ZOOM_STEP
                );

            }


            if (
                event.key === '-' ||
                event.key === '_'
            ) {

                setZoom(
                    zoom - ZOOM_STEP
                );

            }


            if (
                event.key === 'r' ||
                event.key === 'R'
            ) {

                if (btnRotateRight) {

                    btnRotateRight.click();

                }

            }


            if (
                event.key === 'l' ||
                event.key === 'L'
            ) {

                if (btnRotateLeft) {

                    btnRotateLeft.click();

                }

            }


            if (
                event.key === 'f' ||
                event.key === 'F'
            ) {

                fitImage();

            }


            if (
                event.key === 'Escape'
            ) {

                window.close();

            }

        }
    );


    /* =========================================================
       SAVE ROTATION
    ========================================================== */

    if (rotateForm) {

        rotateForm.addEventListener(
            'submit',
            function (event)
            {

                if (
                    rotation ===
                    originalRotation
                ) {

                    event.preventDefault();

                    return false;

                }


                var difference =
                    normalizeRotation(
                        rotation -
                        originalRotation
                    );


                if (difference === 90) {

                    rotateDirection.value =
                        'right';

                }

                else if (difference === 270) {

                    rotateDirection.value =
                        'left';

                }

                else if (difference === 180) {

                    rotateDirection.value =
                        '180';

                }

                else {

                    event.preventDefault();

                    return false;

                }


                if (btnSave) {

                    btnSave.disabled =
                        true;

                    btnSave.innerHTML =
                        '<i class="fas fa-spinner fa-spin"></i>' +
                        '<span>Menyimpan...</span>';

                }

            }
        );

    }


    /* =========================================================
       IMAGE LOAD
    ========================================================== */

    function initializeViewer()
    {

        if (
            !image.naturalWidth ||
            !image.naturalHeight
        ) {

            return;

        }


        /*
         * Pastikan ukuran awal dihitung
         * setelah gambar benar-benar loaded.
         */

        image.style.width =
            image.naturalWidth + 'px';


        image.style.height =
            image.naturalHeight + 'px';


        fitImage();

    }


    if (image.complete) {

        setTimeout(
            initializeViewer,
            100
        );

    }

    else {

        image.addEventListener(
            'load',
            initializeViewer
        );

    }


    /* =========================================================
       RESIZE
    ========================================================== */

    var resizeTimer;


    window.addEventListener(
        'resize',
        function ()
        {

            clearTimeout(
                resizeTimer
            );


            resizeTimer =
                setTimeout(
                    function ()
                    {

                        fitImage();

                    },
                    150
                );

        }
    );


    /*
     * Initial
     */

    updateZoomLabel();

    updateSave();

})();

</script>