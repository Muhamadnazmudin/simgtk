<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SiswaBerkas extends CI_Controller
{
    private $sheet_url =
    'https://script.google.com/macros/s/AKfycbySQTOl8EZLnAxYaO5v2PEYwgTUIKgpK2ROeJUlkN2lpbI5UUcfUyUvAT6lc1A5cdtP2A/exec';
    public function __construct()
    {
        parent::__construct();

        // ==========================================
        // LIBRARY
        // ==========================================
        $this->load->library([
            'session',
            'upload'
        ]);

        // ==========================================
        // HELPER
        // ==========================================
        $this->load->helper([
            'url',
            'form',
            'file'
        ]);

        // ==========================================
        // MODEL
        // ==========================================
        $this->load->model(
            'Berkas_siswa_model',
            'berkas'
        );

        // ==========================================
        // CEK LOGIN SISWA
        // ==========================================
        // =========================================================
// AUTH GUARD
// =========================================================

$public_methods = [
    'file'
];

$current_method = $this->router->fetch_method();

if (!in_array($current_method, $public_methods)) {

    $siswa_id = (int) $this->session->userdata('siswa_id');

    if (!$siswa_id) {

        redirect('SiswaAuth');

        exit;
    }
}
    }


    // =========================================================
    // INDEX
    // =========================================================

    public function index()
    {
        $siswa_id = (int) $this->session->userdata('siswa_id');

        if (!$siswa_id) {
            redirect('SiswaAuth/logout');
            return;
        }

        // ==========================================
        // DATA SISWA
        // ==========================================

        $siswa = $this->berkas
            ->get_siswa_by_id($siswa_id);

        if (!$siswa) {
            show_error('Data siswa tidak ditemukan.');
            return;
        }

        // ==========================================
        // JENIS BERKAS
        // ==========================================

        $jenis_berkas = $this->berkas->jenis_berkas();

        // ==========================================
        // BERKAS SISWA
        // ==========================================

        $berkas = $this->berkas
            ->get_all_berkas($siswa_id);

        // ==========================================
        // DATA VIEW
        // ==========================================

        $data = [
            'title'        => 'Upload Berkas',
            'active'       => 'berkas',
            'siswa'        => $siswa,
            'jenis_berkas' => $jenis_berkas,
            'berkas'       => $berkas
        ];

        // ==========================================
        // LAYOUT SISWA
        // ==========================================

        $this->load->view(
            'siswa/layout/header',
            $data
        );

        $this->load->view(
            'siswa/layout/sidebar',
            $data
        );

        $this->load->view(
            'siswa/berkas/index',
            $data
        );

        $this->load->view(
            'siswa/layout/footer'
        );
    }


    // =========================================================
    // UPLOAD
    // =========================================================

    public function upload()
    {
        $siswa_id = (int) $this->session->userdata('siswa_id');

        if (!$siswa_id) {
            redirect('SiswaAuth/logout');
            return;
        }

        // ==========================================
        // CEK SISWA
        // ==========================================

        $siswa = $this->berkas
            ->get_siswa_by_id($siswa_id);

        if (!$siswa) {
            show_error('Data siswa tidak ditemukan.');
            return;
        }

        // ==========================================
        // JENIS BERKAS
        // ==========================================

        $jenis = trim(
            $this->input->post(
                'jenis_berkas',
                TRUE
            )
        );

        $jenis_berkas = $this->berkas->jenis_berkas();

        // ==========================================
        // VALIDASI JENIS
        // ==========================================

        if (
            empty($jenis) ||
            !array_key_exists(
                $jenis,
                $jenis_berkas
            )
        ) {

            $this->session->set_flashdata(
                'error',
                'Jenis berkas tidak valid.'
            );

            redirect('SiswaBerkas');
            return;
        }

        // ==========================================
        // CEK FILE
        // ==========================================

        if (
            !isset($_FILES['file']) ||
            empty($_FILES['file']['name'])
        ) {

            $this->session->set_flashdata(
                'error',
                'Silakan pilih file terlebih dahulu.'
            );

            redirect('SiswaBerkas');
            return;
        }

        // ==========================================
        // FOLDER SISWA
        // ==========================================

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

        // ==========================================
        // KONFIGURASI UPLOAD
        // ==========================================

        $config = [

            'upload_path'   => $upload_path,

            'allowed_types' =>
                'jpg|jpeg|png|pdf',

            'max_size' =>
                5120,

            'encrypt_name' =>
                TRUE
        ];

        $this->upload->initialize($config);

        // ==========================================
        // PROSES UPLOAD
        // ==========================================

        if (
            !$this->upload
                ->do_upload('file')
        ) {

            $error = strip_tags(
                $this->upload
                    ->display_errors(
                        '',
                        ''
                    )
            );

            $this->session->set_flashdata(
                'error',
                $error
            );

            redirect('SiswaBerkas');
            return;
        }

        // ==========================================
        // DATA FILE
        // ==========================================

        $upload_data =
            $this->upload->data();

        $nama_file =
            $upload_data['file_name'];

        $nama_file_asli =
            $upload_data['client_name'];

        $tipe_file =
            $upload_data['file_type'];

        /*
         * CodeIgniter memberikan file_size
         * dalam KB.
         *
         * Database kita simpan BYTE.
         */
        $ukuran_file =
            $upload_data['file_size'] * 1024;

        // ==========================================
        // CEK BERKAS LAMA
        // ==========================================

        $berkas_lama =
            $this->berkas->get_berkas(
                $siswa_id,
                $jenis
            );

        // =========================================================
        // BERKAS SUDAH ADA → GANTI FILE
        // =========================================================

        if ($berkas_lama) {

            // ==========================================
            // TOKEN LAMA
            // ==========================================

            $access_token =
                !empty($berkas_lama->access_token)
                ? $berkas_lama->access_token
                : bin2hex(random_bytes(32));

            // ==========================================
            // HAPUS FILE LAMA
            // ==========================================

            $file_lama =
                $upload_path .
                $berkas_lama->nama_file;

            if (is_file($file_lama)) {
                @unlink($file_lama);
            }

            // ==========================================
            // UPDATE DATABASE
            // ==========================================

            $update = [

                'access_token' =>
                    $access_token,

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
            ];

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

            // ==========================================
            // GENERATE TOKEN
            // ==========================================

            $access_token =
                bin2hex(random_bytes(32));

            // ==========================================
            // INSERT DATABASE
            // ==========================================

            $insert = [

                'siswa_id' =>
                    $siswa_id,

                'access_token' =>
                    $access_token,

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
            ];

            $this->berkas->insert($insert);

            $pesan =
                $jenis_berkas[$jenis] .
                ' berhasil diupload.';
        }

        // ==========================================
        // SUCCESS
        // ==========================================

        // =========================================================
// SYNC KE GOOGLE SHEET
// =========================================================

$this->syncBerkasGoogleSheet($siswa_id);


// =========================================================
// SUCCESS
// =========================================================

$this->session->set_flashdata(
    'success',
    $pesan
);

redirect(
    'SiswaBerkas'
);
    }


    // =========================================================
    // LIHAT FILE MILIK SISWA
    // =========================================================

    public function lihat($id)
    {
        $siswa_id =
            (int) $this->session->userdata('siswa_id');

        $id = (int) $id;

        if (!$siswa_id) {
            redirect('SiswaAuth');
            return;
        }

        /*
         * Ambil berdasarkan:
         *
         * ID BERKAS
         * +
         * ID SISWA
         *
         * sehingga siswa tidak dapat
         * melihat berkas siswa lain.
         */

        $berkas =
            $this->berkas
                ->get_berkas_by_id_siswa(
                    $id,
                    $siswa_id
                );

        if (!$berkas) {
            show_404();
            return;
        }

        $file =
            FCPATH .
            'uploads/berkas_siswa/' .
            $siswa_id .
            '/' .
            $berkas->nama_file;

        if (!is_file($file)) {
            show_404();
            return;
        }

        $mime =
            $berkas->tipe_file;

        if (
            !$mime &&
            function_exists('mime_content_type')
        ) {
            $mime =
                mime_content_type($file);
        }

        header(
            'Content-Type: ' .
            $mime
        );

        header(
            'Content-Length: ' .
            filesize($file)
        );

        header(
            'Content-Disposition: inline; filename="' .
            basename(
                $berkas->nama_file_asli
            ) .
            '"'
        );

        readfile($file);
        exit;
    }


    // =========================================================
    // DOWNLOAD FILE MILIK SISWA
    // =========================================================

    public function download($id)
    {
        $siswa_id =
            (int) $this->session->userdata('siswa_id');

        $id = (int) $id;

        if (!$siswa_id) {
            redirect('SiswaAuth');
            return;
        }

        $berkas =
            $this->berkas
                ->get_berkas_by_id_siswa(
                    $id,
                    $siswa_id
                );

        if (!$berkas) {
            show_404();
            return;
        }

        $file =
            FCPATH .
            'uploads/berkas_siswa/' .
            $siswa_id .
            '/' .
            $berkas->nama_file;

        if (!is_file($file)) {
            show_404();
            return;
        }

        $this->load->helper('download');

        force_download(
            $berkas->nama_file_asli,
            file_get_contents($file)
        );
    }


    // =========================================================
    // HAPUS BERKAS
    // =========================================================

    public function hapus($id)
    {
        $siswa_id =
            (int) $this->session->userdata('siswa_id');

        $id = (int) $id;

        if (!$siswa_id) {
            redirect('SiswaAuth');
            return;
        }

        $berkas =
            $this->berkas
                ->get_berkas_by_id_siswa(
                    $id,
                    $siswa_id
                );

        if (!$berkas) {
            show_404();
            return;
        }

        $file =
            FCPATH .
            'uploads/berkas_siswa/' .
            $siswa_id .
            '/' .
            $berkas->nama_file;

        if (is_file($file)) {
            @unlink($file);
        }

        $this->berkas->delete($id);

        $this->session->set_flashdata(
            'success',
            'Berkas berhasil dihapus.'
        );

        redirect('SiswaBerkas');
    }


    // =========================================================
// FILE PUBLIK BERDASARKAN ACCESS TOKEN
// =========================================================
public function file($token = '')
{
    // ---------------------------------------------------------
    // Validasi token
    // ---------------------------------------------------------
    $token = trim($token);

    if ($token === '') {
        show_404();
        return;
    }

    // ---------------------------------------------------------
    // Ambil berkas berdasarkan access_token
    // ---------------------------------------------------------
    $berkas = $this->berkas
        ->get_berkas_by_token($token);

    if (!$berkas) {
        show_404();
        return;
    }

    // ---------------------------------------------------------
    // Lokasi file
    // ---------------------------------------------------------
    $file = FCPATH
        . 'uploads/berkas_siswa/'
        . (int) $berkas->siswa_id
        . '/'
        . $berkas->nama_file;

    // ---------------------------------------------------------
    // Pastikan file benar-benar ada
    // ---------------------------------------------------------
    if (!is_file($file)) {
        show_404();
        return;
    }

    // ---------------------------------------------------------
    // Tentukan MIME
    // ---------------------------------------------------------
    $mime = '';

    if (function_exists('mime_content_type')) {
        $mime = mime_content_type($file);
    }

    if (empty($mime)) {
        $mime = $berkas->tipe_file;
    }

    if (empty($mime)) {
        $mime = 'application/octet-stream';
    }

    // ---------------------------------------------------------
    // Bersihkan output buffer
    // ---------------------------------------------------------
    while (ob_get_level()) {
        ob_end_clean();
    }

    // ---------------------------------------------------------
    // Header file
    // ---------------------------------------------------------
    header('Content-Type: ' . $mime);

    header(
        'Content-Length: ' .
        filesize($file)
    );

    header(
        'Content-Disposition: inline; filename="' .
        basename($berkas->nama_file_asli) .
        '"'
    );

    header('Cache-Control: private, max-age=3600');

    // ---------------------------------------------------------
    // Tampilkan file
    // ---------------------------------------------------------
    readfile($file);

    exit;
}
    // =========================================================
// SYNC BERKAS KE GOOGLE SHEET
// =========================================================
private function syncBerkasGoogleSheet($siswa_id)
{
    $siswa_id = (int) $siswa_id;


    // =========================================================
    // AMBIL DATA SISWA
    // =========================================================

    $siswa = $this->db
        ->select('
            siswa.*,
            kelas.nama AS kelas
        ')
        ->from('siswa')
        ->join(
            'kelas',
            'kelas.id = siswa.id_kelas',
            'left'
        )
        ->where(
            'siswa.id',
            $siswa_id
        )
        ->get()
        ->row_array();


    if (!$siswa) {

        return false;
    }


    // =========================================================
    // AMBIL BERKAS
    // =========================================================

    $berkas = $this->db
        ->where(
            'siswa_id',
            $siswa_id
        )
        ->get('siswa_berkas')
        ->result();


    // =========================================================
    // DEFAULT URL
    // =========================================================

    $siswa['kartu_keluarga_url'] = '';
    $siswa['akta_lahir_url']     = '';
    $siswa['ijazah_sd_url']      = '';
    $siswa['ijazah_smp_url']     = '';
    $siswa['ktp_ayah_url']       = '';
    $siswa['ktp_ibu_url']        = '';


    // =========================================================
    // GENERATE URL
    // =========================================================

    foreach ($berkas as $b) {

        if (
            empty($b->access_token)
        ) {
            continue;
        }


        $url =
            base_url(
                'SiswaBerkas/file/' .
                $b->access_token
            );


        switch ($b->jenis_berkas) {

            case 'kartu_keluarga':

                $siswa['kartu_keluarga_url'] =
                    $url;

                break;


            case 'akta_lahir':

                $siswa['akta_lahir_url'] =
                    $url;

                break;


            case 'ijazah_sd':

                $siswa['ijazah_sd_url'] =
                    $url;

                break;


            case 'ijazah_smp':

                $siswa['ijazah_smp_url'] =
                    $url;

                break;


            case 'ktp_ayah':

                $siswa['ktp_ayah_url'] =
                    $url;

                break;


            case 'ktp_ibu':

                $siswa['ktp_ibu_url'] =
                    $url;

                break;
        }
    }


    // =========================================================
    // DATA LAMA
    // =========================================================
    //
    // Karena perubahan hanya pada berkas,
    // data lama tidak perlu dibandingkan.
    //
    // Kita kirim data yang sama supaya Apps Script
    // menemukan siswa berdasarkan NISN lalu memperbarui
    // kolom URL berkas.
    // =========================================================

    $lama = $siswa;
    $baru = $siswa;


    // =========================================================
    // HAPUS FIELD INTERNAL
    // =========================================================

    $remove_fields = [

        'id',
        'id_kelas',
        'password',
        'token_qr',
        'foto',
        'rfid_uid',
        'created_at',
        'status_verval',
        'catatan_verval',
        'verval_by',
        'verval_at'

    ];


    foreach ($remove_fields as $field) {

        unset(
            $lama[$field],
            $baru[$field]
        );
    }


    // =========================================================
    // PAYLOAD
    // =========================================================

    $payload = [

        'lama' => $lama,

        'baru' => $baru

    ];


    // =========================================================
    // CURL
    // =========================================================

    $ch = curl_init(
        $this->sheet_url
    );


    curl_setopt(
        $ch,
        CURLOPT_RETURNTRANSFER,
        true
    );


    curl_setopt(
        $ch,
        CURLOPT_FOLLOWLOCATION,
        true
    );


    curl_setopt(
        $ch,
        CURLOPT_POST,
        true
    );


    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        [
            'Content-Type: application/json'
        ]
    );


    curl_setopt(
        $ch,
        CURLOPT_POSTFIELDS,
        json_encode($payload)
    );


    $response = curl_exec($ch);

$curl_error = curl_error($ch);

$http_code = curl_getinfo(
    $ch,
    CURLINFO_HTTP_CODE
);

curl_close($ch);


// =========================================================
// DEBUG SINKRON GOOGLE SHEET
// =========================================================

if ($response === false) {

    log_message(
        'error',
        'SYNC BERKAS GOOGLE SHEET CURL ERROR: ' .
        $curl_error
    );

} else {

    log_message(
        'error',
        'SYNC BERKAS GOOGLE SHEET RESPONSE: ' .
        $response .
        ' | HTTP: ' .
        $http_code
    );
}


return $response;
}
}