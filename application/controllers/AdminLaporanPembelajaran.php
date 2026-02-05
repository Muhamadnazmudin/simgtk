<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminLaporanPembelajaran extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        is_logged_in();

        // hanya ADMIN
        if ($this->session->userdata('role_name') != 'admin') {
            show_error('Akses ditolak',403);
        }

        $this->load->model('Laporan_pembelajaran_siswa_model');
        $this->load->library('pdf');
    }

    public function index()
    {
        $data['title']  = 'Laporan Jam Kosong Siswa';
        $data['active'] = 'laporan_pembelajaran_siswa';

        // ===== FILTER =====
        $filter = [
            'kelas_id' => $this->input->get('kelas_id'),
            'guru_id'  => $this->input->get('guru_id'),
            'tanggal'  => $this->input->get('tanggal')
        ];

        $data['laporan'] = $this->Laporan_pembelajaran_siswa_model->get_all($filter);

        // master dropdown
        $data['kelas'] = $this->db->order_by('nama','ASC')->get('kelas')->result();
        $data['guru']  = $this->db->order_by('nama','ASC')->get('guru')->result();

        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('admin/laporan_pembelajaran_siswa',$data);
        $this->load->view('templates/footer');
    }
    public function export_excel()
{
    $filter = [
        'kelas_id' => $this->input->get('kelas_id'),
        'guru_id'  => $this->input->get('guru_id'),
        'tanggal'  => $this->input->get('tanggal')
    ];

    $data = $this->Laporan_pembelajaran_siswa_model->get_all($filter);

    $excel = new Spreadsheet();
    $sheet = $excel->getActiveSheet();
    $sheet->setTitle('Laporan Jam Kosong');

    // HEADER
    $header = [
        'A1'=>'Tanggal','B1'=>'Kelas','C1'=>'Ketua Kelas','D1'=>'Hari',
        'E1'=>'Jam','F1'=>'Mapel','G1'=>'Guru','H1'=>'Alasan'
    ];

    foreach ($header as $cell=>$text) {
        $sheet->setCellValue($cell,$text);
        $sheet->getStyle($cell)->getFont()->setBold(true);
        $sheet->getColumnDimension(substr($cell,0,1))->setAutoSize(true);
    }

    // DATA
    $row = 2;
    foreach ($data as $d) {
        $sheet->setCellValue("A$row",$d->tanggal);
        $sheet->setCellValue("B$row",$d->nama_kelas);
        $sheet->setCellValue("C$row",$d->nama_siswa);
        $sheet->setCellValue("D$row",$d->hari);
        $sheet->setCellValue(
            "E$row",
            "{$d->jam_awal} ({$d->jam_mulai}) - {$d->jam_akhir} ({$d->jam_selesai})"
        );
        $sheet->setCellValue("F$row",$d->nama_mapel);
        $sheet->setCellValue("G$row",$d->nama_guru);
        $sheet->setCellValue("H$row",$d->alasan);
        $row++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Laporan_Jam_Kosong_Siswa.xlsx"');
    header('Cache-Control: max-age=0');

    (new Xlsx($excel))->save('php://output');
    exit;
}
public function export_pdf()
{
    $filter = [
        'kelas_id' => $this->input->get('kelas_id'),
        'guru_id'  => $this->input->get('guru_id'),
        'tanggal'  => $this->input->get('tanggal')
    ];

    $data['laporan'] = $this->Laporan_pembelajaran_siswa_model->get_all($filter);

    // ===== TCPDF CONFIG =====
    $this->pdf->SetTitle('Laporan Jam Kosong Siswa');
    $this->pdf->SetMargins(10, 10, 10);
    $this->pdf->AddPage('P');

    $html = $this->load->view(
        'admin/laporan_pembelajaran_pdf',
        $data,
        true
    );

    $this->pdf->writeHTML($html, true, false, true, false, '');
    $this->pdf->Output('Laporan_Jam_Kosong_Siswa.pdf', 'I');
}

}
