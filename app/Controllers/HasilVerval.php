<?php

namespace App\Controllers;

use App\Models\Vervalpd_model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use TCPDF;

class HasilVerval extends BaseController
{
    protected Vervalpd_model $vervalModel;

    public function __construct()
    {
        $this->vervalModel = new Vervalpd_model();
    }

    private function checkWalikelas(): ?object
    {
        $this->requireAuth();

        $guruId = $this->session->get('guru_id');
        if (!$guruId) {
            header('Location: ' . site_url('guru_dashboard'));
            exit;
        }

        $kelas = $this->db->table('kelas')->where('wali_kelas_id', $guruId)->get()->getRow();
        if (!$kelas) {
            header('Location: ' . site_url('guru_dashboard'));
            exit;
        }

        $this->session->set([
            'kelas_id'   => (int) $kelas->id,
            'kelas_nama' => $kelas->nama,
        ]);

        return $kelas;
    }

    public function index()
    {
        $kelas = $this->checkWalikelas();
        $kelasId = (int) $kelas->id;

        $data = [
            'title'    => 'Hasil Verval PD',
            'active'   => 'wk_vervalpd',
            'laporan'  => $this->vervalModel->laporan_walikelas($kelasId),
            'siswa'    => $this->vervalModel->siswa_walikelas($kelasId),
        ];

        return $this->compatView->render('walikelas/templates/header', $data)
            . $this->compatView->render('walikelas/templates/sidebar', $data)
            . $this->compatView->render('walikelas/hasil_verval/index', $data)
            . $this->compatView->render('walikelas/templates/footer');
    }

    public function export_excel()
    {
        $kelas = $this->checkWalikelas();
        $kelasId = (int) $kelas->id;
        $kelasNama = $kelas->nama;

        $siswa = $this->vervalModel->siswa_walikelas($kelasId);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Hasil Verval');

        $sheet->setCellValue('A1', 'Kelas');
        $sheet->setCellValue('B1', $kelasNama);

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'NISN');
        $sheet->setCellValue('C3', 'Nama');
        $sheet->setCellValue('D3', 'Status');
        $sheet->setCellValue('E3', 'Catatan');

        $sheet->getStyle('A3:E3')->getFont()->setBold(true);

        $row = 4;
        $no  = 1;
        foreach ($siswa as $s) {
            if ($s->status_verval == 1) {
                $status = 'Valid';
            } elseif ($s->status_verval == 2) {
                $status = 'Perbaikan';
            } else {
                $status = 'Belum';
            }

            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", (string) $s->nisn);
            $sheet->setCellValue("C{$row}", $s->nama);
            $sheet->setCellValue("D{$row}", $status);
            $sheet->setCellValue("E{$row}", $s->catatan_verval);
            $row++;
        }

        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Hasil_Verval_' . str_replace(' ', '_', $kelasNama) . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $kelas = $this->checkWalikelas();
        $kelasId = (int) $kelas->id;
        $kelasNama = $kelas->nama;

        $data = [
            'kelas_nama' => $kelasNama,
            'siswa'      => $this->vervalModel->siswa_walikelas($kelasId),
        ];

        $html = $this->compatView->render('walikelas/hasil_verval/cetakpdf', $data);

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();
        $pdf->writeHTML($html);

        $filename = 'Hasil_Verval_' . preg_replace('/\s+/', '_', $kelasNama) . '.pdf';
        $pdf->Output($filename, 'I');
        exit;
    }
}
