<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use TCPDF;

class Walikelas extends BaseController
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
        $kelasId = (int) $kelas->id;
        $kelasNama = $kelas->nama;

        $siswa = $this->db->table('siswa')
            ->select('nis')
            ->where('id_kelas', $kelasId)
            ->where('status', 'aktif')
            ->get()
            ->getResult();

        $nisList = [];
        foreach ($siswa as $s) {
            if (!empty($s->nis)) {
                $nisList[] = $s->nis;
            }
        }
        if (empty($nisList)) {
            $nisList = ['0'];
        }

        $laki = $this->db->table('siswa')
            ->where('id_kelas', $kelasId)
            ->where('jk', 'L')
            ->where('status', 'aktif')
            ->countAllResults();

        $perempuan = $this->db->table('siswa')
            ->where('id_kelas', $kelasId)
            ->where('jk', 'P')
            ->where('status', 'aktif')
            ->countAllResults();

        $tanggal = date('Y-m-d');
        $kode    = ['H', 'I', 'S', 'A'];
        $dataHari = [];
        foreach ($kode as $k) {
            $dataHari['hari_' . $k] = $this->db->table('absensi_qr')
                ->where('tanggal', $tanggal)
                ->where('kehadiran', $k)
                ->whereIn('nis', $nisList)
                ->countAllResults();
        }

        $bulan = date('m');
        $tahun = date('Y');
        $awalBulan = "{$tahun}-{$bulan}-01";
        $akhirBulan = date('Y-m-t', strtotime($awalBulan));

        $dataBulan = [];
        foreach ($kode as $k) {
            $dataBulan['bulan_' . $k] = $this->db->table('absensi_qr')
                ->where('kehadiran', $k)
                ->whereIn('nis', $nisList)
                ->where('tanggal >=', $awalBulan)
                ->where('tanggal <=', $akhirBulan)
                ->countAllResults();
        }

        $izinKeluar = $this->db->table('izin_keluar')
            ->whereIn('nis', $nisList)
            ->where('jam_keluar >=', "{$tanggal} 00:00:00")
            ->where('jam_keluar <=', "{$tanggal} 23:59:59")
            ->where('jenis_izin', 'keluar')
            ->countAllResults();

        $izinPulang = $this->db->table('izin_keluar')
            ->whereIn('nis', $nisList)
            ->where('jam_keluar >=', "{$tanggal} 00:00:00")
            ->where('jam_keluar <=', "{$tanggal} 23:59:59")
            ->where('jenis_izin', 'pulang')
            ->countAllResults();

        $data = array_merge([
            'kelas_nama'            => $kelasNama,
            'title'                 => 'Dashboard Wali Kelas',
            'active'                => 'dashboard',
            'laki'                  => $laki,
            'perempuan'             => $perempuan,
            'total_siswa'           => $laki + $perempuan,
            'izin_keluar_hari_ini'  => $izinKeluar,
            'izin_pulang_hari_ini'  => $izinPulang,
        ], $dataHari, $dataBulan);

        return $this->compatView->render('walikelas/templates/header', $data)
            . $this->compatView->render('walikelas/templates/sidebar', $data)
            . $this->compatView->render('walikelas/dashboard', $data)
            . $this->compatView->render('walikelas/templates/footer');
    }

    public function siswa()
    {
        $kelas = $this->checkWalikelas();
        $kelasId = (int) $kelas->id;
        $kelasNama = $kelas->nama;

        $search = trim((string) $this->request->getGet('search'));

        $builder = $this->db->table('siswa')
            ->select('siswa.*, kelas.nama AS nama_kelas')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->where('siswa.id_kelas', $kelasId)
            ->where('siswa.status', 'aktif');

        if (!empty($search)) {
            $builder->groupStart()
                ->like('siswa.nama', $search)
                ->orLike('siswa.nis', $search)
                ->orLike('siswa.nisn', $search)
                ->groupEnd();
        }

        $siswaList = $builder->orderBy('siswa.nama', 'ASC')->get()->getResult();

        $data = [
            'siswa'      => $siswaList,
            'kelas_nama' => $kelasNama,
            'active'     => 'wk_siswa',
            'title'      => 'Data Siswa Kelas ' . $kelasNama,
            'search'     => $search,
        ];

        return $this->compatView->render('walikelas/templates/header', $data)
            . $this->compatView->render('walikelas/templates/sidebar', $data)
            . $this->compatView->render('walikelas/siswa/index', $data)
            . $this->compatView->render('walikelas/templates/footer');
    }

    public function siswa_export_excel()
    {
        $kelas = $this->checkWalikelas();
        $kelasId = (int) $kelas->id;

        $siswa = $this->db->table('siswa')
            ->select('siswa.*, kelas.nama AS nama_kelas, tahun_ajaran.tahun AS tahun_ajaran')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.tahun_id', 'left')
            ->where('siswa.id_kelas', $kelasId)
            ->orderBy('siswa.nama', 'ASC')
            ->get()
            ->getResult();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['No', 'NIS', 'NISN', 'Nama', 'JK', 'Kelas', 'Tahun Ajaran'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col++ . '1', $h);
        }

        $row = 2;
        $no  = 1;
        foreach ($siswa as $s) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValueExplicit('B' . $row, (string) $s->nis, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C' . $row, (string) $s->nisn, DataType::TYPE_STRING);
            $sheet->setCellValue('D' . $row, $s->nama);
            $sheet->setCellValue('E' . $row, $s->jk);
            $sheet->setCellValue('F' . $row, $s->nama_kelas);
            $sheet->setCellValue('G' . $row, $s->tahun_ajaran);
            $row++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="siswa_kelas_' . $kelas->nama . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function cetak($id)
    {
        return $this->cetak_biodata($id);
    }

    public function cetak_biodata($id)
    {
        $siswa = $this->db->table('siswa')
            ->select('siswa.*, kelas.nama AS nama_kelas, tahun_ajaran.tahun AS tahun_ajaran, kelas.wali_kelas_id')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.tahun_id', 'left')
            ->where('siswa.id', (int) $id)
            ->get()
            ->getRow();

        if (!$siswa) {
            return $this->response->setBody('Data siswa tidak ditemukan.');
        }

        $walikelas = (object)['nama' => '-', 'nip' => '-'];
        if (!empty($siswa->wali_kelas_id)) {
            $wali = $this->db->table('guru')->where('id', $siswa->wali_kelas_id)->get()->getRow();
            if ($wali) {
                $walikelas = $wali;
            }
        }

        $data = [
            'siswa'     => $siswa,
            'walikelas' => $walikelas,
        ];

        $html = $this->compatView->render('walikelas/cetak_biodata', $data);

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();
        $pdf->writeHTML($html);

        $fileName = 'Biodata_' . str_replace(' ', '_', $siswa->nama) . '.pdf';
        $pdf->Output($fileName, 'I');
        exit;
    }

    public function cetak_biodata_all()
    {
        $kelas = $this->checkWalikelas();
        $kelasId = (int) $kelas->id;

        $siswa = $this->db->table('siswa')
            ->select('siswa.*, kelas.nama AS nama_kelas, tahun_ajaran.tahun AS tahun_ajaran')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.tahun_id', 'left')
            ->where('siswa.id_kelas', $kelasId)
            ->where('siswa.status', 'aktif')
            ->orderBy('siswa.nama', 'ASC')
            ->get()
            ->getResult();

        $data = ['siswa' => $siswa];
        return $this->compatView->render('walikelas/cetak_biodata_all', $data);
    }

    public function absensi()
    {
        $kelas = $this->checkWalikelas();
        $kelasNama = $kelas->nama;

        $data = [
            'kelas'      => [$kelas],
            'kelas_nama' => $kelasNama,
            'title'      => 'Laporan Absensi QR - Wali Kelas',
            'active'     => 'wk_absensi',
        ];

        return $this->compatView->render('walikelas/templates/header', $data)
            . $this->compatView->render('walikelas/templates/sidebar', $data)
            . $this->compatView->render('walikelas/absensi/index', $data)
            . $this->compatView->render('walikelas/templates/footer');
    }

    public function absensi_data()
    {
        $kelas = $this->checkWalikelas();
        $kelasId = (int) $kelas->id;

        $nama   = trim((string) $this->request->getPost('nama'));
        $dari   = trim((string) $this->request->getPost('dari'));
        $sampai = trim((string) $this->request->getPost('sampai'));
        $status = trim((string) $this->request->getPost('status'));

        $builder = $this->db->table('absensi_qr q')
            ->select('q.id, q.nis, q.tanggal, q.jam_masuk, q.jam_pulang, q.status, q.kehadiran, s.nama as nama_siswa')
            ->join('siswa s', 's.nis = q.nis', 'left')
            ->where('s.id_kelas', $kelasId);

        if ($nama !== '') {
            $builder->like('s.nama', $nama);
        }
        if ($status !== '') {
            $builder->where('q.kehadiran', $status);
        }
        if ($dari !== '' && $sampai !== '') {
            $builder->where('q.tanggal >=', $dari);
            $builder->where('q.tanggal <=', $sampai);
        }

        $builder->orderBy('q.tanggal', 'ASC');

        return $this->response->setJSON($builder->get()->getResult());
    }

    public function absensi_pdf()
    {
        $kelas = $this->checkWalikelas();
        $kelasId = (int) $kelas->id;
        $kelasNama = $kelas->nama;

        $dari   = $this->request->getGet('dari');
        $sampai = $this->request->getGet('sampai');

        if (!$dari || !$sampai) {
            return $this->response->setBody('Tanggal belum diisi');
        }

        $pdf = new TCPDF('L', 'mm', [330, 210], true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(5, 5, 5);
        $pdf->SetFont('helvetica', '', 9);

        $tanggal = [];
        $start = strtotime($dari);
        $end   = strtotime($sampai);
        while ($start <= $end) {
            $tanggal[] = date('Y-m-d', $start);
            $start = strtotime('+1 day', $start);
        }

        $siswa = $this->db->table('siswa')
            ->select('nis, nama')
            ->where('id_kelas', $kelasId)
            ->where('status', 'aktif')
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResult();

        $q = $this->db->table('absensi_qr')
            ->select('nis, tanggal, kehadiran')
            ->where('tanggal >=', $dari)
            ->where('tanggal <=', $sampai)
            ->get()
            ->getResult();

        $rekap = [];
        foreach ($q as $r) {
            $rekap[$r->nis][$r->tanggal] = $r->kehadiran;
        }

        $hariLibur = $this->db->table('hari_libur')->get()->getResult();
        $tanggalMerah = array_map(fn($x) => $x->start, $hariLibur);

        $wali = null;
        if (!empty($kelas->wali_kelas_id)) {
            $wali = $this->db->table('guru')->where('id', $kelas->wali_kelas_id)->get()->getRow();
        }
        $walikelas = $wali ?: (object)['nama' => '-', 'nip' => '-'];

        $data = [
            'tanggal'      => $tanggal,
            'rekap'        => $rekap,
            'siswa'        => $siswa,
            'kelas_nama'   => $kelasNama,
            'tanggalMerah' => $tanggalMerah,
            'bulan_label'  => date('F Y', strtotime($dari)),
            'tahun'        => date('Y', strtotime($dari)),
            'logo'         => base_url('assets/img/logo.png'),
            'walikelas'    => $walikelas,
            'tanggal_ttd'  => 'Kuningan, ' . date('d F Y'),
        ];

        $pdf->AddPage();
        $html = $this->compatView->render('walikelas/absensi/pdf_rekap_bulan', $data);
        $pdf->writeHTML($html, true, false, true, false, '');

        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('Rekap_Absensi_QR_' . $kelasNama . '.pdf', 'I');
        exit;
    }

    public function absensi_excel()
    {
        $kelas = $this->checkWalikelas();
        $kelasId = (int) $kelas->id;
        $kelasNama = $kelas->nama;

        $dari   = $this->request->getGet('dari');
        $sampai = $this->request->getGet('sampai');

        if (!$dari || !$sampai) {
            return $this->response->setBody('Tanggal wajib diisi.');
        }

        $spreadsheet = new Spreadsheet();

        $tanggal_all = [];
        $start = new \DateTime($dari);
        $end   = new \DateTime($sampai);
        for ($d = $start; $d <= $end; $d->modify('+1 day')) {
            $tanggal_all[] = $d->format('Y-m-d');
        }

        $tanggal_per_bulan = [];
        foreach ($tanggal_all as $tgl) {
            $k = date('Y-m', strtotime($tgl));
            $tanggal_per_bulan[$k][] = $tgl;
        }

        $siswa = $this->db->table('siswa')
            ->select('id, nis, nama')
            ->where('id_kelas', $kelasId)
            ->where('status', 'aktif')
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResult();

        $abs = $this->db->table('absensi_qr')
            ->select('nis, tanggal, kehadiran')
            ->where('tanggal >=', $dari)
            ->where('tanggal <=', $sampai)
            ->get()
            ->getResult();

        $rekap = [];
        foreach ($abs as $a) {
            $rekap[$a->nis][$a->tanggal] = $a->kehadiran;
        }

        $sheetIndex = 0;
        foreach ($tanggal_per_bulan as $bulan_key => $tgl_bulan) {
            if ($sheetIndex > 0) {
                $spreadsheet->createSheet();
            }
            $sheet = $spreadsheet->setActiveSheetIndex($sheetIndex);
            $sheet->setTitle(substr(date('M Y', strtotime($bulan_key . '-01')), 0, 31));

            $sheet->setCellValue('A1', 'REKAP ABSENSI QR KELAS ' . $kelasNama);
            $sheet->setCellValue('A3', 'Periode: ' . $dari . ' s/d ' . $sampai);
            $sheet->setCellValue('A5', 'No');
            $sheet->setCellValue('B5', 'Nama');

            $col = 'C';
            foreach ($tgl_bulan as $tgl) {
                $sheet->setCellValue($col . '5', date('d', strtotime($tgl)));
                $col++;
            }

            $row = 6;
            $no = 1;
            foreach ($siswa as $s) {
                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $s->nama);
                $col = 'C';
                foreach ($tgl_bulan as $tgl) {
                    $val = $rekap[$s->nis][$tgl] ?? '-';
                    $sheet->setCellValue($col . $row, $val);
                    $col++;
                }
                $row++;
            }
            $sheetIndex++;
        }

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Rekap_Walikelas_' . $kelasNama . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function izin()
    {
        return redirect()->to(site_url('izin'));
    }
}
