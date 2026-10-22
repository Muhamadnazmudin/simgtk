<?php

namespace App\Controllers;

use App\Models\SekolahModel;
use App\Models\GuruModel;

class Sekolah extends BaseController
{
    protected SekolahModel $sekolahModel;
    protected GuruModel $guruModel;

    public function __construct()
    {
        $this->sekolahModel = new SekolahModel();
        $this->guruModel    = new GuruModel();
    }

    public function index()
    {
        $this->requireRole([1]); // Admin only

        $data = [
            'active'  => 'Sekolah',
            'title'   => 'Pengaturan Sekolah',
            'sekolah' => $this->sekolahModel->getSingle(),
            'total'   => $this->sekolahModel->countAllResults(),
        ];

        return $this->renderView('sekolah/index', $data);
    }

    public function tambah()
    {
        $this->requireRole([1]);

        if ($this->sekolahModel->countAllResults() > 0) {
            return redirect()->to(site_url('sekolah'));
        }

        $data = [
            'active' => 'Sekolah',
            'title'  => 'Tambah Data Sekolah',
            'guru'   => $this->guruModel->orderBy('nama', 'ASC')->findAll(),
        ];

        return $this->renderView('sekolah/form', $data);
    }

    public function simpan()
    {
        $this->requireRole([1]);

        if ($this->sekolahModel->countAllResults() > 0) {
            return redirect()->to(site_url('sekolah'));
        }

        $logoName = '';
        $file = $this->request->getFile('logo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png'];
            if (in_array($file->getMimeType(), $allowedTypes, true)) {
                $logoName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/logo', $logoName);
            }
        }

        $wakaId = $this->request->getPost('waka_kurikulum_id');

        $data = [
            'nama_sekolah'        => (string) $this->request->getPost('nama_sekolah', FILTER_DEFAULT),
            'npsn'                => (string) $this->request->getPost('npsn', FILTER_DEFAULT),
            'alamat'              => (string) $this->request->getPost('alamat', FILTER_DEFAULT),
            'desa'                => (string) $this->request->getPost('desa', FILTER_DEFAULT),
            'kecamatan'           => (string) $this->request->getPost('kecamatan', FILTER_DEFAULT),
            'kabupaten'           => (string) $this->request->getPost('kabupaten', FILTER_DEFAULT),
            'latitude'            => $this->request->getPost('latitude') ?: null,
            'longitude'           => $this->request->getPost('longitude') ?: null,
            'logo'                => $logoName,
            'nama_kepala_sekolah' => (string) $this->request->getPost('nama_kepala_sekolah', FILTER_DEFAULT),
            'nip_kepala_sekolah'  => (string) $this->request->getPost('nip_kepala_sekolah', FILTER_DEFAULT),
            'waka_kurikulum_id'   => !empty($wakaId) ? (int) $wakaId : null,
        ];

        $this->sekolahModel->insert($data);

        if (!empty($wakaId)) {
            $this->sekolahModel->setRoleWakakur((int) $wakaId);
        }

        $this->session->setFlashdata('success', 'Data sekolah berhasil disimpan.');
        return redirect()->to(site_url('sekolah'));
    }

    public function edit($id = null)
    {
        $this->requireRole([1]);

        $row = $this->sekolahModel->find($id);
        if (!$row) {
            return redirect()->to(site_url('sekolah'));
        }

        $data = [
            'active' => 'Sekolah',
            'title'  => 'Edit Data Sekolah',
            'row'    => $row,
            'guru'   => $this->guruModel->orderBy('nama', 'ASC')->findAll(),
        ];

        return $this->renderView('sekolah/form', $data);
    }

    public function update($id = null)
    {
        $this->requireRole([1]);

        $row = $this->sekolahModel->find($id);
        if (!$row) {
            return redirect()->to(site_url('sekolah'));
        }

        $logoName = $row->logo;
        $file = $this->request->getFile('logo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png'];
            if (in_array($file->getMimeType(), $allowedTypes, true)) {
                $newLogo = $file->getRandomName();
                $file->move(FCPATH . 'uploads/logo', $newLogo);
                if (!empty($logoName) && file_exists(FCPATH . 'uploads/logo/' . $logoName)) {
                    @unlink(FCPATH . 'uploads/logo/' . $logoName);
                }
                $logoName = $newLogo;
            }
        }

        $wakaId = $this->request->getPost('waka_kurikulum_id');

        $data = [
            'nama_sekolah'        => (string) $this->request->getPost('nama_sekolah', FILTER_DEFAULT),
            'npsn'                => (string) $this->request->getPost('npsn', FILTER_DEFAULT),
            'alamat'              => (string) $this->request->getPost('alamat', FILTER_DEFAULT),
            'desa'                => (string) $this->request->getPost('desa', FILTER_DEFAULT),
            'kecamatan'           => (string) $this->request->getPost('kecamatan', FILTER_DEFAULT),
            'kabupaten'           => (string) $this->request->getPost('kabupaten', FILTER_DEFAULT),
            'latitude'            => $this->request->getPost('latitude') ?: null,
            'longitude'           => $this->request->getPost('longitude') ?: null,
            'logo'                => $logoName,
            'nama_kepala_sekolah' => (string) $this->request->getPost('nama_kepala_sekolah', FILTER_DEFAULT),
            'nip_kepala_sekolah'  => (string) $this->request->getPost('nip_kepala_sekolah', FILTER_DEFAULT),
            'waka_kurikulum_id'   => !empty($wakaId) ? (int) $wakaId : null,
        ];

        $this->sekolahModel->update($id, $data);

        if (!empty($wakaId)) {
            $this->sekolahModel->resetOldWakakur((int) $wakaId);
            $this->sekolahModel->setRoleWakakur((int) $wakaId);
        }

        if ($this->session->get('guru_id') == $wakaId) {
            $this->session->set([
                'role_id'   => 4,
                'role_name' => 'wakakur',
            ]);
        }

        $this->session->setFlashdata('success', 'Data sekolah berhasil diperbarui.');
        return redirect()->to(site_url('sekolah'));
    }

    public function hapus($id = null)
    {
        $this->requireRole([1]);

        $row = $this->sekolahModel->find($id);
        if ($row && !empty($row->logo) && file_exists(FCPATH . 'uploads/logo/' . $row->logo)) {
            @unlink(FCPATH . 'uploads/logo/' . $row->logo);
        }

        $this->sekolahModel->delete($id);
        $this->session->setFlashdata('success', 'Data sekolah berhasil dihapus.');
        return redirect()->to(site_url('sekolah'));
    }
}
