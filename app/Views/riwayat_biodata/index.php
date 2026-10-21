<div class="container-fluid">

<style>

/* =========================================================
   HEADER
========================================================= */

.riwayat-header {
    margin-bottom: 24px;
}

.riwayat-header h1 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.riwayat-header p {
    margin: 0;
    color: #858796;
}


/* =========================================================
   STAT CARD
========================================================= */

.log-stat {
    border-radius: 14px;
    border: 1px solid rgba(255,255,255,.08);
    padding: 20px;
    height: 100%;
    background: #2b2c41;
}

.log-stat-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(78,115,223,.15);
    color: #4e73df;
    font-size: 18px;
}

.log-stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    margin-top: 12px;
}

.log-stat-label {
    font-size: .82rem;
    color: #858796;
}


/* =========================================================
   FILTER
========================================================= */

.log-filter {
    border-radius: 14px;
    border: 1px solid rgba(255,255,255,.08);
    padding: 18px;
    background: #2b2c41;
    margin-bottom: 20px;
}

.log-search {
    position: relative;
}

.log-search i {
    position: absolute;
    left: 14px;
    top: 13px;
    color: #858796;
}

.log-search input {
    padding-left: 40px;
}


/* =========================================================
   TABLE CARD
========================================================= */

.log-card {
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,.08);
    background: #2b2c41;
}

.log-table {
    margin: 0;
}

.log-table thead th {
    border: 0;
    padding: 15px;
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: .3px;
    white-space: nowrap;
}

.log-table tbody td {
    padding: 15px;
    vertical-align: middle;
}

.student-name {
    font-weight: 600;
}

.student-nisn {
    font-size: .75rem;
    color: #858796;
}

.class-badge {
    display: inline-flex;
    padding: 5px 9px;
    border-radius: 8px;
    font-size: .72rem;
    font-weight: 600;
    background: rgba(78,115,223,.15);
    color: #6f8ff5;
}

.change-count {
    font-weight: 700;
}

.last-change {
    font-size: .82rem;
}

.last-change small {
    display: block;
    color: #858796;
    margin-top: 3px;
}


/* =========================================================
   DETAIL BUTTON
========================================================= */

.btn-detail {
    border-radius: 8px;
    font-size: .78rem;
    padding: 7px 12px;
}


/* =========================================================
   MODAL
========================================================= */

.modal-content {
    border: 0;
    border-radius: 16px;
}

.modal-header {
    border-bottom: 1px solid rgba(0,0,0,.08);
}

.modal-title {
    font-weight: 700;
}

.log-detail-item {
    padding: 15px 0;
    border-bottom: 1px solid rgba(128,128,128,.15);
}

.log-detail-item:last-child {
    border-bottom: 0;
}

.log-field {
    font-weight: 700;
    margin-bottom: 8px;
}

.log-values {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.log-old {
    color: #858796;
}

.log-new {
    color: #1cc88a;
    font-weight: 700;
}

.log-time {
    font-size: .75rem;
    color: #858796;
    margin-top: 7px;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 768px) {

    .log-stat {
        margin-bottom: 12px;
    }

    .log-table {
        min-width: 850px;
    }

}

</style>


<!-- =========================================================
     HEADER
========================================================= -->

<div class="riwayat-header">

    <div class="d-flex justify-content-between align-items-center">

        <div>

            <h1>
                <i class="fas fa-history text-primary mr-2"></i>
                Riwayat Perubahan Biodata
            </h1>

            <p>
                Pantau perubahan biodata yang dilakukan siswa.
            </p>

        </div>

    </div>

</div>


<!-- =========================================================
     STATISTIK
========================================================= -->

<div class="row mb-4">

    <div class="col-md-4 mb-3">

        <div class="log-stat">

            <div class="log-stat-icon">
                <i class="fas fa-user-graduate"></i>
            </div>

            <div class="log-stat-number">
                <?= number_format($total_siswa) ?>
            </div>

            <div class="log-stat-label">
                Siswa Pernah Mengubah Biodata
            </div>

        </div>

    </div>


    <div class="col-md-4 mb-3">

        <div class="log-stat">

            <div class="log-stat-icon">
                <i class="fas fa-history"></i>
            </div>

            <div class="log-stat-number">
                <?= number_format($total_log) ?>
            </div>

            <div class="log-stat-label">
                Total Data Perubahan
            </div>

        </div>

    </div>


    <div class="col-md-4 mb-3">

        <div class="log-stat">

            <div class="log-stat-icon">
                <i class="fas fa-calendar-day"></i>
            </div>

            <div class="log-stat-number">
                <?= number_format($today) ?>
            </div>

            <div class="log-stat-label">
                Perubahan Hari Ini
            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     FILTER
========================================================= -->

<div class="log-filter">

    <form method="get"
          action="<?= site_url('riwayat_biodata') ?>">

        <div class="row align-items-end">

            <!-- SEARCH -->

            <div class="col-md-6 mb-2">

                <label class="small font-weight-bold">
                    Cari Siswa
                </label>

                <div class="log-search">

                    <i class="fas fa-search"></i>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Nama siswa atau NISN..."
                        value="<?= html_escape($search) ?>"
                    >

                </div>

            </div>


            <!-- KELAS -->

            <div class="col-md-4 mb-2">

                <label class="small font-weight-bold">
                    Kelas
                </label>

                <select
                    name="kelas_id"
                    class="form-control"
                >

                    <option value="">
                        Semua Kelas
                    </option>

                    <?php foreach ($kelas as $k): ?>

                        <option
                            value="<?= $k->id ?>"
                            <?= $kelas_id == $k->id ? 'selected' : '' ?>
                        >
                            <?= html_escape($k->nama) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <!-- BUTTON -->

            <div class="col-md-2 mb-2">

                <button
                    type="submit"
                    class="btn btn-primary btn-block"
                >

                    <i class="fas fa-filter mr-1"></i>
                    Filter

                </button>

            </div>

        </div>

    </form>

</div>


<!-- =========================================================
     TABLE
========================================================= -->

<div class="log-card">

    <div class="table-responsive">

        <table class="table log-table">

            <thead>

                <tr>

                    <th width="5%">
                        No
                    </th>

                    <th>
                        Siswa
                    </th>

                    <th>
                        Kelas
                    </th>

                    <th>
                        Perubahan
                    </th>

                    <th>
                        Terakhir Diubah
                    </th>

                    <th width="10%"
                        class="text-center">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

            <?php if (empty($riwayat)): ?>

                <tr>

                    <td
                        colspan="6"
                        class="text-center py-5"
                    >

                        <i class="fas fa-history fa-2x text-muted mb-3"></i>

                        <div class="font-weight-bold">
                            Belum ada data perubahan
                        </div>

                        <small class="text-muted">
                            Belum ada siswa yang mengubah biodata.
                        </small>

                    </td>

                </tr>

            <?php else: ?>


                <?php

                $no =
                    (($page - 1) * $per_page) + 1;

                ?>

                <?php foreach ($riwayat as $r): ?>

                    <tr>

                        <!-- NO -->

                        <td>
                            <?= $no++ ?>
                        </td>


                        <!-- SISWA -->

                        <td>

                            <div class="student-name">

                                <?= html_escape(
                                    $r->nama_siswa
                                ) ?>

                            </div>

                            <div class="student-nisn">

                                NISN:
                                <?= html_escape(
                                    $r->nisn
                                ) ?>

                            </div>

                        </td>


                        <!-- KELAS -->

                        <td>

                            <span class="class-badge">

                                <?= html_escape(
                                    $r->nama_kelas ?: '-'
                                ) ?>

                            </span>

                        </td>


                        <!-- JUMLAH -->

                        <td>

                            <span class="change-count">

                                <?= number_format(
                                    $r->jumlah_perubahan
                                ) ?>

                            </span>

                            <span class="text-muted small">
                                field
                            </span>

                        </td>


                        <!-- TERAKHIR -->

                        <td>

                            <div class="last-change">

                                <?= date(
                                    'd-m-Y',
                                    strtotime(
                                        $r->terakhir_diubah
                                    )
                                ) ?>

                                <small>

                                    <?= date(
                                        'H:i:s',
                                        strtotime(
                                            $r->terakhir_diubah
                                        )
                                    ) ?>

                                </small>

                            </div>

                        </td>


                        <!-- DETAIL -->

                        <td class="text-center">

                            <button
                                type="button"
                                class="btn btn-primary btn-sm btn-detail"
                                onclick="lihatDetail(
                                    <?= (int) $r->siswa_id ?>,
                                    '<?= html_escape(
                                        addslashes(
                                            $r->nama_siswa
                                        )
                                    ) ?>'
                                )"
                            >

                                <i class="fas fa-eye mr-1"></i>
                                Detail

                            </button>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>

        </table>

    </div>


    <!-- =====================================================
         PAGINATION
    ====================================================== -->

    <?php if ($total_page > 1): ?>

        <div class="d-flex justify-content-between align-items-center p-3">

            <div class="small text-muted">

                Menampilkan
                <?= $total > 0
                    ? (($page - 1) * $per_page) + 1
                    : 0
                ?>

                -
                <?= min(
                    $page * $per_page,
                    $total
                ) ?>

                dari
                <?= number_format($total) ?>
                siswa

            </div>


            <nav>

                <ul class="pagination pagination-sm mb-0">

                    <?php if ($page > 1): ?>

                        <li class="page-item">

                            <a
                                class="page-link"
                                href="?<?= http_build_query([
                                    'search'   => $search,
                                    'kelas_id' => $kelas_id,
                                    'page'     => $page - 1
                                ]) ?>"
                            >

                                &laquo;

                            </a>

                        </li>

                    <?php endif; ?>


                    <?php

                    $start =
                        max(1, $page - 2);

                    $end =
                        min(
                            $total_page,
                            $page + 2
                        );

                    ?>


                    <?php for (
                        $i = $start;
                        $i <= $end;
                        $i++
                    ): ?>

                        <li class="page-item
                            <?= $i == $page
                                ? 'active'
                                : ''
                            ?>">

                            <a
                                class="page-link"
                                href="?<?= http_build_query([
                                    'search'   => $search,
                                    'kelas_id' => $kelas_id,
                                    'page'     => $i
                                ]) ?>"
                            >

                                <?= $i ?>

                            </a>

                        </li>

                    <?php endfor; ?>


                    <?php if ($page < $total_page): ?>

                        <li class="page-item">

                            <a
                                class="page-link"
                                href="?<?= http_build_query([
                                    'search'   => $search,
                                    'kelas_id' => $kelas_id,
                                    'page'     => $page + 1
                                ]) ?>"
                            >

                                &raquo;

                            </a>

                        </li>

                    <?php endif; ?>

                </ul>

            </nav>

        </div>

    <?php endif; ?>

</div>


<!-- =========================================================
     MODAL DETAIL
========================================================= -->

<div
    class="modal fade"
    id="detailModal"
    tabindex="-1"
    role="dialog"
>

    <div
        class="modal-dialog modal-lg modal-dialog-scrollable"
        role="document"
    >

        <div class="modal-content">


            <div class="modal-header">

                <div>

                    <h5
                        class="modal-title"
                        id="detailNama"
                    >
                        Riwayat Perubahan
                    </h5>

                    <small
                        class="text-muted"
                        id="detailInfo"
                    ></small>

                </div>


                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                >

                    <span>&times;</span>

                </button>

            </div>


            <div
                class="modal-body"
                id="detailBody"
            >

                <div class="text-center py-5">

                    <i class="fas fa-spinner fa-spin fa-2x"></i>

                    <div class="mt-2">
                        Memuat data...
                    </div>

                </div>

            </div>


        </div>

    </div>

</div>


<script>

function lihatDetail(siswaId, namaSiswa)
{

    // =====================================================
    // BUKA MODAL
    // =====================================================

    $('#detailNama').text(
        'Riwayat Perubahan'
    );

    $('#detailInfo').text(
        namaSiswa
    );


    $('#detailBody').html(`
        <div class="text-center py-5">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <div class="mt-3 text-muted">
                Memuat riwayat perubahan...
            </div>
        </div>
    `);


    $('#detailModal').modal('show');


    // =====================================================
    // AJAX
    // =====================================================

    $.ajax({

        url:
            '<?= site_url(
                'riwayat_biodata/detail'
            ) ?>/' + siswaId,

        type: 'GET',

        dataType: 'json',

        success: function(response)
        {

            if (
                !response.status ||
                !response.data ||
                response.data.length === 0
            ) {

                $('#detailBody').html(`
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-history fa-2x mb-3"></i>
                        <div>
                            Belum ada riwayat perubahan.
                        </div>
                    </div>
                `);

                return;
            }


            var html = '';


            // =================================================
            // DETAIL DATA
            // =================================================

            $.each(
                response.data,
                function(index, item)
                {

                    var oldValue =
                        item.old_value
                        ? escapeHtml(
                            item.old_value
                        )
                        : '-';


                    var newValue =
                        item.new_value
                        ? escapeHtml(
                            item.new_value
                        )
                        : '-';


                    var tanggal =
                        formatTanggal(
                            item.changed_at
                        );


                    html += `

                        <div class="log-detail-item">

                            <div class="log-field">

                                ${escapeHtml(
                                    item.field_label
                                )}

                            </div>


                            <div class="log-values">

                                <span class="log-old">

                                    ${oldValue}

                                </span>


                                <i class="
                                    fas
                                    fa-arrow-right
                                    text-muted
                                "></i>


                                <span class="log-new">

                                    ${newValue}

                                </span>

                            </div>


                            <div class="log-time">

                                <i class="far fa-clock mr-1"></i>

                                ${tanggal}

                            </div>

                        </div>

                    `;

                }
            );


            $('#detailBody').html(html);

        },


        error: function()
        {

            $('#detailBody').html(`

                <div class="alert alert-danger">

                    <i class="fas fa-exclamation-triangle mr-1"></i>

                    Gagal mengambil riwayat perubahan.

                </div>

            `);

        }

    });

}


// =========================================================
// ESCAPE HTML
// =========================================================

function escapeHtml(text)
{

    return $('<div>')
        .text(text)
        .html();

}


// =========================================================
// FORMAT TANGGAL
// =========================================================

function formatTanggal(value)
{

    if (!value) {
        return '-';
    }


    var date =
        new Date(
            value.replace(' ', 'T')
        );


    if (isNaN(date.getTime())) {
        return value;
    }


    var tanggal =
        String(
            date.getDate()
        ).padStart(2, '0')
        + '-'
        +
        String(
            date.getMonth() + 1
        ).padStart(2, '0')
        + '-'
        +
        date.getFullYear();


    var jam =
        String(
            date.getHours()
        ).padStart(2, '0')
        + ':'
        +
        String(
            date.getMinutes()
        ).padStart(2, '0')
        + ':'
        +
        String(
            date.getSeconds()
        ).padStart(2, '0');


    return tanggal + ' ' + jam;

}

</script>

</div>