<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AbsensiJadwalModel;

class Jadwalabsensi extends BaseController
{
    protected AbsensiJadwalModel $jadwalModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->jadwalModel = new AbsensiJadwalModel();
    }

    public function index()
    {
        $this->requireRole([1, 2]);

        $data['active'] = 'jadwal_absensi';
        $data['title']  = 'Pengaturan Jam Masuk & Pulang Siswa';
        $data['jadwal'] = $this->jadwalModel->get_all();

        return $this->renderView('absensiqr/jadwal', $data);
    }

    public function update()
    {
        $this->requireRole([1, 2]);

        $ids       = (array) $this->request->getPost('id');
        $jamMasuk  = (array) $this->request->getPost('jam_masuk');
        $jamPulang = (array) $this->request->getPost('jam_pulang');

        foreach ($ids as $i => $id) {
            $dataUpdate = [
                'jam_masuk'  => $jamMasuk[$i] ?? '07:00:00',
                'jam_pulang' => $jamPulang[$i] ?? '15:00:00',
            ];

            $this->jadwalModel->update_jadwal((int) $id, $dataUpdate);
        }

        $this->session->setFlashdata('success', 'Jadwal absensi berhasil diperbarui.');
        return redirect()->to(base_url('jadwalabsensi'));
    }
}
