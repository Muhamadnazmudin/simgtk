<?php

namespace App\Controllers;

use App\Models\BukuTamuModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Buku_tamu extends BaseController
{
    protected BukuTamuModel $bukuTamuModel;

    public function __construct()
    {
        $this->bukuTamuModel = new BukuTamuModel();
    }

    public function tambah()
    {
        if ($this->request->getMethod() === 'POST') {
            $fotoSelfie   = null;
            $selfieBase64 = (string) $this->request->getPost('selfie_base64');

            if (!empty($selfieBase64)) {
                $cleaned = preg_replace('#^data:image/\w+;base64,#i', '', $selfieBase64);
                $decoded = base64_decode($cleaned, true);

                if ($decoded !== false) {
                    $selfieDir = FCPATH . 'uploads/buku_tamu/selfie/';
                    if (!is_dir($selfieDir)) {
                        @mkdir($selfieDir, 0777, true);
                    }
                    $filename = 'selfie_' . time() . '_' . rand(1000, 9999) . '.jpg';
                    file_put_contents($selfieDir . $filename, $decoded);
                    $fotoSelfie = $filename;
                }
            }

            $fotoIdentitas = null;
            $file = $this->request->getFile('foto_identitas');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $identitasDir = FCPATH . 'uploads/buku_tamu/identitas/';
                if (!is_dir($identitasDir)) {
                    @mkdir($identitasDir, 0777, true);
                }
                $fotoIdentitas = $file->getRandomName();
                $file->move($identitasDir, $fotoIdentitas);
            }

            $data = [
                'tanggal'        => date('Y-m-d H:i:s'),
                'nama_tamu'      => (string) $this->request->getPost('nama_tamu', FILTER_DEFAULT),
                'instansi'       => (string) $this->request->getPost('instansi', FILTER_DEFAULT),
                'jumlah_orang'   => (int) $this->request->getPost('jumlah_orang'),
                'alamat'         => (string) $this->request->getPost('alamat', FILTER_DEFAULT),
                'no_hp'          => (string) $this->request->getPost('no_hp', FILTER_DEFAULT),
                'tujuan'         => (string) $this->request->getPost('tujuan', FILTER_DEFAULT),
                'keperluan'      => (string) $this->request->getPost('keperluan', FILTER_DEFAULT),
                'bertemu_dengan' => (string) $this->request->getPost('bertemu_dengan', FILTER_DEFAULT),
                'foto_selfie'    => $fotoSelfie,
                'foto_identitas' => $fotoIdentitas,
                'status'         => 0,
            ];

            $this->bukuTamuModel->insert($data);
            $this->session->setFlashdata('success', 'Data buku tamu berhasil disimpan.');

            $roleName = (string) $this->session->get('role_name');
            if (in_array($roleName, ['admin', 'operator', 'bpbk'], true)) {
                return redirect()->to(site_url('buku_tamu'));
            }

            return redirect()->to(site_url('buku_tamu/daftar_hari_ini'));
        }

        return $this->compatView->render('buku_tamu/tambah');
    }

    public function index()
    {
        $this->cekAdmin();

        $data = [
            'active' => 'buku_tamu',
            'title'  => 'Daftar Buku Tamu',
            'list'   => $this->bukuTamuModel->orderBy('tanggal', 'DESC')->findAll(),
        ];

        return $this->renderView('buku_tamu/index', $data);
    }

    public function edit($id = null)
    {
        $this->cekAdmin();

        $row = $this->bukuTamuModel->find($id);
        if (!$row) {
            return redirect()->to(site_url('buku_tamu'));
        }

        if ($this->request->getMethod() === 'POST') {
            $update = [
                'nama_tamu'      => (string) $this->request->getPost('nama_tamu', FILTER_DEFAULT),
                'instansi'       => (string) $this->request->getPost('instansi', FILTER_DEFAULT),
                'jumlah_orang'   => (int) $this->request->getPost('jumlah_orang'),
                'alamat'         => (string) $this->request->getPost('alamat', FILTER_DEFAULT),
                'no_hp'          => (string) $this->request->getPost('no_hp', FILTER_DEFAULT),
                'tujuan'         => (string) $this->request->getPost('tujuan', FILTER_DEFAULT),
                'keperluan'      => (string) $this->request->getPost('keperluan', FILTER_DEFAULT),
                'bertemu_dengan' => (string) $this->request->getPost('bertemu_dengan', FILTER_DEFAULT),
            ];

            $this->bukuTamuModel->update($id, $update);
            return redirect()->to(site_url('buku_tamu'));
        }

        $data = [
            'active' => 'buku_tamu',
            'row'    => $row,
        ];

        return $this->compatView->render('buku_tamu/edit_modal', $data);
    }

    public function hapus($id = null)
    {
        $this->cekAdmin();

        $this->bukuTamuModel->delete($id);
        return redirect()->to(site_url('buku_tamu'));
    }

    public function daftar_hari_ini($offset = 0)
    {
        $limit  = 10;
        $offset = (int) $offset;

        $totalRows = $this->bukuTamuModel->countHariIni();
        $list      = $this->bukuTamuModel->getHariIni($limit, $offset);

        $pager = \Config\Services::pager();
        $pager->makeLinks($offset / $limit + 1, $limit, $totalRows, 'default_full');

        $data = [
            'title'      => 'Buku Tamu Hari Ini',
            'list'       => $list,
            'pagination' => $pager->links(),
            'start'      => $offset,
        ];

        return $this->compatView->render('buku_tamu/daftar_hari_ini', $data);
    }

    public function export_excel()
    {
        $this->cekAdmin();

        $list = $this->bukuTamuModel->orderBy('tanggal', 'DESC')->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $header = ['No', 'Tanggal', 'Nama', 'Instansi', 'Jumlah Orang', 'No HP', 'Tujuan', 'Keperluan', 'Bertemu Dengan'];
        $col = 'A';
        foreach ($header as $h) {
            $sheet->setCellValue($col++ . '1', $h);
        }

        $row = 2;
        $no  = 1;
        foreach ($list as $r) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $r->tanggal);
            $sheet->setCellValue('C' . $row, $r->nama_tamu);
            $sheet->setCellValue('D' . $row, $r->instansi);
            $sheet->setCellValue('E' . $row, $r->jumlah_orang);
            $sheet->setCellValue('F' . $row, $r->no_hp);
            $sheet->setCellValue('G' . $row, $r->tujuan);
            $sheet->setCellValue('H' . $row, $r->keperluan);
            $sheet->setCellValue('I' . $row, $r->bertemu_dengan);
            $row++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="buku_tamu.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function cekAdmin(): void
    {
        $role = (string) $this->session->get('role_name');
        if (!in_array($role, ['admin', 'operator', 'bpbk'], true)) {
            header('Location: ' . site_url('dashboard'));
            exit;
        }
    }
}
