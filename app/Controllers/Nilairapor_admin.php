<?php

namespace App\Controllers;

use App\Models\NilaiRaporModel;
use App\Models\SiswaModel;
use App\Models\MapelModel;

class Nilairapor_admin extends BaseController
{
    protected NilaiRaporModel $nilaiModel;
    protected SiswaModel $siswaModel;
    protected MapelModel $mapelModel;

    public function __construct()
    {
        $this->nilaiModel = new NilaiRaporModel();
        $this->siswaModel = new SiswaModel();
        $this->mapelModel = new MapelModel();
    }

    public function index()
    {
        $this->requireRole([1, 4, 5]);

        $data = [
            'active' => 'nilai_input',
            'title'  => 'Input Nilai Rapor',
            'siswa'  => $this->siswaModel->select('id, nisn, nama, jk')->where('status', 'aktif')->orderBy('nama', 'ASC')->findAll(),
            'mapel'  => $this->mapelModel->orderBy('nama_mapel', 'ASC')->findAll(),
        ];

        return $this->renderView('admin/nilai_rapor/index', $data);
    }

    public function simpan()
    {
        $this->requireRole([1, 4, 5]);

        $siswaId = (int) $this->request->getPost('siswa_id');
        $mapelId = (int) $this->request->getPost('mapel_id');
        $semester = (int) $this->request->getPost('semester');
        $nilai    = (float) $this->request->getPost('nilai_angka');
        $tahunId  = $this->tahunAktifId();

        $this->nilaiModel->insertOrUpdate([
            'siswa_id'    => $siswaId,
            'mapel_id'    => $mapelId,
            'semester'    => $semester,
            'tahun_id'    => $tahunId,
            'nilai_angka' => $nilai,
        ]);

        $this->session->setFlashdata('success', 'Nilai berhasil disimpan / diperbarui.');
        return redirect()->to(site_url('Nilairapor_admin'));
    }

    public function rekap()
    {
        $this->requireRole([1, 4, 5]);

        $kelasId = $this->request->getGet('kelas') ?: null;
        $tahunId = $this->tahunAktifId();

        $perPage = 100;
        $page    = (int) ($this->request->getGet('page') ?: 1);
        $offset  = ($page > 1) ? ($page - 1) * $perPage : 0;

        $totalRows = $this->nilaiModel->countRekapSiswa($kelasId, $tahunId);
        $rekap     = $this->nilaiModel->rekapSiswa($kelasId, $tahunId, $perPage, $offset);
        $mapelList = $this->nilaiModel->getMapelRekap($kelasId, $tahunId);

        $pager = \Config\Services::pager();
        $pager->makeLinks($page, $perPage, $totalRows, 'default_full');

        $data = [
            'active'     => 'nilai_daftar',
            'title'      => 'Rekap Nilai Rapor',
            'rekap'      => $rekap,
            'mapel'      => $mapelList,
            'kelas_id'   => $kelasId,
            'pagination' => $pager->links(),
        ];

        return $this->renderView('admin/nilai_rapor/rekap', $data);
    }
}
