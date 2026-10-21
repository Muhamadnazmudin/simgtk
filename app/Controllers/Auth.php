<?php

namespace App\Controllers;

use App\Models\TahunModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Auth Controller - CodeIgniter 4
 * Secure authentication with rate limiting and session fixation defense.
 */
class Auth extends BaseController
{
    protected UserModel $userModel;
    protected TahunModel $tahunModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->userModel  = new UserModel();
        $this->tahunModel = new TahunModel();
    }

    public function index()
    {
        return $this->login();
    }

    public function login()
    {
        // If already logged in, redirect based on role
        if ($this->session->get('logged_in')) {
            return $this->redirectByRole((int) $this->session->get('role_id'));
        }

        if ($this->request->is('post')) {
            // Apply Rate Limiting: max 10 login attempts per minute per IP
            $throttler = service('throttler');
            $ip = $this->request->getIPAddress();
            if ($throttler->check('login_attempt_' . md5($ip), 10, 60) === false) {
                return redirect()->to('/auth/login')
                    ->with('error', 'Terlalu banyak percobaan login. Silakan tunggu 1 menit.');
            }

            $username = trim((string) $this->request->getPost('username'));
            $password = (string) $this->request->getPost('password');
            $tahunId  = (int) $this->request->getPost('tahun_id');

            if ($username === '' || $password === '') {
                return redirect()->to('/auth/login')
                    ->with('error', 'Username dan password wajib diisi.');
            }

            $user = $this->userModel->get_by_username($username);

            if ($user && password_verify($password, $user->password)) {
                // Prevent Session Fixation attack
                $this->session->regenerate(true);

                // Update last login
                $this->userModel->update_last_login((int) $user->id);

                // Basic session payload
                $sessionData = [
                    'logged_in' => true,
                    'user_id'   => (int) $user->id,
                    'username'  => $user->username,
                    'nama'      => $user->nama,
                    'role_id'   => (int) $user->role_id,
                    'guru_id'   => $user->guru_id ? (int) $user->guru_id : null,
                    'role_name' => $this->userModel->role_name((int) $user->role_id),
                    'tahun_id'  => $tahunId,
                ];

                // Check if user is also Walikelas
                if ((int) $user->role_id === 3 || (int) $user->role_id === 4) {
                    $kelas = $this->db->table('kelas')
                        ->where('wali_kelas_id', $user->guru_id)
                        ->get()
                        ->getRow();

                    if ($kelas) {
                        $sessionData['is_walikelas'] = true;
                        $sessionData['kelas_id']     = (int) $kelas->id;
                        $sessionData['kelas_nama']   = $kelas->nama;
                    } else {
                        $sessionData['is_walikelas'] = false;
                    }
                }

                $this->session->set($sessionData);

                return $this->redirectByRole((int) $user->role_id);
            }

            return redirect()->to('/auth/login')
                ->with('error', 'Username atau password salah.');
        }

        $data = [
            'title' => 'Login - SimSGTK',
            'tahun' => $this->tahunModel->get_all(),
        ];

        return view('auth/login', $data);
    }

    private function redirectByRole(int $roleId)
    {
        return match ($roleId) {
            1       => redirect()->to('/dashboard'),
            2       => redirect()->to('/dashboard'),
            3       => redirect()->to('/guru_dashboard'),
            4       => redirect()->to('/guru_dashboard'),
            5       => redirect()->to('/dashboard'),
            6       => redirect()->to('/bpbk'),
            default => redirect()->to('/dashboard'),
        };
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/auth/login');
    }
}
