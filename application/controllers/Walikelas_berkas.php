<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Walikelas_berkas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // ==========================================
        // CEK LOGIN GURU
        // ==========================================

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
            return;
        }

        // ==========================================
        // CEK GURU
        // ==========================================

        $guru_id = (int) $this->session->userdata('guru_id');

        if (!$guru_id) {
            show_error('Akses ditolak.', 403);
            return;
        }

        // ==========================================
        // CEK WALI KELAS
        // ==========================================

        $kelas = $this->db
            ->where('wali_kelas_id', $guru_id)
            ->get('kelas')
            ->row();

        if (!$kelas) {
            show_error('Anda bukan wali kelas.', 403);
            return;
        }

        // ==========================================
        // SIMPAN KELAS
        // ==========================================

        $this->session->set_userdata([
            'kelas_id'   => $kelas->id,
            'kelas_nama' => $kelas->nama
        ]);
    }


    // =========================================================
    // DAFTAR BERKAS SISWA
    // =========================================================

    public function index()
    {
        $kelas_id   = (int) $this->session->userdata('kelas_id');
        $kelas_nama = $this->session->userdata('kelas_nama');

        if (!$kelas_id) {
            show_error('Kelas wali tidak ditemukan.', 403);
            return;
        }

        // ==========================================
        // AMBIL SISWA KELAS WALI
        // ==========================================

        $this->db->select('
            siswa.id,
            siswa.nis,
            siswa.nisn,
            siswa.nama,
            siswa.id_kelas,
            kelas.nama AS nama_kelas
        ');

        $this->db->from('siswa');

        $this->db->join(
            'kelas',
            'kelas.id = siswa.id_kelas',
            'left'
        );

        $this->db->where(
            'siswa.id_kelas',
            $kelas_id
        );

        $this->db->where(
            'siswa.status',
            'aktif'
        );

        $this->db->order_by(
            'siswa.nama',
            'ASC'
        );

        $siswa = $this->db
            ->get()
            ->result();

        // ==========================================
        // HITUNG BERKAS
        // ==========================================

        foreach ($siswa as $s) {

            $s->jumlah_berkas = $this->db
                ->where(
                    'siswa_id',
                    $s->id
                )
                ->count_all_results('siswa_berkas');
        }

        // ==========================================
        // DATA VIEW
        // ==========================================

        $data = [
            'title'      => 'Berkas Siswa',
            'active'     => 'wk_berkas',
            'kelas_nama' => $kelas_nama,
            'siswa'      => $siswa
        ];

        // ==========================================
        // VIEW
        // ==========================================

        $this->load->view(
            'walikelas/templates/header',
            $data
        );

        $this->load->view(
            'walikelas/templates/sidebar',
            $data
        );

        $this->load->view(
            'walikelas/berkas/index',
            $data
        );

        $this->load->view(
            'walikelas/templates/footer',
            $data
        );
    }


    // =========================================================
    // DETAIL BERKAS SISWA
    // =========================================================

    public function detail($siswa_id = 0)
    {
        $kelas_id = (int) $this->session->userdata('kelas_id');

        $siswa_id = (int) $siswa_id;

        if (!$kelas_id) {
            show_error('Kelas wali tidak ditemukan.', 403);
            return;
        }

        if (!$siswa_id) {
            show_404();
            return;
        }

        // ==========================================
        // AMBIL SISWA
        // HANYA SISWA DI KELAS WALI
        // ==========================================

        $siswa = $this->db
            ->select('
                siswa.id,
                siswa.nis,
                siswa.nisn,
                siswa.nama,
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
            ->where(
                'siswa.id_kelas',
                $kelas_id
            )
            ->where(
                'siswa.status',
                'aktif'
            )
            ->get()
            ->row();

        // ==========================================
        // SISWA TIDAK DITEMUKAN
        // ==========================================

        if (!$siswa) {
            show_error(
                'Siswa tidak ditemukan atau bukan siswa kelas Anda.',
                404
            );

            return;
        }

        // ==========================================
        // AMBIL BERKAS
        // ==========================================

        $berkas = $this->db
            ->where(
                'siswa_id',
                $siswa_id
            )
            ->order_by(
                'jenis_berkas',
                'ASC'
            )
            ->get('siswa_berkas')
            ->result();

        // ==========================================
        // DATA VIEW
        // ==========================================

        $data = [
            'title'      => 'Berkas Siswa - ' . $siswa->nama,
            'active'     => 'wk_berkas',
            'kelas_nama' => $this->session->userdata('kelas_nama'),
            'siswa'      => $siswa,
            'berkas'     => $berkas
        ];

        // ==========================================
        // VIEW
        // ==========================================

        $this->load->view(
            'walikelas/templates/header',
            $data
        );

        $this->load->view(
            'walikelas/templates/sidebar',
            $data
        );

        $this->load->view(
            'walikelas/berkas/detail',
            $data
        );

        $this->load->view(
            'walikelas/templates/footer',
            $data
        );
    }
}