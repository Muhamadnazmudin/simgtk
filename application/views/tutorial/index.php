<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><?= $title ?></h5>
        <a href="<?= site_url('tutorial/add') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah
        </a>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="thead-light">
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="15%" class="text-center">Thumbnail</th>
                    <th>Judul</th>
                    <th width="10%" class="text-center">Tipe</th>
                    <th width="20%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($tutorial)): ?>
                <?php $no=1; foreach($tutorial as $row): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>

                    <!-- THUMBNAIL -->
                    <td class="text-center">
                        <?php if (!empty($row->thumbnail)): ?>
                            <img src="<?= base_url('uploads/tutorial/thumbnail/'.$row->thumbnail) ?>"
                                 class="img-thumbnail"
                                 style="max-height:80px;">
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>

                    <!-- JUDUL -->
                    <td><?= htmlspecialchars($row->judul) ?></td>

                    <!-- TIPE -->
                    <td class="text-center">
                        <?php if ($row->tipe == 'youtube'): ?>
                            <span class="badge badge-danger">YouTube</span>
                        <?php else: ?>
                            <span class="badge badge-info">Upload</span>
                        <?php endif; ?>
                    </td>

                    <!-- AKSI -->
                    <td class="text-center">
                        <a href="<?= site_url('tutorial/edit/'.$row->id) ?>"
                           class="btn btn-warning btn-sm mb-1">
                            <i class="fas fa-edit"></i>
                        </a>

                        <a href="<?= site_url('tutorial/delete/'.$row->id) ?>"
                           class="btn btn-danger btn-sm mb-1"
                           onclick="return confirm('Yakin ingin menghapus tutorial ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Belum ada data tutorial
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
