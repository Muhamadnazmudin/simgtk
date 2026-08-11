<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_queue_model extends CI_Model
{
    private $table = 'sync_queue';

    /**
     * Tandai siswa sebagai perlu sinkronisasi
     *
     * Kalau sudah pending, tidak membuat record baru.
     */
    public function tandai_pending($siswa_id)
{
    $siswa_id = (int) $siswa_id;

    if (!$siswa_id) {
        return false;
    }

    // Cek apakah siswa sudah punya queue
    $existing = $this->db
        ->where('siswa_id', $siswa_id)
        ->get($this->table)
        ->row();

    // Kalau sudah ada
    if ($existing) {

        return $this->db
            ->where('id', $existing->id)
            ->update($this->table, [
                'status'        => 'pending',
                'updated_at'    => date('Y-m-d H:i:s'),
                'synced_at'     => null,
                'error_message' => null
            ]);
    }

    // Kalau belum ada
    return $this->db->insert($this->table, [
        'siswa_id'       => $siswa_id,
        'status'         => 'pending',
        'created_at'     => date('Y-m-d H:i:s'),
        'updated_at'     => date('Y-m-d H:i:s'),
        'synced_at'      => null,
        'error_message'  => null
    ]);
}


    /**
     * Ambil semua siswa yang menunggu sync
     */
    public function get_pending()
    {
        return $this->db
            ->where('status', 'pending')
            ->order_by('updated_at', 'ASC')
            ->get($this->table)
            ->result();
    }


    /**
     * Ambil satu queue berdasarkan ID
     */
    public function get_by_id($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->get($this->table)
            ->row();
    }


    /**
     * Tandai sedang diproses
     */
    public function set_processing($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, [
                'status'     => 'processing',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }


    /**
     * Tandai berhasil
     */
    public function set_synced($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, [
                'status'        => 'synced',
                'synced_at'     => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
                'error_message' => null
            ]);
    }


    /**
     * Tandai gagal
     */
    public function set_error($id, $message)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, [
                'status'        => 'error',
                'updated_at'    => date('Y-m-d H:i:s'),
                'error_message' => $message
            ]);
    }


    /**
     * Hitung jumlah yang menunggu sync
     */
    public function count_pending()
    {
        return $this->db
            ->where('status', 'pending')
            ->count_all_results($this->table);
    }


    /**
     * Kembalikan error menjadi pending
     */
    public function retry($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->where('status', 'error')
            ->update($this->table, [
                'status'        => 'pending',
                'updated_at'    => date('Y-m-d H:i:s'),
                'error_message' => null
            ]);
    }
}