<?php

namespace App\Controllers;

use App\Models\UserModel;

class Guru_account extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    private function checkGuru(): void
    {
        $this->requireAuth();
        $roleId = (int) $this->session->get('role_id');
        if (!in_array($roleId, [3, 4], true)) {
            header('Location: ' . site_url('dashboard'));
            exit;
        }
    }

    public function index()
    {
        $this->checkGuru();

        $userId = (int) $this->session->get('user_id');
        $user   = $this->userModel->get_by_id($userId);

        $data = [
            'title'  => 'Profile',
            'active' => 'guru_account',
            'user'   => $user,
        ];

        return $this->compatView->render('templates/header', $data)
            . $this->compatView->render('templates/sidebar_guru', $data)
            . $this->compatView->render('guru/account', $data)
            . $this->compatView->render('templates/footer');
    }

    public function update_password()
    {
        $this->checkGuru();

        $rules = [
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'matches[password]',
        ];

        if (!$this->validate($rules)) {
            $this->session->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->to(site_url('guru_account'));
        }

        $userId = (int) $this->session->get('user_id');
        $password = (string) $this->request->getPost('password');

        $this->userModel->update($userId, [
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        $this->session->setFlashdata('success', 'Password berhasil diperbarui.');
        return redirect()->to(site_url('guru_account'));
    }

    public function upload_foto()
    {
        $this->checkGuru();

        $file = $this->request->getFile('foto');
        if (!$file || !$file->isValid()) {
            $this->session->setFlashdata('error', 'Silakan pilih foto yang valid.');
            return redirect()->to(site_url('guru_account'));
        }

        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file->getMimeType(), $allowedMimes, true)) {
            $this->session->setFlashdata('error', 'Format foto harus JPG, JPEG, atau PNG.');
            return redirect()->to(site_url('guru_account'));
        }

        if ($file->getSize() > 2 * 1024 * 1024) {
            $this->session->setFlashdata('error', 'Ukuran foto maksimal 2MB.');
            return redirect()->to(site_url('guru_account'));
        }

        $uploadDir = FCPATH . 'uploads/profile/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadDir, $newName);

        $userId = (int) $this->session->get('user_id');
        $this->userModel->update($userId, ['foto' => $newName]);

        $this->session->setFlashdata('success', 'Foto profil berhasil diperbarui.');
        return redirect()->to(site_url('guru_account'));
    }
}
