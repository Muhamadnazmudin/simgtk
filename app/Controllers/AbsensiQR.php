<?php

namespace App\Controllers;

use App\Models\AbsensiQRModel;

class AbsensiQR extends BaseController
{
    protected AbsensiQRModel $qrModel;

    public function __construct()
    {
        $this->qrModel = new AbsensiQRModel();
    }

    public function scan(?string $token = null)
    {
        $hariIndex = (int) date('N');
        $hariNamaMap = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis',
            5 => 'Jumat', 6 => 'Sabtu',  7 => 'Minggu',
        ];
        $hariNama = $hariNamaMap[$hariIndex] ?? 'Senin';
        $tanggalHariIni = date('Y-m-d');

        $cekLibur = $this->db->table('hari_libur')->where('start', $tanggalHariIni)->get()->getRow();
        $isWeekend = ($hariNama === 'Sabtu' || $hariNama === 'Minggu');

        if ($cekLibur || $isWeekend) {
            $data = [
                'hari_nama'        => $hariNama,
                'libur'            => true,
                'keterangan_libur' => $cekLibur ? $cekLibur->nama : "Hari {$hariNama}",
            ];
            return $this->compatView->render('absensiqr/scan', $data);
        }

        $jadwal = $this->db->table('absensi_jadwal')->where('hari', $hariNama)->get()->getRow();
        $jamMasukResmi  = $jadwal ? $jadwal->jam_masuk  : '07:00:00';
        $jamPulangResmi = $jadwal ? $jadwal->jam_pulang : '14:00:00';

        if (empty($token)) {
            $data = [
                'jam_masuk'  => $jamMasukResmi,
                'jam_pulang' => $jamPulangResmi,
                'hari_nama'  => $hariNama,
            ];
            return $this->compatView->render('absensiqr/scan', $data);
        }

        $siswa = $this->qrModel->getSiswaByToken($token);
        if (!$siswa) {
            return $this->response->setBody("<h2 style='color:red;'>QR tidak dikenal!</h2>");
        }

        $nis     = $siswa->nis;
        $tanggal = date('Y-m-d');
        $jamNow  = date('H:i:s');

        $absen = $this->qrModel->getAbsenHariIni($nis, $tanggal);

        if (!$absen) {
            if (strtotime($jamNow) <= strtotime($jamMasukResmi)) {
                $status = 'Tepat';
                $keteranganTelat = null;
            } else {
                $status = 'Terlambat';
                $telatDetik = strtotime($jamNow) - strtotime($jamMasukResmi);
                $keteranganTelat = $this->formatTelat($telatDetik);
            }

            $insert = [
                'nis'              => $nis,
                'tanggal'          => $tanggal,
                'jam_masuk'        => $jamNow,
                'status'           => $status,
                'kehadiran'        => 'H',
                'keterangan_telat' => $keteranganTelat,
                'sumber'           => 'scan_qr',
            ];

            $this->qrModel->insert($insert);

            return $this->compatView->render('absensiqr/hasil', [
                'type'             => 'masuk',
                'nama'             => $siswa->nama,
                'jam_masuk'        => $jamNow,
                'status'           => $status,
                'jam_resmi_masuk'  => $jamMasukResmi,
                'jam_resmi_pulang' => $jamPulangResmi,
            ]);
        }

        if ($absen->jam_pulang !== null) {
            return $this->compatView->render('absensiqr/hasil', [
                'type' => 'sudah_pulang',
                'nama' => $siswa->nama,
            ]);
        }

        if (strtotime($jamNow) < strtotime($jamPulangResmi)) {
            return $this->compatView->render('absensiqr/hasil', [
                'type'             => 'belum_waktu',
                'nama'             => $siswa->nama,
                'jam_now'          => $jamNow,
                'jam_resmi_pulang' => $jamPulangResmi,
            ]);
        }

        $this->qrModel->update($absen->id, ['jam_pulang' => $jamNow]);

        return $this->compatView->render('absensiqr/hasil', [
            'type'       => 'pulang',
            'nama'       => $siswa->nama,
            'jam_pulang' => $jamNow,
        ]);
    }

    public function index()
    {
        $this->requireAuth();
        return redirect()->to(site_url('AbsensiQRAdmin'));
    }

    public function simpan()
    {
        $this->requireAuth();

        $data = [
            'nis'        => (string) $this->request->getPost('nis'),
            'tanggal'    => (string) $this->request->getPost('tanggal'),
            'jam_masuk'  => (string) $this->request->getPost('jam_masuk'),
            'jam_pulang' => $this->request->getPost('jam_pulang') ?: null,
            'kehadiran'  => (string) $this->request->getPost('kehadiran'),
            'status'     => (string) $this->request->getPost('status'),
        ];

        $this->qrModel->insert($data);
        $this->session->setFlashdata('success', 'Data absensi berhasil ditambahkan!');
        return redirect()->to(site_url('AbsensiQRAdmin'));
    }

    public function update()
    {
        $this->requireAuth();

        $id = (int) $this->request->getPost('id');
        $data = [
            'kehadiran'  => (string) $this->request->getPost('kehadiran'),
            'status'     => (string) $this->request->getPost('status'),
            'tanggal'    => (string) $this->request->getPost('tanggal'),
            'jam_masuk'  => (string) $this->request->getPost('jam_masuk'),
            'jam_pulang' => $this->request->getPost('jam_pulang') ?: null,
        ];

        $this->qrModel->update($id, $data);
        $this->session->setFlashdata('success', 'Data absensi berhasil diupdate!');
        return redirect()->to(site_url('AbsensiQRAdmin'));
    }

    public function hapus($id = null)
    {
        $this->requireAuth();

        if (!empty($id)) {
            $this->qrModel->delete($id);
            $this->session->setFlashdata('success', 'Data berhasil dihapus!');
        }
        return redirect()->to(site_url('AbsensiQRAdmin'));
    }

    private function formatTelat(int $totalDetik): string
    {
        $jam   = floor($totalDetik / 3600);
        $menit = floor(($totalDetik % 3600) / 60);
        $detik = $totalDetik % 60;

        $hasil = [];
        if ($jam > 0)   $hasil[] = $jam . ' jam';
        if ($menit > 0) $hasil[] = $menit . ' menit';
        if ($detik > 0) $hasil[] = $detik . ' detik';

        return implode(' ', $hasil);
    }
}
