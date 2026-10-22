<?php

namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\SiswaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Verval extends BaseController
{
    protected KelasModel $kelasModel;
    protected SiswaModel $siswaModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
        $this->siswaModel = new SiswaModel();
    }

    public function index()
    {
        return $this->siswa();
    }

    public function siswa()
    {
        $this->requireRole([1, 4, 5]);

        $kelasId = $this->request->getGet('kelas') ?: null;
        $keyword = trim((string) $this->request->getGet('q'));
        $limit   = (int) ($this->request->getGet('limit') ?: 20);
        $page    = (int) ($this->request->getGet('page') ?: 1);
        $offset  = ($page > 1) ? ($page - 1) * $limit : 0;

        $builder = $this->db->table('siswa s')
            ->select('s.*, k.nama AS nama_kelas')
            ->join('kelas k', 'k.id = s.id_kelas', 'inner')
            ->where('s.status', 'aktif');

        if (!empty($kelasId)) {
            $builder->where('s.id_kelas', $kelasId);
        }

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('s.nama', $keyword)
                ->orLike('s.nisn', $keyword)
                ->groupEnd();
        }

        $totalRows = $builder->countAllResults(false);
        $siswaList = $builder->orderBy('s.nama', 'ASC')->limit($limit, $offset)->get()->getResult();

        $pager = \Config\Services::pager();
        $pager->makeLinks($page, $limit, $totalRows, 'default_full');

        // Classes that have active students
        $kelasList = $this->db->table('kelas k')
            ->select('k.id, k.nama')
            ->join('siswa s', 's.id_kelas = k.id', 'inner')
            ->where('s.status', 'aktif')
            ->groupBy('k.id')
            ->orderBy('k.nama', 'ASC')
            ->get()
            ->getResult();

        $data = [
            'active'     => 'verval_siswa',
            'title'      => 'Verval Siswa',
            'siswa'      => $siswaList,
            'kelas'      => $kelasList,
            'pagination' => $pager->links(),
            'total_rows' => $totalRows,
        ];

        return $this->renderView('verval/verval_siswa', $data);
    }

    public function laporan()
    {
        $this->requireRole([1, 4, 5]);

        $kelasId = $this->request->getGet('kelas') ?: null;

        $kelasList = $this->db->table('kelas k')
            ->select('k.id, k.nama')
            ->join('siswa s', 's.id_kelas = k.id', 'inner')
            ->where('s.status', 'aktif')
            ->groupBy('k.id')
            ->orderBy('k.nama', 'ASC')
            ->get()
            ->getResult();

        $builder = $this->db->table('kelas k')
            ->select('
                k.id,
                k.nama AS nama_kelas,
                COUNT(s.id) AS total_siswa,
                SUM(CASE WHEN s.status_verval = 1 THEN 1 ELSE 0 END) AS valid,
                SUM(CASE WHEN s.status_verval = 2 THEN 1 ELSE 0 END) AS perbaikan,
                SUM(CASE WHEN s.status_verval IN (1, 2) THEN 1 ELSE 0 END) AS sudah_verval,
                SUM(CASE WHEN s.status_verval = 0 OR s.status_verval IS NULL THEN 1 ELSE 0 END) AS belum_verval
            ')
            ->join('siswa s', 's.id_kelas = k.id AND s.status = "aktif"', 'inner');

        if (!empty($kelasId)) {
            $builder->where('k.id', $kelasId);
        }

        $laporan = $builder->groupBy('k.id')->orderBy('k.nama', 'ASC')->get()->getResult();

        $data = [
            'active'  => 'laporan_verval',
            'title'   => 'Laporan Hasil Verval',
            'kelas'   => $kelasList,
            'laporan' => $laporan,
        ];

        return $this->renderView('verval/laporan_verval', $data);
    }

    public function valid($id = null)
    {
        $this->requireRole([1, 4, 5]);

        $id = (int) $id;
        $this->db->table('siswa')->where('id', $id)->update([
            'status_verval'  => 1,
            'catatan_verval' => null,
            'verval_by'      => $this->session->get('user_id'),
            'verval_at'      => date('Y-m-d H:i:s'),
        ]);

        $redirect = $this->request->getGet('redirect') ?: $this->request->getServer('HTTP_REFERER');
        return redirect()->to($redirect ?: site_url('verval/siswa'));
    }

    public function perbaikan()
    {
        $this->requireRole([1, 4, 5]);

        $id      = (int) $this->request->getPost('id_siswa');
        $catatan = (string) $this->request->getPost('catatan');

        $this->db->table('siswa')->where('id', $id)->update([
            'status_verval'  => 2,
            'catatan_verval' => $catatan,
            'verval_by'      => $this->session->get('user_id'),
            'verval_at'      => date('Y-m-d H:i:s'),
        ]);

        $redirect = $this->request->getPost('redirect_url');
        return redirect()->to($redirect ?: site_url('verval/siswa'));
    }

    public function reset($id = null)
    {
        $this->requireRole([1, 4, 5]);

        $id = (int) $id;
        $this->db->table('siswa')->where('id', $id)->update([
            'status_verval'  => 0,
            'catatan_verval' => null,
            'verval_by'      => null,
            'verval_at'      => null,
        ]);

        $ref = $this->request->getServer('HTTP_REFERER');
        return redirect()->to($ref ?: site_url('verval/siswa'));
    }
}
