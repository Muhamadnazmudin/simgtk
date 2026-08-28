<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berkas_siswa extends CI_Controller
{
    public function __construct()
{
    parent::__construct();

    // ===============================
    // MODEL
    // ===============================
    $this->load->model('Berkas_siswa_model', 'berkas');

    // ===============================
    // LIBRARY
    // ===============================
    $this->load->library([
        'session',
        'pagination'
    ]);

    // ===============================
    // HELPER
    // ===============================
    $this->load->helper([
        'url',
        'form',
        'file'
    ]);

    // ===============================
    // AUTH GUARD
    // ===============================
    $role = $this->session->userdata('role_name');

    if (!in_array($role, ['admin', 'operator'])) {
        redirect('dashboard');
        exit;
    }
}

    /**
     * Halaman daftar siswa
     */
    public function index($offset = 0)
{
    // ==========================================
    // FILTER
    // ==========================================

    $keyword  = trim($this->input->get('keyword', TRUE));
    $kelas_id = trim($this->input->get('kelas', TRUE));

    // ==========================================
    // PAGINATION
    // ==========================================

    $limit = 20;

    // Pastikan offset angka
    $offset = (int) $offset;

    // ==========================================
    // TOTAL DATA
    // ==========================================

    $total_rows = $this->berkas->count_siswa_aktif(
        $keyword,
        $kelas_id
    );

    // ==========================================
    // KONFIGURASI PAGINATION
    // ==========================================

    $config['base_url'] = site_url('berkas_siswa/index');

    $config['total_rows'] = $total_rows;

    $config['per_page'] = $limit;

    $config['uri_segment'] = 3;

    // Pertahankan GET parameter
    $config['reuse_query_string'] = TRUE;

    // ==========================================
    // BOOTSTRAP PAGINATION
    // ==========================================

    $config['full_tag_open'] =
        '<nav><ul class="pagination pagination-sm justify-content-center mb-0">';

    $config['full_tag_close'] =
        '</ul></nav>';

    $config['attributes'] = [
        'class' => 'page-link'
    ];

    // FIRST
    $config['first_link'] = '<i class="fas fa-angle-double-left"></i>';

    $config['first_tag_open'] =
        '<li class="page-item">';

    $config['first_tag_close'] =
        '</li>';

    // LAST
    $config['last_link'] =
        '<i class="fas fa-angle-double-right"></i>';

    $config['last_tag_open'] =
        '<li class="page-item">';

    $config['last_tag_close'] =
        '</li>';

    // NEXT
    $config['next_link'] =
        '<i class="fas fa-angle-right"></i>';

    $config['next_tag_open'] =
        '<li class="page-item">';

    $config['next_tag_close'] =
        '</li>';

    // PREVIOUS
    $config['prev_link'] =
        '<i class="fas fa-angle-left"></i>';

    $config['prev_tag_open'] =
        '<li class="page-item">';

    $config['prev_tag_close'] =
        '</li>';

    // CURRENT
    $config['cur_tag_open'] =
        '<li class="page-item active">
            <span class="page-link">';

    $config['cur_tag_close'] =
        '</span></li>';

    // NUMBER
    $config['num_tag_open'] =
        '<li class="page-item">';

    $config['num_tag_close'] =
        '</li>';

    // ==========================================
    // INITIALIZE
    // ==========================================

    $this->pagination->initialize($config);

    // ==========================================
    // DATA SISWA
    // ==========================================

    $data['siswa'] = $this->berkas->get_siswa_with_berkas(
        $keyword,
        $kelas_id,
        $limit,
        $offset
    );

    // ==========================================
    // DATA FILTER
    // ==========================================

    $data['kelas'] = $this->berkas->get_kelas_aktif();

    // ==========================================
    // DATA VIEW
    // ==========================================

    $data['title'] = 'Upload Berkas Siswa';

    $data['active'] = 'berkas_siswa';

    $data['keyword'] = $keyword;

    $data['kelas_id'] = $kelas_id;

    $data['limit'] = $limit;

    $data['offset'] = $offset;

    $data['total_rows'] = $total_rows;

    $data['pagination'] =
        $this->pagination->create_links();

    // ==========================================
    // RANGE DATA
    // ==========================================

    if ($total_rows > 0) {

        $data['nomor_awal'] = $offset + 1;

        $data['nomor_akhir'] =
            min(
                $offset + $limit,
                $total_rows
            );

    } else {

        $data['nomor_awal'] = 0;

        $data['nomor_akhir'] = 0;
    }

    // ==========================================
    // LOAD VIEW
    // ==========================================

    $this->load->view(
        'templates/header',
        $data
    );

    $this->load->view(
        'templates/sidebar',
        $data
    );

    $this->load->view(
        'admin/berkas_siswa/index',
        $data
    );

    $this->load->view(
        'templates/footer'
    );
}

    /**
     * Kelola berkas siswa
     */
    public function kelola($siswa_id)
    {
        $siswa_id = (int) $siswa_id;

        $siswa = $this->berkas
            ->get_siswa_by_id($siswa_id);

        if (!$siswa) {
            show_404();
        }

        $data['title']       = 'Berkas Siswa';
        $data['active']      = 'berkas_siswa';
        $data['siswa']       = $siswa;
        $data['jenis_berkas'] = $this->berkas->jenis_berkas();

        $berkas = $this->berkas
            ->get_berkas_siswa($siswa_id);

        $data['berkas'] = array();

        foreach ($berkas as $item) {
            $data['berkas'][$item->jenis_berkas] = $item;
        }

        $this->load->view('templates/header', $data);
$this->load->view('templates/sidebar', $data);
$this->load->view('admin/berkas_siswa/kelola', $data);
$this->load->view('templates/footer');
    }

    /**
     * Proses upload
     */
    public function upload($siswa_id)
{
    $siswa_id = (int) $siswa_id;

    // =========================================================
    // CEK SISWA
    // =========================================================

    $siswa = $this->berkas->get_siswa_by_id($siswa_id);

    if (!$siswa) {
        show_404();
        return;
    }


    // =========================================================
    // AMBIL JENIS BERKAS
    // =========================================================

    $jenis = trim(
        $this->input->post('jenis_berkas', TRUE)
    );


    // =========================================================
    // DAFTAR JENIS BERKAS
    // =========================================================

    $jenis_berkas = $this->berkas->jenis_berkas();


    // =========================================================
    // VALIDASI JENIS BERKAS
    // =========================================================

    if (
        empty($jenis) ||
        !array_key_exists($jenis, $jenis_berkas)
    ) {

        log_message(
            'error',
            'UPLOAD BERKAS: jenis_berkas tidak valid. ' .
            'Siswa ID: ' . $siswa_id .
            ' | POST: ' . var_export($jenis, TRUE)
        );

        $this->session->set_flashdata(
            'error',
            'Jenis berkas tidak valid.'
        );

        redirect(
            'berkas_siswa/kelola/' . $siswa_id
        );

        return;
    }


    // =========================================================
    // CEK FILE
    // =========================================================

    if (
        !isset($_FILES['file']) ||
        empty($_FILES['file']['name'])
    ) {

        $this->session->set_flashdata(
            'error',
            'Silakan pilih file terlebih dahulu.'
        );

        redirect(
            'berkas_siswa/kelola/' . $siswa_id
        );

        return;
    }


    // =========================================================
    // FOLDER SISWA
    // =========================================================

    $upload_path =
        FCPATH .
        'uploads/berkas_siswa/' .
        $siswa_id .
        '/';


    if (!is_dir($upload_path)) {

        mkdir(
            $upload_path,
            0755,
            TRUE
        );
    }


    // =========================================================
    // KONFIGURASI UPLOAD
    // =========================================================

    $config = array(

        'upload_path'   => $upload_path,

        'allowed_types' => 'jpg|jpeg|png|pdf',

        'max_size'      => 5120,

        'encrypt_name'  => TRUE

    );


    $this->load->library(
        'upload',
        $config
    );


    // =========================================================
    // PROSES UPLOAD
    // =========================================================

    if (!$this->upload->do_upload('file')) {

        $error = strip_tags(
            $this->upload->display_errors(
                '',
                ''
            )
        );

        $this->session->set_flashdata(
            'error',
            $error
        );

        redirect(
            'berkas_siswa/kelola/' . $siswa_id
        );

        return;
    }


    // =========================================================
    // DATA FILE
    // =========================================================

    $upload_data = $this->upload->data();


    $nama_file = $upload_data['file_name'];

    $nama_file_asli =
        $upload_data['client_name'];

    $tipe_file =
        $upload_data['file_type'];

    /*
     * file_size dari CodeIgniter
     * adalah dalam KB.
     *
     * Database kita simpan dalam BYTE.
     */
    $ukuran_file =
        $upload_data['file_size'] * 1024;


    // =========================================================
    // CEK BERKAS LAMA
    // =========================================================

    $berkas_lama = $this->berkas->get_berkas(
        $siswa_id,
        $jenis
    );


    // =========================================================
    // JIKA SUDAH ADA → GANTI
    // =========================================================

    if ($berkas_lama) {

        // -----------------------------------------
        // Hapus file lama
        // -----------------------------------------

        $file_lama =
            $upload_path .
            $berkas_lama->nama_file;


        if (is_file($file_lama)) {

            @unlink($file_lama);
        }


        // -----------------------------------------
        // Update database
        // -----------------------------------------

        $update = array(

            'nama_file' =>
                $nama_file,

            'nama_file_asli' =>
                $nama_file_asli,

            'tipe_file' =>
                $tipe_file,

            'ukuran_file' =>
                $ukuran_file,

            'updated_at' =>
                date('Y-m-d H:i:s')

        );


        $this->berkas->update(
            $berkas_lama->id,
            $update
        );


        $pesan =
            $jenis_berkas[$jenis] .
            ' berhasil diganti.';

    }


    // =========================================================
    // BERKAS BARU
    // =========================================================

    else {

        $insert = array(

            'siswa_id' =>
                $siswa_id,

            'jenis_berkas' =>
                $jenis,

            'nama_file' =>
                $nama_file,

            'nama_file_asli' =>
                $nama_file_asli,

            'tipe_file' =>
                $tipe_file,

            'ukuran_file' =>
                $ukuran_file,

            'created_at' =>
                date('Y-m-d H:i:s'),

            'updated_at' =>
                date('Y-m-d H:i:s')

        );


        $this->berkas->insert(
            $insert
        );


        $pesan =
            $jenis_berkas[$jenis] .
            ' berhasil diupload.';
    }


    // =========================================================
    // SUCCESS
    // =========================================================

    $this->session->set_flashdata(
        'success',
        $pesan
    );


    redirect(
        'berkas_siswa/kelola/' . $siswa_id
    );
}

    /**
 * Lihat / preview file
 */
public function lihat($id)
{
    $id = (int) $id;

    $berkas = $this->db
        ->where('id', $id)
        ->get('siswa_berkas')
        ->row();

    if (!$berkas) {
        show_404();
        return;
    }

    $file = FCPATH .
        'uploads/berkas_siswa/' .
        $berkas->siswa_id . '/' .
        $berkas->nama_file;

    if (!is_file($file)) {
        show_404();
        return;
    }

    $data['title'] = 'Lihat Berkas';
    $data['active'] = 'berkas_siswa';
    $data['berkas'] = $berkas;

    $data['file_url'] = site_url(
        'berkas_siswa/preview/' . $berkas->id
    );

    $data['download_url'] = site_url(
        'berkas_siswa/download/' . $berkas->id
    );

    $mime = $berkas->tipe_file;

    if (!$mime) {
        $mime = mime_content_type($file);
    }

    $data['mime'] = $mime;

    $data['is_image'] = in_array(
        strtolower($mime),
        [
            'image/jpeg',
            'image/jpg',
            'image/png'
        ],
        TRUE
    );

    $data['is_pdf'] = (
        strtolower($mime) === 'application/pdf'
    );

    $this->load->view(
        'templates/header',
        $data
    );

    $this->load->view(
        'templates/sidebar',
        $data
    );

    $this->load->view(
        'admin/berkas_siswa/lihat',
        $data
    );

    $this->load->view(
        'templates/footer'
    );
}


/**
 * Preview file secara langsung
 */
public function preview($id)
{
    $id = (int) $id;

    $berkas = $this->db
        ->where('id', $id)
        ->get('siswa_berkas')
        ->row();

    if (!$berkas) {
        show_404();
        return;
    }

    $file = FCPATH .
        'uploads/berkas_siswa/' .
        $berkas->siswa_id . '/' .
        $berkas->nama_file;

    if (!is_file($file)) {
        show_404();
        return;
    }

    $mime = $berkas->tipe_file;

    if (!$mime) {
        $mime = mime_content_type($file);
    }

    /*
     * Hanya izinkan file yang memang
     * didukung oleh sistem.
     */
    $allowed_mime = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'application/pdf'
    ];

    if (!in_array($mime, $allowed_mime, TRUE)) {
        show_404();
        return;
    }

    header(
        'Content-Type: ' . $mime
    );

    header(
        'Content-Length: ' . filesize($file)
    );

    header(
        'Content-Disposition: inline; filename="' .
        basename($berkas->nama_file_asli) .
        '"'
    );

    header(
        'Cache-Control: private, max-age=3600'
    );

    readfile($file);
    exit;
}


/**
 * Simpan rotasi gambar
 */
public function rotate($id)
{
    $id = (int) $id;

    /*
     * Hanya POST
     */
    if ($this->input->method(TRUE) !== 'POST') {
        show_404();
        return;
    }

    /*
     * Ambil data berkas
     */
    $berkas = $this->db
        ->where('id', $id)
        ->get('siswa_berkas')
        ->row();

    if (!$berkas) {
        show_404();
        return;
    }

    /*
     * File fisik
     */
    $file = FCPATH .
        'uploads/berkas_siswa/' .
        $berkas->siswa_id . '/' .
        $berkas->nama_file;

    if (!is_file($file)) {
        $this->session->set_flashdata(
            'error',
            'File berkas tidak ditemukan.'
        );

        redirect(
            'berkas_siswa/lihat/' . $id
        );

        return;
    }

    /*
     * Hanya gambar yang bisa diputar
     */
    $mime = strtolower(
        $berkas->tipe_file
    );

    if (!in_array(
        $mime,
        [
            'image/jpeg',
            'image/jpg',
            'image/png'
        ],
        TRUE
    )) {

        $this->session->set_flashdata(
            'error',
            'File ini tidak dapat diputar.'
        );

        redirect(
            'berkas_siswa/lihat/' . $id
        );

        return;
    }

    /*
     * Arah rotasi
     *
     * right = 90 derajat
     * left  = -90 derajat
     */
    $direction =
        $this->input->post(
            'direction',
            TRUE
        );

    if (!in_array(
        $direction,
        ['left', 'right'],
        TRUE
    )) {

        $this->session->set_flashdata(
            'error',
            'Arah rotasi tidak valid.'
        );

        redirect(
            'berkas_siswa/lihat/' . $id
        );

        return;
    }

    /*
     * Baca gambar
     */
    if (
        $mime === 'image/jpeg' ||
        $mime === 'image/jpg'
    ) {

        $image = @imagecreatefromjpeg(
            $file
        );

    } elseif ($mime === 'image/png') {

        $image = @imagecreatefrompng(
            $file
        );

    } else {

        $image = false;
    }

    if (!$image) {

        $this->session->set_flashdata(
            'error',
            'Gambar tidak dapat diproses.'
        );

        redirect(
            'berkas_siswa/lihat/' . $id
        );

        return;
    }

    /*
     * Sudut rotasi
     */
    $angle =
        ($direction === 'right')
            ? -90
            : 90;

    /*
     * Untuk PNG pertahankan transparansi.
     */
    if ($mime === 'image/png') {

        imagealphablending(
            $image,
            FALSE
        );

        imagesavealpha(
            $image,
            TRUE
        );
    }

    /*
     * Rotasi
     */
    $rotated = @imagerotate(
        $image,
        $angle,
        0
    );

    imagedestroy($image);

    if (!$rotated) {

        $this->session->set_flashdata(
            'error',
            'Gagal memutar gambar.'
        );

        redirect(
            'berkas_siswa/lihat/' . $id
        );

        return;
    }

    /*
     * Simpan kembali ke file asli
     */
    $saved = false;

    if (
        $mime === 'image/jpeg' ||
        $mime === 'image/jpg'
    ) {

        /*
         * Quality 92 agar kualitas tetap bagus
         */
        $saved = @imagejpeg(
            $rotated,
            $file,
            92
        );

    } elseif ($mime === 'image/png') {

        /*
         * PNG compression 6
         */
        imagealphablending(
            $rotated,
            FALSE
        );

        imagesavealpha(
            $rotated,
            TRUE
        );

        $saved = @imagepng(
            $rotated,
            $file,
            6
        );
    }

    imagedestroy($rotated);

    if (!$saved) {

        $this->session->set_flashdata(
            'error',
            'Gagal menyimpan hasil rotasi.'
        );

        redirect(
            'berkas_siswa/lihat/' . $id
        );

        return;
    }

    /*
     * Update ukuran file
     */
    $this->db
        ->where('id', $id)
        ->update(
            'siswa_berkas',
            [
                'ukuran_file' =>
                    filesize($file),

                'updated_at' =>
                    date('Y-m-d H:i:s')
            ]
        );

    /*
     * Success
     */
    $this->session->set_flashdata(
        'success',
        'Berkas berhasil diputar.'
    );

    redirect(
        'berkas_siswa/lihat/' . $id
    );
}

    /**
     * Download file
     */
    public function download($id)
    {
        $id = (int) $id;

        $berkas = $this->db
            ->where('id', $id)
            ->get('siswa_berkas')
            ->row();

        if (!$berkas) {
            show_404();
        }

        $file = FCPATH .
            'uploads/berkas_siswa/' .
            $berkas->siswa_id . '/' .
            $berkas->nama_file;

        if (!is_file($file)) {
            show_404();
        }

        $this->load->helper('download');

        force_download(
            $berkas->nama_file_asli,
            file_get_contents($file)
        );
    }

    /**
     * Hapus berkas
     */
    public function hapus($id)
    {
        $id = (int) $id;

        $berkas = $this->db
            ->where('id', $id)
            ->get('siswa_berkas')
            ->row();

        if (!$berkas) {
            show_404();
        }

        $file = FCPATH .
            'uploads/berkas_siswa/' .
            $berkas->siswa_id . '/' .
            $berkas->nama_file;

        /*
         * Hapus file fisik
         */
        if (is_file($file)) {
            @unlink($file);
        }

        /*
         * Hapus database
         */
        $this->berkas->delete($id);

        $this->session->set_flashdata(
            'success',
            'Berkas berhasil dihapus.'
        );

        redirect(
            'berkas_siswa/kelola/' .
            $berkas->siswa_id
        );
    }
}