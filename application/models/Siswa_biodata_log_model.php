<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_biodata_log_model extends CI_Model
{
    protected $table = 'siswa_biodata_log';


    // =========================================================
    // SIMPAN / TIMPA PERUBAHAN
    // =========================================================

    public function save_change(
        $siswa_id,
        $field_name,
        $field_label,
        $old_value,
        $new_value,
        $changed_by
    ) {

        /*
         * Jika field yang sama sudah memiliki log,
         * update log tersebut.
         *
         * Jadi:
         *
         * Nama Ayah
         * Budi -> Ahmad
         *
         * kemudian diubah lagi:
         *
         * Ahmad -> Dedi
         *
         * maka log menjadi:
         *
         * Budi -> Dedi
         *
         * bukan membuat baris baru.
         */

        $existing = $this->db
            ->where('siswa_id', $siswa_id)
            ->where('field_name', $field_name)
            ->get($this->table)
            ->row();


        if ($existing) {

            return $this->db
                ->where('id', $existing->id)
                ->update($this->table, [

                    'old_value'  => $old_value,
                    'new_value'  => $new_value,
                    'changed_by' => $changed_by,
                    'changed_at' => date('Y-m-d H:i:s')

                ]);

        }


        return $this->db->insert($this->table, [

            'siswa_id'    => $siswa_id,
            'field_name'  => $field_name,
            'field_label' => $field_label,
            'old_value'   => $old_value,
            'new_value'   => $new_value,
            'changed_by'  => $changed_by,
            'changed_at'  => date('Y-m-d H:i:s')

        ]);
    }


    // =========================================================
    // JUMLAH SISWA YANG MEMILIKI PERUBAHAN
    // =========================================================

    public function count_siswa_summary($search = '', $kelas_id = '')
    {
        $this->db
            ->select('siswa_biodata_log.siswa_id')
            ->from($this->table)
            ->join(
                'siswa',
                'siswa.id = siswa_biodata_log.siswa_id',
                'left'
            );


        if ($search !== '') {

            $this->db->group_start();

            $this->db
                ->like('siswa.nama', $search)
                ->or_like('siswa.nisn', $search);

            $this->db->group_end();
        }


        if ($kelas_id !== '') {

            $this->db->where(
                'siswa.id_kelas',
                $kelas_id
            );
        }


        $this->db->group_by(
            'siswa_biodata_log.siswa_id'
        );


        $query = $this->db->get();

        return $query->num_rows();
    }


    // =========================================================
    // DATA RINGKASAN SISWA
    // 1 SISWA = 1 BARIS
    // =========================================================

    public function get_siswa_summary(
        $limit = 25,
        $offset = 0,
        $search = '',
        $kelas_id = ''
    ) {

        $this->db->select('
            siswa_biodata_log.siswa_id,
            siswa.nama AS nama_siswa,
            siswa.nisn,
            kelas.nama AS nama_kelas,
            COUNT(siswa_biodata_log.id) AS jumlah_perubahan,
            MAX(siswa_biodata_log.changed_at) AS terakhir_diubah
        ');

        $this->db->from($this->table);

        $this->db->join(
            'siswa',
            'siswa.id = siswa_biodata_log.siswa_id',
            'left'
        );

        $this->db->join(
            'kelas',
            'kelas.id = siswa.id_kelas',
            'left'
        );


        // =====================================================
        // SEARCH
        // =====================================================

        if ($search !== '') {

            $this->db->group_start();

            $this->db
                ->like('siswa.nama', $search)
                ->or_like('siswa.nisn', $search);

            $this->db->group_end();
        }


        // =====================================================
        // FILTER KELAS
        // =====================================================

        if ($kelas_id !== '') {

            $this->db->where(
                'siswa.id_kelas',
                $kelas_id
            );
        }


        // =====================================================
        // GROUP
        // =====================================================

        $this->db->group_by(
            'siswa_biodata_log.siswa_id'
        );


        // =====================================================
        // SORT
        // =====================================================

        $this->db->order_by(
            'terakhir_diubah',
            'DESC'
        );


        // =====================================================
        // PAGINATION
        // =====================================================

        $this->db->limit(
            (int) $limit,
            (int) $offset
        );


        return $this->db
            ->get()
            ->result();
    }


    // =========================================================
    // DETAIL PERUBAHAN SATU SISWA
    // =========================================================

    public function get_by_siswa($siswa_id)
    {
        return $this->db
            ->select('
                siswa_biodata_log.*,
                siswa.nama AS nama_siswa,
                siswa.nisn,
                kelas.nama AS nama_kelas
            ')
            ->from($this->table)
            ->join(
                'siswa',
                'siswa.id = siswa_biodata_log.siswa_id',
                'left'
            )
            ->join(
                'kelas',
                'kelas.id = siswa.id_kelas',
                'left'
            )
            ->where(
                'siswa_biodata_log.siswa_id',
                $siswa_id
            )
            ->order_by(
                'siswa_biodata_log.changed_at',
                'DESC'
            )
            ->get()
            ->result();
    }


    // =========================================================
    // DAFTAR KELAS UNTUK FILTER
    // =========================================================

    public function get_kelas()
    {
        return $this->db
            ->order_by('nama', 'ASC')
            ->get('kelas')
            ->result();
    }


    

    // =========================================================
    // SISWA YANG PERNAH MELAKUKAN PERUBAHAN
    // =========================================================

    // =========================================================
// TOTAL SISWA YANG PERNAH MENGUBAH
// =========================================================

public function count_all_siswa($kelas_id = '')
{
    $this->db
        ->select('siswa_biodata_log.siswa_id')
        ->from($this->table)
        ->join(
            'siswa',
            'siswa.id = siswa_biodata_log.siswa_id',
            'left'
        );


    if ($kelas_id !== '' && $kelas_id !== null) {

        $this->db->where(
            'siswa.id_kelas',
            $kelas_id
        );
    }


    $this->db->group_by(
        'siswa_biodata_log.siswa_id'
    );


    return $this->db
        ->get()
        ->num_rows();
}


// =========================================================
// TOTAL LOG
// =========================================================

public function count_all_log($kelas_id = '')
{
    $this->db
        ->from($this->table)
        ->join(
            'siswa',
            'siswa.id = siswa_biodata_log.siswa_id',
            'left'
        );


    if ($kelas_id !== '' && $kelas_id !== null) {

        $this->db->where(
            'siswa.id_kelas',
            $kelas_id
        );
    }


    return $this->db
        ->count_all_results();
}


// =========================================================
// PERUBAHAN HARI INI
// =========================================================

public function count_today($kelas_id = '')
{
    $this->db
        ->from($this->table)
        ->join(
            'siswa',
            'siswa.id = siswa_biodata_log.siswa_id',
            'left'
        );


    $this->db->where(
        'DATE(siswa_biodata_log.changed_at)',
        date('Y-m-d')
    );


    if ($kelas_id !== '' && $kelas_id !== null) {

        $this->db->where(
            'siswa.id_kelas',
            $kelas_id
        );
    }


    return $this->db
        ->count_all_results();
}
}