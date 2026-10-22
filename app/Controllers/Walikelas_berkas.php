<?php

namespace App\Controllers;

class Walikelas_berkas extends BaseController
{
    private function checkWalikelas(): ?object
    {
        $this->requireAuth();

        $guruId = $this->session->get('guru_id');
        if (!$guruId) {
            header('Location: ' . site_url('guru_dashboard'));
            exit;
        }

        $kelas = $this->db->table('kelas')->where('wali_kelas_id', $guruId)->get()->getRow();
        if (!$kelas) {
            header('Location: ' . site_url('guru_dashboard'));
            exit;
        }

        $this->session->set([
            'kelas_id'   => (int) $kelas->id,
            'kelas_nama' => $kelas->nama,
        ]);

        return $kelas;
    }

    public function index()
    {
        $kelas = $this->checkWalikelas();
        $kelasId = (int) $kelas->id;
        $kelasNama = $kelas->nama;

        $siswa = $this->db->table('siswa')
            ->select('siswa.id, siswa.nis, siswa.nisn, siswa.nama, siswa.id_kelas, kelas.nama AS nama_kelas')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->where('siswa.id_kelas', $kelasId)
            ->where('siswa.status', 'aktif')
            ->orderBy('siswa.nama', 'ASC')
            ->get()
            ->getResult();

        foreach ($siswa as $s) {
            $s->jumlah_berkas = $this->db->table('siswa_berkas')
                ->where('siswa_id', $s->id)
                ->countAllResults();
        }

        $data = [
            'title'      => 'Berkas Siswa',
            'active'     => 'wk_berkas',
            'kelas_nama' => $kelasNama,
            'siswa'      => $siswa,
        ];

        return $this->compatView->render('walikelas/templates/header', $data)
            . $this->compatView->render('walikelas/templates/sidebar', $data)
            . $this->compatView->render('walikelas/berkas/index', $data)
            . $this->compatView->render('walikelas/templates/footer', $data);
    }

    public function detail($siswaId = 0)
    {
        $kelas = $this->checkWalikelas();
        $kelasId = (int) $kelas->id;
        $siswaId = (int) $siswaId;

        if (!$siswaId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Siswa tidak ditemukan.');
        }

        $siswa = $this->db->table('siswa')
            ->select('siswa.id, siswa.nis, siswa.nisn, siswa.nama, siswa.id_kelas, kelas.nama AS nama_kelas')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->where('siswa.id', $siswaId)
            ->where('siswa.id_kelas', $kelasId)
            ->where('siswa.status', 'aktif')
            ->get()
            ->getRow();

        if (!$siswa) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Siswa tidak ditemukan atau bukan siswa kelas Anda.');
        }

        $berkas = $this->db->table('siswa_berkas')
            ->where('siswa_id', $siswaId)
            ->orderBy('jenis_berkas', 'ASC')
            ->get()
            ->getResult();

        $data = [
            'title'      => 'Berkas Siswa - ' . $siswa->nama,
            'active'     => 'wk_berkas',
            'kelas_nama' => $kelas->nama,
            'siswa'      => $siswa,
            'berkas'     => $berkas,
        ];

        return $this->compatView->render('walikelas/templates/header', $data)
            . $this->compatView->render('walikelas/templates/sidebar', $data)
            . $this->compatView->render('walikelas/berkas/detail', $data)
            . $this->compatView->render('walikelas/templates/footer', $data);
    }
}
