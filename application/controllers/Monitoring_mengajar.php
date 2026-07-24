<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_mengajar extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if (
            !$this->session->userdata('logged_in') ||
            $this->session->userdata('role_name') !== 'admin'
        ) {
            redirect('auth/login');
        }

        $this->load->model('Monitoring_mengajar_model');
    }

    public function index()
    {
        $data['title']  = 'Monitoring Mengajar Guru';
        $data['active'] = 'monitoring_mengajar';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('monitoring_mengajar/index', $data);
        $this->load->view('templates/footer');
    }

    public function load_data()
{
    $guru_id  = $this->input->get('guru_id');
    $kelas_id = $this->input->get('kelas_id');

    $rows = $this->Monitoring_mengajar_model
        ->getMonitoring($guru_id, $kelas_id);

    $result = [];

    foreach ($rows as $r) {

        if ($r->log_mulai && !$r->log_selesai) {

            $status = 'sedang';

        } elseif ($r->log_selesai) {

            $status = 'selesai';

        } else {

            $status = 'belum';

        }

        $result[] = [

            'guru' => $r->nama_guru,

            'kelas' => $r->nama_kelas,

            'mapel' => $r->nama_mapel,

            'jam_awal' => $r->jam_awal,

            'jam_akhir' => $r->jam_akhir,

            'jam_mulai' => $r->jam_mulai
                ? substr($r->jam_mulai,0,5)
                : '-',

            'jam_selesai' => $r->jam_selesai
                ? substr($r->jam_selesai,0,5)
                : '-',

            'materi' => $r->materi ?: '-',

            'selfie' => $r->selfie
                ? base_url('uploads/selfie/'.$r->selfie)
                : '',

            'status' => $status

        ];
    }

    echo json_encode($result);
}

public function filter_data()
{
    echo json_encode([

        'guru'  => $this->Monitoring_mengajar_model->getGuru(),

        'kelas' => $this->Monitoring_mengajar_model->getKelas()

    ]);
}


}
