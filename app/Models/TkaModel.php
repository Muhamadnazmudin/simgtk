<?php

namespace App\Models;

use CodeIgniter\Model;

class TkaModel extends Model
{
    protected $table = 'tka_siswa';
    protected $primaryKey = 'id';
    protected $allowedFields = ['siswa_id', 'tahun_id', 'mapel_1_id', 'mapel_2_id'];
    protected $returnType = 'object';

    public function getMapelAktif(): array
    {
        return $this->db->table('tka_mapel')
            ->where('aktif', 1)
            ->orderBy('nama_mapel', 'ASC')
            ->get()
            ->getResult();
    }

    public function getMapelProgramKeahlian()
    {
        return $this->db->table('tka_mapel')
            ->where('kode', 'PK')
            ->where('aktif', 1)
            ->limit(1)
            ->get()
            ->getRow();
    }

    public function getMapelPilihan(): array
    {
        return $this->db->table('tka_mapel')
            ->where('aktif', 1)
            ->where('kode !=', 'PK')
            ->orderBy('nama_mapel', 'ASC')
            ->get()
            ->getResult();
    }

    public function getPilihan(int $siswaId, int $tahunId)
    {
        return $this->db->table('tka_siswa ts')
            ->select('
                ts.*,
                m1.nama_mapel AS mapel_1,
                m1.kode AS kode_mapel_1,
                m2.nama_mapel AS mapel_2,
                m2.kode AS kode_mapel_2
            ')
            ->join('tka_mapel m1', 'm1.id = ts.mapel_1_id', 'left')
            ->join('tka_mapel m2', 'm2.id = ts.mapel_2_id', 'left')
            ->where('ts.siswa_id', $siswaId)
            ->where('ts.tahun_id', $tahunId)
            ->limit(1)
            ->get()
            ->getRow();
    }

    public function getById(int $id)
    {
        return $this->db->table('tka_siswa ts')
            ->select('
                ts.*,
                s.id AS siswa_id,
                s.nama,
                s.nisn,
                k.nama AS nama_kelas,
                ta.tahun AS tahun_ajaran,
                m1.nama_mapel AS mapel_1,
                m1.kode AS kode_mapel_1,
                m2.nama_mapel AS mapel_2,
                m2.kode AS kode_mapel_2
            ')
            ->join('siswa s', 's.id = ts.siswa_id', 'left')
            ->join('kelas k', 'k.id = s.id_kelas', 'left')
            ->join('tahun_ajaran ta', 'ta.id = s.tahun_id', 'left')
            ->join('tka_mapel m1', 'm1.id = ts.mapel_1_id', 'left')
            ->join('tka_mapel m2', 'm2.id = ts.mapel_2_id', 'left')
            ->where('ts.id', $id)
            ->limit(1)
            ->get()
            ->getRow();
    }

    public function getSiswaTka(int $siswaId)
    {
        return $this->db->table('siswa')
            ->select('
                siswa.id,
                siswa.nama,
                siswa.nisn,
                siswa.id_kelas,
                siswa.tahun_id,
                siswa.status,
                kelas.nama AS nama_kelas,
                tahun_ajaran.tahun AS tahun_ajaran
            ')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.tahun_id', 'left')
            ->where('siswa.id', $siswaId)
            ->limit(1)
            ->get()
            ->getRow();
    }
}
