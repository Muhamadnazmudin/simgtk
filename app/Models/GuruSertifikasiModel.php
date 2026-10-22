<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruSertifikasiModel extends Model
{
    protected $table = 'guru_sertifikasi';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'guru_id',
        'nama_sertifikasi',
        'nomor_sertifikasi',
        'lembaga_penerbit',
        'tgl_mulai_berlaku',
        'tgl_habis_berlaku',
        'keterangan',
    ];
    protected $returnType = 'object';

    public function getAll(): array
    {
        return $this->db->table('guru_sertifikasi gs')
            ->select('gs.*, g.nama AS nama_guru')
            ->join('guru g', 'g.id = gs.guru_id')
            ->orderBy('g.nama', 'ASC')
            ->get()
            ->getResult();
    }

    public function getByGuru(int $guruId): array
    {
        return $this->db->table('guru_sertifikasi gs')
            ->select('gs.*, g.nama AS nama_guru')
            ->join('guru g', 'g.id = gs.guru_id')
            ->where('gs.guru_id', $guruId)
            ->orderBy('gs.tgl_mulai_berlaku', 'DESC')
            ->get()
            ->getResult();
    }

    public function ownedBy(int $sertifikasiId, int $guruId): bool
    {
        return $this->db->table('guru_sertifikasi')
            ->where('id', $sertifikasiId)
            ->where('guru_id', $guruId)
            ->countAllResults() > 0;
    }
}
