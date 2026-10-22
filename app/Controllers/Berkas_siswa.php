<?php

namespace App\Controllers;

use App\Models\BerkasSiswaModel;
use App\Models\KelasModel;

class Berkas_siswa extends BaseController
{
    protected BerkasSiswaModel $berkasModel;
    protected KelasModel $kelasModel;

    public function __construct()
    {
        $this->berkasModel = new BerkasSiswaModel();
        $this->kelasModel  = new KelasModel();
    }

    /**
     * Halaman daftar siswa berkas
     */
    public function index($offset = 0)
    {
        $this->requireRole([1, 2, 5]);

        $keyword = trim((string) $this->request->getGet('keyword'));
        $kelasId = trim((string) $this->request->getGet('kelas'));
        $limit   = 36;
        $page    = (int) ($this->request->getGet('page') ?: 0);
        if ($page > 0) {
            $offset = ($page - 1) * $limit;
        } elseif ((int) $offset > 0) {
            $offset = (int) $offset;
            $page   = (int) floor($offset / $limit) + 1;
        } else {
            $page   = 1;
            $offset = 0;
        }

        $totalRows = $this->berkasModel->countSiswaAktif($keyword, $kelasId);
        $siswaList = $this->berkasModel->getSiswaAktif($limit, $offset, $keyword, $kelasId);

        $pager = \Config\Services::pager();
        $paginationHtml = $pager->makeLinks($page, $limit, $totalRows, 'bootstrap_pagination');

        $nomorAwal  = $totalRows > 0 ? $offset + 1 : 0;
        $nomorAkhir = $totalRows > 0 ? min($offset + $limit, $totalRows) : 0;

        $data = [
            'title'       => 'Upload Berkas Siswa',
            'active'      => 'berkas_siswa',
            'siswa'       => $siswaList,
            'kelas'       => $this->berkasModel->getKelasAktif(),
            'pagination'  => $paginationHtml,
            'keyword'     => $keyword,
            'kelas_id'    => $kelasId,
            'total_rows'  => $totalRows,
            'offset'      => $offset,
            'nomor_awal'  => $nomorAwal,
            'nomor_akhir' => $nomorAkhir,
        ];

        return $this->renderView('admin/berkas_siswa/index', $data);
    }

    /**
     * Halaman kelola berkas siswa
     */
    public function kelola($siswaId = null)
    {
        $this->requireRole([1, 2, 5]);

        $siswaId = (int) $siswaId;
        $siswa = $this->berkasModel->getSiswaById($siswaId);

        if (!$siswa) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Siswa tidak ditemukan');
        }

        $berkas = $this->berkasModel->getBerkasSiswa($siswaId);
        $berkasMap = [];
        foreach ($berkas as $item) {
            $berkasMap[$item->jenis_berkas] = $item;
        }

        $data = [
            'title'        => 'Berkas Siswa',
            'active'       => 'berkas_siswa',
            'siswa'        => $siswa,
            'jenis_berkas' => $this->berkasModel->jenisBerkas(),
            'berkas'       => $berkasMap,
        ];

        return $this->renderView('admin/berkas_siswa/kelola', $data);
    }

    /**
     * Alias detail menuju kelola
     */
    public function detail($siswaId = null)
    {
        return $this->kelola($siswaId);
    }

    /**
     * Upload berkas siswa
     */
    public function upload($siswaId = null)
    {
        $this->requireRole([1, 2, 5]);

        $siswaId = (int) ($siswaId ?: $this->request->getPost('siswa_id'));
        $siswa = $this->berkasModel->getSiswaById($siswaId);
        if (!$siswa) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Siswa tidak ditemukan');
        }

        $jenis = trim((string) $this->request->getPost('jenis_berkas'));
        $jenisList = $this->berkasModel->jenisBerkas();

        if (empty($jenis) || !array_key_exists($jenis, $jenisList)) {
            $this->session->setFlashdata('error', 'Jenis berkas tidak valid.');
            return redirect()->to(site_url('berkas_siswa/kelola/' . $siswaId));
        }

        $file = $this->request->getFile('file') ?: $this->request->getFile('berkas');
        if (!$file || !$file->isValid()) {
            $this->session->setFlashdata('error', 'Silakan pilih file terlebih dahulu.');
            return redirect()->to(site_url('berkas_siswa/kelola/' . $siswaId));
        }

        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        $mime = $file->getMimeType();
        if (!in_array($mime, $allowedMimes, true)) {
            $this->session->setFlashdata('error', 'Format berkas harus JPG, PNG, atau PDF.');
            return redirect()->to(site_url('berkas_siswa/kelola/' . $siswaId));
        }

        if ($file->getSize() > 5 * 1024 * 1024) {
            $this->session->setFlashdata('error', 'Ukuran file maksimal 5MB.');
            return redirect()->to(site_url('berkas_siswa/kelola/' . $siswaId));
        }

        $uploadPath = FCPATH . 'uploads/berkas_siswa/' . $siswaId . '/';
        if (!is_dir($uploadPath)) {
            @mkdir($uploadPath, 0755, true);
        }

        $berkasLama = $this->berkasModel->get_berkas($siswaId, $jenis);
        if ($berkasLama && !empty($berkasLama->nama_file)) {
            $fileLama = $uploadPath . $berkasLama->nama_file;
            if (is_file($fileLama)) {
                @unlink($fileLama);
            }
        }

        $fileName = $file->getRandomName();
        $file->move($uploadPath, $fileName);

        $clientName = $file->getClientName();
        $fileSize   = $file->getSize();

        if ($berkasLama) {
            $this->db->table('siswa_berkas')->where('id', $berkasLama->id)->update([
                'nama_file'      => $fileName,
                'nama_file_asli' => $clientName,
                'tipe_file'      => $mime,
                'ukuran_file'    => $fileSize,
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
            $pesan = $jenisList[$jenis] . ' berhasil diganti.';
        } else {
            $this->db->table('siswa_berkas')->insert([
                'siswa_id'       => $siswaId,
                'jenis_berkas'   => $jenis,
                'nama_file'      => $fileName,
                'nama_file_asli' => $clientName,
                'tipe_file'      => $mime,
                'ukuran_file'    => $fileSize,
                'access_token'   => $this->berkasModel->generateAccessToken(),
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
            $pesan = $jenisList[$jenis] . ' berhasil diupload.';
        }

        $this->session->setFlashdata('success', $pesan);
        return redirect()->to(site_url('berkas_siswa/kelola/' . $siswaId));
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

    /**
     * Lihat / preview file dalam viewer
     */
    public function lihat($id = null)
    {
        $this->requireRole([1, 2, 5]);

        $id = (int) $id;
        $berkas = $this->db->table('siswa_berkas')->where('id', $id)->get()->getRow();
        if (!$berkas) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Berkas tidak ditemukan.');
        }

        $file = $this->getBerkasFilePath($berkas);
        if (!$file) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File fisik tidak ditemukan.');
        }

        $mime = $berkas->tipe_file ?: mime_content_type($file);
        $isImage = in_array(strtolower($mime), ['image/jpeg', 'image/jpg', 'image/png'], true);
        $isPdf   = (strtolower($mime) === 'application/pdf');

        $data = [
            'title'        => 'Lihat Berkas',
            'active'       => 'berkas_siswa',
            'berkas'       => $berkas,
            'file_url'     => site_url('berkas_siswa/preview/' . $berkas->id),
            'download_url' => site_url('berkas_siswa/download/' . $berkas->id),
            'mime'         => $mime,
            'is_image'     => $isImage,
            'is_pdf'       => $isPdf,
        ];

        return $this->renderView('admin/berkas_siswa/lihat', $data);
    }

    /**
     * Stream file preview
     */
    public function preview($id = null)
    {
        $this->requireRole([1, 2, 5]);

        $id = (int) $id;
        $berkas = $this->db->table('siswa_berkas')->where('id', $id)->get()->getRow();
        if (!$berkas) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $file = $this->getBerkasFilePath($berkas);
        if (!$file) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File fisik tidak ditemukan.');
        }

        $mime = $berkas->tipe_file ?: mime_content_type($file);

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($berkas->nama_file_asli) . '"')
            ->setBody(file_get_contents($file));
    }

    /**
     * Rotasi gambar
     */
    public function rotate($id = null)
    {
        $this->requireRole([1, 2, 5]);

        $id = (int) $id;
        $berkas = $this->db->table('siswa_berkas')->where('id', $id)->get()->getRow();
        if (!$berkas) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $file = $this->getBerkasFilePath($berkas);
        if (!$file) {
            $this->session->setFlashdata('error', 'File berkas tidak ditemukan.');
            return redirect()->to(site_url('berkas_siswa/lihat/' . $id));
        }

        $mime = strtolower((string) $berkas->tipe_file);
        if (!in_array($mime, ['image/jpeg', 'image/jpg', 'image/png'], true)) {
            $this->session->setFlashdata('error', 'File ini tidak dapat diputar.');
            return redirect()->to(site_url('berkas_siswa/lihat/' . $id));
        }

        $direction = $this->request->getPost('direction');
        if (!in_array($direction, ['left', 'right'], true)) {
            $this->session->setFlashdata('error', 'Arah rotasi tidak valid.');
            return redirect()->to(site_url('berkas_siswa/lihat/' . $id));
        }

        if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            $image = @imagecreatefromjpeg($file);
        } elseif ($mime === 'image/png') {
            $image = @imagecreatefrompng($file);
        } else {
            $image = false;
        }

        if (!$image) {
            $this->session->setFlashdata('error', 'Gambar tidak dapat diproses.');
            return redirect()->to(site_url('berkas_siswa/lihat/' . $id));
        }

        $angle = ($direction === 'right') ? -90 : 90;

        if ($mime === 'image/png') {
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }

        $rotated = @imagerotate($image, $angle, 0);
        imagedestroy($image);

        if (!$rotated) {
            $this->session->setFlashdata('error', 'Gagal memutar gambar.');
            return redirect()->to(site_url('berkas_siswa/lihat/' . $id));
        }

        if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            $saved = @imagejpeg($rotated, $file, 92);
        } elseif ($mime === 'image/png') {
            imagealphablending($rotated, false);
            imagesavealpha($rotated, true);
            $saved = @imagepng($rotated, $file, 6);
        } else {
            $saved = false;
        }
        imagedestroy($rotated);

        if (!$saved) {
            $this->session->setFlashdata('error', 'Gagal menyimpan hasil rotasi.');
            return redirect()->to(site_url('berkas_siswa/lihat/' . $id));
        }

        $this->db->table('siswa_berkas')->where('id', $id)->update([
            'ukuran_file' => filesize($file),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        $this->session->setFlashdata('success', 'Berkas berhasil diputar.');
        return redirect()->to(site_url('berkas_siswa/lihat/' . $id));
    }

    /**
     * Download file
     */
    public function download($id = null)
    {
        $this->requireRole([1, 2, 5]);

        $id = (int) $id;
        $berkas = $this->db->table('siswa_berkas')->where('id', $id)->get()->getRow();
        if (!$berkas) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $file = $this->getBerkasFilePath($berkas);
        if (!$file) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File fisik tidak ditemukan.');
        }

        return $this->response->download($file, null)->setFileName($berkas->nama_file_asli);
    }

    /**
     * Hapus berkas
     */
    public function hapus($id = null)
    {
        $this->requireRole([1, 2, 5]);

        $id = (int) $id;
        $berkas = $this->db->table('siswa_berkas')->where('id', $id)->get()->getRow();
        if (!$berkas) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $file = $this->getBerkasFilePath($berkas);
        if ($file && is_file($file)) {
            @unlink($file);
        }

        $this->db->table('siswa_berkas')->where('id', $id)->delete();

        $this->session->setFlashdata('success', 'Berkas berhasil dihapus.');
        return redirect()->to(site_url('berkas_siswa/kelola/' . $berkas->siswa_id));
    }
}
