<?php

namespace App\Controllers;

use App\Models\LaporanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use TCPDF;

class Laporan extends BaseController
{
    protected LaporanModel $laporanModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
    }

    public function index()
    {
        $this->requireRole([1, 2]);

        $tahunId = $this->tahunAktifId();
        $tahunRow = $this->db->table('tahun_ajaran')->where('id', $tahunId)->get()->getRow();
        $tahunLabel = $tahunRow ? $tahunRow->tahun : '-';

        $kelas  = $this->request->getGet('kelas');
        $jenis  = $this->request->getGet('jenis');
        $search = $this->request->getGet('search');

        $data = [
            'judul'      => 'Laporan Mutasi Siswa',
            'active'     => 'laporan',
            'tahun'      => $tahunLabel,
            'kelas_list' => $this->laporanModel->getKelas(),
            'mutasi'     => $this->laporanModel->getLaporan($tahunId, $kelas, $jenis, $search),
        ];

        return $this->renderView('laporan/index', $data);
    }

    public function export_pdf()
    {
        $this->requireRole([1, 2]);

        $tahunId = $this->tahunAktifId();
        $tahunRow = $this->db->table('tahun_ajaran')->where('id', $tahunId)->get()->getRow();
        $tahunLabel = $tahunRow ? $tahunRow->tahun : '-';

        $kelas  = $this->request->getGet('kelas');
        $jenis  = $this->request->getGet('jenis');
        $search = $this->request->getGet('search');

        $mutasi = $this->laporanModel->getLaporan($tahunId, $kelas, $jenis, $search);

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Laporan Mutasi Siswa ' . $tahunLabel);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage('L');

        $html = view('laporan/laporan_pdf', [
            'mutasi' => $mutasi,
            'tahun'  => $tahunLabel,
        ]);

        $pdf->writeHTML($html);
        $pdf->Output('Laporan_Mutasi_' . $tahunLabel . '.pdf', 'I');
        exit;
    }

    public function export_excel()
    {
        $this->requireRole([1, 2]);

        $tahunId = $this->tahunAktifId();
        $tahunRow = $this->db->table('tahun_ajaran')->where('id', $tahunId)->get()->getRow();
        $tahunLabel = $tahunRow ? $tahunRow->tahun : '-';

        $kelas  = $this->request->getGet('kelas');
        $jenis  = $this->request->getGet('jenis');
        $search = $this->request->getGet('search');

        $mutasi = $this->laporanModel->getLaporan($tahunId, $kelas, $jenis, $search);

        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->setTitle('Laporan Mutasi');

        $header = ['No', 'Tanggal', 'Jenis Mutasi', 'Nama Siswa', 'NIS', 'NISN', 'Kelas Asal', 'Kelas Tujuan', 'Sekolah Asal', 'Sekolah Tujuan', 'Alasan'];
        $sheet->fromArray($header, null, 'A1');

        $rowNum = 2;
        $no = 1;
        foreach ($mutasi as $r) {
            $sheet->setCellValue("A{$rowNum}", $no++);
            $sheet->setCellValue("B{$rowNum}", $r->tanggal ?? '-');
            $sheet->setCellValue("C{$rowNum}", ucfirst($r->jenis ?? '-'));
            $sheet->setCellValue("D{$rowNum}", $r->nama_siswa ?? '-');
            $sheet->setCellValue("E{$rowNum}", $r->nis ?? '-');
            $sheet->setCellValue("F{$rowNum}", $r->nisn ?? '-');
            $sheet->setCellValue("G{$rowNum}", $r->nama_kelas_asal ?? '-');
            $sheet->setCellValue("H{$rowNum}", $r->nama_kelas_tujuan ?? '-');
            $sheet->setCellValue("I{$rowNum}", $r->sekolah_asal ?? '-');
            $sheet->setCellValue("J{$rowNum}", $r->sekolah_tujuan ?? '-');
            $sheet->setCellValue("K{$rowNum}", $r->alasan ?? '-');
            $rowNum++;
        }

        $filename = 'Laporan_Mutasi_' . $tahunLabel . '_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($excel);
        $writer->save('php://output');
        exit;
    }
}
