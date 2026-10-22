<?php

namespace App\Models;

use CodeIgniter\Model;

class DashboardBpbkModel extends Model
{
    public function getSummary(): array
    {
        return [
            'jumlah_siswa' => $this->db->table('siswa')->where('status', 'aktif')->countAllResults(),
            'jumlah_guru'  => $this->db->table('guru')->countAllResults(),
            'jumlah_kelas' => $this->db->table('kelas')->where('is_active', 1)->countAllResults(),
        ];
    }

    private function totalSiswaAktif(): int
    {
        return $this->db->table('siswa')->where('status', 'aktif')->countAllResults();
    }

    public function getHariIni(): array
    {
        $tanggal = date('Y-m-d');

        $q = $this->db->table('absensi_detail d')
            ->select("
                SUM(CASE WHEN UPPER(d.status) IN ('I','IZIN') THEN 1 ELSE 0 END) AS izin,
                SUM(CASE WHEN UPPER(d.status) IN ('S','SAKIT') THEN 1 ELSE 0 END) AS sakit,
                SUM(CASE WHEN UPPER(d.status) IN ('A','ALPA') THEN 1 ELSE 0 END) AS alpa
            ", false)
            ->join('absensi a', 'a.id_absensi = d.id_absensi')
            ->where('a.tanggal', $tanggal)
            ->get()
            ->getRow();

        $total = $this->totalSiswaAktif();
        $izin  = (int) ($q->izin ?? 0);
        $sakit = (int) ($q->sakit ?? 0);
        $alpa  = (int) ($q->alpa ?? 0);

        $hadir = max(0, $total - ($izin + $sakit + $alpa));

        return [
            'H'           => $hadir,
            'I'           => $izin,
            'S'           => $sakit,
            'A'           => $alpa,
            'T'           => $izin + $sakit + $alpa,
            'TOTAL_SISWA' => $total,
        ];
    }

    public function getBulanIni(): array
    {
        $awalBulan = date('Y-m-01');
        $hariIni   = date('Y-m-d');

        $totalSiswa = $this->totalSiswaAktif();

        $hariRow = $this->db->table('absensi')
            ->select('COUNT(DISTINCT tanggal) AS jumlah')
            ->where('tanggal >=', $awalBulan)
            ->where('tanggal <=', $hariIni)
            ->get()
            ->getRow();

        $hariEfektif = (int) ($hariRow->jumlah ?? 0);
        $targetHadir = $totalSiswa * $hariEfektif;

        $q = $this->db->table('absensi_detail d')
            ->select("
                SUM(CASE WHEN UPPER(d.status) IN ('I','IZIN') THEN 1 ELSE 0 END) AS izin,
                SUM(CASE WHEN UPPER(d.status) IN ('S','SAKIT') THEN 1 ELSE 0 END) AS sakit,
                SUM(CASE WHEN UPPER(d.status) IN ('A','ALPA') THEN 1 ELSE 0 END) AS alpa
            ", false)
            ->join('absensi a', 'a.id_absensi = d.id_absensi')
            ->where('a.tanggal >=', $awalBulan)
            ->where('a.tanggal <=', $hariIni)
            ->get()
            ->getRow();

        $izin  = (int) ($q->izin ?? 0);
        $sakit = (int) ($q->sakit ?? 0);
        $alpa  = (int) ($q->alpa ?? 0);

        $hadir = max(0, $targetHadir - ($izin + $sakit + $alpa));
        $persentase = $targetHadir > 0 ? round(($hadir / $targetHadir) * 100, 1) : 0;

        return [
            'H'      => $hadir,
            'I'      => $izin,
            'S'      => $sakit,
            'A'      => $alpa,
            'TOTAL'  => $targetHadir,
            'PERSEN' => $persentase,
        ];
    }

    public function getTidakHadirHariIni(): array
    {
        return $this->db->table('absensi_detail d')
            ->select('s.id, s.nama, k.nama AS nama_kelas, d.status, d.keterangan')
            ->join('absensi a', 'a.id_absensi = d.id_absensi')
            ->join('siswa s', 's.id = d.id_siswa')
            ->join('kelas k', 'k.id = s.id_kelas')
            ->where('a.tanggal', date('Y-m-d'))
            ->whereIn('UPPER(d.status)', ['I', 'IZIN', 'S', 'SAKIT', 'A', 'ALPA'])
            ->orderBy('k.nama', 'ASC')
            ->orderBy('s.nama', 'ASC')
            ->get()
            ->getResult();
    }

    public function getTopAlpa(int $limit = 10): array
    {
        $awalBulan = date('Y-m-01');
        $hariIni   = date('Y-m-d');

        return $this->db->table('absensi_detail d')
            ->select('s.id, s.nama, k.nama AS nama_kelas, COUNT(*) AS jumlah')
            ->join('absensi a', 'a.id_absensi = d.id_absensi')
            ->join('siswa s', 's.id = d.id_siswa')
            ->join('kelas k', 'k.id = s.id_kelas')
            ->where('a.tanggal >=', $awalBulan)
            ->where('a.tanggal <=', $hariIni)
            ->whereIn('UPPER(d.status)', ['A', 'ALPA'])
            ->groupBy('d.id_siswa')
            ->orderBy('jumlah', 'DESC')
            ->orderBy('s.nama', 'ASC')
            ->limit($limit)
            ->get()
            ->getResult();
    }
}
