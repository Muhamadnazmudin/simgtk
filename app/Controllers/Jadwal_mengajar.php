<?php

namespace App\Controllers;

use App\Models\JadwalMengajarModel;
use App\Models\GuruModel;
use App\Models\KelasModel;
use App\Models\MapelModel;

class Jadwal_mengajar extends BaseController
{
    protected JadwalMengajarModel $jadwalModel;
    protected GuruModel $guruModel;
    protected KelasModel $kelasModel;
    protected MapelModel $mapelModel;

    public function __construct()
    {
        $this->jadwalModel = new JadwalMengajarModel();
        $this->guruModel   = new GuruModel();
        $this->kelasModel  = new KelasModel();
        $this->mapelModel  = new MapelModel();
    }

    public function index()
    {
        $this->requireAuth();

        $filter = [
            'hari'     => $this->request->getGet('hari'),
            'guru_id'  => $this->request->getGet('guru_id'),
            'kelas_id' => $this->request->getGet('kelas_id'),
        ];

        $data = [
            'title'  => 'Jadwal Mengajar Guru',
            'active' => 'jadwal_mengajar',
            'jadwal' => $this->jadwalModel->getAll($filter),
            'guru'   => $this->guruModel->orderBy('nama', 'ASC')->findAll(),
            'kelas'  => $this->kelasModel->orderBy('nama', 'ASC')->findAll(),
        ];

        return $this->renderView('jadwal_mengajar/index', $data);
    }

    public function tambah()
    {
        $this->requireRole([1, 4]);

        $data = [
            'title'  => 'Tambah Jadwal Mengajar',
            'active' => 'jadwal_mengajar',
            'guru'   => $this->guruModel->orderBy('nama', 'ASC')->findAll(),
            'kelas'  => $this->kelasModel->orderBy('nama', 'ASC')->findAll(),
            'mapel'  => $this->mapelModel->orderBy('nama_mapel', 'ASC')->findAll(),
            'jam'    => $this->jadwalModel->jamMengajar(),
        ];

        return $this->renderView('jadwal_mengajar/tambah', $data);
    }

    public function store()
    {
        $this->requireRole([1, 4]);

        $jamAwal  = (int) $this->request->getPost('jam_mulai_id');
        $jamAkhir = (int) $this->request->getPost('jam_selesai_id');

        if (!$jamAwal || !$jamAkhir || $jamAkhir < $jamAwal) {
            $this->session->setFlashdata('error', 'Jam awal & akhir tidak valid');
            return redirect()->to(site_url('jadwal_mengajar/tambah'));
        }

        $hari     = (string) $this->request->getPost('hari');
        $guruId   = (int) $this->request->getPost('guru_id');
        $rombelId = (int) $this->request->getPost('rombel_id');

        $bentrok = $this->jadwalModel->getBentrokDetail($hari, $jamAwal, $jamAkhir, $guruId, $rombelId);
        if ($bentrok) {
            $this->session->setFlashdata('error', "❌ Jadwal Bentrok! Hari: {$bentrok->hari}, Guru: {$bentrok->nama_guru}, Kelas: {$bentrok->nama_kelas}");
            return redirect()->to(site_url('jadwal_mengajar/tambah'));
        }

        $this->jadwalModel->insert([
            'hari'           => $hari,
            'jam_mulai_id'   => $jamAwal,
            'jam_selesai_id' => $jamAkhir,
            'guru_id'        => $guruId,
            'rombel_id'      => $rombelId,
            'mapel_id'       => (int) $this->request->getPost('mapel_id'),
        ]);

        $this->session->setFlashdata('success', 'Jadwal berhasil ditambahkan.');
        return redirect()->to(site_url('jadwal_mengajar'));
    }

    public function hapus($id = null)
    {
        $this->requireRole([1, 4]);

        $this->jadwalModel->delete($id);
        $this->session->setFlashdata('success', 'Jadwal berhasil dihapus.');
        return redirect()->to(site_url('jadwal_mengajar'));
    }
}
