<?php

namespace App\Models;

use CodeIgniter\Model;

class SyncQueueModel extends Model
{
    protected $table            = 'sync_queue';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'siswa_id',
        'biodata_pending',
        'berkas_pending',
        'status',
        'synced_at',
        'error_message',
        'created_at',
        'updated_at',
    ];

    public function tandai_biodata_pending(int $siswaId)
    {
        if ($siswaId <= 0) {
            return false;
        }

        $existing = $this->where('siswa_id', $siswaId)->first();

        if ($existing) {
            return $this->update($existing->id, [
                'biodata_pending' => 1,
                'status'          => 'pending',
                'updated_at'      => date('Y-m-d H:i:s'),
                'synced_at'       => null,
                'error_message'   => null,
            ]);
        }

        return $this->insert([
            'siswa_id'        => $siswaId,
            'biodata_pending' => 1,
            'berkas_pending'  => 0,
            'status'          => 'pending',
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
            'synced_at'       => null,
            'error_message'   => null,
        ]);
    }

    public function tandai_berkas_pending(int $siswaId)
    {
        if ($siswaId <= 0) {
            return false;
        }

        $existing = $this->where('siswa_id', $siswaId)->first();

        if ($existing) {
            return $this->update($existing->id, [
                'berkas_pending' => 1,
                'status'         => 'pending',
                'updated_at'     => date('Y-m-d H:i:s'),
                'synced_at'      => null,
                'error_message'  => null,
            ]);
        }

        return $this->insert([
            'siswa_id'        => $siswaId,
            'biodata_pending' => 0,
            'berkas_pending'  => 1,
            'status'          => 'pending',
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
            'synced_at'       => null,
            'error_message'   => null,
        ]);
    }

    public function tandai_pending(int $siswaId)
    {
        if ($siswaId <= 0) {
            return false;
        }

        $existing = $this->where('siswa_id', $siswaId)->first();

        if ($existing) {
            return $this->update($existing->id, [
                'status'        => 'pending',
                'updated_at'    => date('Y-m-d H:i:s'),
                'synced_at'     => null,
                'error_message' => null,
            ]);
        }

        return $this->insert([
            'siswa_id'        => $siswaId,
            'biodata_pending' => 0,
            'berkas_pending'  => 0,
            'status'          => 'pending',
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
            'synced_at'       => null,
            'error_message'   => null,
        ]);
    }

    public function get_pending()
    {
        return $this->where('status', 'pending')
            ->orderBy('updated_at', 'ASC')
            ->findAll();
    }

    public function get_pending_detail()
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)
            ->select('
                sync_queue.*,
                siswa.nisn,
                siswa.nama,
                kelas.nama AS kelas,
                COUNT(siswa_berkas.id) AS jumlah_berkas
            ')
            ->join('siswa', 'siswa.id = sync_queue.siswa_id', 'left')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('siswa_berkas', 'siswa_berkas.siswa_id = sync_queue.siswa_id', 'left')
            ->where('sync_queue.status', 'pending')
            ->groupBy('sync_queue.id')
            ->orderBy('sync_queue.updated_at', 'ASC')
            ->get()
            ->getResult();
    }

    public function get_by_id($id)
    {
        return $this->find((int) $id);
    }

    public function set_processing($id)
    {
        return $this->update((int) $id, [
            'status'     => 'processing',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function set_synced($id)
    {
        return $this->update((int) $id, [
            'status'          => 'synced',
            'biodata_pending' => 0,
            'berkas_pending'  => 0,
            'synced_at'       => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
            'error_message'   => null,
        ]);
    }

    public function set_error($id, $message)
    {
        return $this->update((int) $id, [
            'status'        => 'error',
            'updated_at'    => date('Y-m-d H:i:s'),
            'error_message' => $message,
        ]);
    }

    public function count_pending(): int
    {
        return (int) $this->where('status', 'pending')->countAllResults();
    }

    public function retry($id)
    {
        return $this->where('id', (int) $id)
            ->where('status', 'error')
            ->set([
                'status'        => 'pending',
                'updated_at'    => date('Y-m-d H:i:s'),
                'error_message' => null,
            ])
            ->update();
    }
}
