<?php

namespace App\Controllers;

/**
 * Guru_dashboard Controller - CodeIgniter 4
 */
class Guru_dashboard extends BaseController
{
    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        helper('tanggal');
    }

    public function index()
    {
        if (
            !$this->session->get('logged_in') ||
            !in_array((int) $this->session->get('role_id'), [3, 4], true)
        ) {
            return redirect()->to('/auth/login');
        }

        $guruId = (int) $this->session->get('guru_id');

        // Check holiday
        $today = date('Y-m-d');
        $libur = $this->db->table('hari_libur')
            ->where('tanggal', $today)
            ->get()
            ->getRow();

        if ($libur && empty($libur->jam_mulai)) {
            $data = [
                'is_libur'   => true,
                'libur_half' => false,
                'nama_libur' => $libur->nama,
                'title'      => 'Dashboard Guru',
                'active'     => 'guru_dashboard',
            ];
            return $this->renderView('guru_dashboard/index', $data);
        }

        $hari = $this->hariIni();

        // Get teacher schedule today
        $jadwal = $this->db->table('jadwal_mengajar j')
            ->select('
                j.id_jadwal AS jadwal_id,
                j.hari,
                k.nama AS nama_kelas,
                m.nama_mapel,
                js1.nama_jam AS jam_awal,
                js1.jam_mulai AS jam_mulai,
                js2.nama_jam AS jam_akhir,
                js2.jam_selesai AS jam_selesai
            ')
            ->join('kelas k', 'k.id = j.rombel_id', 'left')
            ->join('mapel m', 'm.id_mapel = j.mapel_id', 'left')
            ->join('jam_sekolah js1', 'js1.id_jam = j.jam_mulai_id', 'left')
            ->join('jam_sekolah js2', 'js2.id_jam = j.jam_selesai_id', 'left')
            ->where('j.guru_id', $guruId)
            ->where('j.hari', $hari)
            ->orderBy('js1.urutan', 'ASC')
            ->get()
            ->getResult();

        if (!empty($jadwal)) {
            foreach ($jadwal as &$j) {
                $j->is_libur = false;
                if ($libur && !empty($libur->jam_mulai)) {
                    if (strtotime($j->jam_mulai) >= strtotime($libur->jam_mulai)) {
                        $j->is_libur = true;
                    }
                }

                if (!$j->is_libur) {
                    $j->log = $this->db->table('log_mengajar')
                        ->where('jadwal_id', $j->jadwal_id)
                        ->where('guru_id', $guruId)
                        ->where('tanggal', $today)
                        ->get()
                        ->getRow();
                } else {
                    $j->log = null;
                }
            }
            unset($j);
        }

        $data = [
            'is_libur'        => false,
            'libur_half'      => ($libur && !empty($libur->jam_mulai)),
            'jam_libur'       => $libur->jam_mulai ?? null,
            'nama_libur'      => $libur->nama ?? null,
            'hari_ini'        => $hari,
            'jadwal_hari_ini' => $jadwal,
            'title'           => 'Dashboard Guru',
            'active'          => 'guru_dashboard',
        ];

        return $this->renderView('guru_dashboard/index', $data);
    }

    private function hariIni(): string
    {
        $hariInggris = date('l');
        $map = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        return $map[$hariInggris] ?? $hariInggris;
    }
}
