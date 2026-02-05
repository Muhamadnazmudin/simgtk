<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_pembelajaran_model extends CI_Model {

    protected $table = 'laporan_pembelajaran_siswa';

    public function get_by_kelas($kelas_id)
    {
        return $this->db
            ->select('
                l.*,
                s.nama AS nama_siswa,
                g.nama AS nama_guru,
                m.nama_mapel,
                j1.nama_jam AS jam_mulai,
                j2.nama_jam AS jam_selesai
            ')
            ->from($this->table.' l')
            ->join('siswa s','s.id=l.siswa_id')
            ->join('guru g','g.id=l.guru_id')
            ->join('mapel m','m.id_mapel=l.mapel_id')
            ->join('jam_sekolah j1','j1.id_jam=l.jam_mulai_id')
            ->join('jam_sekolah j2','j2.id_jam=l.jam_selesai_id')
            ->where('l.kelas_id',$kelas_id)
            ->order_by('l.created_at','DESC')
            ->get()
            ->result();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table,$data);
    }
}
