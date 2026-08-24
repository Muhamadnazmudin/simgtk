<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| PhpSpreadsheet
|--------------------------------------------------------------------------
*/
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;


class Tka_admin extends CI_Controller
{
    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('Tka_model');

        $this->cek_akses();
    }


    /*
    |--------------------------------------------------------------------------
    | CEK AKSES
    |--------------------------------------------------------------------------
    */
    private function cek_akses()
    {
        $role = strtolower(
            trim(
                (string) $this->session->userdata('role_name')
            )
        );

        if (!in_array($role, ['admin', 'kesiswaan'], true)) {

            show_error(
                'Anda tidak memiliki akses ke menu TKA.',
                403,
                'Akses Ditolak'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CEK LOGIN
    |--------------------------------------------------------------------------
    */
    private function cek_login()
    {
        if (
            !$this->session->userdata('logged_in') &&
            !$this->session->userdata('login')
        ) {
            redirect('auth');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | TAHUN AJARAN AKTIF
    |--------------------------------------------------------------------------
    */
    private function get_tahun_aktif()
    {
        $tahun_id = (int) $this->session->userdata('tahun_id');

        if ($tahun_id > 0) {
            return $tahun_id;
        }

        $row = $this->db
            ->select('id')
            ->from('tahun_ajaran')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        return $row
            ? (int) $row->id
            : 0;
    }


    /*
    |--------------------------------------------------------------------------
    | DAFTAR TAHUN AJARAN
    |--------------------------------------------------------------------------
    */
    private function get_tahun_list()
    {
        return $this->db
            ->select('id, tahun')
            ->from('tahun_ajaran')
            ->order_by('id', 'DESC')
            ->get()
            ->result();
    }


    /*
    |--------------------------------------------------------------------------
    | DAFTAR KELAS XII
    |--------------------------------------------------------------------------
    */
    private function get_kelas_xii()
    {
        return $this->db
            ->select('id, nama')
            ->from('kelas')
            ->where(
                "UPPER(nama) LIKE 'XII %'",
                null,
                false
            )
            ->order_by('nama', 'ASC')
            ->get()
            ->result();
    }


    /*
    |--------------------------------------------------------------------------
    | NAMA TAHUN AJARAN
    |--------------------------------------------------------------------------
    */
    private function get_nama_tahun($tahun_id)
    {
        $row = $this->db
            ->select('tahun')
            ->from('tahun_ajaran')
            ->where('id', (int) $tahun_id)
            ->limit(1)
            ->get()
            ->row();

        return $row
            ? $row->tahun
            : '-';
    }


    /*
    |--------------------------------------------------------------------------
    | NAMA KELAS
    |--------------------------------------------------------------------------
    */
    private function get_nama_kelas($kelas_id)
    {
        if ((int) $kelas_id <= 0) {
            return 'Semua Kelas XII';
        }

        $row = $this->db
            ->select('nama')
            ->from('kelas')
            ->where('id', (int) $kelas_id)
            ->limit(1)
            ->get()
            ->row();

        return $row
            ? $row->nama
            : 'Semua Kelas XII';
    }


    /*
    |--------------------------------------------------------------------------
    | LABEL STATUS
    |--------------------------------------------------------------------------
    */
    private function get_status_label($status)
    {
        switch ($status) {

            case 'sudah':
                return 'Sudah Mengisi';

            case 'belum':
                return 'Belum Mengisi';

            default:
                return 'Semua Peserta';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALISASI STATUS
    |--------------------------------------------------------------------------
    */
    private function normalize_status($status)
    {
        $status = strtolower(
            trim(
                (string) $status
            )
        );

        if (!in_array(
            $status,
            ['sudah', 'belum'],
            true
        )) {
            return '';
        }

        return $status;
    }


    /*
    |--------------------------------------------------------------------------
    | DATA PESERTA TKA
    |--------------------------------------------------------------------------
    |
    | Digunakan oleh:
    | - index()
    | - export_excel()
    | - export_pdf()
    |
    | Filter:
    | - tahun ajaran
    | - kelas
    | - status TKA
    | - nama siswa
    |
    |--------------------------------------------------------------------------
    */
    private function get_peserta_tka(
        $tahun_id,
        $kelas_id = 0,
        $status = '',
        $nama = ''
    ) {

        $tahun_id = (int) $tahun_id;
        $kelas_id = (int) $kelas_id;

        $status = $this->normalize_status(
            $status
        );

        $nama = trim(
            (string) $nama
        );


        /*
        |--------------------------------------------------------------------------
        | QUERY
        |--------------------------------------------------------------------------
        */

        $this->db
            ->select('
                s.id,
                s.nisn,
                s.nis,
                s.nama,
                s.id_kelas,
                s.tahun_id,
                s.status,

                k.nama AS nama_kelas,

                ta.tahun AS tahun_ajaran,

                ts.id AS tka_id,
                ts.mapel_1_id,
                ts.mapel_2_id,

                m1.nama_mapel AS mapel_1,
                m2.nama_mapel AS mapel_2
            ')

            ->from('siswa s')


            /*
            |--------------------------------------------------------------------------
            | KELAS
            |--------------------------------------------------------------------------
            */
            ->join(
                'kelas k',
                'k.id = s.id_kelas',
                'left'
            )


            /*
            |--------------------------------------------------------------------------
            | TAHUN AJARAN
            |--------------------------------------------------------------------------
            */
            ->join(
                'tahun_ajaran ta',
                'ta.id = s.tahun_id',
                'left'
            )


            /*
            |--------------------------------------------------------------------------
            | DATA TKA
            |--------------------------------------------------------------------------
            |
            | Harus berdasarkan tahun yang sedang difilter.
            |
            |--------------------------------------------------------------------------
            */
            ->join(
                'tka_siswa ts',
                'ts.siswa_id = s.id
                 AND ts.tahun_id = ' . $tahun_id,
                'left',
                false
            )


            /*
            |--------------------------------------------------------------------------
            | MAPEL TKA 1
            |--------------------------------------------------------------------------
            */
            ->join(
                'tka_mapel m1',
                'm1.id = ts.mapel_1_id',
                'left'
            )


            /*
            |--------------------------------------------------------------------------
            | MAPEL TKA 2
            |--------------------------------------------------------------------------
            */
            ->join(
                'tka_mapel m2',
                'm2.id = ts.mapel_2_id',
                'left'
            );


        /*
        |--------------------------------------------------------------------------
        | HANYA SISWA AKTIF
        |--------------------------------------------------------------------------
        */

        $this->db->where(
            'LOWER(s.status)',
            'aktif'
        );


        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $this->db->where(
            's.tahun_id',
            $tahun_id
        );


        /*
        |--------------------------------------------------------------------------
        | HANYA KELAS XII
        |--------------------------------------------------------------------------
        */

        $this->db->where(
            "UPPER(k.nama) LIKE 'XII %'",
            null,
            false
        );


        /*
        |--------------------------------------------------------------------------
        | FILTER KELAS
        |--------------------------------------------------------------------------
        */

        if ($kelas_id > 0) {

            $this->db->where(
                's.id_kelas',
                $kelas_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER NAMA
        |--------------------------------------------------------------------------
        |
        | Contoh:
        | ?nama=ahmad
        |
        | Akan mencari:
        | Ahmad
        | Ahmad Fauzan
        | Muhammad Ahmad
        |
        |--------------------------------------------------------------------------
        */

        if ($nama !== '') {

            $this->db->like(
                's.nama',
                $nama
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS TKA
        |--------------------------------------------------------------------------
        */

        if ($status === 'sudah') {

            $this->db->where(
                'ts.id IS NOT NULL',
                null,
                false
            );

        } elseif ($status === 'belum') {

            $this->db->where(
                'ts.id IS NULL',
                null,
                false
            );
        }


        /*
        |--------------------------------------------------------------------------
        | URUTAN
        |--------------------------------------------------------------------------
        */

        $this->db
            ->order_by(
                'k.nama',
                'ASC'
            )
            ->order_by(
                's.nama',
                'ASC'
            );


        /*
        |--------------------------------------------------------------------------
        | RESULT
        |--------------------------------------------------------------------------
        */

        return $this->db
            ->get()
            ->result();
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->cek_login();


        /*
        |--------------------------------------------------------------------------
        | TAHUN
        |--------------------------------------------------------------------------
        */

        $tahun_id = (int) $this->input->get(
            'tahun_id'
        );

        if ($tahun_id <= 0) {

            $tahun_id =
                $this->get_tahun_aktif();
        }


        /*
        |--------------------------------------------------------------------------
        | KELAS
        |--------------------------------------------------------------------------
        */

        $kelas_id = (int) $this->input->get(
            'kelas_id'
        );


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        $status = $this->normalize_status(
            $this->input->get('status')
        );


        /*
        |--------------------------------------------------------------------------
        | PENCARIAN NAMA
        |--------------------------------------------------------------------------
        */

        $nama = trim(
            (string) $this->input->get(
                'nama'
            )
        );


        /*
        |--------------------------------------------------------------------------
        | DATA PESERTA
        |--------------------------------------------------------------------------
        */

        $data['siswa'] =
            $this->get_peserta_tka(
                $tahun_id,
                $kelas_id,
                $status,
                $nama
            );


        /*
        |--------------------------------------------------------------------------
        | DATA FILTER
        |--------------------------------------------------------------------------
        */

        $data['tahun_list'] =
            $this->get_tahun_list();

        $data['kelas_list'] =
            $this->get_kelas_xii();


        /*
        |--------------------------------------------------------------------------
        | NILAI FILTER
        |--------------------------------------------------------------------------
        */

        $data['tahun_id'] =
            $tahun_id;

        $data['kelas_id'] =
            $kelas_id;

        $data['status'] =
            $status;

        $data['nama'] =
            $nama;


        /*
        |--------------------------------------------------------------------------
        | INFORMASI FILTER
        |--------------------------------------------------------------------------
        */

        $data['nama_tahun'] =
            $this->get_nama_tahun(
                $tahun_id
            );

        $data['nama_kelas'] =
            $this->get_nama_kelas(
                $kelas_id
            );

        $data['status_label'] =
            $this->get_status_label(
                $status
            );


        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $data['total_siswa'] =
            count(
                $data['siswa']
            );

        $data['total_sudah'] = 0;
        $data['total_belum'] = 0;


        foreach (
            $data['siswa']
            as $row
        ) {

            if (!empty($row->tka_id)) {

                $data['total_sudah']++;

            } else {

                $data['total_belum']++;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ACTIVE MENU
        |--------------------------------------------------------------------------
        */

        $data['active'] = 'tka';


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        $this->load->view(
            'templates/header',
            $data
        );

        $this->load->view(
            'templates/sidebar',
            $data
        );

        $this->load->view(
            'admin/tka/index',
            $data
        );

        $this->load->view(
            'templates/footer'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit($id = null)
    {
        $this->cek_login();

        $id = (int) $id;

        if ($id <= 0) {
            show_404();
        }


        /*
        |--------------------------------------------------------------------------
        | DATA TKA
        |--------------------------------------------------------------------------
        */

        $tka = $this->db
            ->select('
                ts.*,

                s.nisn,
                s.nis,
                s.nama,
                s.id_kelas,
                s.tahun_id,
                s.status,

                k.nama AS nama_kelas,

                ta.tahun AS tahun_ajaran
            ')

            ->from('tka_siswa ts')

            ->join(
                'siswa s',
                's.id = ts.siswa_id',
                'inner'
            )

            ->join(
                'kelas k',
                'k.id = s.id_kelas',
                'left'
            )

            ->join(
                'tahun_ajaran ta',
                'ta.id = ts.tahun_id',
                'left'
            )

            ->where(
                'ts.id',
                $id
            )

            ->limit(1)

            ->get()
            ->row();


        if (!$tka) {

            $this->session->set_flashdata(
                'error',
                'Data TKA tidak ditemukan.'
            );

            redirect(
                'Tka_admin'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI KELAS XII
        |--------------------------------------------------------------------------
        */

        $nama_kelas = strtoupper(
            trim(
                (string) $tka->nama_kelas
            )
        );


        if (
            $nama_kelas !== 'XII' &&
            strpos(
                $nama_kelas,
                'XII '
            ) !== 0
        ) {

            show_error(
                'Data TKA hanya untuk siswa kelas XII.',
                403,
                'Data Tidak Valid'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | MAPEL
        |--------------------------------------------------------------------------
        */

        $data['mapel'] =
            $this->Tka_model
                ->get_mapel_aktif();


        $data['tka'] =
            $tka;

        $data['active'] =
            'tka';


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        $this->load->view(
            'templates/header',
            $data
        );

        $this->load->view(
            'templates/sidebar',
            $data
        );

        $this->load->view(
            'admin/tka/edit',
            $data
        );

        $this->load->view(
            'templates/footer'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update()
    {
        $this->cek_login();


        $id = (int) $this->input->post(
            'id'
        );


        if ($id <= 0) {

            $this->session->set_flashdata(
                'error',
                'ID TKA tidak valid.'
            );

            redirect(
                'Tka_admin'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATA TKA
        |--------------------------------------------------------------------------
        */

        $tka = $this->db
            ->where(
                'id',
                $id
            )
            ->limit(1)
            ->get(
                'tka_siswa'
            )
            ->row();


        if (!$tka) {

            $this->session->set_flashdata(
                'error',
                'Data TKA tidak ditemukan.'
            );

            redirect(
                'Tka_admin'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | INPUT
        |--------------------------------------------------------------------------
        */

        $mapel_1_id = (int) $this->input->post(
            'mapel_1_id'
        );

        $mapel_2_id = (int) $this->input->post(
            'mapel_2_id'
        );


        /*
        |--------------------------------------------------------------------------
        | WAJIB DIISI
        |--------------------------------------------------------------------------
        */

        if (
            $mapel_1_id <= 0 ||
            $mapel_2_id <= 0
        ) {

            $this->session->set_flashdata(
                'error',
                'Kedua pilihan mata pelajaran wajib diisi.'
            );

            redirect(
                'Tka_admin/edit/' . $id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TIDAK BOLEH SAMA
        |--------------------------------------------------------------------------
        */

        if (
            $mapel_1_id === $mapel_2_id
        ) {

            $this->session->set_flashdata(
                'error',
                'Pilihan TKA 1 dan 2 tidak boleh sama.'
            );

            redirect(
                'Tka_admin/edit/' . $id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI MAPEL AKTIF
        |--------------------------------------------------------------------------
        */

        $jumlah_mapel =
            $this->db
                ->where_in(
                    'id',
                    [
                        $mapel_1_id,
                        $mapel_2_id
                    ]
                )
                ->where(
                    'aktif',
                    1
                )
                ->count_all_results(
                    'tka_mapel'
                );


        if ($jumlah_mapel !== 2) {

            $this->session->set_flashdata(
                'error',
                'Mata pelajaran TKA tidak valid.'
            );

            redirect(
                'Tka_admin/edit/' . $id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $update = $this->db
            ->where(
                'id',
                $id
            )
            ->update(
                'tka_siswa',
                [
                    'mapel_1_id' => $mapel_1_id,
                    'mapel_2_id' => $mapel_2_id
                ]
            );


        if (!$update) {

            log_message(
                'error',
                'UPDATE TKA GAGAL | ID: ' .
                $id .
                ' | QUERY: ' .
                $this->db->last_query()
            );

            $this->session->set_flashdata(
                'error',
                'Gagal menyimpan perubahan TKA.'
            );

            redirect(
                'Tka_admin/edit/' . $id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SUKSES
        |--------------------------------------------------------------------------
        */

        $this->session->set_flashdata(
            'success',
            'Pilihan TKA berhasil diperbarui.'
        );


        redirect(
            'Tka_admin'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    */
    public function export_excel()
    {
        $this->cek_login();


        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        $tahun_id = (int) $this->input->get(
            'tahun_id'
        );

        if ($tahun_id <= 0) {

            $tahun_id =
                $this->get_tahun_aktif();
        }


        $kelas_id = (int) $this->input->get(
            'kelas_id'
        );


        $status = $this->normalize_status(
            $this->input->get('status')
        );


        $nama = trim(
            (string) $this->input->get(
                'nama'
            )
        );


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $siswa =
            $this->get_peserta_tka(
                $tahun_id,
                $kelas_id,
                $status,
                $nama
            );


        $tahun_ajaran =
            $this->get_nama_tahun(
                $tahun_id
            );

        $kelas_nama =
            $this->get_nama_kelas(
                $kelas_id
            );

        $status_label =
            $this->get_status_label(
                $status
            );


        /*
        |--------------------------------------------------------------------------
        | AUTOLOAD
        |--------------------------------------------------------------------------
        */

        $autoload =
            FCPATH .
            'vendor/autoload.php';


        if (!file_exists($autoload)) {

            show_error(
                'Composer autoload tidak ditemukan.'
            );
        }


        require_once $autoload;


        /*
        |--------------------------------------------------------------------------
        | SPREADSHEET
        |--------------------------------------------------------------------------
        */

        $spreadsheet =
            new Spreadsheet();

        $sheet =
            $spreadsheet->getActiveSheet();


        $sheet->setTitle(
            'Peserta TKA'
        );


        /*
        |--------------------------------------------------------------------------
        | JUDUL
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells(
            'A1:G1'
        );

        $sheet->setCellValue(
            'A1',
            'DAFTAR PESERTA TKA'
        );


        $sheet->mergeCells(
            'A2:G2'
        );

        $sheet->setCellValue(
            'A2',
            'Tahun Ajaran: ' .
            $tahun_ajaran
        );


        $sheet->mergeCells(
            'A3:G3'
        );


        $filter_info =
            'Kelas: ' .
            $kelas_nama .
            ' | Status: ' .
            $status_label;


        if ($nama !== '') {

            $filter_info .=
                ' | Pencarian: "' .
                $nama .
                '"';
        }


        $sheet->setCellValue(
            'A3',
            $filter_info
        );


        /*
        |--------------------------------------------------------------------------
        | STYLE JUDUL
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getStyle('A1:G1')
            ->getFont()
            ->setBold(true)
            ->setSize(16);


        $sheet
            ->getStyle('A1:G3')
            ->getAlignment()
            ->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            );


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        $header_row = 5;


        $headers = [
            'No',
            'NISN',
            'NIS',
            'Nama Siswa',
            'Kelas',
            'Pilihan TKA 1',
            'Pilihan TKA 2'
        ];


        foreach (
            $headers as $index => $header
        ) {

            $column =
                chr(
                    65 + $index
                );

            $sheet->setCellValue(
                $column . $header_row,
                $header
            );
        }


        /*
        |--------------------------------------------------------------------------
        | STYLE HEADER
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getStyle(
                'A' .
                $header_row .
                ':G' .
                $header_row
            )
            ->getFont()
            ->setBold(true);


        $sheet
            ->getStyle(
                'A' .
                $header_row .
                ':G' .
                $header_row
            )
            ->getAlignment()
            ->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            );


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $row_number = 6;
        $no = 1;


        foreach (
            $siswa as $row
        ) {

            $sheet->setCellValue(
                'A' . $row_number,
                $no
            );


            $sheet->setCellValueExplicit(
                'B' . $row_number,
                (string) $row->nisn,
                DataType::TYPE_STRING
            );


            $sheet->setCellValueExplicit(
                'C' . $row_number,
                (string) $row->nis,
                DataType::TYPE_STRING
            );


            $sheet->setCellValue(
                'D' . $row_number,
                $row->nama
            );


            $sheet->setCellValue(
                'E' . $row_number,
                $row->nama_kelas
            );


            $sheet->setCellValue(
                'F' . $row_number,
                !empty($row->mapel_1)
                    ? $row->mapel_1
                    : '-'
            );


            $sheet->setCellValue(
                'G' . $row_number,
                !empty($row->mapel_2)
                    ? $row->mapel_2
                    : '-'
            );


            $row_number++;
            $no++;
        }


        /*
        |--------------------------------------------------------------------------
        | LAST ROW
        |--------------------------------------------------------------------------
        */

        $last_row =
            max(
                $header_row,
                $row_number - 1
            );


        /*
        |--------------------------------------------------------------------------
        | BORDER
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getStyle(
                'A5:G' . $last_row
            )
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                Border::BORDER_THIN
            );


        /*
        |--------------------------------------------------------------------------
        | ALIGNMENT
        |--------------------------------------------------------------------------
        */

        if ($last_row >= 6) {

            $sheet
                ->getStyle(
                    'A6:A' . $last_row
                )
                ->getAlignment()
                ->setHorizontal(
                    Alignment::HORIZONTAL_CENTER
                );


            $sheet
                ->getStyle(
                    'B6:C' . $last_row
                )
                ->getAlignment()
                ->setHorizontal(
                    Alignment::HORIZONTAL_CENTER
                );
        }


        /*
        |--------------------------------------------------------------------------
        | WIDTH
        |--------------------------------------------------------------------------
        */

        foreach (
            ['A', 'B', 'C', 'D', 'E', 'F', 'G']
            as $column
        ) {

            $sheet
                ->getColumnDimension($column)
                ->setAutoSize(true);
        }


        /*
        |--------------------------------------------------------------------------
        | FREEZE
        |--------------------------------------------------------------------------
        */

        $sheet->freezePane(
            'A6'
        );


        /*
        |--------------------------------------------------------------------------
        | FILENAME
        |--------------------------------------------------------------------------
        */

        $filename =
            'Peserta_TKA_' .
            preg_replace(
                '/[^0-9\-]/',
                '_',
                $tahun_ajaran
            );


        if ($nama !== '') {

            $filename .=
                '_Cari_' .
                preg_replace(
                    '/[^a-zA-Z0-9\-]/',
                    '_',
                    $nama
                );
        }


        $filename .=
            '_' .
            date('Ymd_His') .
            '.xlsx';


        /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */

        $writer =
            new Xlsx(
                $spreadsheet
            );


        while (
            ob_get_level() > 0
        ) {

            ob_end_clean();
        }


        header(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        header(
            'Content-Disposition: attachment; filename="' .
            $filename .
            '"'
        );

        header(
            'Cache-Control: max-age=0'
        );


        $writer->save(
            'php://output'
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORT PDF
    |--------------------------------------------------------------------------
    */
    public function export_pdf()
    {
        $this->cek_login();


        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        $tahun_id = (int) $this->input->get(
            'tahun_id'
        );

        if ($tahun_id <= 0) {

            $tahun_id =
                $this->get_tahun_aktif();
        }


        $kelas_id = (int) $this->input->get(
            'kelas_id'
        );


        $status = $this->normalize_status(
            $this->input->get('status')
        );


        $nama = trim(
            (string) $this->input->get(
                'nama'
            )
        );


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $siswa =
            $this->get_peserta_tka(
                $tahun_id,
                $kelas_id,
                $status,
                $nama
            );


        $tahun_ajaran =
            $this->get_nama_tahun(
                $tahun_id
            );

        $kelas_nama =
            $this->get_nama_kelas(
                $kelas_id
            );

        $status_label =
            $this->get_status_label(
                $status
            );


        /*
        |--------------------------------------------------------------------------
        | PDF
        |--------------------------------------------------------------------------
        */

        $this->load->library(
            'pdf'
        );


        $pdf = new Tcpdf(
            'L',
            'mm',
            'A4',
            true,
            'UTF-8',
            false
        );


        /*
        |--------------------------------------------------------------------------
        | SETTING
        |--------------------------------------------------------------------------
        */

        $pdf->SetCreator(
            'SimSGTK'
        );

        $pdf->SetAuthor(
            'SimSGTK'
        );

        $pdf->SetTitle(
            'Daftar Peserta TKA'
        );


        $pdf->SetMargins(
            10,
            10,
            10
        );

        $pdf->SetHeaderMargin(
            5
        );

        $pdf->SetFooterMargin(
            8
        );

        $pdf->SetAutoPageBreak(
            true,
            12
        );

        $pdf->setPrintHeader(
            false
        );

        $pdf->setPrintFooter(
            true
        );


        $pdf->SetFont(
            'helvetica',
            '',
            8
        );


        $pdf->AddPage();


        /*
        |--------------------------------------------------------------------------
        | JUDUL
        |--------------------------------------------------------------------------
        */

        $pdf->SetFont(
            'helvetica',
            'B',
            16
        );

        $pdf->Cell(
            0,
            8,
            'DAFTAR PESERTA TKA',
            0,
            1,
            'C'
        );


        $pdf->SetFont(
            'helvetica',
            '',
            10
        );

        $pdf->Cell(
            0,
            6,
            'Tahun Ajaran ' .
            $tahun_ajaran,
            0,
            1,
            'C'
        );


        $pdf->Ln(5);


        /*
        |--------------------------------------------------------------------------
        | INFORMASI FILTER
        |--------------------------------------------------------------------------
        */

        $pdf->SetFont(
            'helvetica',
            '',
            9
        );


        $pdf->Cell(
            75,
            6,
            'Kelas: ' .
            $kelas_nama,
            0,
            0,
            'L'
        );


        $pdf->Cell(
            75,
            6,
            'Status: ' .
            $status_label,
            0,
            0,
            'L'
        );


        $pdf->Cell(
            75,
            6,
            'Jumlah: ' .
            count($siswa) .
            ' siswa',
            0,
            1,
            'L'
        );


        if ($nama !== '') {

            $pdf->Cell(
                0,
                6,
                'Pencarian Nama: "' .
                $nama .
                '"',
                0,
                1,
                'L'
            );
        }


        $pdf->Ln(2);


        /*
        |--------------------------------------------------------------------------
        | LEBAR KOLOM
        |--------------------------------------------------------------------------
        |
        | Total area:
        | 297 - 10 - 10 = 277 mm
        |
        |--------------------------------------------------------------------------
        */

        $w_no    = 12;
        $w_nisn  = 38;
        $w_nis   = 38;
        $w_nama  = 72;
        $w_kelas = 30;
        $w_tka1  = 43.5;
        $w_tka2  = 43.5;


        $header_height = 10;


        /*
        |--------------------------------------------------------------------------
        | FUNCTION HEADER TABEL
        |--------------------------------------------------------------------------
        |
        | Supaya header tidak ditulis berulang-ulang.
        |
        |--------------------------------------------------------------------------
        */

        $draw_header = function () use (
            $pdf,
            $w_no,
            $w_nisn,
            $w_nis,
            $w_nama,
            $w_kelas,
            $w_tka1,
            $w_tka2,
            $header_height
        ) {

            $pdf->SetFont(
                'helvetica',
                'B',
                8
            );

            $pdf->SetFillColor(
                235,
                235,
                235
            );


            $pdf->Cell(
                $w_no,
                $header_height,
                'No',
                1,
                0,
                'C',
                true
            );


            $pdf->Cell(
                $w_nisn,
                $header_height,
                'NISN',
                1,
                0,
                'C',
                true
            );


            $pdf->Cell(
                $w_nis,
                $header_height,
                'NIS',
                1,
                0,
                'C',
                true
            );


            $pdf->Cell(
                $w_nama,
                $header_height,
                'Nama Siswa',
                1,
                0,
                'C',
                true
            );


            $pdf->Cell(
                $w_kelas,
                $header_height,
                'Kelas',
                1,
                0,
                'C',
                true
            );


            $pdf->Cell(
                $w_tka1,
                $header_height,
                'Pilihan TKA 1',
                1,
                0,
                'C',
                true
            );


            $pdf->Cell(
                $w_tka2,
                $header_height,
                'Pilihan TKA 2',
                1,
                1,
                'C',
                true
            );


            $pdf->SetFont(
                'helvetica',
                '',
                8
            );

            $pdf->SetFillColor(
                255,
                255,
                255
            );
        };


        /*
        |--------------------------------------------------------------------------
        | HEADER PERTAMA
        |--------------------------------------------------------------------------
        */

        $draw_header();


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $pdf->SetFont(
            'helvetica',
            '',
            8
        );


        $no = 1;


        foreach (
            $siswa as $row
        ) {

            /*
            |--------------------------------------------------------------------------
            | DATA
            |--------------------------------------------------------------------------
            */

            $nisn = !empty($row->nisn)
                ? $row->nisn
                : '-';

            $nis = !empty($row->nis)
                ? $row->nis
                : '-';

            $nama_siswa = !empty($row->nama)
                ? $row->nama
                : '-';

            $kelas = !empty($row->nama_kelas)
                ? $row->nama_kelas
                : '-';

            $mapel_1 = !empty($row->mapel_1)
                ? $row->mapel_1
                : '-';

            $mapel_2 = !empty($row->mapel_2)
                ? $row->mapel_2
                : '-';


            /*
            |--------------------------------------------------------------------------
            | HITUNG BARIS
            |--------------------------------------------------------------------------
            */

            $nama_lines =
                $pdf->getNumLines(
                    $nama_siswa,
                    $w_nama - 4
                );

            $tka1_lines =
                $pdf->getNumLines(
                    $mapel_1,
                    $w_tka1 - 4
                );

            $tka2_lines =
                $pdf->getNumLines(
                    $mapel_2,
                    $w_tka2 - 4
                );


            $max_lines = max(
                1,
                $nama_lines,
                $tka1_lines,
                $tka2_lines
            );


            $row_height =
                ($max_lines * 4.5) + 3;


            /*
            |--------------------------------------------------------------------------
            | PAGE BREAK
            |--------------------------------------------------------------------------
            */

            if (
                $pdf->GetY() +
                $row_height >
                $pdf->getPageHeight() -
                15
            ) {

                $pdf->AddPage();

                $draw_header();
            }


            /*
            |--------------------------------------------------------------------------
            | POSISI
            |--------------------------------------------------------------------------
            */

            $x =
                $pdf->GetX();

            $y =
                $pdf->GetY();


            /*
            |--------------------------------------------------------------------------
            | NO
            |--------------------------------------------------------------------------
            */

            $pdf->MultiCell(
                $w_no,
                $row_height,
                $no,
                1,
                'C',
                false,
                0,
                $x,
                $y,
                true,
                0,
                false,
                true,
                $row_height,
                'M'
            );


            /*
            |--------------------------------------------------------------------------
            | NISN
            |--------------------------------------------------------------------------
            */

            $x += $w_no;

            $pdf->MultiCell(
                $w_nisn,
                $row_height,
                $nisn,
                1,
                'C',
                false,
                0,
                $x,
                $y,
                true,
                0,
                false,
                true,
                $row_height,
                'M'
            );


            /*
            |--------------------------------------------------------------------------
            | NIS
            |--------------------------------------------------------------------------
            */

            $x += $w_nisn;

            $pdf->MultiCell(
                $w_nis,
                $row_height,
                $nis,
                1,
                'C',
                false,
                0,
                $x,
                $y,
                true,
                0,
                false,
                true,
                $row_height,
                'M'
            );


            /*
            |--------------------------------------------------------------------------
            | NAMA
            |--------------------------------------------------------------------------
            */

            $x += $w_nis;

            $pdf->MultiCell(
                $w_nama,
                $row_height,
                $nama_siswa,
                1,
                'L',
                false,
                0,
                $x,
                $y,
                true,
                0,
                false,
                true,
                $row_height,
                'M'
            );


            /*
            |--------------------------------------------------------------------------
            | KELAS
            |--------------------------------------------------------------------------
            */

            $x += $w_nama;

            $pdf->MultiCell(
                $w_kelas,
                $row_height,
                $kelas,
                1,
                'C',
                false,
                0,
                $x,
                $y,
                true,
                0,
                false,
                true,
                $row_height,
                'M'
            );


            /*
            |--------------------------------------------------------------------------
            | TKA 1
            |--------------------------------------------------------------------------
            */

            $x += $w_kelas;

            $pdf->MultiCell(
                $w_tka1,
                $row_height,
                $mapel_1,
                1,
                'L',
                false,
                0,
                $x,
                $y,
                true,
                0,
                false,
                true,
                $row_height,
                'M'
            );


            /*
            |--------------------------------------------------------------------------
            | TKA 2
            |--------------------------------------------------------------------------
            */

            $x += $w_tka1;

            $pdf->MultiCell(
                $w_tka2,
                $row_height,
                $mapel_2,
                1,
                'L',
                false,
                1,
                $x,
                $y,
                true,
                0,
                false,
                true,
                $row_height,
                'M'
            );


            $no++;
        }


        /*
        |--------------------------------------------------------------------------
        | DATA KOSONG
        |--------------------------------------------------------------------------
        */

        if (empty($siswa)) {

            $pdf->SetFont(
                'helvetica',
                '',
                9
            );

            $pdf->Cell(
                277,
                10,
                $nama !== ''
                    ? 'Tidak ditemukan siswa dengan nama "' .
                      $nama .
                      '".'
                    : 'Tidak ada data peserta TKA.',
                1,
                1,
                'C'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILENAME
        |--------------------------------------------------------------------------
        */

        $filename =
            'Peserta_TKA_' .
            preg_replace(
                '/[^0-9\-]/',
                '_',
                $tahun_ajaran
            );


        if ($nama !== '') {

            $filename .=
                '_Cari_' .
                preg_replace(
                    '/[^a-zA-Z0-9\-]/',
                    '_',
                    $nama
                );
        }


        $filename .=
            '_' .
            date('Ymd_His') .
            '.pdf';


        /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */

        $pdf->Output(
            $filename,
            'I'
        );

        exit;
    }
}