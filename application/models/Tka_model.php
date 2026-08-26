<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tka_model extends CI_Model
{
    private $table = 'tka_siswa';


    /*
    |--------------------------------------------------------------------------
    | AMBIL SEMUA MAPEL AKTIF
    |--------------------------------------------------------------------------
    */
    public function get_mapel_aktif()
    {
        return $this->db
            ->where('aktif', 1)
            ->order_by('nama_mapel', 'ASC')
            ->get('tka_mapel')
            ->result();
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL MAPEL PROGRAM KEAHLIAN
    |--------------------------------------------------------------------------
    |
    | Mapel dengan kode PK otomatis menjadi Pilihan 1.
    |
    */
    public function get_mapel_program_keahlian()
    {
        return $this->db
            ->where('kode', 'PK')
            ->where('aktif', 1)
            ->limit(1)
            ->get('tka_mapel')
            ->row();
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL MAPEL PILIHAN SISWA
    |--------------------------------------------------------------------------
    |
    | Pilihan 2 tidak menampilkan Program Keahlian.
    |
    */
    public function get_mapel_pilihan()
    {
        return $this->db
            ->where('aktif', 1)
            ->where('kode !=', 'PK')
            ->order_by('nama_mapel', 'ASC')
            ->get('tka_mapel')
            ->result();
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL PILIHAN TKA SISWA
    |--------------------------------------------------------------------------
    */
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

            ->where(
                'ts.siswa_id',
                (int) $siswa_id
            )

            ->where(
                'ts.tahun_id',
                (int) $tahun_id
            )

            ->limit(1)

            ->get()
            ->row();
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN PILIHAN
    |--------------------------------------------------------------------------
    |
    | Hanya INSERT.
    | Siswa tidak boleh melakukan UPDATE.
    |
    */
    public function save_pilihan(
        $siswa_id,
        $tahun_id,
        $mapel_1_id,
        $mapel_2_id
    ) {
        return $this->db->insert(
            $this->table,
            [
                'siswa_id'   => (int) $siswa_id,
                'tahun_id'   => (int) $tahun_id,
                'mapel_1_id' => (int) $mapel_1_id,
                'mapel_2_id' => (int) $mapel_2_id
            ]
        );
    }
}