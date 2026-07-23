<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buku_induk_model extends CI_Model
{

    protected $table = 'siswa';

    /**
     * ==========================================================
     * BASE QUERY
     * ==========================================================
     */
    private function _baseQuery()
    {
        $this->db->from($this->table);

        $this->db->join(
            'kelas',
            'kelas.id = siswa.id_kelas',
            'left'
        );

        $this->db->join(
            'tahun_ajaran',
            'tahun_ajaran.id = siswa.tahun_id',
            'left'
        );

        // Buku Induk hanya siswa aktif
        $this->db->where('siswa.status', 'aktif');
    }

    /**
     * ==========================================================
     * FILTER
     * ==========================================================
     */
    private function _filter($filter = [])
    {
        if (!empty($filter['kelas'])) {
            $this->db->where(
                'siswa.id_kelas',
                $filter['kelas']
            );
        }
// ==========================================================
// FILTER KHUSUS WALI KELAS
// ==========================================================
if (!empty($filter['kelas_id'])) {

    $this->db->where(
        'siswa.id_kelas',
        $filter['kelas_id']
    );

}
        if (!empty($filter['search'])) {

            $this->db->group_start();

            $this->db->like(
                'siswa.nama',
                $filter['search']
            );

            $this->db->or_like(
                'siswa.nis',
                $filter['search']
            );

            $this->db->or_like(
                'siswa.nisn',
                $filter['search']
            );

            $this->db->group_end();
        }
    }

    /**
     * ==========================================================
     * TOTAL DATA
     * ==========================================================
     */
    public function countAll($filter = [])
    {
        $this->_baseQuery();

        $this->_filter($filter);

        return $this->db->count_all_results();
    }

    /**
     * ==========================================================
     * LIST DATA
     * ==========================================================
     */
    public function getData($limit, $offset, $filter = [])
    {
        $this->_baseQuery();

        $this->_filter($filter);

        $this->db->select("
            siswa.*,
            kelas.nama AS nama_kelas,
            tahun_ajaran.tahun AS tahun_ajaran
        ");

        $this->db->order_by(
            'siswa.nama',
            'ASC'
        );

        return $this->db
            ->limit($limit, $offset)
            ->get()
            ->result();
    }

    /**
     * ==========================================================
     * DETAIL SISWA
     * ==========================================================
     */
    public function getById($id)
    {
        $this->_baseQuery();

        $this->db->select("
            siswa.*,
            kelas.nama AS nama_kelas,
            tahun_ajaran.tahun AS tahun_ajaran
        ");

        $this->db->where(
            'siswa.id',
            $id
        );

        return $this->db
            ->get()
            ->row();
    }

    /**
     * ==========================================================
     * DAFTAR KELAS
     * ==========================================================
     */
    public function getKelas()
    {
        return $this->db
            ->order_by('nama', 'ASC')
            ->get('kelas')
            ->result();
    }
public function getRiwayatTahun($siswa_id)
{
    return $this->db
        ->select('
            st.tahun_id,
            ta.tahun
        ')
        ->from('siswa_tahun st')
        ->join('tahun_ajaran ta', 'ta.id = st.tahun_id')
        ->where('st.siswa_id', $siswa_id)
        ->order_by('ta.id', 'ASC')
        ->get()
        ->result();
}
}