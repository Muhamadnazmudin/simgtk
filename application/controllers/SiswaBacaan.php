<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SiswaBacaan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // Proteksi login siswa
        if (!$this->session->userdata('siswa_id')) {
            redirect('SiswaAuth');
        }

        // Load model (nanti kita buat)
        $this->load->model('Bacaan_model');
    }

    public function index()
{
    $this->load->library('pagination');

    // ambil filter dari GET
    $kelas = $this->input->get('kelas');
    $mapel = $this->input->get('mapel');

    $limit  = 10;
    $offset = $this->input->get('page') ?? 0;

    // total data sesuai filter
    $total = $this->Bacaan_model->count_filtered($kelas, $mapel);

    // pagination
    $config['base_url'] = site_url('SiswaBacaan') . '?' . http_build_query([
        'kelas' => $kelas,
        'mapel' => $mapel
    ]);

    $config['total_rows'] = $total;
    $config['per_page']   = $limit;
    $config['page_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';

    // bootstrap
    $config['full_tag_open'] = '<nav><ul class="pagination pagination-sm justify-content-center">';
    $config['full_tag_close'] = '</ul></nav>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close'] = '</span></li>';
    $config['attributes'] = ['class' => 'page-link'];
    $config['prev_link'] = '&laquo;';
    $config['next_link'] = '&raquo;';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';

    $this->pagination->initialize($config);

    $data['buku'] = $this->Bacaan_model->get_filtered($limit, $offset, $kelas, $mapel);
    $data['pagination'] = $this->pagination->create_links();

    // kirim filter ke view (biar selected)
    $data['filter_kelas'] = $kelas;
    $data['filter_mapel'] = $mapel;

    // dropdown mapel
    $data['mapel_list'] = $this->Bacaan_model->get_mapel_unik();

    $data['active'] = 'bacaan';

    $this->load->view('siswa/layout/header', $data);
    $this->load->view('siswa/layout/sidebar', $data);
    $this->load->view('siswa/bacaan/index', $data);
    $this->load->view('siswa/layout/footer');
}


public function baca($id)
{
    // Ambil data buku
    $buku = $this->Bacaan_model->get_by_id($id);

    if (!$buku) {
        show_404();
    }

    $data['buku']   = $buku;
    $data['active'] = 'bacaan';

    $this->load->view('siswa/layout/header', $data);
    $this->load->view('siswa/layout/sidebar', $data);
    $this->load->view('siswa/bacaan/baca', $data);
    $this->load->view('siswa/layout/footer');
}

}
