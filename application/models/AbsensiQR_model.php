<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AbsensiQR_model extends CI_Model {

    public function get_siswa_by_token($token)
{
    $token = preg_replace('/[^0-9]/', '', trim($token));

    return $this->db
        ->get_where('siswa', ['nisn' => $token])
        ->row();
}


    public function get_absen_hari_ini($nis, $tanggal) {
        return $this->db->get_where('absensi_qr', [
            'nis' => $nis,
            'tanggal' => $tanggal
        ])->row();
    }

    public function insert_absen_masuk($data) {
        return $this->db->insert('absensi_qr', $data);
    }

    public function update_absen_pulang($id, $jam) {
        return $this->db->where('id', $id)->update('absensi_qr', [
            'jam_pulang' => $jam
        ]);
    }

    public function history_today() {
        $today = date('Y-m-d');
        return $this->db->order_by('id', 'DESC')
                        ->get_where('absensi_qr', ['tanggal' => $today])
                        ->result();
    }

    // ambil mode ulangan
    public function mode_ulangan() {
        $row = $this->db->get_where('system_config', ['config_key' => 'mode_ulangan'])->row();
        return ($row && $row->config_value == '1');
    }
    public function get_all_join()
{
    $this->db->select("a.*, s.nama AS nama_siswa, k.nama AS nama_kelas");
    $this->db->from("absensi_qr a");
    $this->db->join("siswa s", "s.nis = a.nis", "left");
    $this->db->join("kelas k", "k.id = s.id_kelas", "left");
    $this->db->order_by("a.tanggal", "DESC");
    $this->db->order_by("a.jam_masuk", "DESC");
    return $this->db->get()->result();
}

public function rekap_by_siswa($nis)
{
    return $this->db
        ->query("
            SELECT

                CASE
                    WHEN MONTH(tanggal) >= 7
                    THEN CONCAT(YEAR(tanggal),'/',YEAR(tanggal)+1)
                    ELSE CONCAT(YEAR(tanggal)-1,'/',YEAR(tanggal))
                END AS tahun_ajaran,

                CASE
                    WHEN MONTH(tanggal) >= 7
                    THEN 1
                    ELSE 2
                END AS semester,

                SUM(CASE WHEN kehadiran='H' THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN kehadiran='S' THEN 1 ELSE 0 END) AS sakit,
                SUM(CASE WHEN kehadiran='I' THEN 1 ELSE 0 END) AS izin,
                SUM(CASE WHEN kehadiran='A' THEN 1 ELSE 0 END) AS alpha,

                SUM(CASE WHEN status='Terlambat' THEN 1 ELSE 0 END) AS terlambat

            FROM absensi_qr

            WHERE nis = ?

            GROUP BY tahun_ajaran, semester

            ORDER BY tahun_ajaran DESC, semester ASC
        ", [$nis])
        ->result();
}
public function getRekapSemester($nis, $tahunAjaran, $semester)
{
    list($thAwal, $thAkhir) = explode('/', $tahunAjaran);

    if ($semester == 1) {

        $tgl1 = $thAwal . '-07-01';
        $tgl2 = $thAwal . '-12-31';

    } else {

        $tgl1 = $thAkhir . '-01-01';
        $tgl2 = $thAkhir . '-06-30';

    }

    return $this->db
        ->select("
            SUM(CASE WHEN kehadiran='H' THEN 1 ELSE 0 END) AS hadir,
            SUM(CASE WHEN kehadiran='S' THEN 1 ELSE 0 END) AS sakit,
            SUM(CASE WHEN kehadiran='I' THEN 1 ELSE 0 END) AS izin,
            SUM(CASE WHEN kehadiran='A' THEN 1 ELSE 0 END) AS alpha,
            SUM(CASE WHEN status='Terlambat' THEN 1 ELSE 0 END) AS terlambat
        ", false)
        ->from('absensi_qr')
        ->where('nis', $nis)
        ->where('tanggal >=', $tgl1)
        ->where('tanggal <=', $tgl2)
        ->get()
        ->row();
}
}
