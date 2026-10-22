<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Walikelas_laporan extends BaseController
{
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
        $kelasNama = $kelas->nama;

        $tahunAktif = $this->db->table('tahun_ajaran')->where('aktif', 1)->get()->getRow();

        $data = [
            'title'       => 'Rekap Absensi Manual',
            'active'      => 'wk_absensi_manual',
            'kelas_nama'  => $kelasNama,
            'tahun_aktif' => $tahunAktif,
        ];

        return $this->compatView->render('walikelas/templates/header', $data)
            . $this->compatView->render('walikelas/templates/sidebar', $data)
            . $this->compatView->render('walikelas/absensi_manual/index', $data)
            . $this->compatView->render('walikelas/templates/footer', $data);
    }

    public function data()
    {
        $kelas = $this->checkWalikelas();
        $kelasId = (int) $kelas->id;

        $dari   = trim((string) $this->request->getPost('dari'));
        $sampai = trim((string) $this->request->getPost('sampai'));
        $ket    = trim((string) $this->request->getPost('keterangan'));

        $builder = $this->db->table('absensi_detail ad')
            ->select('ad.id_detail, a.tanggal, s.nama AS nama_siswa, k.nama AS nama_kelas, ad.status, ad.keterangan')
            ->join('absensi a', 'a.id_absensi = ad.id_absensi')
            ->join('siswa s', 's.id = ad.id_siswa')
            ->join('kelas k', 'k.id = s.id_kelas')
            ->where('a.id_kelas', $kelasId);

        if (!empty($ket) && $ket !== 'all') {
            $builder->where('ad.status', $ket);
        }
        if (!empty($dari)) {
            $builder->where('a.tanggal >=', $dari);
        }
        if (!empty($sampai)) {
            $builder->where('a.tanggal <=', $sampai);
        }

        $builder->orderBy('a.tanggal', 'ASC');
        $builder->orderBy('s.nama', 'ASC');

        return $this->response->setJSON($builder->get()->getResult());
    }

    public function excel()
    {
        if (ob_get_length()) {
            ob_end_clean();
        }

        $kelas = $this->checkWalikelas();
        $kelasId = (int) $kelas->id;
        $kelasNama = $kelas->nama;

        $dari   = $this->request->getGet('dari');
        $sampai = $this->request->getGet('sampai');

        if (!$dari || !$sampai) {
            return $this->response->setBody('Filter tanggal wajib diisi.');
        }

        $dari   = date('Y-m-d', strtotime($dari));
        $sampai = date('Y-m-d', strtotime($sampai));

        $tanggal_all = [];
        $start = new \DateTime($dari);
        $end   = new \DateTime($sampai);
        for ($d = $start; $d <= $end; $d->modify('+1 day')) {
            $tanggal_all[] = $d->format('Y-m-d');
        }

        $tanggal_per_bulan = [];
        foreach ($tanggal_all as $tgl) {
            $bulan = date('Y-m', strtotime($tgl));
            $tanggal_per_bulan[$bulan][] = $tgl;
        }

        $qLibur = $this->db->table('hari_libur')->select('start')->get()->getResult();
        $hariMerah = array_map(fn($l) => $l->start, $qLibur);

        $siswa = $this->db->table('siswa')
            ->select('id, nama')
            ->where('id_kelas', $kelasId)
            ->where('status', 'aktif')
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResult();

        $qabsen = $this->db->query("
            SELECT d.id_siswa, a.tanggal, d.status
            FROM absensi_detail d
            JOIN absensi a ON a.id_absensi = d.id_absensi
            WHERE a.id_kelas = ?
            AND a.tanggal BETWEEN ? AND ?
        ", [$kelasId, $dari, $sampai])->getResult();

        $arrAbsen = [];
        foreach ($qabsen as $a) {
            $st = strtoupper(trim($a->status));
            if ($st === 'SAKIT' || $st === 'S') {
                $kode = 'S';
            } elseif ($st === 'IZIN' || $st === 'I') {
                $kode = 'I';
            } elseif ($st === 'ALPA' || $st === 'A') {
                $kode = 'A';
            } else {
                $kode = 'H';
            }
            $arrAbsen[$a->id_siswa][$a->tanggal] = $kode;
        }

        $spreadsheet = new Spreadsheet();
        $sheetIndex = 0;

        foreach ($tanggal_per_bulan as $bulanKey => $tglBulan) {
            if ($sheetIndex > 0) {
                $spreadsheet->createSheet();
            }
            $sheet = $spreadsheet->setActiveSheetIndex($sheetIndex);
            $sheet->setTitle(substr(date('F Y', strtotime($bulanKey . '-01')), 0, 31));

            $sheet->setCellValue('A1', 'REKAP ABSENSI MANUAL');
            $sheet->setCellValue('A2', 'KELAS : ' . $kelasNama);
            $sheet->setCellValue('A3', 'PERIODE : ' . date('d-m-Y', strtotime($dari)) . ' s/d ' . date('d-m-Y', strtotime($sampai)));
            $sheet->setCellValue('A4', 'BULAN : ' . date('F Y', strtotime($bulanKey . '-01')));

            $sheet->setCellValue('A6', 'NO');
            $sheet->setCellValue('B6', 'NAMA SISWA');

            $col = 'C';
            foreach ($tglBulan as $tgl) {
                $sheet->setCellValue($col . '6', date('d', strtotime($tgl)));
                $sheet->getColumnDimension($col)->setWidth(4);
                $col++;
            }

            $sheet->setCellValue($col . '6', 'H'); $col++;
            $sheet->setCellValue($col . '6', 'S'); $col++;
            $sheet->setCellValue($col . '6', 'I'); $col++;
            $sheet->setCellValue($col . '6', 'A'); $col++;
            $sheet->setCellValue($col . '6', 'L');

            $row = 7;
            $no  = 1;

            foreach ($siswa as $s) {
                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $s->nama);

                $jumlahH = 0;
                $jumlahS = 0;
                $jumlahI = 0;
                $jumlahA = 0;
                $jumlahL = 0;

                $col = 'C';
                foreach ($tglBulan as $tgl) {
                    $hari = (int) date('N', strtotime($tgl));
                    $isWeekend = ($hari === 6 || $hari === 7);
                    $isLibur = in_array($tgl, $hariMerah, true);

                    if ($isWeekend || $isLibur) {
                        $kode = 'L';
                    } else {
                        $kode = $arrAbsen[$s->id][$tgl] ?? 'H';
                    }

                    match ($kode) {
                        'H' => $jumlahH++,
                        'S' => $jumlahS++,
                        'I' => $jumlahI++,
                        'A' => $jumlahA++,
                        'L' => $jumlahL++,
                        default => null,
                    };

                    $sheet->setCellValue($col . $row, $kode);
                    if ($kode === 'L') {
                        $sheet->getStyle($col . $row)->getFill()->setFillType(Fill::FILL_SOLID);
                        $sheet->getStyle($col . $row)->getFill()->getStartColor()->setRGB('FFCCCC');
                    }
                    $col++;
                }

                $sheet->setCellValue($col . $row, $jumlahH); $col++;
                $sheet->setCellValue($col . $row, $jumlahS); $col++;
                $sheet->setCellValue($col . $row, $jumlahI); $col++;
                $sheet->setCellValue($col . $row, $jumlahA); $col++;
                $sheet->setCellValue($col . $row, $jumlahL);

                $row++;
            }

            $sheetIndex++;
        }

        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'Rekap_Absensi_Manual_' . $kelasNama . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
