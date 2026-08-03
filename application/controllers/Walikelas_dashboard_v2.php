<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Walikelas_dashboard_v2 extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $guru_id = $this->session->userdata('guru_id');

        if (!$guru_id) {
            show_error('Akses ditolak.',403);
        }

        $kelas = $this->db
            ->get_where('kelas',[
                'wali_kelas_id'=>$guru_id
            ])
            ->row();

        if(!$kelas){
            show_error('Anda bukan wali kelas.',403);
        }

        $this->session->set_userdata([
            'kelas_id'=>$kelas->id,
            'kelas_nama'=>$kelas->nama
        ]);

        $this->load->model('Walikelas_dashboard_v2_model');
    }

    public function index()
    {

        $kelas_id   = $this->session->userdata('kelas_id');
        $kelas_nama = $this->session->userdata('kelas_nama');

        /* ===============================
           DATA KELAS
        =============================== */

        $kelas = $this->Walikelas_dashboard_v2_model
                        ->getKelas($kelas_id);

        /* ===============================
           TOTAL SISWA
        =============================== */

        $siswa = $this->Walikelas_dashboard_v2_model
                        ->getTotalSiswa($kelas_id);

        /* ===============================
           REKAP HARI INI
        =============================== */

        $hari = $this->Walikelas_dashboard_v2_model
                        ->getHariIni($kelas_id);

        /* ===============================
           REKAP BULAN
        =============================== */

        $bulan = $this->Walikelas_dashboard_v2_model
                        ->getBulanIni($kelas_id);

        /* ===============================
           SISWA TIDAK HADIR
        =============================== */

        $tidak_hadir = $this->Walikelas_dashboard_v2_model
                            ->getSiswaTidakHadir($kelas_id);

        /* ===============================
           TOP ALPA
        =============================== */

        $top_alpha = $this->Walikelas_dashboard_v2_model
                            ->getTopAlpha($kelas_id);

        /* ===============================
           CHART
        =============================== */

        $chart = $this->Walikelas_dashboard_v2_model
                        ->chart7Hari($kelas_id);

        /* ===============================
           IZIN
        =============================== */

        $izin = $this->Walikelas_dashboard_v2_model
                        ->getIzinHariIni($kelas_id);

        /* ===============================
           TAHUN AJARAN
        =============================== */

        $tahun = $this->db
            ->get_where('tahun_ajaran',[
                'id'=>$this->session->userdata('tahun_id')
            ])
            ->row();

        /* ===============================
           PERSENTASE HADIR
        =============================== */

        $persen = 0;

        if($siswa['total']>0){

            $persen = round(
                ($hari['H']/$siswa['total'])*100
            );

        }

        /* ===============================
           KIRIM KE VIEW
        =============================== */

        $data = [];

        $data['title']  = 'Dashboard Wali Kelas';
        $data['active'] = 'dashboard_v2';

        $data['kelas']      = $kelas;
        $data['kelas_nama'] = $kelas_nama;
        $data['tahun']      = $tahun;

        $data['total_siswa'] = $siswa['total'];
        $data['laki']        = $siswa['l'];
        $data['perempuan']   = $siswa['p'];

        $data['hari_H'] = $hari['H'];
        $data['hari_I'] = $hari['I'];
        $data['hari_S'] = $hari['S'];
        $data['hari_A'] = $hari['A'];

        $data['bulan_H'] = $bulan['H'];
        $data['bulan_I'] = $bulan['I'];
        $data['bulan_S'] = $bulan['S'];
        $data['bulan_A'] = $bulan['A'];
$data['tidak_hadir'] = $this->Walikelas_dashboard_v2_model->getSiswaTidakHadir($kelas_id);

$data['top_alpha'] = $this->Walikelas_dashboard_v2_model->getTopAlpha($kelas_id);

        $data['izin_keluar_hari_ini'] = $izin['keluar'];
        $data['izin_pulang_hari_ini'] = $izin['pulang'];

        $data['persen_hadir'] = $persen;

        $data['chart'] = $chart;

        $data['top_alpha'] = $top_alpha;

        // nama variabel tetap supaya view lama tidak perlu diubah
        $data['belum_absen'] = $tidak_hadir;

        /* ===============================
           LOAD VIEW
        =============================== */

        $this->load->view(
            'walikelas/templates/header',
            $data
        );

        $this->load->view(
            'walikelas/templates/sidebar',
            $data
        );

        $this->load->view(
            'walikelas/dashboard_v2',
            $data
        );

        $this->load->view(
            'walikelas/templates/footer'
        );

    }

}