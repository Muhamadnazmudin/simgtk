<?php

namespace App\Models;

use CodeIgniter\Model;

class AkunBelajarModel extends Model
{
    protected $table = 'akun_belajar_siswa';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nisn', 'email_belajar', 'password_default', 'status', 'sudah_subscribe'];
    protected $returnType = 'object';

    public function listSiswa(?string $nama = null, $kelasId = null, int $limit = 20, int $offset = 0): array
    {
        $builder = $this->db->table('siswa s')
            ->select('
                s.nisn,
                s.nama,
                k.nama AS nama_kelas,
                a.email_belajar,
                a.password_default,
                a.status,
                a.sudah_subscribe
            ')
            ->join('kelas k', 'k.id = s.id_kelas', 'left')
            ->join('akun_belajar_siswa a', 'a.nisn COLLATE utf8mb4_unicode_ci = s.nisn COLLATE utf8mb4_unicode_ci', 'left', false)
            ->where('s.status', 'aktif');

        if (!empty($nama)) {
            $builder->like('s.nama', $nama);
        }
        if (!empty($kelasId)) {
            $builder->where('s.id_kelas', (int) $kelasId);
        }

        return $builder->orderBy('s.nama', 'ASC')->limit($limit, $offset)->get()->getResult();
    }

    public function countSiswa(?string $nama = null, $kelasId = null): int
    {
        $builder = $this->db->table('siswa s')->where('s.status', 'aktif');
        if (!empty($nama)) {
            $builder->like('s.nama', $nama);
        }
        if (!empty($kelasId)) {
            $builder->where('s.id_kelas', (int) $kelasId);
        }
        return $builder->countAllResults();
    }

    public function saveAkun(array $data): void
    {
        $cek = $this->db->table($this->table)->where('nisn', $data['nisn'])->get()->getRow();
        if ($cek) {
            $this->db->table($this->table)->where('nisn', $data['nisn'])->update($data);
        } else {
            $this->db->table($this->table)->insert($data);
        }
    }

    public function deleteByNisn(string $nisn): void
    {
        $this->db->table($this->table)->where('nisn', $nisn)->delete();
    }
}
