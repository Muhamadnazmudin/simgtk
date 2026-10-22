<?php

namespace App\Models;

use CodeIgniter\Model;

class Vervalpd_model extends Model
{
    protected $table = 'siswa';

    public function laporan_walikelas($kelas_id)
    {
        return $this->db->table('kelas')
            ->select('
                kelas.nama AS nama_kelas,
                COUNT(siswa.id) AS total_siswa,
                SUM(CASE WHEN siswa.status_verval = 1 THEN 1 ELSE 0 END) AS valid,
                SUM(CASE WHEN siswa.status_verval = 2 THEN 1 ELSE 0 END) AS perbaikan,
                SUM(CASE WHEN siswa.status_verval IN (1,2) THEN 1 ELSE 0 END) AS sudah_verval,
                SUM(CASE WHEN siswa.status_verval = 0 THEN 1 ELSE 0 END) AS belum_verval
            ')
            ->join('siswa', 'siswa.id_kelas = kelas.id AND siswa.status = "aktif"', 'left')
            ->where('kelas.id', (int) $kelas_id)
            ->groupBy('kelas.id')
            ->get()
            ->getRow();
    }

    public function siswa_walikelas($kelas_id)
    {
        return $this->db->table('siswa')
            ->select('
                siswa.nisn,
                siswa.nama,
                siswa.status_verval,
                siswa.catatan_verval,
                kelas.nama AS nama_kelas
            ')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->where('siswa.id_kelas', (int) $kelas_id)
            ->where('siswa.status', 'aktif')
            ->orderBy('siswa.nama', 'ASC')
            ->get()
            ->getResult();
    }
}
