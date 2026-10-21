<?php
$is_edit = isset($row);
$action  = $is_edit
    ? site_url('tutorial/update/'.$row->id)
    : site_url('tutorial/store');
?>

<form method="post" action="<?= $action ?>" enctype="multipart/form-data">

<div class="card shadow">
    <div class="card-header">
        <h5 class="mb-0"><?= $title ?></h5>
    </div>

    <div class="card-body">

        <!-- JUDUL -->
        <div class="form-group">
            <label>Judul Video</label>
            <input type="text"
                   name="judul"
                   class="form-control"
                   value="<?= $is_edit ? htmlspecialchars($row->judul) : '' ?>"
                   required>
        </div>

        <!-- TIPE VIDEO -->
        <div class="form-group">
            <label>Tipe Video</label>
            <select name="tipe" class="form-control" id="tipe">
                <option value="youtube"
                    <?= $is_edit && $row->tipe=='youtube' ? 'selected' : '' ?>>
                    YouTube
                </option>
                <option value="upload"
                    <?= $is_edit && $row->tipe=='upload' ? 'selected' : '' ?>>
                    Upload Video
                </option>
            </select>
        </div>

        <!-- YOUTUBE -->
        <div class="form-group" id="youtube">
            <label>Link YouTube</label>
            <input type="text"
                   name="video_url"
                   class="form-control"
                   value="<?= $is_edit ? $row->video_url : '' ?>"
                   placeholder="https://www.youtube.com/embed/xxxxx">
        </div>

        <!-- UPLOAD VIDEO -->
        <div class="form-group d-none" id="upload">
            <label>Upload Video</label>
            <input type="file" name="video_file" class="form-control">

            <?php if ($is_edit && !empty($row->video_file)): ?>
                <small class="text-muted">
                    File saat ini: <?= $row->video_file ?>
                </small>
            <?php endif; ?>
        </div>

        <!-- THUMBNAIL -->
        <div class="form-group">
            <label>Thumbnail</label>
            <input type="file" name="thumbnail" class="form-control">

            <?php if ($is_edit && !empty($row->thumbnail)): ?>
                <div class="mt-2">
                    <img src="<?= base_url('uploads/tutorial/thumbnail/'.$row->thumbnail) ?>"
                         class="img-thumbnail"
                         style="max-height:120px;">
                </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="card-footer text-right">
        <a href="<?= site_url('tutorial') ?>" class="btn btn-secondary">
            Kembali
        </a>
        <button class="btn btn-success">
            <i class="fas fa-save"></i> Simpan
        </button>
    </div>

    <!-- CSRF -->
    <input type="hidden"
           name="<?= $this->security->get_csrf_token_name(); ?>"
           value="<?= $this->security->get_csrf_hash(); ?>">

</div>

</form>

<script>
function toggleVideoInput() {
    let tipe = document.getElementById('tipe').value;
    document.getElementById('youtube').classList.toggle('d-none', tipe !== 'youtube');
    document.getElementById('upload').classList.toggle('d-none', tipe !== 'upload');
}

// trigger saat load (PENTING untuk EDIT)
document.getElementById('tipe').addEventListener('change', toggleVideoInput);
toggleVideoInput();
</script>
