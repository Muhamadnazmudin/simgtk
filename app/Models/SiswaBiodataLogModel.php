<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaBiodataLogModel extends Model
{
    protected $table            = 'siswa_biodata_log';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = false;
    protected $useTimestamps    = false;

    public function countSiswaSummary(string $search = '', ?int $kelasId = null): int
    {
        $builder = $this->db->table($this->table . ' l')
            ->select('l.siswa_id')
            ->join('siswa s', 's.id = l.siswa_id', 'left');

        if ($search !== '') {
            $builder->groupStart()
                ->like('s.nama', $search)
                ->orLike('s.nisn', $search)
                ->groupEnd();
        }

        if (!empty($kelasId)) {
            $builder->where('s.id_kelas', $kelasId);
        }

        return $builder->groupBy('l.siswa_id')->countAllResults(false);
    }

    public function getSiswaSummary(int $limit = 36, int $offset = 0, string $search = '', ?int $kelasId = null): array
    {
        $builder = $this->db->table($this->table . ' l')
            ->select('
                l.siswa_id,
                s.nama AS nama_siswa,
                s.nisn,
                k.nama AS nama_kelas,
                COUNT(l.id) AS jumlah_perubahan,
                MAX(l.changed_at) AS terakhir_diubah
            ')
            ->join('siswa s', 's.id = l.siswa_id', 'left')
            ->join('kelas k', 'k.id = s.id_kelas', 'left');

        if ($search !== '') {
            $builder->groupStart()
                ->like('s.nama', $search)
                ->orLike('s.nisn', $search)
                ->groupEnd();
        }

        if (!empty($kelasId)) {
            $builder->where('s.id_kelas', $kelasId);
        }

        return $builder->groupBy('l.siswa_id')
            ->orderBy('terakhir_diubah', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResult();
    }

    public function getBySiswa(int $siswaId): array
    {
        return $this->db->table($this->table)
            ->where('siswa_id', $siswaId)
            ->orderBy('changed_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function countAllSiswa(?int $kelasId = null): int
    {
        $builder = $this->db->table($this->table . ' l')
            ->select('l.siswa_id')
            ->join('siswa s', 's.id = l.siswa_id', 'left');

        if (!empty($kelasId)) {
            $builder->where('s.id_kelas', $kelasId);
        }

        return $builder->groupBy('l.siswa_id')->countAllResults(false);
    }

    public function countAllLog(?int $kelasId = null): int
    {
        $builder = $this->db->table($this->table . ' l')
            ->join('siswa s', 's.id = l.siswa_id', 'left');

        if (!empty($kelasId)) {
            $builder->where('s.id_kelas', $kelasId);
        }

        return $builder->countAllResults();
    }

    public function countToday(?int $kelasId = null): int
    {
        $today = date('Y-m-d');
        $builder = $this->db->table($this->table . ' l')
            ->join('siswa s', 's.id = l.siswa_id', 'left')
            ->where('l.changed_at >=', $today . ' 00:00:00')
            ->where('l.changed_at <=', $today . ' 23:59:59');

        if (!empty($kelasId)) {
            $builder->where('s.id_kelas', $kelasId);
        }

        return $builder->countAllResults();
    }
}
