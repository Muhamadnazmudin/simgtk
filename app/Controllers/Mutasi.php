<?php

namespace App\Controllers;

use App\Models\MutasiModel;
use App\Models\SiswaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class Mutasi extends BaseController
{
    protected MutasiModel $mutasiModel;
    protected SiswaModel $siswaModel;

    public function __construct()
    {
        $this->mutasiModel = new MutasiModel();
        $this->siswaModel  = new SiswaModel();
    }

    public function index($offset = 0)
    {
        $this->requireAuth();

        $tahunId = $this->tahunAktifId();
        $offset  = (int) $offset;
        $perPage = 15;

        $totalRows = $this->mutasiModel->countByYear($tahunId);
        $mutasi    = $this->mutasiModel->getAll($perPage, $offset, $tahunId);

        $pager = \Config\Services::pager();
        $pager->makeLinks($offset / $perPage + 1, $perPage, $totalRows, 'default_full');

        $data = [
            'title'      => 'Data Mutasi Siswa',
            'active'     => 'mutasi',
            'offset'     => $offset,
            'mutasi'     => $mutasi,
            'pagination' => $pager->links(),
            'siswa'      => $this->mutasiModel->getSiswaAktif(),
            'kelas'      => $this->mutasiModel->getKelasList(),
            'tahun'      => $this->mutasiModel->getTahunList(),
        ];

        return $this->renderView('mutasi/index', $data);
    }

    public function add()
    {
        $this->requireAuth();

        if ($this->request->getMethod() === 'POST') {
            $jenis     = (string) $this->request->getPost('jenis');
            $tahunId   = (int) ($this->request->getPost('tahun_id') ?: $this->tahunAktifId());
            $createdBy = (int) $this->session->get('user_id');

            if (!$createdBy) {
                $this->session->setFlashdata('error', 'Session login tidak valid.');
                return redirect()->to(site_url('auth/login'));
            }

            // PROSES MUTASI MASUK (SISWA BARU)
            if ($jenis === 'masuk') {
                $namaBaru = trim((string) $this->request->getPost('nama_baru'));
                if (empty($namaBaru)) {
                    $this->session->setFlashdata('error', 'Nama siswa baru wajib diisi.');
                    return redirect()->to(site_url('mutasi'));
                }

                $tujuanKelasId = (int) $this->request->getPost('tujuan_kelas_id');

                $dataSiswa = [
                    'nis'          => (string) $this->request->getPost('nis_baru'),
                    'nisn'         => (string) $this->request->getPost('nisn_baru'),
                    'nama'         => $namaBaru,
                    'jk'           => (string) $this->request->getPost('jk_baru'),
                    'sekolah_asal' => (string) $this->request->getPost('asal_sekolah_baru'),
                    'id_kelas'     => $tujuanKelasId,
                    'tahun_id'     => $tahunId,
                    'status'       => 'aktif',
                ];

                $this->db->table('siswa')->insert($dataSiswa);
                $siswaId = $this->db->insertID();

                // Generate QR Token via chillerlan
                $token = 'qr_' . $siswaId;
                $qrFolder = FCPATH . 'uploads/qr/';
                if (!is_dir($qrFolder)) {
                    @mkdir($qrFolder, 0777, true);
                }
                $qrFile = $qrFolder . $token . '.png';

                try {
                    $options = new QROptions([
                        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                        'eccLevel'   => QRCode::ECC_H,
                        'scale'      => 6,
                    ]);
                    (new QRCode($options))->render($token, $qrFile);
                } catch (\Throwable $e) {
                    log_message('error', 'QR generation error: ' . $e->getMessage());
                }

                $this->db->table('siswa')->where('id', $siswaId)->update(['token_qr' => $token]);

                // Record siswa_tahun
                $this->db->table('siswa_tahun')->insert([
                    'siswa_id' => $siswaId,
                    'kelas_id' => $tujuanKelasId,
                    'tahun_id' => $tahunId,
                    'status'   => 'aktif',
                ]);

                // Upload Berkas
                $fileName = null;
                $file = $this->request->getFile('berkas');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    if ($file->getMimeType() === 'application/pdf') {
                        $fileName = $file->getRandomName();
                        $file->move(FCPATH . 'uploads/mutasi', $fileName);
                    }
                }

                $dataMutasi = [
                    'siswa_id'        => $siswaId,
                    'kelas_asal_id'   => null,
                    'jenis'           => 'masuk',
                    'tanggal'         => $this->request->getPost('tanggal') ?: date('Y-m-d'),
                    'tujuan_kelas_id' => $tujuanKelasId,
                    'tujuan_sekolah'  => null,
                    'alasan'          => (string) $this->request->getPost('alasan'),
                    'nohp_ortu'       => (string) $this->request->getPost('nohp_ortu'),
                    'tahun_id'        => $tahunId,
                    'berkas'          => $fileName,
                    'created_by'      => $createdBy,
                ];

                $this->db->table('mutasi')->insert($dataMutasi);
                $this->session->setFlashdata('success', 'Mutasi siswa masuk berhasil disimpan.');
                return redirect()->to(site_url('mutasi'));
            }

            // PROSES MUTASI KELUAR
            $siswaId = (int) $this->request->getPost('siswa_id');
            if (empty($siswaId)) {
                $this->session->setFlashdata('error', 'Siswa belum dipilih.');
                return redirect()->to(site_url('mutasi'));
            }

            $fileName = null;
            $file = $this->request->getFile('berkas');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                if ($file->getMimeType() === 'application/pdf') {
                    $fileName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/mutasi', $fileName);
                }
            }

            $dataMutasiKeluar = [
                'siswa_id'        => $siswaId,
                'jenis'           => 'keluar',
                'jenis_keluar'    => (string) $this->request->getPost('jenis_keluar'),
                'tanggal'         => $this->request->getPost('tanggal') ?: date('Y-m-d'),
                'alasan'          => (string) $this->request->getPost('alasan'),
                'nohp_ortu'       => (string) $this->request->getPost('nohp_ortu'),
                'tujuan_sekolah'  => (string) $this->request->getPost('tujuan_sekolah'),
                'tujuan_kelas_id' => null,
                'tahun_id'        => $tahunId,
                'berkas'          => $fileName,
                'created_by'      => $createdBy,
            ];

            $this->mutasiModel->mutasiKeluar($dataMutasiKeluar);
            $this->session->setFlashdata('success', 'Mutasi siswa keluar berhasil disimpan.');
            return redirect()->to(site_url('mutasi'));
        }

        return redirect()->to(site_url('mutasi'));
    }

    public function edit($id = null)
    {
        $this->requireAuth();

        $mutasi = $this->mutasiModel->find($id);
        if (!$mutasi) {
            $this->session->setFlashdata('error', 'Data mutasi tidak ditemukan.');
            return redirect()->to(site_url('mutasi'));
        }

        $jenis = (string) $this->request->getPost('jenis');
        $data = [
            'jenis'        => $jenis,
            'jenis_keluar' => (string) $this->request->getPost('jenis_keluar'),
            'alasan'       => (string) $this->request->getPost('alasan'),
            'nohp_ortu'    => (string) $this->request->getPost('nohp_ortu'),
            'tanggal'      => $this->request->getPost('tanggal'),
            'tahun_id'     => $this->request->getPost('tahun_id') ?: $mutasi->tahun_id,
        ];

        if ($jenis === 'keluar') {
            $data['tujuan_sekolah']  = (string) $this->request->getPost('tujuan');
            $data['tujuan_kelas_id'] = null;
        } else {
            $data['tujuan_kelas_id'] = $this->request->getPost('tujuan_kelas_id') ?: null;
            $data['tujuan_sekolah']  = null;
        }

        $file = $this->request->getFile('berkas');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if ($file->getMimeType() === 'application/pdf') {
                $fileName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/mutasi', $fileName);
                if (!empty($mutasi->berkas) && file_exists(FCPATH . 'uploads/mutasi/' . $mutasi->berkas)) {
                    @unlink(FCPATH . 'uploads/mutasi/' . $mutasi->berkas);
                }
                $data['berkas'] = $fileName;
            }
        }

        $this->mutasiModel->update($id, $data);
        $this->session->setFlashdata('success', 'Data mutasi berhasil diperbarui.');
        return redirect()->to(site_url('mutasi'));
    }

    public function delete($id = null)
    {
        $this->requireAuth();
        $this->mutasiModel->delete($id);
        $this->session->setFlashdata('success', 'Data mutasi dihapus.');
        return redirect()->to(site_url('mutasi'));
    }

    public function batalkan($id = null)
    {
        $this->requireAuth();

        $mutasi = $this->mutasiModel->find($id);
        if (!$mutasi) {
            $this->session->setFlashdata('error', 'Data mutasi tidak ditemukan.');
            return redirect()->to(site_url('mutasi'));
        }

        $updateData = ['status' => 'aktif'];
        if (!empty($mutasi->kelas_asal_id)) {
            $updateData['id_kelas'] = $mutasi->kelas_asal_id;
        }

        $this->db->table('siswa')->where('id', $mutasi->siswa_id)->update($updateData);

        $st = $this->db->table('siswa_tahun')
            ->where('siswa_id', $mutasi->siswa_id)
            ->where('tahun_id', $mutasi->tahun_id)
            ->get()
            ->getRow();

        if ($st) {
            $this->db->table('siswa_tahun')->where('id', $st->id)->update([
                'status'   => 'aktif',
                'kelas_id' => $mutasi->kelas_asal_id,
            ]);
        } else {
            $this->db->table('siswa_tahun')->insert([
                'siswa_id' => $mutasi->siswa_id,
                'kelas_id' => $mutasi->kelas_asal_id,
                'tahun_id' => $mutasi->tahun_id,
                'status'   => 'aktif',
            ]);
        }

        $this->mutasiModel->delete($id);
        $this->session->setFlashdata('success', 'Mutasi siswa berhasil dibatalkan.');
        return redirect()->to(site_url('mutasi'));
    }

    public function search_siswa()
    {
        $this->requireAuth();

        $term  = trim((string) $this->request->getGet('term'));
        $jenis = (string) $this->request->getGet('jenis');

        $builder = $this->db->table('siswa')
            ->groupStart()
                ->like('nama', $term)
                ->orLike('nis', $term)
            ->groupEnd();

        if ($jenis === 'masuk') {
            $builder->where('status', 'mutasi_masuk');
        } else {
            $builder->where('status', 'aktif');
        }

        $rows = $builder->limit(10)->get()->getResult();
        $result = [];
        foreach ($rows as $row) {
            $result[] = [
                'id'    => $row->id,
                'label' => $row->nis . ' - ' . $row->nama,
                'value' => $row->nama,
            ];
        }

        return $this->response->setJSON($result);
    }

    public function export_excel()
    {
        $this->requireAuth();

        $tahunId = $this->tahunAktifId();
        $data    = $this->mutasiModel->getAll(10000, 0, $tahunId);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Mutasi');

        $sheet->setCellValue('A1', 'No')
              ->setCellValue('B1', 'Nama Siswa')
              ->setCellValue('C1', 'Jenis Mutasi')
              ->setCellValue('D1', 'Tanggal')
              ->setCellValue('E1', 'Alasan')
              ->setCellValue('F1', 'Tujuan Kelas')
              ->setCellValue('G1', 'Tujuan Sekolah')
              ->setCellValue('H1', 'Tahun Ajaran');

        $row = 2;
        $no  = 1;
        foreach ($data as $m) {
            $sheet->setCellValue("A{$row}", $no++)
                  ->setCellValue("B{$row}", $m->nama_siswa)
                  ->setCellValue("C{$row}", ucfirst($m->jenis))
                  ->setCellValue("D{$row}", $m->tanggal)
                  ->setCellValue("E{$row}", $m->alasan)
                  ->setCellValue("F{$row}", $m->tujuan_kelas_nama)
                  ->setCellValue("G{$row}", $m->tujuan_sekolah)
                  ->setCellValue("H{$row}", $m->tahun_ajaran);
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
