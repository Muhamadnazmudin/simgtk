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

    /**
     * =========================================================
     * CEK LOGIN SISWA
     * =========================================================
     */
    private function cek_login()
    {
        if (!$this->session->userdata('siswa_login')) {
            redirect('SiswaAuth');
        }
    }

    /**
     * =========================================================
     * AMBIL DATA SISWA
     * =========================================================
     */
    private function getSiswa()
    {
        $siswa_id = $this->session->userdata('siswa_id');

        if (!$siswa_id) {
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
            ->get()
            ->row();
    }

    /**
     * =========================================================
     * CEK SISWA KELAS XII
     * =========================================================
     */
    private function cek_kelas_xii($siswa)
    {
        if (!$siswa) {
            redirect('SiswaAuth/logout');
        }

        $nama_kelas = strtoupper(
            trim($siswa->nama_kelas)
        );

        if (
            strpos($nama_kelas, 'XII ') !== 0 &&
            $nama_kelas !== 'XII'
        ) {

            $this->session->set_flashdata(
                'error',
                'Form TKA hanya dapat diakses oleh siswa kelas XII.'
            );

            redirect('SiswaDashboard');
        }
    }

    /**
     * =========================================================
     * HALAMAN TKA
     * =========================================================
     */
    public function index()
    {
        $this->cek_login();

        $siswa = $this->getSiswa();

        if (!$siswa) {
            redirect('SiswaAuth/logout');
        }

        // Hanya kelas XII
        $this->cek_kelas_xii($siswa);

        $tahun_id = (int) $siswa->tahun_id;

        $data['siswa'] = $siswa;

        /*
         * Ambil pilihan siswa pada tahun ajaran aktif
         */
        $data['pilihan'] = $this->Tka_model
            ->get_pilihan(
                $siswa->id,
                $tahun_id
            );

        /*
         * Hanya ambil master mapel jika
         * siswa BELUM mempunyai pilihan.
         *
         * Kalau sudah terkunci, sebenarnya
         * tidak perlu mengirim daftar mapel lagi.
         */
        $data['mapel'] = [];

        if (!$data['pilihan']) {

            $data['mapel'] = $this->Tka_model
                ->get_mapel_aktif();
        }

        /*
         * Status pengisian
         */
        $data['tka_terkunci'] = !empty($data['pilihan']);

        $data['active'] = 'tka';

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

    /**
     * =========================================================
     * SIMPAN PILIHAN TKA
     * =========================================================
     *
     * PENTING:
     * Method ini HANYA BOLEH INSERT.
     *
     * Jika siswa sudah mempunyai pilihan,
     * maka ditolak.
     */
    public function simpan()
    {
        $this->cek_login();

        $siswa = $this->getSiswa();

        if (!$siswa) {
            redirect('SiswaAuth/logout');
        }

        // Hanya XII
        $this->cek_kelas_xii($siswa);

        $tahun_id = (int) $siswa->tahun_id;

        /*
         * =====================================================
         * CEK APAKAH SUDAH PERNAH MEMILIH
         * =====================================================
         */

        $sudah_memilih = $this->Tka_model
            ->get_pilihan(
                $siswa->id,
                $tahun_id
            );

        if ($sudah_memilih) {

            $this->session->set_flashdata(
                'error',
                'Pilihan TKA Anda sudah dikunci dan tidak dapat diubah. Silakan hubungi admin jika ingin melakukan perubahan.'
            );

            redirect('Tka');
        }

        /*
         * =====================================================
         * AMBIL INPUT
         * =====================================================
         */

        $mapel_1_id = (int) $this->input->post('mapel_1_id');
        $mapel_2_id = (int) $this->input->post('mapel_2_id');

        /*
         * =====================================================
         * VALIDASI
         * =====================================================
         */

        if (!$mapel_1_id) {

            $this->session->set_flashdata(
                'error',
                'Silakan pilih mata pelajaran TKA pertama.'
            );

            redirect('Tka');
        }

        if (!$mapel_2_id) {

            $this->session->set_flashdata(
                'error',
                'Silakan pilih mata pelajaran TKA kedua.'
            );

            redirect('Tka');
        }

        /*
         * Tidak boleh memilih mapel yang sama
         */
        if ($mapel_1_id === $mapel_2_id) {

            $this->session->set_flashdata(
                'error',
                'Mata pelajaran pilihan 1 dan 2 tidak boleh sama.'
            );

            redirect('Tka');
        }

        /*
         * =====================================================
         * PASTIKAN MAPEL AKTIF
         * =====================================================
         */

        $mapel_count = $this->db
            ->where_in(
                'id',
                [$mapel_1_id, $mapel_2_id]
            )
            ->where(
                'aktif',
                1
            )
            ->count_all_results('tka_mapel');

        if ($mapel_count != 2) {

            $this->session->set_flashdata(
                'error',
                'Mata pelajaran TKA yang dipilih tidak valid.'
            );

            redirect('Tka');
        }

        /*
         * =====================================================
         * INSERT PERTAMA
         * =====================================================
         *
         * Jangan menggunakan UPDATE.
         */

        $data = [
            'siswa_id'   => $siswa->id,
            'tahun_id'   => $tahun_id,
            'mapel_1_id' => $mapel_1_id,
            'mapel_2_id' => $mapel_2_id
        ];

        $save = $this->db
            ->insert(
                'tka_siswa',
                $data
            );

        if (!$save) {

            log_message(
                'error',
                'GAGAL SIMPAN TKA SISWA ID: ' .
                $siswa->id .
                ' | ' .
                $this->db->last_query()
            );

            $this->session->set_flashdata(
                'error',
                'Gagal menyimpan pilihan TKA.'
            );

            redirect('Tka');
        }

        /*
         * =====================================================
         * BERHASIL
         * =====================================================
         */

        $this->session->set_flashdata(
            'success',
            'Pilihan TKA berhasil disimpan dan sekarang telah dikunci.'
        );

        redirect('Tka');
    }
}