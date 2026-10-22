<?php

namespace App\Controllers;

use App\Models\AbsensiSolatModel;
use DateTime;
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

/**
 * Controller Absensi Salat - CodeIgniter 4 & PHP 8.3
 * Secure Prayer Attendance Module with QR Code & RFID Reader
 */
class Absensi_solat extends BaseController
{
    protected AbsensiSolatModel $solat;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->solat = new AbsensiSolatModel();
        date_default_timezone_set('Asia/Jakarta');
    }

    private function adminDiizinkan(): bool
    {
        $roleId = (int) $this->session->get('role_id');
        return $this->session->get('logged_in') === true
            && in_array($roleId, [1, 2, 3, 4, 5], true);
    }

    private function wajibAdmin()
    {
        if (!$this->session->get('logged_in')) {
            $this->session->setFlashdata('error', 'Silakan login terlebih dahulu.');
            header('Location: ' . base_url('auth/login'));
            exit;
        }

        if (!$this->adminDiizinkan()) {
            return $this->response->setStatusCode(403)->setBody(
                view('errors/html/error_403', [
                    'message' => 'Anda tidak memiliki hak akses untuk fitur ini.'
                ])
            );
        }
        return true;
    }

    // =====================================================
    // DASHBOARD ABSENSI SALAT
    // =====================================================
    public function index()
    {
        $check = $this->wajibAdmin();
        if ($check !== true) {
            return $check;
        }

        $tahunId = $this->tahunAktifId();

        $data['title']                = 'Absensi Salat Dzuhur';
        $data['judul']                = 'Absensi Salat Dzuhur';
        $data['tahun_id']             = $tahunId;
        $data['sesi']                 = [];
        $data['statistik_sesi']       = 0;
        $data['statistik_hadir']      = 0;
        $data['statistik_terlambat']  = 0;
        $data['statistik_belum_absen'] = 0;

        if ($tahunId > 0) {
            $data['sesi'] = $this->solat->get_sesi($tahunId);

            $sekarangTanggal = date('Y-m-d');
            $sekarangJam     = date('H:i:s');

            foreach ($data['sesi'] as $item) {
                if ($item->tanggal < $sekarangTanggal || $item->tanggal > $sekarangTanggal) {
                    $item->status = 'tutup';
                } elseif ($sekarangJam >= $item->jam_mulai && $sekarangJam <= $item->jam_selesai) {
                    $item->status = 'buka';
                } else {
                    $item->status = 'tutup';
                }
            }

            $data['statistik_sesi'] = count($data['sesi']);

            foreach ($data['sesi'] as $item) {
                $stat = $this->solat->get_statistik((int) $item->id);
                $data['statistik_hadir']     += $stat['total_hadir'];
                $data['statistik_terlambat'] += $stat['terlambat'];
            }
        }

        return $this->renderView('absensi_solat/index', $data);
    }

    // =====================================================
    // MEMBUAT SESI ABSENSI
    // =====================================================
    public function buat_sesi()
    {
        $check = $this->wajibAdmin();
        if ($check !== true) {
            return $check;
        }

        if (!$this->request->is('post')) {
            return $this->response->setStatusCode(405)->setBody('Metode request tidak diizinkan.');
        }

        $tahunId = $this->tahunAktifId();
        if ($tahunId <= 0) {
            return redirect()->to('/absensi_solat')->with('error', 'Tahun ajaran aktif belum tersedia.');
        }

        $tanggal    = trim((string) $this->request->getPost('tanggal'));
        $jamMulai   = trim((string) $this->request->getPost('jam_mulai'));
        $jamSelesai = trim((string) $this->request->getPost('jam_selesai'));

        if (!$this->tanggalValid($tanggal) || !$this->jamValid($jamMulai) || !$this->jamValid($jamSelesai)) {
            return redirect()->to('/absensi_solat')->with('error', 'Tanggal atau jam sesi tidak valid.');
        }

        if (strtotime($jamSelesai) <= strtotime($jamMulai)) {
            return redirect()->to('/absensi_solat')->with('error', 'Jam selesai harus setelah jam mulai.');
        }

        $sesiLama = $this->solat->get_sesi_tanggal($tahunId, $tanggal);
        if ($sesiLama) {
            return redirect()->to('/absensi_solat')->with('error', 'Sesi salat Dzuhur untuk tanggal tersebut sudah tersedia.');
        }

        $berhasil = $this->solat->buat_sesi([
            'tahun_id'    => $tahunId,
            'tanggal'     => $tanggal,
            'jenis_solat' => 'dzuhur',
            'jam_mulai'   => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'status'      => 'buka',
            'dibuat_oleh' => (int) $this->session->get('user_id'),
        ]);

        return redirect()->to('/absensi_solat')->with(
            $berhasil ? 'success' : 'error',
            $berhasil ? 'Sesi absensi salat Dzuhur berhasil dibuat.' : 'Sesi gagal dibuat.'
        );
    }

    // =====================================================
    // HALAMAN SCANNER
    // =====================================================
    public function scan($id = null)
    {
        $check = $this->wajibAdmin();
        if ($check !== true) {
            return $check;
        }

        $tahunId = $this->tahunAktifId();
        $sesi = $this->solat->get_sesi_by_id((int) $id, $tahunId);

        if (!$sesi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Sesi absensi tidak ditemukan.');
        }

        $tanggalSekarang = date('Y-m-d');
        $jamSekarang     = date('H:i:s');

        if (
            $sesi->tanggal === $tanggalSekarang &&
            $jamSekarang >= $sesi->jam_mulai &&
            $jamSekarang <= $sesi->jam_selesai
        ) {
            $sesi->status = 'buka';
        } else {
            $sesi->status = 'tutup';
        }

        $data['title']     = 'Scan Absensi Salat Dzuhur';
        $data['judul']     = 'Scan Absensi Salat Dzuhur';
        $data['tahun_id']  = $tahunId;
        $data['sesi']      = $sesi;
        $data['statistik'] = $this->solat->get_statistik((int) $sesi->id);

        return $this->renderView('absensi_solat/scan', $data);
    }

    // =====================================================
    // PROSES SCAN (AJAX - WITH RATE LIMITING)
    // =====================================================
    public function proses_scan()
    {
        if (!$this->adminDiizinkan()) {
            return $this->jsonResponse(['success' => false, 'message' => 'Anda tidak memiliki hak akses.'], 403);
        }

        if (!$this->request->is('post')) {
            return $this->jsonResponse(['success' => false, 'message' => 'Metode request tidak diizinkan.'], 405);
        }

        // Throttle scans: Max 60 scans per minute per IP to mitigate DoS
        $throttler = service('throttler');
        $ip = $this->request->getIPAddress();
        if ($throttler->check('scan_' . md5($ip), 60, 60) === false) {
            return $this->jsonResponse(['success' => false, 'message' => 'Terlalu banyak permintaan scan. Mohon jeda sebentar.'], 429);
        }

        $sesiId = (int) $this->request->getPost('sesi_id');
        $token  = trim((string) $this->request->getPost('token'));
        $metode = strtolower(trim((string) $this->request->getPost('metode')));

        if ($sesiId <= 0 || $token === '') {
            return $this->jsonResponse(['success' => false, 'message' => 'Data scan belum lengkap.'], 400);
        }

        if (!in_array($metode, ['qrcode', 'rfid'], true)) {
            return $this->jsonResponse(['success' => false, 'message' => 'Metode absensi tidak valid.'], 400);
        }

        $tahunId = $this->tahunAktifId();
        if ($tahunId <= 0) {
            return $this->jsonResponse(['success' => false, 'message' => 'Tahun ajaran aktif belum tersedia.'], 400);
        }

        $sesi = $this->solat->get_sesi_by_id($sesiId, $tahunId);
        if (!$sesi) {
            return $this->jsonResponse(['success' => false, 'message' => 'Sesi absensi tidak ditemukan.'], 404);
        }

        $tanggalSekarang = date('Y-m-d');
        $jamSekarang     = date('H:i:s');

        if ($sesi->tanggal !== $tanggalSekarang) {
            return $this->jsonResponse(['success' => false, 'message' => 'Sesi absensi bukan untuk tanggal hari ini.'], 400);
        }

        if ($jamSekarang < $sesi->jam_mulai || $jamSekarang > $sesi->jam_selesai) {
            return $this->jsonResponse([
                'success'     => false,
                'message'     => 'Di luar waktu absensi.',
                'jam_mulai'   => $sesi->jam_mulai,
                'jam_selesai' => $sesi->jam_selesai
            ], 400);
        }

        // Cari Siswa (QR Code = NISN, RFID = UID)
        $siswa = ($metode === 'qrcode')
            ? $this->solat->get_siswa_by_qr($token)
            : $this->solat->get_siswa_by_rfid($token);

        if (!$siswa) {
            return $this->jsonResponse([
                'success' => false,
                'message' => ($metode === 'qrcode') ? 'QR tidak dikenali. NISN tidak ditemukan.' : 'Kartu RFID tidak dikenali.'
            ], 404);
        }

        if (!isset($siswa->status) || $siswa->status !== 'aktif') {
            return $this->jsonResponse(['success' => false, 'message' => 'Siswa tidak berstatus aktif.'], 400);
        }

        $siswaId = (int) $siswa->id;
        if ($siswaId <= 0) {
            return $this->jsonResponse(['success' => false, 'message' => 'ID siswa tidak valid.'], 500);
        }

        // Cek duplikasi
        if ($this->solat->sudah_absen($sesiId, $siswaId)) {
            return $this->jsonResponse([
                'success'   => false,
                'duplicate' => true,
                'message'   => $siswa->nama . ' sudah melakukan absensi.',
                'nama'      => $siswa->nama,
                'nisn'      => $siswa->nisn ?? ''
            ], 200);
        }

        $statusAbsen = ($jamSekarang > $sesi->jam_mulai) ? 'terlambat' : 'hadir';

        $berhasil = $this->solat->simpan_absensi([
            'sesi_id'      => $sesiId,
            'siswa_id'     => $siswaId,
            'waktu_absen'  => date('Y-m-d H:i:s'),
            'metode_absen' => $metode,
            'status'       => $statusAbsen,
        ]);

        if (!$berhasil) {
            return $this->jsonResponse(['success' => false, 'message' => 'Absensi gagal disimpan.'], 500);
        }

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Absensi berhasil disimpan.',
            'nama'    => $siswa->nama,
            'nis'     => $siswa->nis ?? '',
            'nisn'    => $siswa->nisn ?? '',
            'status'  => $statusAbsen,
            'metode'  => $metode,
            'waktu'   => date('H:i:s'),
        ], 200);
    }

    // =====================================================
    // TUTUP SESI
    // =====================================================
    public function tutup_sesi($id = null)
    {
        $check = $this->wajibAdmin();
        if ($check !== true) {
            return $check;
        }

        if (!$this->request->is('post')) {
            return $this->response->setStatusCode(405)->setBody('Metode request tidak diizinkan.');
        }

        $sesi = $this->solat->get_sesi_by_id((int) $id, $this->tahunAktifId());
        if (!$sesi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Sesi tidak ditemukan.');
        }

        $berhasil = $this->solat->update_status_sesi((int) $sesi->id, 'tutup');

        return redirect()->to('/absensi_solat')->with(
            $berhasil ? 'success' : 'error',
            $berhasil ? 'Sesi absensi berhasil ditutup.' : 'Sesi absensi gagal ditutup.'
        );
    }

    // =====================================================
    // REKAP ABSENSI
    // =====================================================
    public function rekap()
    {
        $check = $this->wajibAdmin();
        if ($check !== true) {
            return $check;
        }

        $tahunId = $this->tahunAktifId();

        $filter = [
            'tahun_id'        => $tahunId,
            'tanggal_mulai'   => trim((string) $this->request->getGet('tanggal_mulai')),
            'tanggal_selesai' => trim((string) $this->request->getGet('tanggal_selesai')),
            'tanggal'         => trim((string) $this->request->getGet('tanggal')),
            'kelas_id'        => (int) $this->request->getGet('kelas_id'),
            'keyword'         => trim((string) $this->request->getGet('keyword')),
            'sesi_id'         => (int) $this->request->getGet('sesi_id'),
        ];

        if (!$this->tanggalValid($filter['tanggal_mulai'])) {
            $filter['tanggal_mulai'] = '';
        }
        if (!$this->tanggalValid($filter['tanggal_selesai'])) {
            $filter['tanggal_selesai'] = '';
        }
        if (!$this->tanggalValid($filter['tanggal'])) {
            $filter['tanggal'] = '';
        }

        $data['title']    = 'Rekap Absensi Salat';
        $data['judul']    = 'Rekap Absensi Salat';
        $data['tahun_id'] = $tahunId;
        $data['filter']   = $filter;
        $data['rekap']    = $this->solat->get_rekap($filter);
        $data['kelas']    = $this->solat->get_kelas();
        $data['sesi']     = $this->solat->get_sesi($tahunId);

        return $this->renderView('absensi_solat/rekap', $data);
    }

    // =====================================================
    // IMPORT JADWAL
    // =====================================================
    public function import_jadwal()
    {
        $check = $this->wajibAdmin();
        if ($check !== true) {
            return $check;
        }

        $data['title']    = 'Import Jadwal Sesi Salat';
        $data['judul']    = 'Import Jadwal Sesi Salat';
        $data['tahun_id'] = $this->tahunAktifId();

        return $this->renderView('absensi_solat/import_jadwal', $data);
    }

    public function download_template()
    {
        $check = $this->wajibAdmin();
        if ($check !== true) {
            return $check;
        }

        $mulai   = trim((string) $this->request->getGet('tanggal_mulai'));
        $selesai = trim((string) $this->request->getGet('tanggal_selesai'));

        if (!$this->tanggalValid($mulai) || !$this->tanggalValid($selesai) || $selesai < $mulai) {
            return redirect()->to('/absensi_solat/import_jadwal')->with('error', 'Rentang tanggal template tidak valid.');
        }

        $jumlahHari = (int) ((strtotime($selesai) - strtotime($mulai)) / 86400) + 1;
        if ($jumlahHari > 366) {
            return redirect()->to('/absensi_solat/import_jadwal')->with('error', 'Template maksimal untuk 366 hari dalam sekali unduh.');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Jadwal Sesi');
        $sheet->fromArray(['Tanggal (YYYY-MM-DD)', 'Jam Mulai (HH:MM)', 'Jam Selesai (HH:MM)'], null, 'A1');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(24);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(24);

        $baris   = 2;
        $tanggal = new DateTime($mulai);
        $batas   = new DateTime($selesai);

        while ($tanggal <= $batas) {
            $sheet->setCellValueExplicit('A' . $baris, $tanggal->format('Y-m-d'), DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $baris, '11:30');
            $sheet->setCellValue('C' . $baris, '12:00');
            $tanggal->modify('+1 day');
            $baris++;
        }

        $filename = 'template_jadwal_solat_' . $mulai . '_sampai_' . $selesai . '.xlsx';
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $spreadsheet->disconnectWorksheets();
        exit;
    }

    public function import_proses()
    {
        $check = $this->wajibAdmin();
        if ($check !== true) {
            return $check;
        }

        if (!$this->request->is('post')) {
            return $this->response->setStatusCode(405)->setBody('Metode request tidak diizinkan.');
        }

        $tahunId = $this->tahunAktifId();
        if ($tahunId <= 0) {
            return redirect()->to('/absensi_solat/import_jadwal')->with('error', 'Tahun ajaran aktif belum tersedia.');
        }

        $file = $this->request->getFile('file_jadwal');
        if (!$file || !$file->isValid()) {
            return redirect()->to('/absensi_solat/import_jadwal')->with('error', 'Pilih file Excel jadwal terlebih dahulu.');
        }

        $ext = strtolower($file->getExtension());
        if (!in_array($ext, ['xlsx', 'xls', 'csv'], true)) {
            return redirect()->to('/absensi_solat/import_jadwal')->with('error', 'Format file harus XLSX, XLS, atau CSV.');
        }

        try {
            $reader = IOFactory::createReaderForFile($file->getTempName());
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getTempName());
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);
        } catch (Exception $e) {
            log_message('error', 'Import jadwal salat gagal membaca file: ' . $e->getMessage());
            return redirect()->to('/absensi_solat/import_jadwal')->with('error', 'File tidak dapat dibaca. Gunakan template yang disediakan.');
        }

        $berhasil = 0;
        $duplikat = 0;
        $invalid  = 0;
        $errors   = [];

        $this->db->transBegin();

        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue;
            } // Header
            $tanggalRaw = $row[0] ?? '';
            $mulaiRaw   = $row[1] ?? '';
            $selesaiRaw = $row[2] ?? '';

            if (trim((string) $tanggalRaw) === '' && trim((string) $mulaiRaw) === '' && trim((string) $selesaiRaw) === '') {
                continue;
            }

            $tanggal    = $this->normalisasiTanggalExcel($tanggalRaw);
            $jamMulai   = $this->normalisasiJamExcel($mulaiRaw);
            $jamSelesai = $this->normalisasiJamExcel($selesaiRaw);

            if (!$tanggal || !$jamMulai || !$jamSelesai || $jamSelesai <= $jamMulai) {
                $invalid++;
                if (count($errors) < 10) {
                    $errors[] = 'Baris ' . ($index + 1) . ': tanggal atau jam tidak valid.';
                }
                continue;
            }

            if ($this->solat->get_sesi_tanggal($tahunId, $tanggal)) {
                $duplikat++;
                continue;
            }

            $ok = $this->solat->buat_sesi([
                'tahun_id'    => $tahunId,
                'tanggal'     => $tanggal,
                'jenis_solat' => 'dzuhur',
                'jam_mulai'   => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'status'      => 'tutup',
                'dibuat_oleh' => (int) $this->session->get('user_id'),
            ]);

            if ($ok) {
                $berhasil++;
            } else {
                $invalid++;
            }
        }

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return redirect()->to('/absensi_solat/import_jadwal')->with('error', 'Import dibatalkan karena kesalahan database.');
        }

        $this->db->transCommit();

        $pesan = 'Import selesai. Berhasil: ' . $berhasil . ', dilewati (sudah ada): ' . $duplikat . ', tidak valid: ' . $invalid . '.';
        if (!empty($errors)) {
            $pesan .= ' Detail: ' . implode(' ', $errors);
        }

        return redirect()->to('/absensi_solat')->with($invalid > 0 ? 'error' : 'success', $pesan);
    }

    private function normalisasiTanggalExcel($value)
    {
        if (is_numeric($value) && (float) $value > 20000) {
            try {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (Exception $e) {
                return false;
            }
        }
        $value = trim((string) $value);
        if ($this->tanggalValid($value)) {
            return $value;
        }
        foreach (['d/m/Y', 'd-m-Y', 'Y/m/d', 'd.m.Y'] as $format) {
            $dt = DateTime::createFromFormat('!' . $format, $value);
            if ($dt && $dt->format($format) === $value) {
                return $dt->format('Y-m-d');
            }
        }
        return false;
    }

    private function normalisasiJamExcel($value)
    {
        if (is_numeric($value) && (float) $value >= 0 && (float) $value < 1) {
            $seconds = (int) round((float) $value * 86400);
            return gmdate('H:i:s', $seconds);
        }
        $value = trim((string) $value);
        foreach (['H:i', 'H:i:s'] as $format) {
            $dt = DateTime::createFromFormat('!' . $format, $value);
            if ($dt && $dt->format($format) === $value) {
                return $dt->format('H:i:s');
            }
        }
        return false;
    }

    public function edit_sesi($id = null)
    {
        $check = $this->wajibAdmin();
        if ($check !== true) {
            return $check;
        }

        $tahunId = $this->tahunAktifId();
        $sesi = $this->solat->get_sesi_by_id((int) $id, $tahunId);
        if (!$sesi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Sesi tidak ditemukan.');
        }

        if ($this->request->is('post')) {
            $tanggal    = trim((string) $this->request->getPost('tanggal'));
            $jamMulai   = trim((string) $this->request->getPost('jam_mulai'));
            $jamSelesai = trim((string) $this->request->getPost('jam_selesai'));

            if (!$this->tanggalValid($tanggal) || !$this->jamValid($jamMulai) || !$this->jamValid($jamSelesai) || $jamSelesai <= $jamMulai) {
                return redirect()->to('/absensi_solat/edit_sesi/' . (int) $id)->with('error', 'Tanggal atau jam tidak valid.');
            }

            $sesiLain = $this->solat->get_sesi_tanggal($tahunId, $tanggal);
            if ($sesiLain && (int) $sesiLain->id !== (int) $id) {
                return redirect()->to('/absensi_solat/edit_sesi/' . (int) $id)->with('error', 'Tanggal tersebut sudah memiliki sesi.');
            }

            $ok = $this->solat->update_sesi((int) $id, $tahunId, [
                'tanggal'     => $tanggal,
                'jam_mulai'   => $jamMulai,
                'jam_selesai' => $jamSelesai,
            ]);

            return redirect()->to('/absensi_solat')->with(
                $ok ? 'success' : 'error',
                $ok ? 'Sesi berhasil diperbarui.' : 'Sesi gagal diperbarui.'
            );
        }

        $data['title']    = 'Edit Sesi Absensi';
        $data['judul']    = 'Edit Sesi Absensi';
        $data['tahun_id'] = $tahunId;
        $data['sesi']     = $sesi;

        return $this->renderView('absensi_solat/edit_sesi', $data);
    }

    private function tanggalValid($tanggal): bool
    {
        if (empty($tanggal)) {
            return false;
        }
        $dt = DateTime::createFromFormat('!Y-m-d', $tanggal);
        return $dt && $dt->format('Y-m-d') === $tanggal;
    }

    private function jamValid($jam): bool
    {
        if (empty($jam)) {
            return false;
        }
        $dt = DateTime::createFromFormat('!H:i', $jam);
        if ($dt && $dt->format('H:i') === $jam) {
            return true;
        }
        $dt2 = DateTime::createFromFormat('!H:i:s', $jam);
        return $dt2 && $dt2->format('H:i:s') === $jam;
    }

    private function jsonResponse(array $data, int $httpCode = 200)
    {
        // Provide fresh CSRF token so continuous scanning never fails
        $data['csrf_test_name'] = csrf_hash();

        return $this->response
            ->setStatusCode($httpCode)
            ->setJSON($data);
    }
}
