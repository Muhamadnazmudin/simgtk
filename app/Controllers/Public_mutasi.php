<?php

namespace App\Controllers;

use App\Models\MutasiModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Public_mutasi extends BaseController
{
    protected MutasiModel $mutasiModel;

    public function __construct()
    {
        $this->mutasiModel = new MutasiModel();
    }

    public function index()
    {
        $kelas    = $this->request->getGet('kelas', FILTER_DEFAULT);
        $jenis    = $this->request->getGet('jenis', FILTER_DEFAULT);
        $search   = $this->request->getGet('search', FILTER_DEFAULT);
        $tahunId  = (int) $this->request->getGet('tahun_id');

        if (empty($tahunId)) {
            $aktif = $this->db->table('tahun_ajaran')->where('aktif', 1)->get()->getRow();
            $tahunId = $aktif ? (int) $aktif->id : null;
        }

        $tahun = $tahunId ? $this->db->table('tahun_ajaran')->where('id', $tahunId)->get()->getRow() : null;

        $perPage = 10;
        $page    = (int) ($this->request->getGet('page') ?: 1);
        $offset  = ($page > 1) ? ($page - 1) * $perPage : 0;

        $allMutasi = $this->mutasiModel->getPublic([
            'tahun_id' => $tahunId,
            'kelas'    => $kelas,
            'jenis'    => $jenis,
            'search'   => $search,
        ]);

        $totalRows = count($allMutasi);
        $pager = \Config\Services::pager();
        $pager->makeLinks($page, $perPage, $totalRows, 'default_full');

        $data = [
            'judul'      => 'Data Siswa Mutasi',
            'tahun'      => $tahun ? $tahun->tahun : '',
            'tahun_id'   => $tahunId,
            'tahun_list' => $this->mutasiModel->getTahunList(),
            'mutasi'     => array_slice($allMutasi, $offset, $perPage),
            'kelas_list' => $this->mutasiModel->getKelasList(),
            'pagination' => $pager->links(),
        ];

        return $this->compatView->render('dashboard/mutasi_public', $data);
    }

    public function export_excel()
    {
        $filter = [
            'tahun_id' => $this->request->getGet('tahun_id'),
            'kelas'    => $this->request->getGet('kelas'),
            'jenis'    => $this->request->getGet('jenis'),
            'search'   => $this->request->getGet('search'),
        ];

        $data = $this->mutasiModel->getPublic($filter);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Mutasi');

        $sheet->setCellValue('A1', 'No')
              ->setCellValue('B1', 'Nama Siswa')
              ->setCellValue('C1', 'NIS')
              ->setCellValue('D1', 'NISN')
              ->setCellValue('E1', 'Jenis Mutasi')
              ->setCellValue('F1', 'Tanggal')
              ->setCellValue('G1', 'Tujuan')
              ->setCellValue('H1', 'Tahun Ajaran');

        $row = 2;
        $no  = 1;
        foreach ($data as $m) {
            $sheet->setCellValue("A{$row}", $no++)
                  ->setCellValue("B{$row}", $m->nama_siswa);
            $sheet->setCellValueExplicit("C{$row}", (string) $m->nis, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("D{$row}", (string) $m->nisn, DataType::TYPE_STRING);
            $sheet->setCellValue("E{$row}", ucfirst($m->jenis));
            $sheet->setCellValue("F{$row}", $m->tanggal ? date('d-m-Y', strtotime($m->tanggal)) : '-');
            $sheet->setCellValue("G{$row}", $m->jenis === 'keluar' ? ($m->tujuan_sekolah ?: '-') : '-');
            $sheet->setCellValue("H{$row}", $m->tahun_ajaran);
            $row++;
        }

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="data_mutasi_siswa.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
