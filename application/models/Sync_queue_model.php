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
    /**
 * Tandai siswa karena perubahan BIODATA
 */
public function tandai_biodata_pending($siswa_id)
{
    $siswa_id = (int) $siswa_id;

    if (!$siswa_id) {
        return false;
    }

    $existing = $this->db
        ->where('siswa_id', $siswa_id)
        ->where_in('status', ['pending', 'processing'])
        ->get($this->table)
        ->row();

    // Jika sudah ada queue
    if ($existing) {

        return $this->db
            ->where('id', $existing->id)
            ->update($this->table, [
                'biodata_pending' => 1,
                'status'          => 'pending',
                'updated_at'      => date('Y-m-d H:i:s'),
                'synced_at'       => null,
                'error_message'   => null
            ]);
    }

    // Jika belum ada queue
    return $this->db->insert($this->table, [
        'siswa_id'         => $siswa_id,
        'biodata_pending'  => 1,
        'berkas_pending'   => 0,
        'status'           => 'pending',
        'created_at'       => date('Y-m-d H:i:s'),
        'updated_at'       => date('Y-m-d H:i:s'),
        'synced_at'        => null,
        'error_message'    => null
    ]);
}


/**
 * Tandai siswa karena perubahan BERKAS
 */
public function tandai_berkas_pending($siswa_id)
{
    $siswa_id = (int) $siswa_id;

    if (!$siswa_id) {
        return false;
    }

    $existing = $this->db
        ->where('siswa_id', $siswa_id)
        ->where_in('status', ['pending', 'processing'])
        ->get($this->table)
        ->row();

    // Jika sudah ada queue
    if ($existing) {

        return $this->db
            ->where('id', $existing->id)
            ->set('berkas_pending', 'berkas_pending + 1', false)
            ->set('status', 'pending')
            ->set('updated_at', date('Y-m-d H:i:s'))
            ->set('synced_at', null)
            ->set('error_message', null)
            ->update($this->table);
    }

    // Jika belum ada queue
    return $this->db->insert($this->table, [
        'siswa_id'         => $siswa_id,
        'biodata_pending'  => 0,
        'berkas_pending'   => 1,
        'status'           => 'pending',
        'created_at'       => date('Y-m-d H:i:s'),
        'updated_at'       => date('Y-m-d H:i:s'),
        'synced_at'        => null,
        'error_message'    => null
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

    /**
 * Ambil detail siswa yang menunggu sinkronisasi
 */
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
}