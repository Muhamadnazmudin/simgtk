<?php

namespace App\Controllers;

use App\Models\RekapJjmModel;
use ZipArchive;

class Rekap_jjm extends BaseController
{
    protected RekapJjmModel $rekapModel;

    public function __construct()
    {
        $this->rekapModel = new RekapJjmModel();
    }

    public function index()
    {
        $this->requireRole([1, 4]);

        $data = [
            'title'      => 'Rekap JJM Guru',
            'active'     => 'rekap_jjm',
            'group_guru' => true,
            'guru'       => $this->rekapModel->getAllGuru(),
        ];

        return $this->renderView('admin/rekap_jjm/index', $data);
    }

    public function upload_all()
    {
        $this->requireRole([1, 4]);

        $zipPath  = FCPATH . 'uploads/jjm/temp/';
        $destPath = FCPATH . 'uploads/jjm/guru/';

        if (!is_dir($zipPath)) {
            mkdir($zipPath, 0755, true);
        }
        if (!is_dir($destPath)) {
            mkdir($destPath, 0755, true);
        }

        $file = $this->request->getFile('zip_file');
        if (!$file || !$file->isValid()) {
            $this->session->setFlashdata('error', 'File ZIP tidak valid atau belum dipilih.');
            return redirect()->to(site_url('rekap_jjm'));
        }

        $newName = 'jjm_' . time() . '.zip';
        $file->move($zipPath, $newName);
        $fullPath = $zipPath . $newName;

        $zip = new ZipArchive();
        if ($zip->open($fullPath) === true) {
            $zip->extractTo($zipPath);
            $zip->close();
        }
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        $pdfFiles = glob($zipPath . '*.pdf') ?: [];
        foreach ($pdfFiles as $pdfFile) {
            $filename = basename($pdfFile);
            $guruId   = (int) pathinfo($filename, PATHINFO_FILENAME);

            $guru = $this->db->table('guru')->where('id', $guruId)->get()->getRow();
            if (!$guru) {
                continue;
            }

            rename($pdfFile, $destPath . $filename);

            $cek = $this->rekapModel->getByGuru($guruId);
            if ($cek) {
                $this->db->table('rekap_jjm')->where('guru_id', $guruId)->update(['file_jjm' => $filename]);
            } else {
                $this->db->table('rekap_jjm')->insert([
                    'guru_id'  => $guruId,
                    'file_jjm' => $filename,
                ]);
            }
        }

        // Clean up remaining files in temp
        foreach (glob($zipPath . '*') ?: [] as $tmp) {
            if (is_file($tmp)) {
                unlink($tmp);
            }
        }

        $this->session->setFlashdata('success', 'Upload massal JJM berhasil');
        return redirect()->to(site_url('rekap_jjm'));
    }

    public function upload_single()
    {
        $this->requireRole([1, 4]);

        $guruId = (int) $this->request->getPost('guru_id');
        $file   = $this->request->getFile('file_jjm');

        if (!$file || !$file->isValid()) {
            $this->session->setFlashdata('error', 'File PDF belum dipilih.');
            return redirect()->to(site_url('rekap_jjm'));
        }

        $path = FCPATH . 'uploads/jjm/guru/';
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $fileName = $guruId . '.pdf';
        if (file_exists($path . $fileName)) {
            unlink($path . $fileName);
        }
        $file->move($path, $fileName);

        $cek = $this->rekapModel->getByGuru($guruId);
        if ($cek) {
            $this->db->table('rekap_jjm')->where('guru_id', $guruId)->update(['file_jjm' => $fileName]);
        } else {
            $this->db->table('rekap_jjm')->insert([
                'guru_id'  => $guruId,
                'file_jjm' => $fileName,
            ]);
        }

        $this->session->setFlashdata('success', 'File JJM berhasil diupload');
        return redirect()->to(site_url('rekap_jjm'));
    }

    public function delete($guruId)
    {
        $this->requireRole([1, 4]);

        $guruId = (int) $guruId;
        $data   = $this->rekapModel->getByGuru($guruId);
        if ($data && $data->file_jjm) {
            $file = FCPATH . 'uploads/jjm/guru/' . $data->file_jjm;
            if (file_exists($file)) {
                unlink($file);
            }
            $this->db->table('rekap_jjm')->where('guru_id', $guruId)->delete();
        }

        $this->session->setFlashdata('success', 'File JJM berhasil dihapus');
        return redirect()->to(site_url('rekap_jjm'));
    }
}
