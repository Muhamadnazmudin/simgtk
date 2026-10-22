<?php

namespace App\Models;

use CodeIgniter\Model;

class Laporan_guru_model extends Model
{
    protected $table = 'log_mengajar';

    public function get_laporan_bulanan($guru_id, $bulan, $tahun)
    {
        $awalBulan  = sprintf('%04d-%02d-01', (int) $tahun, (int) $bulan);
        $akhirBulan = date('Y-m-t', strtotime($awalBulan));

        $data = $this->db->table('log_mengajar lm')
            ->select('
                lm.tanggal,
                DAYNAME(lm.tanggal) AS hari,
                k.nama AS nama_kelas,
                m.nama_mapel,
                js1.nama_jam AS jam_awal,
                TIME(lm.jam_mulai) AS jam_mulai,
                TIME(lm.jam_selesai) AS jam_selesai,
                lm.materi,
                lm.selfie
            ')
            ->join('jadwal_mengajar j', 'j.id_jadwal = lm.jadwal_id', 'left')
            ->join('kelas k', 'k.id = j.rombel_id', 'left')
            ->join('mapel m', 'm.id_mapel = j.mapel_id', 'left')
            ->join('jam_sekolah js1', 'js1.id_jam = j.jam_mulai_id', 'left')
            ->where('lm.guru_id', (int) $guru_id)
            ->where('lm.tanggal >=', $awalBulan)
            ->where('lm.tanggal <=', $akhirBulan)
            ->where('lm.status', 'selesai')
            ->orderBy('lm.tanggal', 'ASC')
            ->orderBy('lm.jam_mulai', 'ASC')
            ->get()
            ->getResult();

        return [
            'rekap'  => $data,
            'selfie' => array_filter($data, fn($d) => !empty($d->selfie)),
        ];
    }
}
