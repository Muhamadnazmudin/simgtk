<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_keluar_model extends CI_Model
{

    /* ==========================================================
     * BASE QUERY
     * ========================================================== */

    private function _baseQuery()
    {
        $subquery = "
            SELECT m1.siswa_id
            FROM mutasi m1
            INNER JOIN (
                SELECT siswa_id, MAX(id) AS max_id
                FROM mutasi
                GROUP BY siswa_id
            ) m2
            ON m1.id = m2.max_id
            WHERE m1.status_mutasi='dibatalkan'
        ";

        $this->db->from('mutasi');

        $this->db->join(
            'siswa',
            'siswa.id = mutasi.siswa_id'
        );

        $this->db->join(
            'kelas',
            'kelas.id = mutasi.kelas_asal_id',
            'left'
        );

        $this->db->join(
            'tahun_ajaran',
            'tahun_ajaran.id = mutasi.tahun_id',
            'left'
        );

        $this->db->where(
            'mutasi.jenis',
            'keluar'
        );

        $this->db->where_not_in(
            'mutasi.siswa_id',
            $subquery,
            false
        );
    }

    /* ==========================================================
     * LIST DATA
     * ========================================================== */

    public function getData()
    {
        $this->_baseQuery();

        $this->db->select("
            mutasi.*,

            siswa.nis,
            siswa.nisn,
            siswa.nama,

            kelas.nama AS nama_kelas,

            tahun_ajaran.tahun
        ");

        $this->db->order_by(
            'mutasi.tanggal',
            'DESC'
        );

        return $this->db
            ->get()
            ->result();
    }

}