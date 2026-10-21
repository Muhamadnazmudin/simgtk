<?php

namespace App\Models;

use CodeIgniter\Model;

class TahunModel extends Model
{
    protected $table            = 'tahun_ajaran';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['tahun', 'semester', 'aktif'];
    protected $useTimestamps    = false;

    public function get_all(): array
    {
        return $this->orderBy('tahun', 'DESC')->findAll();
    }

    public function get_aktif(): ?object
    {
        return $this->where('aktif', 1)->first();
    }

    public function reset_active(): bool
    {
        return $this->builder()->update(['aktif' => 0]);
    }
}
