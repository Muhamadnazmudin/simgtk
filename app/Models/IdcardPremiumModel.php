<?php

namespace App\Models;

use CodeIgniter\Model;

class IdcardPremiumModel extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id';
    protected $returnType = 'object';

    private function baseBuilder()
    {
        return $this->db->table('siswa')
            ->select('siswa.*, kelas.nama AS nama_kelas, tahun_ajaran.tahun')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.tahun_id', 'left')
            ->where('siswa.status', 'aktif');
    }

    public function getList(?string $nama = null, $kelas = null): array
    {
        $builder = $this->baseBuilder();

        if (!empty($nama)) {
            $builder->groupStart()
                ->like('siswa.nama', $nama)
                ->orLike('siswa.nis', $nama)
                ->orLike('siswa.nisn', $nama)
                ->groupEnd();
        }

        if (!empty($kelas)) {
            $builder->where('siswa.id_kelas', $kelas);
        }

        return $builder->orderBy('kelas.nama', 'ASC')
            ->orderBy('siswa.nama', 'ASC')
            ->get()
            ->getResult();
    }

    public function getById(int $id)
    {
        return $this->baseBuilder()
            ->where('siswa.id', $id)
            ->get()
            ->getRow();
    }

    public function getByKelas($kelasId): array
    {
        return $this->baseBuilder()
            ->where('siswa.id_kelas', $kelasId)
            ->orderBy('siswa.nama', 'ASC')
            ->get()
            ->getResult();
    }

    public function getKelas(): array
    {
        return $this->db->table('kelas')
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResult();
    }
}
