<?php

namespace App\Controllers;

use App\Models\NilaiRaporModel;
use App\Models\SiswaModel;
use App\Models\MapelModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Nilairapor_admin extends BaseController
{
    protected NilaiRaporModel $nilaiModel;
    protected SiswaModel $siswaModel;
    protected MapelModel $mapelModel;

    public function __construct()
    {
        $this->nilaiModel = new NilaiRaporModel();
        $this->siswaModel = new SiswaModel();
        $this->mapelModel = new MapelModel();
    }

    public function index()
    {
        $this->requireRole([1, 4, 5]);

        $data = [
            'active' => 'nilai_input',
            'title'  => 'Input Nilai Rapor',
            'siswa'  => $this->siswaModel->select('id, nisn, nama, jk')->where('status', 'aktif')->orderBy('nama', 'ASC')->findAll(),
            'mapel'  => $this->mapelModel->orderBy('nama_mapel', 'ASC')->findAll(),
        ];

        return $this->renderView('admin/nilai_rapor/index', $data);
    }

    public function simpan()
    {
        $this->requireRole([1, 4, 5]);

        $siswaId  = (int) $this->request->getPost('siswa_id');
        $mapelId  = (int) $this->request->getPost('mapel_id');
        $semester = (int) $this->request->getPost('semester');
        $nilai    = (float) $this->request->getPost('nilai_angka');
        $tahunId  = $this->tahunAktifId();

        $this->nilaiModel->insertOrUpdate([
            'siswa_id'    => $siswaId,
            'mapel_id'    => $mapelId,
            'semester'    => $semester,
            'tahun_id'    => $tahunId,
            'nilai_angka' => $nilai,
        ]);

        $this->session->setFlashdata('success', 'Nilai berhasil disimpan / diperbarui.');
        return redirect()->to(site_url('Nilairapor_admin'));
    }

    public function rekap()
    {
        $this->requireRole([1, 4, 5]);

        $kelasId = $this->request->getGet('kelas') ?: null;
        $tahunId = $this->tahunAktifId();

        $perPage = 100;
        $page    = (int) ($this->request->getGet('page') ?: 1);
        if ($page < 1) {
            $page = 1;
        }
        $offset  = ($page > 1) ? ($page - 1) * $perPage : 0;

        $totalRows = $this->nilaiModel->countRekapSiswa($kelasId, $tahunId);
        $rekap     = $this->nilaiModel->rekapSiswa($kelasId, $tahunId, $perPage, $offset);
        $mapelList = $this->nilaiModel->getMapelRekap($kelasId, $tahunId);
        $kelasList = $this->db->table('kelas')->where('is_active', 1)->orderBy('nama', 'ASC')->get()->getResult();

        $pager = \Config\Services::pager();
        $pager->makeLinks($page, $perPage, $totalRows, 'default_full');

        $data = [
            'active'     => 'nilai_daftar',
            'title'      => 'Rekap Nilai Rapor',
            'rekap'      => $rekap,
            'mapel'      => $mapelList,
            'kelas'      => $kelasList,
            'kelas_id'   => $kelasId,
            'page'       => $page,
            'total_rows' => $totalRows,
            'pagination' => $pager->links(),
        ];

        return $this->renderView('admin/nilai_rapor/rekap', $data);
    }

    public function import()
    {
        $this->requireRole([1, 4, 5]);

        $data = [
            'active' => 'nilai_import',
            'title'  => 'Import Nilai Rapor',
        ];

        return $this->renderView('admin/nilai_rapor/import', $data);
    }

    public function import_proses_lebar()
    {
        $this->requireRole([1, 4, 5]);

        $file = $this->request->getFile('file');
        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            $this->session->setFlashdata('error', 'Silakan pilih file Excel (.xlsx) yang valid.');
            return redirect()->to(site_url('Nilairapor_admin/import'));
        }

        $allowedExtensions = ['xlsx', 'xls'];
        if (! in_array($file->getClientExtension(), $allowedExtensions, true)) {
            $this->session->setFlashdata('error', 'Hanya file dengan format .xlsx atau .xls yang diperbolehkan.');
            return redirect()->to(site_url('Nilairapor_admin/import'));
        }

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheetData   = $spreadsheet->getActiveSheet()->toArray();
        } catch (\Throwable $e) {
            $this->session->setFlashdata('error', 'Gagal membaca file Excel: ' . esc($e->getMessage()));
            return redirect()->to(site_url('Nilairapor_admin/import'));
        }

        if (count($sheetData) < 3) {
            $this->session->setFlashdata('error', 'Format file Excel tidak sesuai atau data kosong.');
            return redirect()->to(site_url('Nilairapor_admin/import'));
        }

        $headerMapel = $sheetData[0];
        $headerSmt   = $sheetData[1];

        // Mapping Kolom -> Mapel + Semester
        $mapelMap = [];
        $currentMapel = '';

        for ($col = 2; $col < count($headerMapel); $col++) {
            if (isset($headerMapel[$col]) && trim((string) $headerMapel[$col]) !== '') {
                $currentMapel = trim((string) $headerMapel[$col]);
            }

            if ($currentMapel === '') {
                continue;
            }

            $smtRaw = isset($headerSmt[$col]) ? (string) $headerSmt[$col] : '';
            $semester = (int) preg_replace('/[^0-9]/', '', $smtRaw);
            if ($semester === 0) {
                continue;
            }

            $mapel = $this->db->table('mapel')
                ->where('nama_mapel', $currentMapel)
                ->get()
                ->getRow();

            if (! $mapel) {
                continue;
            }

            $mapelMap[$col] = [
                'mapel_id' => $mapel->id_mapel,
                'semester' => $semester,
            ];
        }

        $berhasil   = 0;
        $skipSiswa  = 0;
        $skipNilai  = 0;
        $tahunId    = $this->tahunAktifId();

        foreach ($sheetData as $rowIndex => $row) {
            if ($rowIndex < 2) {
                continue;
            }

            $nisn = isset($row[0]) ? trim((string) $row[0]) : '';
            if ($nisn === '') {
                continue;
            }

            $siswa = $this->db->table('siswa')
                ->where('nisn', $nisn)
                ->where('status', 'aktif')
                ->get()
                ->getRow();

            if (! $siswa) {
                $skipSiswa++;
                continue;
            }

            foreach ($mapelMap as $col => $info) {
                if (! isset($row[$col])) {
                    continue;
                }

                $nilaiRaw = trim((string) $row[$col]);
                if ($nilaiRaw === '') {
                    $skipNilai++;
                    continue;
                }

                $nilaiAngka = (float) str_replace(',', '.', $nilaiRaw);

                $this->nilaiModel->insertOrUpdate([
                    'siswa_id'    => $siswa->id,
                    'mapel_id'    => $info['mapel_id'],
                    'semester'    => $info['semester'],
                    'tahun_id'    => $tahunId,
                    'nilai_angka' => $nilaiAngka,
                ]);

                $berhasil++;
            }
        }

        $msg = "Import selesai.<br>Nilai diproses: <b>{$berhasil}</b><br>Siswa tidak ditemukan: <b>{$skipSiswa}</b><br>Nilai kosong dilewati: <b>{$skipNilai}</b>";
        $this->session->setFlashdata('success', $msg);
        return redirect()->to(site_url('Nilairapor_admin/import'));
    }

    public function download_template_lebar()
    {
        $this->requireRole([1, 4, 5]);

        $mapel = $this->db->table('mapel')
            ->orderBy('nama_mapel', 'ASC')
            ->get()
            ->getResult();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Nilai');

        $sheet->setCellValue('A1', 'NISN');
        $sheet->mergeCells('A1:A2');

        $sheet->setCellValue('B1', 'NAMA SISWA');
        $sheet->mergeCells('B1:B2');

        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(35);

        $col = 3; // Start at Column C

        foreach ($mapel as $m) {
            $startCol = $col;
            $endCol   = $col + 5;

            $sheet->mergeCellsByColumnAndRow($startCol, 1, $endCol, 1);
            $sheet->setCellValueByColumnAndRow($startCol, 1, $m->nama_mapel);

            for ($smt = 1; $smt <= 6; $smt++) {
                $sheet->setCellValueByColumnAndRow($col, 2, 'Smt' . $smt);
                $sheet->getColumnDimensionByColumn($col)->setWidth(9);
                $col++;
            }
        }

        $lastColumn = Coordinate::stringFromColumnIndex($col - 1);
        $headerStyle = [
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle("A1:{$lastColumn}2")->applyFromArray($headerStyle);
        $sheet->setCellValue('A3', '0083666053');
        $sheet->setCellValue('B3', 'NAMA SISWA CONTOH');
        $sheet->freezePane('C3');

        $filename = 'template_import_nilai_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function export_rekap_excel()
    {
        $this->requireRole([1, 4, 5]);

        $kelasId = $this->request->getGet('kelas') ?: null;
        $tahunId = $this->tahunAktifId();

        $mapelList = $this->nilaiModel->getMapelRekap($kelasId, $tahunId);
        $rekap     = $this->nilaiModel->rekapSiswa($kelasId, $tahunId, 99999, 0);

        $kelasNama = 'Semua_Kelas';
        if ($kelasId) {
            $kls = $this->db->table('kelas')->where('id', $kelasId)->get()->getRow();
            if ($kls) {
                $kelasNama = str_replace(' ', '_', $kls->nama);
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Nilai');

        $sheet->setCellValue('A1', 'No');
        $sheet->mergeCells('A1:A2');
        $sheet->setCellValue('B1', 'NISN');
        $sheet->mergeCells('B1:B2');
        $sheet->setCellValue('C1', 'Nama Siswa');
        $sheet->mergeCells('C1:C2');
        $sheet->setCellValue('D1', 'Kelas');
        $sheet->mergeCells('D1:D2');

        $col = 5; // Column E
        foreach ($mapelList as $m) {
            $startCol = $col;
            $endCol   = $col + 5;
            $sheet->mergeCellsByColumnAndRow($startCol, 1, $endCol, 1);
            $sheet->setCellValueByColumnAndRow($startCol, 1, $m->nama_mapel);

            for ($smt = 1; $smt <= 6; $smt++) {
                $sheet->setCellValueByColumnAndRow($col, 2, 'Smt ' . $smt);
                $col++;
            }
        }

        $lastColStr = Coordinate::stringFromColumnIndex(max($col - 1, 4));

        $headerStyle = [
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4E73DF'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle("A1:{$lastColStr}2")->applyFromArray($headerStyle);

        $rowNum = 3;
        $no = 1;
        foreach ($rekap as $r) {
            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, (string) $r['nisn']);
            $sheet->setCellValue('C' . $rowNum, $r['nama']);
            $sheet->setCellValue('D' . $rowNum, $r['kelas']);

            $colIdx = 5;
            foreach ($mapelList as $m) {
                for ($smt = 1; $smt <= 6; $smt++) {
                    $nilai = $r['nilai'][$m->id_mapel][$smt] ?? null;
                    $sheet->setCellValueByColumnAndRow($colIdx, $rowNum, $nilai !== null ? $nilai : '-');
                    $colIdx++;
                }
            }
            $rowNum++;
        }

        foreach (['A', 'B', 'C', 'D'] as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $filename = 'Rekap_Nilai_Rapor_' . $kelasNama . '_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function hapus_nilai()
    {
        $this->requireRole([1, 4, 5]);

        $kelasId  = (int) $this->request->getPost('kelas_id');
        $mapelId  = (int) $this->request->getPost('mapel_id');
        $semester = (string) $this->request->getPost('semester');
        $tahunId  = $this->tahunAktifId();

        if (empty($kelasId) || empty($mapelId)) {
            $this->session->setFlashdata('error', 'Kelas dan Mata Pelajaran wajib dipilih.');
            return redirect()->to(site_url('Nilairapor_admin/rekap?kelas=' . $kelasId));
        }

        $hapus = $this->nilaiModel->hapusNilai($kelasId, $mapelId, $semester, $tahunId);

        $this->session->setFlashdata('success', $hapus . ' nilai berhasil dihapus.');
        return redirect()->to(site_url('Nilairapor_admin/rekap?kelas=' . $kelasId));
    }
}
