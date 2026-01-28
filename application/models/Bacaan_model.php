<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bacaan_model extends CI_Model {

    protected $table = 'tbl_bacaan';

    /**
     * Ambil buku sesuai kelas siswa + buku umum
     */
    public function get_all()
{
    $this->db->select('*');
    $this->db->from($this->table);
    $this->db->where('status', 'aktif');
    $this->db->order_by('judul', 'ASC');

    return $this->db->get()->result();
}
    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, [
            'id'     => $id,
            'status' => 'aktif'
        ])->row();
    }
public function get_all_admin()
{
    return $this->db
        ->order_by('created_at','DESC')
        ->get($this->table)
        ->result();
}

public function update($id, $data)
{
    return $this->db->where('id',$id)->update($this->table,$data);
}

public function delete($id)
{
    return $this->db->delete($this->table, ['id'=>$id]);
}
public function get_paginated($limit, $offset)
{
    return $this->db
        ->where('status', 'aktif')
        ->order_by('created_at', 'DESC')
        ->limit($limit, $offset)
        ->get($this->table)
        ->result();
}

public function count_all_aktif()
{
    return $this->db
        ->where('status', 'aktif')
        ->count_all_results($this->table);
}
public function get_filtered($limit, $offset, $kelas = null, $mapel = null)
{
    $this->db->where('status', 'aktif');

    if ($kelas) {
        $this->db->where('kelas', $kelas);
    }

    if ($mapel) {
        $this->db->where('mapel', $mapel);
    }

    return $this->db
        ->order_by('created_at', 'DESC')
        ->limit($limit, $offset)
        ->get($this->table)
        ->result();
}
public function count_filtered($kelas = null, $mapel = null)
{
    $this->db->where('status', 'aktif');

    if ($kelas) {
        $this->db->where('kelas', $kelas);
    }

    if ($mapel) {
        $this->db->where('mapel', $mapel);
    }

    return $this->db->count_all_results($this->table);
}
public function get_mapel_unik()
{
    return $this->db
        ->select('mapel')
        ->where('mapel IS NOT NULL')
        ->group_by('mapel')
        ->order_by('mapel', 'ASC')
        ->get($this->table)
        ->result();
}

}
