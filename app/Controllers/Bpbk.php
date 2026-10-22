<?php

namespace App\Controllers;

use App\Models\DashboardBpbkModel;
use App\Models\TahunModel;

class Bpbk extends BaseController
{
    protected DashboardBpbkModel $bpbkModel;
    protected TahunModel $tahunModel;

    public function __construct()
    {
        $this->bpbkModel  = new DashboardBpbkModel();
        $this->tahunModel = new TahunModel();
    }

    public function index()
    {
        $this->requireRole([6, 1]);

        $summary     = $this->bpbkModel->getSummary();
        $hari        = $this->bpbkModel->getHariIni();
        $bulan       = $this->bpbkModel->getBulanIni();
        $tidak_hadir = $this->bpbkModel->getTidakHadirHariIni();
        $top_alpha   = $this->bpbkModel->getTopAlpa();

        $tahunAktif = $this->db->table('tahun_ajaran')->where('aktif', 1)->get()->getRow();

        $data = [
            'title'                 => 'Dashboard BP/BK',
            'judul'                 => 'Dashboard BP/BK',
            'active'                => 'dashboard',
            'tahun'                 => $tahunAktif,
            'jumlah_siswa'          => $summary['jumlah_siswa'],
            'jumlah_guru'           => $summary['jumlah_guru'],
            'jumlah_kelas'          => $summary['jumlah_kelas'],
            'hari_H'                => $hari['H'],
            'hari_I'                => $hari['I'],
            'hari_S'                => $hari['S'],
            'hari_A'                => $hari['A'],
            'tidak_hadir_hari_ini'  => $hari['T'],
            'bulan_H'               => $bulan['H'],
            'bulan_I'               => $bulan['I'],
            'bulan_S'               => $bulan['S'],
            'bulan_A'               => $bulan['A'],
            'persentase_hadir'      => $bulan['PERSEN'],
            'tidak_hadir'           => $tidak_hadir,
            'top_alpha'             => $top_alpha,
        ];

        return $this->compatView->render('templates/header', $data)
            . $this->compatView->render('templates/sidebar', $data)
            . $this->compatView->render('bpbk/dashboard', $data)
            . $this->compatView->render('templates/footer');
    }
}
