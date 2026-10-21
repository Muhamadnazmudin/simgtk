<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruModel extends Model
{
    protected $table            = 'guru';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'nip',
        'nuptk',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'no_hp',
        'email',
        'foto',
        'status_kepegawaian',
        'jenis_ptk',
        'jabatan',
        'pendidikan_terakhir',
        'jurusan',
    ];
    protected $useTimestamps    = false;

    public function get_all(?int $limit = null, ?int $offset = null, string $search = ''): array
    {
        $builder = $this->builder();

        if (!empty($search)) {
            $builder->groupStart()
                ->like('nama', $search)
                ->orLike('nip', $search)
                ->groupEnd();
        }

        $builder->orderBy('nama', 'ASC');

        if ($limit !== null) {
            $builder->limit($limit, $offset ?? 0);
        }

        return $builder->get()->getResult();
    }

    public function count_all(string $search = ''): int
    {
        $builder = $this->builder();
        if (!empty($search)) {
            $builder->groupStart()
                ->like('nama', $search)
                ->orLike('nip', $search)
                ->groupEnd();
        }
        return $builder->countAllResults();
    }

    public function get_by_id(int $id): ?object
    {
        return $this->find($id);
    }

}
