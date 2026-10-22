<?php

namespace App\Controllers;

use App\Models\NilaiRaporModel;

class Nilairapor_siswa extends BaseController
{
    protected NilaiRaporModel $nilaiModel;

    public function __construct()
    {
        $this->nilaiModel = new NilaiRaporModel();
    }

    public function index()
    {
        $siswaId = $this->session->get('siswa_id');
        if (!$siswaId) {
            return redirect()->to(site_url('siswa-login'));
        }

        $data = [
            'active' => 'nilai_rapor',
            'title'  => 'Nilai Rapor Siswa',
            'nilai'  => $this->nilaiModel->rekapBySiswaKelas((int) $siswaId),
        ];

        return $this->compatView->render('siswa/layout/header', $data)
            . $this->compatView->render('siswa/layout/sidebar', $data)
            . $this->compatView->render('siswa/nilai_rapor/index', $data)
            . $this->compatView->render('siswa/layout/footer');
    }
}
