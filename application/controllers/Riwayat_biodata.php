<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_biodata extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Siswa_biodata_log_model');
    }


    // =========================================================
    // CEK AKSES
    // =========================================================

    private function cek_akses()
    {
        $role_id = $this->session->userdata('role_id');

        /*
         * 1 = Admin
         * 3 = Guru / Wali Kelas
         */

        if ($role_id != 1 && $role_id != 3) {

            show_error(
                'Anda tidak memiliki akses ke halaman ini.',
                403,
                'Akses Ditolak'
            );
        }
    }


    // =========================================================
    // HALAMAN UTAMA
    // =========================================================

    public function index()
{
    // =========================================================
    // CEK AKSES
    // =========================================================
    $this->cek_akses();

    $role_id = (int) $this->session->userdata('role_id');


    // =========================================================
    // FILTER SEARCH
    // =========================================================
    $search = trim(
        $this->input->get('search', TRUE)
    );


    // =========================================================
    // FILTER KELAS
    // =========================================================
    $kelas_id = trim(
        $this->input->get('kelas_id', TRUE)
    );


    /*
     * ADMIN
     * ----------
     * Boleh memilih semua kelas.
     *
     * WALIKELAS
     * ----------
     * Tidak boleh memilih kelas sendiri.
     * Kelas dipaksa menggunakan kelas yang ada
     * di session login.
     */

    if ($role_id === 3) {

        $kelas_id = $this->session->userdata('kelas_id');

        if (!$kelas_id) {

            show_error(
                'Kelas wali kelas tidak ditemukan.',
                403,
                'Akses Ditolak'
            );
        }
    }


    // =========================================================
    // PAGINATION
    // =========================================================
    $per_page = 25;

    $page = (int) $this->input->get(
        'page',
        TRUE
    );

    if ($page < 1) {
        $page = 1;
    }

    $offset = ($page - 1) * $per_page;


    // =========================================================
    // TOTAL SISWA YANG MEMILIKI RIWAYAT
    // =========================================================
    $total =
        $this->Siswa_biodata_log_model
            ->count_siswa_summary(
                $search,
                $kelas_id
            );


    // =========================================================
    // DATA RIWAYAT SISWA
    // =========================================================
    $data['riwayat'] =
        $this->Siswa_biodata_log_model
            ->get_siswa_summary(
                $per_page,
                $offset,
                $search,
                $kelas_id
            );


    // =========================================================
    // DATA KELAS
    // =========================================================

    if ($role_id === 1) {

        // -----------------------------------------
        // ADMIN
        // -----------------------------------------

        $data['kelas'] =
            $this->Siswa_biodata_log_model
                ->get_kelas();

    } else {

        // -----------------------------------------
        // WALIKELAS
        // Tidak perlu dropdown kelas
        // -----------------------------------------

        $data['kelas'] = [];
    }


    // =========================================================
    // FILTER UNTUK VIEW
    // =========================================================

    $data['search']   = $search;
    $data['kelas_id'] = $kelas_id;


    // =========================================================
    // DATA PAGINATION
    // =========================================================

    $data['page'] = $page;

    $data['per_page'] =
        $per_page;

    $data['total'] =
        $total;

    $data['total_page'] =
        ($total > 0)
            ? ceil($total / $per_page)
            : 1;


    // =========================================================
    // STATISTIK
    // =========================================================

    if ($role_id === 1) {

        // =====================================================
        // ADMIN
        // SEMUA SISWA
        // =====================================================

        $data['total_siswa'] =
            $this->Siswa_biodata_log_model
                ->count_all_siswa();

        $data['total_log'] =
            $this->Siswa_biodata_log_model
                ->count_all_log();

        $data['today'] =
            $this->Siswa_biodata_log_model
                ->count_today();

    } else {

        // =====================================================
        // WALIKELAS
        // HANYA KELASNYA
        // =====================================================

        $data['total_siswa'] =
            $this->Siswa_biodata_log_model
                ->count_all_siswa(
                    $kelas_id
                );

        $data['total_log'] =
            $this->Siswa_biodata_log_model
                ->count_all_log(
                    $kelas_id
                );

        $data['today'] =
            $this->Siswa_biodata_log_model
                ->count_today(
                    $kelas_id
                );
    }


    // =========================================================
    // ACTIVE MENU
    // =========================================================

    if ($role_id === 3) {

        $data['active'] =
            'wk_riwayat';

    } else {

        $data['active'] =
            'riwayat_biodata';
    }


    // =========================================================
    // TITLE
    // =========================================================

    $data['title'] =
        'Riwayat Perubahan Biodata';


    // =========================================================
    // LOAD VIEW SESUAI ROLE
    // =========================================================

    if ($role_id === 3) {

        // =====================================================
        // WALIKELAS
        // =====================================================

        $this->load->view(
            'walikelas/templates/header',
            $data
        );

        $this->load->view(
            'walikelas/templates/sidebar',
            $data
        );

        $this->load->view(
            'riwayat_biodata/index',
            $data
        );

        $this->load->view(
            'walikelas/templates/footer',
            $data
        );

    } else {

        // =====================================================
        // ADMIN
        // =====================================================

        $this->load->view(
            'templates/header',
            $data
        );

        $this->load->view(
            'templates/sidebar',
            $data
        );

        $this->load->view(
            'riwayat_biodata/index',
            $data
        );

        $this->load->view(
            'templates/footer',
            $data
        );
    }
}

    // =========================================================
    // DETAIL RIWAYAT SATU SISWA
    // =========================================================

    public function detail($siswa_id)
    {
        $this->cek_akses();


        $role_id =
            $this->session->userdata('role_id');


        $siswa_id =
            (int) $siswa_id;


        if (!$siswa_id) {

            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(
                    json_encode([
                        'status'  => false,
                        'message' => 'ID siswa tidak valid.'
                    ])
                );
        }


        // =====================================================
        // CEK SISWA
        // =====================================================

        $siswa = $this->db
            ->select('
                siswa.id,
                siswa.nama,
                siswa.nisn,
                siswa.id_kelas,
                kelas.nama AS nama_kelas
            ')
            ->from('siswa')
            ->join(
                'kelas',
                'kelas.id = siswa.id_kelas',
                'left'
            )
            ->where(
                'siswa.id',
                $siswa_id
            )
            ->get()
            ->row();


        if (!$siswa) {

            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(
                    json_encode([
                        'status'  => false,
                        'message' => 'Siswa tidak ditemukan.'
                    ])
                );
        }


        // =====================================================
        // WALIKELAS
        // =====================================================

        if ($role_id == 3) {

            $kelas_wali =
                (int) $this->session
                    ->userdata('kelas_id');


            /*
             * INI SECURITY PALING PENTING.
             *
             * Walikelas hanya boleh melihat
             * siswa dari kelasnya sendiri.
             */

            if (
                (int) $siswa->id_kelas
                !== $kelas_wali
            ) {

                return $this->output
                    ->set_status_header(403)
                    ->set_content_type(
                        'application/json'
                    )
                    ->set_output(
                        json_encode([
                            'status'  => false,
                            'message' =>
                                'Anda tidak memiliki akses ke data siswa ini.'
                        ])
                    );
            }
        }


        // =====================================================
        // AMBIL LOG
        // =====================================================

        $logs =
            $this->Siswa_biodata_log_model
                ->get_by_siswa(
                    $siswa_id
                );


        return $this->output
            ->set_content_type(
                'application/json'
            )
            ->set_output(
                json_encode([
                    'status' => true,
                    'siswa'  => $siswa,
                    'data'   => $logs
                ])
            );
    }
}