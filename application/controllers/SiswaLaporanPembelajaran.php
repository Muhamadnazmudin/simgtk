<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SiswaLaporanPembelajaran extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Laporan_pembelajaran_model');
        $this->load->database();
    }

    private function cek_login()
    {
        if (!$this->session->userdata('siswa_login')) {
            redirect('SiswaAuth');
        }
    }

    private function cek_ketua()
    {
        $kelas = $this->db
            ->where('ketua_kelas_id',$this->session->userdata('siswa_id'))
            ->get('kelas')->row();

        if (!$kelas) show_error('Akses ditolak',403);
        return $kelas;
    }

    public function index()
    {
        $this->cek_login();
        $kelas = $this->cek_ketua();

        $data['active'] = 'laporan_pembelajaran';
        $data['kelas']  = $kelas;
        $data['laporan']= $this->Laporan_pembelajaran_model->get_by_kelas($kelas->id);

        // master data
        $data['guru']  = $this->db->order_by('nama','ASC')->get('guru')->result();
        $data['mapel'] = $this->db->order_by('nama_mapel','ASC')->get('mapel')->result();

        $this->load->view('siswa/layout/header',$data);
        $this->load->view('siswa/layout/sidebar',$data);
        $this->load->view('siswa/laporan_pembelajaran',$data);
        $this->load->view('siswa/layout/footer');
    }

    public function simpan()
    {
        $this->cek_login();
        $kelas = $this->cek_ketua();

        $this->Laporan_pembelajaran_model->insert([
            'kelas_id'       => $kelas->id,
            'siswa_id'       => $this->session->userdata('siswa_id'),
            'hari'           => $this->input->post('hari'),
            'jam_mulai_id'   => $this->input->post('jam_mulai_id'),
            'jam_selesai_id' => $this->input->post('jam_selesai_id'),
            'mapel_id'       => $this->input->post('mapel_id'),
            'guru_id'        => $this->input->post('guru_id'),
            'alasan'         => $this->input->post('alasan'),
            'tanggal'        => date('Y-m-d')
        ]);

        $this->session->set_flashdata('success','Laporan berhasil dikirim');
        redirect('SiswaLaporanPembelajaran');
    }
// ===============================
// AJAX: AMBIL JAM SEKOLAH BY HARI
// ===============================
public function ajax_jam_by_hari()
{
    $this->cek_login();
    $this->cek_ketua();

    $hari = $this->input->get('hari', TRUE);

    if (!$hari) {
        echo json_encode([]);
        return;
    }

    $jam = $this->db
        ->select('id_jam, nama_jam, jam_mulai, jam_selesai, urutan')
        ->from('jam_sekolah')
        ->where('hari', $hari)
        ->where('is_active', 1)
        ->order_by('urutan', 'ASC')
        ->get()
        ->result();

    echo json_encode($jam);
}

}
