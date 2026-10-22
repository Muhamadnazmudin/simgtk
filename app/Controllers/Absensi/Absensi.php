<?php

namespace App\Controllers\Absensi;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\KelasModel;
use App\Models\SiswaModel;

class Absensi extends BaseController
{
    protected AbsensiModel $absensiModel;
    protected KelasModel $kelasModel;
    protected SiswaModel $siswaModel;

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
        $this->kelasModel   = new KelasModel();
        $this->siswaModel   = new SiswaModel();
    }

    public function index()
    {
        $this->requireRole([1, 2, 3, 4, 5]);

        $absensi = $this->db->table('absensi_detail ad')
            ->select('
                ad.id_detail,
                ad.id_absensi,
                ad.status,
                ad.keterangan,
                a.tanggal,
                a.tahun_pelajaran,
                s.nama AS nama_siswa,
                k.nama AS nama_kelas,
                g.telp AS nohp_wali
            ')
            ->join('absensi a', 'a.id_absensi = ad.id_absensi')
            ->join('siswa s', 's.id = ad.id_siswa')
            ->join('kelas k', 'k.id = s.id_kelas', 'left')
            ->join('guru g', 'g.id = k.wali_kelas_id', 'left')
            ->orderBy('ad.id_detail', 'DESC')
            ->limit(200)
            ->get()
            ->getResult();

        $data = [
            'judul'   => 'Data Absensi',
            'active'  => 'absensi',
            'absensi' => $absensi,
        ];

        return $this->renderView('absensi/index', $data);
    }

    public function tambah()
    {
        $this->requireRole([1, 2, 3, 4, 5]);

        $data = [
            'judul'     => 'Tambah Absensi',
            'active'    => 'absensi',
            'kelas'     => $this->kelasModel->orderBy('nama', 'ASC')->findAll(),
            'siswa_all' => $this->siswaModel->select('id, nisn, nama, jk')->where('status', 'aktif')->orderBy('nama', 'ASC')->findAll(),
        ];

        return $this->renderView('absensi/tambah', $data);
    }

    public function form()
    {
        $this->requireRole([1, 2, 3, 4, 5]);

        $tanggal  = $this->request->getPost('tanggal');
        $idKelas  = (int) $this->request->getPost('id_kelas');
        $tahun    = (string) $this->request->getPost('tahun_pelajaran');

        $kelas = $this->kelasModel->find($idKelas);
        $siswa = $this->siswaModel->where('id_kelas', $idKelas)->where('status', 'aktif')->orderBy('nama', 'ASC')->findAll();

        $data = [
            'active'  => 'absensi',
            'tanggal' => $tanggal,
            'tahun'   => $tahun,
            'kelas'   => $kelas,
            'siswa'   => $siswa,
        ];

        return $this->renderView('absensi/form', $data);
    }

    public function simpan()
    {
        $this->requireRole([1, 2, 3, 4, 5]);

        $tanggal = (string) $this->request->getPost('tanggal');
        $idKelas = (int) $this->request->getPost('id_kelas');
        $idSiswa = (int) $this->request->getPost('id_siswa');
        $status  = (string) $this->request->getPost('status');
        $alasan  = (string) $this->request->getPost('keterangan');
        $tahun   = (string) ($this->request->getPost('tahun_pelajaran') ?: date('Y') . '/' . (date('Y') + 1));

        if (!$tanggal || !$idKelas || !$idSiswa || !$status) {
            $this->session->setFlashdata('error', 'Lengkapi semua data!');
            return redirect()->to(site_url('Absensi/Absensi'));
        }

        $idAbsensi = $this->absensiModel->getOrCreateAbsensi($tanggal, $idKelas, $tahun);

        // Check duplicate
        $cek = $this->db->table('absensi_detail')
            ->where('id_absensi', $idAbsensi)
            ->where('id_siswa', $idSiswa)
            ->get()
            ->getRow();

        if ($cek) {
            $this->session->setFlashdata('error', 'Siswa ini sudah memiliki catatan absensi pada tanggal tersebut.');
            return redirect()->to(site_url('Absensi/Absensi'));
        }

        $this->absensiModel->insertDetail([
            'id_absensi' => $idAbsensi,
            'id_siswa'   => $idSiswa,
            'status'     => $status,
            'keterangan' => $alasan,
        ]);

        $this->session->setFlashdata('success', 'Absensi berhasil disimpan.');
        return redirect()->to(site_url('Absensi/Absensi'));
    }

    public function detail($idAbsensi = null)
    {
        $this->requireRole([1, 2, 3, 4, 5]);

        $idAbsensi = (int) $idAbsensi;
        $absen = $this->absensiModel->getById($idAbsensi);
        if (!$absen) {
            $this->session->setFlashdata('error', 'Data absensi tidak ditemukan.');
            return redirect()->to(site_url('Absensi/Absensi'));
        }

        $siswa = $this->db->query("
            SELECT 
                s.id AS id_siswa,
                s.nama AS nama_siswa,
                IFNULL(
                    (SELECT UPPER(LEFT(status, 1)) 
                     FROM absensi_detail 
                     WHERE id_siswa = s.id 
                       AND id_absensi = ?
                    ),
                    'H'
                ) AS status
            FROM siswa s
            WHERE s.id_kelas = ? AND s.status = 'aktif'
            ORDER BY s.nama ASC
        ", [$idAbsensi, $absen->id_kelas])->getResult();

        $data = [
            'judul'  => 'Detail Absensi',
            'active' => 'absensi',
            'absen'  => $absen,
            'siswa'  => $siswa,
        ];

        return $this->renderView('absensi/detail', $data);
    }

    public function update()
    {
        $this->requireRole([1, 2, 3, 4, 5]);

        $idDetail = (int) $this->request->getPost('id_detail');
        $status   = (string) $this->request->getPost('status');
        $ket      = (string) $this->request->getPost('keterangan');
        $tanggal  = (string) $this->request->getPost('tanggal');

        $detail = $this->db->table('absensi_detail')->where('id_detail', $idDetail)->get()->getRow();
        if (!$detail) {
            $this->session->setFlashdata('error', 'Data absensi tidak ditemukan.');
            return redirect()->to(site_url('Absensi/Absensi'));
        }

        $header = $this->db->table('absensi')->where('id_absensi', $detail->id_absensi)->get()->getRow();
        if (!$header) {
            $this->session->setFlashdata('error', 'Header absensi tidak ditemukan.');
            return redirect()->to(site_url('Absensi/Absensi'));
        }

        if (!empty($tanggal)) {
            $this->db->table('absensi')->where('id_absensi', $detail->id_absensi)->update(['tanggal' => $tanggal]);
        }

        $this->db->table('absensi_detail')->where('id_detail', $idDetail)->update([
            'status'     => $status,
            'keterangan' => $ket,
        ]);

        $this->session->setFlashdata('success', 'Data absensi berhasil diperbarui.');
        return redirect()->to(site_url('Absensi/Absensi'));
    }

    public function ajax_siswa()
    {
        $this->requireAuth();
        $keyword = trim((string) $this->request->getPost('keyword'));

        $builder = $this->db->table('siswa s')
            ->select('s.id, s.nisn, s.nama, k.nama AS nama_kelas, s.id_kelas')
            ->join('kelas k', 'k.id = s.id_kelas', 'left')
            ->where('s.status', 'aktif');

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('s.nama', $keyword)
                ->orLike('s.nisn', $keyword)
                ->groupEnd();
        }

        $result = $builder->limit(20)->get()->getResult();
        return $this->response->setJSON($result);
    }

    public function hapus($idAbsensi = null)
    {
        $this->requireRole([1, 4, 5]);

        $idAbsensi = (int) $idAbsensi;
        $this->db->table('absensi_detail')->where('id_absensi', $idAbsensi)->delete();
        $this->db->table('absensi')->where('id_absensi', $idAbsensi)->delete();

        $this->session->setFlashdata('success', 'Data absensi berhasil dihapus.');
        return redirect()->to(site_url('Absensi/Absensi'));
    }
}
