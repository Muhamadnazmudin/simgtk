<style>
.book-cover {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 8px;
    background: #f3f4f6;
}

.book-title {
    font-size: 15px;
    font-weight: 600;
    line-height: 1.3;
    max-height: 40px;
    overflow: hidden;
}
.book-cover {
    transition: transform .2s ease, box-shadow .2s ease;
}

.book-cover:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 20px rgba(0,0,0,.15);
}

</style>

<div class="container-fluid">

    <h1 class="h4 mb-4 text-gray-800">
        <i class="fas fa-book-reader"></i> Bacaan / E-Book
    </h1>

    <!-- FILTER -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body p-3">

            <form method="get" class="row g-2">

                <!-- FILTER KELAS -->
                <div class="col-12 col-md-4">
                    <select name="kelas"
                            class="form-control form-control-sm"
                            onchange="this.form.submit()">
                        <option value="">Semua Kelas</option>
                        <?php foreach (['Umum','X','XI','XII'] as $k): ?>
                            <option value="<?= $k ?>"
                                <?= ($filter_kelas == $k) ? 'selected' : '' ?>>
                                <?= $k ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- FILTER MAPEL -->
                <div class="col-12 col-md-4">
                    <select name="mapel"
                            class="form-control form-control-sm"
                            onchange="this.form.submit()">
                        <option value="">Semua Mapel</option>
                        <?php foreach ($mapel_list as $m): ?>
                            <option value="<?= $m->mapel ?>"
                                <?= ($filter_mapel == $m->mapel) ? 'selected' : '' ?>>
                                <?= $m->mapel ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- RESET -->
                <div class="col-12 col-md-4">
                    <a href="<?= site_url('SiswaBacaan') ?>"
                       class="btn btn-secondary btn-sm w-100">
                        <i class="fas fa-sync"></i> Reset
                    </a>
                </div>

            </form>

        </div>
    </div>

    <!-- LIST BUKU -->
    <?php if (empty($buku)) : ?>

        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i>
            Belum ada bacaan tersedia.
        </div>

    <?php else : ?>

        <div class="row">

            <?php foreach ($buku as $b) : ?>

                <div class="col-12 col-sm-6 col-lg-3 mb-4">

                    <div class="card shadow-sm h-100 border-0">

                        <!-- COVER -->
                        <?php if (!empty($b->cover)) : ?>
                            <img src="<?= base_url('assets/uploads/cover_buku/' . $b->cover) ?>"
     class="book-cover"
     style="cursor:pointer"
     onclick="previewCover(this.src)"
     alt="<?= htmlspecialchars($b->judul) ?>">

                        <?php else : ?>
                            <div class="book-cover d-flex align-items-center justify-content-center text-muted">
                                <i class="fas fa-book fa-2x"></i>
                            </div>
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column p-3">

                            <div class="book-title mb-2">
                                <?= htmlspecialchars($b->judul) ?>
                            </div>

                            <small class="text-muted mb-1">
                                <i class="fas fa-book"></i>
                                <?= $b->mapel ?: '-' ?>
                            </small>

                            <small class="text-muted mb-3">
                                <i class="fas fa-layer-group"></i>
                                Kelas <?= $b->kelas ?>
                            </small>

                            <a href="<?= site_url('SiswaBacaan/baca/' . $b->id) ?>"
                               class="btn btn-primary btn-sm mt-auto">
                                <i class="fas fa-eye"></i> Baca
                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <!-- PAGINATION -->
    <?php if (!empty($pagination)) : ?>
        <div class="mt-3">
            <?= $pagination ?>
        </div>
    <?php endif; ?>
<!-- MODAL PREVIEW COVER (SB ADMIN 2 - BOOTSTRAP 4) -->
<div class="modal fade" id="modalCover" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-transparent border-0 text-center">

            <!-- CLOSE -->
            <button type="button"
                    class="close text-white position-absolute"
                    style="right:20px; top:10px; font-size:32px; z-index:1051"
                    data-dismiss="modal">
                <span>&times;</span>
            </button>

            <!-- IMAGE -->
            <img id="modalCoverImg"
                 src=""
                 style="
                    max-width: 100%;
                    max-height: 85vh;
                    object-fit: contain;
                 "
                 class="rounded shadow">
        </div>
    </div>
</div>

</div>
<script>
$('#modalCover').on('click', function (e) {
    if (e.target === this) {
        $(this).modal('hide');
    }
});
</script>

<script>
function previewCover(src) {
    $('#modalCoverImg').attr('src', src);
    $('#modalCover').modal('show');
}
</script>

