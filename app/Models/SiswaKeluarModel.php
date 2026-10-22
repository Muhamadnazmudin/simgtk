<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaKeluarModel extends Model
{
    protected $table = 'mutasi';
    protected $primaryKey = 'id';
    protected $returnType = 'object';

    public function getData(): array
    {
        $subquery = "
            SELECT m1.siswa_id
            FROM mutasi m1
            INNER JOIN (
                SELECT siswa_id, MAX(id) AS max_id
                FROM mutasi
                GROUP BY siswa_id
            ) m2 ON m1.id = m2.max_id
            WHERE m1.status_mutasi = 'dibatalkan'
        ";

        return $this->db->table('mutasi')
            ->select("
                mutasi.*,
                siswa.nis,
                siswa.nisn,
                siswa.nama,
                kelas.nama AS nama_kelas,
                tahun_ajaran.tahun
            ")
            ->join('siswa', 'siswa.id = mutasi.siswa_id')
            ->join('kelas', 'kelas.id = mutasi.kelas_asal_id', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = mutasi.tahun_id', 'left')
            ->where('mutasi.jenis', 'keluar')
            ->where("mutasi.siswa_id NOT IN ({$subquery})", null, false)
            ->orderBy('mutasi.tanggal', 'DESC')
            ->get()
            ->getResult();
    }
}
