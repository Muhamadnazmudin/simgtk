<?php

namespace App\Controllers;

use App\Models\SiswaKeluarModel;

class Siswa_keluar extends BaseController
{
    protected SiswaKeluarModel $model;

    public function __construct()
    {
        $this->model = new SiswaKeluarModel();
    }

    public function index()
    {
        $this->requireRole([1, 2]);

        $data = [
            'active' => 'siswa_keluar',
            'title'  => 'Data Siswa Keluar',
            'siswa'  => $this->model->getData(),
        ];

        return $this->renderView('siswa/keluar', $data);
    }
}
