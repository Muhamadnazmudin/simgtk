<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Spreadsheet_Lib
{
    public function __construct()
    {
        // Composer Autoload sudah di index.php
    }

    // =====================================================================
    // 1. EXPORT LAPORAN MUTASI
    // =====================================================================
    public function export_laporan_mutasi($data, $tahun)
    {
        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->setTitle('Laporan Mutasi Siswa');

        // Header kolom
        $headers = [
            'No', 'Nama Siswa', 'NIS', 'NISN', 'Kelas Asal', 'Jenis',
            'Jenis Keluar', 'Tanggal', 'Alasan', 'No. HP Ortu',
            'Tujuan', 'Tahun Ajaran', 'Dibuat Oleh'
        ];

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $col++;
        }

        // Isi data
        $rowNum = 2;
        $no = 1;

        foreach ($data as $m) {
            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, $m->nama_siswa);
            $sheet->setCellValue('C' . $rowNum, $m->nis);
            $sheet->setCellValue('D' . $rowNum, $m->nisn);
            $sheet->setCellValue('E' . $rowNum, $m->kelas_asal);
            $sheet->setCellValue('F' . $rowNum, ucfirst($m->jenis));
            $sheet->setCellValue('G' . $rowNum, $m->jenis == 'keluar' ? ($m->jenis_keluar ?: '-') : '-');
            $sheet->setCellValue('H' . $rowNum, !empty($m->tanggal) ? date('d-m-Y', strtotime($m->tanggal)) : '-');
            $sheet->setCellValue('I' . $rowNum, $m->alasan);
            $sheet->setCellValue('J' . $rowNum, $m->nohp_ortu);
            $sheet->setCellValue('K' . $rowNum, $m->jenis == 'keluar' ? ($m->tujuan_sekolah ?: '-') : ($m->kelas_tujuan ?: '-'));
            $sheet->setCellValue('L' . $rowNum, $m->tahun_ajaran);
            $sheet->setCellValue('M' . $rowNum, $m->dibuat_oleh);

            $rowNum++;
        }

        // Styling header
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font'      => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allBorders' => ['style' => Border::BORDER_THIN]],
        ]);

        // Auto width
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="laporan_mutasi_' . $tahun . '.xls"');
        header('Cache-Control: max-age=0');

        $writer = new Xls($excel);
        $writer->save('php://output');
        exit();
    }

    // =====================================================================
    // 2. EXPORT SISWA PER KELAS
    // =====================================================================
    public function export_siswa_per_kelas($siswa, $kelas_nama)
    {
        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->setTitle('Siswa ' . $kelas_nama);

        // Header kolom
        $sheet->setCellValue('A1', 'No')
              ->setCellValue('B1', 'NIS')
              ->setCellValue('C1', 'Nama Siswa')
              ->setCellValue('D1', 'Jenis Kelamin')
              ->setCellValue('E1', 'Alamat');

        // Isi data
        $row = 2;
        $no  = 1;
        foreach ($siswa as $s) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $s->nis);
            $sheet->setCellValue('C' . $row, $s->nama);
            $sheet->setCellValue('D' . $row, $s->jk);
            $sheet->setCellValue('E' . $row, $s->alamat);
            $row++;
        }

        // Styling header
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font'      => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allBorders' => ['style' => Border::BORDER_THIN]],
        ]);

        // Auto width
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output
        $filename = 'Daftar_Siswa_' . str_replace(' ', '_', $kelas_nama) . '.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xls($excel);
        $writer->save('php://output');
        exit();
    }


    // =====================================================================
// 3. EXPORT REKAP NILAI RAPOR
// =====================================================================
public function export_rekap_nilai($rekap, $mapel)
{
    $excel = new Spreadsheet();
    $sheet = $excel->getActiveSheet();
    $sheet->setTitle('Rekap Nilai');

    // =====================================================
    // HEADER
    // =====================================================

    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'NISN');
    $sheet->setCellValue('C1', 'Nama Siswa');
    $sheet->setCellValue('D1', 'Kelas');

    $sheet->mergeCells('A1:A2');
    $sheet->mergeCells('B1:B2');
    $sheet->mergeCells('C1:C2');
    $sheet->mergeCells('D1:D2');

    $col = 5; // Kolom E

    foreach ($mapel as $m) {

        $start = $col;
        $end   = $col + 5;

        $sheet->mergeCellsByColumnAndRow($start, 1, $end, 1);
        $sheet->setCellValueByColumnAndRow($start, 1, $m->nama_mapel);

        for ($smt = 1; $smt <= 6; $smt++) {
            $sheet->setCellValueByColumnAndRow($col, 2, $smt);
            $col++;
        }
    }

    // =====================================================
    // DATA
    // =====================================================

    $row = 3;
    $no  = 1;

    foreach ($rekap as $r) {

        $sheet->setCellValueByColumnAndRow(1, $row, $no++);
        $sheet->setCellValueByColumnAndRow(2, $row, $r['nisn']);
        $sheet->setCellValueByColumnAndRow(3, $row, $r['nama']);
        $sheet->setCellValueByColumnAndRow(4, $row, $r['kelas']);

        $col = 5;

        foreach ($mapel as $m) {

            for ($smt = 1; $smt <= 6; $smt++) {

                $nilai = $r['nilai'][$m->id_mapel][$smt] ?? '';

                $sheet->setCellValueByColumnAndRow(
                    $col,
                    $row,
                    $nilai
                );

                $col++;
            }
        }

        $row++;
    }

    // =====================================================
    // STYLE
    // =====================================================

    $lastCol = $sheet->getHighestColumn();
    $lastRow = $sheet->getHighestRow();

    $sheet->getStyle("A1:{$lastCol}2")->applyFromArray([
        'font' => [
            'bold' => true
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical'   => Alignment::VERTICAL_CENTER
        ],
        'borders' => [
            'allBorders' => [
                'style' => Border::BORDER_THIN
            ]
        ]
    ]);

    $sheet->getStyle("A3:{$lastCol}{$lastRow}")
          ->getBorders()
          ->getAllBorders()
          ->setBorderStyle(Border::BORDER_THIN);

    foreach (range('A', $lastCol) as $c) {
        $sheet->getColumnDimension($c)->setAutoSize(true);
    }

    $sheet->freezePane('E3');

    // =====================================================
    // OUTPUT
    // =====================================================

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Rekap_Nilai_Rapor.xls"');
    header('Cache-Control: max-age=0');

    $writer = new Xls($excel);
    $writer->save('php://output');
    exit;
}


// =====================================================================
// 4. EXPORT REKAP ABSENSI MANUAL
// =====================================================================
public function export_absensi_manual(
    $kelas_nama,
    $dari,
    $sampai,
    $tanggal_per_bulan,
    $siswa,
    $arrAbsen,
    $hariMerah
)
{
    $spreadsheet = new Spreadsheet();

    $sheetIndex = 0;

    foreach ($tanggal_per_bulan as $bulanKey => $tglBulan) {

        if ($sheetIndex > 0) {
            $spreadsheet->createSheet();
        }

        $sheet = $spreadsheet->setActiveSheetIndex($sheetIndex);

        $sheet->setTitle(substr(date('F Y', strtotime($bulanKey.'-01')),0,31));

        // =====================================================
        // HEADER
        // =====================================================

        $sheet->setCellValue('A1','REKAP ABSENSI MANUAL');

        $sheet->mergeCells('A1:H1');

        $sheet->getStyle('A1')->getFont()
            ->setBold(true)
            ->setSize(15);

        $sheet->setCellValue(
            'A2',
            'Kelas : '.$kelas_nama
        );

        $sheet->setCellValue(
            'A3',
            'Periode : '
            .date('d-m-Y',strtotime($dari))
            .' s/d '.
            date('d-m-Y',strtotime($sampai))
        );

        $sheet->setCellValue(
            'A4',
            'Bulan : '.date('F Y',strtotime($bulanKey.'-01'))
        );

        // =====================================================
        // HEADER TABEL
        // =====================================================

        $sheet->setCellValue('A6','NO');
        $sheet->setCellValue('B6','NAMA SISWA');

        $col = 'C';

        foreach($tglBulan as $tgl){

            $sheet->setCellValue(
                $col.'6',
                date('d',strtotime($tgl))
            );

            $sheet->getColumnDimension($col)
                  ->setWidth(4);

            $col++;
        }

        // Rekap kanan
        $sheet->setCellValue($col.'6','H'); $col++;
        $sheet->setCellValue($col.'6','S'); $col++;
        $sheet->setCellValue($col.'6','I'); $col++;
        $sheet->setCellValue($col.'6','A'); $col++;
        $sheet->setCellValue($col.'6','L');

        // Style Header
        $lastCol = $col;

        $sheet->getStyle("A6:{$lastCol}6")->applyFromArray([
            'font'=>[
                'bold'=>true
            ],
            'alignment'=>[
                'horizontal'=>Alignment::HORIZONTAL_CENTER,
                'vertical'=>Alignment::VERTICAL_CENTER
            ],
            'borders'=>[
                'allBorders'=>[
                    'style'=>Border::BORDER_THIN
                ]
            ]
        ]);

        // =====================================================
        // MULAI DATA SISWA
        // =====================================================

        $row = 7;
        $no  = 1;
                foreach($siswa as $s){

            $sheet->setCellValue('A'.$row,$no++);
            $sheet->setCellValue('B'.$row,$s->nama);

            $jumlahH = 0;
            $jumlahS = 0;
            $jumlahI = 0;
            $jumlahA = 0;
            $jumlahL = 0;

            $col = 'C';

            foreach($tglBulan as $tgl){

                $hari = date('N',strtotime($tgl));

                $isWeekend = ($hari==6 || $hari==7);

                $isLibur = in_array($tgl,$hariMerah);

                // ===========================
                // Default Status
                // ===========================

                if($isWeekend || $isLibur){

                    $kode='L';

                }else{

                    $kode='H';

                    if(isset($arrAbsen[$s->id][$tgl])){

                        $kode = strtoupper($arrAbsen[$s->id][$tgl]);

                    }

                }

                // ===========================
                // Hitung Rekap
                // ===========================

                switch($kode){

                    case 'H':
                        $jumlahH++;
                    break;

                    case 'S':
                        $jumlahS++;
                    break;

                    case 'I':
                        $jumlahI++;
                    break;

                    case 'A':
                        $jumlahA++;
                    break;

                    case 'L':
                        $jumlahL++;
                    break;

                }

                // ===========================
                // Isi Cell
                // ===========================

                $sheet->setCellValue(
                    $col.$row,
                    $kode
                );

                // Border
                $sheet->getStyle($col.$row)
                      ->getBorders()
                      ->getAllBorders()
                      ->setBorderStyle(Border::BORDER_THIN);

                // Libur diberi warna
                if($kode=='L'){

                    $sheet->getStyle($col.$row)
                          ->getFill()
                          ->setFillType(
                              \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
                          )
                          ->getStartColor()
                          ->setRGB('FFCCCC');

                }

                $col++;

            }
                        // ====================================
            // TOTAL H S I A L
            // ====================================

            $sheet->setCellValue($col.$row,$jumlahH);
            $sheet->getStyle($col.$row)->getBorders()
                  ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $col++;

            $sheet->setCellValue($col.$row,$jumlahS);
            $sheet->getStyle($col.$row)->getBorders()
                  ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $col++;

            $sheet->setCellValue($col.$row,$jumlahI);
            $sheet->getStyle($col.$row)->getBorders()
                  ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $col++;

            $sheet->setCellValue($col.$row,$jumlahA);
            $sheet->getStyle($col.$row)->getBorders()
                  ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $col++;

            $sheet->setCellValue($col.$row,$jumlahL);
            $sheet->getStyle($col.$row)->getBorders()
                  ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Border kolom NO & NAMA
            $sheet->getStyle("A{$row}:B{$row}")
                  ->getBorders()
                  ->getAllBorders()
                  ->setBorderStyle(Border::BORDER_THIN);

            $row++;

        }

        // ====================================
        // AUTO SIZE
        // ====================================

        foreach(range('A',$sheet->getHighestColumn()) as $c){

            $sheet->getColumnDimension($c)
                  ->setAutoSize(true);

        }

        // Freeze header
        $sheet->freezePane('C7');

        $sheetIndex++;

    }
        // =====================================================
    // AKTIFKAN SHEET PERTAMA
    // =====================================================

    $spreadsheet->setActiveSheetIndex(0);

    // =====================================================
    // OUTPUT FILE
    // =====================================================

    $filename = 'Rekap_Absensi_Manual_' .
                str_replace(' ','_',$kelas_nama) .
                '_' .
                date('Ymd_His') .
                '.xls';

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    $writer = new Xls($spreadsheet);
    $writer->save('php://output');
    exit;

}



}
