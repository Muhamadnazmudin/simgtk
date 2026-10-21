
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$filter = isset($filter) && is_array($filter) ? $filter : [];
$rekap  = isset($rekap) && is_array($rekap) ? $rekap : [];

$tanggal_mulai   = isset($filter['tanggal_mulai']) ? $filter['tanggal_mulai'] : '';
$tanggal_selesai = isset($filter['tanggal_selesai']) ? $filter['tanggal_selesai'] : '';
$tanggal         = isset($filter['tanggal']) ? $filter['tanggal'] : '';
$kelas_id        = isset($filter['kelas_id']) ? $filter['kelas_id'] : '';
$keyword         = isset($filter['keyword']) ? $filter['keyword'] : '';
$sesi_id         = isset($filter['sesi_id']) ? $filter['sesi_id'] : '';
?>

<style>
    .solat-rekap {
        color: var(--solat-text, #212529);
        font-size: .9rem;
    }

    .solat-rekap .rekap-card {
        background: var(--solat-card, #fff);
        border: 1px solid var(--solat-border, #e3e6f0);
        border-radius: 14px;
        margin-bottom: 22px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, .04);
    }

    .solat-rekap .rekap-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid var(--solat-border, #e3e6f0);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .solat-rekap .rekap-card-body {
        padding: 20px;
    }

    .solat-rekap .rekap-title {
        margin: 0;
        font-weight: 700;
        font-size: 1rem;
    }

    .solat-rekap .rekap-subtitle {
        color: var(--solat-muted, #858796);
        margin-top: 5px;
        font-size: .82rem;
    }

    .solat-rekap .form-control,
    .solat-rekap .custom-select,
    .solat-rekap select {
        min-height: 42px;
        border-radius: 8px;
        border-color: var(--solat-border, #d1d3e2);
        background-color: var(--solat-input, #fff);
        color: var(--solat-text, #495057);
    }

    .solat-rekap .form-control:focus,
    .solat-rekap select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 .15rem rgba(78, 115, 223, .15);
    }

    .solat-rekap .btn {
        border-radius: 8px;
        padding: 9px 15px;
        font-weight: 600;
    }

    .solat-rekap .table-responsive {
        border-radius: 9px;
    }

    .solat-rekap .table {
        color: var(--solat-text, #212529);
        margin-bottom: 0;
        white-space: nowrap;
    }

    .solat-rekap .table thead th {
        background: var(--solat-table-head, #f8f9fc);
        color: var(--solat-text, #5a5c69);
        border-color: var(--solat-border, #e3e6f0);
        font-size: .79rem;
        font-weight: 700;
        padding: 14px 12px;
        vertical-align: middle;
    }

    .solat-rekap .table tbody td {
        border-color: var(--solat-border, #e3e6f0);
        padding: 13px 12px;
        vertical-align: middle;
    }

    .solat-rekap .table tbody tr:hover {
        background: var(--solat-hover, #f8f9fc);
    }

    .solat-rekap .student-name {
        font-weight: 600;
    }

    .solat-rekap .nisn {
        color: var(--solat-muted, #858796);
        font-size: .84rem;
    }

    .solat-rekap .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 700;
    }

    .solat-rekap .status-hadir {
        color: #1cc88a;
        background: rgba(28, 200, 138, .13);
    }

    .solat-rekap .status-terlambat {
        color: #f6c23e;
        background: rgba(246, 194, 62, .16);
    }

    .solat-rekap .status-lain {
        color: var(--solat-muted, #858796);
        background: rgba(133, 135, 150, .13);
    }

    .solat-rekap .method-badge {
        display: inline-block;
        border: 1px solid var(--solat-border, #e3e6f0);
        border-radius: 6px;
        padding: 4px 7px;
        font-size: .73rem;
        font-weight: 700;
    }

    .solat-rekap .empty-state {
        padding: 45px 20px !important;
        text-align: center;
        color: var(--solat-muted, #858796);
        white-space: normal;
    }

    .solat-rekap .empty-state i {
        font-size: 2rem;
        display: block;
        margin-bottom: 12px;
        opacity: .6;
    }

    .solat-rekap .result-count {
        color: var(--solat-muted, #858796);
        font-size: .83rem;
    }

    /* Mendukung tema gelap jika body memakai dark-mode */
    body.dark-mode .solat-rekap,
    body.dark-mode .solat-rekap .table {
        --solat-text: #e5e7eb;
        --solat-muted: #a7adbb;
        --solat-card: #252638;
        --solat-border: #414256;
        --solat-input: #2c2d40;
        --solat-table-head: #34354b;
        --solat-hover: #303146;
    }

    @media (max-width: 767.98px) {
        .solat-rekap .rekap-card-header,
        .solat-rekap .rekap-card-body {
            padding: 15px;
        }

        .solat-rekap .filter-actions {
            display: flex;
            gap: 8px;
        }

        .solat-rekap .filter-actions .btn {
            flex: 1;
        }
    }
</style>

<div class="container-fluid solat-rekap">

    <!-- HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 font-weight-bold">
                <i class="fas fa-clipboard-list text-primary mr-2"></i>
                Rekap Absensi Salat Dzuhur
            </h1>
            <div class="text-muted">
                Rekap kehadiran siswa berdasarkan sesi dan tahun ajaran aktif.
            </div>
        </div>

        <a href="<?= base_url('absensi_solat'); ?>"
           class="btn btn-secondary mt-3 mt-sm-0">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>
    </div>

    <!-- FILTER -->
    <div class="rekap-card">
        <div class="rekap-card-header">
            <div>
                <h6 class="rekap-title">
                    <i class="fas fa-filter text-primary mr-2"></i>
                    Filter Rekap
                </h6>
                <div class="rekap-subtitle">
                    Atur tanggal, kelas, sesi, atau cari siswa.
                </div>
            </div>
        </div>

        <div class="rekap-card-body">
            <form method="get"
                  action="<?= base_url('absensi_solat/rekap'); ?>">

                <div class="form-row">

                    <div class="form-group col-md-3">
                        <label for="tanggal_mulai">Dari tanggal</label>
                        <input type="date"
                               class="form-control"
                               id="tanggal_mulai"
                               name="tanggal_mulai"
                               value="<?= html_escape($tanggal_mulai); ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="tanggal_selesai">Sampai tanggal</label>
                        <input type="date"
                               class="form-control"
                               id="tanggal_selesai"
                               name="tanggal_selesai"
                               value="<?= html_escape($tanggal_selesai); ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="kelas_id">Kelas</label>
                        <select class="form-control"
                                id="kelas_id"
                                name="kelas_id">
                            <option value="">Semua kelas</option>

                            <?php if (!empty($kelas)): ?>
                                <?php foreach ($kelas as $k): ?>
                                    <option value="<?= (int) $k->id; ?>"
                                        <?= (string) $kelas_id === (string) $k->id
                                            ? 'selected' : ''; ?>>
                                        <?= html_escape($k->nama); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="keyword">Cari siswa</label>
                        <input type="text"
                               class="form-control"
                               id="keyword"
                               name="keyword"
                               placeholder="Nama, NIS, atau NISN"
                               value="<?= html_escape($keyword); ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="tanggal">Tanggal tertentu</label>
                        <input type="date"
                               class="form-control"
                               id="tanggal"
                               name="tanggal"
                               value="<?= html_escape($tanggal); ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="sesi_id">Sesi absensi</label>
                        <select class="form-control"
                                id="sesi_id"
                                name="sesi_id">
                            <option value="">Semua sesi</option>

                            <?php if (!empty($sesi)): ?>
                                <?php foreach ($sesi as $s): ?>
                                    <option value="<?= (int) $s->id; ?>"
                                        <?= (string) $sesi_id === (string) $s->id
                                            ? 'selected' : ''; ?>>
                                        <?= html_escape($s->tanggal); ?>
                                        - <?= html_escape(ucfirst($s->jenis_solat)); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                </div>

                <div class="filter-actions d-flex flex-wrap align-items-center">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-search mr-1"></i>
                        Tampilkan
                    </button>

                    <a href="<?= base_url('absensi_solat/rekap'); ?>"
                       class="btn btn-secondary">
                        <i class="fas fa-redo mr-1"></i>
                        Reset
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- TABEL REKAP -->
    <div class="rekap-card">

        <div class="rekap-card-header">
            <div>
                <h6 class="rekap-title">
                    <i class="fas fa-table text-primary mr-2"></i>
                    Data Absensi Siswa
                </h6>
                <div class="rekap-subtitle">
                    Menampilkan data sesuai filter yang dipilih.
                </div>
            </div>

            <div class="result-count">
                Total data:
                <strong><?= count($rekap); ?></strong> absensi
            </div>
        </div>

        <div class="rekap-card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Tanggal</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Nama siswa</th>
                            <th>Kelas</th>
                            <th>Waktu scan</th>
                            <th>Metode</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($rekap)): ?>
                            <?php $no = 1; ?>

                            <?php foreach ($rekap as $row): ?>
                                <?php
                                $status = isset($row->status)
                                    ? strtolower($row->status)
                                    : '';

                                $nama_kelas = isset($row->nama_kelas)
                                    && $row->nama_kelas !== ''
                                    ? $row->nama_kelas
                                    : '-';

                                $nis = isset($row->nis) ? $row->nis : '-';
                                $nisn = isset($row->nisn) ? $row->nisn : '-';
                                $nama_siswa = isset($row->nama_siswa)
                                    ? $row->nama_siswa : '-';

                                $waktu_absen = isset($row->waktu_absen)
                                    ? $row->waktu_absen : '-';

                                $metode = isset($row->metode_absen)
                                    ? strtoupper($row->metode_absen) : '-';

                                if ($status === 'hadir') {
                                    $status_class = 'status-hadir';
                                    $status_label = 'Hadir';
                                } elseif ($status === 'terlambat') {
                                    $status_class = 'status-terlambat';
                                    $status_label = 'Terlambat';
                                } else {
                                    $status_class = 'status-lain';
                                    $status_label = $status !== ''
                                        ? ucfirst($status) : 'Tidak diketahui';
                                }
                                ?>

                                <tr>
                                    <td class="text-center">
                                        <?= $no++; ?>
                                    </td>

                                    <td>
                                        <?= html_escape(
                                            isset($row->tanggal)
                                                ? $row->tanggal : '-'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= html_escape($nis); ?>
                                    </td>

                                    <td>
                                        <span class="nisn">
                                            <?= html_escape($nisn); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="student-name">
                                            <?= html_escape($nama_siswa); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?= html_escape($nama_kelas); ?>
                                    </td>

                                    <td>
                                        <?= html_escape($waktu_absen); ?>
                                    </td>

                                    <td>
                                        <span class="method-badge">
                                            <?= html_escape($metode); ?>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <span class="status-badge <?= $status_class; ?>">
                                            <?= html_escape($status_label); ?>
                                        </span>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="9" class="empty-state">
                                    <i class="fas fa-clipboard-list"></i>
                                    <strong>Belum ada data absensi</strong>
                                    <div class="mt-2">
                                        Coba ubah filter tanggal, kelas, atau pencarian siswa.
                                    </div>
                                </td>
                            </tr>

                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
