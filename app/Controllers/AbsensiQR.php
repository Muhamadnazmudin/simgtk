<?php

namespace App\Controllers;

use App\Models\AbsensiQRModel;

class AbsensiQR extends BaseController
{
    protected AbsensiQRModel $qrModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->qrModel = new AbsensiQRModel();
    }

    public function scan(?string $token = null)
    {
        // Jika request berupa AJAX atau meminta JSON dengan parameter token
        if ($this->request->isAJAX() || !empty($token) || $this->request->getPost('token') || $this->request->getGet('token')) {
            $tok = $token ?: ($this->request->getPost('token') ?: $this->request->getGet('token'));
            if (!empty($tok) && ($this->request->isAJAX() || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest' || $this->request->getGet('format') === 'json')) {
                return $this->proses_scan($tok);
            }
        }

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

        // Jika ada token via URL non-AJAX (legacy fallback)
        if (!empty($token)) {
            $res = $this->proses_scan_internal($token);
            if ($res['status'] === 'error') {
                return $this->response->setBody("<h2 style='color:red;'>{$res['message']}</h2><meta http-equiv='refresh' content='2; url=" . site_url('AbsensiQR/scan') . "'>");
            }
            return $this->compatView->render('absensiqr/hasil', [
                'type'             => $res['type'],
                'nama'             => $res['nama'],
                'jam_masuk'        => $res['jam'] ?? $res['jam_masuk'] ?? '-',
                'jam_pulang'       => $res['jam_pulang'] ?? null,
                'jam_now'          => $res['jam_now'] ?? date('H:i:s'),
                'status'           => $res['status_absen'] ?? '-',
                'jam_resmi_masuk'  => $jamMasukResmi,
                'jam_resmi_pulang' => $jamPulangResmi,
            ]);
        }

        $data = [
            'jam_masuk'    => $jamMasukResmi,
            'jam_pulang'   => $jamPulangResmi,
            'hari_nama'    => $hariNama,
            'stats'        => $this->getStatsToday($tanggalHariIni),
            'recent_scans' => $this->getRecentScans($tanggalHariIni, 10),
        ];

        return $this->compatView->render('absensiqr/scan', $data);
    }

    public function proses_scan(?string $tokenParam = null)
    {
        $token  = trim((string) ($tokenParam ?: ($this->request->getPost('token') ?: $this->request->getGet('token'))));
        $sumber = (string) ($this->request->getPost('sumber') ?: 'scan_qr');

        $result = $this->proses_scan_internal($token, $sumber);
        return $this->response->setJSON($result);
    }

    private function proses_scan_internal(string $token, string $sumber = 'scan_qr'): array
    {
        if (empty($token)) {
            return [
                'status'  => 'error',
                'type'    => 'empty',
                'message' => 'Token QR tidak boleh kosong.',
            ];
        }

        $hariIndex = (int) date('N');
        $hariNamaMap = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis',
            5 => 'Jumat', 6 => 'Sabtu',  7 => 'Minggu',
        ];
        $hariNama = $hariNamaMap[$hariIndex] ?? 'Senin';
        $tanggalHariIni = date('Y-m-d');
        $jamNow = date('H:i:s');

        $cekLibur = $this->db->table('hari_libur')->where('start', $tanggalHariIni)->get()->getRow();
        $isWeekend = ($hariNama === 'Sabtu' || $hariNama === 'Minggu');

        if ($cekLibur || $isWeekend) {
            $alasan = $cekLibur ? $cekLibur->nama : "Hari {$hariNama} Libur";
            return [
                'status'  => 'warning',
                'type'    => 'libur',
                'message' => "Hari ini libur: {$alasan}",
                'stats'   => $this->getStatsToday($tanggalHariIni),
            ];
        }

        $jadwal = $this->db->table('absensi_jadwal')->where('hari', $hariNama)->get()->getRow();
        $jamMasukResmi  = $jadwal ? $jadwal->jam_masuk  : '07:00:00';
        $jamPulangResmi = $jadwal ? $jadwal->jam_pulang : '14:00:00';

        $siswa = $this->qrModel->getSiswaByToken($token);
        if (!$siswa) {
            return [
                'status'  => 'error',
                'type'    => 'not_found',
                'message' => "QR Code tidak dikenali! NISN/NIS tidak terdaftar.",
                'token'   => $token,
                'stats'   => $this->getStatsToday($tanggalHariIni),
            ];
        }

        $nis   = $siswa->nis;
        $absen = $this->qrModel->getAbsenHariIni($nis, $tanggalHariIni);

        // Cari info kelas
        $namaKelas = $siswa->nama_kelas ?? '-';
        if (empty($namaKelas) || $namaKelas === '-') {
            $kelasRow = !empty($siswa->id_kelas) ? $this->db->table('kelas')->where('id', $siswa->id_kelas)->get()->getRow() : null;
            if ($kelasRow) {
                $namaKelas = $kelasRow->nama;
            }
        }

        // Cari foto siswa jika ada
        $fotoUrl = '';
        if (!empty($siswa->foto)) {
            if (file_exists(FCPATH . 'uploads/siswa/' . $siswa->foto)) {
                $fotoUrl = base_url('uploads/siswa/' . $siswa->foto);
            } elseif (file_exists(FCPATH . 'assets/img/siswa/' . $siswa->foto)) {
                $fotoUrl = base_url('assets/img/siswa/' . $siswa->foto);
            }
        }

        // 1. Belum ada catatan absensi hari ini -> Catat Jam Masuk
        if (!$absen) {
            if (strtotime($jamNow) <= strtotime($jamMasukResmi)) {
                $status = 'Tepat';
                $keteranganTelat = null;
                $deskripsi = 'Hadir Tepat Waktu';
            } else {
                $status = 'Terlambat';
                $telatDetik = strtotime($jamNow) - strtotime($jamMasukResmi);
                $keteranganTelat = $this->formatTelat($telatDetik);
                $deskripsi = 'Hadir Terlambat (' . $keteranganTelat . ')';
            }

            $insert = [
                'nis'              => $nis,
                'tanggal'          => $tanggalHariIni,
                'jam_masuk'        => $jamNow,
                'status'           => $status,
                'kehadiran'        => 'H',
                'keterangan_telat' => $keteranganTelat,
                'sumber'           => $sumber,
            ];

            $this->qrModel->insert($insert);

            return [
                'status'           => 'success',
                'type'             => 'masuk',
                'message'          => 'Absen Masuk Berhasil!',
                'nama'             => $siswa->nama,
                'nis'              => $siswa->nis,
                'nisn'             => $siswa->nisn ?? '-',
                'kelas'            => $namaKelas,
                'foto'             => $fotoUrl,
                'jam'              => $jamNow,
                'status_absen'     => $status,
                'keterangan_telat' => $keteranganTelat,
                'deskripsi'        => $deskripsi,
                'stats'            => $this->getStatsToday($tanggalHariIni),
            ];
        }

        // 2. Sudah absen pulang
        if ($absen->jam_pulang !== null) {
            return [
                'status'       => 'warning',
                'type'         => 'sudah_pulang',
                'message'      => 'Siswa sudah melakukan absen pulang sebelumnya.',
                'nama'         => $siswa->nama,
                'nis'          => $siswa->nis,
                'kelas'        => $namaKelas,
                'foto'         => $fotoUrl,
                'jam'          => $absen->jam_pulang,
                'jam_masuk'    => $absen->jam_masuk,
                'jam_pulang'   => $absen->jam_pulang,
                'status_absen' => $absen->status,
                'deskripsi'    => 'Sudah Pulang (' . $absen->jam_pulang . ')',
                'stats'        => $this->getStatsToday($tanggalHariIni),
            ];
        }

        // 3. Belum waktunya jam pulang
        if (strtotime($jamNow) < strtotime($jamPulangResmi)) {
            return [
                'status'           => 'warning',
                'type'             => 'belum_waktu',
                'message'          => "Belum waktunya jam pulang (Resmi: {$jamPulangResmi}).",
                'nama'             => $siswa->nama,
                'nis'              => $siswa->nis,
                'kelas'            => $namaKelas,
                'foto'             => $fotoUrl,
                'jam_now'          => $jamNow,
                'jam_masuk'        => $absen->jam_masuk,
                'jam_resmi_pulang' => $jamPulangResmi,
                'status_absen'     => $absen->status,
                'deskripsi'        => "Sudah Absen Masuk ({$absen->jam_masuk}), Pulang: {$jamPulangResmi}",
                'stats'            => $this->getStatsToday($tanggalHariIni),
            ];
        }

        // 4. Catat absen pulang
        $this->qrModel->update($absen->id, ['jam_pulang' => $jamNow]);

        return [
            'status'       => 'success',
            'type'         => 'pulang',
            'message'      => 'Absen Pulang Berhasil!',
            'nama'         => $siswa->nama,
            'nis'          => $siswa->nis,
            'kelas'        => $namaKelas,
            'foto'         => $fotoUrl,
            'jam'          => $jamNow,
            'jam_masuk'    => $absen->jam_masuk,
            'status_absen' => $absen->status,
            'deskripsi'    => 'Pulang Pukul ' . $jamNow,
            'stats'        => $this->getStatsToday($tanggalHariIni),
        ];
    }

    public function recent_scans()
    {
        $tanggalHariIni = date('Y-m-d');
        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->getRecentScans($tanggalHariIni, 10),
            'stats'  => $this->getStatsToday($tanggalHariIni),
        ]);
    }

    private function getStatsToday(?string $tanggal = null): array
    {
        $tanggal = $tanggal ?: date('Y-m-d');
        $row = $this->db->table('absensi_qr')
            ->select('
                COUNT(id) AS total,
                SUM(CASE WHEN status = "Tepat" THEN 1 ELSE 0 END) AS tepat,
                SUM(CASE WHEN status = "Terlambat" THEN 1 ELSE 0 END) AS telat,
                SUM(CASE WHEN jam_pulang IS NOT NULL THEN 1 ELSE 0 END) AS pulang
            ')
            ->where('tanggal', $tanggal)
            ->get()
            ->getRow();

        return [
            'total'  => (int) ($row->total ?? 0),
            'tepat'  => (int) ($row->tepat ?? 0),
            'telat'  => (int) ($row->telat ?? 0),
            'pulang' => (int) ($row->pulang ?? 0),
        ];
    }

    private function getRecentScans(?string $tanggal = null, int $limit = 10): array
    {
        $tanggal = $tanggal ?: date('Y-m-d');
        return $this->db->table('absensi_qr q')
            ->select('
                q.id,
                q.nis,
                q.jam_masuk,
                q.jam_pulang,
                q.status,
                q.kehadiran,
                q.keterangan_telat,
                s.nama AS nama_siswa,
                s.foto,
                k.nama AS nama_kelas
            ')
            ->join('siswa s', 's.nis = q.nis', 'left')
            ->join('kelas k', 'k.id = s.id_kelas', 'left')
            ->where('q.tanggal', $tanggal)
            ->orderBy('q.id', 'DESC')
            ->limit($limit)
            ->get()
            ->getResult();
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
