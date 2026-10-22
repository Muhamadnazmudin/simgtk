<?php

namespace App\Controllers;

use App\Libraries\Idcard_lib;

class Idcard extends BaseController
{
    public function cetak($id = null)
    {
        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $siswa = $this->db->table('siswa')
            ->select('siswa.*, kelas.nama AS nama_kelas')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->where('siswa.id', (int) $id)
            ->get()
            ->getRow();

        if (!$siswa) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data siswa tidak ditemukan.');
        }

        $lib = new Idcard_lib();
        $binary = $lib->generate($id);

        if (!$binary) {
            return $this->response->setStatusCode(500)->setBody('Gagal membuat ID Card.');
        }

        $namaFile = 'ID_CARD_' . strtoupper(preg_replace('/[^A-Z0-9]+/i', '_', $siswa->nama)) . '.png';

        return $this->response
            ->setHeader('Content-Type', 'image/png')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $namaFile . '"')
            ->setBody($binary);
    }
}
