<?php

namespace App\Controllers;

use App\Models\KelasModel;

class Buku_induk extends BaseController
{
    protected KelasModel $kelasModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
    }

    public function index($offset = 0)
    {
        $this->requireRole([1, 2, 3, 4, 5]);

        $limit   = (int) ($this->request->getGet('limit') ?: 20);
        $offset  = (int) $offset;
        $kelas   = $this->request->getGet('kelas');
        $search  = trim((string) $this->request->getGet('search'));

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
        $pager->makeLinks($offset / $limit + 1, $limit, $totalRows, 'default_full');

        $kelasList = $this->kelasModel->orderBy('nama', 'ASC')->findAll();

        $data = [
            'title'      => 'Buku Induk Siswa',
            'active'     => 'buku_induk',
            'siswa'      => $siswaList,
            'kelas'      => $kelasList,
            'kelas_list' => $kelasList,
            'pagination' => $pager->links(),
            'total_rows' => $totalRows,
            'limit'      => $limit,
            'offset'     => $offset,
            'filter'     => ['kelas' => $kelas, 'search' => $search],
        ];

        return $this->renderView('buku_induk/index', $data);
    }

    public function detail($id = null)
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
            return redirect()->to(site_url('buku_induk'));
        }

        $data = [
            'title'  => 'Detail Buku Induk - ' . $siswa->nama,
            'active' => 'buku_induk',
            'siswa'  => $siswa,
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
