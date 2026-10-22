<?php

namespace App\Models;

use CodeIgniter\Model;

class BukuTamuModel extends Model
{
    protected $table            = 'buku_tamu';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = false;
    protected $useTimestamps    = false;

    public function countHariIni(): int
    {
        $start = date('Y-m-d 00:00:00');
        $end   = date('Y-m-d 23:59:59');

        return $this->db->table($this->table)
            ->where('tanggal >=', $start)
            ->where('tanggal <=', $end)
            ->countAllResults();
    }

    public function getHariIni(int $limit, int $offset): array
    {
        $start = date('Y-m-d 00:00:00');
        $end   = date('Y-m-d 23:59:59');

        return $this->db->table($this->table)
            ->where('tanggal >=', $start)
            ->where('tanggal <=', $end)
            ->orderBy('tanggal', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResult();
    }
}
