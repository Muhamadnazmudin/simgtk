<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buku_induk extends CI_Controller
{

    public function __construct()
{
    parent::__construct();

    $this->load->library('session');
    $this->load->database();

    $this->load->model('Buku_induk_model');
    $this->load->library('pagination');

    // Tahun aktif
    $session_tahun = $this->session->userdata('tahun_id');

    if ($session_tahun) {
        $this->tahun_id = $session_tahun;
    } else {
        $t = $this->db
            ->get_where('tahun_ajaran', ['aktif' => 1])
            ->row();

        $this->tahun_id = $t ? $t->id : null;
    }
}

    /*
    |--------------------------------------------------------------------------
    | LIST BUKU INDUK
    |--------------------------------------------------------------------------
    */

    public function index($offset = 0)
    {

    if (!$this->session->userdata('logged_in')) {
    redirect('auth');
}

$role = $this->session->userdata('role_name');

if (!in_array($role, ['admin', 'kesiswaan'])) {
    show_error('Akses ditolak', 403);
}
        $limit = (int)$this->input->get('limit');

        if ($limit <= 0) {
            $limit = 20;
        }

        $filter = [
            'kelas'  => $this->input->get('kelas', TRUE),
            'search' => trim($this->input->get('search', TRUE))
        ];

        /*
        |--------------------------------------------------------------------------
        | TOTAL DATA
        |--------------------------------------------------------------------------
        */

        $total_rows = $this->Buku_induk_model->countAll($filter);

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $config['base_url'] = site_url('buku_induk/index');

        $config['total_rows'] = $total_rows;

        $config['per_page'] = $limit;

        $config['uri_segment'] = 3;

        $config['reuse_query_string'] = TRUE;

        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-end">';
        $config['full_tag_close'] = '</ul></nav>';

        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';

        $config['attributes'] = [
            'class' => 'page-link'
        ];

        $config['first_link'] = '&laquo;';
        $config['last_link']  = '&raquo;';
        $config['next_link']  = '&rsaquo;';
        $config['prev_link']  = '&lsaquo;';

        $this->pagination->initialize($config);

        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $data['title'] = 'Buku Induk';

        $data['active'] = 'buku_induk';

        $data['filter'] = $filter;

        $data['limit'] = $limit;

        $data['offset'] = $offset;

        $data['total_rows'] = $total_rows;

        $data['kelas'] = $this->Buku_induk_model->getKelas();

        $data['siswa'] = $this->Buku_induk_model->getData(
            $limit,
            $offset,
            $filter
        );

        $data['pagination'] = $this->pagination->create_links();

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('buku_induk/index', $data);
        $this->load->view('templates/footer');
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL BUKU INDUK
    |--------------------------------------------------------------------------
    */

    public function detail($id)
{
    $data['title'] = 'Buku Induk';

    $data['siswa'] = $this->Buku_induk_model->getById($id);

    if (!$data['siswa']) {
        show_404();
    }

    // ==========================
    // rapor didieu
    // ==========================
    $this->load->model('Nilai_rapor_model');

$data['rapor'] = $this->Nilai_rapor_model
    ->rekap_by_siswa_kelas($id);

    // ==========================

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('buku_induk/detail', $data);
    $this->load->view('templates/footer');
}

}