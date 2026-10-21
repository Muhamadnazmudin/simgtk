<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table            = 'siswa';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'nis',
        'nisn',
        'nik',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'no_hp',
        'email',
        'foto',
        'id_kelas',
        'tahun_id',
        'status',
        'rfid_uid',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'no_hp_ortu',
        'password',
    ];
    protected $useTimestamps    = false;

    public function get_all(?int $limit = null, ?int $offset = null): array
    {
        $builder = $this->select('siswa.*, kelas.nama AS nama_kelas, tahun_ajaran.tahun AS tahun_ajaran')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.tahun_id', 'left')
            ->orderBy('siswa.id', 'DESC');

        if ($limit !== null) {
            $builder->limit($limit, $offset ?? 0);
        }

        return $builder->findAll();
    }

    public function get_by_id(int $id): ?object
    {
        return $this->find($id);
    }

    public function get_by_kelas(int $kelasId): array
    {
        return $this->select('siswa.*, kelas.nama AS nama_kelas, tahun_ajaran.tahun AS tahun_ajaran')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.tahun_id', 'left')
            ->where('siswa.id_kelas', $kelasId)
            ->where('siswa.status', 'aktif')
            ->orderBy('siswa.nama', 'ASC')
            ->findAll();
    }

    public function get_by_status($status = ['aktif']): array
    {
        $builder = $this->select('siswa.*, kelas.nama AS nama_kelas, tahun_ajaran.tahun AS tahun_ajaran')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.tahun_id', 'left');

        if (is_array($status)) {
            $builder->whereIn('siswa.status', $status);
        } else {
            $builder->where('siswa.status', $status);
        }

        return $builder->findAll();
    }

    public function get_kelas_list(): array
    {

        return $this->db->table('kelas')->orderBy('nama', 'ASC')->get()->getResult();
    }

    public function get_tahun_list(): array
    {
        return $this->db->table('tahun_ajaran')->orderBy('id', 'DESC')->get()->getResult();
    }
}


