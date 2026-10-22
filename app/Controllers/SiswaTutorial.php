<?php

namespace App\Controllers;

use App\Models\TutorialModel;

class SiswaTutorial extends BaseController
{
    protected TutorialModel $model;

    public function __construct()
    {
        $this->model = new TutorialModel();
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

        $data = [
            'title'    => 'Kumpulan Video Tutorial',
            'active'   => 'tutorial',
            'tutorial' => $this->model->getAll(),
        ];

        return $this->compatView->render('siswa/layout/header', $data)
             . $this->compatView->render('siswa/layout/sidebar', $data)
             . $this->compatView->render('siswa/tutorial/index', $data)
             . $this->compatView->render('siswa/layout/footer', $data);
    }

    public function lihat($id = null)
    {
        $this->cekLogin();

        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $row = $this->model->find((int) $id);
        if (!$row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Tutorial tidak ditemukan.');
        }

        $data = [
            'title'  => $row->judul,
            'active' => 'tutorial',
            'row'    => $row,
        ];

        return $this->compatView->render('siswa/layout/header', $data)
             . $this->compatView->render('siswa/layout/sidebar', $data)
             . $this->compatView->render('siswa/tutorial/lihat', $data)
             . $this->compatView->render('siswa/layout/footer', $data);
    }
}
