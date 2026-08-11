<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_queue_model extends CI_Model
{
    private $table = 'sync_queue';


    // =========================================================
    // TANDAI BIODATA PENDING
    // =========================================================

    public function tandai_biodata_pending($siswa_id)
    {
        $siswa_id = (int) $siswa_id;

        if (!$siswa_id) {
            return false;
        }

        $existing = $this->db
            ->where('siswa_id', $siswa_id)
            ->get($this->table)
            ->row();

        // =====================================================
        // SUDAH ADA → UPDATE
        // =====================================================

        if ($existing) {

            return $this->db
                ->where('id', $existing->id)
                ->update($this->table, [

                    'biodata_pending' => 1,

                    'status' => 'pending',

                    'updated_at' =>
                        date('Y-m-d H:i:s'),

                    'synced_at' => null,

                    'error_message' => null
                ]);
        }


        // =====================================================
        // BELUM ADA → INSERT
        // =====================================================

        return $this->db->insert($this->table, [

            'siswa_id' => $siswa_id,

            'biodata_pending' => 1,

            'berkas_pending' => 0,

            'status' => 'pending',

            'created_at' =>
                date('Y-m-d H:i:s'),

            'updated_at' =>
                date('Y-m-d H:i:s'),

            'synced_at' => null,

            'error_message' => null
        ]);
    }


    // =========================================================
    // TANDAI BERKAS PENDING
    // =========================================================

    public function tandai_berkas_pending($siswa_id)
    {
        $siswa_id = (int) $siswa_id;

        if (!$siswa_id) {
            return false;
        }

        $existing = $this->db
            ->where('siswa_id', $siswa_id)
            ->get($this->table)
            ->row();

        // =====================================================
        // SUDAH ADA → UPDATE
        // =====================================================

        if ($existing) {

            return $this->db
                ->where('id', $existing->id)
                ->update($this->table, [

                    'berkas_pending' => 1,

                    'status' => 'pending',

                    'updated_at' =>
                        date('Y-m-d H:i:s'),

                    'synced_at' => null,

                    'error_message' => null
                ]);
        }


        // =====================================================
        // BELUM ADA → INSERT
        // =====================================================

        return $this->db->insert($this->table, [

            'siswa_id' => $siswa_id,

            'biodata_pending' => 0,

            'berkas_pending' => 1,

            'status' => 'pending',

            'created_at' =>
                date('Y-m-d H:i:s'),

            'updated_at' =>
                date('Y-m-d H:i:s'),

            'synced_at' => null,

            'error_message' => null
        ]);
    }


    // =========================================================
    // METHOD LAMA / UMUM
    // =========================================================

    public function tandai_pending($siswa_id)
    {
        $siswa_id = (int) $siswa_id;

        if (!$siswa_id) {
            return false;
        }

        $existing = $this->db
            ->where('siswa_id', $siswa_id)
            ->get($this->table)
            ->row();

        if ($existing) {

            return $this->db
                ->where('id', $existing->id)
                ->update($this->table, [

                    'status' => 'pending',

                    'updated_at' =>
                        date('Y-m-d H:i:s'),

                    'synced_at' => null,

                    'error_message' => null
                ]);
        }

        return $this->db->insert($this->table, [

            'siswa_id' => $siswa_id,

            'biodata_pending' => 0,

            'berkas_pending' => 0,

            'status' => 'pending',

            'created_at' =>
                date('Y-m-d H:i:s'),

            'updated_at' =>
                date('Y-m-d H:i:s'),

            'synced_at' => null,

            'error_message' => null
        ]);
    }


    // =========================================================
    // AMBIL PENDING
    // =========================================================

    public function get_pending()
    {
        return $this->db
            ->where('status', 'pending')
            ->order_by('updated_at', 'ASC')
            ->get($this->table)
            ->result();
    }


    // =========================================================
    // DETAIL PENDING
    // =========================================================

    public function get_pending_detail()
    {
        return $this->db
            ->select('
                sync_queue.*,
                siswa.nisn,
                siswa.nama,
                kelas.nama AS kelas,
                COUNT(siswa_berkas.id) AS jumlah_berkas
            ')
            ->from($this->table)
            ->join(
                'siswa',
                'siswa.id = sync_queue.siswa_id',
                'left'
            )
            ->join(
                'kelas',
                'kelas.id = siswa.id_kelas',
                'left'
            )
            ->join(
                'siswa_berkas',
                'siswa_berkas.siswa_id = sync_queue.siswa_id',
                'left'
            )
            ->where(
                'sync_queue.status',
                'pending'
            )
            ->group_by('sync_queue.id')
            ->order_by(
                'sync_queue.updated_at',
                'ASC'
            )
            ->get()
            ->result();
    }


    // =========================================================
    // GET BY ID
    // =========================================================

    public function get_by_id($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->get($this->table)
            ->row();
    }


    // =========================================================
    // PROCESSING
    // =========================================================

    public function set_processing($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, [

                'status' => 'processing',

                'updated_at' =>
                    date('Y-m-d H:i:s')
            ]);
    }


    // =========================================================
    // SYNCED
    // =========================================================

    public function set_synced($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, [

                'status' => 'synced',

                'biodata_pending' => 0,

                'berkas_pending' => 0,

                'synced_at' =>
                    date('Y-m-d H:i:s'),

                'updated_at' =>
                    date('Y-m-d H:i:s'),

                'error_message' => null
            ]);
    }


    // =========================================================
    // ERROR
    // =========================================================

    public function set_error($id, $message)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, [

                'status' => 'error',

                'updated_at' =>
                    date('Y-m-d H:i:s'),

                'error_message' => $message
            ]);
    }


    // =========================================================
    // COUNT PENDING
    // =========================================================

    public function count_pending()
    {
        return $this->db
            ->where('status', 'pending')
            ->count_all_results($this->table);
    }


    // =========================================================
    // RETRY ERROR
    // =========================================================

    public function retry($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->where('status', 'error')
            ->update($this->table, [

                'status' => 'pending',

                'updated_at' =>
                    date('Y-m-d H:i:s'),

                'error_message' => null
            ]);
    }
}