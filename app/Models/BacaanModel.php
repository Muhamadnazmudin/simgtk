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
}
