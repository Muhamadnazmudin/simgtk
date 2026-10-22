<?php

namespace App\Models;

use CodeIgniter\Model;

class SekolahModel extends Model
{
    protected $table            = 'sekolah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_sekolah',
        'npsn',
        'alamat',
        'desa',
        'kecamatan',
        'kabupaten',
        'latitude',
        'longitude',
        'logo',
        'nama_kepala_sekolah',
        'nip_kepala_sekolah',
        'waka_kurikulum_id',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getSingle()
    {
        return $this->db->table($this->table . ' s')
            ->select('s.*, g.nama AS nama_waka_kurikulum')
            ->join('guru g', 'g.id = s.waka_kurikulum_id', 'left')
            ->get()
            ->getRow();
    }

    public function setRoleWakakur(int $guruId): void
    {
        $user = $this->db->table('users')->where('guru_id', $guruId)->get()->getRow();
        if ($user) {
            $this->db->table('users')->where('id', $user->id)->update(['role_id' => 4]);
        }
    }

    public function resetOldWakakur(?int $exceptGuruId = null): void
    {
        $builder = $this->db->table('users')->where('role_id', 4);
        if ($exceptGuruId !== null) {
            $builder->where('guru_id !=', $exceptGuruId);
        }
        $builder->update(['role_id' => 3]);
    }
}
