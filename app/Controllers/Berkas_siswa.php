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

    public function index($offset = 0)
    {
        $this->requireRole([1, 2]);

        $keyword = trim((string) $this->request->getGet('keyword'));
        $kelasId = trim((string) $this->request->getGet('kelas'));
        $limit   = 36;
        $page    = (int) ($this->request->getGet('page') ?: 1);
        if ($page > 1 && (int) $offset === 0) {
            $offset = ($page - 1) * $limit;
        } else {
            $offset = (int) $offset;
        }

        $totalRows = $this->berkasModel->countSiswaAktif($keyword, $kelasId);
        $siswaList = $this->berkasModel->getSiswaAktif($limit, $offset, $keyword, $kelasId);

        $pager = \Config\Services::pager();
        $pager->makeLinks($page, $limit, $totalRows, 'default_full');

        $nomorAwal  = $totalRows > 0 ? $offset + 1 : 0;
        $nomorAkhir = $totalRows > 0 ? min($offset + $limit, $totalRows) : 0;

        $data = [
            'title'       => 'Upload Berkas Siswa',
            'active'      => 'berkas_siswa',
            'siswa'       => $siswaList,
            'kelas'       => $this->berkasModel->getKelasAktif(),
            'pagination'  => $pager->links(),
            'keyword'     => $keyword,
            'kelas_id'    => $kelasId,
            'total_rows'  => $totalRows,
            'offset'      => $offset,
            'nomor_awal'  => $nomorAwal,
            'nomor_akhir' => $nomorAkhir,
        ];

        return $this->renderView('admin/berkas_siswa/index', $data);
    }

    public function detail($siswaId = null)
    {
        $this->requireRole([1, 2]);

        $siswa = $this->berkasModel->getSiswaById((int) $siswaId);
        if (!$siswa) {
            return redirect()->to(site_url('berkas_siswa'));
        }

        $berkas = $this->berkasModel->getBerkasSiswa((int) $siswaId);
        $berkasMap = [];
        foreach ($berkas as $b) {
            $berkasMap[$b->jenis_berkas] = $b;
        }

        $data = [
            'title'        => 'Detail Berkas Siswa - ' . $siswa->nama,
            'active'       => 'berkas_siswa',
            'siswa'        => $siswa,
            'berkas'       => $berkasMap,
            'jenis_berkas' => $this->berkasModel->jenisBerkas(),
        ];

        return $this->renderView('berkas_siswa/detail', $data);
    }

    public function upload()
    {
        $this->requireRole([1, 2]);

        $siswaId     = (int) $this->request->getPost('siswa_id');
        $jenisBerkas = (string) $this->request->getPost('jenis_berkas');

        $file = $this->request->getFile('berkas');
        if (!$file || !$file->isValid()) {
            $this->session->setFlashdata('error', 'Gagal mengupload berkas.');
            return redirect()->to(site_url('berkas_siswa/detail/' . $siswaId));
        }

        $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!in_array($file->getMimeType(), $allowedMimes, true)) {
            $this->session->setFlashdata('error', 'Format berkas harus PDF, JPG, atau PNG.');
            return redirect()->to(site_url('berkas_siswa/detail/' . $siswaId));
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
        return redirect()->to(site_url('berkas_siswa/detail/' . $siswaId));
    }
}
