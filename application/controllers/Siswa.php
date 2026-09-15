<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class Siswa extends CI_Controller {

 public function __construct() {
    parent::__construct();

    $this->load->model('Siswa_model');
    $this->load->library(['form_validation', 'pagination', 'Spreadsheet_Lib']);
    $this->load->helper(['url', 'form', 'tanggal']);
    $this->load->library('idcard_lib');

    // ===============================
    // AUTH GUARD (EXCEPT PDF)
    // ===============================
    $public_methods = [
        'cetak',
        'surat_pernyataan'
    ];

    if (!in_array($this->router->fetch_method(), $public_methods)) {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
            exit;
        }
    }
}


  public function index($offset = 0)
{
    $this->load->library('pagination');

    // 🔹 Ambil parameter filter dan pencarian dari GET
    $kelas_id = $this->input->get('kelas');
    $search   = $this->input->get('search');
    $limit    = $this->input->get('limit') ?: 10;

    // 🔹 Hitung total baris untuk pagination
    $this->db->from('siswa');
    $this->db->where('status', 'aktif');
    if (!empty($kelas_id)) {
        $this->db->where('id_kelas', $kelas_id);
    }
    if (!empty($search)) {
        $this->db->group_start()
                 ->like('nama', $search)
                 ->or_like('nis', $search)
                 ->or_like('nisn', $search)
                 ->group_end();
    }
    $config['total_rows'] = $this->db->count_all_results();

    // 🔹 Konfigurasi pagination
    $config['base_url'] = site_url('siswa/index');
    $config['per_page'] = $limit;
    $config['uri_segment'] = 3;
    $config['reuse_query_string'] = true;

    // 💅 Bootstrap 5 pagination style
$config['full_tag_open']   = '<nav><ul class="pagination pagination-sm justify-content-center my-3">';
$config['full_tag_close']  = '</ul></nav>';
$config['attributes']      = ['class' => 'page-link'];

$config['first_link']      = '<i class="fas fa-angle-double-left"></i>';
$config['first_tag_open']  = '<li class="page-item">';
$config['first_tag_close'] = '</li>';

$config['last_link']       = '<i class="fas fa-angle-double-right"></i>';
$config['last_tag_open']   = '<li class="page-item">';
$config['last_tag_close']  = '</li>';

$config['next_link']       = '<i class="fas fa-angle-right"></i>';
$config['next_tag_open']   = '<li class="page-item">';
$config['next_tag_close']  = '</li>';

$config['prev_link']       = '<i class="fas fa-angle-left"></i>';
$config['prev_tag_open']   = '<li class="page-item">';
$config['prev_tag_close']  = '</li>';

$config['cur_tag_open']    = '<li class="page-item active"><a class="page-link bg-primary border-primary text-white" href="#">';
$config['cur_tag_close']   = '</a></li>';

$config['num_tag_open']    = '<li class="page-item">';
$config['num_tag_close']   = '</li>';

$config['reuse_query_string'] = true;

    $this->pagination->initialize($config);

    // 🔹 Query utama siswa (dengan join dan filter)
    $this->db->select('siswa.*, kelas.nama AS nama_kelas, tahun_ajaran.tahun AS tahun_ajaran');
    $this->db->join('kelas', 'kelas.id = siswa.id_kelas', 'left');
    $this->db->join('tahun_ajaran', 'tahun_ajaran.id = siswa.tahun_id', 'left');
    $this->db->where('siswa.status', 'aktif');
    if (!empty($kelas_id)) {
        $this->db->where('siswa.id_kelas', $kelas_id);
    }
    if (!empty($search)) {
        $this->db->group_start()
                 ->like('siswa.nama', $search)
                 ->or_like('siswa.nis', $search)
                 ->or_like('siswa.nisn', $search)
                 ->group_end();
    }
    $this->db->order_by('siswa.id', 'ASC');
    $data['siswa'] = $this->db->get('siswa', $limit, $offset)->result();

    // 🔹 Data tambahan untuk tampilan
    $data['title'] = 'Data Siswa Aktif';
    $data['active'] = 'siswa';
    $data['pagination'] = $this->pagination->create_links();
    $data['kelas'] = $this->Siswa_model->get_kelas_list();
    $data['tahun'] = $this->Siswa_model->get_tahun_list();
    $data['start'] = $offset;
    $data['kelas_id'] = $kelas_id;
    $data['search'] = $search;
    $data['limit'] = $limit;

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $data);
    $this->load->view('siswa/index', $data);
    $this->load->view('templates/footer');
}


  // ========================================
  // TAMBAH SISWA
  // ========================================
  public function add()
{
    if (!$this->input->post()) {
        redirect('siswa');
        return;
    }

    $nisn = $this->input->post('nisn', TRUE);

    // =========================
    // CEK NISN DUPLIKAT
    // =========================
    $cek = $this->db->get_where('siswa', ['nisn' => $nisn])->row();
    if ($cek) {
        $this->session->set_flashdata(
            'error',
            'NISN <b>' . $nisn . '</b> sudah terdaftar atas nama <b>' . $cek->nama . '</b>'
        );
        redirect('siswa');
        return;
    }

    // =========================
    // INSERT SISWA
    // =========================
    $data = [
        'nis'          => $this->input->post('nis', TRUE),
        'nisn'         => $nisn,
        'nama'         => $this->input->post('nama', TRUE),
        'jk'           => $this->input->post('jk', TRUE),
        'agama'        => $this->input->post('agama', TRUE),
        'tempat_lahir' => $this->input->post('tempat_lahir', TRUE),
        'tgl_lahir'    => $this->input->post('tgl_lahir', TRUE),
        'alamat'       => $this->input->post('alamat', TRUE),
        'id_kelas'     => $this->input->post('id_kelas', TRUE),
        'tahun_id'     => $this->input->post('tahun_id', TRUE),
        'status'       => 'aktif',
        'created_at'   => date('Y-m-d H:i:s')
    ];

    $this->db->insert('siswa', $data);
    $id_siswa = $this->db->insert_id();

    // =========================
    // GENERATE QR (ISI = NISN)
    // =========================
    require_once APPPATH . 'libraries/phpqrcode/qrlib.php';

    $qr_folder = FCPATH . 'uploads/qr/';
    if (!file_exists($qr_folder)) {
        mkdir($qr_folder, 0777, true);
    }

    $qr_file = $qr_folder . $nisn . '.png';

    if (!file_exists($qr_file)) {
        QRcode::png(
            $nisn,
            $qr_file,
            QR_ECLEVEL_H,
            10,
            2
        );
    }

    // Simpan token_qr = nisn (opsional, biar backward compatible)
    $this->db->where('id', $id_siswa)->update('siswa', [
        'token_qr' => $nisn
    ]);

    $this->session->set_flashdata('success', 'Data siswa berhasil ditambahkan.');
    redirect('siswa');
}


  // ========================================
  // EDIT SISWA
  // ========================================
  public function edit($id)
{
    $siswa = $this->Siswa_model->get_by_id($id);

    if ($this->input->post()) {

        $data = [

    // ===========================
    // DATA PRIBADI
    // ===========================
    'nis'             => $this->input->post('nis', TRUE),
    'nisn'            => $this->input->post('nisn', TRUE),
    'nama'            => $this->input->post('nama', TRUE),
    'jk'              => $this->input->post('jk', TRUE),
    'agama'           => $this->input->post('agama', TRUE),
    'tempat_lahir'    => $this->input->post('tempat_lahir', TRUE),
    'tgl_lahir'       => $this->input->post('tgl_lahir', TRUE),
    'nomor_kk'        => $this->input->post('nomor_kk', TRUE),
    'nik'             => $this->input->post('nik', TRUE),
    'anak_keberapa'   => $this->input->post('anak_keberapa', TRUE),

    'alamat'          => $this->input->post('alamat', TRUE),
    'rt'              => $this->input->post('rt', TRUE),
    'rw'              => $this->input->post('rw', TRUE),
    'dusun'           => $this->input->post('dusun', TRUE),
    'kecamatan'       => $this->input->post('kecamatan', TRUE),
    'kode_pos'        => $this->input->post('kode_pos', TRUE),
    'telp'            => $this->input->post('telp', TRUE),
    'hp'              => $this->input->post('hp', TRUE),
    'email'           => $this->input->post('email', TRUE),

    // ===========================
    // KESEJAHTERAAN
    // ===========================
    'desil'           => $this->input->post('desil', TRUE),
    'penerima_kps'    => $this->input->post('penerima_kps', TRUE),
    'no_kps'          => $this->input->post('no_kps', TRUE),

    // ===========================
    // DATA PERIODIK
    // ===========================
    'tinggi_badan'    => $this->input->post('tinggi_badan', TRUE),
    'berat_badan'     => $this->input->post('berat_badan', TRUE),
    'hobi'            => $this->input->post('hobi', TRUE),
    'cita_cita'       => $this->input->post('cita_cita', TRUE),

    // ===========================
    // DATA PENDIDIKAN
    // ===========================
    'sekolah_asal'    => $this->input->post('sekolah_asal', TRUE),
    'skhun'           => $this->input->post('skhun', TRUE),

    // ===========================
    // DATA AYAH
    // ===========================
    'nama_ayah'            => $this->input->post('nama_ayah', TRUE),
    'nik_ayah'             => $this->input->post('nik_ayah', TRUE),
    'tahun_lahir_ayah'     => $this->input->post('tahun_lahir_ayah', TRUE),
    'pendidikan_ayah'      => $this->input->post('pendidikan_ayah', TRUE),
    'pekerjaan_ayah'       => $this->input->post('pekerjaan_ayah', TRUE),
    'penghasilan_ayah'     => $this->input->post('penghasilan_ayah', TRUE),

    // ===========================
    // DATA IBU
    // ===========================
    'nama_ibu'             => $this->input->post('nama_ibu', TRUE),
    'nik_ibu'              => $this->input->post('nik_ibu', TRUE),
    'tahun_lahir_ibu'      => $this->input->post('tahun_lahir_ibu', TRUE),
    'pendidikan_ibu'       => $this->input->post('pendidikan_ibu', TRUE),
    'pekerjaan_ibu'        => $this->input->post('pekerjaan_ibu', TRUE),
    'penghasilan_ibu'      => $this->input->post('penghasilan_ibu', TRUE),

    // ===========================
    // DATA WALI
    // ===========================
    'nama_wali'            => $this->input->post('nama_wali', TRUE),
    'nik_wali'             => $this->input->post('nik_wali', TRUE),
    'tahun_lahir_wali'     => $this->input->post('tahun_lahir_wali', TRUE),
    'pendidikan_wali'      => $this->input->post('pendidikan_wali', TRUE),
    'pekerjaan_wali'       => $this->input->post('pekerjaan_wali', TRUE),
    'penghasilan_wali'     => $this->input->post('penghasilan_wali', TRUE),

    // ===========================
    // AKADEMIK
    // ===========================
    'id_kelas'        => $this->input->post('id_kelas', TRUE),
    'tahun_id'        => $this->input->post('tahun_id', TRUE),
    'status'          => $this->input->post('status', TRUE),

    // ===========================
    // KONTAK
    // ===========================
    'no_hp_ortu'      => $this->input->post('no_hp_ortu', TRUE),

];
// ===========================
// TIDAK MEMILIKI WALI
// ===========================
if ($this->input->post('punya_wali') == '0') {

    $data['nama_wali']          = NULL;
    $data['nik_wali']           = NULL;
    $data['tahun_lahir_wali']   = NULL;
    $data['pendidikan_wali']    = NULL;
    $data['pekerjaan_wali']     = NULL;
    $data['penghasilan_wali']   = NULL;

}

        // ============================================
        // 🔥 HANDLE UPLOAD FOTO
        // ============================================
        if (!empty($_FILES['foto']['name'])) {

            // Konfigurasi upload
            $config['upload_path']   = './uploads/foto/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048; // 2MB
            $config['file_name']     = 'foto_' . time();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {

                $upload = $this->upload->data();
                $data['foto'] = $upload['file_name'];

                // Hapus foto lama jika ada
                if (!empty($siswa->foto) && file_exists('./uploads/foto/' . $siswa->foto)) {
                    unlink('./uploads/foto/' . $siswa->foto);
                }

            } else {
                // Jika upload gagal
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('siswa/edit/' . $id);
            }
        }
        // ============================================


        // UPDATE KE DATABASE
        $this->Siswa_model->update($id, $data);

        $this->session->set_flashdata('success', 'Data siswa berhasil diperbarui.');
        redirect('siswa');

    } else {

        // Menampilkan form edit
        $data['siswa'] = $siswa;
        $data['kelas'] = $this->Siswa_model->get_kelas_list();
        $data['tahun'] = $this->Siswa_model->get_tahun_list();
        $data['title'] = 'Edit Siswa';
        $data['active'] = 'siswa';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('siswa/edit', $data);
        $this->load->view('templates/footer');
    }
}


  // ========================================
  // HAPUS SISWA (cek mutasi dulu)
  // ========================================
  public function delete($id) {
    $ada_mutasi = $this->db->where('siswa_id', $id)->count_all_results('mutasi');
    if ($ada_mutasi > 0) {
      $this->session->set_flashdata('error', 'Siswa ini sudah memiliki data mutasi dan tidak dapat dihapus.');
    } else {
      $this->Siswa_model->delete($id);
      $this->session->set_flashdata('success', 'Data siswa berhasil dihapus.');
    }
    redirect('siswa');
  }

 // ========================================================
// EXPORT EXCEL - HANYA SISWA AKTIF
// ========================================================
public function export_excel()
{
    @ob_end_clean();
    ob_start();

    $this->load->database();
    $this->load->model("Siswa_model");

    error_reporting(0);

    // ========================================================
    // AMBIL FILTER DARI URL
    // ========================================================
    $kelas_id = $this->input->get('kelas', TRUE);
    $search   = $this->input->get('search', TRUE);

    // ========================================================
    // QUERY DATA SISWA
    // HANYA STATUS AKTIF
    // ========================================================
    $this->db->select('
        siswa.*,
        kelas.nama AS nama_kelas,
        kelas.jurusan AS nama_jurusan
    ');

    $this->db->from('siswa');

    $this->db->join(
        'kelas',
        'kelas.id = siswa.id_kelas',
        'left'
    );

    // 🔥 PENTING:
    // HANYA SISWA AKTIF
    $this->db->where('siswa.status', 'aktif');

    // ========================================================
    // FILTER KELAS
    // ========================================================
    if (!empty($kelas_id)) {
        $this->db->where('siswa.id_kelas', $kelas_id);
    }

    // ========================================================
    // FILTER PENCARIAN
    // ========================================================
    if (!empty($search)) {

        $this->db->group_start();

        $this->db->like('siswa.nama', $search);
        $this->db->or_like('siswa.nis', $search);
        $this->db->or_like('siswa.nisn', $search);

        $this->db->group_end();
    }

    // ========================================================
    // URUTKAN
    // ========================================================
    $this->db->order_by('siswa.id', 'ASC');

    $data = $this->db->get()->result();

    // ========================================================
    // FIELD EXPORT
    // ========================================================

    // Ambil semua field tabel siswa
    $fields = $this->db->list_fields('siswa');

    // Jangan export id_kelas karena sudah ada nama kelas
    $fields = array_filter($fields, function ($f) {
        return $f != 'id_kelas';
    });

    // Reset index array
    $fields = array_values($fields);

    // Tambahkan field relasi
    $fields[] = 'nama_kelas';
    $fields[] = 'nama_jurusan';

    // ========================================================
    // SPREADSHEET
    // ========================================================

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    $sheet = $spreadsheet->setActiveSheetIndex(0);

    $sheet->setTitle('Data Siswa Aktif');

    // ========================================================
    // HEADER
    // ========================================================

    $colIndex = 1;

    foreach ($fields as $f) {

        $sheet->setCellValueByColumnAndRow(
            $colIndex,
            1,
            strtoupper($f)
        );

        $colIndex++;
    }

    // ========================================================
    // STYLE HEADER
    // ========================================================

    $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
        count($fields)
    );

    $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
        'font' => [
            'bold' => true
        ],

        'alignment' => [
            'horizontal' =>
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        ],

        'borders' => [
            'allBorders' => [
                'style' =>
                    \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            ]
        ]
    ]);

    // ========================================================
    // FIELD YANG HARUS TEXT
    // ========================================================

    $textFields = [
        'nis',
        'nisn',
        'nomor_kk',
        'nik',
        'nik_ayah',
        'nik_ibu',
        'nik_wali',
        'no_hp_ortu',
        'hp',
        'telp'
    ];

    // ========================================================
    // DATA
    // ========================================================

    $row = 2;

    foreach ($data as $d) {

        $colIndex = 1;

        foreach ($fields as $f) {

            $value = isset($d->$f) ? $d->$f : '';

            $cell =
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                    $colIndex
                ) . $row;

            // Deteksi angka panjang
            $isLongNumber =
                is_numeric($value) &&
                strlen((string)$value) >= 12;

            // ====================================================
            // PAKSA TEXT UNTUK NIS / NIK / HP DLL
            // ====================================================

            if (in_array($f, $textFields) || $isLongNumber) {

                $sheet->setCellValueExplicit(
                    $cell,
                    (string)$value,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );

                $sheet->getStyle($cell)
                    ->getNumberFormat()
                    ->setFormatCode(
                        \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT
                    );

            } else {

                $sheet->setCellValueByColumnAndRow(
                    $colIndex,
                    $row,
                    $value
                );
            }

            $colIndex++;
        }

        $row++;
    }

    // ========================================================
    // AUTOSIZE
    // ========================================================

    for ($i = 1; $i <= count($fields); $i++) {

        $colLetter =
            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);

        $sheet->getColumnDimension($colLetter)
            ->setAutoSize(true);
    }

    // ========================================================
    // BORDER DATA
    // ========================================================

    $lastRow = $row - 1;

    if ($lastRow >= 1) {

        $sheet->getStyle("A1:{$lastCol}{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );
    }

    // ========================================================
    // OUTPUT
    // ========================================================

    header('Content-Type: application/vnd.ms-excel');

    header(
        'Content-Disposition: attachment; filename="data_siswa_aktif.xls"'
    );

    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);

    $writer->save('php://output');

    ob_end_flush();

    exit;
}


public function download_template()
{
    // ambil referensi data
    $kelas       = $this->db->select('nama')->get('kelas')->result_array();
    $tahun       = $this->db->select('tahun')->get('tahun_ajaran')->result_array();
    $status      = ['aktif','mutasi_keluar','mutasi_masuk','lulus','keluar'];
    $agama_list  = ['Islam','Kristen','Katolik','Hindu','Budha','Konghucu'];

    // kolom template (jangan diubah)
    $fields = [
        'nis','nisn','nik','nama','jk','agama','tempat_lahir','tgl_lahir',
        'alamat','jalan','rt','rw','dusun','kecamatan','kode_pos','jenis_tinggal',
        'alat_transportasi','telp','hp','email','skhun','penerima_kps','no_kps',
        'nama_ayah','tahun_lahir_ayah','pendidikan_ayah','pekerjaan_ayah','penghasilan_ayah','nik_ayah',
        'nama_ibu','tahun_lahir_ibu','pendidikan_ibu','pekerjaan_ibu','penghasilan_ibu','nik_ibu',
        'no_hp_ortu',
        'nama_wali','tahun_lahir_wali','pendidikan_wali','pekerjaan_wali','penghasilan_wali','nik_wali',
        'sekolah_asal','hobi','cita_cita','anak_keberapa','nomor_kk','berat_badan',
        'tinggi_badan','jumlah_saudara_kandung',
        'kelas','tahun_ajaran','status'
    ];

    // start excel
    $spreadsheet = new Spreadsheet();
    $sheet       = $spreadsheet->setActiveSheetIndex(0);
    $sheet->setTitle('Template Siswa');

    // header
    $colIndex = 1;
    foreach ($fields as $f) {
        $sheet->setCellValueByColumnAndRow($colIndex, 1, strtoupper($f));
        $colIndex++;
    }

    // sheet referensi
    $ref = $spreadsheet->createSheet();
    $ref->setTitle('Referensi');

    // isi referensi kelas
    $i=1;
    foreach ($kelas as $k) {
        $ref->setCellValue("A$i", $k['nama']);
        $i++;
    }

    // isi referensi tahun
    $i=1;
    foreach ($tahun as $t) {
        $ref->setCellValue("B$i", $t['tahun']);
        $i++;
    }

    // isi referensi status
    $i=1;
    foreach ($status as $s) {
        $ref->setCellValue("C$i", $s);
        $i++;
    }

    // isi referensi agama
    $i=1;
    foreach ($agama_list as $a) {
        $ref->setCellValue("D$i", $a);
        $i++;
    }

    // kembali ke sheet utama
    $sheet = $spreadsheet->setActiveSheetIndex(0);

    // range dropdown
    $kelasRange  = "'Referensi'!\$A\$1:\$A\$" . count($kelas);
$tahunRange  = "'Referensi'!\$B\$1:\$B\$" . count($tahun);
$statusRange = "'Referensi'!\$C\$1:\$C\$" . count($status);
$agamaRange  = "'Referensi'!\$D\$1:\$D\$" . count($agama_list);

    // mapping kolom
    $jkCol         = array_search('jk', $fields) + 1;
    $agamaCol      = array_search('agama', $fields) + 1;
    $kelasCol      = array_search('kelas', $fields) + 1;
    $tahunCol      = array_search('tahun_ajaran', $fields) + 1;
    $statusCol     = array_search('status', $fields) + 1;

    // apply validation
    for ($row = 2; $row <= 300; $row++) {

        // JK
        $sheet->getCellByColumnAndRow($jkCol, $row)
              ->getDataValidation()
              ->setType(DataValidation::TYPE_LIST)
              ->setAllowBlank(true)
              ->setShowDropDown(true)
              ->setFormula1('"L,P"');

        // Agama
        $sheet->getCellByColumnAndRow($agamaCol, $row)
              ->getDataValidation()
              ->setType(DataValidation::TYPE_LIST)
              ->setAllowBlank(true)
              ->setShowDropDown(true)
              ->setFormula1($agamaRange);

        // Kelas
        $sheet->getCellByColumnAndRow($kelasCol, $row)
              ->getDataValidation()
              ->setType(DataValidation::TYPE_LIST)
              ->setAllowBlank(true)
              ->setShowDropDown(true)
              ->setFormula1($kelasRange);

        // Tahun
        $sheet->getCellByColumnAndRow($tahunCol, $row)
              ->getDataValidation()
              ->setType(DataValidation::TYPE_LIST)
              ->setAllowBlank(true)
              ->setShowDropDown(true)
              ->setFormula1($tahunRange);

        // Status
        $sheet->getCellByColumnAndRow($statusCol, $row)
              ->getDataValidation()
              ->setType(DataValidation::TYPE_LIST)
              ->setAllowBlank(true)
              ->setShowDropDown(true)
              ->setFormula1($statusRange);
    }

    // autosize columns
    $lastCol = count($fields);
    for ($i=1; $i <= $lastCol; $i++) {
        $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
    }

    // output
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="template_siswa_full.xls"');
    header('Cache-Control: max-age=0');

    $writer = new Xls($spreadsheet);
    $writer->save('php://output');
}



 // ============================================================
// IMPORT EXCEL SISWA
// ============================================================
public function import_excel()
{
    if (
        !isset($_FILES['file']) ||
        empty($_FILES['file']['name']) ||
        $_FILES['file']['error'] !== UPLOAD_ERR_OK
    ) {
        $this->session->set_flashdata(
            'error',
            'File Excel tidak ditemukan atau gagal diupload.'
        );

        redirect('siswa');
        return;
    }

    // ========================================================
    // LOAD EXCEL
    // ========================================================
    try {

        $path = $_FILES['file']['tmp_name'];

        $spreadsheet = IOFactory::load($path);
        $sheet       = $spreadsheet->getActiveSheet();

        $rowsRaw = $sheet->toArray(null, true, true, false);

    } catch (Exception $e) {

        $this->session->set_flashdata(
            'error',
            'File Excel tidak dapat dibaca: ' . $e->getMessage()
        );

        redirect('siswa');
        return;
    }

    if (empty($rowsRaw) || count($rowsRaw) < 2) {

        $this->session->set_flashdata(
            'error',
            'File Excel kosong atau hanya berisi header.'
        );

        redirect('siswa');
        return;
    }

    // ========================================================
    // NORMALIZE FUNCTION
    // ========================================================
    $normalize = function ($value) {

        $value = trim((string) $value);

        $value = str_replace(
            [
                "\xc2\xa0",
                "\r",
                "\n",
                "\t"
            ],
            ' ',
            $value
        );

        $value = preg_replace('/\s+/', ' ', $value);

        $value = strtolower($value);

        // Buang karakter pemisah
        $value = str_replace(
            [
                ' ',
                '_',
                '.',
                '-',
                '–',
                '—',
                '/',
                '\\'
            ],
            '',
            $value
        );

        return $value;
    };

    // ========================================================
    // CONVERT ROW
    // Numeric index → A, B, C...
    // ========================================================
    $rows = [];

    foreach ($rowsRaw as $rowIndex => $columns) {

        $newRow  = [];
        $colIndex = 1;

        foreach ($columns as $value) {

            $colLetter = Coordinate::stringFromColumnIndex($colIndex);

            $newRow[$colLetter] = $value;

            $colIndex++;
        }

        $rows[$rowIndex] = $newRow;
    }

    // ========================================================
    // FIELD DATABASE YANG DIDUKUNG
    // ========================================================
    $expected_fields = [

        'nis',
        'nisn',
        'nik',
        'nama',
        'jk',
        'agama',
        'tempat_lahir',
        'tgl_lahir',

        'alamat',
        'jalan',
        'rt',
        'rw',
        'dusun',
        'kecamatan',
        'kode_pos',
        'jenis_tinggal',
        'alat_transportasi',

        'telp',
        'hp',
        'email',

        'skhun',
        'penerima_kps',
        'no_kps',

        'nama_ayah',
        'tahun_lahir_ayah',
        'pendidikan_ayah',
        'pekerjaan_ayah',
        'penghasilan_ayah',
        'nik_ayah',

        'nama_ibu',
        'tahun_lahir_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'penghasilan_ibu',
        'nik_ibu',

        'no_hp_ortu',

        'nama_wali',
        'tahun_lahir_wali',
        'pendidikan_wali',
        'pekerjaan_wali',
        'penghasilan_wali',
        'nik_wali',

        'sekolah_asal',
        'hobi',
        'cita_cita',
        'anak_keberapa',

        'nomor_kk',
        'berat_badan',
        'tinggi_badan',
        'jumlah_saudara_kandung',

        'id_kelas',
        'tahun_id',
        'status'
    ];

    // ========================================================
    // SINONIM HEADER EXCEL
    // ========================================================
    $synonyms = [

        'nis' => [
            'nis',
            'no nis',
            'nomor nis',
            'no.nis'
        ],

        'nisn' => [
            'nisn',
            'no nisn',
            'nomor nisn',
            'no.nisn',
            'nonisn',
            'nis n'
        ],

        'nik' => [
            'nik',
            'no nik',
            'nomor nik',
            'no.nik'
        ],

        'nama' => [
            'nama',
            'nama siswa',
            'nama lengkap',
            'nama_lengkap'
        ],

        'jk' => [
            'jk',
            'jenis kelamin',
            'jenis_kelamin',
            'kelamin'
        ],

        'agama' => [
            'agama'
        ],

        'tempat_lahir' => [
            'tempat lahir',
            'tempat_lahir'
        ],

        'tgl_lahir' => [
            'tgl lahir',
            'tanggal lahir',
            'tgl_lahir',
            'tanggal_lahir'
        ],

        'id_kelas' => [
            'kelas',
            'id kelas',
            'id_kelas',
            'nama kelas',
            'nama_kelas',
            'kelas nama'
        ],

        'tahun_id' => [
            'tahun',
            'tahun ajaran',
            'tahun_ajaran',
            'tahunajaran',
            'tahun id',
            'tahun_id',
            'id tahun',
            'id_tahun'
        ],

        'status' => [
            'status'
        ],

        'no_kps' => [
            'no kps',
            'no_kps',
            'nokps'
        ],

        'anak_keberapa' => [
            'anak keberapa',
            'anak_keberapa',
            'anak ke',
            'anak_ke'
        ],

        'nomor_kk' => [
            'nomor kk',
            'nomor_kk',
            'no kk',
            'no_kk',
            'nokk'
        ],

        'no_hp_ortu' => [
            'no hp ortu',
            'no_hp_ortu',
            'nomor hp orang tua',
            'hp orang tua',
            'no hp orang tua'
        ]
    ];

    // ========================================================
    // HEADER EXCEL
    // ========================================================
    $header = $rows[0];

    $fieldToCol = [];

    foreach ($header as $col => $headerText) {

        $headerNorm = $normalize($headerText);

        if ($headerNorm === '') {
            continue;
        }

        // ----------------------------------------------------
        // Cocokkan langsung dengan field DB
        // ----------------------------------------------------
        foreach ($expected_fields as $field) {

            if (
                $headerNorm ===
                $normalize($field)
            ) {

                $fieldToCol[$field] = $col;
                break;
            }
        }

        // Kalau sudah ketemu
        if (isset($fieldToCol[$field])) {
            continue;
        }

        // ----------------------------------------------------
        // Cocokkan sinonim
        // ----------------------------------------------------
        foreach ($synonyms as $field => $variants) {

            foreach ($variants as $variant) {

                if (
                    $headerNorm ===
                    $normalize($variant)
                ) {

                    $fieldToCol[$field] = $col;

                    break 2;
                }
            }
        }
    }

    // ========================================================
    // VALIDASI HEADER WAJIB
    // ========================================================
    if (!isset($fieldToCol['nisn'])) {

        $this->session->set_flashdata(
            'error',
            'Kolom NISN tidak ditemukan pada file Excel.'
        );

        redirect('siswa');
        return;
    }

    // ========================================================
    // AMBIL DATA REFERENSI
    // ========================================================
    $kelasDB = $this->db
        ->get('kelas')
        ->result();

    $tahunDB = $this->db
        ->get('tahun_ajaran')
        ->result();

    // ========================================================
    // LOOKUP KELAS
    // ========================================================
    $kelasById   = [];
    $kelasByName = [];

    foreach ($kelasDB as $kelas) {

        $kelasById[(string) $kelas->id] = $kelas->id;

        $kelasByName[
            $normalize($kelas->nama)
        ] = $kelas->id;
    }

    // ========================================================
    // LOOKUP TAHUN AJARAN
    // ========================================================
    $tahunById    = [];
    $tahunByValue = [];

    foreach ($tahunDB as $tahun) {

        $tahunById[
            (string) $tahun->id
        ] = $tahun->id;

        $tahunByValue[
            $normalize($tahun->tahun)
        ] = $tahun->id;
    }

    // ========================================================
// TAHUN AJARAN AKTIF
// ========================================================
$tahunAktif = $this->db
    ->where('aktif', 1)
    ->order_by('id', 'DESC')
    ->get('tahun_ajaran')
    ->row();

    // ========================================================
    // COUNTER
    // ========================================================
    $insert = 0;
    $update = 0;
    $gagal  = [];

    // ========================================================
    // FIELD YANG TIDAK BOLEH KOSONG SAAT INSERT
    // ========================================================
    $requiredInsert = [
        'nisn',
        'nama'
    ];

    // ========================================================
    // FIELD YANG HARUS TEXT
    // ========================================================
    $textFields = [
        'nis',
        'nisn',
        'nik',
        'nomor_kk',
        'nik_ayah',
        'nik_ibu',
        'nik_wali',
        'no_hp_ortu',
        'hp',
        'telp'
    ];

    // ========================================================
    // LOOP DATA EXCEL
    // ========================================================
    foreach ($rows as $rowIdx => $row) {

        // Header
        if ($rowIdx === 0) {
            continue;
        }

        // ----------------------------------------------------
        // Ambil data dari Excel
        // HANYA FIELD YANG ADA DI HEADER
        // ----------------------------------------------------
        $excelData = [];

        foreach ($fieldToCol as $field => $col) {

            $value = isset($row[$col])
                ? trim((string) $row[$col])
                : '';

            $excelData[$field] = $value;
        }

        // ----------------------------------------------------
        // NISN
        // ----------------------------------------------------
        $nisn = trim(
            $excelData['nisn'] ?? ''
        );

        if ($nisn === '') {

            $gagal[] =
                "Baris $rowIdx: NISN wajib diisi.";

            continue;
        }

        // ----------------------------------------------------
        // Cari siswa berdasarkan NISN
        // ----------------------------------------------------
        $exist = $this->db
            ->where('nisn', $nisn)
            ->get('siswa')
            ->row();

        // ====================================================
        // DATA YANG AKAN DISIMPAN
        // ====================================================
        $save = [];

        // ====================================================
        // INSERT / UPDATE
        // ====================================================
        if ($exist) {

            // =================================================
            // UPDATE SISWA LAMA
            // =================================================
            //
            // HANYA field yang TIDAK KOSONG dari Excel
            // yang akan di-update.
            //
            // Data lama tidak akan ditimpa NULL.
            // =================================================

            foreach ($excelData as $field => $value) {

                if ($value === '') {
                    continue;
                }

                // Jangan masukkan field relasi sebelum mapping
                if (
                    $field === 'id_kelas' ||
                    $field === 'tahun_id' ||
                    $field === 'status'
                ) {
                    continue;
                }

                $save[$field] = $value;
            }

            // -------------------------------------------------
            // KELAS
            // -------------------------------------------------
            if (
                isset($excelData['id_kelas']) &&
                $excelData['id_kelas'] !== ''
            ) {

                $kelasRaw = trim(
                    $excelData['id_kelas']
                );

                if (ctype_digit($kelasRaw)) {

                    if (
                        isset($kelasById[$kelasRaw])
                    ) {

                        $save['id_kelas'] =
                            $kelasById[$kelasRaw];

                    } else {

                        $gagal[] =
                            "Baris $rowIdx: Kelas tidak ditemukan ('$kelasRaw').";

                        continue;
                    }

                } else {

                    $kelasNorm =
                        $normalize($kelasRaw);

                    if (
                        isset($kelasByName[$kelasNorm])
                    ) {

                        $save['id_kelas'] =
                            $kelasByName[$kelasNorm];

                    } else {

                        $gagal[] =
                            "Baris $rowIdx: Kelas tidak valid ('$kelasRaw').";

                        continue;
                    }
                }
            }

            // -------------------------------------------------
            // TAHUN AJARAN
            // -------------------------------------------------
            if (
                isset($excelData['tahun_id']) &&
                $excelData['tahun_id'] !== ''
            ) {

                $tahunRaw = trim(
                    $excelData['tahun_id']
                );

                if (ctype_digit($tahunRaw)) {

                    if (
                        isset($tahunById[$tahunRaw])
                    ) {

                        $save['tahun_id'] =
                            $tahunById[$tahunRaw];

                    } else {

                        $gagal[] =
                            "Baris $rowIdx: ID tahun ajaran tidak ditemukan ('$tahunRaw').";

                        continue;
                    }

                } else {

                    $tahunNorm =
                        $normalize($tahunRaw);

                    if (
                        isset($tahunByValue[$tahunNorm])
                    ) {

                        $save['tahun_id'] =
                            $tahunByValue[$tahunNorm];

                    } else {

                        $gagal[] =
                            "Baris $rowIdx: Tahun ajaran tidak valid ('$tahunRaw').";

                        continue;
                    }
                }

            } else {

                // Excel kosong → pertahankan tahun lama
                $save['tahun_id'] =
                    $exist->tahun_id;
            }

            // -------------------------------------------------
            // STATUS
            // -------------------------------------------------
            if (
                isset($excelData['status']) &&
                $excelData['status'] !== ''
            ) {

                $save['status'] =
                    $excelData['status'];

            } else {

                // Pertahankan status lama
                $save['status'] =
                    $exist->status;
            }

            // -------------------------------------------------
            // Pastikan tahun_id tidak NULL
            // -------------------------------------------------
            if (
                empty($save['tahun_id'])
            ) {

                if (!empty($exist->tahun_id)) {

                    $save['tahun_id'] =
                        $exist->tahun_id;

                } elseif ($tahunAktif) {

                    $save['tahun_id'] =
                        $tahunAktif->id;

                } else {

                    $gagal[] =
                        "Baris $rowIdx: Tahun ajaran tidak tersedia.";

                    continue;
                }
            }

            // -------------------------------------------------
            // Update database
            // -------------------------------------------------
            if (!empty($save)) {

                $this->db
                    ->where('id', $exist->id)
                    ->update('siswa', $save);
            }

            $siswa_id = $exist->id;

            $update++;

        } else {

            // =================================================
            // INSERT SISWA BARU
            // =================================================

            foreach ($excelData as $field => $value) {

                if ($field === 'id_kelas') {
                    continue;
                }

                if ($field === 'tahun_id') {
                    continue;
                }

                if ($field === 'status') {
                    continue;
                }

                $save[$field] =
                    ($value === '')
                        ? null
                        : $value;
            }

            // -------------------------------------------------
            // Validasi field wajib
            // -------------------------------------------------
            foreach ($requiredInsert as $required) {

                if (
                    !isset($save[$required]) ||
                    $save[$required] === null ||
                    trim((string) $save[$required]) === ''
                ) {

                    $gagal[] =
                        "Baris $rowIdx: " .
                        strtoupper($required) .
                        " wajib diisi.";

                    continue 2;
                }
            }

            // -------------------------------------------------
            // Map Kelas
            // -------------------------------------------------
            $kelasRaw =
                trim(
                    $excelData['id_kelas'] ?? ''
                );

            if ($kelasRaw !== '') {

                if (ctype_digit($kelasRaw)) {

                    if (
                        isset($kelasById[$kelasRaw])
                    ) {

                        $save['id_kelas'] =
                            $kelasById[$kelasRaw];

                    } else {

                        $gagal[] =
                            "Baris $rowIdx: Kelas tidak ditemukan ('$kelasRaw').";

                        continue;
                    }

                } else {

                    $kelasNorm =
                        $normalize($kelasRaw);

                    if (
                        isset($kelasByName[$kelasNorm])
                    ) {

                        $save['id_kelas'] =
                            $kelasByName[$kelasNorm];

                    } else {

                        $gagal[] =
                            "Baris $rowIdx: Kelas tidak valid ('$kelasRaw').";

                        continue;
                    }
                }

            } else {

                $save['id_kelas'] = null;
            }

            // -------------------------------------------------
            // Map Tahun Ajaran
            // -------------------------------------------------
            $tahunRaw =
                trim(
                    $excelData['tahun_id'] ?? ''
                );

            if ($tahunRaw !== '') {

                if (ctype_digit($tahunRaw)) {

                    if (
                        isset($tahunById[$tahunRaw])
                    ) {

                        $save['tahun_id'] =
                            $tahunById[$tahunRaw];

                    } else {

                        $gagal[] =
                            "Baris $rowIdx: ID tahun ajaran tidak ditemukan ('$tahunRaw').";

                        continue;
                    }

                } else {

                    $tahunNorm =
                        $normalize($tahunRaw);

                    if (
                        isset($tahunByValue[$tahunNorm])
                    ) {

                        $save['tahun_id'] =
                            $tahunByValue[$tahunNorm];

                    } else {

                        $gagal[] =
                            "Baris $rowIdx: Tahun ajaran tidak valid ('$tahunRaw').";

                        continue;
                    }
                }

            } else {

                // Excel kosong → tahun aktif
                if ($tahunAktif) {

                    $save['tahun_id'] =
                        $tahunAktif->id;

                } else {

                    $gagal[] =
                        "Baris $rowIdx: Tahun ajaran kosong dan tidak ada tahun ajaran aktif.";

                    continue;
                }
            }

            // -------------------------------------------------
            // Status
            // -------------------------------------------------
            $statusExcel =
                trim(
                    $excelData['status'] ?? ''
                );

            $allowedStatus = [
                'aktif',
                'mutasi_keluar',
                'mutasi_masuk',
                'lulus',
                'keluar'
            ];

            if ($statusExcel !== '') {

                if (
                    !in_array(
                        $statusExcel,
                        $allowedStatus,
                        true
                    )
                ) {

                    $gagal[] =
                        "Baris $rowIdx: Status tidak valid ('$statusExcel').";

                    continue;
                }

                $save['status'] =
                    $statusExcel;

            } else {

                $save['status'] =
                    'aktif';
            }

            // -------------------------------------------------
            // created_at
            // -------------------------------------------------
            $save['created_at'] =
                date('Y-m-d H:i:s');

            // -------------------------------------------------
            // Insert
            // -------------------------------------------------
            $this->db->insert(
                'siswa',
                $save
            );

            $siswa_id =
                $this->db->insert_id();

            if (!$siswa_id) {

                $gagal[] =
                    "Baris $rowIdx: Gagal menyimpan siswa.";

                continue;
            }

            $insert++;
        }

        // ====================================================
        // NORMALIZE JK
        // ====================================================
        if (
            isset($save['jk']) &&
            $save['jk'] !== null &&
            $save['jk'] !== ''
        ) {

            $jk =
                strtoupper(
                    trim((string) $save['jk'])
                );

            if (
                $jk === 'P' ||
                $jk === 'PEREMPUAN'
            ) {

                $jk = 'P';

            } else {

                $jk = 'L';
            }

            $this->db
                ->where('id', $siswa_id)
                ->update('siswa', [
                    'jk' => $jk
                ]);
        }

        // ====================================================
        // QR CODE
        // ====================================================
        require_once APPPATH .
            'libraries/phpqrcode/qrlib.php';

        $qr_folder =
            FCPATH . 'uploads/qr/';

        if (!file_exists($qr_folder)) {

            mkdir(
                $qr_folder,
                0777,
                true
            );
        }

        $qr_file =
            $qr_folder . $nisn . '.png';

        if (!file_exists($qr_file)) {

            QRcode::png(
                $nisn,
                $qr_file,
                QR_ECLEVEL_H,
                10,
                2
            );
        }

        // Simpan token QR = NISN
        $this->db
            ->where('id', $siswa_id)
            ->update('siswa', [
                'token_qr' => $nisn
            ]);

        // ====================================================
        // SINKRON SISWA_TAHUN
        // ====================================================
        $siswaFinal =
            $this->db
                ->where('id', $siswa_id)
                ->get('siswa')
                ->row();

        if ($siswaFinal) {

            $kelas_id =
                $siswaFinal->id_kelas;

            $tahun_id =
                $siswaFinal->tahun_id;

            // -----------------------------------------------
            // Hanya sinkron kalau keduanya valid
            // -----------------------------------------------
            if (
                !empty($kelas_id) &&
                !empty($tahun_id)
            ) {

                $st =
                    $this->db
                        ->where(
                            'siswa_id',
                            $siswa_id
                        )
                        ->where(
                            'tahun_id',
                            $tahun_id
                        )
                        ->get('siswa_tahun')
                        ->row();

                if ($st) {

                    $this->db
                        ->where(
                            'id',
                            $st->id
                        )
                        ->update(
                            'siswa_tahun',
                            [
                                'kelas_id' => $kelas_id,
                                'status'   => 'aktif'
                            ]
                        );

                } else {

                    $this->db
                        ->insert(
                            'siswa_tahun',
                            [
                                'siswa_id' => $siswa_id,
                                'kelas_id' => $kelas_id,
                                'tahun_id' => $tahun_id,
                                'status'   => 'aktif'
                            ]
                        );
                }
            }
        }
    }

    // ========================================================
    // HASIL IMPORT
    // ========================================================
    $totalGagal = count($gagal);

    if ($totalGagal > 0) {

        $message =
            '<b>Import selesai dengan catatan.</b><br><br>' .
            'Insert: <b>' . $insert . '</b><br>' .
            'Update: <b>' . $update . '</b><br>' .
            'Gagal: <b>' . $totalGagal . '</b><br><br>' .
            '<ul>';

        foreach ($gagal as $error) {

            $message .=
                '<li>' . $error . '</li>';
        }

        $message .= '</ul>';

        $this->session->set_flashdata(
            'error',
            $message
        );

    } else {

        $this->session->set_flashdata(
            'success',
            'Import berhasil. ' .
            'Insert: <b>' . $insert . '</b>, ' .
            'Update: <b>' . $update . '</b>.'
        );
    }

    redirect('siswa');
}


// ============================================================
// IMPORT FOTO SISWA VIA ZIP
// NAMA FILE = NISN
// Contoh:
// 00012292.jpg
// 00012293.png
// 00012294.jpeg
// ============================================================
public function import_foto_zip()
{
    // ========================================================
    // VALIDASI FILE
    // ========================================================
    if (
        !isset($_FILES['file']) ||
        empty($_FILES['file']['name']) ||
        $_FILES['file']['error'] !== UPLOAD_ERR_OK
    ) {

        $this->session->set_flashdata(
            'error',
            'File ZIP tidak ditemukan atau gagal diupload.'
        );

        redirect('siswa');
        return;
    }

    // ========================================================
    // VALIDASI EXTENSION
    // ========================================================
    $originalName = $_FILES['file']['name'];
    $extension    = strtolower(
        pathinfo($originalName, PATHINFO_EXTENSION)
    );

    if ($extension !== 'zip') {

        $this->session->set_flashdata(
            'error',
            'File yang diupload harus berformat ZIP.'
        );

        redirect('siswa');
        return;
    }

    // ========================================================
    // VALIDASI ZIP
    // ========================================================
    if (!class_exists('ZipArchive')) {

        $this->session->set_flashdata(
            'error',
            'PHP ZipArchive tidak tersedia di server.'
        );

        redirect('siswa');
        return;
    }

    $zip = new ZipArchive();

    if (
        $zip->open($_FILES['file']['tmp_name'])
        !== true
    ) {

        $this->session->set_flashdata(
            'error',
            'File ZIP tidak dapat dibuka atau rusak.'
        );

        redirect('siswa');
        return;
    }

    // ========================================================
    // FOLDER FOTO
    // ========================================================
    $fotoFolder = FCPATH . 'uploads/foto/';

    if (!is_dir($fotoFolder)) {

        if (!mkdir($fotoFolder, 0777, true)) {

            $zip->close();

            $this->session->set_flashdata(
                'error',
                'Folder uploads/foto tidak dapat dibuat.'
            );

            redirect('siswa');
            return;
        }
    }

    // ========================================================
    // COUNTER
    // ========================================================
    $berhasil       = 0;
    $tidakDitemukan = 0;
    $fileTidakValid = 0;
    $gagal          = 0;

    $detailTidakDitemukan = [];
    $detailTidakValid     = [];
    $detailGagal          = [];

    // ========================================================
    // LOOP FILE ZIP
    // ========================================================
    for ($i = 0; $i < $zip->numFiles; $i++) {

        $entryName = $zip->getNameIndex($i);

        if ($entryName === false) {
            continue;
        }

        // ----------------------------------------------------
        // Normalisasi path
        // ----------------------------------------------------
        $entryName = str_replace('\\', '/', $entryName);

        // Abaikan folder
        if (substr($entryName, -1) === '/') {
            continue;
        }

        // Ambil nama file saja
        $fileName = basename($entryName);

        // ----------------------------------------------------
        // Abaikan file tersembunyi / file sistem
        // ----------------------------------------------------
        if (
            $fileName === '' ||
            $fileName === '.' ||
            $fileName === '..' ||
            stripos($fileName, '__MACOSX') !== false ||
            stripos($fileName, '.DS_Store') !== false
        ) {
            continue;
        }

        // ====================================================
        // EXTENSION
        // ====================================================
        $ext = strtolower(
            pathinfo($fileName, PATHINFO_EXTENSION)
        );

        if (
            !in_array(
                $ext,
                ['jpg', 'jpeg', 'png'],
                true
            )
        ) {

            $fileTidakValid++;

            $detailTidakValid[] =
                $fileName . ' — format tidak didukung.';

            continue;
        }

        // ====================================================
        // AMBIL NISN DARI NAMA FILE
        // ====================================================
        $nisn = pathinfo(
            $fileName,
            PATHINFO_FILENAME
        );

        $nisn = trim($nisn);

        // ----------------------------------------------------
        // NISN hanya angka
        // ----------------------------------------------------
        if (
            $nisn === '' ||
            !preg_match('/^\d+$/', $nisn)
        ) {

            $fileTidakValid++;

            $detailTidakValid[] =
                $fileName . ' — nama file bukan NISN yang valid.';

            continue;
        }

        // ====================================================
        // CARI SISWA BERDASARKAN NISN
        // ====================================================
        $siswa = $this->db
            ->where('nisn', $nisn)
            ->get('siswa')
            ->row();

        if (!$siswa) {

            $tidakDitemukan++;

            $detailTidakDitemukan[] =
                $fileName . ' — NISN tidak ditemukan.';

            continue;
        }

        // ====================================================
        // AMBIL DATA FILE DARI ZIP
        // ====================================================
        $fileContent = $zip->getFromIndex($i);

        if ($fileContent === false) {

            $gagal++;

            $detailGagal[] =
                $fileName . ' — gagal membaca isi file ZIP.';

            continue;
        }

        // ====================================================
        // VALIDASI GAMBAR
        // ====================================================
        $imageInfo = @getimagesizefromstring($fileContent);

        if ($imageInfo === false) {

            $fileTidakValid++;

            $detailTidakValid[] =
                $fileName . ' — file bukan gambar yang valid.';

            continue;
        }

        // ====================================================
        // VALIDASI MIME
        // ====================================================
        $allowedMime = [
            'image/jpeg',
            'image/png'
        ];

        if (
            !in_array(
                $imageInfo['mime'],
                $allowedMime,
                true
            )
        ) {

            $fileTidakValid++;

            $detailTidakValid[] =
                $fileName . ' — MIME gambar tidak didukung.';

            continue;
        }

        // ====================================================
        // NAMA FILE BARU
        // ====================================================
        //
        // Kita simpan dengan nama:
        //
        // 00012292.jpg
        //
        // sehingga gampang dilacak berdasarkan NISN.
        //
        $newFileName = $nisn . '.' . $ext;

        $destination =
            $fotoFolder . $newFileName;

        // ====================================================
        // HAPUS FOTO LAMA
        // ====================================================
        if (
            !empty($siswa->foto) &&
            $siswa->foto !== $newFileName
        ) {

            $oldFile =
                $fotoFolder . basename($siswa->foto);

            if (
                file_exists($oldFile) &&
                is_file($oldFile)
            ) {

                @unlink($oldFile);
            }
        }

        // ====================================================
        // SIMPAN FILE
        // ====================================================
        if (
            file_put_contents(
                $destination,
                $fileContent
            ) === false
        ) {

            $gagal++;

            $detailGagal[] =
                $fileName . ' — gagal menyimpan file.';

            continue;
        }

        // ====================================================
        // UPDATE DATABASE
        // ====================================================
        $update = $this->db
            ->where('id', $siswa->id)
            ->update(
                'siswa',
                [
                    'foto' => $newFileName
                ]
            );

        if (!$update) {

            // Kalau database gagal, hapus file yang baru ditulis
            if (
                file_exists($destination)
            ) {

                @unlink($destination);
            }

            $gagal++;

            $detailGagal[] =
                $fileName . ' — gagal memperbarui database.';

            continue;
        }

        // ====================================================
        // BERHASIL
        // ====================================================
        $berhasil++;
    }

    // ========================================================
    // TUTUP ZIP
    // ========================================================
    $zip->close();

    // ========================================================
    // HASIL IMPORT
    // ========================================================
    $totalDiproses =
        $berhasil +
        $tidakDitemukan +
        $fileTidakValid +
        $gagal;

    $message =
        '<b>Import foto selesai.</b><br><br>' .

        'Berhasil: <b>' .
        $berhasil .
        '</b><br>' .

        'NISN tidak ditemukan: <b>' .
        $tidakDitemukan .
        '</b><br>' .

        'File tidak valid: <b>' .
        $fileTidakValid .
        '</b><br>' .

        'Gagal: <b>' .
        $gagal .
        '</b><br>';

    // ========================================================
    // DETAIL ERROR
    // ========================================================

    if (!empty($detailTidakDitemukan)) {

        $message .=
            '<br><b>NISN tidak ditemukan:</b><ul>';

        foreach (
            $detailTidakDitemukan
            as $item
        ) {

            $message .=
                '<li>' . html_escape($item) . '</li>';
        }

        $message .= '</ul>';
    }

    if (!empty($detailTidakValid)) {

        $message .=
            '<br><b>File tidak valid:</b><ul>';

        foreach (
            $detailTidakValid
            as $item
        ) {

            $message .=
                '<li>' . html_escape($item) . '</li>';
        }

        $message .= '</ul>';
    }

    if (!empty($detailGagal)) {

        $message .=
            '<br><b>File gagal diproses:</b><ul>';

        foreach (
            $detailGagal
            as $item
        ) {

            $message .=
                '<li>' . html_escape($item) . '</li>';
        }

        $message .= '</ul>';
    }

    // ========================================================
    // FLASH MESSAGE
    // ========================================================
    if ($berhasil > 0) {

        $this->session->set_flashdata(
            'success',
            $message
        );

    } else {

        $this->session->set_flashdata(
            'error',
            $message
        );
    }

    redirect('siswa');
}

public function cetak($id)
{
    // Ambil data siswa
    $data['siswa'] = $this->db
        ->select('siswa.*, kelas.nama AS nama_kelas, tahun_ajaran.tahun AS tahun_ajaran')
        ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
        ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.tahun_id', 'left')
        ->where('siswa.id', $id)
        ->get('siswa')
        ->row();

    if (!$data['siswa']) {
        show_error('Data siswa tidak ditemukan.');
    }

    // Tambahkan ini
    $data['sekolah'] = $this->db->get('sekolah')->row();

    $html = $this->load->view('siswa/cetak', $data, TRUE);

    $this->load->library('pdf');

    $pdf = new Tcpdf('P', PDF_UNIT, 'A4', true, 'UTF-8', false);

    $pdf->SetCreator('Sistem Mutasi');
    $pdf->SetAuthor('Sekolah');
    $pdf->SetTitle('Data Siswa');

    $pdf->SetMargins(10,10,10);
    $pdf->SetHeaderMargin(0);
    $pdf->SetFooterMargin(0);
    $pdf->SetAutoPageBreak(TRUE,10);

    $pdf->AddPage();
    $pdf->writeHTML($html, true, false, true, false, '');

    $fileName = 'Data_Siswa_'.str_replace(' ','_',$data['siswa']->nama).'.pdf';

    $mode = $this->input->get('download') ? 'D' : 'I';
    $pdf->Output($fileName,$mode);
    exit;
}
public function cetak_semua()
{
    // Ambil filter dari URL
    $kelas_id = $this->input->get('kelas');
    $search   = $this->input->get('search');

    // Load library ZIP & Idcard_lib
    $this->load->library('zip');
    $this->load->library('Idcard_lib');

    // Ambil data siswa sesuai filter
    $this->db->select('siswa.*, kelas.nama AS nama_kelas');
    $this->db->from('siswa');
    $this->db->join('kelas', 'kelas.id = siswa.id_kelas', 'left');
    $this->db->where('siswa.status', 'aktif');

    if (!empty($kelas_id)) {
        $this->db->where('siswa.id_kelas', $kelas_id);
    }

    if (!empty($search)) {
        $this->db->group_start();
        $this->db->like('siswa.nama', $search);
        $this->db->or_like('siswa.nis', $search);
        $this->db->or_like('siswa.nisn', $search);
        $this->db->group_end();
    }

    $query = $this->db->get();
    $siswa_list = $query->result();

    if (!$siswa_list) {
        $this->session->set_flashdata('error', 'Tidak ada data siswa sesuai filter.');
        redirect('siswa');
    }

    // =====================================================================
    // ZIP generator per folder kelas
    // =====================================================================
    $folder_zip = [];

    foreach ($siswa_list as $s) {

        // Generate JPG dari library
        $jpgData = $this->idcard_lib->generate($s->id);
        if (!$jpgData) continue;

        // Tentukan nama folder kelas
        $kelas_folder = !empty($s->nama_kelas) ? $s->nama_kelas : "Tanpa_Kelas";

        // Nama file idcard
        $safeName = strtolower(str_replace(" ", "_", $s->nama));
        $fileName = "idcard_" . $safeName . ".jpg";

        // Masukkan ke folder kelas
        $this->zip->add_data($kelas_folder . "/" . $fileName, $jpgData);
    }

    // Nama file ZIP hasil download
    $zipName = "idcard_filtered_" . date("Ymd_His") . ".zip";

    // Download ZIP
    $this->zip->download($zipName);
}
public function surat_pernyataan($id)
{
    // Ambil data siswa
    $data['siswa'] = $this->db
        ->select('siswa.*, kelas.nama AS nama_kelas, tahun_ajaran.tahun AS tahun_ajaran')
        ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
        ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.tahun_id', 'left')
        ->where('siswa.id', $id)
        ->get('siswa')
        ->row();

    if (!$data['siswa']) {
        show_error('Data siswa tidak ditemukan');
    }

    // Load helper tanggal
    $this->load->helper('tanggal');

    // Render HTML surat pernyataan
    $html = $this->load->view('siswa/cetak_surat_pernyataan', $data, TRUE);

    // Load TCPDF
    $this->load->library('pdf');

    /**
     * ===========================
     * SETUP PDF (AMBIL REFERENSI DARI cetak())
     * ===========================
     */

    // PILIH SALAH SATU:
    // LETTER (8.5 x 11)
    $pdf = new Tcpdf('P', 'mm', array(215.9, 279.4), true, 'UTF-8', false);


    // F4 (kalau mau)
    // $pdf = new Tcpdf('P', 'mm', array(210, 330), true, 'UTF-8', false);

    // Info PDF
    $pdf->SetCreator('Sistem Mutasi');
    $pdf->SetAuthor('Sekolah');
    $pdf->SetTitle('Surat Pernyataan Orang Tua');
    $pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

    // Margin (PENTING biar tidak melebar)
    $pdf->SetMargins(28, 10, 25);
    $pdf->SetHeaderMargin(0);
    $pdf->SetFooterMargin(0);

    // Auto page break
    $pdf->SetAutoPageBreak(false, 0);

    // Tambah halaman
    $pdf->AddPage();

    // Tulis HTML
    $pdf->writeHTML($html, true, false, true, false, '');

    // Output
    $fileName = 'cetak_surat_Pernyataan_' . str_replace(' ', '_', $data['siswa']->nama) . '.pdf';
    $pdf->Output($fileName, 'I');
}

}
