<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Dashboard Controller - CodeIgniter 4
 */
class Dashboard extends BaseController
{
    protected ?int $tahun_id = null;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $sessionTahun = $this->session->get('tahun_id');
        if ($sessionTahun) {
            $this->tahun_id = (int) $sessionTahun;
        } else {
            $t = $this->db->table('tahun_ajaran')->where('aktif', 1)->get()->getRow();
            $this->tahun_id = $t ? (int) $t->id : null;
        }
    }

    public function index()
    {
        $data['title'] = 'Dashboard';

        // ==========================================
        // Authenticated Dashboard
        // ==========================================
        if ($this->session->get('logged_in')) {
            $role = strtolower((string) $this->session->get('role_name'));

            // Teachers go to Guru Dashboard
            if ($role === 'guru') {
                return redirect()->to('/guru_dashboard');
            }

            $allowed = ['admin', 'kesiswaan', 'operator', 'bpbk'];
            if (!in_array($role, $allowed, true)) {
                return $this->response->setStatusCode(403)->setBody(
                    view('errors/html/error_403', ['message' => 'Akses ditolak'])
                );
            }

            $data['active'] = 'dashboard';
            $data['rombel'] = $this->get_kelas_by_tingkat();
            $data['aktif']  = $this->get_siswa_aktif_by_tingkat();
            $data['masuk']  = $this->get_siswa_masuk_by_tingkat();
            $data['keluar'] = $this->get_siswa_keluar_by_tingkat();

            // Summary cards
            $data['jumlah_guru']        = $this->db->table('guru')->countAllResults();
            $data['jumlah_tendik']      = $this->db->table('tendik')->countAllResults();
            $data['jumlah_siswa_aktif'] = $this->db->table('siswa')->where('status', 'aktif')->countAllResults();
            $data['jumlah_rombel']      = $this->db->table('kelas')->countAllResults();

            $data['lulus'] = $this->db->table('siswa')
                ->select('tahun_ajaran.tahun, COUNT(siswa.id) AS jumlah')
                ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.tahun_id', 'left')
                ->where('siswa.status', 'lulus')
                ->groupBy('tahun_ajaran.id')
                ->orderBy('tahun_ajaran.id', 'DESC')
                ->get()
                ->getResult();

            $data['per_rombel'] = $this->get_siswa_per_rombel();

            return $this->renderView('dashboard/index', $data);
        }

        // ==========================================
        // Public Dashboard (Unauthenticated)
        // ==========================================
        $data['rombel']         = $this->get_kelas_by_tingkat();
        $data['aktif']          = $this->get_siswa_aktif_by_tingkat();
        $data['masuk']          = $this->get_siswa_masuk_by_tingkat();
        $data['keluar']         = $this->get_siswa_keluar_by_tingkat();
        $data['per_rombel']     = $this->get_siswa_per_rombel();
        $data['gender_tingkat'] = $this->get_siswa_aktif_gender_by_tingkat();

        $data['lulus'] = $this->db->table('siswa')
            ->select('tahun_ajaran.tahun, COUNT(siswa.id) AS jumlah')
            ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.tahun_id', 'left')
            ->where('siswa.status', 'lulus')
            ->groupBy('tahun_ajaran.id')
            ->orderBy('tahun_ajaran.id', 'DESC')
            ->get()
            ->getResult();

        $data['tahun_aktif'] = $this->db->table('tahun_ajaran')
            ->where('aktif', 1)
            ->get()
            ->getRow();

        return view('dashboard/public', $data);
    }

    private function get_kelas_by_tingkat(): array
    {
        $out = ['x' => 0, 'xi' => 0, 'xii' => 0, 'total' => 0];
        $regex = [
            'x'   => "^X( |$|[^I])",
            'xi'  => "^XI( |$)",
            'xii' => "^XII( |$)",
        ];

        foreach ($regex as $k => $r) {
            $count = $this->db->table('kelas')
                ->where("nama REGEXP '$r'")
                ->countAllResults();
            $out[$k] = $count;
        }

        $out['total'] = $out['x'] + $out['xi'] + $out['xii'];
        return $out;
    }

    private function get_siswa_aktif_by_tingkat(): array
    {
        $out = ['x' => 0, 'xi' => 0, 'xii' => 0, 'total' => 0];
        $regex = [
            'x'   => "^X( |$)",
            'xi'  => "^XI( |$)",
            'xii' => "^XII( |$)",
        ];

        foreach ($regex as $k => $r) {
            $row = $this->db->table('siswa')
                ->select('COUNT(siswa.id) AS jumlah')
                ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
                ->where('siswa.status', 'aktif')
                ->where("kelas.nama REGEXP '$r'")
                ->get()
                ->getRow();

            $out[$k] = $row ? (int) $row->jumlah : 0;
        }

        $out['total'] = $out['x'] + $out['xi'] + $out['xii'];
        return $out;
    }

    private function get_siswa_aktif_gender_by_tingkat(): array
    {
        $out = [
            'x'     => ['l' => 0, 'p' => 0, 'total' => 0],
            'xi'    => ['l' => 0, 'p' => 0, 'total' => 0],
            'xii'   => ['l' => 0, 'p' => 0, 'total' => 0],
            'total' => ['l' => 0, 'p' => 0, 'total' => 0],
        ];

        $regex = [
            'x'   => "^X( |$)",
            'xi'  => "^XI( |$)",
            'xii' => "^XII( |$)",
        ];

        foreach ($regex as $tingkat => $r) {
            $q = $this->db->table('siswa')
                ->select("
                    SUM(CASE WHEN siswa.jk = 'L' THEN 1 ELSE 0 END) AS laki,
                    SUM(CASE WHEN siswa.jk = 'P' THEN 1 ELSE 0 END) AS perempuan,
                    COUNT(siswa.id) AS total
                ", false)
                ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
                ->where('siswa.status', 'aktif')
                ->where("kelas.nama REGEXP '$r'")
                ->get()
                ->getRow();

            $out[$tingkat]['l']     = (int) ($q->laki ?? 0);
            $out[$tingkat]['p']     = (int) ($q->perempuan ?? 0);
            $out[$tingkat]['total'] = (int) ($q->total ?? 0);

            $out['total']['l']     += $out[$tingkat]['l'];
            $out['total']['p']     += $out[$tingkat]['p'];
            $out['total']['total'] += $out[$tingkat]['total'];
        }

        return $out;
    }

    private function get_siswa_keluar_by_tingkat(): array
    {
        $out = ['x' => 0, 'xi' => 0, 'xii' => 0, 'total' => 0];
        $regex = [
            'x'   => "^X( |$)",
            'xi'  => "^XI( |$)",
            'xii' => "^XII( |$)",
        ];

        foreach ($regex as $k => $r) {
            $builder = $this->db->table('mutasi m')
                ->select('COUNT(m.id) AS jumlah')
                ->join('siswa s', 's.id = m.siswa_id', 'left')
                ->join('siswa_tahun st', 'st.siswa_id = s.id AND st.tahun_id = m.tahun_id', 'left')
                ->join('kelas k', 'k.id = st.kelas_id', 'left')
                ->where('m.jenis', 'keluar');

            if ($this->tahun_id) {
                $builder->where('m.tahun_id', $this->tahun_id);
            }

            $builder->where("k.nama REGEXP '$r'");
            $row = $builder->get()->getRow();

            $out[$k] = $row ? (int) $row->jumlah : 0;
        }

        $out['total'] = $out['x'] + $out['xi'] + $out['xii'];
        return $out;
    }

    private function get_siswa_masuk_by_tingkat(): array
    {
        $out = ['x' => 0, 'xi' => 0, 'xii' => 0, 'total' => 0];
        $regex = [
            'x'   => "^X( |$)",
            'xi'  => "^XI( |$)",
            'xii' => "^XII( |$)",
        ];

        foreach ($regex as $k => $r) {
            $builder = $this->db->table('mutasi m')
                ->select('COUNT(m.id) AS jumlah')
                ->join('siswa s', 's.id = m.siswa_id', 'left')
                ->join('kelas k', 'k.id = s.id_kelas', 'left')
                ->where('m.jenis', 'masuk');

            if ($this->tahun_id) {
                $builder->where('m.tahun_id', $this->tahun_id);
            }

            $builder->where("k.nama REGEXP '$r'");
            $row = $builder->get()->getRow();

            $out[$k] = $row ? (int) $row->jumlah : 0;
        }

        $out['total'] = $out['x'] + $out['xi'] + $out['xii'];
        return $out;
    }

    private function get_siswa_per_rombel(): array
    {
        return $this->db->table('siswa')
            ->select("
                kelas.nama AS nama_kelas,
                SUM(CASE WHEN siswa.jk = 'L' THEN 1 ELSE 0 END) AS laki,
                SUM(CASE WHEN siswa.jk = 'P' THEN 1 ELSE 0 END) AS perempuan,
                COUNT(siswa.id) AS total
            ", false)
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->where('siswa.status', 'aktif')
            ->groupBy('kelas.id')
            ->orderBy('kelas.nama', 'ASC')
            ->get()
            ->getResult();
    }

}
