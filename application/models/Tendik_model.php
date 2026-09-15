<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tendik_model extends CI_Model
{
    private $table = 'tendik';


    // =========================================================
    // AMBIL DATA TENDIK
    // DENGAN SEARCH + PAGINATION
    // =========================================================

    public function get_all(
        $limit = null,
        $offset = null,
        $search = ''
    ) {

        if (!empty($search)) {

            $this->db->group_start();

            $this->db->like(
                'nama',
                $search
            );

            $this->db->or_like(
                'nuptk',
                $search
            );

            $this->db->or_like(
                'nip',
                $search
            );

            $this->db->or_like(
                'jenis_ptk',
                $search
            );

            $this->db->or_like(
                'status_kepegawaian',
                $search
            );

            $this->db->group_end();
        }


        $this->db->order_by(
            'nama',
            'ASC'
        );


        if ($limit !== null) {

            $this->db->limit(
                $limit,
                $offset
            );
        }


        return $this->db
            ->get($this->table)
            ->result();
    }


    // =========================================================
    // HITUNG TOTAL TENDIK
    // DENGAN SEARCH
    // =========================================================

    public function count_all(
        $search = ''
    ) {

        if (!empty($search)) {

            $this->db->group_start();

            $this->db->like(
                'nama',
                $search
            );

            $this->db->or_like(
                'nuptk',
                $search
            );

            $this->db->or_like(
                'nip',
                $search
            );

            $this->db->or_like(
                'jenis_ptk',
                $search
            );

            $this->db->or_like(
                'status_kepegawaian',
                $search
            );

            $this->db->group_end();
        }


        return $this->db
            ->count_all_results(
                $this->table
            );
    }


    // =========================================================
    // CEK NUPTK
    // =========================================================

    public function exists_nuptk(
        $nuptk,
        $exclude_id = null
    ) {

        if (empty($nuptk)) {
            return false;
        }


        $this->db
            ->where(
                'nuptk',
                $nuptk
            );


        if ($exclude_id !== null) {

            $this->db
                ->where(
                    'id !=',
                    $exclude_id
                );
        }


        return $this->db
            ->count_all_results(
                $this->table
            ) > 0;
    }


    // =========================================================
    // INSERT
    // =========================================================

    public function insert($data)
    {
        return $this->db
            ->insert(
                $this->table,
                $data
            );
    }


    // =========================================================
    // AMBIL TENDIK BERDASARKAN ID
    // =========================================================

    public function get_by_id($id)
    {
        return $this->db
            ->get_where(
                $this->table,
                [
                    'id' => $id
                ]
            )
            ->row();
    }


    // =========================================================
    // UPDATE
    // =========================================================

    public function update(
        $id,
        $data
    ) {

        $this->db
            ->where(
                'id',
                $id
            );

        return $this->db
            ->update(
                $this->table,
                $data
            );
    }


    // =========================================================
    // DELETE
    // =========================================================

    public function delete($id)
    {
        $this->db
            ->where(
                'id',
                $id
            );

        return $this->db
            ->delete(
                $this->table
            );
    }


    // =========================================================
    // GET
    // =========================================================

    public function get($id)
    {
        return $this->db
            ->get_where(
                $this->table,
                [
                    'id' => $id
                ]
            )
            ->row();
    }

    public function import_upsert($data)
{
    if (empty($data['nuptk'])) {
        return [
            'status'  => false,
            'action'  => 'skip',
            'message' => 'NUPTK kosong'
        ];
    }

    $nuptk = trim($data['nuptk']);

    /*
    |--------------------------------------------------------------------------
    | CARI DATA BERDASARKAN NUPTK
    |--------------------------------------------------------------------------
    */
    $existing = $this->db
        ->where('nuptk', $nuptk)
        ->get($this->table)
        ->row();

    /*
    |--------------------------------------------------------------------------
    | BELUM ADA → INSERT
    |--------------------------------------------------------------------------
    */
    if (!$existing) {

        $this->db->insert($this->table, $data);

        return [
            'status'  => $this->db->affected_rows() > 0,
            'action'  => 'insert',
            'message' => 'Data baru ditambahkan'
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CEK APAKAH ADA PERUBAHAN
    |--------------------------------------------------------------------------
    */
    $changed = false;

    foreach ($data as $field => $value) {

        if ($field === 'id') {
            continue;
        }

        $old_value = isset($existing->$field)
            ? $existing->$field
            : null;

        /*
        |--------------------------------------------------------------------------
        | NORMALISASI NULL / STRING KOSONG
        |--------------------------------------------------------------------------
        */
        if ($value === '') {
            $value = null;
        }

        if ($old_value === '') {
            $old_value = null;
        }

        if ((string) $old_value !== (string) $value) {
            $changed = true;
            break;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DATA SAMA → TIDAK PERLU UPDATE
    |--------------------------------------------------------------------------
    */
    if (!$changed) {

        return [
            'status'  => true,
            'action'  => 'same',
            'message' => 'Data tidak berubah'
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | DATA BERUBAH → UPDATE
    |--------------------------------------------------------------------------
    */
    $this->db
        ->where('id', $existing->id)
        ->update($this->table, $data);

    return [
        'status'  => $this->db->affected_rows() >= 0,
        'action'  => 'update',
        'message' => 'Data diperbarui'
    ];
}
}