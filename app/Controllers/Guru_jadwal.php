<?php

namespace App\Controllers;

use App\Models\Guru_jadwal_model;

class Guru_jadwal extends BaseController
{
    protected Guru_jadwal_model $jadwalModel;

    public function __construct()
    {
        $this->jadwalModel = new Guru_jadwal_model();
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
            'title'  => 'Jadwal Mengajar Saya',
            'active' => 'guru_jadwal',
            'jadwal' => $this->jadwalModel->get_by_guru($guruId),
        ];

        return $this->compatView->render('templates/header', $data)
            . $this->compatView->render('templates/sidebar_guru', $data)
            . $this->compatView->render('guru/jadwal', $data)
            . $this->compatView->render('templates/footer');
    }
}
