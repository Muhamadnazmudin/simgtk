<?php

namespace App\Controllers;

use App\Models\LaporanPembelajaranSiswaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminLaporanPembelajaran extends BaseController
{
    protected LaporanPembelajaranSiswaModel $model;

    public function __construct()
    {
        $this->model = new LaporanPembelajaranSiswaModel();
    }

    public function index()
    {
        $this->requireRole([1]);

        $filter = [
            'kelas_id' => $this->request->getGet('kelas_id'),
            'guru_id'  => $this->request->getGet('guru_id'),
            'tanggal'  => $this->request->getGet('tanggal'),
        ];

        $data = [
            'title'   => 'Laporan Jam Kosong Siswa',
            'active'  => 'laporan_pembelajaran_siswa',
            'laporan' => $this->model->getAll($filter),
            'kelas'   => $this->db->table('kelas')->orderBy('nama', 'ASC')->get()->getResult(),
            'guru'    => $this->db->table('guru')->orderBy('nama', 'ASC')->get()->getResult(),
        ];

        return $this->renderView('admin/laporan_pembelajaran_siswa', $data);
    }

    public function export_excel()
    {
        $this->requireRole([1]);

        $filter = [
            'kelas_id' => $this->request->getGet('kelas_id'),
            'guru_id'  => $this->request->getGet('guru_id'),
            'tanggal'  => $this->request->getGet('tanggal'),
        ];

        $data = $this->model->getAll($filter);

        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->setTitle('Laporan Jam Kosong');

        $header = ['No', 'Tanggal', 'Kelas', 'Pelapor (Siswa)', 'Guru Pengajar', 'Mata Pelajaran', 'Jam Kosong', 'Keterangan'];
        $sheet->fromArray($header, null, 'A1');

        $rowNum = 2;
        $no = 1;
        foreach ($data as $r) {
            $jam = ($r->jam_awal && $r->jam_akhir)
                ? "{$r->jam_awal} - {$r->jam_akhir}"
                : "{$r->jam_mulai} - {$r->jam_selesai}";

            $sheet->setCellValue("A{$rowNum}", $no++);
            $sheet->setCellValue("B{$rowNum}", $r->tanggal);
            $sheet->setCellValue("C{$rowNum}", $r->nama_kelas);
            $sheet->setCellValue("D{$rowNum}", $r->nama_siswa);
            $sheet->setCellValue("E{$rowNum}", $r->nama_guru);
            $sheet->setCellValue("F{$rowNum}", $r->nama_mapel);
            $sheet->setCellValue("G{$rowNum}", $jam);
            $sheet->setCellValue("H{$rowNum}", $r->keterangan);
            $rowNum++;
        }

        $filename = 'Laporan_Jam_Kosong_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($excel);
        $writer->save('php://output');
        exit;
    }
}
