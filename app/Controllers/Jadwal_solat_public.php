<?php

namespace App\Controllers;

/**
 * Controller Jadwal Salat Public - CodeIgniter 4
 */
class Jadwal_solat_public extends BaseController
{
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');

        $tanggalMulai = date('Y-m-d');
        $tanggalAkhir = date('Y-m-d', strtotime('+6 days'));

        $jadwal = [];
        if ($this->db->tableExists('absensi_solat_sesi')) {
            $jadwal = $this->db->table('absensi_solat_sesi')
                ->select('tanggal, jam_mulai AS jam_dzuhur')
                ->where('tanggal >=', $tanggalMulai)
                ->where('tanggal <=', $tanggalAkhir)
                ->where('jenis_solat', 'dzuhur')
                ->orderBy('tanggal', 'ASC')
                ->get()
                ->getResultArray();
        }

        $jadwalByTanggal = [];
        foreach ($jadwal as $row) {
            if (!empty($row['tanggal'])) {
                $jadwalByTanggal[$row['tanggal']] = $row;
            }
        }

        $daftarHari = [];
        for ($i = 0; $i < 7; $i++) {
            $tanggal = date('Y-m-d', strtotime($tanggalMulai . ' +' . $i . ' days'));
            $dataJadwal = $jadwalByTanggal[$tanggal] ?? null;
            $jamDzuhur  = ($dataJadwal && !empty($dataJadwal['jam_dzuhur'])) ? $dataJadwal['jam_dzuhur'] : null;

            $daftarHari[] = [
                'tanggal'    => $tanggal,
                'hari'       => $this->namaHari($tanggal),
                'tanggal_id' => $this->tanggalIndonesia($tanggal),
                'jam_dzuhur' => $jamDzuhur,
                'kota'       => 'Kuningan',
                'sumber'     => 'Input Admin',
                'tersedia'   => !empty($jamDzuhur),
            ];
        }

        $data = [
            'title'              => 'Jadwal Salat Dzuhur',
            'jadwal'             => $daftarHari,
            'tanggal_hari_ini'   => $tanggalMulai,
            'tanggal_diperbarui' => date('d-m-Y H:i:s'),
        ];

        return view('dashboard/jadwal_solat_public', $data);
    }

    private function namaHari(string $tanggal): string
    {
        $hari = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];
        $nama = date('l', strtotime($tanggal));
        return $hari[$nama] ?? $nama;
    }

    private function tanggalIndonesia(string $tanggal): string
    {
        $bulan = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $ts = strtotime($tanggal);
        return date('j', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
    }
}
