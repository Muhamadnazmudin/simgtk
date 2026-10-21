<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table            = 'kelas';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['nama', 'tingkat', 'jurusan', 'wali_kelas_id', 'ketua_kelas_id'];
    protected $useTimestamps    = false;

    public function get_all(?int $limit = null, ?int $offset = null): array
    {
        $builder = $this->select('kelas.*, guru.nama AS wali_nama, siswa.nama AS ketua_nama')
            ->join('guru', 'guru.id = kelas.wali_kelas_id', 'left')
            ->join('siswa', 'siswa.id = kelas.ketua_kelas_id', 'left')
            ->orderBy('kelas.nama', 'ASC');

        if ($limit !== null) {
            $builder->limit($limit, $offset ?? 0);
        }

        return $builder->findAll();
    }

    public function get_by_id(int $id): ?object
    {
        return $this->select('kelas.*, guru.nama AS wali_nama, siswa.nama AS ketua_nama')
            ->join('guru', 'guru.id = kelas.wali_kelas_id', 'left')
            ->join('siswa', 'siswa.id = kelas.ketua_kelas_id', 'left')
            ->where('kelas.id', $id)
            ->first();
    }

    public function get_guru_list(): array
    {
        $sql = "
            SELECT g.*
            FROM guru g
            LEFT JOIN kelas k ON k.wali_kelas_id = g.id
            WHERE k.wali_kelas_id IS NULL
            GROUP BY g.id
            ORDER BY g.nama ASC
        ";
        return $this->db->query($sql)->getResult();
    }

    public function get_guru_list_edit(int $idKelas): array
    {
        $kelas = $this->get_by_id($idKelas);
        $waliNow = $kelas ? (int) $kelas->wali_kelas_id : 0;

        $sql = "
            SELECT g.*
            FROM guru g
            LEFT JOIN kelas k ON k.wali_kelas_id = g.id
            WHERE k.wali_kelas_id IS NULL
               OR g.id = ?
            GROUP BY g.id
            ORDER BY g.nama ASC
        ";
        return $this->db->query($sql, [$waliNow])->getResult();
    }

    public function get_all_simple(): array
    {
        return $this->orderBy('nama', 'ASC')->findAll();
    }

}
