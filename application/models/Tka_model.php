<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tka_model extends CI_Model
{
    private $table = 'tka_siswa';
    private $mapel_table = 'tka_mapel';


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
            ->get($this->mapel_table)
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
            ->get($this->mapel_table)
            ->row();
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL MAPEL PILIHAN SISWA
    |--------------------------------------------------------------------------
    |
    | Pilihan 2 tidak menampilkan mapel Program Keahlian.
    |
    */
    public function get_mapel_pilihan()
    {
        return $this->db
            ->where('aktif', 1)
            ->where('kode !=', 'PK')
            ->order_by('nama_mapel', 'ASC')
            ->get($this->mapel_table)
            ->result();
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL MAPEL PILIHAN UNTUK ADMIN
    |--------------------------------------------------------------------------
    |
    | Digunakan pada halaman:
    |
    | views/admin/tka/input.php
    | views/admin/tka/edit.php
    |
    | Konsep:
    | - Pilihan 1 = PK
    | - Pilihan 2 = mapel selain PK
    |
    | Jika $exclude_id diberikan, mapel tersebut tidak akan
    | ditampilkan. Ini berguna agar Pilihan 2 tidak bisa
    | sama dengan Pilihan 1.
    |
    */
    public function get_mapel_pilihan_admin()
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
                m1.kode AS kode_mapel_1,

                m2.nama_mapel AS mapel_2,
                m2.kode AS kode_mapel_2
            ')
            ->from(
                $this->table . ' ts'
            )

            ->join(
                $this->mapel_table . ' m1',
                'm1.id = ts.mapel_1_id',
                'left'
            )

            ->join(
                $this->mapel_table . ' m2',
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
    | AMBIL DATA TKA BERDASARKAN ID
    |--------------------------------------------------------------------------
    |
    | Dipakai halaman admin edit.
    |
    */
    public function get_by_id($id)
{
    return $this->db
        ->select('
            ts.*,

            s.id AS siswa_id,
            s.nama,
            s.nisn,

            k.nama AS nama_kelas,

            ta.tahun AS tahun_ajaran,

            m1.nama_mapel AS mapel_1,
            m1.kode AS kode_mapel_1,

            m2.nama_mapel AS mapel_2,
            m2.kode AS kode_mapel_2
        ')
        ->from('tka_siswa ts')

        ->join(
            'siswa s',
            's.id = ts.siswa_id',
            'left'
        )

        ->join(
            'kelas k',
            'k.id = s.id_kelas',
            'left'
        )

        ->join(
            'tahun_ajaran ta',
            'ta.id = s.tahun_id',
            'left'
        )

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
            'ts.id',
            (int) $id
        )

        ->limit(1)

        ->get()
        ->row();
}



    /*
    |--------------------------------------------------------------------------
    | CEK DATA TKA SISWA
    |--------------------------------------------------------------------------
    */
    public function exists($siswa_id, $tahun_id)
    {
        return $this->db
            ->where(
                'siswa_id',
                (int) $siswa_id
            )
            ->where(
                'tahun_id',
                (int) $tahun_id
            )
            ->count_all_results(
                $this->table
            ) > 0;
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN PILIHAN
    |--------------------------------------------------------------------------
    |
    | INSERT baru.
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


    /*
    |--------------------------------------------------------------------------
    | UPDATE PILIHAN
    |--------------------------------------------------------------------------
    |
    | Khusus admin.
    | Siswa tidak menggunakan method ini.
    |
    */
    public function update_pilihan(
        $id,
        $mapel_1_id,
        $mapel_2_id
    ) {
        return $this->db
            ->where(
                'id',
                (int) $id
            )
            ->update(
                $this->table,
                [
                    'mapel_1_id' => (int) $mapel_1_id,
                    'mapel_2_id' => (int) $mapel_2_id
                ]
            );
    }

/*
|--------------------------------------------------------------------------
| AMBIL DATA SISWA UNTUK INPUT TKA
|--------------------------------------------------------------------------
*/
public function get_siswa_tka($siswa_id)
{
    return $this->db
        ->select('
            siswa.id,
            siswa.nama,
            siswa.nisn,
            siswa.id_kelas,
            siswa.tahun_id,

            kelas.nama AS nama_kelas,

            tahun_ajaran.tahun AS tahun_ajaran
        ')
        ->from('siswa')

        ->join(
            'kelas',
            'kelas.id = siswa.id_kelas',
            'left'
        )

        ->join(
            'tahun_ajaran',
            'tahun_ajaran.id = siswa.tahun_id',
            'left'
        )

        ->where(
            'siswa.id',
            (int) $siswa_id
        )

        ->limit(1)

        ->get()
        ->row();
}
    
}