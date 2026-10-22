<?php

namespace App\Controllers;

use App\Models\LaporanPembelajaranSiswaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use TCPDF;

class WakakurLaporanPembelajaran extends BaseController
{
    protected LaporanPembelajaranSiswaModel $laporanModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanPembelajaranSiswaModel();
    }

    private function checkWakakur(): void
    {
        $this->requireAuth();
        $roleId = (int) $this->session->get('role_id');
        if ($roleId !== 4) {
            header('Location: ' . site_url('guru_dashboard'));
            exit;
        }
    }

    public function index()
    {
        $this->checkWakakur();

        $filter = [
            'kelas_id' => $this->request->getGet('kelas_id'),
            'guru_id'  => $this->request->getGet('guru_id'),
            'tanggal'  => $this->request->getGet('tanggal'),
        ];

        $data = [
            'title'   => 'Report Siswa (Kurikulum)',
            'active'  => 'wakakur_report_siswa',
            'laporan' => $this->laporanModel->getAll($filter),
            'kelas'   => $this->db->table('kelas')->orderBy('nama', 'ASC')->get()->getResult(),
            'guru'    => $this->db->table('guru')->orderBy('nama', 'ASC')->get()->getResult(),
        ];

        return $this->compatView->render('templates/header', $data)
            . $this->compatView->render('templates/sidebar_guru', $data)
            . $this->compatView->render('wakakur/laporan_pembelajaran_siswa', $data)
            . $this->compatView->render('templates/footer');
    }

    public function export_excel()
    {
        $this->checkWakakur();

        $filter = [
            'kelas_id' => $this->request->getGet('kelas_id'),
            'guru_id'  => $this->request->getGet('guru_id'),
            'tanggal'  => $this->request->getGet('tanggal'),
        ];

        $data = $this->laporanModel->getAll($filter);

        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->setTitle('Report Jam Kosong');

        $header = [
            'A1' => 'Tanggal', 'B1' => 'Kelas', 'C1' => 'Ketua Kelas', 'D1' => 'Hari',
            'E1' => 'Jam', 'F1' => 'Mapel', 'G1' => 'Guru', 'H1' => 'Alasan',
        ];

        foreach ($header as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getColumnDimension(substr($cell, 0, 1))->setAutoSize(true);
        }

        $row = 2;
        foreach ($data as $d) {
            $sheet->setCellValue("A$row", $d->tanggal);
            $sheet->setCellValue("B$row", $d->nama_kelas);
            $sheet->setCellValue("C$row", $d->nama_siswa);
            $sheet->setCellValue("D$row", $d->hari);
            $sheet->setCellValue(
                "E$row",
                "{$d->jam_awal} ({$d->jam_mulai}) - {$d->jam_akhir} ({$d->jam_selesai})"
            );
            $sheet->setCellValue("F$row", $d->nama_mapel);
            $sheet->setCellValue("G$row", $d->nama_guru);
            $sheet->setCellValue("H$row", $d->alasan);
            $row++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Report_Jam_Kosong_Siswa.xlsx"');
        header('Cache-Control: max-age=0');

        (new Xlsx($excel))->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $this->checkWakakur();

        $filter = [
            'kelas_id' => $this->request->getGet('kelas_id'),
            'guru_id'  => $this->request->getGet('guru_id'),
            'tanggal'  => $this->request->getGet('tanggal'),
        ];

        $data = [
            'laporan' => $this->laporanModel->getAll($filter),
        ];

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Sistem Akademik');
        $pdf->SetAuthor('Waka Kurikulum');
        $pdf->SetTitle('Report Jam Kosong Siswa');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetMargins(15, 12, 15);
        $pdf->SetAutoPageBreak(true, 12);
        $pdf->AddPage('L');

        $html = $this->compatView->render('wakakur/laporan_pembelajaran_pdf', $data);
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('Report_Jam_Kosong_Siswa.pdf', 'I');
        exit;
    }
}
