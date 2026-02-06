<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class WakakurLaporanPembelajaran extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        is_logged_in();

        // 🔐 HANYA WAKAKUR
        if ($this->session->userdata('role_id') != 4) {
            show_error('Akses ditolak', 403);
        }

        $this->load->model('Laporan_pembelajaran_siswa_model');
        $this->load->library('pdf');
    }

    public function index()
    {
        $data['title']  = 'Report Siswa (Kurikulum)';
        $data['active'] = 'wakakur_report_siswa';

        // ===== FILTER =====
        $filter = [
            'kelas_id' => $this->input->get('kelas_id'),
            'guru_id'  => $this->input->get('guru_id'),
            'tanggal'  => $this->input->get('tanggal')
        ];

        $data['laporan'] = $this->Laporan_pembelajaran_siswa_model->get_all($filter);

        // dropdown master
        $data['kelas'] = $this->db->order_by('nama','ASC')->get('kelas')->result();
        $data['guru']  = $this->db->order_by('nama','ASC')->get('guru')->result();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_guru', $data);
        $this->load->view('wakakur/laporan_pembelajaran_siswa', $data);
        $this->load->view('templates/footer');
    }

    // ================= EXPORT EXCEL =================
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
        $sheet->setTitle('Report Jam Kosong');

        $header = [
            'A1'=>'Tanggal','B1'=>'Kelas','C1'=>'Ketua Kelas','D1'=>'Hari',
            'E1'=>'Jam','F1'=>'Mapel','G1'=>'Guru','H1'=>'Alasan'
        ];

        foreach ($header as $cell=>$text) {
            $sheet->setCellValue($cell,$text);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getColumnDimension(substr($cell,0,1))->setAutoSize(true);
        }

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
        header('Content-Disposition: attachment; filename="Report_Jam_Kosong_Siswa.xlsx"');
        header('Cache-Control: max-age=0');

        (new Xlsx($excel))->save('php://output');
        exit;
    }

    // ================= EXPORT PDF =================
    public function export_pdf()
    {
        $filter = [
            'kelas_id' => $this->input->get('kelas_id'),
            'guru_id'  => $this->input->get('guru_id'),
            'tanggal'  => $this->input->get('tanggal')
        ];

        $data['laporan'] = $this->Laporan_pembelajaran_siswa_model->get_all($filter);

        $this->pdf->SetCreator('Sistem Akademik');
        $this->pdf->SetAuthor('Waka Kurikulum');
        $this->pdf->SetTitle('Report Jam Kosong Siswa');

        $this->pdf->SetFont('helvetica', '', 9);
        $this->pdf->SetMargins(15, 12, 15);
        $this->pdf->SetHeaderMargin(0);
        $this->pdf->SetFooterMargin(0);
        $this->pdf->SetAutoPageBreak(TRUE, 12);

        $this->pdf->AddPage('L');

        $html = $this->load->view(
            'wakakur/laporan_pembelajaran_pdf',
            $data,
            true
        );

        $this->pdf->writeHTML($html, true, false, true, false, '');
        $this->pdf->Output('Report_Jam_Kosong_Siswa.pdf', 'I');
    }
}
