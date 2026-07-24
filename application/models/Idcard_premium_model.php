<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Idcard_premium_model extends CI_Model
{
    protected $table = 'siswa';

    /* ==========================================================
     * BASE QUERY
     * ========================================================== */
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

        // hanya siswa aktif
        $this->db->where('siswa.status', 'aktif');
    }

    /* ==========================================================
     * FILTER
     * ========================================================== */
    private function _filter($nama = null, $kelas = null)
    {
        if (!empty($nama)) {

            $this->db->group_start();

            $this->db->like('siswa.nama', $nama);
            $this->db->or_like('siswa.nis', $nama);
            $this->db->or_like('siswa.nisn', $nama);

            $this->db->group_end();
        }

        if (!empty($kelas)) {
            $this->db->where(
                'siswa.id_kelas',
                $kelas
            );
        }
    }

    /* ==========================================================
     * LIST SISWA
     * ========================================================== */
    public function getList($nama = null, $kelas = null)
    {
        $this->_baseQuery();
        $this->_filter($nama, $kelas);

        $this->db->select("
            siswa.*,
            kelas.nama AS nama_kelas,
            tahun_ajaran.tahun
        ");

        $this->db->order_by('kelas.nama', 'ASC');
        $this->db->order_by('siswa.nama', 'ASC');

        return $this->db->get()->result();
    }

    /* ==========================================================
     * DETAIL SISWA
     * ========================================================== */
    public function getById($id)
    {
        $this->_baseQuery();

        $this->db->select("
            siswa.*,
            kelas.nama AS nama_kelas,
            tahun_ajaran.tahun
        ");

        $this->db->where(
            'siswa.id',
            $id
        );

        return $this->db
            ->get()
            ->row();
    }

    /* ==========================================================
     * CETAK PER KELAS
     * ========================================================== */
    public function getByKelas($kelas_id)
    {
        $this->_baseQuery();

        $this->db->select("
            siswa.*,
            kelas.nama AS nama_kelas,
            tahun_ajaran.tahun
        ");

        $this->db->where(
            'siswa.id_kelas',
            $kelas_id
        );

        $this->db->order_by('siswa.nama', 'ASC');

        return $this->db
            ->get()
            ->result();
    }

    /* ==========================================================
     * DAFTAR KELAS
     * ========================================================== */
    public function getKelas()
    {
        return $this->db
            ->order_by('nama', 'ASC')
            ->get('kelas')
            ->result();
    }

    /* ==========================================================
     * BACKWARD COMPATIBILITY
     * ========================================================== */
    public function getAll()
    {
        return $this->getList();
    }
}