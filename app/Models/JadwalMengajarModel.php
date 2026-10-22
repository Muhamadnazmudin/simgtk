<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalMengajarModel extends Model
{
    protected $table            = 'jadwal_mengajar';
    protected $primaryKey       = 'id_jadwal';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = false;
    protected $useTimestamps    = false;

    public function getAll(array $filter = []): array
    {
        $builder = $this->db->table($this->table . ' j')
            ->select('
                j.id_jadwal,
                j.hari,
                g.nama AS nama_guru,
                k.nama AS nama_kelas,
                m.nama_mapel,
                js1.nama_jam AS jam_awal,
                js1.jam_mulai AS jam_mulai,
                js2.nama_jam AS jam_akhir,
                js2.jam_selesai AS jam_selesai
            ')
            ->join('guru g', 'g.id = j.guru_id')
            ->join('kelas k', 'k.id = j.rombel_id')
            ->join('mapel m', 'm.id_mapel = j.mapel_id')
            ->join('jam_sekolah js1', 'js1.id_jam = j.jam_mulai_id')
            ->join('jam_sekolah js2', 'js2.id_jam = j.jam_selesai_id');

        if (!empty($filter['hari'])) {
            $builder->where('j.hari', $filter['hari']);
        }
        if (!empty($filter['guru_id'])) {
            $builder->where('j.guru_id', $filter['guru_id']);
        }
        if (!empty($filter['kelas_id'])) {
            $builder->where('j.rombel_id', $filter['kelas_id']);
        }

        return $builder->orderBy('j.hari', 'ASC')
            ->orderBy('js1.urutan', 'ASC')
            ->get()
            ->getResult();
    }

    public function jamMengajar(): array
    {
        return $this->db->table('jam_sekolah')
            ->where('jenis', 'Mengajar')
            ->where('is_active', 1)
            ->orderBy('urutan', 'ASC')
            ->get()
            ->getResult();
    }

    public function getBentrokDetail(string $hari, int $jamAwal, int $jamAkhir, int $guruId, int $rombelId): ?object
    {
        return $this->db->table('jadwal_mengajar j')
            ->select('j.hari, g.nama AS nama_guru, k.nama AS nama_kelas')
            ->join('guru g', 'g.id = j.guru_id')
            ->join('kelas k', 'k.id = j.rombel_id')
            ->where('j.hari', $hari)
            ->groupStart()
                ->where('j.guru_id', $guruId)
                ->orWhere('j.rombel_id', $rombelId)
            ->groupEnd()
            ->where('j.jam_mulai_id <=', $jamAkhir)
            ->where('j.jam_selesai_id >=', $jamAwal)
            ->get()
            ->getRow();
    }
}
