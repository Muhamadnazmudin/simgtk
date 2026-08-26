<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tka extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('Tka_model');
        $this->load->model('Siswa_model');
    }


    /*
    |--------------------------------------------------------------------------
    | CEK LOGIN
    |--------------------------------------------------------------------------
    */
    private function cek_login()
    {
        if (!$this->session->userdata('siswa_login')) {
            redirect('SiswaAuth');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL DATA SISWA
    |--------------------------------------------------------------------------
    */
    private function getSiswa()
    {
        $siswa_id = (int) $this->session->userdata('siswa_id');

        if ($siswa_id <= 0) {
            return null;
        }

        return $this->db
            ->select('
                siswa.*,
                kelas.nama AS nama_kelas,
                tahun_ajaran.tahun AS tahun_ajaran
            ')
            ->from('siswa')

            ->join(
                'kelas',
                'kelas.id = siswa.id_kelas',
                'left'
            )

            ->join(
                'tahun_ajaran',
                'tahun_ajaran.id = siswa.tahun_id',
                'left'
            )

            ->where(
                'siswa.id',
                $siswa_id
            )

            ->limit(1)

            ->get()
            ->row();
    }


    /*
    |--------------------------------------------------------------------------
    | CEK KELAS XII
    |--------------------------------------------------------------------------
    */
    private function cek_kelas_xii($siswa)
    {
        if (!$siswa) {
            redirect('SiswaAuth/logout');
        }

        $nama_kelas = strtoupper(
            trim((string) $siswa->nama_kelas)
        );

        if (
            $nama_kelas !== 'XII' &&
            strpos($nama_kelas, 'XII ') !== 0
        ) {

            $this->session->set_flashdata(
                'error',
                'Form TKA hanya dapat diakses oleh siswa kelas XII.'
            );

            redirect('SiswaDashboard');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | HALAMAN TKA
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->cek_login();

        $siswa = $this->getSiswa();

        if (!$siswa) {
            redirect('SiswaAuth/logout');
        }

        $this->cek_kelas_xii($siswa);

        $tahun_id = (int) $siswa->tahun_id;


        /*
        |--------------------------------------------------------------------------
        | DATA PILIHAN YANG SUDAH TERSIMPAN
        |--------------------------------------------------------------------------
        */
        $pilihan = $this->Tka_model->get_pilihan(
            $siswa->id,
            $tahun_id
        );


        /*
        |--------------------------------------------------------------------------
        | MAPEL PROGRAM KEAHLIAN
        |--------------------------------------------------------------------------
        |
        | Selalu berasal dari tka_mapel dengan kode PK.
        |
        */
        $mapel_program = $this->Tka_model
            ->get_mapel_program_keahlian();


        /*
        |--------------------------------------------------------------------------
        | MAPEL PILIHAN SISWA
        |--------------------------------------------------------------------------
        |
        | Program Keahlian otomatis dikeluarkan dari pilihan 2.
        |
        */
        $mapel_pilihan = [];

        if (!$pilihan) {

            $mapel_pilihan = $this->Tka_model
                ->get_mapel_pilihan();
        }


        /*
        |--------------------------------------------------------------------------
        | DATA VIEW
        |--------------------------------------------------------------------------
        */
        $data = [

            'siswa' => $siswa,

            'pilihan' => $pilihan,

            'mapel_program' => $mapel_program,

            'mapel' => $mapel_pilihan,

            'tka_terkunci' => !empty($pilihan),

            'active' => 'tka'
        ];


        $this->load->view(
            'siswa/layout/header',
            $data
        );

        $this->load->view(
            'siswa/layout/sidebar',
            $data
        );

        $this->load->view(
            'siswa/tka/index',
            $data
        );

        $this->load->view(
            'siswa/layout/footer'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN PILIHAN TKA
    |--------------------------------------------------------------------------
    */
    public function simpan()
    {
        $this->cek_login();

        $siswa = $this->getSiswa();

        if (!$siswa) {
            redirect('SiswaAuth/logout');
        }

        $this->cek_kelas_xii($siswa);

        $tahun_id = (int) $siswa->tahun_id;


        /*
        |--------------------------------------------------------------------------
        | CEK SUDAH MEMILIH
        |--------------------------------------------------------------------------
        */
        $sudah_memilih = $this->Tka_model->get_pilihan(
            $siswa->id,
            $tahun_id
        );

        if ($sudah_memilih) {

            $this->session->set_flashdata(
                'error',
                'Pilihan TKA Anda sudah dikunci. Silakan hubungi admin jika terdapat kesalahan.'
            );

            redirect('Tka');
        }


        /*
        |--------------------------------------------------------------------------
        | MAPEL 1 OTOMATIS = PROGRAM KEAHLIAN
        |--------------------------------------------------------------------------
        */
        $mapel_1 = $this->Tka_model
            ->get_mapel_program_keahlian();


        if (!$mapel_1) {

            $this->session->set_flashdata(
                'error',
                'Mata pelajaran Program Keahlian belum tersedia. Silakan hubungi admin.'
            );

            redirect('Tka');
        }


        $mapel_1_id = (int) $mapel_1->id;


        /*
        |--------------------------------------------------------------------------
        | MAPEL 2 = PILIHAN SISWA
        |--------------------------------------------------------------------------
        */
        $mapel_2_id = (int) $this->input->post(
            'mapel_2_id'
        );


        if ($mapel_2_id <= 0) {

            $this->session->set_flashdata(
                'error',
                'Silakan pilih mata pelajaran pilihan kedua.'
            );

            redirect('Tka');
        }


        /*
        |--------------------------------------------------------------------------
        | MAPEL 2 TIDAK BOLEH PROGRAM KEAHLIAN
        |--------------------------------------------------------------------------
        */
        if ($mapel_2_id === $mapel_1_id) {

            $this->session->set_flashdata(
                'error',
                'Mata pelajaran pilihan kedua tidak boleh Program Keahlian.'
            );

            redirect('Tka');
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI MAPEL 2
        |--------------------------------------------------------------------------
        */
        $mapel_2 = $this->db
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
            ->get('tka_mapel')
            ->row();


        if (!$mapel_2) {

            $this->session->set_flashdata(
                'error',
                'Mata pelajaran pilihan kedua tidak valid.'
            );

            redirect('Tka');
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */
        $save = $this->Tka_model->save_pilihan(
            $siswa->id,
            $tahun_id,
            $mapel_1_id,
            $mapel_2_id
        );


        if (!$save) {

            log_message(
                'error',
                'GAGAL SIMPAN TKA | SISWA ID: ' .
                $siswa->id .
                ' | QUERY: ' .
                $this->db->last_query()
            );

            $this->session->set_flashdata(
                'error',
                'Gagal menyimpan pilihan TKA.'
            );

            redirect('Tka');
        }


        $this->session->set_flashdata(
            'success',
            'Pilihan TKA berhasil disimpan dan dikunci.'
        );

        redirect('Tka');
    }
}