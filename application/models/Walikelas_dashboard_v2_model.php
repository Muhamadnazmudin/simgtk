<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Walikelas_dashboard_v2_model extends CI_Model
{

    /* =====================================================
     * KELAS
     * ===================================================== */

    public function getKelas($kelas_id)
    {
        return $this->db
            ->where('id',$kelas_id)
            ->get('kelas')
            ->row();
    }

    /* =====================================================
     * TOTAL SISWA
     * ===================================================== */

    public function getTotalSiswa($kelas_id)
    {
        $q = $this->db
            ->select("
                COUNT(*) total,
                SUM(jk='L') l,
                SUM(jk='P') p
            ")
            ->where('id_kelas',$kelas_id)
            ->where('status','aktif')
            ->get('siswa')
            ->row();

        return [
            'total'=>(int)$q->total,
            'l'=>(int)$q->l,
            'p'=>(int)$q->p
        ];
    }

    /* =====================================================
     * TANGGAL ABSENSI TERAKHIR
     * ===================================================== */

    public function getLastTanggal($kelas_id)
    {
        $row = $this->db
            ->select('tanggal')
            ->where('id_kelas',$kelas_id)
            ->order_by('tanggal','DESC')
            ->limit(1)
            ->get('absensi')
            ->row();

        return $row ? $row->tanggal : date('Y-m-d');
    }

    /* =====================================================
     * REKAP HARI
     * ===================================================== */

    public function getHari($kelas_id,$tanggal=NULL)
{
    if(!$tanggal){
        $tanggal = $this->getLastTanggal($kelas_id);
    }

    $total = $this->db
        ->where('id_kelas',$kelas_id)
        ->where('status','aktif')
        ->count_all_results('siswa');

    $q = $this->db
        ->select("
            SUM(CASE WHEN LOWER(d.status)='izin' THEN 1 ELSE 0 END) I,
SUM(CASE WHEN LOWER(d.status)='sakit' THEN 1 ELSE 0 END) S,
SUM(CASE WHEN LOWER(d.status)='alpa' THEN 1 ELSE 0 END) A
        ")
        ->from('absensi a')
        ->join('absensi_detail d','d.id_absensi=a.id_absensi')
        ->where('a.id_kelas',$kelas_id)
        ->where('a.tanggal',$tanggal)
        ->get()
        ->row();

    $izin  = (int)$q->I;
    $sakit = (int)$q->S;
    $alpa  = (int)$q->A;

    $hadir = $total - ($izin+$sakit+$alpa);

    if($hadir<0){
        $hadir=0;
    }

    return [
        'tanggal'=>$tanggal,
        'H'=>$hadir,
        'I'=>$izin,
        'S'=>$sakit,
        'A'=>$alpa
    ];
}

    /* =====================================================
     * REKAP BULAN
     * ===================================================== */

    public function getBulan($kelas_id,$tanggal=NULL)
    {

        if(!$tanggal){
            $tanggal = $this->getLastTanggal($kelas_id);
        }

        $bulan = date('m',strtotime($tanggal));
        $tahun = date('Y',strtotime($tanggal));

        $q = $this->db
            ->select("
                SUM(IF(LOWER(d.status)='hadir',1,0)) H,
                SUM(IF(LOWER(d.status)='izin',1,0)) I,
                SUM(IF(LOWER(d.status)='sakit',1,0)) S,
                SUM(IF(LOWER(d.status)='alpa',1,0)) A
            ")
            ->from('absensi a')
            ->join('absensi_detail d','d.id_absensi=a.id_absensi')
            ->where('a.id_kelas',$kelas_id)
            ->where('MONTH(a.tanggal)',$bulan)
            ->where('YEAR(a.tanggal)',$tahun)
            ->get()
            ->row();

        return [

            'H'=>(int)$q->H,
            'I'=>(int)$q->I,
            'S'=>(int)$q->S,
            'A'=>(int)$q->A

        ];

    }

    /* =====================================================
     * BELUM ABSEN
     * ===================================================== */

    public function getSiswaTidakHadir($kelas_id)
{
    return $this->db
        ->select("
            s.nama,
            d.status,
            d.keterangan
        ")
        ->from('absensi_detail d')
        ->join('absensi a','a.id_absensi=d.id_absensi')
        ->join('siswa s','s.id=d.id_siswa')
        ->where('a.id_kelas',$kelas_id)
        ->where('a.tanggal',date('Y-m-d'))
        ->order_by('s.nama','ASC')
        ->get()
        ->result();
}

    /* =====================================================
     * TOP ALPA
     * ===================================================== */

    public function getTopAlpha($kelas_id)
    {

        return $this->db
            ->select("
                s.nama,
                COUNT(*) jumlah
            ")
            ->from('absensi_detail d')
            ->join('absensi a','a.id_absensi=d.id_absensi')
            ->join('siswa s','s.id=d.id_siswa')
            ->where('a.id_kelas',$kelas_id)
            ->where('LOWER(d.status)','alpa')
            ->group_by('s.id')
            ->order_by('jumlah','DESC')
            ->limit(5)
            ->get()
            ->result();

    }

    /* =====================================================
     * CHART 7 HARI
     * ===================================================== */

    public function chart7Hari($kelas_id)
    {

        return $this->db
            ->select("
                a.tanggal,
                SUM(IF(LOWER(d.status)='hadir',1,0)) hadir,
                SUM(IF(LOWER(d.status)='izin',1,0)) izin,
                SUM(IF(LOWER(d.status)='sakit',1,0)) sakit,
                SUM(IF(LOWER(d.status)='alpa',1,0)) alpa
            ")
            ->from('absensi a')
            ->join('absensi_detail d','d.id_absensi=a.id_absensi')
            ->where('a.id_kelas',$kelas_id)
            ->where('a.tanggal >=',date('Y-m-d',strtotime('-6 days')))
            ->group_by('a.tanggal')
            ->order_by('a.tanggal')
            ->get()
            ->result();

    }

    /* =====================================================
     * IZIN
     * ===================================================== */

    public function getIzinHariIni()
    {

        return [
            'keluar'=>0,
            'pulang'=>0
        ];

    }

    /* =====================================================
 * COMPATIBILITY DENGAN CONTROLLER
 * ===================================================== */

public function getHariIni($kelas_id)
{
    $tanggal = date('Y-m-d');

    // Total siswa aktif
    $total = $this->db
        ->where('id_kelas', $kelas_id)
        ->where('status', 'aktif')
        ->count_all_results('siswa');

    // Rekap hanya siswa yang diinput BP/BK
    $q = $this->db
        ->select("
            SUM(CASE WHEN LOWER(d.status)='izin' THEN 1 ELSE 0 END) AS I,
SUM(CASE WHEN LOWER(d.status)='sakit' THEN 1 ELSE 0 END) AS S,
SUM(CASE WHEN LOWER(d.status)='alpa' THEN 1 ELSE 0 END) AS A
        ")
        ->from('absensi a')
        ->join('absensi_detail d','d.id_absensi=a.id_absensi')
        ->join('siswa s','s.id=d.id_siswa')
        ->where('a.id_kelas',$kelas_id)
        ->where('a.tanggal',$tanggal)
        ->get()
        ->row();

    $izin  = (int)($q->I ?? 0);
    $sakit = (int)($q->S ?? 0);
    $alpa  = (int)($q->A ?? 0);

    $hadir = $total - ($izin + $sakit + $alpa);

    if($hadir < 0){
        $hadir = 0;
    }

    return [
        'H'=>$hadir,
        'I'=>$izin,
        'S'=>$sakit,
        'A'=>$alpa
    ];
}

public function getBulanIni($kelas_id)
{
    return $this->getBulan($kelas_id);
}
}