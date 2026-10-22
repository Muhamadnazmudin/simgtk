<?php

namespace App\Controllers;

use App\Models\TkaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use TCPDF;

class Tka_admin extends BaseController
{
    protected TkaModel $tkaModel;

    public function __construct()
    {
        $this->tkaModel = new TkaModel();
    }

    public function index()
    {
        $this->requireRole([1, 2]);

        $filter = $this->getFilter();
        $siswa  = $this->getPesertaTka($filter['tahun_id'], $filter['kelas_id'], $filter['status'], $filter['nama']);

        $totalSudah = 0;
        $totalBelum = 0;
        foreach ($siswa as $row) {
            if (!empty($row->tka_id)) {
                $totalSudah++;
            } else {
                $totalBelum++;
            }
        }

        $data = [
            'title'        => 'Data Pilihan TKA Siswa',
            'active'       => 'tka',
            'siswa'        => $siswa,
            'tahun_list'   => $this->getTahunList(),
            'kelas_list'   => $this->getKelasXii(),
            'tahun_id'     => $filter['tahun_id'],
            'kelas_id'     => $filter['kelas_id'],
            'status'       => $filter['status'],
            'nama'         => $filter['nama'],
            'nama_tahun'   => $this->getNamaTahun($filter['tahun_id']),
            'nama_kelas'   => $this->getNamaKelas($filter['kelas_id']),
            'status_label' => $this->getStatusLabel($filter['status']),
            'total_sudah'  => $totalSudah,
            'total_belum'  => $totalBelum,
        ];

        return $this->renderView('admin/tka/index', $data);
    }

    public function input($siswaId = null)
    {
        $this->requireRole([1, 2]);

        $siswaId = (int) $siswaId;
        if ($siswaId <= 0) {
            $this->session->setFlashdata('error', 'ID siswa tidak valid.');
            return redirect()->to(site_url('Tka_admin'));
        }

        $siswa = $this->tkaModel->getSiswaTka($siswaId);
        if (!$siswa) {
            $this->session->setFlashdata('error', 'Data siswa tidak ditemukan.');
            return redirect()->to(site_url('Tka_admin'));
        }

        $existing = $this->tkaModel->getPilihan($siswa->id, (int) $siswa->tahun_id);
        if ($existing) {
            return redirect()->to(site_url('Tka_admin/edit/' . (int) $existing->id));
        }

        $mapelProgram = $this->tkaModel->getMapelProgramKeahlian();
        $mapelPilihan = $this->tkaModel->getMapelPilihan();

        $data = [
            'title'         => 'Input Pilihan TKA Siswa',
            'active'        => 'tka',
            'siswa'         => $siswa,
            'mapel_program' => $mapelProgram,
            'mapel_pilihan' => $mapelPilihan,
        ];

        return $this->renderView('admin/tka/input', $data);
    }

    public function store()
    {
        $this->requireRole([1, 2]);

        $siswaId  = (int) $this->request->getPost('siswa_id');
        $mapel1Id = (int) $this->request->getPost('mapel_1_id');
        $mapel2Id = (int) $this->request->getPost('mapel_2_id');

        $siswa = $this->tkaModel->getSiswaTka($siswaId);
        if (!$siswa) {
            $this->session->setFlashdata('error', 'Data siswa tidak valid.');
            return redirect()->to(site_url('Tka_admin'));
        }

        $tahunId = (int) $siswa->tahun_id;
        $existing = $this->tkaModel->getPilihan($siswaId, $tahunId);
        if ($existing) {
            $this->session->setFlashdata('warning', 'Siswa sudah pernah mengisi pilihan TKA.');
            return redirect()->to(site_url('Tka_admin'));
        }

        $this->tkaModel->insert([
            'siswa_id'   => $siswaId,
            'tahun_id'   => $tahunId,
            'mapel_1_id' => $mapel1Id,
            'mapel_2_id' => $mapel2Id,
        ]);

        $this->session->setFlashdata('success', 'Pilihan TKA berhasil disimpan.');
        return redirect()->to(site_url('Tka_admin'));
    }

    public function edit($id = null)
    {
        $this->requireRole([1, 2]);

        $id = (int) $id;
        if ($id <= 0) {
            $this->session->setFlashdata('error', 'ID data tidak valid.');
            return redirect()->to(site_url('Tka_admin'));
        }

        $tka = $this->tkaModel->getById($id);
        if (!$tka) {
            $this->session->setFlashdata('error', 'Data TKA tidak ditemukan.');
            return redirect()->to(site_url('Tka_admin'));
        }

        $mapelProgram = $this->tkaModel->getMapelProgramKeahlian();
        $mapelPilihan = $this->tkaModel->getMapelPilihan();

        $data = [
            'title'         => 'Edit Pilihan TKA Siswa',
            'active'        => 'tka',
            'tka'           => $tka,
            'mapel_program' => $mapelProgram,
            'mapel_pilihan' => $mapelPilihan,
        ];

        return $this->renderView('admin/tka/edit', $data);
    }

    public function update()
    {
        $this->requireRole([1, 2]);

        $id       = (int) $this->request->getPost('id');
        $mapel1Id = (int) $this->request->getPost('mapel_1_id');
        $mapel2Id = (int) $this->request->getPost('mapel_2_id');

        $this->tkaModel->update($id, [
            'mapel_1_id' => $mapel1Id,
            'mapel_2_id' => $mapel2Id,
        ]);

        $this->session->setFlashdata('success', 'Pilihan TKA berhasil diperbarui.');
        return redirect()->to(site_url('Tka_admin'));
    }

    public function export_excel()
    {
        $this->requireRole([1, 2]);

        $filter = $this->getFilter();
        $siswa  = $this->getPesertaTka($filter['tahun_id'], $filter['kelas_id'], $filter['status'], $filter['nama']);

        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->setTitle('Data Peserta TKA');

        $header = ['No', 'NISN', 'NIS', 'Nama Siswa', 'Kelas', 'Tahun Ajaran', 'Pilihan 1 (PK)', 'Pilihan 2', 'Status'];
        $sheet->fromArray($header, null, 'A1');

        $rowNum = 2;
        $no = 1;
        foreach ($siswa as $r) {
            $statusStr = !empty($r->tka_id) ? 'Sudah Mengisi' : 'Belum Mengisi';
            $sheet->setCellValue("A{$rowNum}", $no++);
            $sheet->setCellValue("B{$rowNum}", $r->nisn);
            $sheet->setCellValue("C{$rowNum}", $r->nis);
            $sheet->setCellValue("D{$rowNum}", $r->nama);
            $sheet->setCellValue("E{$rowNum}", $r->nama_kelas);
            $sheet->setCellValue("F{$rowNum}", $r->tahun_ajaran);
            $sheet->setCellValue("G{$rowNum}", $r->mapel_1 ?: '-');
            $sheet->setCellValue("H{$rowNum}", $r->mapel_2 ?: '-');
            $sheet->setCellValue("I{$rowNum}", $statusStr);
            $rowNum++;
        }

        $filename = 'Data_Peserta_TKA_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($excel);
        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $this->requireRole([1, 2]);

        $filter = $this->getFilter();
        $siswa  = $this->getPesertaTka($filter['tahun_id'], $filter['kelas_id'], $filter['status'], $filter['nama']);

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('SimSGTK');
        $pdf->SetAuthor('SimSGTK');
        $pdf->SetTitle('Laporan Pilihan TKA Siswa');
        $pdf->SetMargins(10, 15, 10);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 8, 'DATA PILIHAN TKA SISWA KELAS XII', 0, 1, 'C');
        $pdf->Ln(2);

        $pdf->SetFont('helvetica', '', 9);
        $w = [8, 25, 20, 60, 25, 60, 60, 20];
        $header = ['No', 'NISN', 'NIS', 'Nama Siswa', 'Kelas', 'Pilihan 1 (PK)', 'Pilihan 2', 'Status'];

        $pdf->SetFillColor(230, 230, 230);
        $pdf->SetFont('helvetica', 'B', 9);
        for ($i = 0; $i < count($header); $i++) {
            $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 8);
        $no = 1;
        foreach ($siswa as $r) {
            $statusStr = !empty($r->tka_id) ? 'Sudah' : 'Belum';
            $pdf->Cell($w[0], 6, $no++, 1, 0, 'C');
            $pdf->Cell($w[1], 6, $r->nisn, 1, 0, 'C');
            $pdf->Cell($w[2], 6, $r->nis, 1, 0, 'C');
            $pdf->Cell($w[3], 6, mb_strimwidth($r->nama, 0, 32, '..'), 1, 0, 'L');
            $pdf->Cell($w[4], 6, $r->nama_kelas, 1, 0, 'C');
            $pdf->Cell($w[5], 6, mb_strimwidth($r->mapel_1 ?: '-', 0, 32, '..'), 1, 0, 'L');
            $pdf->Cell($w[6], 6, mb_strimwidth($r->mapel_2 ?: '-', 0, 32, '..'), 1, 0, 'L');
            $pdf->Cell($w[7], 6, $statusStr, 1, 1, 'C');
        }

        $pdf->Output('laporan_tka.pdf', 'I');
        exit;
    }

    private function getFilter(): array
    {
        $tahunId = (int) $this->request->getGet('tahun_id');
        if ($tahunId <= 0) {
            $tahunId = $this->tahunAktifId();
        }

        $status = strtolower(trim((string) $this->request->getGet('status')));
        if (!in_array($status, ['sudah', 'belum'], true)) {
            $status = '';
        }

        return [
            'tahun_id' => $tahunId,
            'kelas_id' => (int) $this->request->getGet('kelas_id'),
            'status'   => $status,
            'nama'     => trim((string) $this->request->getGet('nama')),
        ];
    }

    private function getPesertaTka(int $tahunId, int $kelasId = 0, string $status = '', string $nama = ''): array
    {
        $builder = $this->db->table('siswa s')
            ->select('
                s.id,
                s.nisn,
                s.nis,
                s.nama,
                s.id_kelas,
                s.tahun_id,
                s.status,
                k.nama AS nama_kelas,
                ta.tahun AS tahun_ajaran,
                ts.id AS tka_id,
                ts.mapel_1_id,
                ts.mapel_2_id,
                m1.nama_mapel AS mapel_1,
                m2.nama_mapel AS mapel_2
            ')
            ->join('kelas k', 'k.id = s.id_kelas', 'left')
            ->join('tahun_ajaran ta', 'ta.id = s.tahun_id', 'left')
            ->join('tka_siswa ts', "ts.siswa_id = s.id AND ts.tahun_id = {$tahunId}", 'left', false)
            ->join('tka_mapel m1', 'm1.id = ts.mapel_1_id', 'left')
            ->join('tka_mapel m2', 'm2.id = ts.mapel_2_id', 'left')
            ->where('LOWER(s.status)', 'aktif')
            ->where('s.tahun_id', $tahunId)
            ->where("UPPER(k.nama) LIKE 'XII %'", null, false);

        if ($kelasId > 0) {
            $builder->where('s.id_kelas', $kelasId);
        }
        if ($nama !== '') {
            $builder->like('s.nama', $nama);
        }
        if ($status === 'sudah') {
            $builder->where('ts.id IS NOT NULL', null, false);
        } elseif ($status === 'belum') {
            $builder->where('ts.id IS NULL', null, false);
        }

        return $builder->orderBy('k.nama', 'ASC')->orderBy('s.nama', 'ASC')->get()->getResult();
    }

    private function getTahunList(): array
    {
        return $this->db->table('tahun_ajaran')->select('id, tahun')->orderBy('id', 'DESC')->get()->getResult();
    }

    private function getKelasXii(): array
    {
        return $this->db->table('kelas')->select('id, nama')->where("UPPER(nama) LIKE 'XII %'", null, false)->orderBy('nama', 'ASC')->get()->getResult();
    }

    private function getNamaTahun(int $tahunId): string
    {
        $row = $this->db->table('tahun_ajaran')->select('tahun')->where('id', $tahunId)->get()->getRow();
        return $row ? $row->tahun : '-';
    }

    private function getNamaKelas(int $kelasId): string
    {
        if ($kelasId <= 0) {
            return 'Semua Kelas XII';
        }
        $row = $this->db->table('kelas')->select('nama')->where('id', $kelasId)->get()->getRow();
        return $row ? $row->nama : 'Semua Kelas XII';
    }

    private function getStatusLabel(string $status): string
    {
        return match ($status) {
            'sudah' => 'Sudah Mengisi',
            'belum' => 'Belum Mengisi',
            default => 'Semua Peserta',
        };
    }
}
