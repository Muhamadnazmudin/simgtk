<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\TahunModel;

class Kenaikan extends BaseController
{
    protected SiswaModel $siswaModel;
    protected KelasModel $kelasModel;
    protected TahunModel $tahunModel;
    protected int $tahunAktif;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
        $this->tahunModel = new TahunModel();
        $aktif = $this->tahunModel->get_aktif();
        $this->tahunAktif = $aktif ? (int) $aktif->id : 0;
    }

    public function index()
    {
        $this->requireRole([1, 2]);

        $kelasId = $this->request->getGet('kelas_id');

        if ($kelasId) {
            $siswa = $this->siswaModel->get_by_kelas((int) $kelasId);
            $kelasSekarang = $this->kelasModel->get_by_id((int) $kelasId);
        } else {
            $siswa = [];
            $kelasSekarang = null;
        }

        $data = [
            'title'          => 'Kenaikan Kelas Siswa',
            'active'         => 'kenaikan',
            'tahun'          => $this->tahunModel->get_aktif(),
            'kelas'          => $this->kelasModel->get_all_simple(),
            'siswa'          => $siswa,
            'kelas_sekarang' => $kelasSekarang,
        ];

        return $this->renderView('kenaikan/index', $data);
    }

    private function prosesSiswaTahun(int $idSiswa, $kelasLama, $kelasBaru, int $tahunBaru): void
    {
        $kelasBaruFix = ($kelasBaru === '' || $kelasBaru === null) ? null : (int) $kelasBaru;

        // 1) Simpan riwayat tahun lama
        $this->db->table('siswa_history')->insert([
            'siswa_id' => $idSiswa,
            'kelas_id' => $kelasLama,
            'tahun_id' => $this->tahunAktif,
            'status'   => 'aktif',
        ]);

        // 2) Tambah entri siswa_tahun jika ada kelas tujuan
        if ($kelasBaruFix !== null) {
            $this->db->table('siswa_tahun')->insert([
                'siswa_id' => $idSiswa,
                'kelas_id' => $kelasBaruFix,
                'tahun_id' => $tahunBaru,
                'status'   => 'aktif',
            ]);
        }

        // 3) Update tabel siswa
        $this->db->table('siswa')->where('id', $idSiswa)->update([
            'id_kelas' => $kelasBaruFix,
            'tahun_id' => $tahunBaru,
        ]);
    }

    public function naik_manual($idSiswa = null)
    {
        $this->requireRole([1, 2]);

        if (!$idSiswa) {
            return redirect()->to(site_url('kenaikan'));
        }

        $idSiswa    = (int) $idSiswa;
        $kelasBaru  = $this->request->getPost('kelas_tujuan');
        $tahunAktif = $this->tahunModel->get_aktif();
        $tahunBaru  = $tahunAktif ? (int) $tahunAktif->id : $this->tahunAktif;

        $siswa = $this->siswaModel->get_by_id($idSiswa);
        if ($siswa) {
            $this->prosesSiswaTahun($idSiswa, $siswa->id_kelas, $kelasBaru, $tahunBaru);
            $this->session->setFlashdata('success', 'Siswa berhasil dinaikkan.');
        }

        return redirect()->to(site_url('kenaikan'));
    }

    public function luluskan($idSiswa = null)
    {
        $this->requireRole([1, 2]);

        if (!$idSiswa) {
            return redirect()->to(site_url('kenaikan'));
        }

        $idSiswa = (int) $idSiswa;
        $siswa   = $this->siswaModel->get_by_id($idSiswa);

        if ($siswa) {
            $this->db->table('siswa_history')->insert([
                'siswa_id' => $idSiswa,
                'kelas_id' => $siswa->id_kelas,
                'tahun_id' => $this->tahunAktif,
                'status'   => 'lulus',
            ]);

            $this->siswaModel->update($idSiswa, ['status' => 'lulus']);
            $this->session->setFlashdata('success', 'Siswa berhasil diluluskan.');
        }

        return redirect()->to(site_url('kenaikan'));
    }

    public function simpan_massal()
    {
        $this->requireRole([1, 2]);

        $postSiswaId     = (array) $this->request->getPost('siswa_id');
        $postKelasTujuan = (array) $this->request->getPost('kelas_tujuan');
        $tahunAktif      = $this->tahunModel->get_aktif();
        $tahunBaru       = $tahunAktif ? (int) $tahunAktif->id : $this->tahunAktif;

        if (!empty($postSiswaId)) {
            foreach ($postSiswaId as $i => $idSiswa) {
                $kelasBaru = $postKelasTujuan[$i] ?? null;
                if ($kelasBaru === '' || $kelasBaru === null) {
                    continue;
                }

                $idSiswa = (int) $idSiswa;
                $siswa   = $this->siswaModel->get_by_id($idSiswa);
                if (!$siswa) {
                    continue;
                }

                $kelasLama = $siswa->id_kelas;

                if ($kelasBaru === 'lulus') {
                    $this->db->table('siswa_history')->insert([
                        'siswa_id' => $idSiswa,
                        'kelas_id' => $kelasLama,
                        'tahun_id' => $this->tahunAktif,
                        'status'   => 'lulus',
                    ]);

                    $this->siswaModel->update($idSiswa, ['status' => 'lulus']);
                } else {
                    $this->prosesSiswaTahun($idSiswa, $kelasLama, $kelasBaru, $tahunBaru);
                }
            }

            $this->session->setFlashdata('success', 'Kenaikan massal berhasil.');
        }

        $kelasId = $this->request->getPost('kelas_id');
        return redirect()->to(site_url('kenaikan' . ($kelasId ? '?kelas_id=' . $kelasId : '')));
    }

    public function naik_otomatis()
    {
        $this->requireRole([1, 2]);

        $tahunBaru = $this->tahunAktif;
        $siswaXi   = $this->siswaModel->get_by_kelas_pattern('XI');
        $kelasXii  = $this->kelasModel->get_by_pattern('XII');

        if ($kelasXii) {
            foreach ($siswaXi as $s) {
                $this->prosesSiswaTahun((int) $s->id, $s->id_kelas, $kelasXii->id, $tahunBaru);
            }
            $this->session->setFlashdata('success', 'Kenaikan otomatis berhasil!');
        } else {
            $this->session->setFlashdata('error', 'Kelas XII tujuan tidak ditemukan.');
        }

        return redirect()->to(site_url('kenaikan'));
    }
}
