<?php

namespace App\Models;

use CodeIgniter\Model;

class RekapJjmModel extends Model
{
    protected $table = 'rekap_jjm';
    protected $primaryKey = 'id';
    protected $allowedFields = ['guru_id', 'file_jjm'];
    protected $returnType = 'object';

    public function getAllGuru(): array
    {
        return $this->db->table('guru g')
            ->select('g.id, g.nama, g.email, g.status_kepegawaian, r.file_jjm')
            ->join('rekap_jjm r', 'r.guru_id = g.id', 'left')
            ->orderBy('g.nama', 'ASC')
            ->get()
            ->getResult();
    }

    public function getByGuru(int $guruId)
    {
        return $this->db->table('rekap_jjm')
            ->where('guru_id', $guruId)
            ->get()
            ->getRow();
    }
}
