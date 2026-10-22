<?php

namespace App\Models;

use CodeIgniter\Model;

class TendikModel extends Model
{
    protected $table            = 'tendik';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = false;
    protected $useTimestamps    = false;

    public function getAll(?int $limit = null, ?int $offset = null, string $search = ''): array
    {
        $builder = $this->db->table($this->table);

        if (!empty($search)) {
            $builder->groupStart()
                ->like('nama', $search)
                ->orLike('nuptk', $search)
                ->orLike('nip', $search)
                ->orLike('jenis_ptk', $search)
                ->orLike('status_kepegawaian', $search)
                ->groupEnd();
        }

        $builder->orderBy('nama', 'ASC');

        if ($limit !== null) {
            $builder->limit($limit, $offset ?: 0);
        }

        return $builder->get()->getResult();
    }

    public function countSearch(string $search = ''): int
    {
        $builder = $this->db->table($this->table);

        if (!empty($search)) {
            $builder->groupStart()
                ->like('nama', $search)
                ->orLike('nuptk', $search)
                ->orLike('nip', $search)
                ->orLike('jenis_ptk', $search)
                ->orLike('status_kepegawaian', $search)
                ->groupEnd();
        }

        return $builder->countAllResults();
    }

    public function existsNuptk(string $nuptk, ?int $excludeId = null): bool
    {
        if (empty($nuptk)) {
            return false;
        }

        $builder = $this->db->table($this->table)->where('nuptk', $nuptk);
        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    public function importUpsert(array $data): array
    {
        if (empty($data['nuptk'])) {
            return ['status' => false, 'action' => 'skip', 'message' => 'NUPTK kosong'];
        }

        $nuptk = trim((string) $data['nuptk']);
        $existing = $this->db->table($this->table)->where('nuptk', $nuptk)->get()->getRow();

        if (!$existing) {
            $this->db->table($this->table)->insert($data);
            return [
                'status'  => $this->db->affectedRows() > 0,
                'action'  => 'insert',
                'message' => 'Data baru ditambahkan',
            ];
        }

        $changed = false;
        foreach ($data as $field => $value) {
            if ($field === 'id') continue;
            $oldValue = $existing->$field ?? null;
            if ($value === '') $value = null;
            if ($oldValue === '') $oldValue = null;
            if ((string) $oldValue !== (string) $value) {
                $changed = true;
                break;
            }
        }

        if (!$changed) {
            return ['status' => true, 'action' => 'same', 'message' => 'Data tidak berubah'];
        }

        $this->db->table($this->table)->where('id', $existing->id)->update($data);
        return [
            'status'  => true,
            'action'  => 'update',
            'message' => 'Data diperbarui',
        ];
    }
}
