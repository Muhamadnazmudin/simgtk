<?php

namespace App\Controllers;

class SiswaAkunBelajar extends BaseController
{
    private function cekLogin()
    {
        if (!$this->session->get('siswa_login')) {
            header('Location: ' . base_url('siswa-login'));
            exit;
        }
    }

    public function index()
    {
        $this->cekLogin();

        $nisn = $this->session->get('siswa_nisn') ?: $this->session->get('nisn');

        if ($this->request->getPost('confirm_subscribe') == '1') {
            $this->db->table('akun_belajar_siswa')
                ->where('nisn', $nisn)
                ->update(['sudah_subscribe' => 1]);
        }

        $akun = $this->db->table('akun_belajar_siswa a')
            ->select('a.*, s.nama, k.nama AS nama_kelas')
            ->join('siswa s', 's.nisn COLLATE utf8mb4_unicode_ci = a.nisn COLLATE utf8mb4_unicode_ci', 'left', false)
            ->join('kelas k', 'k.id = s.id_kelas', 'left')
            ->where('a.nisn', $nisn)
            ->get()
            ->getRow();

        $data = [
            'title'  => 'Akun BelajarID',
            'active' => 'akun_belajar',
            'akun'   => $akun,
        ];

        return $this->compatView->render('siswa/layout/header', $data)
             . $this->compatView->render('siswa/layout/sidebar', $data)
             . $this->compatView->render('siswa/akun_belajar', $data)
             . $this->compatView->render('siswa/layout/footer', $data);
    }
}
