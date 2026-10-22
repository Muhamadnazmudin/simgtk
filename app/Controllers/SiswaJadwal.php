<?php

namespace App\Controllers;

class SiswaJadwal extends BaseController
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

        $siswaId = $this->session->get('siswa_id');
        $siswa = $this->db->table('siswa')
            ->select('siswa.*, kelas.nama AS nama_kelas')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->where('siswa.id', $siswaId)
            ->get()
            ->getRow();

        $kelasId = $this->session->get('kelas_id') ?: ($siswa ? $siswa->id_kelas : null);

        $data['title']  = 'Jadwal Pelajaran';
        $data['active'] = 'jadwal';
        $data['siswa']  = $siswa;

        $data['jadwal'] = $this->db->table('jadwal_mengajar j')
            ->select('
                j.hari,
                js1.nama_jam AS nama_jam_awal,
                js2.nama_jam AS nama_jam_akhir,
                js1.jam_mulai   AS jam_mulai,
                js2.jam_selesai AS jam_selesai,
                m.nama_mapel,
                g.nama AS nama_guru
            ')
            ->join('mapel m', 'm.id_mapel = j.mapel_id', 'left')
            ->join('guru g', 'g.id = j.guru_id', 'left')
            ->join('jam_sekolah js1', 'js1.id_jam = j.jam_mulai_id', 'left')
            ->join('jam_sekolah js2', 'js2.id_jam = j.jam_selesai_id', 'left')
            ->where('j.rombel_id', $kelasId)
            ->orderBy('FIELD(j.hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu")')
            ->orderBy('js1.urutan', 'ASC')
            ->get()
            ->getResult();

        return $this->compatView->render('siswa/layout/header', $data)
             . $this->compatView->render('siswa/layout/sidebar', $data)
             . $this->compatView->render('siswa/jadwal/index', $data)
             . $this->compatView->render('siswa/layout/footer', $data);
    }
}
