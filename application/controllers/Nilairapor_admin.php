<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Nilairapor_admin extends CI_Controller {

    private $tahun_id;

    public function __construct()
    {
        parent::__construct();

        $session_tahun = $this->session->userdata('tahun_id');

        if ($session_tahun) {
            $this->tahun_id = $session_tahun;
        } else {
            $t = $this->db->get_where('tahun_ajaran', ['aktif' => 1])->row();
            $this->tahun_id = $t ? $t->id : null;
        }

        $this->load->model([
            'Nilai_rapor_model',
            'Siswa_model',
            'Mapel_model'
        ]);
    }


    /* =========================
     * INPUT NILAI (MANUAL)
     * ========================= */
    public function index()
    {
        $data['active'] = 'nilai_input';
        $data['siswa']  = $this->Siswa_model->get_all_simple();
        $data['mapel']  = $this->Mapel_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/nilai_rapor/index', $data);
        $this->load->view('templates/footer');
    }

    public function simpan()
    {
        $siswa_id = $this->input->post('siswa_id');
        $mapel_id = $this->input->post('mapel_id');
        $semester = $this->input->post('semester');
        $nilai    = $this->input->post('nilai_angka');

        $this->Nilai_rapor_model->insert_or_update([
    'siswa_id'    => $siswa_id,
    'mapel_id'    => $mapel_id,
    'semester'    => $semester,
    'tahun_id'    => $this->tahun_id,
    'nilai_angka' => $nilai
]);

        $this->session->set_flashdata(
            'success',
            'Nilai berhasil disimpan / diperbarui'
        );
        redirect('Nilairapor_admin');
    }

    /* =========================
 * REKAP NILAI (ADMIN)
 * ========================= */
public function rekap()
{
    $this->load->library('pagination');

    $data['active'] = 'nilai_daftar';

    // =========================
    // FILTER
    // =========================
    $kelas_id = $this->input->get('kelas', true);

    // =========================
    // PAGINATION
    // =========================
    $per_page = 100;

    $page = (int)$this->input->get('page');
    if ($page < 1) {
        $page = 1;
    }

    $offset = ($page - 1) * $per_page;

    // =========================
    // TOTAL DATA
    // =========================
    $total_rows = $this->Nilai_rapor_model
        ->count_rekap_siswa($kelas_id);

    // =========================
    // PAGINATION CONFIG
    // =========================
    $config['base_url'] = site_url('Nilairapor_admin/rekap');

    $query = [];

    if (!empty($kelas_id)) {
        $query['kelas'] = $kelas_id;
    }

    $config['suffix'] = !empty($query)
        ? '&' . http_build_query($query)
        : '';

    $config['first_url'] = $config['base_url']
        . '?page=1'
        . $config['suffix'];

    $config['total_rows'] = $total_rows;
    $config['per_page'] = $per_page;

    $config['page_query_string'] = true;
    $config['query_string_segment'] = 'page';
    $config['use_page_numbers'] = true;

    // Bootstrap
    $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
    $config['full_tag_close'] = '</ul>';

    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';

    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';

    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';

    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';

    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';

    $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close'] = '</span></li>';

    $config['attributes'] = [
        'class' => 'page-link'
    ];

    $this->pagination->initialize($config);

    // =========================
    // DATA
    // =========================

    $data['kelas'] = $this->db
        ->order_by('nama', 'ASC')
        ->get('kelas')
        ->result();

    // Header tabel
    $data['mapel'] = $this->Nilai_rapor_model
    ->get_mapel_rekap($kelas_id);

    // Rekap nilai (SEMUA SEMESTER)
    $data['rekap'] = $this->Nilai_rapor_model
        ->rekap_siswa(
            $kelas_id,
            $per_page,
            $offset
        );

    $data['pagination'] = $this->pagination->create_links();
    $data['page'] = $page;

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $data);
    $this->load->view('admin/nilai_rapor/rekap', $data);
    $this->load->view('templates/footer');
}

    /* =========================
     * IMPORT EXCEL (LEBAR)
     * ========================= */
    public function import()
    {
        $data['active'] = 'nilai_import';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/nilai_rapor/import', $data);
        $this->load->view('templates/footer');
    }

    public function import_proses_lebar()
{
    require FCPATH . 'vendor/autoload.php';

    if (empty($_FILES['file']['name'])) {
        redirect('Nilairapor_admin/import');
    }

    $spreadsheet = IOFactory::load($_FILES['file']['tmp_name']);
    $sheetData   = $spreadsheet->getActiveSheet()->toArray();

    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    | Row 0 = Nama Mapel
    | Row 1 = Semester
    | Row 2 dst = Data
    |--------------------------------------------------------------------------
    */

    $headerMapel = $sheetData[0];
    $headerSmt   = $sheetData[1];

    /*
    |--------------------------------------------------------------------------
    | Mapping Kolom -> Mapel + Semester
    |--------------------------------------------------------------------------
    */

    $mapelMap = [];
    $currentMapel = '';

    for ($col = 2; $col < count($headerMapel); $col++) {

        if (trim($headerMapel[$col]) != '') {
            $currentMapel = trim($headerMapel[$col]);
        }

        if ($currentMapel == '') {
            continue;
        }

        $semester = (int) preg_replace('/[^0-9]/', '', $headerSmt[$col]);

        if ($semester == 0) {
            continue;
        }

        $mapel = $this->db
            ->where('nama_mapel', $currentMapel)
            ->get('mapel')
            ->row();

        if (!$mapel) {
            continue;
        }

        $mapelMap[$col] = [
            'mapel_id'  => $mapel->id_mapel,
            'semester'  => $semester
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT DATA
    |--------------------------------------------------------------------------
    */

    $berhasil = 0;
    $skipSiswa = 0;
    $skipNilai = 0;

    foreach ($sheetData as $rowIndex => $row) {

        if ($rowIndex < 2) {
            continue;
        }

        $nisn = trim($row[0]);

        if ($nisn == '') {
            continue;
        }

        // Nama hanya sebagai referensi
        $namaExcel = isset($row[1]) ? trim($row[1]) : '';

        $siswa = $this->db
            ->where('nisn', $nisn)
            ->where('status', 'aktif')
            ->get('siswa')
            ->row();

        if (!$siswa) {
            $skipSiswa++;
            continue;
        }

        foreach ($mapelMap as $col => $info) {

            if (!isset($row[$col])) {
                continue;
            }

            $nilai = trim($row[$col]);

            // kosong = skip
            if ($nilai === '') {
                $skipNilai++;
                continue;
            }

            $this->Nilai_rapor_model->insert_or_update([
                'siswa_id'    => $siswa->id,
                'mapel_id'    => $info['mapel_id'],
                'semester'    => $info['semester'],
                'tahun_id'    => $this->tahun_id,
                'nilai_angka' => $nilai
            ]);

            $berhasil++;
        }
    }

    $this->session->set_flashdata(
        'success',
        'Import selesai.<br>
        Nilai diproses : <b>'.$berhasil.'</b><br>
        Siswa tidak ditemukan : <b>'.$skipSiswa.'</b><br>
        Nilai kosong dilewati : <b>'.$skipNilai.'</b>'
    );

    redirect('Nilairapor_admin/import');
}

    /* =========================
     * DOWNLOAD TEMPLATE
     * ========================= */
    public function download_template_lebar()
{
    require FCPATH . 'vendor/autoload.php';

    $mapel = $this->db
        ->order_by('nama_mapel', 'ASC')
        ->get('mapel')
        ->result();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Template Nilai');

    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    $sheet->setCellValue('A1', 'NISN');
    $sheet->mergeCells('A1:A2');

    $sheet->setCellValue('B1', 'NAMA SISWA');
    $sheet->mergeCells('B1:B2');

    $sheet->getColumnDimension('A')->setWidth(18);
    $sheet->getColumnDimension('B')->setWidth(35);

    $col = 3; // Kolom C

    foreach ($mapel as $m) {

        $startCol = $col;
        $endCol   = $col + 5;

        // Merge nama mapel
        $sheet->mergeCellsByColumnAndRow(
            $startCol,
            1,
            $endCol,
            1
        );

        $sheet->setCellValueByColumnAndRow(
            $startCol,
            1,
            $m->nama_mapel
        );

        // Semester
        for ($smt = 1; $smt <= 6; $smt++) {

            $sheet->setCellValueByColumnAndRow(
                $col,
                2,
                'Smt'.$smt
            );

            $sheet->getColumnDimensionByColumn($col)
                  ->setWidth(9);

            $col++;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STYLE HEADER
    |--------------------------------------------------------------------------
    */

    $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col - 1);

    $headerStyle = [
        'font' => [
            'bold'  => true,
            'color' => ['rgb' => 'FFFFFF']
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'wrapText'   => true
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '4F81BD'
            ]
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            ]
        ]
    ];

    $sheet->getStyle("A1:{$lastColumn}2")
          ->applyFromArray($headerStyle);

    /*
    |--------------------------------------------------------------------------
    | CONTOH DATA
    |--------------------------------------------------------------------------
    */

    $sheet->setCellValue('A3', '0083666053');
    $sheet->setCellValue('B3', 'NAMA SISWA');

    /*
    |--------------------------------------------------------------------------
    | FREEZE HEADER
    |--------------------------------------------------------------------------
    */

    $sheet->freezePane('C3');

    /*
    |--------------------------------------------------------------------------
    | OUTPUT
    |--------------------------------------------------------------------------
    */

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="template_import_nilai.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}


public function export_rekap_excel()
{
    $this->load->library('Spreadsheet_lib');

    $kelas_id = $this->input->get('kelas');

    $mapel = $this->Nilai_rapor_model->get_mapel_rekap($kelas_id);

    $rekap = $this->Nilai_rapor_model->rekap_siswa(
        $kelas_id,
        99999,
        0
    );
// echo '<pre>';
// print_r($rekap);
// exit;
    $this->spreadsheet_lib->export_rekap_nilai(
        $rekap,
        $mapel
    );
}

/* ==========================================
 * HAPUS NILAI RAPOR
 * ========================================== */
public function hapus_nilai()
{
    $kelas_id  = $this->input->post('kelas_id');
    $mapel_id  = $this->input->post('mapel_id');
    $semester  = $this->input->post('semester');

    if (empty($kelas_id) || empty($mapel_id)) {

        $this->session->set_flashdata(
            'error',
            'Kelas dan Mata Pelajaran wajib dipilih.'
        );

        redirect('Nilairapor_admin/rekap?kelas='.$kelas_id);
    }

    $hapus = $this->Nilai_rapor_model->hapus_nilai(
        $kelas_id,
        $mapel_id,
        $semester
    );

    $this->session->set_flashdata(
        'success',
        $hapus.' nilai berhasil dihapus.'
    );

    redirect('Nilairapor_admin/rekap?kelas='.$kelas_id);
}
}
