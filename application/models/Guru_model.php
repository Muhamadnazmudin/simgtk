<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guru_model extends CI_Model
{
    private $table = 'guru';


    // =========================================================
    // AMBIL DATA GURU
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
                'nip',
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
    // HITUNG TOTAL GURU
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
                'nip',
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
    // INSERT
    // =========================================================

    public function insert($data)
    {
        $this->db->trans_start();


        // -----------------------------------------------------
        // INSERT GURU
        // -----------------------------------------------------

        $this->db->insert(
            'guru',
            $data
        );


        $guru_id =
            $this->db->insert_id();


        // -----------------------------------------------------
        // INSERT USER GURU
        // -----------------------------------------------------

        $user = [

            'username' =>
                isset($data['email'])
                ? $data['email']
                : '',

            'password' =>
                password_hash(
                    'KOSONG',
                    PASSWORD_DEFAULT
                ),

            'nama' =>
                isset($data['nama'])
                ? $data['nama']
                : '',

            'email' =>
                isset($data['email'])
                ? $data['email']
                : '',

            'role_id' =>
                3,

            'guru_id' =>
                $guru_id
        ];


        $this->db->insert(
            'users',
            $user
        );


        $this->db->trans_complete();


        return $this->db->trans_status();
    }


    // =========================================================
    // AMBIL GURU BERDASARKAN ID
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
                'guru',
                [
                    'id' => $id
                ]
            )
            ->row();
    }
}