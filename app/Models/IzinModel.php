<?php

namespace App\Models;

use CodeIgniter\Model;

class IzinModel extends Model
{
    protected $table            = 'izin_keluar';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = false;
    protected $useTimestamps    = false;

    public function getSiswaByToken(string $token): ?object
    {
        return $this->db->table('siswa')
            ->select('siswa.*, kelas.nama AS kelas_nama, kelas.id AS kelas_id')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->where('siswa.nisn', $token)
            ->orWhere('siswa.token_qr', $token)
            ->get()
            ->getRow();
    }

    public function izinAktif(int $siswaId): ?object
    {
        return $this->db->table($this->table)
            ->where('siswa_id', $siswaId)
            ->where('status', 'keluar')
            ->orderBy('id', 'DESC')
            ->get()
            ->getRow();
    }

    public function getIzinByTokenKembali(string $token): ?object
    {
        return $this->db->table($this->table)
            ->where('token_kembali', $token)
            ->get()
            ->getRow();
    }

    public function setKembali(int $id): bool
    {
        return $this->db->table($this->table)
            ->where('id', $id)
            ->update([
                'jam_masuk' => date('Y-m-d H:i:s'),
                'status'    => 'kembali',
            ]);
    }
}
