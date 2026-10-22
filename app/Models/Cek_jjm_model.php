<?php

namespace App\Models;

use CodeIgniter\Model;

class Cek_jjm_model extends Model
{
    protected $table = 'rekap_jjm';

    public function get_by_guru($guru_id)
    {
        return $this->db->table('rekap_jjm r')
            ->select('r.file_jjm, g.nama')
            ->join('guru g', 'g.id = r.guru_id')
            ->where('r.guru_id', (int) $guru_id)
            ->get()
            ->getRow();
    }
}
