<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SiswaBerkas extends CI_Controller
{
    public function __construct()
{
    parent::__construct();

    // ==========================================
    // LIBRARY
    // ==========================================

    $this->load->library([
        'session',
        'upload'
    ]);

    // ==========================================
    // HELPER
    // ==========================================

    $this->load->helper([
        'url',
        'form',
        'file'
    ]);

    // ==========================================
    // MODEL
    // ==========================================

    $this->load->model(
        'Berkas_siswa_model',
        'berkas'
    );

    // ==========================================
    // CEK LOGIN SISWA
    // ==========================================

    $siswa_id = (int) $this->session->userdata('siswa_id');

    if (!$siswa_id) {

        redirect('SiswaAuth');

        exit;
    }
}

    // =========================================================
    // INDEX
    // =========================================================

    public function index()
    {
        /*
         * ID SISWA DIAMBIL DARI SESSION
         *
         * BUKAN dari URL.
         */

        $siswa_id =
            (int) $this->session->userdata('siswa_id');


        if (!$siswa_id) {

            redirect('SiswaAuth/logout');

            return;
        }


        // =====================================================
        // DATA SISWA
        // =====================================================

        $siswa =
            $this->berkas
                ->get_siswa_by_id($siswa_id);


        if (!$siswa) {

            show_error(
                'Data siswa tidak ditemukan.'
            );

            return;
        }


        // =====================================================
        // JENIS BERKAS
        // =====================================================

        $jenis_berkas =
            $this->berkas->jenis_berkas();


        // =====================================================
        // BERKAS SISWA
        // =====================================================

        $berkas =
            $this->berkas
                ->get_all_berkas($siswa_id);


        // =====================================================
        // DATA VIEW
        // =====================================================

        $data = [

            'title' =>
                'Upload Berkas',

            'active' =>
                'berkas',

            'siswa' =>
                $siswa,

            'jenis_berkas' =>
                $jenis_berkas,

            'berkas' =>
                $berkas

        ];


        // =====================================================
        // LAYOUT SISWA
        // =====================================================

        $this->load->view(
            'siswa/layout/header',
            $data
        );

        $this->load->view(
            'siswa/layout/sidebar',
            $data
        );

        $this->load->view(
            'siswa/berkas/index',
            $data
        );

        $this->load->view(
            'siswa/layout/footer'
        );
    }


    // =========================================================
    // UPLOAD
    // =========================================================

    public function upload()
    {
        // =====================================================
        // AMBIL ID DARI SESSION
        // =====================================================

        $siswa_id =
            (int) $this->session->userdata('siswa_id');


        if (!$siswa_id) {

            redirect('SiswaAuth/logout');

            return;
        }


        // =====================================================
        // CEK SISWA
        // =====================================================

        $siswa =
            $this->berkas
                ->get_siswa_by_id($siswa_id);


        if (!$siswa) {

            show_error(
                'Data siswa tidak ditemukan.'
            );

            return;
        }


        // =====================================================
        // JENIS BERKAS
        // =====================================================

        $jenis =
            trim(
                $this->input->post(
                    'jenis_berkas',
                    TRUE
                )
            );


        $jenis_berkas =
            $this->berkas->jenis_berkas();


        // =====================================================
        // VALIDASI JENIS
        // =====================================================

        if (
            empty($jenis) ||
            !array_key_exists(
                $jenis,
                $jenis_berkas
            )
        ) {

            $this->session->set_flashdata(
                'error',
                'Jenis berkas tidak valid.'
            );

            redirect(
                'SiswaBerkas'
            );

            return;
        }


        // =====================================================
        // CEK FILE
        // =====================================================

        if (
            !isset($_FILES['file']) ||
            empty($_FILES['file']['name'])
        ) {

            $this->session->set_flashdata(
                'error',
                'Silakan pilih file terlebih dahulu.'
            );

            redirect(
                'SiswaBerkas'
            );

            return;
        }


        // =====================================================
        // FOLDER SISWA
        // =====================================================

        $upload_path =
            FCPATH .
            'uploads/berkas_siswa/' .
            $siswa_id .
            '/';


        if (!is_dir($upload_path)) {

            mkdir(
                $upload_path,
                0755,
                TRUE
            );
        }


        // =====================================================
        // KONFIGURASI UPLOAD
        // =====================================================

        $config = [

            'upload_path' =>
                $upload_path,

            'allowed_types' =>
                'jpg|jpeg|png|pdf',

            'max_size' =>
                5120,

            'encrypt_name' =>
                TRUE

        ];


        $this->upload->initialize(
            $config
        );


        // =====================================================
        // UPLOAD
        // =====================================================

        if (
            !$this->upload
                ->do_upload('file')
        ) {

            $error =
                strip_tags(
                    $this->upload
                        ->display_errors(
                            '',
                            ''
                        )
                );


            $this->session->set_flashdata(
                'error',
                $error
            );


            redirect(
                'SiswaBerkas'
            );

            return;
        }


        // =====================================================
        // DATA FILE
        // =====================================================

        $upload_data =
            $this->upload->data();


        $nama_file =
            $upload_data['file_name'];


        $nama_file_asli =
            $upload_data['client_name'];


        $tipe_file =
            $upload_data['file_type'];


        $ukuran_file =
            $upload_data['file_size'] * 1024;


        // =====================================================
        // CEK BERKAS LAMA
        // =====================================================

        $berkas_lama =
            $this->berkas->get_berkas(
                $siswa_id,
                $jenis
            );


        // =====================================================
        // GANTI BERKAS
        // =====================================================

        if ($berkas_lama) {

            $file_lama =
                $upload_path .
                $berkas_lama->nama_file;


            if (is_file($file_lama)) {

                @unlink($file_lama);

            }


            $update = [

                'nama_file' =>
                    $nama_file,

                'nama_file_asli' =>
                    $nama_file_asli,

                'tipe_file' =>
                    $tipe_file,

                'ukuran_file' =>
                    $ukuran_file,

                'updated_at' =>
                    date(
                        'Y-m-d H:i:s'
                    )

            ];


            $this->berkas->update(
                $berkas_lama->id,
                $update
            );


            $pesan =
                $jenis_berkas[$jenis] .
                ' berhasil diganti.';

        }


        // =====================================================
        // BERKAS BARU
        // =====================================================

        else {

            $insert = [

                'siswa_id' =>
                    $siswa_id,

                'jenis_berkas' =>
                    $jenis,

                'nama_file' =>
                    $nama_file,

                'nama_file_asli' =>
                    $nama_file_asli,

                'tipe_file' =>
                    $tipe_file,

                'ukuran_file' =>
                    $ukuran_file,

                'created_at' =>
                    date(
                        'Y-m-d H:i:s'
                    ),

                'updated_at' =>
                    date(
                        'Y-m-d H:i:s'
                    )

            ];


            $this->berkas->insert(
                $insert
            );


            $pesan =
                $jenis_berkas[$jenis] .
                ' berhasil diupload.';
        }


        // =====================================================
        // SUCCESS
        // =====================================================

        $this->session->set_flashdata(
            'success',
            $pesan
        );


        redirect(
            'SiswaBerkas'
        );
    }


    // =========================================================
    // LIHAT
    // =========================================================

    public function lihat($id)
    {
        $siswa_id =
            (int) $this->session->userdata('siswa_id');


        $id =
            (int) $id;


        if (!$siswa_id) {

            redirect('SiswaAuth');

            return;
        }


        /*
         * PENTING:
         * Ambil berkas berdasarkan
         * ID BERKAS + SISWA ID.
         *
         * Jadi siswa tidak bisa melihat
         * berkas siswa lain hanya dengan
         * mengganti ID URL.
         */

        $berkas =
            $this->berkas
                ->get_berkas_by_id_siswa(
                    $id,
                    $siswa_id
                );


        if (!$berkas) {

            show_404();

            return;
        }


        $file =
            FCPATH .
            'uploads/berkas_siswa/' .
            $siswa_id .
            '/' .
            $berkas->nama_file;


        if (!is_file($file)) {

            show_404();

            return;
        }


        $mime =
            function_exists('mime_content_type')
            ? mime_content_type($file)
            : $berkas->tipe_file;


        header(
            'Content-Type: ' .
            $mime
        );


        header(
            'Content-Length: ' .
            filesize($file)
        );


        header(
            'Content-Disposition: inline; filename="' .
            basename(
                $berkas->nama_file_asli
            ) .
            '"'
        );


        readfile($file);

        exit;
    }


    // =========================================================
    // DOWNLOAD
    // =========================================================

    public function download($id)
    {
        $siswa_id =
            (int) $this->session->userdata('siswa_id');


        $id =
            (int) $id;


        if (!$siswa_id) {

            redirect('SiswaAuth');

            return;
        }


        $berkas =
            $this->berkas
                ->get_berkas_by_id_siswa(
                    $id,
                    $siswa_id
                );


        if (!$berkas) {

            show_404();

            return;
        }


        $file =
            FCPATH .
            'uploads/berkas_siswa/' .
            $siswa_id .
            '/' .
            $berkas->nama_file;


        if (!is_file($file)) {

            show_404();

            return;
        }


        $this->load->helper(
            'download'
        );


        force_download(
            $berkas->nama_file_asli,
            file_get_contents($file)
        );
    }


    // =========================================================
    // HAPUS
    // =========================================================

    public function hapus($id)
    {
        $siswa_id =
            (int) $this->session->userdata('siswa_id');


        $id =
            (int) $id;


        if (!$siswa_id) {

            redirect('SiswaAuth');

            return;
        }


        $berkas =
            $this->berkas
                ->get_berkas_by_id_siswa(
                    $id,
                    $siswa_id
                );


        if (!$berkas) {

            show_404();

            return;
        }


        $file =
            FCPATH .
            'uploads/berkas_siswa/' .
            $siswa_id .
            '/' .
            $berkas->nama_file;


        if (is_file($file)) {

            @unlink($file);

        }


        $this->berkas->delete(
            $id
        );


        $this->session->set_flashdata(
            'success',
            'Berkas berhasil dihapus.'
        );


        redirect(
            'SiswaBerkas'
        );
    }
}