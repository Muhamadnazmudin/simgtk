<?php

namespace App\Controllers;

use App\Models\IzinModel;
use App\Models\GuruModel;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class Izin extends BaseController
{
    protected IzinModel $izinModel;
    protected GuruModel $guruModel;

    public function __construct()
    {
        $this->izinModel = new IzinModel();
        $this->guruModel = new GuruModel();
    }

    public function index($offset = 0)
    {
        $this->requireAuth();

        $perPage = 10;
        $totalRows = $this->izinModel->countAllResults();

        $page = (int) ($this->request->getGet('page') ?: 0);
        if ($page > 0) {
            $offset = ($page - 1) * $perPage;
        } elseif ((int) $offset > 0) {
            $offset = (int) $offset;
            $page   = (int) floor($offset / $perPage) + 1;
        } else {
            $page   = 1;
            $offset = 0;
        }

        $izin = $this->izinModel->orderBy('id', 'DESC')->findAll($perPage, $offset);

        $pager = \Config\Services::pager();
        $paginationHtml = $pager->makeLinks($page, $perPage, $totalRows, 'bootstrap_pagination');

        $data = [
            'active'     => 'izin',
            'title'      => 'Monitoring Izin Keluar',
            'izin'       => $izin,
            'pagination' => $paginationHtml,
            'start'      => $offset,
            'page'       => $page,
            'total'      => $totalRows,
        ];

        return $this->renderView('izin/index', $data);
    }

    public function scan(?string $token = null)
    {
        $this->requireAuth();

        if ($token !== null && strpos($token, 'http') !== false) {
            $parts = explode('/', $token);
            $token = end($parts);
        }

        if ($token !== null && strpos($token, 'kembali_') === 0) {
            return redirect()->to(site_url('izin/kembali/' . $token));
        }

        if ($token === null || $token === '') {
            return $this->compatView->render('izin/scan');
        }

        $siswa = $this->izinModel->getSiswaByToken($token);
        if (!$siswa) {
            return $this->response->setBody('<h3>QR Code tidak valid!</h3>');
        }

        $kelas = $this->db->table('kelas')->where('id', $siswa->kelas_id)->get()->getRow();
        $walikelas = null;
        if ($kelas && !empty($kelas->wali_kelas_id)) {
            $walikelas = $this->db->table('guru')->where('id', $kelas->wali_kelas_id)->get()->getRow();
        }

        $guruList = $this->guruModel->orderBy('nama', 'ASC')->findAll();

        $data = [
            'siswa'     => $siswa,
            'kelas'     => $kelas,
            'walikelas' => $walikelas,
            'guru_list' => $guruList,
            'token_qr'  => $token,
        ];

        return $this->compatView->render('izin/scan_form', $data);
    }

    public function ajukan(string $tokenQr)
    {
        $this->requireAuth();

        $siswa = $this->izinModel->getSiswaByToken($tokenQr);
        if (!$siswa) {
            return $this->response->setBody('Token QR tidak valid.');
        }

        $kelas = $this->db->table('kelas')->where('id', $siswa->kelas_id)->get()->getRow();

        $jenis     = (string) $this->request->getPost('jenis');
        $keperluan = (string) $this->request->getPost('keperluan');
        $estimasi  = $this->request->getPost('estimasi');

        if (empty($jenis) || empty($keperluan)) {
            $this->session->setFlashdata('error', 'Jenis izin dan keperluan harus diisi.');
            return redirect()->to(site_url('izin/scan/' . $tokenQr));
        }

        $tokenKembali = null;
        if ($jenis === 'keluar') {
            $tokenKembali = uniqid('kembali_', true);
        }

        $dataInsert = [
            'siswa_id'       => $siswa->id,
            'nis'            => $siswa->nis ?? null,
            'nama'           => $siswa->nama,
            'kelas_id'       => $siswa->kelas_id,
            'kelas_nama'     => $kelas ? $kelas->nama : '-',
            'keperluan'      => $keperluan,
            'estimasi_menit' => ($jenis === 'keluar') ? $estimasi : null,
            'jam_keluar'     => date('Y-m-d H:i:s'),
            'token_keluar'   => $tokenQr,
            'token_kembali'  => $tokenKembali,
            'status'         => ($jenis === 'pulang') ? 'pulang' : 'keluar',
            'jenis_izin'     => $jenis,
            'id_guru_mapel'  => $this->request->getPost('id_guru_mapel') ?: null,
            'id_piket'       => $this->request->getPost('id_piket') ?: null,
            'id_walikelas'   => $this->request->getPost('id_walikelas') ?: null,
            'ditujukan'      => $this->request->getPost('ditujukan') ?: null,
        ];

        $this->izinModel->insert($dataInsert);
        $insertId = $this->db->insertID();

        return redirect()->to(site_url('izin/cetak/' . $insertId));
    }

    public function kembali(string $tokenKembali)
    {
        $this->requireAuth();

        $izin = $this->izinModel->getIzinByTokenKembali($tokenKembali);
        if (!$izin) {
            return $this->response->setBody('<h3>Token kembali tidak valid.</h3>');
        }

        if ($izin->status === 'kembali') {
            return $this->compatView->render('izin/kembali_duplikat', ['izin' => $izin]);
        }

        $this->izinModel->setKembali((int) $izin->id);
        return $this->compatView->render('izin/kembali_sukses');
    }

    public function cetak($id = null)
    {
        $this->requireAuth();

        $izin = $this->izinModel->find($id);
        if (!$izin) {
            return $this->response->setBody('Data tidak ditemukan');
        }

        $dummy = (object) ['nama' => '-', 'nip' => '-'];
        $guruMapel = $izin->id_guru_mapel ? $this->guruModel->find($izin->id_guru_mapel) : $dummy;
        $piket     = $izin->id_piket ? $this->guruModel->find($izin->id_piket) : $dummy;
        $walikelas = $izin->id_walikelas ? $this->guruModel->find($izin->id_walikelas) : $dummy;

        $data = [
            'izin'       => $izin,
            'guru_mapel' => $guruMapel ?: $dummy,
            'piket'      => $piket ?: $dummy,
            'walikelas'  => $walikelas ?: $dummy,
        ];

        if ($izin->jenis_izin === 'pulang') {
            return $this->compatView->render('izin/cetak_pulang', $data);
        }

        return $this->compatView->render('izin/cetak_keluar', $data);
    }

    public function edit($id = null)
    {
        $this->requireAuth();

        $izin = $this->izinModel->find($id);
        if (!$izin) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'active' => 'izin',
            'title'  => 'Edit Data Izin',
            'izin'   => $izin,
        ];

        return $this->renderView('izin/edit', $data);
    }

    public function update()
    {
        $this->requireAuth();

        $id = (int) $this->request->getPost('id');
        $izin = $this->izinModel->find($id);
        if (!$izin) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $dataUpdate = [
            'jam_masuk' => $this->request->getPost('jam_masuk') ?: null,
            'status'    => (string) $this->request->getPost('status'),
        ];

        $this->izinModel->update($id, $dataUpdate);
        $this->session->setFlashdata('success', 'Data izin berhasil diperbarui.');
        return redirect()->to(site_url('izin'));
    }

    public function delete($id = null)
    {
        $this->requireAuth();

        $id = (int) $id;
        $izin = $this->izinModel->find($id);
        if (!$izin) {
            $this->session->setFlashdata('error', 'Data tidak ditemukan.');
            return redirect()->to(site_url('izin'));
        }

        $this->izinModel->delete($id);
        $this->session->setFlashdata('success', 'Data izin berhasil dihapus.');
        return redirect()->to(site_url('izin'));
    }

    public function rfid()
    {
        $this->requireAuth();
        $data = ['active' => 'izin', 'title' => 'Scan RFID Izin'];
        return $this->renderView('izin/rfid_scan', $data);
    }

    public function laporan()
    {
        $this->requireAuth();

        $dari   = $this->request->getGet('dari') ?: date('Y-m-01');
        $sampai = $this->request->getGet('sampai') ?: date('Y-m-d');
        $kelas  = $this->request->getGet('kelas') ?: null;
        $status = $this->request->getGet('status') ?: null;

        $builder = $this->db->table('izin_keluar ik')
            ->select('ik.*, DATE(ik.jam_keluar) AS tanggal, COALESCE(s.nama, ik.nama) AS nama_siswa, s.nisn, COALESCE(k.nama, ik.kelas_nama) AS nama_kelas, gm.nama AS nama_guru_mapel, gp.nama AS nama_guru_piket')
            ->join('siswa s', 's.id = ik.siswa_id', 'left')
            ->join('kelas k', 'k.id = ik.kelas_id', 'left')
            ->join('guru gm', 'gm.id = ik.id_guru_mapel', 'left')
            ->join('guru gp', 'gp.id = ik.id_piket', 'left')
            ->where('ik.jam_keluar >=', $dari . ' 00:00:00')
            ->where('ik.jam_keluar <=', $sampai . ' 23:59:59');

        if (!empty($kelas)) {
            $builder->where('ik.kelas_id', $kelas);
        }

        if (!empty($status)) {
            $builder->where('ik.status', $status);
        }

        $list = $builder->orderBy('ik.id', 'DESC')->get()->getResult();

        $data = [
            'active'     => 'izin_laporan',
            'title'      => 'Laporan Izin Keluar Siswa',
            'list'       => $list,
            'kelas'      => $this->db->table('kelas')->where('is_active', 1)->orderBy('nama', 'ASC')->get()->getResult(),
            'dari'       => $dari,
            'sampai'     => $sampai,
            'filter_kls' => $kelas,
            'filter_st'  => $status,
        ];

        return $this->renderView('izin/laporan', $data);
    }

    public function rfid_process()
    {
        if (!$this->session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Session expired']);
        }

        $rfid = trim((string) $this->request->getPost('rfid'));
        if (empty($rfid)) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'RFID Kosong']);
        }

        $siswa = $this->db->table('siswa')->where('rfid_uid', $rfid)->get()->getRow();
        if (!$siswa) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Kartu RFID belum terdaftar!']);
        }

        $tokenQr = $siswa->nisn ?: $siswa->token_qr;
        return $this->response->setJSON([
            'status'   => 'success',
            'redirect' => site_url('izin/scan/' . $tokenQr),
        ]);
    }
}
