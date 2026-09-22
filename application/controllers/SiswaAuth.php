<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SiswaAuth extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library(['session']);
        $this->load->helper(['url', 'form']);
        $this->load->database();
    }

    /**
     * Generate soal matematika random
     */
    private function generate_soal_matematika()
    {
        $operasi = rand(1, 4);

        switch ($operasi) {

            // ==================================================
            // PENJUMLAHAN
            // ==================================================
            case 1:

                $a = rand(1, 50);
                $b = rand(1, 50);

                $soal = $a . ' + ' . $b . ' = ?';
                $jawaban = $a + $b;

                break;


            // ==================================================
            // PENGURANGAN
            // ==================================================
            case 2:

                $a = rand(20, 100);
                $b = rand(1, $a);

                $soal = $a . ' − ' . $b . ' = ?';
                $jawaban = $a - $b;

                break;


            // ==================================================
            // PERKALIAN
            // ==================================================
            case 3:

                $a = rand(2, 20);
                $b = rand(2, 10);

                $soal = $a . ' × ' . $b . ' = ?';
                $jawaban = $a * $b;

                break;


            // ==================================================
            // PEMBAGIAN
            // ==================================================
            case 4:

                // Buat pembagian yang hasilnya bulat
                $b = rand(2, 12);
                $jawaban = rand(2, 15);

                $a = $b * $jawaban;

                $soal = $a . ' ÷ ' . $b . ' = ?';

                break;
        }

        return [
            'soal'    => $soal,
            'jawaban' => $jawaban
        ];
    }


    /**
     * Halaman login siswa
     */
    public function index()
    {
        // Generate soal baru setiap membuka halaman login
        $matematika = $this->generate_soal_matematika();

        // Simpan JAWABAN di session
        // Jawaban tidak ditaruh di HTML
        $this->session->set_userdata([
            'math_soal'    => $matematika['soal'],
            'math_jawaban' => $matematika['jawaban']
        ]);

        $data = [
            'math_soal' => $matematika['soal']
        ];

        $this->load->view('siswa/login', $data);
    }


    /**
     * Proses login
     */
    public function cek_login()
    {
        $nisn      = $this->input->post('nisn', TRUE);
        $password  = $this->input->post('password', TRUE);
        $jawaban   = $this->input->post('jawaban_matematika', TRUE);

        // =====================================================
        // CEK MATEMATIKA
        // =====================================================

        $jawaban_benar = $this->session->userdata('math_jawaban');

        if (
            $jawaban_benar === NULL ||
            trim($jawaban) === '' ||
            (int) $jawaban !== (int) $jawaban_benar
        ) {

            $this->session->set_flashdata(
                'error',
                'Jawaban matematika salah. Silakan coba lagi.'
            );

            redirect('SiswaAuth');
        }


        // =====================================================
        // CEK NISN
        // =====================================================

        $siswa = $this->db->get_where('siswa', [
            'nisn' => $nisn
        ])->row();

        if (!$siswa) {

            $this->session->set_flashdata(
                'error',
                'NISN tidak ditemukan!'
            );

            redirect('SiswaAuth');
        }


        // =====================================================
        // CEK PASSWORD
        // =====================================================

        // Jika password kosong → default = NISN
        $stored = $siswa->password ?: $siswa->nisn;

        if ($password != $stored) {

            $this->session->set_flashdata(
                'error',
                'Password salah!'
            );

            redirect('SiswaAuth');
        }


        // =====================================================
        // SET SESSION SISWA
        // =====================================================

        $this->session->set_userdata([
            'siswa_login' => TRUE,
            'siswa_id'    => $siswa->id,
            'siswa_nama'  => $siswa->nama,
            'siswa_nisn'  => $siswa->nisn,
            'kelas_id'    => $siswa->id_kelas
        ]);


        // Hapus soal matematika setelah berhasil
        $this->session->unset_userdata([
            'math_soal',
            'math_jawaban'
        ]);


        // =====================================================
        // MASUK DASHBOARD
        // =====================================================

        redirect('SiswaDashboard');
    }


    /**
     * Logout
     */
    public function logout()
    {
        $this->session->sess_destroy();

        redirect('SiswaAuth');
    }
}