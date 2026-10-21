<?php

namespace App\Controllers;

/**
 * Controller SiswaDashboard - CodeIgniter 4
 */
class SiswaDashboard extends BaseController
{
    private function cekLogin()
    {
        if (!$this->session->get('siswa_login')) {
            header('Location: ' . base_url('siswa-login'));
            exit;
        }
    }

    private function getSiswa(): ?object
    {
        $siswaId = $this->session->get('siswa_id');
        if (!$siswaId) {
            return null;
        }

        return $this->db->table('siswa')
            ->select('siswa.*, kelas.nama AS nama_kelas, tahun_ajaran.tahun AS tahun_ajaran')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.tahun_id', 'left')
            ->where('siswa.id', $siswaId)
            ->get()
            ->getRow();
    }

    public function index()
    {
        $this->cekLogin();

        $data['siswa'] = $this->getSiswa();
        if (!$data['siswa']) {
            return redirect()->to('/SiswaAuth/logout');
        }

        $namaKelas = strtoupper(trim((string) $data['siswa']->nama_kelas));
        $isXii = ($namaKelas === 'XII' || str_starts_with($namaKelas, 'XII '));

        $data['is_xii']    = $isXii;
        $data['tka_notif'] = false;
        $data['tka_data']  = null;

        if ($isXii) {
            $tka = $this->db->table('tka_siswa')
                ->select('id, siswa_id, tahun_id, mapel_1_id, mapel_2_id')
                ->where('siswa_id', (int) $data['siswa']->id)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get()
                ->getRow();

            if (!$tka || empty($tka->mapel_1_id) || empty($tka->mapel_2_id)) {
                $data['tka_notif'] = true;
            } else {
                $data['tka_data'] = $tka;
            }
        }

        $data['ketua_kelas'] = $this->db->table('kelas')
            ->select('kelas.id, kelas.nama')
            ->where('kelas.ketua_kelas_id', $data['siswa']->id)
            ->get()
            ->getRow();

        $kelasId = $this->session->get('kelas_id') ?: $data['siswa']->id_kelas;
        $hariIni = $this->hariIni();

        $data['hari_ini'] = $hariIni;
        $data['jadwal_hari_ini'] = $this->db->table('jadwal_mengajar j')
            ->select('j.*, m.nama as mapel_nama, g.nama as guru_nama')
            ->join('mapel m', 'm.id = j.mapel_id', 'left')
            ->join('guru g', 'g.id = j.guru_id', 'left')
            ->where('j.kelas_id', $kelasId)
            ->where('j.hari', $hariIni)
            ->orderBy('j.jam_mulai', 'ASC')
            ->get()
            ->getResult();

        $data['active'] = 'dashboard';
        $data['title']  = 'Dashboard Siswa';

        return view('siswa/layout/header', $data)
             . view('siswa/layout/sidebar', $data)
             . view('siswa/dashboard', $data)
             . view('siswa/layout/footer', $data);
    }

    private function hariIni(): string
    {
        $map = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        return $map[date('l')] ?? date('l');
    }
}
