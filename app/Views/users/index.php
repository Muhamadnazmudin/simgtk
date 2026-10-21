<div class="container-fluid users-page">

    <?php
    /* ==========================================================
       HELPER
    ========================================================== */
    $total_users = count($users);
    $total_roles = count($roles);

    // Hitung jumlah user berdasarkan role
    $role_counts = [];

    foreach ($users as $u) {
        $role_key = strtolower(trim($u->role_name));

        if (!isset($role_counts[$role_key])) {
            $role_counts[$role_key] = 0;
        }

        $role_counts[$role_key]++;
    }

    // Warna badge berdasarkan role
    function user_role_badge($role)
    {
        $role = strtolower(trim($role));

        switch ($role) {
            case 'admin':
                return 'badge-admin';

            case 'guru':
                return 'badge-guru';

            case 'operator':
                return 'badge-operator';

            case 'kesiswaan':
                return 'badge-kesiswaan';

            case 'bpbbk':
            case 'bp/bk':
            case 'bk':
                return 'badge-bk';

            default:
                return 'badge-default';
        }
    }

    // Nama role agar lebih enak dibaca
    function user_role_label($role)
    {
        $role = strtolower(trim($role));

        switch ($role) {
            case 'bpbbk':
                return 'BP/BK';

            case 'admin':
                return 'Admin';

            case 'guru':
                return 'Guru';

            case 'operator':
                return 'Operator';

            case 'kesiswaan':
                return 'Kesiswaan';

            default:
                return ucfirst($role);
        }
    }
    ?>

    <style>
        /* ==========================================================
           USERS PAGE
        ========================================================== */

        .users-page {
            padding-bottom: 30px;
        }

        /* HEADER */
        .users-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 22px;
        }

        .users-title {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .users-title-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(78, 115, 223, .15);
            color: #4e73df;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .users-title h1 {
            font-size: 1.45rem;
            font-weight: 700;
            margin: 0;
        }

        .users-title p {
            margin: 3px 0 0;
            font-size: .82rem;
            opacity: .65;
        }

        .btn-add-user {
            border-radius: 10px;
            padding: 9px 16px;
            font-weight: 600;
            box-shadow: 0 5px 14px rgba(78, 115, 223, .22);
        }

        /* ==========================================================
           STAT CARD
        ========================================================== */

        .user-stat {
            border: 0;
            border-radius: 15px;
            padding: 17px 20px;
            height: 100%;
            background: var(--users-card-bg, #fff);
            box-shadow: 0 5px 20px rgba(0,0,0,.05);
            border: 1px solid rgba(0,0,0,.05);
        }

        .user-stat-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-stat-label {
            font-size: .76rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            font-weight: 700;
            opacity: .6;
        }

        .user-stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-top: 3px;
        }

        .user-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            background: rgba(78,115,223,.12);
            color: #4e73df;
        }

        /* ==========================================================
           MAIN CARD
        ========================================================== */

        .users-card {
            margin-top: 22px;
            border: 0;
            border-radius: 16px;
            overflow: hidden;
            background: var(--users-card-bg, #fff);
            box-shadow: 0 6px 24px rgba(0,0,0,.06);
            border: 1px solid rgba(0,0,0,.05);
        }

        /* ==========================================================
           FLASH MESSAGE
        ========================================================== */

        .users-alert {
            border: 0;
            border-radius: 10px;
            font-size: .86rem;
        }

        /* ==========================================================
           TOOLBAR
        ========================================================== */

        .users-toolbar {
            padding: 18px 20px 0;
        }

        .users-search {
            position: relative;
            max-width: 350px;
        }

        .users-search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            opacity: .5;
        }

        .users-search input {
            padding-left: 39px;
            border-radius: 10px;
            height: 40px;
        }

        /* ==========================================================
           ROLE TABS
        ========================================================== */

        .role-tabs {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
            padding: 18px 20px 15px;
            border-bottom: 1px solid rgba(127,127,127,.18);
        }

        .role-tab {
            border: 1px solid rgba(127,127,127,.22);
            background: transparent;
            border-radius: 9px;
            padding: 7px 12px;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .18s ease;
            color: inherit;
        }

        .role-tab:hover {
            border-color: #4e73df;
            color: #4e73df;
        }

        .role-tab.active {
            background: #4e73df;
            color: #fff;
            border-color: #4e73df;
            box-shadow: 0 4px 10px rgba(78,115,223,.2);
        }

        .role-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 21px;
            height: 21px;
            padding: 0 5px;
            margin-left: 4px;
            border-radius: 20px;
            background: rgba(127,127,127,.13);
            font-size: .68rem;
        }

        .role-tab.active .role-count {
            background: rgba(255,255,255,.2);
            color: #fff;
        }

        /* ==========================================================
           TABLE
        ========================================================== */

        .users-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .users-table {
            margin: 0;
            min-width: 980px;
        }

        .users-table thead th {
            border-top: 0;
            border-bottom: 1px solid rgba(127,127,127,.2);
            padding: 13px 15px;
            font-size: .73rem;
            text-transform: uppercase;
            letter-spacing: .35px;
            font-weight: 800;
            white-space: nowrap;
        }

        .users-table tbody td {
            padding: 14px 15px;
            vertical-align: middle;
            border-color: rgba(127,127,127,.15);
            font-size: .84rem;
        }

        .users-table tbody tr {
            transition: background .15s ease;
        }

        .users-table tbody tr:hover {
            background: rgba(78,115,223,.045);
        }

        .user-number {
            color: #858796;
            font-weight: 700;
            width: 45px;
        }

        /* USER INFO */

        .user-main {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 190px;
        }

        .user-avatar {
            width: 37px;
            height: 37px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(78,115,223,.13);
            color: #4e73df;
            font-weight: 800;
            font-size: .78rem;
            flex-shrink: 0;
        }

        .user-name {
            font-weight: 700;
            line-height: 1.2;
        }

        .user-username {
            font-size: .72rem;
            opacity: .55;
            margin-top: 3px;
        }

        .user-email {
            font-size: .8rem;
            opacity: .75;
        }

        /* ==========================================================
           ROLE BADGE
        ========================================================== */

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 7px;
            padding: 5px 9px;
            font-size: .71rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-admin {
            background: rgba(231,74,59,.12);
            color: #e74a3b;
        }

        .badge-guru {
            background: rgba(78,115,223,.12);
            color: #4e73df;
        }

        .badge-operator {
            background: rgba(28,200,138,.12);
            color: #1cc88a;
        }

        .badge-kesiswaan {
            background: rgba(246,194,62,.15);
            color: #c99400;
        }

        .badge-bk {
            background: rgba(111,66,193,.13);
            color: #6f42c1;
        }

        .badge-default {
            background: rgba(127,127,127,.13);
            color: inherit;
        }

        /* ==========================================================
           LAST LOGIN
        ========================================================== */

        .last-login {
            white-space: nowrap;
        }

        .last-login-date {
            font-weight: 700;
            font-size: .79rem;
        }

        .last-login-time {
            font-size: .71rem;
            opacity: .58;
            margin-top: 2px;
        }

        .never-login {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: .73rem;
            opacity: .55;
        }

        /* ==========================================================
           ACTION
        ========================================================== */

        .user-actions {
            white-space: nowrap;
        }

        .btn-user-action {
            width: 34px;
            height: 34px;
            padding: 0;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: 3px;
        }

        /* ==========================================================
           EMPTY
        ========================================================== */

        .users-empty {
            padding: 45px 20px !important;
            text-align: center;
            opacity: .6;
        }

        .users-empty i {
            font-size: 34px;
            margin-bottom: 10px;
        }

        /* ==========================================================
           MODAL
        ========================================================== */

        .users-modal .modal-content {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 45px rgba(0,0,0,.2);
        }

        .users-modal .modal-header {
            border: 0;
            padding: 18px 20px;
        }

        .users-modal .modal-title {
            font-weight: 700;
        }

        .users-modal .modal-body {
            padding: 20px;
        }

        .users-modal .modal-footer {
            border-top: 1px solid rgba(127,127,127,.15);
            padding: 14px 20px;
        }

        .users-modal .form-control,
        .users-modal .custom-select {
            border-radius: 9px;
            min-height: 40px;
        }

        .users-modal label {
            font-size: .78rem;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .modal-section-title {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            font-weight: 800;
            opacity: .55;
            margin-bottom: 12px;
        }

        /* ==========================================================
           DARK MODE
           Mengikuti class dark mode umum yang dipakai aplikasi
        ========================================================== */

        body.dark-mode .users-page,
        body.dark .users-page {
            --users-card-bg: #25263a;
        }

        body.dark-mode .users-card,
        body.dark-mode .user-stat,
        body.dark .users-card,
        body.dark .user-stat {
            background: #25263a;
            border-color: rgba(255,255,255,.06);
            color: #f1f1f1;
        }

        body.dark-mode .users-table,
        body.dark .users-table {
            color: #eee;
        }

        body.dark-mode .users-table thead th,
        body.dark .users-table thead th {
            background: #303148;
        }

        body.dark-mode .users-table tbody tr:hover,
        body.dark .users-table tbody tr:hover {
            background: rgba(255,255,255,.035);
        }

        body.dark-mode .users-search input,
        body.dark .users-search input,
        body.dark-mode .users-modal .form-control,
        body.dark .users-modal .form-control,
        body.dark-mode .users-modal .custom-select,
        body.dark .users-modal .custom-select {
            background: #303148;
            border-color: rgba(255,255,255,.12);
            color: #fff;
        }

        body.dark-mode .users-modal .modal-content,
        body.dark .users-modal .modal-content {
            background: #25263a;
            color: #eee;
        }

        body.dark-mode .users-modal .modal-header,
        body.dark .users-modal .modal-header {
            background: #303148 !important;
        }

        body.dark-mode .users-modal .close,
        body.dark .users-modal .close {
            color: #fff;
        }

        /* ==========================================================
           RESPONSIVE
        ========================================================== */

        @media (max-width: 768px) {

            .users-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .btn-add-user {
                width: 100%;
            }

            .users-search {
                max-width: none;
                width: 100%;
            }

            .users-toolbar {
                padding: 15px;
            }

            .role-tabs {
                padding: 15px;
            }

            .role-tab {
                flex: 0 0 auto;
            }
        }
    </style>


    <!-- ==========================================================
         HEADER
    ========================================================== -->

    <div class="users-header">

        <div class="users-title">

            <div class="users-title-icon">
                <i class="fas fa-users-cog"></i>
            </div>

            <div>
                <h1>Manajemen User</h1>
                <p>Kelola akun pengguna dan pantau aktivitas login.</p>
            </div>

        </div>

        <button
            class="btn btn-primary btn-add-user"
            data-toggle="modal"
            data-target="#addModal">

            <i class="fas fa-plus mr-1"></i>
            Tambah User

        </button>

    </div>


    <!-- ==========================================================
         FLASH MESSAGE
    ========================================================== -->

    <?php if ($this->session->flashdata('error')): ?>

        <div class="alert alert-danger users-alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?= $this->session->flashdata('error') ?>
        </div>

    <?php elseif ($this->session->flashdata('success')): ?>

        <div class="alert alert-success users-alert">
            <i class="fas fa-check-circle mr-2"></i>
            <?= $this->session->flashdata('success') ?>
        </div>

    <?php endif; ?>


    <!-- ==========================================================
         STATISTIK
    ========================================================== -->

    <div class="row">

        <div class="col-md-6 col-xl-3 mb-3">

            <div class="user-stat">

                <div class="user-stat-content">

                    <div>
                        <div class="user-stat-label">
                            Total User
                        </div>

                        <div class="user-stat-value">
                            <?= $total_users ?>
                        </div>
                    </div>

                    <div class="user-stat-icon">
                        <i class="fas fa-users"></i>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl-3 mb-3">

            <div class="user-stat">

                <div class="user-stat-content">

                    <div>
                        <div class="user-stat-label">
                            Total Role
                        </div>

                        <div class="user-stat-value">
                            <?= $total_roles ?>
                        </div>
                    </div>

                    <div class="user-stat-icon">
                        <i class="fas fa-user-tag"></i>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl-3 mb-3">

            <div class="user-stat">

                <div class="user-stat-content">

                    <div>
                        <div class="user-stat-label">
                            Sudah Login
                        </div>

                        <div class="user-stat-value">

                            <?php
                            $sudah_login = 0;

                            foreach ($users as $u) {
                                if (!empty($u->last_login)) {
                                    $sudah_login++;
                                }
                            }

                            echo $sudah_login;
                            ?>

                        </div>
                    </div>

                    <div class="user-stat-icon">
                        <i class="fas fa-sign-in-alt"></i>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl-3 mb-3">

            <div class="user-stat">

                <div class="user-stat-content">

                    <div>
                        <div class="user-stat-label">
                            Belum Login
                        </div>

                        <div class="user-stat-value">

                            <?= $total_users - $sudah_login ?>

                        </div>

                    </div>

                    <div class="user-stat-icon">
                        <i class="fas fa-user-clock"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ==========================================================
         MAIN CARD
    ========================================================== -->

    <div class="users-card">


        <!-- SEARCH -->

        <div class="users-toolbar">

            <div class="users-search">

                <i class="fas fa-search"></i>

                <input
                    type="text"
                    id="userSearch"
                    class="form-control"
                    placeholder="Cari username, nama, email...">

            </div>

        </div>


        <!-- ======================================================
             ROLE TABS
        ====================================================== -->

        <div class="role-tabs">

            <!-- SEMUA -->

            <button
                type="button"
                class="role-tab active"
                data-role="all">

                Semua

                <span class="role-count">
                    <?= $total_users ?>
                </span>

            </button>


            <!-- ROLE DARI DATABASE -->

            <?php foreach ($roles as $r): ?>

                <?php
                $role_key   = strtolower(trim($r->name));
                $role_label = user_role_label($r->name);
                $count      = isset($role_counts[$role_key])
                    ? $role_counts[$role_key]
                    : 0;
                ?>

                <button
                    type="button"
                    class="role-tab"
                    data-role="<?= htmlspecialchars($role_key, ENT_QUOTES, 'UTF-8') ?>">

                    <?= htmlspecialchars($role_label, ENT_QUOTES, 'UTF-8') ?>

                    <span class="role-count">
                        <?= $count ?>
                    </span>

                </button>

            <?php endforeach; ?>

        </div>


        <!-- ======================================================
             TABLE
        ====================================================== -->

        <div class="users-table-wrap">

            <table class="table users-table">

                <thead>

                    <tr>

                        <th width="50">No</th>

                        <th>User</th>

                        <th>Email</th>

                        <th>Role</th>

                        <th>Login Terakhir</th>

                        <th class="text-center" width="120">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody id="usersTableBody">

                    <?php if (!empty($users)): ?>

                        <?php $no = 1; ?>

                        <?php foreach ($users as $u): ?>

                            <?php
                            $role_key = strtolower(trim($u->role_name));
                            $role_label = user_role_label($u->role_name);
                            $badge_class = user_role_badge($u->role_name);

                            // Avatar inisial
                            $nama_parts = preg_split('/\s+/', trim($u->nama));
                            $initial = '';

                            if (!empty($nama_parts[0])) {
                                $initial .= strtoupper(substr($nama_parts[0], 0, 1));
                            }

                            if (count($nama_parts) > 1) {
                                $last = end($nama_parts);
                                $initial .= strtoupper(substr($last, 0, 1));
                            }

                            $search_text = strtolower(
                                $u->username . ' ' .
                                $u->nama . ' ' .
                                $u->email . ' ' .
                                $role_label
                            );
                            ?>

                            <tr
                                class="user-row"
                                data-role="<?= htmlspecialchars($role_key, ENT_QUOTES, 'UTF-8') ?>"
                                data-search="<?= htmlspecialchars($search_text, ENT_QUOTES, 'UTF-8') ?>">

                                <!-- NO -->

                                <td class="user-number">
                                    <?= $no++ ?>
                                </td>


                                <!-- USER -->

                                <td>

                                    <div class="user-main">

                                        <div class="user-avatar">
                                            <?= htmlspecialchars($initial, ENT_QUOTES, 'UTF-8') ?>
                                        </div>

                                        <div>

                                            <div class="user-name">
                                                <?= htmlspecialchars($u->nama, ENT_QUOTES, 'UTF-8') ?>
                                            </div>

                                            <div class="user-username">
                                                @<?= htmlspecialchars($u->username, ENT_QUOTES, 'UTF-8') ?>
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                <!-- EMAIL -->

                                <td>

                                    <?php if (!empty($u->email)): ?>

                                        <span class="user-email">
                                            <?= htmlspecialchars($u->email, ENT_QUOTES, 'UTF-8') ?>
                                        </span>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            -
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- ROLE -->

                                <td>

                                    <span class="role-badge <?= $badge_class ?>">

                                        <i class="fas fa-user-shield"></i>

                                        <?= htmlspecialchars($role_label, ENT_QUOTES, 'UTF-8') ?>

                                    </span>

                                </td>


                                <!-- LAST LOGIN -->

                                <td>

                                    <?php if (!empty($u->last_login)): ?>

                                        <div class="last-login">

                                            <div class="last-login-date">

                                                <?= date(
                                                    'd F Y',
                                                    strtotime($u->last_login)
                                                ) ?>

                                            </div>

                                            <div class="last-login-time">

                                                <i class="far fa-clock mr-1"></i>

                                                <?= date(
                                                    'H:i:s',
                                                    strtotime($u->last_login)
                                                ) ?>

                                            </div>

                                        </div>

                                    <?php else: ?>

                                        <span class="never-login">

                                            <i class="far fa-clock"></i>

                                            Belum pernah login

                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- ACTION -->

                                <td class="text-center user-actions">

                                    <button
                                        type="button"
                                        class="btn btn-warning btn-user-action"
                                        data-toggle="modal"
                                        data-target="#editModal<?= $u->id ?>"
                                        title="Edit User">

                                        <i class="fas fa-edit"></i>

                                    </button>


                                    <a
                                        href="<?= site_url('users/delete/'.$u->id) ?>"
                                        onclick="return confirm('Yakin ingin menghapus user <?= htmlspecialchars($u->nama, ENT_QUOTES, 'UTF-8') ?>?')"
                                        class="btn btn-danger btn-user-action"
                                        title="Hapus User">

                                        <i class="fas fa-trash"></i>

                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>


                    <?php else: ?>

                        <tr>

                            <td colspan="6" class="users-empty">

                                <i class="fas fa-users-slash d-block"></i>

                                Belum ada data user.

                            </td>

                        </tr>

                    <?php endif; ?>


                    <!-- HASIL PENCARIAN KOSONG -->

                    <tr id="emptySearchRow" style="display:none;">

                        <td colspan="6" class="users-empty">

                            <i class="fas fa-search d-block"></i>

                            Tidak ada user yang ditemukan.

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- ==============================================================
     MODAL EDIT USER
     Diletakkan di luar table agar HTML lebih valid
================================================================ -->

<?php foreach ($users as $u): ?>

    <div
        class="modal fade users-modal"
        id="editModal<?= $u->id ?>"
        tabindex="-1"
        role="dialog"
        aria-hidden="true">

        <div
            class="modal-dialog modal-dialog-centered"
            role="document">

            <div class="modal-content">

                <form
                    method="post"
                    action="<?= site_url('users/edit/'.$u->id) ?>">

                    <div class="modal-header bg-warning">

                        <div>

                            <h5 class="modal-title">
                                <i class="fas fa-user-edit mr-2"></i>
                                Edit User
                            </h5>

                            <small class="d-block mt-1">
                                @<?= htmlspecialchars($u->username, ENT_QUOTES, 'UTF-8') ?>
                            </small>

                        </div>

                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal">

                            <span>&times;</span>

                        </button>

                    </div>


                    <div class="modal-body">

                        <input
                            type="hidden"
                            name="<?= $this->security->get_csrf_token_name(); ?>"
                            value="<?= $this->security->get_csrf_hash(); ?>">


                        <div class="modal-section-title">
                            Informasi User
                        </div>


                        <div class="form-group">

                            <label>
                                <i class="fas fa-user mr-1"></i>
                                Nama
                            </label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                value="<?= htmlspecialchars($u->nama, ENT_QUOTES, 'UTF-8') ?>"
                                required>

                        </div>


                        <div class="form-group">

                            <label>
                                <i class="fas fa-envelope mr-1"></i>
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars($u->email, ENT_QUOTES, 'UTF-8') ?>">

                        </div>


                        <div class="form-group">

                            <label>
                                <i class="fas fa-user-tag mr-1"></i>
                                Role
                            </label>

                            <select
                                name="role_id"
                                class="custom-select"
                                required>

                                <?php foreach ($roles as $r): ?>

                                    <option
                                        value="<?= $r->id ?>"
                                        <?= $r->id == $u->role_id ? 'selected' : '' ?>>

                                        <?= htmlspecialchars(
                                            user_role_label($r->name),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <hr>


                        <div class="modal-section-title">
                            Keamanan
                        </div>


                        <div class="form-group mb-0">

                            <label>
                                <i class="fas fa-lock mr-1"></i>
                                Password Baru
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Kosongkan jika tidak diubah">

                            <small class="form-text text-muted">
                                Password lama tetap digunakan jika bagian ini dikosongkan.
                            </small>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-light"
                            data-dismiss="modal">

                            Batal

                        </button>

                        <button
                            type="submit"
                            class="btn btn-warning">

                            <i class="fas fa-save mr-1"></i>
                            Simpan Perubahan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

<?php endforeach; ?>


<!-- ==============================================================
     MODAL TAMBAH USER
================================================================ -->

<div
    class="modal fade users-modal"
    id="addModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">

    <div
        class="modal-dialog modal-dialog-centered"
        role="document">

        <div class="modal-content">

            <form
                method="post"
                action="<?= site_url('users/add') ?>">

                <div class="modal-header bg-primary text-white">

                    <div>

                        <h5 class="modal-title">

                            <i class="fas fa-user-plus mr-2"></i>
                            Tambah User

                        </h5>

                        <small class="d-block mt-1 opacity-75">
                            Buat akun pengguna baru
                        </small>

                    </div>

                    <button
                        type="button"
                        class="close text-white"
                        data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>


                <div class="modal-body">

                    <input
                        type="hidden"
                        name="<?= $this->security->get_csrf_token_name(); ?>"
                        value="<?= $this->security->get_csrf_hash(); ?>">


                    <div class="modal-section-title">
                        Informasi Akun
                    </div>


                    <div class="form-group">

                        <label>
                            <i class="fas fa-at mr-1"></i>
                            Username
                        </label>

                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            placeholder="Masukkan username"
                            required>

                    </div>


                    <div class="form-group">

                        <label>
                            <i class="fas fa-user mr-1"></i>
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            placeholder="Nama pengguna"
                            required>

                    </div>


                    <div class="form-group">

                        <label>
                            <i class="fas fa-envelope mr-1"></i>
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="nama@email.com">

                    </div>


                    <div class="form-group">

                        <label>
                            <i class="fas fa-user-tag mr-1"></i>
                            Role
                        </label>

                        <select
                            name="role_id"
                            class="custom-select"
                            required>

                            <option value="">
                                -- Pilih Role --
                            </option>

                            <?php foreach ($roles as $r): ?>

                                <option value="<?= $r->id ?>">

                                    <?= htmlspecialchars(
                                        user_role_label($r->name),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <hr>


                    <div class="modal-section-title">
                        Keamanan
                    </div>


                    <div class="form-group mb-0">

                        <label>
                            <i class="fas fa-lock mr-1"></i>
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Minimal 6 karakter"
                            required>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="fas fa-save mr-1"></i>
                        Simpan User

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- ==============================================================
     FILTER + SEARCH JAVASCRIPT
================================================================ -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    let activeRole = 'all';

    const searchInput = document.getElementById('userSearch');
    const roleTabs = document.querySelectorAll('.role-tab');
    const userRows = document.querySelectorAll('.user-row');
    const emptyRow = document.getElementById('emptySearchRow');


    /* ==========================================================
       FILTER USER
    ========================================================== */

    function filterUsers() {

        const keyword = searchInput
            ? searchInput.value.toLowerCase().trim()
            : '';

        let visible = 0;

        userRows.forEach(function (row) {

            const rowRole =
                (row.getAttribute('data-role') || '').toLowerCase();

            const rowSearch =
                (row.getAttribute('data-search') || '').toLowerCase();

            const roleMatch =
                activeRole === 'all' ||
                rowRole === activeRole;

            const searchMatch =
                keyword === '' ||
                rowSearch.indexOf(keyword) !== -1;

            if (roleMatch && searchMatch) {

                row.style.display = '';

                visible++;

            } else {

                row.style.display = 'none';

            }

        });


        /* ======================================================
           EMPTY RESULT
        ====================================================== */

        if (emptyRow) {

            emptyRow.style.display =
                visible === 0 ? '' : 'none';

        }

    }


    /* ==========================================================
       ROLE TAB
    ========================================================== */

    roleTabs.forEach(function (tab) {

        tab.addEventListener('click', function (e) {

            e.preventDefault();

            /* Ambil role */

            activeRole =
                (this.getAttribute('data-role') || 'all')
                .toLowerCase();


            /* Hapus active dari semua tab */

            roleTabs.forEach(function (item) {

                item.classList.remove('active');

            });


            /* Aktifkan tab yang diklik */

            this.classList.add('active');


            /* Filter */

            filterUsers();

        });

    });


    /* ==========================================================
       SEARCH
    ========================================================== */

    if (searchInput) {

        searchInput.addEventListener('input', function () {

            filterUsers();

        });

    }


    /* ==========================================================
       INITIAL FILTER
    ========================================================== */

    filterUsers();

});
</script>