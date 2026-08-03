<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bpbk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // ===============================
        // LOGIN
        // ===============================
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        // ===============================
        // HANYA ROLE BP/BK
        // ===============================
        if ($this->session->userdata('role_id') != 6) {
            redirect('dashboard');
        }

        $this->load->model('Dashboard_bpbk_model');
        $this->load->model('Tahun_model');
    }

    public function index()
    {
        // =====================================================
        // AMBIL DATA DASHBOARD
        // =====================================================

        $summary      = $this->Dashboard_bpbk_model->getSummary();
        $hari         = $this->Dashboard_bpbk_model->getHariIni();
        $bulan        = $this->Dashboard_bpbk_model->getBulanIni();
        $tidak_hadir  = $this->Dashboard_bpbk_model->getTidakHadirHariIni();
        $top_alpha    = $this->Dashboard_bpbk_model->getTopAlpa();

        // =====================================================
        // DATA VIEW
        // =====================================================

        $data = [];

        $data['judul']  = 'Dashboard BP/BK';
        $data['active'] = 'dashboard';

        // Tahun aktif
        $data['tahun'] = $this->Tahun_model->get_aktif();

        // ===============================
        // SUMMARY
        // ===============================

        $data['jumlah_siswa'] = $summary['jumlah_siswa'];
        $data['jumlah_guru']  = $summary['jumlah_guru'];
        $data['jumlah_kelas'] = $summary['jumlah_kelas'];

        // ===============================
        // HARI INI
        // ===============================

        $data['hari_H'] = $hari['H'];
        $data['hari_I'] = $hari['I'];
        $data['hari_S'] = $hari['S'];
        $data['hari_A'] = $hari['A'];

        $data['tidak_hadir_hari_ini'] = $hari['T'];

        // ===============================
        // BULAN INI
        // ===============================

        $data['bulan_H'] = $bulan['H'];
        $data['bulan_I'] = $bulan['I'];
        $data['bulan_S'] = $bulan['S'];
        $data['bulan_A'] = $bulan['A'];

        $data['persentase_hadir'] = $bulan['PERSEN'];

        // ===============================
        // TABEL
        // ===============================

        $data['tidak_hadir'] = $tidak_hadir;
        $data['top_alpha']   = $top_alpha;

        // =====================================================
        // VIEW
        // =====================================================

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('bpbk/dashboard', $data);
        $this->load->view('templates/footer');
    }
}