<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Idcard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('Idcard_lib');
    }


    /**
     * =========================================================
     * DOWNLOAD / CETAK ID CARD
     * =========================================================
     */
    public function cetak($id)
    {
        /*
         * Ambil data siswa hanya untuk nama file
         */
        $siswa = $this->db
            ->select('
                siswa.*,
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
                $id
            )
            ->get()
            ->row();


        if (!$siswa) {

            show_error(
                'Data siswa tidak ditemukan.',
                404
            );

            return;
        }


        /*
         * =====================================================
         * GUNAKAN GENERATOR YANG SAMA DENGAN PREVIEW
         * =====================================================
         *
         * Idcard_lib sudah menangani:
         * - ukuran ID Card
         * - nama sekolah
         * - foto
         * - nama siswa
         * - NISN
         * - NIS
         * - KELAS
         * - QR
         */

        $binary =
            $this->idcard_lib->generate(
                $id
            );


        if (!$binary) {

            show_error(
                'Gagal membuat ID Card.',
                500
            );

            return;
        }


        /*
         * =====================================================
         * NAMA FILE
         * =====================================================
         */

        $namaFile =
            'ID_CARD_' .
            strtoupper(
                preg_replace(
                    '/[^A-Z0-9]+/i',
                    '_',
                    $siswa->nama
                )
            ) .
            '.png';


        /*
         * =====================================================
         * DOWNLOAD
         * =====================================================
         */

        while (ob_get_level()) {
            ob_end_clean();
        }

        header_remove();

        header(
            'Content-Type: image/png'
        );

        header(
            'Content-Disposition: attachment; filename="' .
            $namaFile .
            '"'
        );

        header(
            'Content-Length: ' .
            strlen($binary)
        );

        header(
            'Cache-Control: no-cache, no-store, must-revalidate'
        );

        echo $binary;

        exit;
    }
}