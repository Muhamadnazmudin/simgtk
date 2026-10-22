<?php

namespace App\Controllers;

use App\Models\GuruSertifikasiModel;
use App\Models\GuruModel;

class Guru_sertifikasi extends BaseController
{
    protected GuruSertifikasiModel $sertifikasiModel;
    protected GuruModel $guruModel;

    public function __construct()
    {
        $this->sertifikasiModel = new GuruSertifikasiModel();
        $this->guruModel        = new GuruModel();
    }

    public function index()
    {
        $this->requireRole([1, 4]);

        $data = [
            'title'       => 'Sertifikasi Guru',
            'active'      => 'guru_sertifikasi',
            'mode'        => 'admin',
            'guru'        => $this->guruModel->orderBy('nama', 'ASC')->findAll(),
            'sertifikasi' => $this->sertifikasiModel->getAll(),
        ];

        return $this->renderView('guru/sertifikasi', $data);
    }

    public function store()
    {
        $this->requireRole([1, 4]);

        $postData = $this->request->getPost();
        if (empty($postData['tgl_habis_berlaku'])) {
            $postData['tgl_habis_berlaku'] = null;
        }

        $this->sertifikasiModel->insert($postData);
        $this->session->setFlashdata('success', 'Data sertifikasi berhasil ditambahkan');
        return redirect()->to(site_url('guru_sertifikasi'));
    }

    public function update($id = null)
    {
        $this->requireRole([1, 4]);

        if (!$id) {
            return redirect()->to(site_url('guru_sertifikasi'));
        }

        $postData = $this->request->getPost();
        if (empty($postData['tgl_habis_berlaku'])) {
            $postData['tgl_habis_berlaku'] = null;
        }

        $this->sertifikasiModel->update((int) $id, $postData);
        $this->session->setFlashdata('success', 'Data sertifikasi berhasil diperbarui');
        return redirect()->to(site_url('guru_sertifikasi'));
    }

    public function delete($id = null)
    {
        $this->requireRole([1, 4]);

        if ($id) {
            $this->sertifikasiModel->delete((int) $id);
            $this->session->setFlashdata('success', 'Data sertifikasi berhasil dihapus');
        }

        return redirect()->to(site_url('guru_sertifikasi'));
    }
}
