<?php

namespace App\Controllers;

use App\Models\LaporanPembelajaranSiswaModel;

class SiswaLaporanPembelajaran extends BaseController
{
    private function cekLogin()
    {
        if (!$this->session->get('siswa_login')) {
            header('Location: ' . base_url('siswa-login'));
            exit;
        }
    }

    private function cekKetua(): ?object
    {
        $siswaId = (int) $this->session->get('siswa_id');
        $kelas = $this->db->table('kelas')
            ->where('ketua_kelas_id', $siswaId)
            ->get()
            ->getRow();

        if (!$kelas) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Akses ditolak: Anda bukan ketua kelas.');
        }

        return $kelas;
    }

    public function index()
    {
        $this->cekLogin();
        $kelas = $this->cekKetua();

        $model = new LaporanPembelajaranSiswaModel();

        $data['active']  = 'laporan_pembelajaran';
        $data['title']   = 'Laporan Pembelajaran';
        $data['kelas']   = $kelas;
        $data['laporan'] = $model->getAll(['kelas_id' => $kelas->id]);

        $data['guru']  = $this->db->table('guru')->orderBy('nama', 'ASC')->get()->getResult();
        $data['mapel'] = $this->db->table('mapel')->orderBy('nama_mapel', 'ASC')->get()->getResult();

        return $this->compatView->render('siswa/layout/header', $data)
             . $this->compatView->render('siswa/layout/sidebar', $data)
             . $this->compatView->render('siswa/laporan_pembelajaran', $data)
             . $this->compatView->render('siswa/layout/footer', $data);
    }

    public function simpan()
    {
        $this->cekLogin();
        $kelas = $this->cekKetua();

        $this->db->table('laporan_pembelajaran_siswa')->insert([
            'kelas_id'       => $kelas->id,
            'siswa_id'       => (int) $this->session->get('siswa_id'),
            'hari'           => $this->request->getPost('hari'),
            'jam_mulai_id'   => (int) $this->request->getPost('jam_mulai_id'),
            'jam_selesai_id' => (int) $this->request->getPost('jam_selesai_id'),
            'mapel_id'       => (int) $this->request->getPost('mapel_id'),
            'guru_id'        => (int) $this->request->getPost('guru_id'),
            'alasan'         => $this->request->getPost('alasan'),
            'tanggal'        => date('Y-m-d'),
        ]);

        $this->session->setFlashdata('success', 'Laporan berhasil dikirim');
        return redirect()->to('/SiswaLaporanPembelajaran');
    }

    public function ajax_jam_by_hari()
    {
        $this->cekLogin();
        $this->cekKetua();

        $hari = trim((string) $this->request->getGet('hari'));
        if ($hari === '') {
            return $this->response->setJSON([]);
        }

        $jam = $this->db->table('jam_sekolah')
            ->select('id_jam, nama_jam, jam_mulai, jam_selesai, urutan')
            ->where('hari', $hari)
            ->where('is_active', 1)
            ->orderBy('urutan', 'ASC')
            ->get()
            ->getResult();

        return $this->response->setJSON($jam);
    }
}
