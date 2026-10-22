<?php

namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\SiswaModel;
use App\Models\AbsensiQRModel;

class AbsensiQRAdmin extends BaseController
{
    protected KelasModel $kelasModel;
    protected SiswaModel $siswaModel;
    protected AbsensiQRModel $absensiQrModel;

    public function __construct()
    {
        $this->kelasModel     = new KelasModel();
        $this->siswaModel     = new SiswaModel();
        $this->absensiQrModel = new AbsensiQRModel();
    }

    public function index()
    {
        $this->requireRole([1, 2, 4, 5]);

        $absen = $this->db->table('absensi_qr q')
            ->select('
                q.id,
                q.nis,
                q.tanggal,
                q.jam_masuk,
                q.jam_pulang,
                q.status,
                q.kehadiran,
                q.keterangan_telat,
                s.nama as nama_siswa,
                k.nama as nama_kelas
            ')
            ->join('siswa s', 's.nis = q.nis', 'left')
            ->join('kelas k', 'k.id = s.id_kelas', 'left')
            ->orderBy('q.id', 'DESC')
            ->limit(200)
            ->get()
            ->getResult();

        $data = [
            'judul'  => 'Absensi QR Siswa',
            'active' => 'absensiqr_siswa',
            'kelas'  => $this->kelasModel->orderBy('nama', 'ASC')->findAll(),
            'absen'  => $absen,
        ];

        return $this->renderView('absensiqr/admin_index', $data);
    }

    public function laporan()
    {
        $this->requireRole([1, 2, 4, 5]);

        $data = [
            'judul'  => 'Laporan Absensi QR',
            'active' => 'laporan_absensiqr',
            'kelas'  => $this->kelasModel->orderBy('nama', 'ASC')->findAll(),
        ];

        return $this->renderView('absensiqr/admin_laporan', $data);
    }

    public function data()
    {
        $this->requireAuth();

        $nama   = trim((string) $this->request->getPost('nama'));
        $kelas  = $this->request->getPost('kelas');
        $dari   = $this->request->getPost('dari');
        $sampai = $this->request->getPost('sampai');
        $status = $this->request->getPost('status');

        $builder = $this->db->table('absensi_qr q')
            ->select('
                q.id,
                q.nis,
                q.tanggal,
                q.jam_masuk,
                q.jam_pulang,
                q.status,
                q.kehadiran,
                q.keterangan_telat,
                s.nama as nama_siswa,
                k.nama as nama_kelas
            ')
            ->join('siswa s', 's.nis = q.nis', 'left')
            ->join('kelas k', 'k.id = s.id_kelas', 'left');

        if (!empty($nama)) {
            $builder->like('s.nama', $nama);
        }

        if (!empty($kelas)) {
            $builder->where('s.id_kelas', $kelas);
        }

        if (!empty($status)) {
            $builder->where('q.kehadiran', $status);
        }

        if (!empty($dari) && !empty($sampai)) {
            $builder->where('q.tanggal >=', $dari);
            $builder->where('q.tanggal <=', $sampai);
        }

        $res = $builder->orderBy('q.tanggal', 'ASC')->get()->getResult();
        return $this->response->setJSON($res);
    }

    public function simpan()
    {
        $this->requireRole([1, 2, 4, 5]);

        $data = [
            'nis'        => (string) $this->request->getPost('nis'),
            'tanggal'    => (string) $this->request->getPost('tanggal'),
            'jam_masuk'  => (string) $this->request->getPost('jam_masuk'),
            'jam_pulang' => $this->request->getPost('jam_pulang') ?: null,
            'kehadiran'  => (string) $this->request->getPost('kehadiran'),
            'status'     => (string) $this->request->getPost('status'),
            'sumber'     => 'manual_admin',
        ];

        $this->absensiQrModel->insert($data);
        $this->session->setFlashdata('success', 'Data absensi berhasil ditambahkan!');
        return redirect()->to(site_url('AbsensiQRAdmin'));
    }

    public function update()
    {
        $this->requireRole([1, 2, 4, 5]);

        $id = (int) $this->request->getPost('id');
        $data = [
            'kehadiran'  => (string) $this->request->getPost('kehadiran'),
            'status'     => (string) $this->request->getPost('status'),
            'tanggal'    => (string) $this->request->getPost('tanggal'),
            'jam_masuk'  => (string) $this->request->getPost('jam_masuk'),
            'jam_pulang' => $this->request->getPost('jam_pulang') ?: null,
        ];

        $this->absensiQrModel->update($id, $data);
        $this->session->setFlashdata('success', 'Data absensi berhasil diupdate!');
        return redirect()->to(site_url('AbsensiQRAdmin'));
    }

    public function hapus($id = null)
    {
        $this->requireRole([1, 2, 4, 5]);

        if (!empty($id)) {
            $this->absensiQrModel->delete($id);
            $this->session->setFlashdata('success', 'Data berhasil dihapus!');
        }

        return redirect()->to(site_url('AbsensiQRAdmin'));
    }

    public function pdf()
    {
        $this->requireRole([1, 2, 4, 5]);

        $kelasId = $this->request->getGet('kelas');
        $dari    = (string) $this->request->getGet('dari');
        $sampai  = (string) $this->request->getGet('sampai');
        $status  = (string) $this->request->getGet('status');

        $builder = $this->db->table('absensi_qr q')
            ->select('
                q.*,
                s.nama as nama_siswa,
                k.nama as nama_kelas
            ')
            ->join('siswa s', 's.nis = q.nis', 'left')
            ->join('kelas k', 'k.id = s.id_kelas', 'left');

        if (!empty($kelasId)) {
            $builder->where('s.id_kelas', $kelasId);
        }
        if (!empty($status)) {
            $builder->where('q.kehadiran', $status);
        }
        if (!empty($dari) && !empty($sampai)) {
            $builder->where('q.tanggal >=', $dari);
            $builder->where('q.tanggal <=', $sampai);
        }

        $absen = $builder->orderBy('q.tanggal', 'ASC')->get()->getResult();
        $kelasRow = !empty($kelasId) ? $this->kelasModel->find($kelasId) : null;

        $data = [
            'absen'      => $absen,
            'dari'       => $dari ?: '-',
            'sampai'     => $sampai ?: '-',
            'kelas_name' => $kelasRow ? $kelasRow->nama : 'Semua Kelas',
        ];

        return view('absensiqr/pdf_laporan', $data);
    }

    public function excel()
    {
        $this->requireRole([1, 2, 4, 5]);

        $kelasId = $this->request->getGet('kelas');
        $dari    = (string) $this->request->getGet('dari');
        $sampai  = (string) $this->request->getGet('sampai');
        $status  = (string) $this->request->getGet('status');

        $builder = $this->db->table('absensi_qr q')
            ->select('
                q.*,
                s.nama as nama_siswa,
                k.nama as nama_kelas
            ')
            ->join('siswa s', 's.nis = q.nis', 'left')
            ->join('kelas k', 'k.id = s.id_kelas', 'left');

        if (!empty($kelasId)) {
            $builder->where('s.id_kelas', $kelasId);
        }
        if (!empty($status)) {
            $builder->where('q.kehadiran', $status);
        }
        if (!empty($dari) && !empty($sampai)) {
            $builder->where('q.tanggal >=', $dari);
            $builder->where('q.tanggal <=', $sampai);
        }

        $absen = $builder->orderBy('q.tanggal', 'ASC')->get()->getResult();
        $kelasRow = !empty($kelasId) ? $this->kelasModel->find($kelasId) : null;
        $kelasName = $kelasRow ? preg_replace('/[^a-zA-Z0-9_\-]/', '_', $kelasRow->nama) : 'Semua_Kelas';

        $filename = 'Laporan_Absensi_QR_' . $kelasName . '_' . date('Ymd_His') . '.xls';

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        echo view('absensiqr/pdf_laporan', [
            'absen'      => $absen,
            'dari'       => $dari ?: '-',
            'sampai'     => $sampai ?: '-',
            'kelas_name' => $kelasRow ? $kelasRow->nama : 'Semua Kelas',
        ]);
        exit;
    }
}
