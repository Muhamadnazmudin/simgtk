<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_bpbk_model extends CI_Model
{

    /* ==========================================================
     * SUMMARY DASHBOARD
     * ========================================================== */
    public function getSummary()
    {
        return [

            // Jumlah siswa aktif
            'jumlah_siswa' => $this->db
                ->where('status', 'aktif')
                ->count_all_results('siswa'),

            // Jumlah guru
            'jumlah_guru' => $this->db
                ->count_all('guru'),

            // Jumlah kelas aktif
            'jumlah_kelas' => $this->db
                ->where('is_active', 1)
                ->count_all_results('kelas')

        ];
    }

    /* ==========================================================
     * HELPER TOTAL SISWA AKTIF
     * ========================================================== */
    private function totalSiswaAktif()
    {
        return (int) $this->db
            ->where('status', 'aktif')
            ->count_all_results('siswa');
    }

    /* ==========================================================
     * HELPER NORMALISASI STATUS
     * Agar status S/SAKIT, I/IZIN, A/ALPA tetap terbaca
     * ========================================================== */
    private function statusCase($huruf, $teks)
    {
        return "SUM(
                    CASE
                        WHEN UPPER(d.status) IN ('$huruf','$teks')
                        THEN 1
                        ELSE 0
                    END
                )";
    }

        /* ==========================================================
     * REKAP KEHADIRAN HARI INI
     * ========================================================== */
    public function getHariIni()
    {
        $tanggal = date('Y-m-d');

        $q = $this->db->query("
            SELECT

                {$this->statusCase('I','IZIN')}   AS izin,

                {$this->statusCase('S','SAKIT')}  AS sakit,

                {$this->statusCase('A','ALPA')}   AS alpa

            FROM absensi_detail d

            INNER JOIN absensi a
                ON a.id_absensi = d.id_absensi

            WHERE a.tanggal = ?

        ", [$tanggal])->row();

        $total = $this->totalSiswaAktif();

        $izin  = (int) ($q->izin  ?? 0);
        $sakit = (int) ($q->sakit ?? 0);
        $alpa  = (int) ($q->alpa  ?? 0);

        $hadir = $total - ($izin + $sakit + $alpa);

        if ($hadir < 0) {
            $hadir = 0;
        }

        return [

            'H' => $hadir,

            'I' => $izin,

            'S' => $sakit,

            'A' => $alpa,

            'T' => $izin + $sakit + $alpa,

            'TOTAL_SISWA' => $total

        ];
    }
        /* ==========================================================
     * REKAP BULAN BERJALAN
     * ========================================================== */
    public function getBulanIni()
    {
        $awalBulan = date('Y-m-01');
        $hariIni   = date('Y-m-d');

        // ======================================================
        // Total siswa aktif
        // ======================================================

        $totalSiswa = $this->totalSiswaAktif();

        // ======================================================
        // Jumlah hari absensi yang sudah dibuat bulan ini
        // ======================================================

        $hariEfektif = (int) $this->db
            ->select('COUNT(DISTINCT tanggal) jumlah')
            ->where('tanggal >=', $awalBulan)
            ->where('tanggal <=', $hariIni)
            ->get('absensi')
            ->row()
            ->jumlah;

        // Target kehadiran bulan ini
        $targetHadir = $totalSiswa * $hariEfektif;

        // ======================================================
        // Rekap I / S / A
        // ======================================================

        $q = $this->db->query("
            SELECT

                {$this->statusCase('I','IZIN')}  AS izin,

                {$this->statusCase('S','SAKIT')} AS sakit,

                {$this->statusCase('A','ALPA')}  AS alpa

            FROM absensi_detail d

            INNER JOIN absensi a
                ON a.id_absensi = d.id_absensi

            WHERE a.tanggal >= ?

            AND a.tanggal <= ?

        ", [
            $awalBulan,
            $hariIni
        ])->row();

        $izin  = (int) ($q->izin ?? 0);
        $sakit = (int) ($q->sakit ?? 0);
        $alpa  = (int) ($q->alpa ?? 0);

        $hadir = $targetHadir - ($izin + $sakit + $alpa);

        if ($hadir < 0) {
            $hadir = 0;
        }

        $persentase = 0;

        if ($targetHadir > 0) {

            $persentase = round(
                ($hadir / $targetHadir) * 100,
                1
            );

        }

        return [

            'H' => $hadir,

            'I' => $izin,

            'S' => $sakit,

            'A' => $alpa,

            'TOTAL' => $targetHadir,

            'PERSEN' => $persentase

        ];
    }
        /* ==========================================================
     * SISWA TIDAK HADIR HARI INI
     * ========================================================== */
    public function getTidakHadirHariIni()
    {
        return $this->db
            ->select("
                s.id,
                s.nama,
                k.nama AS nama_kelas,
                d.status,
                d.keterangan
            ")
            ->from('absensi_detail d')
            ->join('absensi a', 'a.id_absensi = d.id_absensi')
            ->join('siswa s', 's.id = d.id_siswa')
            ->join('kelas k', 'k.id = s.id_kelas')
            ->where('a.tanggal', date('Y-m-d'))
            ->where_in('UPPER(d.status)', [
                'I',
                'IZIN',
                'S',
                'SAKIT',
                'A',
                'ALPA'
            ])
            ->order_by('k.nama', 'ASC')
            ->order_by('s.nama', 'ASC')
            ->get()
            ->result();
    }


    /* ==========================================================
     * TOP ALPA BULAN BERJALAN
     * ========================================================== */
    public function getTopAlpa($limit = 10)
    {
        $awalBulan = date('Y-m-01');
        $hariIni   = date('Y-m-d');

        return $this->db
            ->select("
                s.id,
                s.nama,
                k.nama AS nama_kelas,
                COUNT(*) AS jumlah
            ")
            ->from('absensi_detail d')
            ->join('absensi a', 'a.id_absensi = d.id_absensi')
            ->join('siswa s', 's.id = d.id_siswa')
            ->join('kelas k', 'k.id = s.id_kelas')
            ->where('a.tanggal >=', $awalBulan)
            ->where('a.tanggal <=', $hariIni)
            ->where_in('UPPER(d.status)', [
                'A',
                'ALPA'
            ])
            ->group_by('d.id_siswa')
            ->order_by('jumlah', 'DESC')
            ->order_by('s.nama', 'ASC')
            ->limit($limit)
            ->get()
            ->result();
    }

}