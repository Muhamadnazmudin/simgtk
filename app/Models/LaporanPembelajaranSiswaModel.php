<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanPembelajaranSiswaModel extends Model
{
    protected $table = 'laporan_pembelajaran_siswa';
    protected $primaryKey = 'id';
    protected $returnType = 'object';

    public function getAll(array $filter = []): array
    {
        $builder = $this->db->table('laporan_pembelajaran_siswa l')
            ->select('
                l.*,
                k.nama AS nama_kelas,
                s.nama AS nama_siswa,
                g.nama AS nama_guru,
                m.nama_mapel,
                j1.nama_jam AS jam_awal,
                j1.jam_mulai,
                j2.nama_jam AS jam_akhir,
                j2.jam_selesai
            ')
            ->join('kelas k', 'k.id = l.kelas_id', 'left')
            ->join('siswa s', 's.id = l.siswa_id', 'left')
            ->join('guru g', 'g.id = l.guru_id', 'left')
            ->join('mapel m', 'm.id_mapel = l.mapel_id', 'left')
            ->join('jam_sekolah j1', 'j1.id_jam = l.jam_mulai_id', 'left')
            ->join('jam_sekolah j2', 'j2.id_jam = l.jam_selesai_id', 'left');

        if (!empty($filter['kelas_id'])) {
            $builder->where('l.kelas_id', (int) $filter['kelas_id']);
        }
        if (!empty($filter['tanggal'])) {
            $builder->where('l.tanggal', $filter['tanggal']);
        }
        if (!empty($filter['guru_id'])) {
            $builder->where('l.guru_id', (int) $filter['guru_id']);
        }

        return $builder->orderBy('l.id', 'DESC')->get()->getResult();
    }
}
