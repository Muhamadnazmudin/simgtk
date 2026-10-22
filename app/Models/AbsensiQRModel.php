<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiQRModel extends Model
{
    protected $table            = 'absensi_qr';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = false;
    protected $useTimestamps    = false;

    public function getSiswaByToken(string $token): ?object
    {
        $token = preg_replace('/[^0-9]/', '', trim($token));
        return $this->db->table('siswa')->where('nisn', $token)->get()->getRow();
    }

    public function getAbsenHariIni(string $nis, string $tanggal): ?object
    {
        return $this->db->table($this->table)
            ->where('nis', $nis)
            ->where('tanggal', $tanggal)
            ->get()
            ->getRow();
    }

    public function getAllJoin(): array
    {
        return $this->db->table($this->table . ' a')
            ->select('a.*, s.nama AS nama_siswa, k.nama AS nama_kelas')
            ->join('siswa s', 's.nis = a.nis', 'left')
            ->join('kelas k', 'k.id = s.id_kelas', 'left')
            ->orderBy('a.tanggal', 'DESC')
            ->orderBy('a.jam_masuk', 'DESC')
            ->get()
            ->getResult();
    }
}
