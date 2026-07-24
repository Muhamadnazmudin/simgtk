<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_keluar extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('username')) {
            redirect('auth');
        }

        $this->load->model('Siswa_keluar_model', 'model');
    }

    public function index()
    {
        $data = [
            'active' => 'siswa_keluar',
            'title'  => 'Data Siswa Keluar',
            'siswa'  => $this->model->getData()
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('siswa/keluar', $data);
        $this->load->view('templates/footer');
    }
}