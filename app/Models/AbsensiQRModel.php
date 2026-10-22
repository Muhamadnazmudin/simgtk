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

    public function getRekapSemester(string $nis, string $tahunAjaran, int $semester): object
    {
        $parts = explode('/', $tahunAjaran);
        $thAwal = $parts[0] ?? date('Y');

        if ($semester === 1) {
            $tgl1 = $thAwal . '-07-01';
            $tgl2 = $thAwal . '-12-31';
        } else {
            $thAkhir = $parts[1] ?? (string) ((int) $thAwal + 1);
            $tgl1 = $thAkhir . '-01-01';
            $tgl2 = $thAkhir . '-06-30';
        }

        $row = $this->db->table($this->table)
            ->select('
                SUM(CASE WHEN kehadiran = "H" THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN kehadiran = "S" THEN 1 ELSE 0 END) AS sakit,
                SUM(CASE WHEN kehadiran = "I" THEN 1 ELSE 0 END) AS izin,
                SUM(CASE WHEN kehadiran = "A" THEN 1 ELSE 0 END) AS alpha,
                SUM(CASE WHEN status = "Terlambat" THEN 1 ELSE 0 END) AS terlambat
            ')
            ->where('nis', $nis)
            ->where('tanggal >=', $tgl1)
            ->where('tanggal <=', $tgl2)
            ->get()
            ->getRow();

        return $row ?? (object) ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0, 'terlambat' => 0];
    }
}

