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

    /**
     * Cari lokasi file fisik berkas (mendukung root uploads/ dan public/uploads/)
     */
    protected function getBerkasFilePath(object $berkas): ?string
    {
        $candidates = [
            FCPATH . 'uploads/berkas_siswa/' . (int) $berkas->siswa_id . '/' . $berkas->nama_file,
            ROOTPATH . 'uploads/berkas_siswa/' . (int) $berkas->siswa_id . '/' . $berkas->nama_file,
            FCPATH . 'uploads/berkas_siswa/' . $berkas->nama_file,
            ROOTPATH . 'uploads/berkas_siswa/' . $berkas->nama_file,
        ];

        foreach ($candidates as $filePath) {
            if (is_file($filePath)) {
                return $filePath;
            }
        }

        return null;
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
        $file = $this->request->getFile('file') ?: $this->request->getFile('berkas');

        if (!$file || !$file->isValid()) {
            $this->session->setFlashdata('error', 'Gagal mengupload berkas.');
            return redirect()->to(site_url('siswa/berkas'));
        }

        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        $mime = $file->getMimeType();
        if (!in_array($mime, $allowedMimes, true)) {
            $this->session->setFlashdata('error', 'Format berkas harus JPG, PNG, atau PDF.');
            return redirect()->to(site_url('siswa/berkas'));
        }

        $uploadDir = FCPATH . 'uploads/berkas_siswa/' . $siswaId . '/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        $existing = $this->db->table('siswa_berkas')
            ->where('siswa_id', $siswaId)
            ->where('jenis_berkas', $jenisBerkas)
            ->get()
            ->getRow();

        if ($existing) {
            $oldFile = $this->getBerkasFilePath($existing);
            if ($oldFile && is_file($oldFile)) {
                @unlink($oldFile);
            }
        }

        $fileName = $file->getRandomName();
        $file->move($uploadDir, $fileName);

        if ($existing) {
            $this->db->table('siswa_berkas')->where('id', $existing->id)->update([
                'nama_file'      => $fileName,
                'nama_file_asli' => $file->getClientName(),
                'tipe_file'      => $mime,
                'ukuran_file'    => $file->getSize(),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
        } else {
            $this->db->table('siswa_berkas')->insert([
                'siswa_id'       => $siswaId,
                'jenis_berkas'   => $jenisBerkas,
                'nama_file'      => $fileName,
                'nama_file_asli' => $file->getClientName(),
                'tipe_file'      => $mime,
                'ukuran_file'    => $file->getSize(),
                'access_token'   => $this->berkasModel->generateAccessToken(),
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
        }

        $this->session->setFlashdata('success', 'Berkas berhasil diupload.');
        return redirect()->to(site_url('siswa/berkas'));
    }

    /**
     * File publik berdasarkan access_token (untuk Google Spreadsheet / pratinjau publik)
     */
    public function file(string $token = '')
    {
        $token = trim($token);
        if ($token === '') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Token kosong');
        }

        $berkas = $this->db->table('siswa_berkas')->where('access_token', $token)->get()->getRow();
        if (!$berkas) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Berkas tidak ditemukan');
        }

        $file = $this->getBerkasFilePath($berkas);
        if (!$file) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File fisik tidak ditemukan');
        }

        $mime = $berkas->tipe_file ?: mime_content_type($file);

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($berkas->nama_file_asli) . '"')
            ->setBody(file_get_contents($file));
    }
}
