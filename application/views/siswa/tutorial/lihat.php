<div class="container-fluid">

    <a href="<?= site_url('SiswaTutorial'); ?>" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    <div class="card shadow">
        <div class="card-header">
            <h5 class="mb-0"><?= htmlspecialchars($row->judul) ?></h5>
        </div>

        <div class="card-body">

            <?php if ($row->tipe == 'youtube'): ?>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item"
                            src="<?= $row->video_url ?>"
                            allowfullscreen></iframe>
                </div>
            <?php else: ?>
                <video controls width="100%">
                    <source src="<?= base_url('uploads/tutorial/video/'.$row->video_file) ?>">
                    Browser tidak mendukung video.
                </video>
            <?php endif; ?>

        </div>
    </div>

</div>
