<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">

    <!-- =========================================================
         HEADER
    ========================================================== -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>

            <h1 class="h3 mb-1 text-gray-800">
                Tambah Tendik
            </h1>

            <div class="small text-muted">
                Tambahkan data tenaga kependidikan
            </div>

        </div>


        <a href="<?= site_url('tendik') ?>"
           class="btn btn-secondary btn-sm">

            <i class="fas fa-arrow-left mr-1"></i>
            Kembali

        </a>

    </div>


    <!-- =========================================================
         ERROR
    ========================================================== -->

    <?php if (validation_errors()): ?>

        <div class="alert alert-danger">

            <i class="fas fa-exclamation-circle mr-1"></i>

            <?= validation_errors() ?>

        </div>

    <?php endif; ?>


    <?php if (!empty($error)): ?>

        <div class="alert alert-danger">

            <i class="fas fa-exclamation-circle mr-1"></i>

            <?= html_escape($error) ?>

        </div>

    <?php endif; ?>


    <!-- =========================================================
         FORM
    ========================================================== -->

    <form method="post"
      action="<?= site_url('tendik/add') ?>">

    <input type="hidden"
           name="<?= $this->security->get_csrf_token_name() ?>"
           value="<?= $this->security->get_csrf_hash() ?>">

        <?php $this->load->view('tendik/_form'); ?>


        <!-- =====================================================
             BUTTON
        ====================================================== -->

        <div class="card shadow mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-end">

                    <a href="<?= site_url('tendik') ?>"
                       class="btn btn-secondary mr-2">

                        Batal

                    </a>


                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save mr-1"></i>

                        Simpan Data

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>