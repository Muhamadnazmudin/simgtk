<?php

namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\NilaiRaporModel;
use App\Models\AbsensiQRModel;

class Buku_induk extends BaseController
{
    protected KelasModel $kelasModel;
    protected NilaiRaporModel $nilaiModel;
    protected AbsensiQRModel $absensiQrModel;

    public function __construct()
    {
        $this->kelasModel     = new KelasModel();
        $this->nilaiModel     = new NilaiRaporModel();
        $this->absensiQrModel = new AbsensiQRModel();
    }

    public function index($offset = 0)
    {
        $this->requireRole([1, 2, 3, 4, 5]);

        $limit   = (int) ($this->request->getGet('limit') ?: 20);
        $kelas   = $this->request->getGet('kelas');
        $search  = trim((string) $this->request->getGet('search'));

        $page = (int) ($this->request->getGet('page') ?: 0);
        if ($page > 0) {
            $offset = ($page - 1) * $limit;
        } elseif ((int) $offset > 0) {
            $offset = (int) $offset;
            $page   = (int) floor($offset / $limit) + 1;
        } else {
            $page   = 1;
            $offset = 0;
        }

        $builder = $this->db->table('siswa s')
            ->select('s.*, k.nama AS nama_kelas, ta.tahun AS tahun_ajaran')
            ->join('kelas k', 'k.id = s.id_kelas', 'left')
            ->join('tahun_ajaran ta', 'ta.id = s.tahun_id', 'left')
            ->where('s.status', 'aktif');

        if (!empty($kelas)) {
            $builder->where('s.id_kelas', $kelas);
        }

        if (!empty($search)) {
            $builder->groupStart()
                ->like('s.nama', $search)
                ->orLike('s.nis', $search)
                ->orLike('s.nisn', $search)
                ->groupEnd();
        }

        $totalRows = $builder->countAllResults(false);
        $siswaList = $builder->orderBy('s.nama', 'ASC')->limit($limit, $offset)->get()->getResult();

        $pager = \Config\Services::pager();
        $paginationHtml = $pager->makeLinks($page, $limit, $totalRows, 'bootstrap_pagination');

        $kelasList = $this->kelasModel->orderBy('nama', 'ASC')->findAll();

        $data = [
            'title'      => 'Buku Induk Siswa',
            'active'     => 'buku_induk',
            'siswa'      => $siswaList,
            'kelas'      => $kelasList,
            'kelas_list' => $kelasList,
            'pagination' => $paginationHtml,
            'total_rows' => $totalRows,
            'limit'      => $limit,
            'offset'     => $offset,
            'page'       => $page,
            'filter'     => ['kelas' => $kelas, 'search' => $search],
        ];

        return $this->renderView('buku_induk/index', $data);
    }

    public function detail($id = null)
    {
        $this->requireRole([1, 2, 3, 4, 5]);

        $id = (int) $id;
        $siswa = $this->db->table('siswa s')
            ->select('s.*, k.nama AS nama_kelas, ta.tahun AS tahun_ajaran')
            ->join('kelas k', 'k.id = s.id_kelas', 'left')
            ->join('tahun_ajaran ta', 'ta.id = s.tahun_id', 'left')
            ->where('s.id', $id)
            ->get()
            ->getRow();

        if (!$siswa) {
            return redirect()->to(site_url('buku_induk'));
        }

        // 1. Dokumen Berkas
        $berkas = $this->db->table('siswa_berkas')
            ->where('siswa_id', $id)
            ->orderBy('jenis_berkas', 'ASC')
            ->get()
            ->getResult();

        // 2. Nilai Rapor
        $rapor = $this->nilaiModel->rekapBySiswaKelas($id);

        // 3. Kehadiran
        $riwayat = $this->db->table('siswa_tahun st')
            ->select('st.tahun_id, ta.tahun')
            ->join('tahun_ajaran ta', 'ta.id = st.tahun_id')
            ->where('st.siswa_id', $id)
            ->orderBy('ta.id', 'ASC')
            ->get()
            ->getResult();

        $kehadiran = [];
        $kelasStr = strtoupper((string) ($siswa->nama_kelas ?? ''));
        if (strpos($kelasStr, 'XII') !== false) {
            $semesterTerakhir = 6;
        } elseif (strpos($kelasStr, 'XI') !== false) {
            $semesterTerakhir = 4;
        } else {
            $semesterTerakhir = 2;
        }

        $jumlahRiwayat = count($riwayat);
        $semesterNo = $semesterTerakhir - ($jumlahRiwayat * 2) + 1;
        if ($semesterNo < 1) {
            $semesterNo = 1;
        }

        foreach ($riwayat as $r) {
            $ganjil = $this->absensiQrModel->getRekapSemester((string) ($siswa->nis ?? ''), $r->tahun, 1);
            $kehadiran[] = (object) [
                'tahun_ajaran' => $r->tahun,
                'semester'     => $semesterNo++,
                'hadir'        => (int) ($ganjil->hadir ?? 0),
                'sakit'        => (int) ($ganjil->sakit ?? 0),
                'izin'         => (int) ($ganjil->izin ?? 0),
                'alpha'        => (int) ($ganjil->alpha ?? 0),
                'terlambat'    => (int) ($ganjil->terlambat ?? 0),
            ];

            $genap = $this->absensiQrModel->getRekapSemester((string) ($siswa->nis ?? ''), $r->tahun, 2);
            $kehadiran[] = (object) [
                'tahun_ajaran' => $r->tahun,
                'semester'     => $semesterNo++,
                'hadir'        => (int) ($genap->hadir ?? 0),
                'sakit'        => (int) ($genap->sakit ?? 0),
                'izin'         => (int) ($genap->izin ?? 0),
                'alpha'        => (int) ($genap->alpha ?? 0),
                'terlambat'    => (int) ($genap->terlambat ?? 0),
            ];
        }

        $data = [
            'title'     => 'Detail Buku Induk - ' . $siswa->nama,
            'active'    => 'buku_induk',
            'siswa'     => $siswa,
            'rapor'     => $rapor,
            'berkas'    => $berkas,
            'kehadiran' => $kehadiran,
        ];

        return $this->renderView('buku_induk/detail', $data);
    }

    public function cetak($id = null)
    {
        $this->requireRole([1, 2, 3, 4, 5]);

        $siswa = $this->db->table('siswa s')
            ->select('s.*, k.nama AS nama_kelas, ta.tahun AS tahun_ajaran')
            ->join('kelas k', 'k.id = s.id_kelas', 'left')
            ->join('tahun_ajaran ta', 'ta.id = s.tahun_id', 'left')
            ->where('s.id', $id)
            ->get()
            ->getRow();

        if (!$siswa) {
            return $this->response->setBody('Data siswa tidak ditemukan.');
        }

        $sekolah = $this->db->table('sekolah')->get()->getRow();

        $data = [
            'siswa'   => $siswa,
            'sekolah' => $sekolah,
        ];

        return $this->compatView->render('buku_induk/cetak', $data);
    }
}
