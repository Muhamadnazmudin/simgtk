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
}
