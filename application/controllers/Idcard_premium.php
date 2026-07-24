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
    $filter = [
        'nama'  => trim($this->input->get('nama')),
        'kelas' => $this->input->get('kelas')
    ];

    $data = [
        'title'       => 'ID Card Premium',
        'active'      => 'idcard_premium',
        'group_data'  => true,
        'siswa'       => $this->model->getList(
                            $filter['nama'],
                            $filter['kelas']
                        ),
        'kelas_list'  => $this->model->getKelas()
    ];

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $data);
    $this->load->view('idcard_premium/index', $data);
    $this->load->view('templates/footer');
}


    /* ===============================
       PREVIEW CARD
       =============================== */
    public function preview($id = null)
{
    if (!$id) {
        show_404();
    }

    $siswa = $this->model->getById($id);

    if (!$siswa) {
        show_404();
    }

    $qr_path = $this->_generateQr($siswa->nisn);

    $data = [
        'title'      => 'Preview ID Card',
        'active'     => 'idcard_premium',
        'group_data' => true,
        'siswa'      => $siswa,
        'qr_path'    => $qr_path
    ];

    $this->load->view('idcard_premium/preview', $data);
}
    public function cetak_kelas($kelas_id = null)
{
    if (!$kelas_id) {
        show_404();
    }

    $siswa = $this->model->getByKelas($kelas_id);

    if (empty($siswa)) {
        show_404();
    }

    $data = [
        'title' => 'Cetak Massal ID Card',
        'siswa' => $siswa
    ];

    $this->load->view(
        'idcard_premium/cetak_massal',
        $data
    );
}
private function _generateQr($text)
{
    $folder = FCPATH . 'assets/qrcodes/';

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    $file = $folder . $text . '.png';

    if (!file_exists($file)) {

        QRcode::png(
            $text,
            $file,
            QR_ECLEVEL_H,
            4,
            2
        );

    }

    return 'assets/qrcodes/' . $text . '.png';
}
}
