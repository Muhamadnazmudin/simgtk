<?php if ($row->tipe == 'youtube'): ?>

    <?php
    $url = trim($row->video_url);
    $video_id = '';

    // Format: youtube.com/watch?v=xxxxx
    if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $match)) {
        $video_id = $match[1];
    }

    // Format: youtu.be/xxxxx
    elseif (preg_match('/youtu\.be\/([^?&]+)/', $url, $match)) {
        $video_id = $match[1];
    }

    // Format: youtube.com/embed/xxxxx
    elseif (preg_match('/youtube\.com\/embed\/([^?&]+)/', $url, $match)) {
        $video_id = $match[1];
    }
    ?>

    <?php if ($video_id): ?>

        <div class="embed-responsive embed-responsive-16by9">
            <iframe
                class="embed-responsive-item"
                src="https://www.youtube.com/embed/<?= htmlspecialchars($video_id) ?>"
                title="<?= htmlspecialchars($row->judul) ?>"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen>
            </iframe>
        </div>

    <?php else: ?>

        <div class="alert alert-danger">
            URL YouTube tidak valid.
        </div>

    <?php endif; ?>

<?php else: ?>

    <video controls width="100%">
        <source src="<?= base_url('uploads/tutorial/video/'.$row->video_file) ?>">
        Browser tidak mendukung video.
    </video>

<?php endif; ?>