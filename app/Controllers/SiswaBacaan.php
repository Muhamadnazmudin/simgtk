<?php

namespace App\Controllers;

use App\Models\BacaanModel;

class SiswaBacaan extends BaseController
{
    protected BacaanModel $model;

    public function __construct()
    {
        $this->model = new BacaanModel();
    }

    private function cekLogin()
    {
        if (!$this->session->get('siswa_login')) {
            header('Location: ' . base_url('siswa-login'));
            exit;
        }
    }

    public function index()
    {
        $this->cekLogin();

        $kelas = $this->request->getGet('kelas');
        $mapel = $this->request->getGet('mapel');

        $limit  = 12;
        $page   = (int) ($this->request->getGet('page') ?? 1);
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        $total = $this->model->countFiltered($kelas, $mapel);
        $buku  = $this->model->getFiltered($limit, $offset, $kelas, $mapel);

        $pager = service('pager');
        $pagerLinks = $pager->makeLinks($page, $limit, $total, 'default_full');

        $data = [
            'title'        => 'Bacaan / E-Book Siswa',
            'active'       => 'bacaan',
            'buku'         => $buku,
            'pagination'   => $pagerLinks,
            'filter_kelas' => $kelas,
            'filter_mapel' => $mapel,
            'mapel_list'   => $this->model->getMapelUnik(),
        ];

        return $this->compatView->render('siswa/layout/header', $data)
             . $this->compatView->render('siswa/layout/sidebar', $data)
             . $this->compatView->render('siswa/bacaan/index', $data)
             . $this->compatView->render('siswa/layout/footer', $data);
    }

    public function baca($id = null)
    {
        $this->cekLogin();

        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $buku = $this->model->getById((int) $id);
        if (!$buku) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Buku tidak ditemukan.');
        }

        $data = [
            'title'  => $buku->judul,
            'active' => 'bacaan',
            'buku'   => $buku,
        ];

        return $this->compatView->render('siswa/layout/header', $data)
             . $this->compatView->render('siswa/layout/sidebar', $data)
             . $this->compatView->render('siswa/bacaan/baca', $data)
             . $this->compatView->render('siswa/layout/footer', $data);
    }
}
