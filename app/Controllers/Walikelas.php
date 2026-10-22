<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Walikelas extends BaseController
{
    private function checkWalikelas(): ?object
    {
        $this->requireAuth();

        $guruId = $this->session->get('guru_id');
        if (!$guruId) {
            header('Location: ' . site_url('dashboard'));
            exit;
        }

        $kelas = $this->db->table('kelas')->where('wali_kelas_id', $guruId)->get()->getRow();
        if (!$kelas) {
            header('Location: ' . site_url('dashboard'));
            exit;
        }

        $this->session->set([
            'kelas_id'   => $kelas->id,
            'kelas_nama' => $kelas->nama,
        ]);

        return $kelas;
    }

    public function index()
    {
        $kelas = $this->checkWalikelas();
        $kelasId = $kelas->id;
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
        $dataBulan = [];
        foreach ($kode as $k) {
            $dataBulan['bulan_' . $k] = $this->db->table('absensi_qr')
                ->where('kehadiran', $k)
                ->whereIn('nis', $nisList)
                ->where('MONTH(tanggal)', $bulan)
                ->where('YEAR(tanggal)', $tahun)
                ->countAllResults();
        }

        $izinKeluar = $this->db->table('izin_keluar')
            ->whereIn('nis', $nisList)
            ->where('DATE(jam_keluar)', $tanggal)
            ->where('jenis_izin', 'keluar')
            ->countAllResults();

        $izinPulang = $this->db->table('izin_keluar')
            ->whereIn('nis', $nisList)
            ->where('DATE(jam_keluar)', $tanggal)
            ->where('jenis_izin', 'pulang')
            ->countAllResults();

        $data = array_merge([
            'kelas_nama'            => $kelasNama,
            'title'                 => 'Dashboard Wali Kelas',
            'active'                => 'walikelas_dashboard',
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
        $kelasId = $kelas->id;
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
        $kelasId = $kelas->id;

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
}
