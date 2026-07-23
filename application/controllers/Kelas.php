<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Kelas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Kelas_model');
        $this->load->library(['form_validation', 'pagination']);
        $this->load->helper(['url', 'form']);

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
            exit;
        }
    }

    /* =====================================================
     * INDEX
     * ===================================================== */
    public function index($offset = 0)
    {
        // Pagination
        $config['base_url'] = site_url('kelas/index');
        $config['total_rows'] = $this->Kelas_model->count_all();
        $config['per_page'] = 15;
        $config['uri_segment'] = 3;

        $config['full_tag_open']   = '<nav><ul class="pagination pagination-sm justify-content-center">';
        $config['full_tag_close']  = '</ul></nav>';
        $config['attributes']      = ['class' => 'page-link'];
        $config['first_tag_open']  = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open']   = '<li class="page-item">';
        $config['last_tag_close']  = '</li>';
        $config['next_tag_open']   = '<li class="page-item">';
        $config['next_tag_close']  = '</li>';
        $config['prev_tag_open']   = '<li class="page-item">';
        $config['prev_tag_close']  = '</li>';
        $config['cur_tag_open']    = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close']   = '</a></li>';
        $config['num_tag_open']    = '<li class="page-item">';
        $config['num_tag_close']   = '</li>';

        $this->pagination->initialize($config);

        // Data kelas (JOIN wali + ketua)
        $kelas = $this->Kelas_model->get_all($config['per_page'], $offset);

        // Hitung jumlah siswa aktif
        foreach ($kelas as &$k) {
            $k->jumlah_siswa = $this->db
                ->where('id_kelas', $k->id)
                ->where('status', 'aktif')
                ->count_all_results('siswa');
        }

        $data = [
            'title'      => 'Data Kelas',
            'active'     => 'kelas',
            'kelas'      => $kelas,
            'pagination' => $this->pagination->create_links(),
            'guru'       => $this->Kelas_model->get_guru_list(),
            'siswa'      => $this->db->order_by('nama', 'ASC')->get('siswa')->result(),
            'start'      => $offset
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('kelas/index', $data);
        $this->load->view('templates/footer');
    }

    /* =====================================================
     * ADD
     * ===================================================== */
    public function add()
    {
        if (!$this->input->post()) {
            redirect('kelas');
        }

        $wali_kelas_id  = $this->input->post('wali_kelas_id', TRUE);
        $ketua_kelas_id = $this->input->post('ketua_kelas_id', TRUE);

        // Validasi wali kelas tidak dobel
        if ($wali_kelas_id) {
            $cek = $this->db->get_where('kelas', ['wali_kelas_id' => $wali_kelas_id])->row();
            if ($cek) {
                $this->session->set_flashdata(
                    'error',
                    'Guru sudah menjadi wali kelas di ' . $cek->nama
                );
                redirect('kelas');
                return;
            }
        }

        $data = [
    'nama'           => $this->input->post('nama', TRUE),
    'wali_kelas_id'  => $wali_kelas_id ?: NULL,
    'jurusan'        => $this->input->post('jurusan', TRUE),
    'ketua_kelas_id' => $ketua_kelas_id ?: NULL,
    'kapasitas'      => $this->input->post('kapasitas', TRUE),
    'is_active'      => 1
];

        $this->Kelas_model->insert($data);

        $this->session->set_flashdata('success', 'Data kelas berhasil ditambahkan.');
        redirect('kelas');
    }

    /* =====================================================
     * EDIT
     * ===================================================== */
    public function edit($id)
{
    $kelas = $this->Kelas_model->get_by_id($id);
    if (!$kelas) redirect('kelas');

    if ($this->input->post()) {

        $wali_kelas_id  = $this->input->post('wali_kelas_id', TRUE);
        $ketua_kelas_id = $this->input->post('ketua_kelas_id', TRUE);

        // Validasi wali kelas tidak dobel
        if ($wali_kelas_id) {
            $cek = $this->db->get_where('kelas', ['wali_kelas_id' => $wali_kelas_id])->row();
            if ($cek && $cek->id != $id) {
                $this->session->set_flashdata(
                    'error',
                    'Guru sudah menjadi wali kelas di '.$cek->nama
                );
                redirect('kelas/edit/'.$id);
                return;
            }
        }

        $update = [
            'nama'           => $this->input->post('nama', TRUE),
            'jurusan'        => $this->input->post('jurusan', TRUE),
            'wali_kelas_id'  => $wali_kelas_id ?: NULL,
            'ketua_kelas_id' => $ketua_kelas_id ?: NULL,
            'kapasitas'      => $this->input->post('kapasitas', TRUE)
        ];

        $this->Kelas_model->update($id, $update);
        $this->session->set_flashdata('success', 'Data kelas berhasil diperbarui.');
        redirect('kelas');
    }

    $data = [
        'title'  => 'Edit Kelas',
        'active' => 'kelas',
        'kelas'  => $kelas,

        // wali kelas: aman + terpilih
        'guru'   => $this->Kelas_model->get_guru_list_edit($id),

        // 🔥 siswa HANYA dari kelas ini + ketua lama
        'siswa'  => $this->Kelas_model->get_siswa_by_kelas(
            $kelas->id,
            $kelas->ketua_kelas_id
        )
    ];

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $data);
    $this->load->view('kelas/edit', $data);
    $this->load->view('templates/footer');
}


    /* =====================================================
 * NONAKTIFKAN KELAS
 * ===================================================== */
public function nonaktif($id)
{
    $jumlah = $this->db
        ->where('id_kelas', $id)
        ->where('status', 'aktif')
        ->count_all_results('siswa');

    if ($jumlah > 0) {

        $this->session->set_flashdata(
            'error',
            'Kelas tidak dapat dinonaktifkan karena masih memiliki <b>'.$jumlah.'</b> siswa aktif-Moal Baleg.'
        );

        redirect('kelas');
        return;
    }

    $this->Kelas_model->update($id, [
        'is_active' => 0
    ]);

    $this->session->set_flashdata(
        'success',
        'Kelas berhasil dinonaktifkan.'
    );

    redirect('kelas');
}
/* =====================================================
 * AKTIFKAN KELAS
 * ===================================================== */
public function aktif($id)
{
    $this->Kelas_model->update($id, [
        'is_active' => 1
    ]);

    $this->session->set_flashdata(
        'success',
        'Kelas berhasil diaktifkan kembali.'
    );

    redirect('kelas');
}

    /* =====================================================
     * EXPORT EXCEL
     * ===================================================== */
    public function export_excel()
    {
        $data = $this->Kelas_model->get_all(10000, 0);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Kelas');
        $sheet->setCellValue('C1', 'Jurusan');
        $sheet->setCellValue('D1', 'Wali Kelas');
        $sheet->setCellValue('E1', 'Ketua Kelas');
        $sheet->setCellValue('F1', 'Kapasitas');

        $row = 2;
        $no  = 1;

        foreach ($data as $k) {
            $sheet->setCellValue("A$row", $no++);
            $sheet->setCellValue("B$row", $k->nama);
            $sheet->setCellValue("C$row", $k->jurusan);
            $sheet->setCellValue("D$row", $k->wali_nama);
            $sheet->setCellValue("E$row", $k->ketua_nama);
            $sheet->setCellValueExplicit(
                "F$row",
                (string)$k->kapasitas,
                DataType::TYPE_STRING
            );
            $row++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="data_kelas.xls"');

        $writer = new Xls($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /* =====================================================
     * IMPORT EXCEL
     * ===================================================== */
    public function import_excel()
    {
        if (empty($_FILES['file']['name'])) {
            redirect('kelas');
        }

        $spreadsheet = IOFactory::load($_FILES['file']['tmp_name']);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        foreach ($rows as $i => $row) {

    if ($i == 1) continue;

    $wali  = $this->db->get_where('guru', [
        'nama' => trim($row['D'])
    ])->row();

    $ketua = $this->db->get_where('siswa', [
        'nama' => trim($row['E'])
    ])->row();

    $this->Kelas_model->insert([
    'nama'           => $row['B'],
    'jurusan'        => $row['C'],
    'wali_kelas_id'  => $wali ? $wali->id : NULL,
    'ketua_kelas_id' => $ketua ? $ketua->id : NULL,
    'kapasitas'      => $row['F'],
    'is_active'      => 1
]);
}

        $this->session->set_flashdata('success', 'Import data kelas selesai.');
        redirect('kelas');
    }
}
