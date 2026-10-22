<?php

namespace App\Controllers;

use App\Models\SiswaBiodataLogModel;
use App\Models\KelasModel;

class Riwayat_biodata extends BaseController
{
    protected SiswaBiodataLogModel $logModel;
    protected KelasModel $kelasModel;

    public function __construct()
    {
        $this->logModel   = new SiswaBiodataLogModel();
        $this->kelasModel = new KelasModel();
    }

    public function index()
    {
        $this->requireRole([1, 2, 3, 4, 5]);

        $search  = trim((string) $this->request->getGet('search'));
        $kelasId = $this->request->getGet('kelas_id') ?: null;
        $roleId  = (int) $this->session->get('role_id');

        if ($roleId === 3 && $this->session->get('kelas_id')) {
            $kelasId = (int) $this->session->get('kelas_id');
        }

        $perPage = 36;
        $page    = (int) ($this->request->getGet('page') ?: 1);
        $offset  = ($page > 1) ? ($page - 1) * $perPage : 0;

        $totalRows = $this->logModel->countSiswaSummary($search, $kelasId ? (int) $kelasId : null);
        $riwayat   = $this->logModel->getSiswaSummary($perPage, $offset, $search, $kelasId ? (int) $kelasId : null);

        $pager = \Config\Services::pager();
        $pager->makeLinks($page, $perPage, $totalRows, 'default_full');

        $kelasList = $this->kelasModel->orderBy('nama', 'ASC')->findAll();

        $data = [
            'title'       => 'Riwayat Perubahan Biodata',
            'active'      => 'riwayat_biodata',
            'riwayat'     => $riwayat,
            'kelas'       => $kelasList,
            'kelas_id'    => $kelasId,
            'search'      => $search,
            'pagination'  => $pager->links(),
            'total_rows'  => $totalRows,
            'total'       => $totalRows,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_page'  => ($totalRows > 0) ? (int) ceil($totalRows / $perPage) : 1,
            'total_siswa' => $this->logModel->countAllSiswa($kelasId ? (int) $kelasId : null),
            'total_log'   => $this->logModel->countAllLog($kelasId ? (int) $kelasId : null),
            'today'       => $this->logModel->countToday($kelasId ? (int) $kelasId : null),
        ];

        return $this->renderView('riwayat_biodata/index', $data);
    }

    public function detail($siswaId = null)
    {
        $this->requireRole([1, 2, 3, 4, 5]);

        $siswaId = (int) $siswaId;
        if (!$siswaId) {
            return $this->responseJson(['status' => false, 'message' => 'ID siswa tidak valid.'], 400);
        }

        $logs = $this->logModel->getBySiswa($siswaId);
        $siswa = $this->db->table('siswa s')
            ->select('s.nama, s.nisn, k.nama as nama_kelas')
            ->join('kelas k', 'k.id = s.id_kelas', 'left')
            ->where('s.id', $siswaId)
            ->get()
            ->getRow();

        return $this->responseJson([
            'status' => true,
            'siswa'  => $siswa,
            'data'   => $logs,
        ]);
    }
}
