<?php

namespace App\Controllers;

use App\Models\Laporan_guru_model;
use TCPDF;

class Laporan_guru extends BaseController
{
    protected Laporan_guru_model $laporanModel;

    public function __construct()
    {
        $this->laporanModel = new Laporan_guru_model();
        helper('tanggal');
    }

    private function checkGuru(): void
    {
        $this->requireAuth();
        $roleId = (int) $this->session->get('role_id');
        if (!in_array($roleId, [3, 4], true)) {
            header('Location: ' . site_url('dashboard'));
            exit;
        }
    }

    public function index()
    {
        $this->checkGuru();

        $data = [
            'title'  => 'Laporan Mengajar',
            'active' => 'laporan_guru',
        ];

        return $this->compatView->render('templates/header', $data)
            . $this->compatView->render('templates/sidebar_guru', $data)
            . $this->compatView->render('laporan_guru/index', $data)
            . $this->compatView->render('templates/footer');
    }

    private function hari_indo($hari)
    {
        $map = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        return $map[$hari] ?? $hari;
    }

    public function export_pdf()
    {
        $this->checkGuru();

        $bulan   = (int) $this->request->getGet('bulan');
        $tahun   = (int) $this->request->getGet('tahun');
        $guruId  = (int) $this->session->get('guru_id');

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $data = $this->laporanModel->get_laporan_bulanan($guruId, $bulan, $tahun);

        $guru = $this->db->table('guru')->where('id', $guruId)->get()->getRow();
        if (!$guru) {
            return $this->response->setBody('Data guru tidak ditemukan');
        }

        $sekolah = $this->db->table('sekolah')->get()->getRow();

        $namaMapel = '-';
        if (!empty($data['rekap'])) {
            $mapelUnik = [];
            foreach ($data['rekap'] as $r) {
                if (!empty($r->nama_mapel)) {
                    $mapelUnik[$r->nama_mapel] = true;
                }
            }
            $namaMapel = implode(' dan ', array_keys($mapelUnik));
        }

        /* ================= PDF ================= */
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();

        /* ================= COVER ================= */
        $pdf->SetAutoPageBreak(false);

        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'LAPORAN JURNAL KEGIATAN MENGAJAR GURU', 0, 1, 'C');
        $pdf->Ln(15);

        if (!empty($sekolah->logo) && file_exists(FCPATH . 'uploads/logo/' . $sekolah->logo)) {
            $pdf->Image(FCPATH . 'uploads/logo/' . $sekolah->logo, 85, $pdf->GetY(), 40);
        }

        $pdf->Ln(55);

        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 8, strtoupper($sekolah->nama_sekolah ?? 'NAMA SEKOLAH'), 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 8, 'Mata Pelajaran : ' . $namaMapel, 0, 1, 'C');
        $pdf->Ln(8);

        $pdf->Cell(0, 8, 'Nama Guru : ' . $guru->nama, 0, 1, 'C');
        $pdf->Cell(0, 8, 'NIP : ' . (!empty($guru->nip) ? $guru->nip : '-'), 0, 1, 'C');
        $pdf->Ln(8);
        $pdf->Cell(0, 8, 'Periode : ' . ($namaBulan[$bulan] ?? '') . ' ' . $tahun, 0, 1, 'C');

        $pdf->SetY(-95);
        $pdf->Ln(6);

        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 7, 'PEMERINTAH DAERAH PROVINSI JAWA BARAT', 0, 1, 'C');
        $pdf->Cell(0, 7, 'DINAS PENDIDIKAN', 0, 1, 'C');
        $pdf->Cell(0, 7, 'CABANG DINAS PENDIDIKAN WILAYAH X', 0, 1, 'C');
        $pdf->Ln(6);

        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 8, strtoupper($sekolah->nama_sekolah ?? 'NAMA SEKOLAH'), 0, 1, 'C');
        $pdf->Ln(3);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->MultiCell(
            0,
            6,
            ($sekolah->alamat ?? 'Alamat sekolah')
            . "\nEmail : smkn_1cilimus@yahoo.com"
            . "\nWebsite : smkn1cilimus.sch.id"
            . "\nKab. Kuningan 45556",
            0,
            'C'
        );

        /* ================= HALAMAN ISI ================= */
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->Cell(0, 8, 'JURNAL KEGIATAN MENGAJAR GURU', 0, 1, 'C');

        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 6, 'Periode : ' . ($namaBulan[$bulan] ?? '') . ' ' . $tahun, 0, 1, 'C');
        $pdf->Ln(6);

        $w = [10, 35, 25, 35, 30, 45];
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(230, 230, 230);

        $header = ['No', 'Hari / Tanggal', 'Kelas', 'Mapel', 'Jam', 'Materi'];
        foreach ($header as $i => $h) {
            $pdf->Cell($w[$i], 8, $h, 1, 0, 'C', true);
        }
        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 9);
        $no = 1;

        foreach ($data['rekap'] as $r) {
            $hariTanggal = $this->hari_indo($r->hari) . ', ' . tgl_indo_teks($r->tanggal);
            $jam = substr($r->jam_mulai, 0, 5) . ' – ' . substr($r->jam_selesai, 0, 5);

            $cells = [
                $no++,
                $hariTanggal,
                $r->nama_kelas,
                $r->nama_mapel,
                $jam,
                $r->materi ?: '-',
            ];

            $rowHeight = 8;
            foreach ($cells as $i => $txt) {
                $rowHeight = max($rowHeight, $pdf->getStringHeight($w[$i], $txt));
            }

            $x = $pdf->GetX();
            $y = $pdf->GetY();

            foreach ($w as $i => $width) {
                $pdf->Rect($x, $y, $width, $rowHeight);
                $x += $width;
            }

            $x = $pdf->GetX();
            foreach ($cells as $i => $txt) {
                $pdf->MultiCell($w[$i], $rowHeight, $txt, 0, 'L', false, 0, $x, $y);
                $x += $w[$i];
            }

            $pdf->Ln($rowHeight);
        }

        /* ================= TTD ================= */
        $pdf->Ln(15);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 6, 'Mengetahui,', 0, 1, 'C');
        $pdf->Ln(8);

        $xLeft  = 25;
        $xRight = 115;
        $yStart = $pdf->GetY();

        $pdf->SetXY($xLeft, $yStart);
        $pdf->Cell(70, 6, 'Kepala Sekolah', 0, 0, 'L');
        $pdf->SetXY($xRight, $yStart);
        $pdf->Cell(67, 6, 'Guru yang bersangkutan', 0, 1, 'R');
        $pdf->Ln(20);

        $yName = $pdf->GetY();
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetXY($xLeft, $yName);
        $pdf->Cell(70, 6, strtoupper($sekolah->nama_kepala_sekolah ?? '-'), 0, 0, 'L');
        $pdf->SetXY($xRight, $yName);
        $pdf->Cell(58, 6, strtoupper($guru->nama), 0, 1, 'R');

        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetXY($xLeft, $yName + 6);
        $pdf->Cell(70, 6, 'NIP. ' . (!empty($sekolah->nip_kepala_sekolah) ? $sekolah->nip_kepala_sekolah : '-'), 0, 0, 'L');
        $pdf->SetXY($xRight, $yName + 6);
        $pdf->Cell(70, 6, 'NIP. ' . (!empty($guru->nip) ? $guru->nip : '-'), 0, 1, 'R');

        /* ================= HALAMAN FOTO ================= */
        if (!empty($data['selfie'])) {
            $pdf->AddPage();
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 8, 'LAMPIRAN FOTO KEGIATAN MENGAJAR', 0, 1, 'C');
            $pdf->Ln(6);

            $fotoWidth = 90;
            $fotoGapY  = 65;
            $pageWidth = $pdf->getPageWidth();
            $xCenter   = ($pageWidth - $fotoWidth) / 2;
            $y         = $pdf->GetY();

            foreach ($data['selfie'] as $row) {
                $path = FCPATH . 'uploads/selfie/' . $row->selfie;
                if (!file_exists($path)) {
                    continue;
                }

                if ($y > 230) {
                    $pdf->AddPage();
                    $y = 30;
                }

                $pdf->Image($path, $xCenter, $y, $fotoWidth, 0);
                $pdf->SetXY(15, $y + 73);
                $pdf->SetFont('helvetica', '', 9);
                $pdf->Cell(0, 5, tgl_indo_teks($row->tanggal), 0, 1, 'C');

                $y += $fotoGapY;
            }
        }

        $pdf->Output('Jurnal_Mengajar_' . $bulan . '_' . $tahun . '.pdf', 'I');
        exit;
    }
}
