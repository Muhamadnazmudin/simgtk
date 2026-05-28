<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cari_nisn extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }

    /* ======================
       TAHUN AJARAN AKTIF
    ====================== */
    private function tahunAktif()
    {
        return $this->db
            ->where('aktif', 1)
            ->get('tahun_ajaran')
            ->row();
    }

    /* ======================
       HALAMAN FORM
    ====================== */
    public function index()
    {
        $data['title'] = 'Cari NISN';

        $this->load->view('public/cari_nisn', $data);
    }

    /* ======================
       PROSES CARI
    ====================== */
    public function proses()
    {
        $ip = $this->input->ip_address();

        /* ======================
           RATE LIMIT
        ====================== */
        $rateKey = 'nisn_rate_' . md5($ip);

        $rate = $this->session->userdata($rateKey)
            ?? ['count' => 0, 'time' => time()];

        if (time() - $rate['time'] > 60) {
            $rate = ['count' => 0, 'time' => time()];
        }

        $rate['count']++;
        $this->session->set_userdata($rateKey, $rate);

        if ($rate['count'] > 20) {
            $this->session->set_flashdata(
                'error',
                '⚠️ Terlalu banyak pencarian. Coba lagi sebentar.'
            );

            redirect('cari_nisn');
        }

        usleep(300000); // anti bot

        /* ======================
           INPUT
        ====================== */
        $nama          = trim($this->input->post('nama', TRUE));
        $tempat_lahir  = trim($this->input->post('tempat_lahir', TRUE));
        $tgl_lahir     = trim($this->input->post('tgl_lahir', TRUE));
        // konversi dd-mm-yyyy -> yyyy-mm-dd
$tgl_lahir = DateTime::createFromFormat(
    'd-m-Y',
    $tgl_lahir
);

if (!$tgl_lahir) {
    $this->session->set_flashdata(
        'error',
        'Format tanggal salah. Gunakan DD-MM-YYYY'
    );

    redirect('cari_nisn');
}

$tgl_lahir = $tgl_lahir->format('Y-m-d');

        if (!$nama || !$tempat_lahir || !$tgl_lahir) {
            $this->session->set_flashdata(
                'error',
                'Semua field wajib diisi.'
            );

            redirect('cari_nisn');
        }

        if (strlen($nama) < 3) {

    $this->session->set_flashdata(
        'error',
        'Nama minimal 3 karakter.'
    );

    redirect('cari_nisn');
}

        $tahun = $this->tahunAktif();
        $tahun_id = $tahun ? $tahun->id : null;

        /* ======================
           QUERY SISWA
        ====================== */
       $this->db
    ->select('s.nama, s.nisn, s.tempat_lahir, s.tgl_lahir, k.nama as kelas')
    ->from('siswa s')
    ->join('kelas k', 'k.id = s.id_kelas', 'left')
    ->where('s.tahun_id', $tahun_id)
    ->where('DATE(s.tgl_lahir)', $tgl_lahir)
    ->where('LOWER(TRIM(s.tempat_lahir))', strtolower(trim($tempat_lahir)))
    ->where('LOWER(TRIM(s.nama))', strtolower(trim($nama)));

        $siswa = $this->db->get()->row();

        if ($siswa) {

            $this->session->set_flashdata('hasil', [
                'nama' => $siswa->nama,
                'nisn' => $siswa->nisn,
                'kelas' => $siswa->kelas,
                'tempat_lahir' => $siswa->tempat_lahir,
                'tgl_lahir' => $siswa->tgl_lahir
            ]);

        } else {

            $this->session->set_flashdata(
                'error',
                '❌ Data tidak ditemukan. Pastikan nama, tempat lahir, dan tanggal lahir sesuai.'
            );
        }

        redirect('cari_nisn');
    }
}