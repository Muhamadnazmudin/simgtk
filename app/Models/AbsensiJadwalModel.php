<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiJadwalModel extends Model
{
    protected $table            = 'absensi_jadwal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'hari',
        'jam_masuk',
        'jam_pulang',
        'toleransi_terlambat',
        'created_at',
        'updated_at',
    ];

    public function get_all()
    {
        return $this->orderBy('id', 'ASC')->findAll();
    }

    public function update_jadwal($id, array $data)
    {
        return $this->update((int) $id, $data);
    }

    public function get_by_day(string $hari)
    {
        return $this->where('hari', $hari)->first();
    }
}
