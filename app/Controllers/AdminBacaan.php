<?php

namespace App\Controllers;

use App\Models\BacaanModel;

class AdminBacaan extends BaseController
{
    protected BacaanModel $bacaanModel;

    public function __construct()
    {
        $this->bacaanModel = new BacaanModel();
    }

    public function index()
    {
        $this->requireRole([1, 2]);

        $data = [
            'title'           => 'Kelola Buku Bacaan',
            'active'          => 'buku',
            'group_kurikulum' => true,
            'buku'            => $this->bacaanModel->getAllAdmin(),
        ];

        return $this->renderView('admin/bacaan/index', $data);
    }

    public function edit($id = null)
    {
        $this->requireRole([1, 2]);

        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $buku = $this->bacaanModel->find((int) $id);
        if (!$buku) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'           => 'Edit Buku',
            'buku'            => $buku,
            'active'          => 'buku',
            'group_kurikulum' => true,
        ];

        return $this->renderView('admin/bacaan/edit', $data);
    }

    public function update($id = null)
    {
        $this->requireRole([1, 2]);

        if (!$id) {
            return redirect()->to(site_url('AdminBacaan'));
        }

        $data = [
            'judul'  => $this->request->getPost('judul'),
            'kelas'  => $this->request->getPost('kelas'),
            'mapel'  => $this->request->getPost('mapel'),
            'status' => $this->request->getPost('status'),
        ];

        $coverFile = $this->request->getFile('cover');
        if ($coverFile && $coverFile->isValid() && !$coverFile->hasMoved()) {
            $uploadPath = FCPATH . 'assets/uploads/cover_buku/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $newName = $coverFile->getRandomName();
            $coverFile->move($uploadPath, $newName);
            $data['cover'] = $newName;
        }

        $this->bacaanModel->update((int) $id, $data);
        $this->session->setFlashdata('success', 'Buku berhasil diperbarui');
        return redirect()->to(site_url('AdminBacaan'));
    }

    public function delete($id = null)
    {
        $this->requireRole([1, 2]);

        if ($id) {
            $this->bacaanModel->delete((int) $id);
            $this->session->setFlashdata('success', 'Buku berhasil dihapus');
        }

        return redirect()->to(site_url('AdminBacaan'));
    }

    public function import()
    {
        $this->requireRole([1, 2]);

        $data = [
            'title'           => 'Import Bacaan',
            'active'          => 'bacaan_import',
            'group_kurikulum' => true,
        ];

        return $this->renderView('admin/bacaan/import', $data);
    }

    public function import_proses()
    {
        $this->requireRole([1, 2]);

        $file = $this->request->getFile('file_csv');
        if (!$file || !$file->isValid()) {
            $this->session->setFlashdata('error', 'File CSV belum dipilih.');
            return redirect()->to(site_url('AdminBacaan/import'));
        }

        $handle = fopen($file->getTempName(), 'r');
        if ($handle) {
            fgetcsv($handle); // skip header row

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($row) < 4) {
                    continue;
                }

                preg_match('/\/d\/(.*?)\//', $row[3], $match);
                $fileId = $match[1] ?? null;
                if (!$fileId) {
                    continue;
                }

                $this->db->table('tbl_bacaan')->insert([
                    'judul'         => trim($row[0]),
                    'kelas'         => trim($row[1]),
                    'mapel'         => trim($row[2]),
                    'drive_file_id' => $fileId,
                    'status'        => trim($row[4] ?? 'aktif'),
                ]);
            }
            fclose($handle);
        }

        $this->session->setFlashdata('success', 'Import bacaan berhasil');
        return redirect()->to(site_url('AdminBacaan/import'));
    }
}
