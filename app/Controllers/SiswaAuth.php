<?php

namespace App\Controllers;

/**
 * Controller SiswaAuth - CodeIgniter 4
 * Student login with math captcha and rate limiting
 */
class SiswaAuth extends BaseController
{
    private function generateSoalMatematika(): array
    {
        $operasi = rand(1, 4);
        switch ($operasi) {
            case 1:
                $a = rand(1, 50);
                $b = rand(1, 50);
                $soal = $a . ' + ' . $b . ' = ?';
                $jawaban = $a + $b;
                break;
            case 2:
                $a = rand(20, 100);
                $b = rand(1, $a);
                $soal = $a . ' − ' . $b . ' = ?';
                $jawaban = $a - $b;
                break;
            case 3:
                $a = rand(2, 20);
                $b = rand(2, 10);
                $soal = $a . ' × ' . $b . ' = ?';
                $jawaban = $a * $b;
                break;
            case 4:
            default:
                $b = rand(2, 12);
                $jawaban = rand(2, 15);
                $a = $b * $jawaban;
                $soal = $a . ' ÷ ' . $b . ' = ?';
                break;
        }

        return ['soal' => $soal, 'jawaban' => $jawaban];
    }

    public function index()
    {
        if ($this->session->get('siswa_login')) {
            return redirect()->to('/SiswaDashboard');
        }

        $matematika = $this->generateSoalMatematika();
        $this->session->set([
            'math_soal'    => $matematika['soal'],
            'math_jawaban' => $matematika['jawaban'],
        ]);

        $data = [
            'title'     => 'Login Siswa',
            'math_soal' => $matematika['soal'],
        ];

        return view('siswa/login', $data);
    }

    public function cek_login()
    {
        // Rate Limiting: max 10 attempts per minute
        $throttler = service('throttler');
        $ip = $this->request->getIPAddress();
        if ($throttler->check('siswa_login_' . md5($ip), 10, 60) === false) {
            return redirect()->to('/siswa-login')->with('error', 'Terlalu banyak percobaan login. Silakan tunggu sebentar.');
        }

        $nisn     = trim((string) $this->request->getPost('nisn'));
        $password = (string) $this->request->getPost('password');
        $jawaban  = trim((string) $this->request->getPost('jawaban_matematika'));

        $jawabanBenar = $this->session->get('math_jawaban');
        if ($jawabanBenar === null || $jawaban === '' || (int) $jawaban !== (int) $jawabanBenar) {
            return redirect()->to('/siswa-login')->with('error', 'Jawaban matematika salah. Silakan coba lagi.');
        }

        $siswa = $this->db->table('siswa')
            ->where('nisn', $nisn)
            ->get()
            ->getRow();

        if (!$siswa) {
            return redirect()->to('/siswa-login')->with('error', 'NISN tidak ditemukan!');
        }

        $stored = $siswa->password ?: $siswa->nisn;
        // Verify both password_hash and plain fallback (for legacy NISN)
        $valid = password_verify($password, $stored) || ($password === $stored);

        if (!$valid) {
            return redirect()->to('/siswa-login')->with('error', 'Password salah!');
        }

        // Regenerate session ID for security
        $this->session->regenerate(true);

        $this->session->set([
            'siswa_login' => true,
            'siswa_id'    => (int) $siswa->id,
            'siswa_nama'  => $siswa->nama,
            'siswa_nisn'  => $siswa->nisn,
            'kelas_id'    => (int) $siswa->id_kelas,
        ]);

        $this->session->remove(['math_soal', 'math_jawaban']);

        return redirect()->to('/SiswaDashboard');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/siswa-login');
    }
}
