<?php

namespace App\Controllers;

use App\Models\Cek_jjm_model;

class Cek_jjm extends BaseController
{
    protected Cek_jjm_model $jjmModel;

    public function __construct()
    {
        $this->jjmModel = new Cek_jjm_model();
    }

    public function index()
    {
        $this->requireAuth();
        $roleId = (int) $this->session->get('role_id');
        if (!in_array($roleId, [3, 4], true)) {
            header('Location: ' . site_url('dashboard'));
            exit;
        }

        $guruId = (int) $this->session->get('guru_id');

        $data = [
            'title'  => 'Cek JJM',
            'active' => 'cek_jjm',
            'jjm'    => $this->jjmModel->get_by_guru($guruId),
        ];

        return $this->compatView->render('templates/header', $data)
            . $this->compatView->render('templates/sidebar_guru', $data)
            . $this->compatView->render('guru/cek_jjm', $data)
            . $this->compatView->render('templates/footer');
    }
}
