<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sekolah_model extends CI_Model {

    private $table = 'sekolah';

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }
    public function get_single()
{
    return $this->db
        ->select('s.*, g.nama AS nama_waka_kurikulum')
        ->from('sekolah s')
        ->join('guru g', 'g.id = s.waka_kurikulum_id', 'left')
        ->get()
        ->row();
}
public function count_all()
{
    return $this->db->count_all($this->table);
}
public function set_role_wakakur($guru_id)
{
    $user = $this->db->get_where('users', [
        'guru_id' => $guru_id
    ])->row();

    if ($user) {
        $this->db->where('id', $user->id)
                 ->update('users', ['role_id' => 4]);
    }
}
public function reset_old_wakakur($except_guru_id = null)
{
    $this->db->where('role_id', 4);

    if ($except_guru_id) {
        $this->db->where('guru_id !=', $except_guru_id);
    }

    $this->db->update('users', ['role_id' => 3]); // balik jadi guru
}

}
