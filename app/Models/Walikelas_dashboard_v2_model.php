<?php

namespace App\Models;

use CodeIgniter\Model;

class Walikelas_dashboard_v2_model extends Model
{
    protected $table = 'absensi';

    public function getKelas($kelas_id)
    {
        return $this->db->table('kelas')
            ->where('id', (int) $kelas_id)
            ->get()
            ->getRow();
    }

    public function getTotalSiswa($kelas_id)
    {
        $q = $this->db->table('siswa')
            ->select("
                COUNT(*) as total,
                SUM(CASE WHEN jk='L' THEN 1 ELSE 0 END) as l,
                SUM(CASE WHEN jk='P' THEN 1 ELSE 0 END) as p
            ", false)
            ->where('id_kelas', (int) $kelas_id)
            ->where('status', 'aktif')
            ->get()
            ->getRow();

        return [
            'total' => (int) ($q->total ?? 0),
            'l'     => (int) ($q->l ?? 0),
            'p'     => (int) ($q->p ?? 0),
        ];
    }

    public function getLastTanggal($kelas_id)
    {
        $row = $this->db->table('absensi')
            ->select('tanggal')
            ->where('id_kelas', (int) $kelas_id)
            ->orderBy('tanggal', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();

        return $row ? $row->tanggal : date('Y-m-d');
    }

    public function getHariIni($kelas_id)
    {
        $tanggal = date('Y-m-d');

        $total = $this->db->table('siswa')
            ->where('id_kelas', (int) $kelas_id)
            ->where('status', 'aktif')
            ->countAllResults();

        $q = $this->db->table('absensi a')
            ->select("
                SUM(CASE WHEN LOWER(d.status)='izin' THEN 1 ELSE 0 END) AS I,
                SUM(CASE WHEN LOWER(d.status)='sakit' THEN 1 ELSE 0 END) AS S,
                SUM(CASE WHEN LOWER(d.status)='alpa' THEN 1 ELSE 0 END) AS A
            ", false)
            ->join('absensi_detail d', 'd.id_absensi = a.id_absensi')
            ->join('siswa s', 's.id = d.id_siswa')
            ->where('a.id_kelas', (int) $kelas_id)
            ->where('a.tanggal', $tanggal)
            ->get()
            ->getRow();

        $izin  = (int) ($q->I ?? 0);
        $sakit = (int) ($q->S ?? 0);
        $alpa  = (int) ($q->A ?? 0);

        $hadir = $total - ($izin + $sakit + $alpa);
        if ($hadir < 0) {
            $hadir = 0;
        }

        return [
            'H' => $hadir,
            'I' => $izin,
            'S' => $sakit,
            'A' => $alpa,
        ];
    }

    public function getBulan($kelas_id, $tanggal = null)
    {
        if (!$tanggal) {
            $tanggal = $this->getLastTanggal($kelas_id);
        }

        $bulan = date('m', strtotime($tanggal));
        $tahun = date('Y', strtotime($tanggal));
        $awalBulan = "{$tahun}-{$bulan}-01";
        $akhirBulan = date('Y-m-t', strtotime($awalBulan));

        $q = $this->db->table('absensi a')
            ->select("
                SUM(CASE WHEN LOWER(d.status)='hadir' THEN 1 ELSE 0 END) AS H,
                SUM(CASE WHEN LOWER(d.status)='izin' THEN 1 ELSE 0 END) AS I,
                SUM(CASE WHEN LOWER(d.status)='sakit' THEN 1 ELSE 0 END) AS S,
                SUM(CASE WHEN LOWER(d.status)='alpa' THEN 1 ELSE 0 END) AS A
            ", false)
            ->join('absensi_detail d', 'd.id_absensi = a.id_absensi')
            ->where('a.id_kelas', (int) $kelas_id)
            ->where('a.tanggal >=', $awalBulan)
            ->where('a.tanggal <=', $akhirBulan)
            ->get()
            ->getRow();

        return [
            'H' => (int) ($q->H ?? 0),
            'I' => (int) ($q->I ?? 0),
            'S' => (int) ($q->S ?? 0),
            'A' => (int) ($q->A ?? 0),
        ];
    }

    public function getBulanIni($kelas_id)
    {
        return $this->getBulan($kelas_id);
    }

    public function getSiswaTidakHadir($kelas_id)
    {
        return $this->db->table('absensi_detail d')
            ->select('s.nama, d.status, d.keterangan')
            ->join('absensi a', 'a.id_absensi = d.id_absensi')
            ->join('siswa s', 's.id = d.id_siswa')
            ->where('a.id_kelas', (int) $kelas_id)
            ->where('a.tanggal', date('Y-m-d'))
            ->orderBy('s.nama', 'ASC')
            ->get()
            ->getResult();
    }

    public function getTopAlpha($kelas_id)
    {
        return $this->db->table('absensi_detail d')
            ->select('s.nama, COUNT(*) as jumlah')
            ->join('absensi a', 'a.id_absensi = d.id_absensi')
            ->join('siswa s', 's.id = d.id_siswa')
            ->where('a.id_kelas', (int) $kelas_id)
            ->where('LOWER(d.status)', 'alpa')
            ->groupBy('s.id')
            ->orderBy('jumlah', 'DESC')
            ->limit(5)
            ->get()
            ->getResult();
    }

    public function chart7Hari($kelas_id)
    {
        $startDate = date('Y-m-d', strtotime('-6 days'));
        return $this->db->table('absensi a')
            ->select("
                a.tanggal,
                SUM(CASE WHEN LOWER(d.status)='hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN LOWER(d.status)='izin' THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN LOWER(d.status)='sakit' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN LOWER(d.status)='alpa' THEN 1 ELSE 0 END) as alpa
            ", false)
            ->join('absensi_detail d', 'd.id_absensi = a.id_absensi')
            ->where('a.id_kelas', (int) $kelas_id)
            ->where('a.tanggal >=', $startDate)
            ->groupBy('a.tanggal')
            ->orderBy('a.tanggal', 'ASC')
            ->get()
            ->getResult();
    }

    public function getIzinHariIni($kelas_id = null)
    {
        return [
            'keluar' => 0,
            'pulang' => 0,
        ];
    }
}
