<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'libraries/phpqrcode/qrlib.php';

class Idcard_premium extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Idcard_premium_model', 'model');
        $this->load->helper('url');
    }

    /* ===============================
       HALAMAN LIST
       =============================== */
    public function index()
{
    $nama   = $this->input->get('nama');
    $kelas  = $this->input->get('kelas');

    $this->db->select('siswa.*, kelas.nama AS nama_kelas');
    $this->db->from('siswa');
    $this->db->join('kelas','kelas.id = siswa.id_kelas','left');

    if (!empty($nama)) {
        $this->db->like('siswa.nama', $nama);
    }

    if (!empty($kelas)) {
        $this->db->where('siswa.id_kelas', $kelas);
    }

    $this->db->order_by('siswa.nama','ASC');

    $data['siswa'] = $this->db->get()->result();
    $data['kelas_list'] = $this->db->get('kelas')->result();

    $data['title'] = "ID Card Premium";
    $data['active'] = 'idcard_premium';
    $data['group_data'] = true;

    
    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $data);
    $this->load->view('idcard_premium/index', $data);
    $this->load->view('templates/footer');
}


    /* ===============================
       PREVIEW CARD
       =============================== */
    public function preview($id)
    {
        $siswa = $this->model->getById($id);
        if (!$siswa) show_404();

        /* GENERATE QR */
        $qrFolder = FCPATH.'assets/qrcodes/';
        if (!is_dir($qrFolder)) {
            mkdir($qrFolder, 0755, true);
        }

        $qrFile = $qrFolder.$siswa->nisn.'.png';

        if (!file_exists($qrFile)) {
            QRcode::png(
                $siswa->nisn,
                $qrFile,
                QR_ECLEVEL_H,
                4,
                2
            );
        }

        $data['qr_path'] = 'assets/qrcodes/'.$siswa->nisn.'.png';
        $data['title'] = "Preview ID Card";
        $data['siswa'] = $siswa;

        $data['active'] = 'idcard_premium';
        $data['group_data'] = true;

        $this->load->view('idcard_premium/preview', $data);
    }
    public function cetak_kelas($kelas_id)
{
    $this->db->select('siswa.*, kelas.nama AS nama_kelas');
    $this->db->from('siswa');
    $this->db->join('kelas','kelas.id = siswa.id_kelas','left');
    $this->db->where('siswa.id_kelas', $kelas_id);
    $this->db->order_by('siswa.nama','ASC');

    $siswa = $this->db->get()->result();

    if (empty($siswa)) show_404();

    $data['siswa'] = $siswa;
    $data['title'] = "Cetak Massal ID Card";

    $this->load->view('idcard_premium/cetak_massal', $data);
}

}
