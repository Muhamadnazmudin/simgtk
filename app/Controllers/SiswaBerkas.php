<?php

namespace App\Controllers;

use App\Models\BerkasSiswaModel;

class SiswaBerkas extends BaseController
{
    protected BerkasSiswaModel $berkasModel;

    public function __construct()
    {
        $this->berkasModel = new BerkasSiswaModel();
    }

    public function index()
    {
        $siswaId = (int) $this->session->get('siswa_id');
        if (!$siswaId) {
            return redirect()->to(site_url('siswa-login'));
        }

        $siswa = $this->berkasModel->getSiswaById($siswaId);
        if (!$siswa) {
            return redirect()->to(site_url('siswa-login'));
        }

        $berkas = $this->berkasModel->getBerkasSiswa($siswaId);
        $berkasMap = [];
        foreach ($berkas as $b) {
            $berkasMap[$b->jenis_berkas] = $b;
        }

        $data = [
            'active'       => 'berkas',
            'title'        => 'Berkas Siswa',
            'siswa'        => $siswa,
            'berkas'       => $berkasMap,
            'jenis_berkas' => $this->berkasModel->jenisBerkas(),
        ];

        return $this->compatView->render('siswa/layout/header', $data)
            . $this->compatView->render('siswa/layout/sidebar', $data)
            . $this->compatView->render('siswa/berkas/index', $data)
            . $this->compatView->render('siswa/layout/footer');
    }

    public function upload()
    {
        $siswaId = (int) $this->session->get('siswa_id');
        if (!$siswaId) {
            return redirect()->to(site_url('siswa-login'));
        }

        $jenisBerkas = (string) $this->request->getPost('jenis_berkas');
        $file = $this->request->getFile('berkas');

        if (!$file || !$file->isValid()) {
            $this->session->setFlashdata('error', 'Gagal mengupload berkas.');
            return redirect()->to(site_url('siswa/berkas'));
        }

        $uploadDir = FCPATH . 'uploads/berkas_siswa/' . $jenisBerkas . '/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        $fileName = $file->getRandomName();
        $file->move($uploadDir, $fileName);

        $existing = $this->db->table('siswa_berkas')
            ->where('siswa_id', $siswaId)
            ->where('jenis_berkas', $jenisBerkas)
            ->get()
            ->getRow();

        if ($existing) {
            if (!empty($existing->nama_file) && file_exists($uploadDir . $existing->nama_file)) {
                @unlink($uploadDir . $existing->nama_file);
            }
            $this->db->table('siswa_berkas')->where('id', $existing->id)->update([
                'nama_file'   => $fileName,
                'ukuran_file' => $file->getSize(),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
        } else {
            $this->db->table('siswa_berkas')->insert([
                'siswa_id'     => $siswaId,
                'jenis_berkas' => $jenisBerkas,
                'nama_file'    => $fileName,
                'ukuran_file'  => $file->getSize(),
                'access_token' => $this->berkasModel->generateAccessToken(),
                'created_at'   => date('Y-m-d H:i:s'),
            ]);
        }

        $this->session->setFlashdata('success', 'Berkas berhasil diupload.');
        return redirect()->to(site_url('siswa/berkas'));
    }
}
