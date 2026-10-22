<?php

namespace App\Controllers\Absensi;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use DateTime;

class Laporan extends BaseController
{
    protected AbsensiModel $absensiModel;
    protected KelasModel $kelasModel;
    protected SiswaModel $siswaModel;

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
        $this->kelasModel   = new KelasModel();
        $this->siswaModel   = new SiswaModel();
    }

    public function index()
    {
        $this->requireRole([1, 2, 3, 4, 5]);

        $data = [
            'judul'   => 'Laporan Absensi Siswa',
            'active'  => 'laporan_absensi',
            'kelas'   => $this->kelasModel->orderBy('nama', 'ASC')->findAll(),
            'hasil'   => [],
            'periode' => '',
        ];

        if ($this->request->getPost('submit') === 'tampil') {
            $nama   = trim((string) $this->request->getPost('nama'));
            $kelas  = $this->request->getPost('kelas');
            $dari   = $this->request->getPost('dari');
            $sampai = $this->request->getPost('sampai');
            $tahun  = $this->request->getPost('tahun');
            $ket    = $this->request->getPost('keterangan');

            $data['hasil']   = $this->absensiModel->laporanFilter($nama, $kelas, $dari, $sampai, $tahun, $ket);
            $data['periode'] = $dari . ' s/d ' . $sampai;
        }

        return $this->renderView('absensi/laporan', $data);
    }
}
