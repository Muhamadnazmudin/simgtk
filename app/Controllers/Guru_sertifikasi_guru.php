<?php

namespace App\Controllers;

use App\Models\GuruSertifikasiModel;

class Guru_sertifikasi_guru extends BaseController
{
    protected GuruSertifikasiModel $sertifikasiModel;

    public function __construct()
    {
        $this->sertifikasiModel = new GuruSertifikasiModel();
    }

    private function checkGuru(): void
    {
        $this->requireAuth();
        $roleId = (int) $this->session->get('role_id');
        if (!in_array($roleId, [3, 4], true)) {
            header('Location: ' . site_url('dashboard'));
            exit;
        }
    }

    public function index()
    {
        $this->checkGuru();

        $guruId = (int) $this->session->get('guru_id');

        $data = [
            'active'      => 'guru_sertifikasi',
            'mode'        => 'guru',
            'sertifikasi' => $this->sertifikasiModel->getByGuru($guruId),
            'guru'        => [],
            'title'       => 'Riwayat Sertifikasi Guru',
        ];

        return $this->compatView->render('templates/header', $data)
            . $this->compatView->render('templates/sidebar_guru', $data)
            . $this->compatView->render('guru/sertifikasi', $data)
            . $this->compatView->render('templates/footer', $data);
    }

    public function update($id = null)
    {
        $this->checkGuru();
        $guruId = (int) $this->session->get('guru_id');
        $id = (int) $id;

        if (!$this->sertifikasiModel->ownedBy($id, $guruId)) {
            return $this->response->setStatusCode(403)->setBody('Akses ditolak');
        }

        $post = $this->request->getPost();
        $this->sertifikasiModel->update($id, $post);
        $this->session->setFlashdata('success', 'Data berhasil diubah');
        return redirect()->to(site_url('guru_sertifikasi_guru'));
    }

    public function delete($id = null)
    {
        $this->checkGuru();
        $guruId = (int) $this->session->get('guru_id');
        $id = (int) $id;

        if (!$this->sertifikasiModel->ownedBy($id, $guruId)) {
            return $this->response->setStatusCode(403)->setBody('Akses ditolak');
        }

        $this->sertifikasiModel->delete($id);
        $this->session->setFlashdata('success', 'Data berhasil dihapus');
        return redirect()->to(site_url('guru_sertifikasi_guru'));
    }
}
