
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi_solat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Absensi_solat_model', 'solat');
        $this->load->database();
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);

        
        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('auth');
        }

    }

    // =====================================================
    // HAK AKSES
    // =====================================================

    private function admin_diizinkan()
    {
        return $this->session->userdata('logged_in') === TRUE
            && (int) $this->session->userdata('role_id') === 1;
    }

    private function wajib_admin()
    {
        if (!$this->admin_diizinkan()) {
            show_error(
                'Anda tidak memiliki hak akses untuk fitur ini.',
                403,
                'Akses Ditolak'
            );
            return false;
        }

        return true;
    }

    // =====================================================
    // TAHUN AJARAN AKTIF
    // =====================================================

    private function tahun_aktif_id()
    {
        $tahun_id = (int) $this->session->userdata('tahun_id');

        if ($tahun_id > 0) {
            return $tahun_id;
        }

        $tahun = $this->db
            ->where('aktif', 1)
            ->limit(1)
            ->get('tahun_ajaran')
            ->row();

        return $tahun ? (int) $tahun->id : 0;
    }

    // =====================================================
    // DASHBOARD ABSENSI SALAT
    // =====================================================

    
    public function index()
    {
        if (!$this->wajib_admin()) {
            return;
        }

        // Tentukan tahun ajaran sebelum digunakan.
        $tahun_id = $this->tahun_aktif_id();

        $data['title'] = 'Absensi Salat Dzuhur';
        $data['judul'] = 'Absensi Salat Dzuhur';
        $data['tahun_id'] = $tahun_id;
        $data['sesi'] = [];
        $data['statistik_sesi'] = 0;
        $data['statistik_hadir'] = 0;
        $data['statistik_terlambat'] = 0;
        $data['statistik_belum_absen'] = 0;

        if ($tahun_id > 0) {
            $data['sesi'] = $this->solat->get_sesi($tahun_id);
            $data['statistik_sesi'] = count($data['sesi']);

            foreach ($data['sesi'] as $item) {
                $stat = $this->solat->get_statistik($item->id);

                $data['statistik_hadir'] += $stat['total_hadir'];
                $data['statistik_terlambat'] += $stat['terlambat'];
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('absensi_solat/index', $data);
        $this->load->view('templates/footer');
    }

    // =====================================================
    // MEMBUAT SESI ABSENSI
    // =====================================================

    public function buat_sesi()
    {
        if (!$this->wajib_admin()) {
            return;
        }

        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Metode request tidak diizinkan.', 405);
            return;
        }

        $tahun_id = $this->tahun_aktif_id();

        if ($tahun_id <= 0) {
            $this->session->set_flashdata(
                'error',
                'Tahun ajaran aktif belum tersedia.'
            );

            redirect('absensi_solat');
            return;
        }

        $tanggal = trim((string) $this->input->post('tanggal', TRUE));
        $jam_mulai = trim((string) $this->input->post('jam_mulai', TRUE));
        $jam_selesai = trim((string) $this->input->post('jam_selesai', TRUE));

        if (
            !$this->tanggal_valid($tanggal)
            || !$this->jam_valid($jam_mulai)
            || !$this->jam_valid($jam_selesai)
        ) {
            $this->session->set_flashdata(
                'error',
                'Tanggal atau jam sesi tidak valid.'
            );

            redirect('absensi_solat');
            return;
        }

        if (strtotime($jam_selesai) <= strtotime($jam_mulai)) {
            $this->session->set_flashdata(
                'error',
                'Jam selesai harus setelah jam mulai.'
            );

            redirect('absensi_solat');
            return;
        }

        // Hindari sesi ganda untuk tanggal dan jenis salat yang sama.
        $sesi_lama = $this->db
            ->where('tahun_id', $tahun_id)
            ->where('tanggal', $tanggal)
            ->where('jenis_solat', 'dzuhur')
            ->limit(1)
            ->get('absensi_solat_sesi')
            ->row();

        if ($sesi_lama) {
            $this->session->set_flashdata(
                'error',
                'Sesi salat Dzuhur untuk tanggal tersebut sudah tersedia.'
            );

            redirect('absensi_solat');
            return;
        }

        $berhasil = $this->solat->buat_sesi([
            'tahun_id'    => $tahun_id,
            'tanggal'     => $tanggal,
            'jenis_solat' => 'dzuhur',
            'jam_mulai'   => $jam_mulai,
            'jam_selesai' => $jam_selesai,
            'status'      => 'buka',
            'dibuat_oleh' => (int) $this->session->userdata('user_id')
        ]);

        if ($berhasil) {
            $this->session->set_flashdata(
                'success',
                'Sesi absensi salat Dzuhur berhasil dibuat.'
            );
        } else {
            $this->session->set_flashdata(
                'error',
                'Sesi gagal dibuat. Periksa log aplikasi.'
            );
        }

        redirect('absensi_solat');
    }

    // =====================================================
    // HALAMAN SCANNER
    // =====================================================

    public function scan($id = null)
    {
        if (!$this->wajib_admin()) {
            return;
        }

        $tahun_id = $this->tahun_aktif_id();
        

        $sesi = $this->solat->get_sesi_by_id(
            (int) $id,
            $tahun_id
        );

        if (!$sesi) {
            show_404();
            return;
        }

        $data['title'] = 'Scan Absensi Salat Dzuhur';
        $data['judul'] = 'Scan Absensi Salat Dzuhur';
        $data['tahun_id'] = $tahun_id;
        $data['sesi'] = $sesi;
        $data['statistik'] = $this->solat->get_statistik($sesi->id);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('absensi_solat/scan', $data);
        $this->load->view('templates/footer');
    }

    // =====================================================
    // PROSES SCAN QR CODE / RFID
    // =====================================================

    public function proses_scan()
    {
        if (!$this->admin_diizinkan()) {
            $this->json_response([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses.'
            ], 403);

            return;
        }

        if ($this->input->method(TRUE) !== 'POST') {
            $this->json_response([
                'success' => false,
                'message' => 'Metode request tidak diizinkan.'
            ], 405);

            return;
        }

        $sesi_id = (int) $this->input->post('sesi_id');
        $token = trim((string) $this->input->post('token', FALSE));
        $metode = strtolower(
            trim((string) $this->input->post('metode', TRUE))
        );

        if ($sesi_id <= 0 || $token === '') {
            $this->json_response([
                'success' => false,
                'message' => 'Data scan belum lengkap.'
            ], 400);

            return;
        }

        if (!in_array($metode, ['qrcode', 'rfid'], true)) {
            $this->json_response([
                'success' => false,
                'message' => 'Metode absensi tidak valid.'
            ], 400);

            return;
        }

        $tahun_id = $this->tahun_aktif_id();

        if ($tahun_id <= 0) {
            $this->json_response([
                'success' => false,
                'message' => 'Tahun ajaran aktif belum tersedia.'
            ], 400);

            return;
        }

        $sesi = $this->solat->get_sesi_by_id(
            $sesi_id,
            $tahun_id
        );

        if (!$sesi) {
            $this->json_response([
                'success' => false,
                'message' => 'Sesi absensi tidak ditemukan.'
            ], 404);

            return;
        }

        if ($sesi->status !== 'buka') {
            $this->json_response([
                'success' => false,
                'message' => 'Sesi absensi sudah ditutup.'
            ], 400);

            return;
        }

        if ($sesi->tanggal !== date('Y-m-d')) {
            $this->json_response([
                'success' => false,
                'message' => 'Absensi hanya dapat dilakukan pada tanggal sesi.'
            ], 400);

            return;
        }

        $jam_sekarang = date('H:i:s');

        if (
            $jam_sekarang < $sesi->jam_mulai
            || $jam_sekarang > $sesi->jam_selesai
        ) {
            $this->json_response([
                'success' => false,
                'message' => 'Saat ini di luar waktu absensi yang ditentukan.',
                'jam_mulai' => $sesi->jam_mulai,
                'jam_selesai' => $sesi->jam_selesai
            ], 400);

            return;
        }

        // QR berisi NISN. RFID menggunakan UID kartu.
        if ($metode === 'qrcode') {
            $siswa = $this->solat->get_siswa_by_qr($token);
        } else {
            $siswa = $this->solat->get_siswa_by_rfid($token);
        }

        if (!$siswa) {
            $this->json_response([
                'success' => false,
                'message' => $metode === 'qrcode'
                    ? 'QR tidak dikenali. NISN tidak ditemukan.'
                    : 'Kartu RFID tidak dikenali.'
            ], 404);

            return;
        }

        // Pastikan siswa aktif.
        if (!isset($siswa->status) || $siswa->status !== 'aktif') {
            $this->json_response([
                'success' => false,
                'message' => 'Siswa tidak berstatus aktif.'
            ], 400);

            return;
        }

        // Primary key siswa adalah kolom id.
        $siswa_id = (int) $siswa->id;

        if ($siswa_id <= 0) {
            $this->json_response([
                'success' => false,
                'message' => 'ID siswa tidak valid.'
            ], 500);

            return;
        }

        // Cegah absensi ganda.
        $absen_lama = $this->solat->sudah_absen(
            $sesi_id,
            $siswa_id
        );

        if ($absen_lama) {
            $this->json_response([
                'success' => false,
                'duplicate' => true,
                'message' => $siswa->nama . ' sudah melakukan absensi.',
                'nama' => $siswa->nama,
                'nisn' => isset($siswa->nisn) ? $siswa->nisn : ''
            ], 200);

            return;
        }

        // Tentukan status berdasarkan jam mulai sesi.
        $status_absen = $jam_sekarang > $sesi->jam_mulai
            ? 'terlambat'
            : 'hadir';

        $berhasil = $this->solat->simpan_absensi([
            'sesi_id'      => $sesi_id,
            'siswa_id'     => $siswa_id,
            'waktu_absen'  => date('Y-m-d H:i:s'),
            'metode_absen' => $metode,
            'status'       => $status_absen
        ]);

        if (!$berhasil) {
            // Periksa ulang untuk membedakan duplikasi dari kegagalan.
            $absen_lama = $this->solat->sudah_absen(
                $sesi_id,
                $siswa_id
            );

            if ($absen_lama) {
                $this->json_response([
                    'success' => false,
                    'duplicate' => true,
                    'message' => $siswa->nama . ' sudah melakukan absensi.',
                    'nama' => $siswa->nama
                ], 200);

                return;
            }

            $this->json_response([
                'success' => false,
                'message' => 'Absensi gagal disimpan. Periksa log aplikasi.'
            ], 500);

            return;
        }

        $this->json_response([
            'success' => true,
            'message' => 'Absensi berhasil disimpan.',
            'nama' => $siswa->nama,
            'nis' => isset($siswa->nis) ? $siswa->nis : '',
            'nisn' => isset($siswa->nisn) ? $siswa->nisn : '',
            'status' => $status_absen,
            'metode' => $metode,
            'waktu' => date('H:i:s')
        ], 200);
    }

    // =====================================================
    // MENUTUP SESI
    // =====================================================

    public function tutup_sesi($id = null)
    {
        if (!$this->wajib_admin()) {
            return;
        }

        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Metode request tidak diizinkan.', 405);
            return;
        }

        $sesi = $this->solat->get_sesi_by_id(
            (int) $id,
            $this->tahun_aktif_id()
        );

        if (!$sesi) {
            show_404();
            return;
        }

        $berhasil = $this->solat->update_status_sesi(
            $sesi->id,
            'tutup'
        );

        $this->session->set_flashdata(
            $berhasil ? 'success' : 'error',
            $berhasil
                ? 'Sesi absensi berhasil ditutup.'
                : 'Sesi absensi gagal ditutup.'
        );

        redirect('absensi_solat');
    }

    // =====================================================
    // REKAP ABSENSI
    // =====================================================

    public function rekap()
    {
        if (!$this->wajib_admin()) {
            return;
        }

        $tahun_id = $this->tahun_aktif_id();

        $filter = [
            'tahun_id' => $tahun_id,
            'tanggal_mulai' => trim(
                (string) $this->input->get('tanggal_mulai', TRUE)
            ),
            'tanggal_selesai' => trim(
                (string) $this->input->get('tanggal_selesai', TRUE)
            ),
            'tanggal' => trim(
                (string) $this->input->get('tanggal', TRUE)
            ),
            'kelas_id' => (int) $this->input->get('kelas_id'),
            'keyword' => trim(
                (string) $this->input->get('keyword', TRUE)
            ),
            'sesi_id' => (int) $this->input->get('sesi_id')
        ];

        if (!$this->tanggal_valid($filter['tanggal_mulai'])) {
            $filter['tanggal_mulai'] = '';
        }

        if (!$this->tanggal_valid($filter['tanggal_selesai'])) {
            $filter['tanggal_selesai'] = '';
        }

        if (!$this->tanggal_valid($filter['tanggal'])) {
            $filter['tanggal'] = '';
        }

        $data['title'] = 'Rekap Absensi Salat';
        $data['judul'] = 'Rekap Absensi Salat';
        $data['tahun_id'] = $tahun_id;
        $data['filter'] = $filter;
        $data['rekap'] = $this->solat->get_rekap($filter);
        $data['kelas'] = $this->solat->get_kelas();
        $data['sesi'] = $this->solat->get_sesi($tahun_id);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('absensi_solat/rekap', $data);
        $this->load->view('templates/footer');
    }

    // =====================================================
    // IMPORT JADWAL SESI DARI EXCEL
    // =====================================================

    public function import_jadwal()
    {
        if (!$this->wajib_admin()) {
            return;
        }

        $data['title'] = 'Import Jadwal Sesi Salat';
        $data['judul'] = 'Import Jadwal Sesi Salat';
        $data['tahun_id'] = $this->tahun_aktif_id();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('absensi_solat/import_jadwal', $data);
        $this->load->view('templates/footer');
    }

    public function download_template()
    {
        if (!$this->wajib_admin()) {
            return;
        }

        $mulai = trim((string) $this->input->get('tanggal_mulai', TRUE));
        $selesai = trim((string) $this->input->get('tanggal_selesai', TRUE));

        if (!$this->tanggal_valid($mulai) || !$this->tanggal_valid($selesai)
            || $selesai < $mulai) {
            $this->session->set_flashdata('error', 'Rentang tanggal template tidak valid.');
            redirect('absensi_solat/import_jadwal');
            return;
        }

        $jumlah_hari = (int) ((strtotime($selesai) - strtotime($mulai)) / 86400) + 1;
        if ($jumlah_hari > 366) {
            $this->session->set_flashdata('error', 'Template maksimal untuk 366 hari dalam sekali unduh.');
            redirect('absensi_solat/import_jadwal');
            return;
        }

        $autoload = FCPATH . 'vendor/autoload.php';
        if (!file_exists($autoload)) {
            show_error('PhpSpreadsheet belum tersedia. Pastikan folder vendor dan autoload.php ada.', 500);
            return;
        }
        require_once $autoload;

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Jadwal Sesi');
        $sheet->fromArray(['Tanggal (YYYY-MM-DD)', 'Jam Mulai (HH:MM)', 'Jam Selesai (HH:MM)'], null, 'A1');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(24);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(24);

        $baris = 2;
        $tanggal = new DateTime($mulai);
        $batas = new DateTime($selesai);
        while ($tanggal <= $batas) {
            $sheet->setCellValueExplicit('A' . $baris, $tanggal->format('Y-m-d'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $baris, '11:30');
            $sheet->setCellValue('C' . $baris, '12:00');
            $tanggal->modify('+1 day');
            $baris++;
        }

        $filename = 'template_jadwal_solat_' . $mulai . '_sampai_' . $selesai . '.xlsx';
        while (ob_get_level() > 0) { ob_end_clean(); }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        $spreadsheet->disconnectWorksheets();
        exit;
    }

    public function import_proses()
    {
        if (!$this->wajib_admin()) {
            return;
        }

        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Metode request tidak diizinkan.', 405);
            return;
        }

        $tahun_id = $this->tahun_aktif_id();
        if ($tahun_id <= 0) {
            $this->session->set_flashdata('error', 'Tahun ajaran aktif belum tersedia.');
            redirect('absensi_solat/import_jadwal');
            return;
        }

        if (empty($_FILES['file_jadwal']['name'])) {
            $this->session->set_flashdata('error', 'Pilih file Excel jadwal terlebih dahulu.');
            redirect('absensi_solat/import_jadwal');
            return;
        }

        $ext = strtolower(pathinfo($_FILES['file_jadwal']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['xlsx', 'xls', 'csv'], true)) {
            $this->session->set_flashdata('error', 'Format file harus XLSX, XLS, atau CSV.');
            redirect('absensi_solat/import_jadwal');
            return;
        }

        $autoload = FCPATH . 'vendor/autoload.php';
        if (!file_exists($autoload)) {
            show_error('PhpSpreadsheet belum tersedia. Pastikan folder vendor dan autoload.php ada.', 500);
            return;
        }
        require_once $autoload;

        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($_FILES['file_jadwal']['tmp_name']);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($_FILES['file_jadwal']['tmp_name']);
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);
        } catch (Exception $e) {
            log_message('error', 'Import jadwal salat gagal membaca file: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'File tidak dapat dibaca. Gunakan template yang disediakan.');
            redirect('absensi_solat/import_jadwal');
            return;
        }

        $berhasil = 0;
        $duplikat = 0;
        $invalid = 0;
        $errors = [];
        $this->db->trans_begin();

        foreach ($rows as $index => $row) {
            if ($index === 0) { continue; } // Header
            $tanggal_raw = isset($row[0]) ? $row[0] : '';
            $mulai_raw = isset($row[1]) ? $row[1] : '';
            $selesai_raw = isset($row[2]) ? $row[2] : '';

            if (trim((string) $tanggal_raw) === '' && trim((string) $mulai_raw) === '' && trim((string) $selesai_raw) === '') {
                continue;
            }

            $tanggal = $this->normalisasi_tanggal_excel($tanggal_raw);
            $jam_mulai = $this->normalisasi_jam_excel($mulai_raw);
            $jam_selesai = $this->normalisasi_jam_excel($selesai_raw);

            if (!$tanggal || !$jam_mulai || !$jam_selesai || $jam_selesai <= $jam_mulai) {
                $invalid++;
                if (count($errors) < 10) { $errors[] = 'Baris ' . ($index + 1) . ': tanggal atau jam tidak valid.'; }
                continue;
            }

            if ($this->solat->get_sesi_tanggal($tahun_id, $tanggal)) {
                $duplikat++;
                continue;
            }

            $ok = $this->solat->buat_sesi([
                'tahun_id' => $tahun_id,
                'tanggal' => $tanggal,
                'jenis_solat' => 'dzuhur',
                'jam_mulai' => $jam_mulai,
                'jam_selesai' => $jam_selesai,
                'status' => 'tutup',
                'dibuat_oleh' => (int) $this->session->userdata('user_id')
            ]);

            if ($ok) { $berhasil++; }
            else {
                $invalid++;
                if (count($errors) < 10) { $errors[] = 'Baris ' . ($index + 1) . ': gagal menyimpan sesi.'; }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Import dibatalkan karena terjadi kesalahan database.');
            redirect('absensi_solat/import_jadwal');
            return;
        }
        $this->db->trans_commit();

        $pesan = 'Import selesai. Berhasil: ' . $berhasil . ', dilewati karena tanggal sudah memiliki sesi: ' . $duplikat . ', baris tidak valid: ' . $invalid . '.';
        if (!empty($errors)) { $pesan .= ' Detail: ' . implode(' ', $errors); }
        $this->session->set_flashdata($invalid > 0 ? 'error' : 'success', $pesan);
        redirect('absensi_solat');
    }

    private function normalisasi_tanggal_excel($value)
    {
        if (is_numeric($value) && (float) $value > 20000) {
            try { return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d'); }
            catch (Exception $e) { return false; }
        }
        $value = trim((string) $value);
        if ($this->tanggal_valid($value)) { return $value; }
        foreach (['d/m/Y', 'd-m-Y', 'Y/m/d', 'd.m.Y'] as $format) {
            $dt = DateTime::createFromFormat('!' . $format, $value);
            if ($dt && $dt->format($format) === $value) { return $dt->format('Y-m-d'); }
        }
        return false;
    }

    private function normalisasi_jam_excel($value)
    {
        if (is_numeric($value) && (float) $value >= 0 && (float) $value < 1) {
            $seconds = (int) round((float) $value * 86400);
            return gmdate('H:i:s', $seconds);
        }
        $value = trim((string) $value);
        foreach (['H:i', 'H:i:s'] as $format) {
            $dt = DateTime::createFromFormat('!' . $format, $value);
            if ($dt && $dt->format($format) === $value) { return $dt->format('H:i:s'); }
        }
        return false;
    }

    // =====================================================
    // EDIT SESI MANUAL
    // =====================================================

    public function edit_sesi($id = null)
    {
        if (!$this->wajib_admin()) { return; }
        $tahun_id = $this->tahun_aktif_id();
        $sesi = $this->solat->get_sesi_by_id((int) $id, $tahun_id);
        if (!$sesi) { show_404(); return; }

        if ($this->input->method(TRUE) === 'POST') {
            $tanggal = trim((string) $this->input->post('tanggal', TRUE));
            $jam_mulai = trim((string) $this->input->post('jam_mulai', TRUE));
            $jam_selesai = trim((string) $this->input->post('jam_selesai', TRUE));

            if (!$this->tanggal_valid($tanggal) || !$this->jam_valid($jam_mulai)
                || !$this->jam_valid($jam_selesai) || $jam_selesai <= $jam_mulai) {
                $this->session->set_flashdata('error', 'Tanggal atau jam tidak valid. Jam selesai harus setelah jam mulai.');
                redirect('absensi_solat/edit_sesi/' . (int) $id);
                return;
            }

            $sesi_lain = $this->solat->get_sesi_tanggal($tahun_id, $tanggal);
            if ($sesi_lain && (int) $sesi_lain->id !== (int) $id) {
                $this->session->set_flashdata('error', 'Tanggal tersebut sudah memiliki sesi Dzuhur.');
                redirect('absensi_solat/edit_sesi/' . (int) $id);
                return;
            }

            $ok = $this->solat->update_sesi((int) $id, $tahun_id, [
                'tanggal' => $tanggal,
                'jam_mulai' => $jam_mulai,
                'jam_selesai' => $jam_selesai
            ]);
            $this->session->set_flashdata($ok ? 'success' : 'error', $ok ? 'Sesi berhasil diperbarui.' : 'Sesi gagal diperbarui.');
            redirect('absensi_solat');
            return;
        }

        $data['title'] = 'Edit Sesi Absensi';
        $data['judul'] = 'Edit Sesi Absensi';
        $data['tahun_id'] = $tahun_id;
        $data['sesi'] = $sesi;
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('absensi_solat/edit_sesi', $data);
        $this->load->view('templates/footer');
    }

    // =====================================================
    // VALIDASI TANGGAL DAN JAM
    // =====================================================

    private function tanggal_valid($tanggal)
    {
        if (empty($tanggal)) {
            return false;
        }

        $tanggal_obj = DateTime::createFromFormat('!Y-m-d', $tanggal);

        return $tanggal_obj
            && $tanggal_obj->format('Y-m-d') === $tanggal;
    }

    private function jam_valid($jam)
    {
        if (empty($jam)) {
            return false;
        }

        $jam_obj = DateTime::createFromFormat('!H:i', $jam);

        if ($jam_obj && $jam_obj->format('H:i') === $jam) {
            return true;
        }

        $jam_obj = DateTime::createFromFormat('!H:i:s', $jam);

        return $jam_obj
            && $jam_obj->format('H:i:s') === $jam;
    }

    // =====================================================
    // RESPONS JSON
    // =====================================================

    private function json_response($data, $http_code = 200)
    {
        // Mengirim token CSRF terbaru agar scanner bisa terus digunakan.
        $data['csrf_test_name'] = $this->security->get_csrf_hash();

        $this->output
            ->set_status_header($http_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode(
                $data,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ));
    }
}
