<?php

namespace App\Models;

use CodeIgniter\Model;

class BacaanModel extends Model
{
    protected $table = 'tbl_bacaan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'judul',
        'kelas',
        'mapel',
        'cover',
        'drive_file_id',
        'status',
    ];
    protected $returnType = 'object';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getAllAdmin(): array
    {
        return $this->orderBy('id', 'DESC')->findAll();
    }

    public function getActive(): array
    {
        return $this->where('status', 'aktif')
            ->orderBy('judul', 'ASC')
            ->findAll();
    }

    public function getById(int $id): ?object
    {
        return $this->where('id', $id)
            ->where('status', 'aktif')
            ->first();
    }

    public function getFiltered(int $limit, int $offset, ?string $kelas = null, ?string $mapel = null): array
    {
        $builder = $this->where('status', 'aktif');
        if (!empty($kelas)) {
            $builder->where('kelas', $kelas);
        }
        if (!empty($mapel)) {
            $builder->where('mapel', $mapel);
        }
        return $builder->orderBy('created_at', 'DESC')
            ->findAll($limit, $offset);
    }

    public function countFiltered(?string $kelas = null, ?string $mapel = null): int
    {
        $builder = $this->where('status', 'aktif');
        if (!empty($kelas)) {
            $builder->where('kelas', $kelas);
        }
        if (!empty($mapel)) {
            $builder->where('mapel', $mapel);
        }
        return $builder->countAllResults();
    }

    public function getMapelUnik(): array
    {
        return $this->db->table($this->table)
            ->select('mapel')
            ->where('mapel IS NOT NULL')
            ->where('mapel !=', '')
            ->groupBy('mapel')
            ->orderBy('mapel', 'ASC')
            ->get()
            ->getResult();
    }
}

