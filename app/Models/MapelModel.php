<?php

namespace App\Models;

use CodeIgniter\Model;

class MapelModel extends Model
{
    protected $table            = 'mapel';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['id_mapel', 'nama_mapel', 'kelompok', 'is_active'];
    protected $useTimestamps    = false;

    public function get_all(): array
    {
        return $this->orderBy('nama_mapel', 'ASC')->findAll();
    }
}
