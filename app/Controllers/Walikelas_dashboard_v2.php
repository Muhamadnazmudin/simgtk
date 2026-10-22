<?php

namespace App\Controllers;

use App\Models\Walikelas_dashboard_v2_model;

class Walikelas_dashboard_v2 extends BaseController
{
    protected Walikelas_dashboard_v2_model $model;

    public function __construct()
    {
        $this->model = new Walikelas_dashboard_v2_model();
    }

    private function checkWalikelas(): ?object
    {
        $this->requireAuth();

        $guruId = $this->session->get('guru_id');
        if (!$guruId) {
            header('Location: ' . site_url('guru_dashboard'));
            exit;
        }

        $kelas = $this->db->table('kelas')->where('wali_kelas_id', $guruId)->get()->getRow();
        if (!$kelas) {
            header('Location: ' . site_url('guru_dashboard'));
            exit;
        }

        $this->session->set([
            'kelas_id'   => (int) $kelas->id,
            'kelas_nama' => $kelas->nama,
        ]);

        return $kelas;
    }

    public function index()
    {
        $kelas = $this->checkWalikelas();
        $kelasId = (int) $kelas->id;
        $kelasNama = $kelas->nama;

        $siswa = $this->model->getTotalSiswa($kelasId);
        $hari  = $this->model->getHariIni($kelasId);
        $bulan = $this->model->getBulanIni($kelasId);
        $tidak_hadir = $this->model->getSiswaTidakHadir($kelasId);
        $top_alpha = $this->model->getTopAlpha($kelasId);
        $chart = $this->model->chart7Hari($kelasId);
        $izin = $this->model->getIzinHariIni($kelasId);

        $tahun = $this->db->table('tahun_ajaran')
            ->where('id', (int) $this->session->get('tahun_id'))
            ->get()
            ->getRow();

        $persen = 0;
        if ($siswa['total'] > 0) {
            $persen = round(($hari['H'] / $siswa['total']) * 100);
        }

        $data = [
            'title'                => 'Dashboard Wali Kelas',
            'active'               => 'dashboard_v2',
            'kelas'                => $kelas,
            'kelas_nama'           => $kelasNama,
            'tahun'                => $tahun,
            'total_siswa'          => $siswa['total'],
            'laki'                 => $siswa['l'],
            'perempuan'            => $siswa['p'],
            'hari_H'               => $hari['H'],
            'hari_I'               => $hari['I'],
            'hari_S'               => $hari['S'],
            'hari_A'               => $hari['A'],
            'bulan_H'              => $bulan['H'],
            'bulan_I'              => $bulan['I'],
            'bulan_S'              => $bulan['S'],
            'bulan_A'              => $bulan['A'],
            'tidak_hadir'          => $tidak_hadir,
            'belum_absen'          => $tidak_hadir,
            'top_alpha'            => $top_alpha,
            'izin_keluar_hari_ini' => $izin['keluar'],
            'izin_pulang_hari_ini' => $izin['pulang'],
            'persen_hadir'         => $persen,
            'chart'                => $chart,
        ];

        return $this->compatView->render('walikelas/templates/header', $data)
            . $this->compatView->render('walikelas/templates/sidebar', $data)
            . $this->compatView->render('walikelas/dashboard_v2', $data)
            . $this->compatView->render('walikelas/templates/footer');
    }
}
