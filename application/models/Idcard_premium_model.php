<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Idcard_premium_model extends CI_Model {

    public function getById($id)
    {
        return $this->db
            ->select('siswa.*, kelas.nama AS nama_kelas')
            ->from('siswa')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->where('siswa.id', $id)
            ->get()
            ->row();
    }
    public function getAll()
{
    return $this->db
        ->select('siswa.*, kelas.nama AS nama_kelas')
        ->from('siswa')
        ->join('kelas','kelas.id = siswa.id_kelas','left')
        ->order_by('siswa.nama','ASC')
        ->get()
        ->result();
}


}
