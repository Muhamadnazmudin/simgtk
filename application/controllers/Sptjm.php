<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sptjm extends CI_Controller
{
    private $upload_path;

    public function __construct()
    {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | VALIDASI LOGIN
        |--------------------------------------------------------------------------
        */

        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI ADMIN
        |--------------------------------------------------------------------------
        */

        if ($this->session->userdata('role_name') !== 'admin') {
            show_error(
                'Anda tidak memiliki akses ke halaman ini.',
                403,
                'Akses Ditolak'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD MODEL
        |--------------------------------------------------------------------------
        */

        $this->load->model('Sptjm_model');


        /*
        |--------------------------------------------------------------------------
        | LOAD HELPER
        |--------------------------------------------------------------------------
        */

        $this->load->helper([
            'url',
            'form',
            'file'
        ]);


        /*
        |--------------------------------------------------------------------------
        | FOLDER UPLOAD UTAMA
        |--------------------------------------------------------------------------
        */

        $this->upload_path = FCPATH . 'uploads/sptjm/';


        /*
        |--------------------------------------------------------------------------
        | BUAT FOLDER JIKA BELUM ADA
        |--------------------------------------------------------------------------
        */

        if (!is_dir($this->upload_path)) {
            @mkdir($this->upload_path, 0755, true);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPLOAD
    |--------------------------------------------------------------------------
    */

    public function upload()
    {
        $data = [
            'title'  => 'Upload SPTJM Dapodik',
            'active' => 'sptjm_upload'
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('sptjm/upload', $data);
        $this->load->view('templates/footer', $data);
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN
    |--------------------------------------------------------------------------
    */

    public function simpan()
    {
        if ($this->input->method() !== 'post') {
            redirect('sptjm/upload');
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL INPUT
        |--------------------------------------------------------------------------
        */

        $tahun_ajaran = trim(
            $this->input->post('tahun_ajaran', true)
        );

        $jenis_sptjm = trim(
            $this->input->post('jenis_sptjm', true)
        );

        $tanggal_cut_off = trim(
            $this->input->post('tanggal_cut_off', true)
        );

        $tanggal_sptjm = trim(
            $this->input->post('tanggal_sptjm', true)
        );

        $keterangan = trim(
            $this->input->post('keterangan', true)
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI INPUT
        |--------------------------------------------------------------------------
        */

        if ($tahun_ajaran === '') {

            $this->session->set_flashdata(
                'error',
                'Tahun ajaran wajib dipilih.'
            );

            redirect('sptjm/upload');
            return;
        }


        $jenis_valid = [
            'cut_off',
            'khusus'
        ];

        if (!in_array($jenis_sptjm, $jenis_valid, true)) {

            $this->session->set_flashdata(
                'error',
                'Jenis SPTJM tidak valid.'
            );

            redirect('sptjm/upload');
            return;
        }


        if ($tanggal_cut_off === '') {

            $this->session->set_flashdata(
                'error',
                'Tanggal cut off wajib diisi.'
            );

            redirect('sptjm/upload');
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKAT
        |--------------------------------------------------------------------------
        */

        if ($this->Sptjm_model->exists(
            $tahun_ajaran,
            $jenis_sptjm
        )) {

            $label_jenis = ($jenis_sptjm === 'cut_off')
                ? 'Cut Off 31 Agustus'
                : 'SPTJM Khusus';

            $this->session->set_flashdata(
                'error',
                'SPTJM ' . $label_jenis .
                ' untuk tahun ajaran ' .
                $tahun_ajaran .
                ' sudah tersedia.'
            );

            redirect('sptjm/upload');
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI FILE
        |--------------------------------------------------------------------------
        */

        if (
            empty($_FILES['file_sptjm']['name']) ||
            empty($_FILES['file_excel']['name'])
        ) {

            $this->session->set_flashdata(
                'error',
                'File PDF SPTJM dan file Excel wajib diupload.'
            );

            redirect('sptjm/upload');
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | FOLDER TAHUN
        |--------------------------------------------------------------------------
        */

        $tahun_folder = str_replace(
            '/',
            '-',
            $tahun_ajaran
        );

        $jenis_folder = $jenis_sptjm;


        $folder = $this->upload_path .
            $tahun_folder .
            DIRECTORY_SEPARATOR .
            $jenis_folder .
            DIRECTORY_SEPARATOR;


        if (!is_dir($folder)) {
            @mkdir($folder, 0755, true);
        }


        /*
        |--------------------------------------------------------------------------
        | UPLOAD PDF
        |--------------------------------------------------------------------------
        */

        $config_pdf = [
            'upload_path'      => $folder,
            'allowed_types'    => 'pdf',
            'max_size'         => 10240,
            'encrypt_name'     => true,
            'remove_spaces'    => true
        ];

        $this->load->library(
            'upload',
            $config_pdf
        );


        if (!$this->upload->do_upload('file_sptjm')) {

            $error = strip_tags(
                $this->upload->display_errors('', '')
            );

            $this->session->set_flashdata(
                'error',
                'Upload PDF gagal: ' . $error
            );

            redirect('sptjm/upload');
            return;
        }


        $pdf_data = $this->upload->data();


        /*
        |--------------------------------------------------------------------------
        | UPLOAD EXCEL
        |--------------------------------------------------------------------------
        */

        $config_excel = [
            'upload_path'      => $folder,
            'allowed_types'    => 'xls|xlsx',
            'max_size'         => 20480,
            'encrypt_name'     => true,
            'remove_spaces'    => true
        ];

        $this->upload->initialize(
            $config_excel,
            true
        );


        if (!$this->upload->do_upload('file_excel')) {

            /*
            |--------------------------------------------------------------------------
            | HAPUS PDF JIKA EXCEL GAGAL
            |--------------------------------------------------------------------------
            */

            if (
                !empty($pdf_data['full_path']) &&
                file_exists($pdf_data['full_path'])
            ) {
                @unlink($pdf_data['full_path']);
            }


            $error = strip_tags(
                $this->upload->display_errors('', '')
            );

            $this->session->set_flashdata(
                'error',
                'Upload Excel gagal: ' . $error
            );

            redirect('sptjm/upload');
            return;
        }


        $excel_data = $this->upload->data();


        /*
        |--------------------------------------------------------------------------
        | DATA DATABASE
        |--------------------------------------------------------------------------
        */

        $data_insert = [

            'tahun_ajaran'    => $tahun_ajaran,

            'jenis_sptjm'     => $jenis_sptjm,

            'tanggal_cut_off' => $tanggal_cut_off,

            'tanggal_sptjm'   =>
                ($tanggal_sptjm !== '')
                    ? $tanggal_sptjm
                    : null,

            'nama_file_sptjm' =>
                $pdf_data['orig_name'],

            'file_sptjm' =>
                $pdf_data['file_name'],

            'nama_file_excel' =>
                $excel_data['orig_name'],

            'file_excel' =>
                $excel_data['file_name'],

            'keterangan' =>
                ($keterangan !== '')
                    ? $keterangan
                    : null,

            'uploaded_by' =>
                (int) $this->session->userdata('user_id'),

            'uploaded_at' =>
                date('Y-m-d H:i:s')
        ];


        /*
        |--------------------------------------------------------------------------
        | INSERT DATABASE
        |--------------------------------------------------------------------------
        */

        $insert = $this->Sptjm_model->insert(
            $data_insert
        );


        if (!$insert) {

            /*
            |--------------------------------------------------------------------------
            | HAPUS FILE JIKA DATABASE GAGAL
            |--------------------------------------------------------------------------
            */

            if (
                !empty($pdf_data['full_path']) &&
                file_exists($pdf_data['full_path'])
            ) {
                @unlink($pdf_data['full_path']);
            }

            if (
                !empty($excel_data['full_path']) &&
                file_exists($excel_data['full_path'])
            ) {
                @unlink($excel_data['full_path']);
            }


            $this->session->set_flashdata(
                'error',
                'Data SPTJM gagal disimpan ke database.'
            );

            redirect('sptjm/upload');
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | BERHASIL
        |--------------------------------------------------------------------------
        */

        $this->session->set_flashdata(
            'success',
            'SPTJM berhasil diupload.'
        );

        redirect('sptjm/riwayat');
    }


    /*
    |--------------------------------------------------------------------------
    | RIWAYAT
    |--------------------------------------------------------------------------
    */

    public function riwayat()
    {
        $tahun_ajaran = trim(
            $this->input->get('tahun_ajaran', true)
        );

        $jenis_sptjm = trim(
            $this->input->get('jenis_sptjm', true)
        );


        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        $data = [

            'title' => 'Riwayat SPTJM Dapodik',

            'active' => 'sptjm_riwayat',

            'tahun_ajaran' => $tahun_ajaran,

            'jenis_sptjm' => $jenis_sptjm,

            'tahun_list' =>
                $this->Sptjm_model->get_tahun_ajaran(),

            'rows' =>
                $this->Sptjm_model->filter(
                    $tahun_ajaran ?: null,
                    $jenis_sptjm ?: null
                )
        ];


        $this->load->view(
            'templates/header',
            $data
        );

        $this->load->view(
            'templates/sidebar',
            $data
        );

        $this->load->view(
            'sptjm/riwayat',
            $data
        );

        $this->load->view(
            'templates/footer',
            $data
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD PDF
    |--------------------------------------------------------------------------
    */

    public function download_pdf($id)
    {
        $row = $this->Sptjm_model->get_by_id($id);

        if (!$row || empty($row->file_sptjm)) {
            show_404();
            return;
        }


        $tahun_folder = str_replace(
            '/',
            '-',
            $row->tahun_ajaran
        );


        $path = $this->upload_path .
            $tahun_folder .
            DIRECTORY_SEPARATOR .
            $row->jenis_sptjm .
            DIRECTORY_SEPARATOR .
            $row->file_sptjm;


        if (!file_exists($path)) {
            show_404();
            return;
        }


        $this->_download_file(
            $path,
            $row->nama_file_sptjm
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD EXCEL
    |--------------------------------------------------------------------------
    */

    public function download_excel($id)
    {
        $row = $this->Sptjm_model->get_by_id($id);

        if (!$row || empty($row->file_excel)) {
            show_404();
            return;
        }


        $tahun_folder = str_replace(
            '/',
            '-',
            $row->tahun_ajaran
        );


        $path = $this->upload_path .
            $tahun_folder .
            DIRECTORY_SEPARATOR .
            $row->jenis_sptjm .
            DIRECTORY_SEPARATOR .
            $row->file_excel;


        if (!file_exists($path)) {
            show_404();
            return;
        }


        $this->_download_file(
            $path,
            $row->nama_file_excel
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function hapus($id)
    {
        $row = $this->Sptjm_model->get_by_id($id);

        if (!$row) {
            show_404();
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS FILE PDF
        |--------------------------------------------------------------------------
        */

        $tahun_folder = str_replace(
            '/',
            '-',
            $row->tahun_ajaran
        );


        $folder = $this->upload_path .
            $tahun_folder .
            DIRECTORY_SEPARATOR .
            $row->jenis_sptjm .
            DIRECTORY_SEPARATOR;


        if (!empty($row->file_sptjm)) {

            $pdf_path = $folder .
                $row->file_sptjm;

            if (file_exists($pdf_path)) {
                @unlink($pdf_path);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS FILE EXCEL
        |--------------------------------------------------------------------------
        */

        if (!empty($row->file_excel)) {

            $excel_path = $folder .
                $row->file_excel;

            if (file_exists($excel_path)) {
                @unlink($excel_path);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS DATABASE
        |--------------------------------------------------------------------------
        */

        $delete = $this->Sptjm_model->delete($id);


        if ($delete) {

            $this->session->set_flashdata(
                'success',
                'Data SPTJM berhasil dihapus.'
            );

        } else {

            $this->session->set_flashdata(
                'error',
                'Data SPTJM gagal dihapus.'
            );
        }


        redirect('sptjm/riwayat');
    }


    /*
    |--------------------------------------------------------------------------
    | PRIVATE DOWNLOAD
    |--------------------------------------------------------------------------
    */

    private function _download_file($path, $download_name)
    {
        if (!file_exists($path)) {
            show_404();
            return;
        }


        $this->output
            ->set_content_type(
                mime_content_type($path)
            )
            ->set_header(
                'Content-Disposition: attachment; filename="' .
                basename($download_name) .
                '"'
            )
            ->set_header(
                'Content-Length: ' . filesize($path)
            )
            ->set_output(
                file_get_contents($path)
            );
    }
}