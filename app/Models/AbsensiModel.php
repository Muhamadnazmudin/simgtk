<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table            = 'absensi';
    protected $primaryKey       = 'id_absensi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = false;
    protected $useTimestamps    = false;

    public function getAll(): array
    {
        return $this->db->table($this->table)
            ->select('absensi.*, kelas.nama as nama_kelas')
            ->join('kelas', 'kelas.id = absensi.id_kelas', 'left')
            ->orderBy('id_absensi', 'DESC')
            ->get()
            ->getResult();
    }

    public function insertAbsensi(array $data): int
    {
        $this->db->table($this->table)->insert($data);
        return (int) $this->db->insertID();
    }

    public function insertDetail(array $data): bool
    {
        return $this->db->table('absensi_detail')->insert($data);
    }

    public function getAbsenDetail(int $idAbsensi): array
    {
        return $this->db->table('absensi_detail')
            ->where('id_absensi', $idAbsensi)
            ->get()
            ->getResult();
    }

    public function getById(int $id): ?object
    {
        return $this->db->table($this->table)
            ->select('absensi.*, kelas.nama as nama_kelas')
            ->join('kelas', 'kelas.id = absensi.id_kelas', 'left')
            ->where('id_absensi', $id)
            ->get()
            ->getRow();
    }

    public function getOrCreateAbsensi(string $tanggal, int $idKelas, string $tahun): int
    {
        $cek = $this->db->table($this->table)
            ->where('tanggal', $tanggal)
            ->where('id_kelas', $idKelas)
            ->where('tahun_pelajaran', $tahun)
            ->get()
            ->getRow();

        if ($cek) {
            return (int) $cek->id_absensi;
        }

        $this->db->table($this->table)->insert([
            'tanggal'         => $tanggal,
            'id_kelas'        => $idKelas,
            'tahun_pelajaran' => $tahun,
        ]);

        return (int) $this->db->insertID();
    }

    public function laporanFilter($nama = null, $kelas = null, $dari = null, $sampai = null, $tahun = null, $ket = null): array
    {
        $builder = $this->db->table('absensi_detail d')
            ->select('
                a.tanggal,
                a.tahun_pelajaran,
                s.nama AS nama_siswa,
                k.nama AS nama_kelas,
                d.status,
                d.keterangan
            ')
            ->join('absensi a', 'a.id_absensi = d.id_absensi')
            ->join('siswa s', 's.id = d.id_siswa')
            ->join('kelas k', 'k.id = s.id_kelas', 'left');

        if (!empty($nama)) {
            $builder->like('s.nama', $nama);
        }

        if (!empty($kelas)) {
            $builder->where('k.id', $kelas);
        }

        if (!empty($ket)) {
            $builder->where('d.status', $ket);
        }

        if (!empty($tahun)) {
            $builder->where('a.tahun_pelajaran', $tahun);
        }

        if (!empty($dari) && !empty($sampai)) {
            $builder->where('a.tanggal >=', $dari);
            $builder->where('a.tanggal <=', $sampai);
        }

        return $builder->orderBy('a.tanggal', 'ASC')->get()->getResult();
    }
}
