<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>

.tka-edit-page {
    padding-bottom: 30px;
}

.tka-edit-card {
    max-width: 850px;
    margin: 0 auto;
    border: 0;
    border-radius: 22px;
    box-shadow: 0 10px 30px rgba(15,23,42,.07);
    overflow: hidden;
}

.tka-edit-header {
    padding: 25px 28px;
    background: linear-gradient(
        135deg,
        #4f9cf9,
        #3b82f6
    );
    color: #fff;
}

.tka-edit-header h4 {
    margin: 0;
    font-weight: 700;
}

.tka-edit-header p {
    margin: 6px 0 0;
    font-size: .85rem;
    opacity: .88;
}

.tka-edit-body {
    padding: 28px;
}

.tka-student-box {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr;
    gap: 12px;
    margin-bottom: 25px;
}

.tka-student-item {
    padding: 15px;
    border-radius: 13px;
    background: #f8fafc;
    border: 1px solid #e5eaf1;
}

.tka-student-label {
    display: block;
    color: #6b7280;
    font-size: .72rem;
    margin-bottom: 4px;
}

.tka-student-value {
    color: #1f2937;
    font-weight: 700;
    font-size: .88rem;
}

.tka-edit-label {
    margin-bottom: 8px;
    color: #374151;
    font-weight: 700;
    font-size: .86rem;
}

.tka-edit-select {
    min-height: 48px;
    border-radius: 12px;
}

.tka-warning {
    padding: 15px 17px;
    margin-bottom: 22px;
    border-radius: 13px;
    background: #fff7ed;
    border: 1px solid #fed7aa;
    color: #9a3412;
    font-size: .83rem;
    line-height: 1.5;
}

.tka-edit-actions {
    display: flex;
    gap: 10px;
    margin-top: 25px;
}

.tka-edit-actions .btn {
    border-radius: 11px;
    font-weight: 600;
}


/* DARK */

body.dark-mode .tka-edit-card {
    background: #202333;
    color: #e5e7eb;
}

body.dark-mode .tka-edit-body {
    background: #202333;
}

body.dark-mode .tka-student-item {
    background: #292d40;
    border-color: #3c4255;
}

body.dark-mode .tka-student-label {
    color: #9ca7ba;
}

body.dark-mode .tka-student-value {
    color: #f3f4f6;
}

body.dark-mode .tka-edit-label {
    color: #f1f5f9;
}

body.dark-mode .tka-edit-select {
    background: #292d40;
    color: #fff;
    border-color: #454b61;
}

body.dark-mode .tka-edit-select option {
    background: #292d40;
    color: #fff;
}

body.dark-mode .tka-warning {
    background: #4a3224;
    border-color: #704a30;
    color: #ffbd8a;
}


/* MOBILE */

@media (max-width: 768px) {

    .tka-edit-body {
        padding: 20px;
    }

    .tka-student-box {
        grid-template-columns: 1fr;
    }

    .tka-edit-actions {
        flex-direction: column;
    }

    .tka-edit-actions .btn {
        width: 100%;
    }

}

</style>


<div class="container-fluid tka-edit-page">

    <div class="card tka-edit-card">

        <div class="tka-edit-header">

            <h4>
                <i class="fas fa-user-edit mr-2"></i>
                Edit Pilihan TKA
            </h4>

            <p>
                Perubahan pilihan hanya dapat dilakukan oleh Admin
                atau Kesiswaan.
            </p>

        </div>


        <div class="tka-edit-body">


            <!-- DATA SISWA -->

            <div class="tka-student-box">

                <div class="tka-student-item">

                    <span class="tka-student-label">
                        Nama Siswa
                    </span>

                    <span class="tka-student-value">

                        <?= html_escape(
                            $tka->nama
                        ); ?>

                    </span>

                </div>


                <div class="tka-student-item">

                    <span class="tka-student-label">
                        Kelas
                    </span>

                    <span class="tka-student-value">

                        <?= html_escape(
                            $tka->nama_kelas
                        ); ?>

                    </span>

                </div>


                <div class="tka-student-item">

                    <span class="tka-student-label">
                        Tahun Ajaran
                    </span>

                    <span class="tka-student-value">

                        <?= html_escape(
                            $tka->tahun_ajaran
                        ); ?>

                    </span>

                </div>

            </div>


            <!-- WARNING -->

            <div class="tka-warning">

                <i class="fas fa-exclamation-triangle mr-2"></i>

                Data pilihan TKA siswa sudah terkunci.
                Perubahan dari halaman ini akan mengganti
                pilihan siswa yang tersimpan.

            </div>


            <!-- FORM -->

            <form
                method="post"
                action="<?= site_url('Tka_admin/update'); ?>"
            >

                <input
                    type="hidden"
                    name="<?= $this->security->get_csrf_token_name(); ?>"
                    value="<?= $this->security->get_csrf_hash(); ?>"
                >

                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $tka->id; ?>"
                >


                <!-- PILIHAN 1 -->

                <div class="form-group">

                    <label class="tka-edit-label">

                        Mata Pelajaran Pilihan 1

                    </label>

                    <select
                        name="mapel_1_id"
                        class="form-control tka-edit-select"
                        required
                    >

                        <option value="">
                            -- Pilih Mata Pelajaran --
                        </option>

                        <?php foreach ($mapel as $m): ?>

                            <option
                                value="<?= (int) $m->id; ?>"
                                <?= (
                                    $tka->mapel_1_id == $m->id
                                )
                                    ? 'selected'
                                    : ''; ?>
                            >

                                <?= html_escape(
                                    $m->nama_mapel
                                ); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- PILIHAN 2 -->

                <div class="form-group">

                    <label class="tka-edit-label">

                        Mata Pelajaran Pilihan 2

                    </label>

                    <select
                        name="mapel_2_id"
                        class="form-control tka-edit-select"
                        required
                    >

                        <option value="">
                            -- Pilih Mata Pelajaran --
                        </option>

                        <?php foreach ($mapel as $m): ?>

                            <option
                                value="<?= (int) $m->id; ?>"
                                <?= (
                                    $tka->mapel_2_id == $m->id
                                )
                                    ? 'selected'
                                    : ''; ?>
                            >

                                <?= html_escape(
                                    $m->nama_mapel
                                ); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- ACTION -->

                <div class="tka-edit-actions">

                    <a
                        href="<?= site_url('Tka_admin'); ?>"
                        class="btn btn-light"
                    >

                        <i class="fas fa-arrow-left mr-1"></i>

                        Kembali

                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="fas fa-save mr-1"></i>

                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>