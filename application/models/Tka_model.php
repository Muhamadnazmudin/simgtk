<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tka_model extends CI_Model
{
    private $table = 'tka_siswa';

    public function get_mapel_aktif()
    {
        return $this->db
            ->where('aktif', 1)
            ->order_by('nama_mapel', 'ASC')
            ->get('tka_mapel')
            ->result();
    }

    public function get_pilihan($siswa_id, $tahun_id)
    {
        return $this->db
            ->select('
                ts.*,
                m1.nama_mapel AS mapel_1,
                m2.nama_mapel AS mapel_2
            ')
            ->from('tka_siswa ts')
            ->join(
                'tka_mapel m1',
                'm1.id = ts.mapel_1_id',
                'left'
            )
            ->join(
                'tka_mapel m2',
                'm2.id = ts.mapel_2_id',
                'left'
            )
            ->where('ts.siswa_id', $siswa_id)
            ->where('ts.tahun_id', $tahun_id)
            ->get()
            ->row();
    }

    public function save_pilihan($siswa_id, $tahun_id, $mapel_1_id, $mapel_2_id = null)
    {
        $existing = $this->get_pilihan($siswa_id, $tahun_id);

        $data = [
            'siswa_id'   => $siswa_id,
            'tahun_id'   => $tahun_id,
            'mapel_1_id' => $mapel_1_id,
            'mapel_2_id' => $mapel_2_id
        ];

        if ($existing) {

            return $this->db
                ->where('id', $existing->id)
                ->update($this->table, $data);
        }

        return $this->db
            ->insert($this->table, $data);
    }
}