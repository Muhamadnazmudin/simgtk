<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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

        $this->cek_login();
        $this->cek_akses();
    }


    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */

    private function cek_login()
    {
        if (
            !$this->session->userdata('logged_in') &&
            !$this->session->userdata('login')
        ) {
            redirect('auth');
            exit;
        }
    }


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
    | VIEW LAYOUT
    |--------------------------------------------------------------------------
    |
    | Semua halaman admin TKA menggunakan layout yang sama.
    | Ini mencegah sidebar hilang pada halaman input/edit.
    |
    */

    private function render($view, $data = [])
    {
        $data['active'] = 'tka';

        $this->load->view(
            'templates/header',
            $data
        );

        $this->load->view(
            'templates/sidebar',
            $data
        );

        $this->load->view(
            $view,
            $data
        );

        $this->load->view(
            'templates/footer'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REDIRECT ERROR
    |--------------------------------------------------------------------------
    */

    private function error_redirect($message, $url = 'Tka_admin')
    {
        $this->session->set_flashdata(
            'error',
            $message
        );

        redirect($url);
        exit;
    }


    private function success_redirect($message)
    {
        $this->session->set_flashdata(
            'success',
            $message
        );

        redirect('Tka_admin');
        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | TAHUN AJARAN
    |--------------------------------------------------------------------------
    */

    private function get_tahun_aktif()
    {
        $tahun_id = (int) $this->session->userdata(
            'tahun_id'
        );

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


    private function get_tahun_list()
    {
        return $this->db
            ->select('id, tahun')
            ->from('tahun_ajaran')
            ->order_by('id', 'DESC')
            ->get()
            ->result();
    }


    private function get_nama_tahun($tahun_id)
    {
        $row = $this->db
            ->select('tahun')
            ->from('tahun_ajaran')
            ->where(
                'id',
                (int) $tahun_id
            )
            ->limit(1)
            ->get()
            ->row();

        return $row
            ? $row->tahun
            : '-';
    }


    /*
    |--------------------------------------------------------------------------
    | KELAS
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
            ->order_by(
                'nama',
                'ASC'
            )
            ->get()
            ->result();
    }


    private function get_nama_kelas($kelas_id)
    {
        $kelas_id = (int) $kelas_id;

        if ($kelas_id <= 0) {
            return 'Semua Kelas XII';
        }

        $row = $this->db
            ->select('nama')
            ->from('kelas')
            ->where(
                'id',
                $kelas_id
            )
            ->limit(1)
            ->get()
            ->row();

        return $row
            ? $row->nama
            : 'Semua Kelas XII';
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER
    |--------------------------------------------------------------------------
    */

    private function normalize_status($status)
    {
        $status = strtolower(
            trim(
                (string) $status
            )
        );

        if (
            !in_array(
                $status,
                ['sudah', 'belum'],
                true
            )
        ) {
            return '';
        }

        return $status;
    }


    private function get_filter()
    {
        $tahun_id = (int) $this->input->get(
            'tahun_id'
        );

        if ($tahun_id <= 0) {
            $tahun_id = $this->get_tahun_aktif();
        }

        return [
            'tahun_id' => $tahun_id,

            'kelas_id' => (int) $this->input->get(
                'kelas_id'
            ),

            'status' => $this->normalize_status(
                $this->input->get('status')
            ),

            'nama' => trim(
                (string) $this->input->get('nama')
            )
        ];
    }


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
    | SISWA
    |--------------------------------------------------------------------------
    */

    private function get_siswa($siswa_id)
    {
        $siswa_id = (int) $siswa_id;

        if ($siswa_id <= 0) {
            return null;
        }

        return $this->db
            ->select('
                s.*,

                k.nama AS nama_kelas,

                ta.tahun AS tahun_ajaran
            ')
            ->from('siswa s')

            ->join(
                'kelas k',
                'k.id = s.id_kelas',
                'left'
            )

            ->join(
                'tahun_ajaran ta',
                'ta.id = s.tahun_id',
                'left'
            )

            ->where(
                's.id',
                $siswa_id
            )

            ->limit(1)

            ->get()
            ->row();
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDASI SISWA TKA
    |--------------------------------------------------------------------------
    */

    private function validasi_siswa_tka($siswa)
    {
        if (!$siswa) {
            return 'Data siswa tidak ditemukan.';
        }

        $status = strtolower(
            trim(
                (string) (
                    isset($siswa->status)
                        ? $siswa->status
                        : ''
                )
            )
        );

        if ($status !== 'aktif') {
            return 'Siswa sudah tidak aktif.';
        }

        $kelas = strtoupper(
            trim(
                (string) (
                    isset($siswa->nama_kelas)
                        ? $siswa->nama_kelas
                        : ''
                )
            )
        );

        if (
            $kelas !== 'XII' &&
            strpos(
                $kelas,
                'XII '
            ) !== 0
        ) {
            return 'Input TKA hanya diperbolehkan untuk siswa kelas XII.';
        }

        if (
            (int) $siswa->tahun_id <= 0
        ) {
            return 'Tahun ajaran siswa tidak valid.';
        }

        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | DATA TKA
    |--------------------------------------------------------------------------
    */

    private function get_tka($id)
    {
        $id = (int) $id;

        if ($id <= 0) {
            return null;
        }

        return $this->db
            ->select('
                ts.*,

                s.nisn,
                s.nis,
                s.nama,
                s.id_kelas,
                s.tahun_id,
                s.status,

                k.nama AS nama_kelas,

                ta.tahun AS tahun_ajaran,

                m1.nama_mapel AS mapel_1,
                m2.nama_mapel AS mapel_2,

                m1.kode AS kode_mapel_1,
                m2.kode AS kode_mapel_2
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

            ->join(
                'tka_mapel m1',
                'm1.id = ts.mapel_1_id',
                'left'
            )

            ->join(
                'tka_mapel m2',
                'm2.id = ts.mapel_2_id',
                'left'
            )

            ->where(
                'ts.id',
                $id
            )

            ->limit(1)

            ->get()
            ->row();
    }


    /*
    |--------------------------------------------------------------------------
    | MAPEL
    |--------------------------------------------------------------------------
    */

    private function get_mapel_program()
    {
        return $this->Tka_model
            ->get_mapel_program_keahlian();
    }


    private function get_mapel_pilihan()
    {
        /*
        | Tidak menggunakan get_mapel_pilihan_admin()
        | karena method tersebut memang tidak ada
        | di Tka_model saat ini.
        */

        return $this->Tka_model
            ->get_mapel_pilihan();
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDASI MAPEL PILIHAN 2
    |--------------------------------------------------------------------------
    */

    private function validate_mapel_2(
        $mapel_1_id,
        $mapel_2_id
    ) {
        $mapel_1_id = (int) $mapel_1_id;
        $mapel_2_id = (int) $mapel_2_id;

        if ($mapel_2_id <= 0) {

            return [
                'valid' => false,
                'message' =>
                    'Silakan pilih mata pelajaran pilihan kedua.'
            ];
        }

        if (
            $mapel_1_id === $mapel_2_id
        ) {

            return [
                'valid' => false,
                'message' =>
                    'Pilihan kedua tidak boleh sama dengan Program Keahlian.'
            ];
        }

        $mapel = $this->db
            ->where(
                'id',
                $mapel_2_id
            )
            ->where(
                'aktif',
                1
            )
            ->where(
                'kode !=',
                'PK'
            )
            ->limit(1)
            ->get(
                'tka_mapel'
            )
            ->row();

        if (!$mapel) {

            return [
                'valid' => false,
                'message' =>
                    'Mata pelajaran pilihan kedua tidak valid.'
            ];
        }

        return [
            'valid' => true,
            'data' => $mapel
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | DATA PESERTA TKA
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
        | Query utama
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

            ->join(
                'kelas k',
                'k.id = s.id_kelas',
                'left'
            )

            ->join(
                'tahun_ajaran ta',
                'ta.id = s.tahun_id',
                'left'
            )

            /*
            | Penting:
            | TKA harus berdasarkan tahun filter.
            */

            ->join(
                'tka_siswa ts',
                'ts.siswa_id = s.id
                 AND ts.tahun_id = ' . $tahun_id,
                'left',
                false
            )

            ->join(
                'tka_mapel m1',
                'm1.id = ts.mapel_1_id',
                'left'
            )

            ->join(
                'tka_mapel m2',
                'm2.id = ts.mapel_2_id',
                'left'
            );


        /*
        | Hanya siswa aktif
        */

        $this->db->where(
            'LOWER(s.status)',
            'aktif'
        );


        /*
        | Tahun ajaran
        */

        $this->db->where(
            's.tahun_id',
            $tahun_id
        );


        /*
        | Kelas XII
        */

        $this->db->where(
            "UPPER(k.nama) LIKE 'XII %'",
            null,
            false
        );


        /*
        | Filter kelas
        */

        if ($kelas_id > 0) {

            $this->db->where(
                's.id_kelas',
                $kelas_id
            );
        }


        /*
        | Pencarian
        */

        if ($nama !== '') {

            $this->db->like(
                's.nama',
                $nama
            );
        }


        /*
        | Filter status TKA
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
        | Urutan
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
        $filter = $this->get_filter();

        $siswa = $this->get_peserta_tka(
            $filter['tahun_id'],
            $filter['kelas_id'],
            $filter['status'],
            $filter['nama']
        );


        $total_sudah = 0;
        $total_belum = 0;

        foreach ($siswa as $row) {

            if (!empty($row->tka_id)) {
                $total_sudah++;
            } else {
                $total_belum++;
            }
        }


        $data = [

            'siswa' => $siswa,

            'tahun_list' =>
                $this->get_tahun_list(),

            'kelas_list' =>
                $this->get_kelas_xii(),

            'tahun_id' =>
                $filter['tahun_id'],

            'kelas_id' =>
                $filter['kelas_id'],

            'status' =>
                $filter['status'],

            'nama' =>
                $filter['nama'],

            'nama_tahun' =>
                $this->get_nama_tahun(
                    $filter['tahun_id']
                ),

            'nama_kelas' =>
                $this->get_nama_kelas(
                    $filter['kelas_id']
                ),

            'status_label' =>
                $this->get_status_label(
                    $filter['status']
                ),

            'total_siswa' =>
                count($siswa),

            'total_sudah' =>
                $total_sudah,

            'total_belum' =>
                $total_belum
        ];


        $this->render(
            'admin/tka/index',
            $data
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INPUT
    |--------------------------------------------------------------------------
    */

    public function input($siswa_id = null)
    {
        $siswa_id = (int) $siswa_id;

        if ($siswa_id <= 0) {

            $this->error_redirect(
                'ID siswa tidak valid.'
            );
        }


        /*
        | Ambil siswa dengan data kelas
        */

        $siswa = $this->get_siswa(
            $siswa_id
        );


        $validasi = $this->validasi_siswa_tka(
            $siswa
        );

        if ($validasi !== true) {

            $this->error_redirect(
                $validasi
            );
        }


        /*
        | Cek apakah sudah memiliki TKA
        */

        $existing = $this->Tka_model
            ->get_pilihan(
                $siswa->id,
                $siswa->tahun_id
            );


        if ($existing) {

            redirect(
                'Tka_admin/edit/' .
                (int) $existing->id
            );

            exit;
        }


        /*
        | Pilihan 1 otomatis = PK
        */

        $mapel_program =
            $this->get_mapel_program();


        if (!$mapel_program) {

            $this->error_redirect(
                'Mata pelajaran Program Keahlian (PK) belum tersedia.'
            );
        }


        /*
        | Pilihan 2
        */

        $mapel_pilihan =
            $this->get_mapel_pilihan();


        $data = [

            'siswa' =>
                $siswa,

            'mapel_program' =>
                $mapel_program,

            'mapel_pilihan' =>
                $mapel_pilihan
        ];


        /*
        | Layout lengkap:
        | header + sidebar + content + footer
        */

        $this->render(
            'admin/tka/input',
            $data
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    |
    | Satu-satunya method untuk INSERT data TKA baru.
    |
    */

    public function store()
    {
        $siswa_id = (int) $this->input->post(
            'siswa_id'
        );

        if ($siswa_id <= 0) {

            $this->error_redirect(
                'Data siswa tidak valid.'
            );
        }


        /*
        | Ambil siswa dari database.
        | Jangan percaya tahun_id dari browser.
        */

        $siswa = $this->get_siswa(
            $siswa_id
        );


        $validasi = $this->validasi_siswa_tka(
            $siswa
        );

        if ($validasi !== true) {

            $this->error_redirect(
                $validasi
            );
        }


        $tahun_id = (int) $siswa->tahun_id;


        /*
        | Cek duplikat
        */

        $existing = $this->Tka_model
            ->get_pilihan(
                $siswa_id,
                $tahun_id
            );


        if ($existing) {

            $this->session->set_flashdata(
                'error',
                'Siswa tersebut sudah memiliki data TKA.'
            );

            redirect(
                'Tka_admin/edit/' .
                (int) $existing->id
            );

            exit;
        }


        /*
        | Pilihan 1 SELALU PK.
        | Tidak mengambil mapel_1_id dari POST.
        */

        $mapel_program =
            $this->get_mapel_program();


        if (!$mapel_program) {

            $this->error_redirect(
                'Mata pelajaran Program Keahlian (PK) belum tersedia.'
            );
        }


        $mapel_1_id =
            (int) $mapel_program->id;


        /*
        | Pilihan 2
        */

        $mapel_2_id = (int) $this->input->post(
            'mapel_2_id'
        );


        $valid_mapel =
            $this->validate_mapel_2(
                $mapel_1_id,
                $mapel_2_id
            );


        if (!$valid_mapel['valid']) {

            $this->error_redirect(
                $valid_mapel['message'],
                'Tka_admin/input/' .
                $siswa_id
            );
        }


        /*
        | INSERT
        */

        $saved =
            $this->Tka_model->save_pilihan(
                $siswa_id,
                $tahun_id,
                $mapel_1_id,
                $mapel_2_id
            );


        if (!$saved) {

            log_message(
                'error',
                'GAGAL INSERT TKA ADMIN | ' .
                'SISWA ID: ' . $siswa_id .
                ' | QUERY: ' .
                $this->db->last_query()
            );


            $this->error_redirect(
                'Gagal menyimpan pilihan TKA.',
                'Tka_admin/input/' .
                $siswa_id
            );
        }


        $this->success_redirect(
            'Pilihan TKA siswa berhasil disimpan.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id = null)
    {
        $id = (int) $id;

        if ($id <= 0) {
            show_404();
        }


        /*
        | Ambil TKA lengkap dengan siswa.
        */

        $tka = $this->get_tka(
            $id
        );


        if (!$tka) {

            $this->error_redirect(
                'Data TKA tidak ditemukan.'
            );
        }


        /*
        | Validasi siswa.
        */

        $validasi =
            $this->validasi_siswa_tka(
                (object) [
                    'status' =>
                        $tka->status,

                    'nama_kelas' =>
                        $tka->nama_kelas,

                    'tahun_id' =>
                        $tka->tahun_id
                ]
            );


        if ($validasi !== true) {

            show_error(
                $validasi,
                403,
                'Data Tidak Valid'
            );
        }


        /*
        | Semua mapel aktif untuk edit.
        */

        $mapel =
            $this->Tka_model
                ->get_mapel_aktif();


        $data = [

            'tka' =>
                $tka,

            'mapel' =>
                $mapel
        ];


        $this->render(
            'admin/tka/edit',
            $data
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    |
    | Satu-satunya method untuk UPDATE data TKA.
    |
    */

    public function update()
    {
        $id = (int) $this->input->post(
            'id'
        );


        if ($id <= 0) {

            $this->error_redirect(
                'ID TKA tidak valid.'
            );
        }


        /*
        | Ambil data TKA lama.
        */

        $tka = $this->get_tka(
            $id
        );


        if (!$tka) {

            $this->error_redirect(
                'Data TKA tidak ditemukan.'
            );
        }


        /*
        | Pilihan 1 tetap harus PK.
        | Jangan percaya hidden input browser.
        */

        $mapel_program =
            $this->get_mapel_program();


        if (!$mapel_program) {

            $this->error_redirect(
                'Mata pelajaran Program Keahlian (PK) belum tersedia.',
                'Tka_admin/edit/' . $id
            );
        }


        $mapel_1_id =
            (int) $mapel_program->id;


        /*
        | Pilihan 2
        */

        $mapel_2_id = (int) $this->input->post(
            'mapel_2_id'
        );


        $valid_mapel =
            $this->validate_mapel_2(
                $mapel_1_id,
                $mapel_2_id
            );


        if (!$valid_mapel['valid']) {

            $this->error_redirect(
                $valid_mapel['message'],
                'Tka_admin/edit/' . $id
            );
        }


        /*
        | UPDATE
        */

        $updated = $this->db
            ->where(
                'id',
                $id
            )
            ->update(
                'tka_siswa',
                [
                    'mapel_1_id' =>
                        $mapel_1_id,

                    'mapel_2_id' =>
                        $mapel_2_id
                ]
            );


        if (!$updated) {

            log_message(
                'error',
                'GAGAL UPDATE TKA | ' .
                'ID: ' . $id .
                ' | QUERY: ' .
                $this->db->last_query()
            );


            $this->error_redirect(
                'Gagal menyimpan perubahan TKA.',
                'Tka_admin/edit/' . $id
            );
        }


        $this->success_redirect(
            'Pilihan TKA berhasil diperbarui.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORT FILTER
    |--------------------------------------------------------------------------
    */

    private function get_export_filter()
    {
        $tahun_id = (int) $this->input->get(
            'tahun_id'
        );

        if ($tahun_id <= 0) {
            $tahun_id =
                $this->get_tahun_aktif();
        }

        return [

            'tahun_id' =>
                $tahun_id,

            'kelas_id' =>
                (int) $this->input->get(
                    'kelas_id'
                ),

            'status' =>
                $this->normalize_status(
                    $this->input->get(
                        'status'
                    )
                ),

            'nama' =>
                trim(
                    (string) $this->input->get(
                        'nama'
                    )
                )
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    */

    public function export_excel()
    {
        $filter =
            $this->get_export_filter();


        $siswa =
            $this->get_peserta_tka(
                $filter['tahun_id'],
                $filter['kelas_id'],
                $filter['status'],
                $filter['nama']
            );


        $tahun_ajaran =
            $this->get_nama_tahun(
                $filter['tahun_id']
            );


        $kelas_nama =
            $this->get_nama_kelas(
                $filter['kelas_id']
            );


        $status_label =
            $this->get_status_label(
                $filter['status']
            );


        /*
        | Composer
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
        | Spreadsheet
        */

        $spreadsheet =
            new Spreadsheet();


        $sheet =
            $spreadsheet->getActiveSheet();


        $sheet->setTitle(
            'Peserta TKA'
        );


        /*
        | Header
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


        if ($filter['nama'] !== '') {

            $filter_info .=
                ' | Pencarian: "' .
                $filter['nama'] .
                '"';
        }


        $sheet->setCellValue(
            'A3',
            $filter_info
        );


        /*
        | Column header
        */

        $headers = [

            'A5' => 'No',

            'B5' => 'NISN',

            'C5' => 'NIS',

            'D5' => 'Nama Siswa',

            'E5' => 'Kelas',

            'F5' => 'Pilihan TKA 1',

            'G5' => 'Pilihan TKA 2'
        ];


        foreach (
            $headers as $cell => $value
        ) {

            $sheet->setCellValue(
                $cell,
                $value
            );
        }


        /*
        | Data
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
                (string) (
                    !empty($row->nisn)
                        ? $row->nisn
                        : '-'
                ),
                DataType::TYPE_STRING
            );


            $sheet->setCellValueExplicit(
                'C' . $row_number,
                (string) (
                    !empty($row->nis)
                        ? $row->nis
                        : '-'
                ),
                DataType::TYPE_STRING
            );


            $sheet->setCellValue(
                'D' . $row_number,
                !empty($row->nama)
                    ? $row->nama
                    : '-'
            );


            $sheet->setCellValue(
                'E' . $row_number,
                !empty($row->nama_kelas)
                    ? $row->nama_kelas
                    : '-'
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
        | Border
        */

        $last_row =
            max(
                5,
                $row_number - 1
            );


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
        | Alignment
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
        | Width
        */

        foreach (
            ['A','B','C','D','E','F','G']
            as $column
        ) {

            $sheet
                ->getColumnDimension($column)
                ->setAutoSize(true);
        }


        $sheet->freezePane(
            'A6'
        );


        /*
        | Filename
        */

        $filename =
            'Peserta_TKA_' .
            preg_replace(
                '/[^0-9\-]/',
                '_',
                $tahun_ajaran
            );


        if ($filter['nama'] !== '') {

            $filename .=
                '_Cari_' .
                preg_replace(
                    '/[^a-zA-Z0-9\-]/',
                    '_',
                    $filter['nama']
                );
        }


        $filename .=
            '_' .
            date('Ymd_His') .
            '.xlsx';


        /*
        | Output
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
        $filter =
            $this->get_export_filter();


        $siswa =
            $this->get_peserta_tka(
                $filter['tahun_id'],
                $filter['kelas_id'],
                $filter['status'],
                $filter['nama']
            );


        $tahun_ajaran =
            $this->get_nama_tahun(
                $filter['tahun_id']
            );


        $kelas_nama =
            $this->get_nama_kelas(
                $filter['kelas_id']
            );


        $status_label =
            $this->get_status_label(
                $filter['status']
            );


        /*
        | PDF
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
        | Judul
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
        | Filter
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


        if ($filter['nama'] !== '') {

            $pdf->Cell(
                0,
                6,
                'Pencarian Nama: "' .
                $filter['nama'] .
                '"',
                0,
                1,
                'L'
            );
        }


        $pdf->Ln(2);


        /*
        | Lebar tabel
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
        | Header tabel
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
                240,
                244,
                248
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


        $draw_header();


        /*
        | Data
        */

        $no = 1;


        foreach ($siswa as $row) {

            $nisn =
                !empty($row->nisn)
                    ? $row->nisn
                    : '-';

            $nis =
                !empty($row->nis)
                    ? $row->nis
                    : '-';

            $nama_siswa =
                !empty($row->nama)
                    ? $row->nama
                    : '-';

            $kelas =
                !empty($row->nama_kelas)
                    ? $row->nama_kelas
                    : '-';

            $mapel_1 =
                !empty($row->mapel_1)
                    ? $row->mapel_1
                    : '-';

            $mapel_2 =
                !empty($row->mapel_2)
                    ? $row->mapel_2
                    : '-';


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
            | Cek halaman
            */

            if (
                $pdf->GetY() +
                $row_height >
                190
            ) {

                $pdf->AddPage();

                $draw_header();
            }


            $x =
                $pdf->GetX();

            $y =
                $pdf->GetY();


            /*
            | NO
            */

            $pdf->MultiCell(
                $w_no,
                $row_height,
                (string) $no,
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
            | NISN
            */

            $x += $w_no;

            $pdf->MultiCell(
                $w_nisn,
                $row_height,
                $nisn,
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
            | NIS
            */

            $x += $w_nisn;

            $pdf->MultiCell(
                $w_nis,
                $row_height,
                $nis,
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
            | NAMA
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
            | KELAS
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
            | TKA 1
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
            | TKA 2
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
        | Data kosong
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
                $filter['nama'] !== ''
                    ? 'Tidak ditemukan siswa dengan nama "' .
                      $filter['nama'] .
                      '".'
                    : 'Tidak ada data peserta TKA.',
                1,
                1,
                'C'
            );
        }


        /*
        | Filename
        */

        $filename =
            'Peserta_TKA_' .
            preg_replace(
                '/[^0-9\-]/',
                '_',
                $tahun_ajaran
            );


        if ($filter['nama'] !== '') {

            $filename .=
                '_Cari_' .
                preg_replace(
                    '/[^a-zA-Z0-9\-]/',
                    '_',
                    $filter['nama']
                );
        }


        $filename .=
            '_' .
            date('Ymd_His') .
            '.pdf';


        $pdf->Output(
            $filename,
            'I'
        );

        exit;
    }
}