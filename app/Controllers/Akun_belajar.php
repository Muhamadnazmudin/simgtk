<?php

namespace App\Controllers;

use App\Models\AkunBelajarModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Akun_belajar extends BaseController
{
    protected AkunBelajarModel $akunModel;

    public function __construct()
    {
        $this->akunModel = new AkunBelajarModel();
    }

    public function index()
    {
        $this->requireRole([1, 2, 5]);

        $nama    = trim((string) $this->request->getGet('nama'));
        $kelasId = $this->request->getGet('kelas');
        $page    = (int) $this->request->getGet('page');
        $limit   = 20;
        $offset  = ($page > 0) ? ($page * $limit) : 0;

        $data = [
            'title'      => 'Distribusi Akun BelajarID',
            'active'     => 'akun_belajar',
            'nama'       => $nama,
            'kelas_id'   => $kelasId,
            'siswa'      => $this->akunModel->listSiswa($nama ?: null, $kelasId ?: null, $limit, $offset),
            'total'      => $this->akunModel->countSiswa($nama ?: null, $kelasId ?: null),
            'limit'      => $limit,
            'page'       => $page,
            'list_kelas' => $this->db->table('kelas')->orderBy('nama', 'ASC')->get()->getResult(),
        ];

        return $this->renderView('admin/akun_belajar/index', $data);
    }

    public function import()
    {
        $this->requireRole([1, 2, 5]);

        $data = [
            'title'  => 'Import Akun BelajarID',
            'active' => 'akun_belajar',
        ];

        return $this->renderView('admin/akun_belajar/import', $data);
    }

    public function import_process()
    {
        $this->requireRole([1, 2, 5]);

        $file = $this->request->getFile('file');
        if (!$file || !$file->isValid()) {
            $this->session->setFlashdata('error', 'File Excel belum dipilih.');
            return redirect()->to(site_url('akun_belajar/import'));
        }

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows  = $sheet->toArray(null, true, true, true);

            $insert = 0;
            $update = 0;
            $gagal  = [];

            foreach ($rows as $i => $row) {
                if ($i === 1) {
                    continue; // skip header
                }

                $nisn     = trim((string) ($row['B'] ?? ''));
                $email    = trim((string) ($row['D'] ?? ''));
                $password = trim((string) ($row['E'] ?? ''));

                if ($nisn === '') {
                    $gagal[] = "Baris {$i}: NISN kosong";
                    continue;
                }

                $siswa = $this->db->table('siswa')->where('nisn', $nisn)->get()->getRow();
                if (!$siswa) {
                    $gagal[] = "Baris {$i}: NISN {$nisn} tidak ditemukan di data siswa";
                    continue;
                }

                $data = [
                    'nisn'             => $nisn,
                    'email_belajar'    => $email,
                    'password_default' => $password,
                    'status'           => 'tersimpan',
                ];

                $cek = $this->db->table('akun_belajar_siswa')->where('nisn', $nisn)->get()->getRow();
                if ($cek) {
                    $this->db->table('akun_belajar_siswa')->where('nisn', $nisn)->update($data);
                    $update++;
                } else {
                    $this->db->table('akun_belajar_siswa')->insert($data);
                    $insert++;
                }
            }

            if (!empty($gagal)) {
                $msg = '<b>Import selesai dengan catatan</b><br>Insert: ' . $insert . '<br>Update: ' . $update . '<br><ul>';
                foreach ($gagal as $e) {
                    $msg .= '<li>' . esc($e) . '</li>';
                }
                $msg .= '</ul>';
                $this->session->setFlashdata('error', $msg);
            } else {
                $this->session->setFlashdata('success', "Import berhasil. Insert: <b>{$insert}</b>, Update: <b>{$update}</b>");
            }
        } catch (\Throwable $e) {
            $this->session->setFlashdata('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }

        return redirect()->to(site_url('akun_belajar'));
    }

    public function simpan()
    {
        $this->requireRole([1, 2, 5]);

        $data = [
            'nisn'             => trim((string) $this->request->getPost('nisn')),
            'email_belajar'    => trim((string) $this->request->getPost('email_belajar')),
            'password_default' => trim((string) $this->request->getPost('password_default')),
            'status'           => 'tersimpan',
        ];

        $this->akunModel->saveAkun($data);
        $this->session->setFlashdata('success', 'Akun belajar berhasil disimpan.');
        return redirect()->to(site_url('akun_belajar'));
    }

    public function hapus($nisn = null)
    {
        $this->requireRole([1, 2, 5]);

        if ($nisn) {
            $this->akunModel->deleteByNisn((string) $nisn);
            $this->session->setFlashdata('success', 'Akun belajar berhasil dihapus.');
        }

        return redirect()->to(site_url('akun_belajar'));
    }

    public function reset_subscribe()
    {
        $this->requireRole([1, 2, 5]);

        $nisn = trim((string) $this->request->getPost('nisn'));
        if (!$nisn) {
            return redirect()->to(site_url('akun_belajar'));
        }

        $this->db->table('akun_belajar_siswa')->where('nisn', $nisn)->update(['sudah_subscribe' => 0]);
        $this->session->setFlashdata('success', 'Status subscribe berhasil direset.');
        return redirect()->to(site_url('akun_belajar'));
    }
}
