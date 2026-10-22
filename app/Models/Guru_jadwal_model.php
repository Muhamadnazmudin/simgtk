<?php

namespace App\Models;

use CodeIgniter\Model;

class Guru_jadwal_model extends Model
{
    protected $table = 'jadwal_mengajar';

    public function get_by_guru($guru_id)
    {
        return $this->db->table('jadwal_mengajar j')
            ->select('
                j.hari,
                k.nama AS nama_kelas,
                m.nama_mapel,
                js1.nama_jam AS jam_awal,
                js1.jam_mulai AS jam_mulai,
                js2.nama_jam AS jam_akhir,
                js2.jam_selesai AS jam_selesai
            ')
            ->join('kelas k', 'k.id = j.rombel_id', 'left')
            ->join('mapel m', 'm.id_mapel = j.mapel_id', 'left')
            ->join('jam_sekolah js1', 'js1.id_jam = j.jam_mulai_id', 'left')
            ->join('jam_sekolah js2', 'js2.id_jam = j.jam_selesai_id', 'left')
            ->where('j.guru_id', (int) $guru_id)
            ->orderBy('j.hari', 'ASC')
            ->orderBy('js1.urutan', 'ASC')
            ->get()
            ->getResult();
    }

    public function get_jadwal_hari_ini($guru_id, $hari)
    {
        return $this->db->table('jadwal_mengajar j')
            ->select('
                j.id_jadwal AS jadwal_id,
                j.hari,
                k.nama AS nama_kelas,
                m.nama_mapel,
                js1.nama_jam AS jam_awal,
                js1.jam_mulai AS jam_mulai,
                js2.nama_jam AS jam_akhir,
                js2.jam_selesai AS jam_selesai
            ')
            ->join('kelas k', 'k.id = j.rombel_id', 'left')
            ->join('mapel m', 'm.id_mapel = j.mapel_id', 'left')
            ->join('jam_sekolah js1', 'js1.id_jam = j.jam_mulai_id', 'left')
            ->join('jam_sekolah js2', 'js2.id_jam = j.jam_selesai_id', 'left')
            ->where('j.guru_id', (int) $guru_id)
            ->where('j.hari', $hari)
            ->orderBy('js1.urutan', 'ASC')
            ->get()
            ->getResult();
    }

    public function get_by_id($jadwal_id)
    {
        return $this->db->table('jadwal_mengajar j')
            ->select('
                j.*,
                js1.jam_mulai,
                js2.jam_selesai
            ')
            ->join('jam_sekolah js1', 'js1.id_jam = j.jam_mulai_id', 'left')
            ->join('jam_sekolah js2', 'js2.id_jam = j.jam_selesai_id', 'left')
            ->where('j.id_jadwal', (int) $jadwal_id)
            ->get()
            ->getRow();
    }
}
