<div class="container-fluid">

    <h4 class="mb-4"><?= $title ?></h4>

    <div class="row">
        <?php if (!empty($tutorial)): ?>
            <?php foreach ($tutorial as $row): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">

                    <?php if ($row->thumbnail): ?>
                        <img src="<?= base_url('uploads/tutorial/thumbnail/'.$row->thumbnail) ?>"
                             class="card-img-top"
                             style="height:180px;object-fit:cover;">
                    <?php endif; ?>

                    <div class="card-body">
                        <h6 class="card-title">
                            <?= htmlspecialchars($row->judul) ?>
                        </h6>

                        <span class="badge badge-<?= $row->tipe=='youtube'?'danger':'info' ?>">
                            <?= strtoupper($row->tipe) ?>
                        </span>
                    </div>

                    <div class="card-footer bg-white border-0">
                        <a href="<?= site_url('SiswaTutorial/lihat/'.$row->id) ?>"
                           class="btn btn-primary btn-sm btn-block">
                            <i class="fas fa-play"></i> Tonton
                        </a>
                    </div>

                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    Belum ada tutorial tersedia.
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>
