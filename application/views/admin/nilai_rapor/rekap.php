<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-3">

        <h4>
            <i class="fas fa-book"></i>
            Rekap Nilai Rapor
        </h4>

        <a href="<?= site_url('Nilairapor_admin') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>

    </div>

    <!-- FILTER -->

    <div class="card shadow mb-3">

        <div class="card-body">

            <form method="get">

                <div class="row">

                    <div class="col-md-4">

                        <label>Kelas</label>

                        <select name="kelas" class="form-control">

                            <option value="">Semua Kelas</option>

                            <?php foreach($kelas as $k): ?>

                                <option
                                    value="<?= $k->id ?>"
                                    <?= $this->input->get('kelas')==$k->id?'selected':'' ?>
                                >
                                    <?= $k->nama ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label>&nbsp;</label>

                        <button class="btn btn-primary btn-block">

                            <i class="fas fa-filter"></i>

                            Filter

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <!-- TABEL -->

    <div class="card shadow">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-sm table-hover mb-0">

                    <thead>

                    <!-- HEADER 1 -->

                    <tr class="thead-dark text-center">

                        <th rowspan="2">No</th>

                        <th rowspan="2">NISN</th>

                        <th rowspan="2">Nama Siswa</th>

                        <th rowspan="2">Kelas</th>

                        <?php foreach($mapel as $m): ?>

                            <th colspan="6">

                                <?= $m->nama_mapel ?>

                            </th>

                        <?php endforeach; ?>

                    </tr>

                    <!-- HEADER 2 -->

                    <tr class="thead-dark text-center">

                        <?php foreach($mapel as $m): ?>

                            <?php for($smt=1;$smt<=6;$smt++): ?>

                                <th>

                                    <?= $smt ?>

                                </th>

                            <?php endfor; ?>

                        <?php endforeach; ?>

                    </tr>

                    </thead>

                    <tbody>

                    <?php if(empty($rekap)): ?>

                        <tr>

                            <td colspan="<?= (count($mapel)*6)+4 ?>" class="text-center">

                                Belum ada data.

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php

                        $no=(($page-1)*100)+1;

                        foreach($rekap as $r):

                        ?>

                        <tr>

                            <td class="text-center">

                                <?= $no++ ?>

                            </td>

                            <td>

                                <?= $r['nisn'] ?>

                            </td>

                            <td>

                                <?= $r['nama'] ?>

                            </td>

                            <td>

                                <?= $r['kelas'] ?>

                            </td>

                            <?php foreach($mapel as $m): ?>

                                <?php for($smt=1;$smt<=6;$smt++): ?>

                                    <td class="text-center">

                                        <?= isset($r['nilai'][$m->id_mapel][$smt])
                                            ? $r['nilai'][$m->id_mapel][$smt]
                                            : '-'
                                        ?>

                                    </td>

                                <?php endfor; ?>

                            <?php endforeach; ?>

                        </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <?php if(!empty($pagination)): ?>

        <div class="mt-3">

            <?= $pagination ?>

        </div>

    <?php endif; ?>

</div>