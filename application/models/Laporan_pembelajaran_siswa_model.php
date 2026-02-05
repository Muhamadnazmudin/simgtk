<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_pembelajaran_siswa_model extends CI_Model {

    protected $table = 'laporan_pembelajaran_siswa';

    // ===============================
    // AMBIL SEMUA LAPORAN (ADMIN)
    // ===============================
    public function get_all($filter = [])
    {
        $this->db
            ->select('
                l.*,
                k.nama AS nama_kelas,
                s.nama AS nama_siswa,
                g.nama AS nama_guru,
                m.nama_mapel,
                j1.nama_jam AS jam_awal,
                j1.jam_mulai,
                j2.nama_jam AS jam_akhir,
                j2.jam_selesai
            ')
            ->from($this->table.' l')
            ->join('kelas k','k.id = l.kelas_id','left')
            ->join('siswa s','s.id = l.siswa_id','left')
            ->join('guru g','g.id = l.guru_id','left')
            ->join('mapel m','m.id_mapel = l.mapel_id','left')
            ->join('jam_sekolah j1','j1.id_jam = l.jam_mulai_id','left')
            ->join('jam_sekolah j2','j2.id_jam = l.jam_selesai_id','left');

        // ===== FILTER OPTIONAL =====
        if (!empty($filter['kelas_id'])) {
            $this->db->where('l.kelas_id', $filter['kelas_id']);
        }

        if (!empty($filter['tanggal'])) {
            $this->db->where('l.tanggal', $filter['tanggal']);
        }

        if (!empty($filter['guru_id'])) {
            $this->db->where('l.guru_id', $filter['guru_id']);
        }

        return $this->db
            ->order_by('l.id','DESC')
            ->get()
            ->result();
    }
}
