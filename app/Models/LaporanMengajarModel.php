<?php

namespace App\Models;

use CodeIgniter\Model;
use DateInterval;
use DatePeriod;
use DateTime;

class LaporanMengajarModel extends Model
{
    public function getGuru(): array
    {
        return $this->db->table('guru')
            ->select('id, nama')
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResult();
    }

    public function getLaporan(?string $tanggal = null, $guruId = null): array
    {
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        $hariMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        $hari = $hariMap[date('l', strtotime($tanggal))] ?? 'Senin';

        $builder = $this->db->table('jadwal_mengajar j')
            ->select("
                '{$tanggal}' AS tanggal,
                g.nama AS nama_guru,
                m.nama_mapel,
                k.nama AS nama_kelas,
                js1.nama_jam AS jam_awal,
                js2.nama_jam AS jam_akhir,
                js1.jam_mulai AS jam_mulai_jadwal,
                js2.jam_selesai AS jam_selesai_jadwal,
                lm.status,
                lm.jam_mulai,
                lm.jam_selesai,
                lm.selfie,
                lm.catatan_keluar,
                hl.jam_mulai AS jam_libur
            ")
            ->join('guru g', 'g.id = j.guru_id')
            ->join('mapel m', 'm.id_mapel = j.mapel_id')
            ->join('kelas k', 'k.id = j.rombel_id')
            ->join('jam_sekolah js1', 'js1.id_jam = j.jam_mulai_id')
            ->join('jam_sekolah js2', 'js2.id_jam = j.jam_selesai_id')
            ->join('log_mengajar lm', "lm.jadwal_id = j.id_jadwal AND DATE(lm.tanggal) = '{$tanggal}'", 'left')
            ->join('hari_libur hl', "hl.start = '{$tanggal}'", 'left')
            ->where('j.hari', $hari);

        if ($guruId) {
            $builder->where('j.guru_id', (int) $guruId);
        }

        $data = $builder->orderBy('g.nama', 'ASC')
            ->orderBy('js1.urutan', 'ASC')
            ->get()
            ->getResult();

        foreach ($data as &$d) {
            $d->status_laporan = 'Tidak Mengajar';

            if (!empty($d->status)) {
                switch ($d->status) {
                    case 'mulai':
                        $d->status_laporan = 'Sedang Mengajar';
                        break;
                    case 'selesai':
                        $d->status_laporan = 'Selesai';
                        break;
                    case 'izin':
                        $d->status_laporan = 'Izin';
                        break;
                    case 'sakit':
                        $d->status_laporan = 'Sakit';
                        break;
                    case 'dinas':
                        $d->status_laporan = 'Dinas';
                        break;
                    default:
                        $d->status_laporan = ucfirst($d->status);
                        break;
                }
                continue;
            }

            if ($this->isLiburFull($tanggal)) {
                $d->status_laporan = 'Libur';
                continue;
            }

            if ($d->jam_libur && strtotime((string) $d->jam_mulai_jadwal) >= strtotime((string) $d->jam_libur)) {
                $d->status_laporan = 'Libur';
                continue;
            }

            if (strtotime((string) $d->jam_mulai_jadwal) > time()) {
                $d->status_laporan = 'Belum Waktu';
            }
        }
        unset($d);

        return $data;
    }

    private function isLiburFull(string $tanggal): bool
    {
        return $this->db->table('hari_libur')
            ->where('start', $tanggal)
            ->where('jam_mulai IS NULL', null, false)
            ->countAllResults() > 0;
    }

    public function getRekap(?string $tanggal = null, $guruId = null): array
    {
        $builder = $this->db->table('log_mengajar lm')
            ->select('g.nama AS nama_guru, COUNT(lm.id) AS total_mengajar')
            ->join('guru g', 'g.id = lm.guru_id')
            ->where('lm.status', 'selesai');

        if ($tanggal) {
            $builder->where('lm.tanggal', $tanggal);
        }
        if ($guruId) {
            $builder->where('lm.guru_id', (int) $guruId);
        }

        return $builder->groupBy('lm.guru_id')
            ->orderBy('g.nama', 'ASC')
            ->get()
            ->getResult();
    }

    public function getLaporanRange(?string $awal = null, ?string $akhir = null, $guruId = null): array
    {
        if (!$awal || !$akhir) {
            $awal  = date('Y-m-d');
            $akhir = date('Y-m-d');
        }

        $hasil = [];
        $periode = new DatePeriod(
            new DateTime($awal),
            new DateInterval('P1D'),
            (new DateTime($akhir))->modify('+1 day')
        );

        foreach ($periode as $tgl) {
            $tanggal = $tgl->format('Y-m-d');
            $dataHarian = $this->getLaporan($tanggal, $guruId);
            foreach ($dataHarian as $row) {
                $hasil[] = $row;
            }
        }

        return $hasil;
    }
}
