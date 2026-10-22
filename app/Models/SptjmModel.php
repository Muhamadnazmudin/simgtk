<?php

namespace App\Models;

use CodeIgniter\Model;

class SptjmModel extends Model
{
    protected $table = 'sptjm_dapodik';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'tahun_ajaran',
        'jenis_sptjm',
        'tanggal_cut_off',
        'tanggal_sptjm',
        'file_sptjm',
        'nama_file_sptjm',
        'file_excel',
        'nama_file_excel',
        'keterangan',
        'created_at',
        'updated_at',
    ];
    protected $returnType = 'object';

    public function getAll(): array
    {
        return $this->orderBy('tahun_ajaran', 'DESC')
            ->orderBy('jenis_sptjm', 'ASC')
            ->orderBy('tanggal_cut_off', 'DESC')
            ->findAll();
    }

    public function getById(int $id)
    {
        return $this->find($id);
    }

    public function getTahunAjaran(): array
    {
        return $this->db->table($this->table)
            ->select('tahun_ajaran')
            ->distinct()
            ->orderBy('tahun_ajaran', 'DESC')
            ->get()
            ->getResult();
    }

    public function filter(?string $tahunAjaran = null, ?string $jenisSptjm = null): array
    {
        $builder = $this->db->table($this->table);

        if (!empty($tahunAjaran)) {
            $builder->where('tahun_ajaran', $tahunAjaran);
        }
        if (!empty($jenisSptjm)) {
            $builder->where('jenis_sptjm', $jenisSptjm);
        }

        return $builder->orderBy('tahun_ajaran', 'DESC')
            ->orderBy('tanggal_cut_off', 'DESC')
            ->get()
            ->getResult();
    }
}
