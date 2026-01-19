<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SiswaTutorial extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // pastikan siswa login
        if (!$this->session->userdata('siswa_nisn')) {
            redirect('SiswaAuth');
        }

        $this->load->model('Tutorial_model');
    }

    public function index()
    {
        $data['active']   = 'tutorial';
        $data['title']    = 'Kumpulan Video Tutorial';
        $data['tutorial'] = $this->Tutorial_model->get_all();

        $this->load->view('siswa/layout/header', $data);
        $this->load->view('siswa/layout/sidebar', $data);
        $this->load->view('siswa/tutorial/index', $data);
        $this->load->view('siswa/layout/footer');
    }

    public function lihat($id)
    {
        $data['active'] = 'tutorial';
        $data['row']    = $this->Tutorial_model->get_by_id($id);

        if (!$data['row']) show_404();

        $data['title'] = $data['row']->judul;

        $this->load->view('siswa/layout/header', $data);
        $this->load->view('siswa/layout/sidebar', $data);
        $this->load->view('siswa/tutorial/lihat', $data);
        $this->load->view('siswa/layout/footer');
    }
}
