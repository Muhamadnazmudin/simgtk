<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * Controller Users Management - CodeIgniter 4
 */
class Users extends BaseController
{
    protected UserModel $userModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->userModel = new UserModel();

        if (!$this->session->get('logged_in') || (int) $this->session->get('role_id') !== 1) {
            header('Location: ' . base_url('auth/login'));
            exit;
        }
    }

    public function index()
    {
        $data = [
            'title'  => 'Manajemen User',
            'active' => 'users',
            'users'  => $this->userModel->get_all(),
            'roles'  => $this->userModel->get_roles(),
        ];

        return $this->renderView('users/index', $data);
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $rules = [
                'username' => 'required|is_unique[users.username]',
                'password' => 'required|min_length[6]',
                'nama'     => 'required',
                'role_id'  => 'required',
            ];

            if (!$this->validate($rules)) {
                return redirect()->to('/users')->with('error', implode('<br>', $this->validator->getErrors()));
            }

            $data = [
                'username' => trim((string) $this->request->getPost('username')),
                'password' => (string) $this->request->getPost('password'),
                'nama'     => trim((string) $this->request->getPost('nama')),
                'email'    => trim((string) $this->request->getPost('email')),
                'role_id'  => (int) $this->request->getPost('role_id'),
            ];

            $this->userModel->create_user($data);
            return redirect()->to('/users')->with('success', 'User baru berhasil ditambahkan.');
        }

        return redirect()->to('/users');
    }

    public function edit($id = null)
    {
        $user = $this->userModel->get_by_id((int) $id);
        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('User tidak ditemukan.');
        }

        if ($this->request->is('post')) {
            $updateData = [
                'nama'    => trim((string) $this->request->getPost('nama')),
                'email'   => trim((string) $this->request->getPost('email')),
                'role_id' => (int) $this->request->getPost('role_id'),
            ];

            $password = (string) $this->request->getPost('password');
            if (!empty($password)) {
                $updateData['password'] = $password;
            }

            $this->userModel->update_user((int) $id, $updateData);
            return redirect()->to('/users')->with('success', 'Data user berhasil diperbarui.');
        }

        return redirect()->to('/users');
    }

    public function delete($id = null)
    {
        $this->userModel->delete((int) $id);
        return redirect()->to('/users')->with('success', 'User berhasil dihapus.');
    }
}
