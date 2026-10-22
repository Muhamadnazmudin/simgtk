<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\UserModel;

class Guru_profile extends BaseController
{
    protected GuruModel $guruModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->guruModel = new GuruModel();
        $this->userModel = new UserModel();
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
        $userId = (int) $this->session->get('user_id');

        $data = [
            'guru'   => $this->guruModel->get_by_id($guruId),
            'user'   => $this->userModel->get_by_id($userId),
            'title'  => 'Data Guru',
            'active' => 'guru_profile',
        ];

        return $this->compatView->render('templates/header', $data)
            . $this->compatView->render('templates/sidebar_guru', $data)
            . $this->compatView->render('guru/profile', $data)
            . $this->compatView->render('templates/footer');
    }
}
