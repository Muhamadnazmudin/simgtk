<?php

namespace App\Models;

use CodeIgniter\Model;

class MonitoringMengajarModel extends Model
{
    protected $table = 'jadwal_mengajar';
    protected $primaryKey = 'id_jadwal';
    protected $returnType = 'object';

    public function getMonitoring($guruId = null, $kelasId = null): array
    {
        $mapHari = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        $hari = $mapHari[date('l')] ?? 'Senin';

        $builder = $this->db->table('jadwal_mengajar j')
            ->select("
                j.id_jadwal,
                g.id AS guru_id,
                g.nama AS nama_guru,
                k.id AS kelas_id,
                k.nama AS nama_kelas,
                m.nama_mapel,
                js1.nama_jam AS jam_awal,
                js1.jam_mulai,
                js2.nama_jam AS jam_akhir,
                js2.jam_selesai,
                lm.jam_mulai AS log_mulai,
                lm.jam_selesai AS log_selesai,
                lm.status,
                lm.materi,
                lm.selfie
            ")
            ->join('guru g', 'g.id = j.guru_id')
            ->join('kelas k', 'k.id = j.rombel_id')
            ->join('mapel m', 'm.id_mapel = j.mapel_id')
            ->join('jam_sekolah js1', 'js1.id_jam = j.jam_mulai_id')
            ->join('jam_sekolah js2', 'js2.id_jam = j.jam_selesai_id')
            ->join('log_mengajar lm', 'lm.jadwal_id = j.id_jadwal AND DATE(lm.tanggal) = CURDATE()', 'left')
            ->where('j.hari', $hari);

        if (!empty($guruId)) {
            $builder->where('g.id', (int) $guruId);
        }

        if (!empty($kelasId)) {
            $builder->where('k.id', (int) $kelasId);
        }

        return $builder->orderBy('js1.urutan', 'ASC')
            ->orderBy('g.nama', 'ASC')
            ->get()
            ->getResult();
    }

    public function getGuru(): array
    {
        return $this->db->table('guru')
            ->select('id, nama')
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResult();
    }

    public function getKelas(): array
    {
        return $this->db->table('kelas')
            ->select('id, nama')
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResult();
    }
}
