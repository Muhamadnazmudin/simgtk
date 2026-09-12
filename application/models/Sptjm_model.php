<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sptjm_model extends CI_Model
{
    private $table = 'sptjm_dapodik';


    /*
    |--------------------------------------------------------------------------
    | GET SEMUA DATA
    |--------------------------------------------------------------------------
    */
    public function get_all()
    {
        return $this->db
            ->order_by('tahun_ajaran', 'DESC')
            ->order_by('jenis_sptjm', 'ASC')
            ->order_by('tanggal_cut_off', 'DESC')
            ->get($this->table)
            ->result();
    }


    /*
    |--------------------------------------------------------------------------
    | GET DATA BERDASARKAN ID
    |--------------------------------------------------------------------------
    */
    public function get_by_id($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->get($this->table)
            ->row();
    }


    /*
    |--------------------------------------------------------------------------
    | GET BERDASARKAN TAHUN
    |--------------------------------------------------------------------------
    */
    public function get_by_tahun($tahun_ajaran)
    {
        return $this->db
            ->where('tahun_ajaran', $tahun_ajaran)
            ->order_by('jenis_sptjm', 'ASC')
            ->get($this->table)
            ->result();
    }


    /*
    |--------------------------------------------------------------------------
    | CEK DATA TAHUN + JENIS
    |--------------------------------------------------------------------------
    */
    public function get_by_tahun_jenis($tahun_ajaran, $jenis_sptjm)
    {
        return $this->db
            ->where('tahun_ajaran', $tahun_ajaran)
            ->where('jenis_sptjm', $jenis_sptjm)
            ->get($this->table)
            ->row();
    }


    /*
    |--------------------------------------------------------------------------
    | CEK DUPLIKAT
    |--------------------------------------------------------------------------
    */
    public function exists($tahun_ajaran, $jenis_sptjm, $exclude_id = null)
    {
        $this->db
            ->where('tahun_ajaran', $tahun_ajaran)
            ->where('jenis_sptjm', $jenis_sptjm);

        if ($exclude_id !== null) {
            $this->db->where('id !=', (int) $exclude_id);
        }

        return $this->db
            ->count_all_results($this->table) > 0;
    }


    /*
    |--------------------------------------------------------------------------
    | INSERT
    |--------------------------------------------------------------------------
    */
    public function insert($data)
    {
        return $this->db
            ->insert($this->table, $data);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update($id, $data)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, $data);
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->delete($this->table);
    }


    /*
    |--------------------------------------------------------------------------
    | DATA TAHUN AJARAN
    |--------------------------------------------------------------------------
    */
    public function get_tahun_ajaran()
    {
        return $this->db
            ->select('tahun_ajaran')
            ->distinct()
            ->order_by('tahun_ajaran', 'DESC')
            ->get($this->table)
            ->result();
    }


    /*
    |--------------------------------------------------------------------------
    | TOTAL DATA
    |--------------------------------------------------------------------------
    */
    public function count_all()
    {
        return $this->db
            ->count_all($this->table);
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER RIWAYAT
    |--------------------------------------------------------------------------
    */
    public function filter($tahun_ajaran = null, $jenis_sptjm = null)
    {
        if (!empty($tahun_ajaran)) {
            $this->db->where('tahun_ajaran', $tahun_ajaran);
        }

        if (!empty($jenis_sptjm)) {
            $this->db->where('jenis_sptjm', $jenis_sptjm);
        }

        return $this->db
            ->order_by('tahun_ajaran', 'DESC')
            ->order_by('tanggal_cut_off', 'DESC')
            ->get($this->table)
            ->result();
    }
}