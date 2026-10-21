<div class="container-fluid">

    <!-- ================= HEADER ================= -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">

        <div>
            <h4 class="mb-1 font-weight-bold text-gray-800">
                <i class="fas fa-graduation-cap text-primary mr-2"></i>
                <?= htmlspecialchars($title) ?>
            </h4>

            <div class="text-muted small">
                <i class="fas fa-play-circle mr-1"></i>
                Kumpulan tutorial dan panduan pembelajaran
            </div>
        </div>

        <?php if (!empty($tutorial)): ?>
            <div class="mt-3 mt-md-0">
                <span class="badge badge-primary px-3 py-2">
                    <i class="fas fa-video mr-1"></i>
                    <?= count($tutorial) ?> Tutorial
                </span>
            </div>
        <?php endif; ?>

    </div>


    <!-- ================= STYLE ================= -->
    <style>

        /* ================= TUTORIAL CARD ================= */

        .tutorial-card {
            border: 0;
            border-radius: 14px;
            overflow: hidden;
            transition: all .25s ease;
            background: #fff;
        }

        .tutorial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 28px rgba(0,0,0,.12) !important;
        }


        /* ================= THUMBNAIL ================= */

        .tutorial-thumbnail {
            position: relative;
            height: 190px;
            overflow: hidden;
            background: #f1f3f5;
        }

        .tutorial-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .35s ease;
        }

        .tutorial-card:hover .tutorial-thumbnail img {
            transform: scale(1.05);
        }


        /* ================= PLAY BUTTON ================= */

        .thumbnail-overlay {
            position: absolute;
            inset: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            background: rgba(0,0,0,.15);

            transition: all .25s ease;
        }

        .tutorial-card:hover .thumbnail-overlay {
            background: rgba(0,0,0,.35);
        }

        .play-button {
            width: 54px;
            height: 54px;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: rgba(255,255,255,.95);
            color: #4e73df;

            font-size: 20px;

            box-shadow: 0 5px 18px rgba(0,0,0,.25);

            transition: all .25s ease;
        }

        .tutorial-card:hover .play-button {
            transform: scale(1.08);
            background: #4e73df;
            color: #fff;
        }


        /* ================= TYPE BADGE ================= */

        .tutorial-type {
            position: absolute;
            top: 12px;
            right: 12px;

            padding: 5px 10px;

            border-radius: 20px;

            font-size: 10px;
            font-weight: 700;

            letter-spacing: .4px;

            box-shadow: 0 2px 8px rgba(0,0,0,.2);
        }


        /* ================= CARD BODY ================= */

        .tutorial-body {
            padding: 18px;
        }

        .tutorial-title {
            font-size: 15px;
            font-weight: 700;

            line-height: 1.45;

            color: #3a3b45;

            margin-bottom: 0;

            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;

            min-height: 44px;
        }


        /* ================= CARD FOOTER ================= */

        .tutorial-footer {
            padding: 0 18px 18px;
        }

        .btn-watch {
            border-radius: 8px;
            font-weight: 600;

            transition: all .2s ease;
        }

        .btn-watch:hover {
            transform: translateY(-1px);
        }


        /* ================= EMPTY STATE ================= */

        .empty-tutorial {
            padding: 60px 20px;
            text-align: center;
        }

        .empty-tutorial-icon {
            width: 80px;
            height: 80px;

            margin: 0 auto 20px;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: rgba(78,115,223,.1);

            color: #4e73df;

            font-size: 30px;
        }


        /* ================= DARK MODE ================= */

        body.dark-mode .tutorial-card,
        body.dark-mode .tutorial-card .card-footer {
            background: #2b2d31 !important;
            color: #fff;
        }

        body.dark-mode .tutorial-title {
            color: #f1f1f1;
        }

        body.dark-mode .tutorial-thumbnail {
            background: #202124;
        }

        body.dark-mode .text-gray-800 {
            color: #f1f1f1 !important;
        }

        body.dark-mode .text-muted {
            color: #adb5bd !important;
        }


        /* ================= MOBILE ================= */

        @media (max-width: 767.98px) {

            .tutorial-thumbnail {
                height: 200px;
            }

            .tutorial-body {
                padding: 16px;
            }

            .tutorial-footer {
                padding: 0 16px 16px;
            }

        }

    </style>


    <!-- ================= CONTENT ================= -->

    <div class="row">

        <?php if (!empty($tutorial)): ?>

            <?php foreach ($tutorial as $row): ?>

                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">

                    <div class="card tutorial-card h-100 shadow-sm">

                        <!-- THUMBNAIL -->
                        <a href="<?= site_url('SiswaTutorial/lihat/'.$row->id) ?>"
                           class="text-decoration-none">

                            <div class="tutorial-thumbnail">

                                <?php if (!empty($row->thumbnail)): ?>

                                    <img
                                        src="<?= base_url('uploads/tutorial/thumbnail/'.$row->thumbnail) ?>"
                                        alt="<?= htmlspecialchars($row->judul) ?>"
                                        loading="lazy">

                                <?php else: ?>

                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">

                                        <i class="fas fa-video fa-3x text-muted"></i>

                                    </div>

                                <?php endif; ?>


                                <!-- OVERLAY -->
                                <div class="thumbnail-overlay">

                                    <div class="play-button">
                                        <i class="fas fa-play"></i>
                                    </div>

                                </div>


                                <!-- TYPE -->
                                <span class="tutorial-type
                                    <?= $row->tipe == 'youtube'
                                        ? 'badge badge-danger'
                                        : 'badge badge-info'
                                    ?>">

                                    <i class="fas
                                        <?= $row->tipe == 'youtube'
                                            ? 'fa-youtube'
                                            : 'fa-file-video'
                                        ?> mr-1">
                                    </i>

                                    <?= strtoupper($row->tipe) ?>

                                </span>

                            </div>

                        </a>


                        <!-- BODY -->
                        <div class="tutorial-body">

                            <h6 class="tutorial-title"
                                title="<?= htmlspecialchars($row->judul) ?>">

                                <?= htmlspecialchars($row->judul) ?>

                            </h6>

                        </div>


                        <!-- FOOTER -->
                        <div class="tutorial-footer mt-auto">

                            <a href="<?= site_url('SiswaTutorial/lihat/'.$row->id) ?>"
                               class="btn btn-primary btn-block btn-watch">

                                <i class="fas fa-play-circle mr-1"></i>

                                Tonton Tutorial

                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>


        <?php else: ?>

            <!-- EMPTY STATE -->

            <div class="col-12">

                <div class="card shadow-sm border-0">

                    <div class="card-body empty-tutorial">

                        <div class="empty-tutorial-icon">

                            <i class="fas fa-video"></i>

                        </div>

                        <h5 class="font-weight-bold text-gray-800">
                            Belum Ada Tutorial
                        </h5>

                        <p class="text-muted mb-0">
                            Saat ini belum tersedia tutorial untuk ditonton.
                        </p>

                    </div>

                </div>

            </div>

        <?php endif; ?>

    </div>

</div>