<?php

namespace App\Models;

use CodeIgniter\Model;

class BerkasSiswaModel extends Model
{
    protected $table            = 'siswa_berkas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = false;
    protected $useTimestamps    = false;

    public function jenisBerkas(): array
    {
        return [
            'kartu_keluarga' => 'Kartu Keluarga',
            'akta_lahir'     => 'Akta Lahir',
            'ijazah_sd'      => 'Ijazah SD',
            'ijazah_smp'     => 'Ijazah SMP/MTs',
            'ktp_ayah'       => 'KTP Ayah',
            'ktp_ibu'        => 'KTP Ibu',
        ];
    }

    public function generateAccessToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    public function getSiswaById(int $id): ?object
    {
        return $this->db->table('siswa s')
            ->select('s.*, k.nama AS nama_kelas, g.nama AS nama_walikelas')
            ->join('kelas k', 'k.id = s.id_kelas', 'left')
            ->join('guru g', 'g.id = k.wali_kelas_id', 'left')
            ->where('s.id', $id)
            ->where('s.status', 'aktif')
            ->get()
            ->getRow();
    }

    public function getBerkasSiswa(int $siswaId): array
    {
        return $this->db->table($this->table)
            ->where('siswa_id', $siswaId)
            ->get()
            ->getResult();
    }

    public function countSiswaAktif(string $keyword = '', string $kelasId = ''): int
    {
        $builder = $this->db->table('siswa s')
            ->where('s.status', 'aktif');

        if (!empty($kelasId)) {
            $builder->where('s.id_kelas', $kelasId);
        }

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('s.nama', $keyword)
                ->orLike('s.nis', $keyword)
                ->orLike('s.nisn', $keyword)
                ->groupEnd();
        }

        return $builder->countAllResults();
    }

    public function getSiswaAktif(int $limit, int $offset, string $keyword = '', string $kelasId = ''): array
    {
        $builder = $this->db->table('siswa s')
            ->select('s.*, k.nama AS nama_kelas, COUNT(sb.id) AS jumlah_berkas')
            ->join('kelas k', 'k.id = s.id_kelas', 'left')
            ->join('siswa_berkas sb', 'sb.siswa_id = s.id', 'left')
            ->where('s.status', 'aktif');

        if (!empty($kelasId)) {
            $builder->where('s.id_kelas', $kelasId);
        }

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('s.nama', $keyword)
                ->orLike('s.nis', $keyword)
                ->orLike('s.nisn', $keyword)
                ->groupEnd();
        }

        return $builder->groupBy('s.id, k.nama')
            ->orderBy('k.nama', 'ASC')
            ->orderBy('s.nama', 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResult();
    }

    public function getKelasAktif(): array
    {
        return $this->db->table('kelas k')
            ->select('k.id, k.nama, COUNT(s.id) AS jumlah_siswa')
            ->join('siswa s', "s.id_kelas = k.id AND s.status = 'aktif'", 'inner')
            ->groupBy('k.id, k.nama')
            ->orderBy('k.nama', 'ASC')
            ->get()
            ->getResult();
    }
}
