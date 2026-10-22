<?php

namespace App\Controllers;

use App\Models\SiswaModel;

class Siswa_lulus extends BaseController
{
    protected SiswaModel $siswaModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
    }

    public function index()
    {
        $this->requireRole([1, 2]);

        $data = [
            'active' => 'siswa_lulus',
            'title'  => 'Data Siswa Lulus',
            'siswa'  => $this->siswaModel->get_by_status(['lulus']),
        ];

        return $this->renderView('siswa/lulus', $data);
    }
}
