<?php

namespace App\Controllers;

use App\Models\LaporanMengajarModel;
use TCPDF;

class Laporan_mengajar extends BaseController
{
    protected LaporanMengajarModel $laporanModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanMengajarModel();
    }

    public function index()
    {
        $this->requireRole([1, 4]);

        $tanggalAwal  = $this->request->getGet('tanggal_awal');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir');
        $guruId       = $this->request->getGet('guru_id');

        $data = [
            'title'   => 'Laporan Mengajar Guru',
            'active'  => 'laporan_mengajar',
            'guru'    => $this->laporanModel->getGuru(),
            'laporan' => $this->laporanModel->getLaporanRange($tanggalAwal, $tanggalAkhir, $guruId),
        ];

        return $this->renderView('laporan_mengajar/index', $data);
    }

    public function export_pdf()
    {
        $this->requireRole([1, 4]);

        $tanggalAwal  = $this->request->getGet('tanggal_awal');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir');
        $guruId       = $this->request->getGet('guru_id');

        $laporan = $this->laporanModel->getLaporanRange($tanggalAwal, $tanggalAkhir, $guruId);

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('SimSGTK');
        $pdf->SetAuthor('SimSGTK');
        $pdf->SetTitle('Laporan Mengajar Guru');
        $pdf->SetMargins(10, 15, 10);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->Cell(0, 8, 'LAPORAN MENGAJAR GURU', 0, 1, 'C');
        $pdf->Ln(2);

        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 6, 'Periode: ' . ($tanggalAwal ?: '-') . ' s/d ' . ($tanggalAkhir ?: '-'), 0, 1, 'C');
        $pdf->Ln(6);

        $w = [
            'no'     => 8,
            'tgl'    => 22,
            'guru'   => 32,
            'kelas'  => 16,
            'mapel'  => 40,
            'masuk'  => 22,
            'keluar' => 22,
        ];

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(230, 230, 230);
        $header = ['No', 'Tanggal', 'Guru', 'Kelas', 'Mapel', 'Masuk', 'Keluar'];
        $i = 0;
        foreach ($w as $width) {
            $pdf->Cell($width, 8, $header[$i++], 1, 0, 'C', true);
        }
        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 9);
        $no = 1;
        foreach ($laporan as $row) {
            $tgl   = $row->tanggal ?? '-';
            $guru  = $row->nama_guru ?? '-';
            $kelas = $row->nama_kelas ?? '-';
            $mapel = $row->nama_mapel ?? '-';

            $jamMasuk  = !empty($row->jam_mulai) ? date('H:i', strtotime($row->jam_mulai)) : '-';
            $jamKeluar = !empty($row->jam_selesai) ? date('H:i', strtotime($row->jam_selesai)) : '-';

            $pdf->Cell($w['no'], 7, $no++, 1, 0, 'C');
            $pdf->Cell($w['tgl'], 7, $tgl, 1, 0, 'C');
            $pdf->Cell($w['guru'], 7, mb_strimwidth($guru, 0, 18, '..'), 1, 0, 'L');
            $pdf->Cell($w['kelas'], 7, $kelas, 1, 0, 'C');
            $pdf->Cell($w['mapel'], 7, mb_strimwidth($mapel, 0, 22, '..'), 1, 0, 'L');
            $pdf->Cell($w['masuk'], 7, $jamMasuk, 1, 0, 'C');
            $pdf->Cell($w['keluar'], 7, $jamKeluar, 1, 1, 'C');
        }

        $pdf->Output('laporan_mengajar.pdf', 'I');
        exit;
    }
}
