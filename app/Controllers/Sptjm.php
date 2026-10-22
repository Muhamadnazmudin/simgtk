<?php

namespace App\Controllers;

use App\Models\SptjmModel;

class Sptjm extends BaseController
{
    protected SptjmModel $sptjmModel;
    protected string $uploadPath;

    public function __construct()
    {
        $this->sptjmModel = new SptjmModel();
        $this->uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'sptjm' . DIRECTORY_SEPARATOR;
    }

    public function index()
    {
        return redirect()->to(site_url('sptjm/upload'));
    }

    public function upload()
    {
        $this->requireRole([1]);

        $data = [
            'title'  => 'Upload SPTJM Dapodik',
            'active' => 'sptjm_upload',
        ];

        return $this->renderView('sptjm/upload', $data);
    }

    public function simpan()
    {
        $this->requireRole([1]);

        $tahunAjaran   = trim((string) $this->request->getPost('tahun_ajaran'));
        $jenisSptjm    = trim((string) $this->request->getPost('jenis_sptjm'));
        $tanggalCutOff = trim((string) $this->request->getPost('tanggal_cut_off'));
        $tanggalSptjm  = trim((string) $this->request->getPost('tanggal_sptjm'));
        $keterangan    = trim((string) $this->request->getPost('keterangan'));

        if (empty($tahunAjaran) || empty($jenisSptjm) || empty($tanggalCutOff) || empty($tanggalSptjm)) {
            $this->session->setFlashdata('error', 'Semua kolom wajib diisi.');
            return redirect()->to(site_url('sptjm/upload'));
        }

        $tahunFolder = str_replace('/', '-', $tahunAjaran);
        $targetDir = $this->uploadPath . $tahunFolder . DIRECTORY_SEPARATOR . $jenisSptjm . DIRECTORY_SEPARATOR;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $pdfFile   = $this->request->getFile('file_sptjm');
        $excelFile = $this->request->getFile('file_excel');

        $fileSptjmName     = null;
        $namaFileSptjm     = null;
        $fileExcelName     = null;
        $namaFileExcel     = null;

        if ($pdfFile && $pdfFile->isValid() && !$pdfFile->hasMoved()) {
            $namaFileSptjm = $pdfFile->getClientName();
            $fileSptjmName = $pdfFile->getRandomName();
            $pdfFile->move($targetDir, $fileSptjmName);
        }

        if ($excelFile && $excelFile->isValid() && !$excelFile->hasMoved()) {
            $namaFileExcel = $excelFile->getClientName();
            $fileExcelName = $excelFile->getRandomName();
            $excelFile->move($targetDir, $fileExcelName);
        }

        $this->sptjmModel->insert([
            'tahun_ajaran'     => $tahunAjaran,
            'jenis_sptjm'      => $jenisSptjm,
            'tanggal_cut_off'  => $tanggalCutOff,
            'tanggal_sptjm'    => $tanggalSptjm,
            'file_sptjm'       => $fileSptjmName,
            'nama_file_sptjm'  => $namaFileSptjm,
            'file_excel'       => $fileExcelName,
            'nama_file_excel'  => $namaFileExcel,
            'keterangan'       => $keterangan,
            'created_at'       => date('Y-m-d H:i:s'),
        ]);

        $this->session->setFlashdata('success', 'Data SPTJM berhasil disimpan.');
        return redirect()->to(site_url('sptjm/riwayat'));
    }

    public function riwayat()
    {
        $this->requireRole([1]);

        $tahunAjaran = trim((string) $this->request->getGet('tahun_ajaran'));
        $jenisSptjm  = trim((string) $this->request->getGet('jenis_sptjm'));

        $data = [
            'title'        => 'Riwayat SPTJM Dapodik',
            'active'       => 'sptjm_riwayat',
            'tahun_ajaran' => $tahunAjaran,
            'jenis_sptjm'  => $jenisSptjm,
            'tahun_list'   => $this->sptjmModel->getTahunAjaran(),
            'rows'         => $this->sptjmModel->filter($tahunAjaran ?: null, $jenisSptjm ?: null),
        ];

        return $this->renderView('sptjm/riwayat', $data);
    }

    public function download_pdf($id = null)
    {
        $this->requireRole([1]);

        $row = $this->sptjmModel->getById((int) $id);
        if (!$row || empty($row->file_sptjm)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $tahunFolder = str_replace('/', '-', $row->tahun_ajaran);
        $path = $this->uploadPath . $tahunFolder . DIRECTORY_SEPARATOR . $row->jenis_sptjm . DIRECTORY_SEPARATOR . $row->file_sptjm;

        if (!file_exists($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $this->response->download($path, null)->setFileName($row->nama_file_sptjm ?: 'sptjm.pdf');
    }

    public function download_excel($id = null)
    {
        $this->requireRole([1]);

        $row = $this->sptjmModel->getById((int) $id);
        if (!$row || empty($row->file_excel)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $tahunFolder = str_replace('/', '-', $row->tahun_ajaran);
        $path = $this->uploadPath . $tahunFolder . DIRECTORY_SEPARATOR . $row->jenis_sptjm . DIRECTORY_SEPARATOR . $row->file_excel;

        if (!file_exists($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $this->response->download($path, null)->setFileName($row->nama_file_excel ?: 'sptjm.xlsx');
    }

    public function hapus($id = null)
    {
        $this->requireRole([1]);

        $row = $this->sptjmModel->getById((int) $id);
        if ($row) {
            $tahunFolder = str_replace('/', '-', $row->tahun_ajaran);
            $dir = $this->uploadPath . $tahunFolder . DIRECTORY_SEPARATOR . $row->jenis_sptjm . DIRECTORY_SEPARATOR;

            if (!empty($row->file_sptjm) && file_exists($dir . $row->file_sptjm)) {
                unlink($dir . $row->file_sptjm);
            }
            if (!empty($row->file_excel) && file_exists($dir . $row->file_excel)) {
                unlink($dir . $row->file_excel);
            }

            $this->sptjmModel->delete((int) $id);
            $this->session->setFlashdata('success', 'Data SPTJM berhasil dihapus.');
        }

        return redirect()->to(site_url('sptjm/riwayat'));
    }
}
