<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SiswaDashboard extends CI_Controller {
private $sheet_url = 'https://script.google.com/macros/s/AKfycbySQTOl8EZLnAxYaO5v2PEYwgTUIKgpK2ROeJUlkN2lpbI5UUcfUyUvAT6lc1A5cdtP2A/exec';
   public function __construct()
{
    parent::__construct();

    $this->load->model('Siswa_model');
    $this->load->model('Siswa_jadwal_model');
    $this->load->model('Siswa_biodata_log_model');

    $this->load->model(
        'Sync_queue_model',
        'sync_queue'
    );

    $this->load->database();
}

    private function cek_login()
    {
        if (!$this->session->userdata('siswa_login')) {
            redirect('SiswaAuth');
        }
    }

    private function getSiswa()
    {
        $siswa_id = $this->session->userdata('siswa_id');

        if (!$siswa_id) {
            return null;
        }

        return $this->db
            ->select('siswa.*, kelas.nama AS nama_kelas, tahun_ajaran.tahun AS tahun_ajaran')
            ->join('kelas','kelas.id = siswa.id_kelas','left')
            ->join('tahun_ajaran','tahun_ajaran.id = siswa.tahun_id','left')
            ->where('siswa.id', $siswa_id)
            ->get('siswa')->row();
    }

    public function index()
{
    $this->cek_login();

    // =========================================================
    // AMBIL DATA SISWA
    // =========================================================

    $data['siswa'] = $this->getSiswa();

    if (!$data['siswa']) {
        redirect('SiswaAuth/logout');
        return;
    }


    // =========================================================
    // CEK SISWA KELAS XII
    // =========================================================

    $nama_kelas = strtoupper(
        trim(
            (string) $data['siswa']->nama_kelas
        )
    );

    $is_xii = (
        $nama_kelas === 'XII' ||
        strpos($nama_kelas, 'XII ') === 0
    );


    // =========================================================
    // DEFAULT NOTIFIKASI TKA
    // =========================================================

    $data['is_xii']    = $is_xii;
    $data['tka_notif'] = false;
    $data['tka_data']  = null;


    // =========================================================
    // CEK TKA
    // =========================================================

    if ($is_xii) {

        /*
         * Ambil data TKA berdasarkan SISWA.
         *
         * Tidak kita kunci dengan tahun_id siswa,
         * supaya notif tidak gagal hanya karena tahun_id
         * pada tabel siswa berbeda.
         */

        $tka = $this->db
            ->select('
                id,
                siswa_id,
                tahun_id,
                mapel_1_id,
                mapel_2_id
            ')
            ->from('tka_siswa')
            ->where(
                'siswa_id',
                (int) $data['siswa']->id
            )
            ->order_by(
                'id',
                'DESC'
            )
            ->limit(1)
            ->get()
            ->row();


        /*
         * Belum mengisi / belum lengkap
         */

        if (
            !$tka ||
            empty($tka->mapel_1_id) ||
            empty($tka->mapel_2_id)
        ) {

            $data['tka_notif'] = true;

        } else {

            /*
             * Sudah mengisi lengkap
             */

            $data['tka_data'] = $tka;
        }
    }


    // =========================================================
    // CEK KETUA KELAS
    // =========================================================

    $data['ketua_kelas'] = $this->db
        ->select('kelas.id, kelas.nama')
        ->from('kelas')
        ->where(
            'kelas.ketua_kelas_id',
            $data['siswa']->id
        )
        ->get()
        ->row();


    // =========================================================
    // JADWAL HARI INI
    // =========================================================

    $kelas_id = $this->session->userdata('kelas_id');

    /*
     * Kalau session kelas_id kosong,
     * gunakan kelas dari data siswa.
     */

    if (!$kelas_id) {
        $kelas_id = $data['siswa']->id_kelas;
    }


    $hari_ini = $this->hari_ini();


    $data['hari_ini'] = $hari_ini;


    $data['jadwal_hari_ini'] =
        $this->Siswa_jadwal_model
            ->get_jadwal_hari_ini(
                $kelas_id,
                $hari_ini
            );


    // =========================================================
    // ACTIVE MENU
    // =========================================================

    $data['active'] = 'dashboard';


    // =========================================================
    // LOAD VIEW
    // =========================================================

    $this->load->view(
        'siswa/layout/header',
        $data
    );

    $this->load->view(
        'siswa/layout/sidebar',
        $data
    );

    $this->load->view(
        'siswa/dashboard',
        $data
    );

    $this->load->view(
        'siswa/layout/footer'
    );
}

    public function biodata()
    {
        $this->cek_login();

        $data['siswa'] = $this->getSiswa();
        if (!$data['siswa']) { redirect('SiswaAuth/logout'); }

        $data['active'] = 'biodata';

        $this->load->view('siswa/layout/header', $data);
        $this->load->view('siswa/layout/sidebar', $data);
        $this->load->view('siswa/biodata', $data);
        $this->load->view('siswa/layout/footer');
    }

    public function cetak()
    {
        $this->cek_login();

        $data['siswa'] = $this->getSiswa();
        if (!$data['siswa']) { redirect('SiswaAuth/logout'); }

        $html = $this->load->view('siswa/cetak', $data, TRUE);

        $this->load->library('pdf');
        $pdf = new Tcpdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();
        $pdf->writeHTML($html);
        $pdf->Output('Biodata_'.$data['siswa']->nama.'.pdf', 'I');
    }

    public function mutasi()
    {
        $this->cek_login();

        $siswa = $this->getSiswa();
        if (!$siswa) { redirect('SiswaAuth/logout'); }

        $data['mutasi'] = $this->db
            ->where('siswa_id', $siswa->id)
            ->get('mutasi')
            ->result();

        $data['siswa']   = $siswa;
        $data['active']  = 'mutasi';

        $this->load->view('siswa/layout/header', $data);
        $this->load->view('siswa/layout/sidebar', $data);
        $this->load->view('siswa/mutasi', $data);
        $this->load->view('siswa/layout/footer');
    }

    public function password()
    {
        $this->cek_login();

        $data['siswa'] = $this->getSiswa();
        if (!$data['siswa']) { redirect('SiswaAuth/logout'); }

        $data['active'] = 'password';

        $this->load->view('siswa/layout/header', $data);
        $this->load->view('siswa/layout/sidebar', $data);
        $this->load->view('siswa/password', $data);
        $this->load->view('siswa/layout/footer');
    }

    public function save_password()
    {
        $this->cek_login();

        $siswa = $this->getSiswa();
        if (!$siswa) { redirect('SiswaAuth/logout'); }

        $old = $this->input->post('old');
        $new = $this->input->post('new');

        $password_now = $siswa->password ?: $siswa->nisn;

        if ($old != $password_now) {
            $this->session->set_flashdata('error', "Password lama salah!");
            redirect('SiswaDashboard/password');
        }

        $this->db->where('id', $siswa->id)
                 ->update('siswa', ['password' => $new]);

        $this->session->set_flashdata('success', "Password berhasil diubah!");
        redirect('SiswaDashboard/password');
    }

    public function kartu()
    {
        $this->cek_login();

        $data['siswa'] = $this->getSiswa();
        if (!$data['siswa']) { redirect('SiswaAuth/logout'); }

        $siswa = $data['siswa'];
        $data['active'] = 'kartu';

        require_once APPPATH . 'libraries/phpqrcode/qrlib.php';

        $qr_folder = FCPATH . 'assets/qrcodes/';
        if (!is_dir($qr_folder)) mkdir($qr_folder, 0777, true);

        if (!$siswa->token_qr) {
            $token = uniqid('qr_');
            $this->db->where('id', $siswa->id)->update('siswa', ['token_qr' => $token]);
            $siswa->token_qr = $token;
        }

        $qr_file = $qr_folder . $siswa->token_qr . '.png';

        if (!file_exists($qr_file)) {
            QRcode::png($siswa->token_qr, $qr_file, QR_ECLEVEL_M, 6, 1);
        }

        $data['qr_file'] = base_url('assets/qrcodes/'.$siswa->token_qr.'.png');

        $this->load->view('siswa/layout/header', $data);
        $this->load->view('siswa/layout/sidebar', $data);
        $this->load->view('siswa/kartu', $data);
        $this->load->view('siswa/layout/footer');
    }
    public function idcard()
{
    $this->cek_login();

    $siswa = $this->getSiswa();
    if (!$siswa) { redirect('SiswaAuth/logout'); }

    $data['siswa'] = $siswa;
    $data['active'] = 'idcard';

    // Load library ID Card
    $this->load->library('Idcard_lib');

    // Generate PNG (binary string)
    $imgBinary = $this->idcard_lib->generate($siswa->id);

    // Convert ke Base64 untuk preview
    $data['idcard_base64'] = base64_encode($imgBinary);

    // Load view
    $this->load->view('siswa/layout/header', $data);
    $this->load->view('siswa/layout/sidebar', $data);
    $this->load->view('siswa/idcard_preview', $data);
    $this->load->view('siswa/layout/footer');
}
public function edit_biodata()
{
    $this->cek_login();

    $data['siswa'] = $this->getSiswa();
    if (!$data['siswa']) {
        redirect('SiswaAuth/logout');
    }

    $data['active'] = 'edit_biodata';

    $this->load->view('siswa/layout/header', $data);
    $this->load->view('siswa/layout/sidebar', $data);
    $this->load->view('siswa/edit_biodata', $data);
    $this->load->view('siswa/layout/footer');
}
public function update_biodata()
{
    $this->cek_login();

    $siswa = $this->getSiswa();

    if (!$siswa) {
        redirect('SiswaAuth/logout');
    }


    // =========================================================
    // DATA BARU DARI FORM
    // =========================================================

    $data = [

        'tempat_lahir'      => $this->input->post('tempat_lahir'),
        'tgl_lahir'         => $this->input->post('tgl_lahir'),
        'nomor_kk'          => $this->input->post('nomor_kk'),
        'nik'               => $this->input->post('nik'),
        'anak_keberapa'     => $this->input->post('anak_keberapa'),
        'agama'             => $this->input->post('agama'),

        'alamat'            => $this->input->post('alamat'),
        'rt'                => $this->input->post('rt'),
        'rw'                => $this->input->post('rw'),
        'dusun'             => $this->input->post('dusun'),
        'kecamatan'         => $this->input->post('kecamatan'),
        'kode_pos'          => $this->input->post('kode_pos'),

        'telp'              => $this->input->post('telp'),
        'hp'                => $this->input->post('hp'),
        'email'             => $this->input->post('email'),

        'desil'             => $this->input->post('desil'),
        'penerima_kps'      => $this->input->post('penerima_kps'),
        'no_kps'            => $this->input->post('no_kps'),

        'tinggi_badan'      => $this->input->post('tinggi_badan'),
        'berat_badan'       => $this->input->post('berat_badan'),
        'hobi'              => $this->input->post('hobi'),
        'cita_cita'         => $this->input->post('cita_cita'),

        'sekolah_asal'      => $this->input->post('sekolah_asal'),
        'skhun'             => $this->input->post('skhun'),


        // =====================================================
        // AYAH
        // =====================================================

        'nama_ayah'         => $this->input->post('nama_ayah'),
        'nik_ayah'          => $this->input->post('nik_ayah'),
        'tahun_lahir_ayah'  => $this->input->post('tahun_lahir_ayah'),
        'pendidikan_ayah'   => $this->input->post('pendidikan_ayah'),
        'pekerjaan_ayah'    => $this->input->post('pekerjaan_ayah'),
        'penghasilan_ayah'  => $this->input->post('penghasilan_ayah'),


        // =====================================================
        // IBU
        // =====================================================

        'nama_ibu'          => $this->input->post('nama_ibu'),
        'nik_ibu'           => $this->input->post('nik_ibu'),
        'tahun_lahir_ibu'   => $this->input->post('tahun_lahir_ibu'),
        'pendidikan_ibu'    => $this->input->post('pendidikan_ibu'),
        'pekerjaan_ibu'     => $this->input->post('pekerjaan_ibu'),
        'penghasilan_ibu'   => $this->input->post('penghasilan_ibu'),


        // =====================================================
        // WALI
        // =====================================================

        'nama_wali'         => $this->input->post('nama_wali'),
        'nik_wali'          => $this->input->post('nik_wali'),
        'tahun_lahir_wali'  => $this->input->post('tahun_lahir_wali'),
        'pendidikan_wali'   => $this->input->post('pendidikan_wali'),
        'pekerjaan_wali'    => $this->input->post('pekerjaan_wali'),
        'penghasilan_wali'  => $this->input->post('penghasilan_wali'),


        // =====================================================
        // ORANG TUA
        // =====================================================

        'no_hp_ortu'        => $this->input->post('no_hp_ortu')
    ];


    // =========================================================
    // LABEL FIELD UNTUK LOG
    // =========================================================

    $field_labels = [

        'tempat_lahir'      => 'Tempat Lahir',
        'tgl_lahir'         => 'Tanggal Lahir',
        'nomor_kk'          => 'Nomor KK',
        'nik'               => 'NIK',
        'anak_keberapa'     => 'Anak Keberapa',
        'agama'             => 'Agama',

        'alamat'            => 'Alamat',
        'rt'                => 'RT',
        'rw'                => 'RW',
        'dusun'             => 'Dusun',
        'kecamatan'         => 'Kecamatan',
        'kode_pos'          => 'Kode Pos',

        'telp'              => 'Telepon',
        'hp'                => 'No. HP',
        'email'             => 'Email',

        'desil'             => 'Desil',
        'penerima_kps'      => 'Penerima KPS',
        'no_kps'            => 'No. KPS',

        'tinggi_badan'      => 'Tinggi Badan',
        'berat_badan'       => 'Berat Badan',
        'hobi'              => 'Hobi',
        'cita_cita'         => 'Cita-cita',

        'sekolah_asal'      => 'Sekolah Asal',
        'skhun'             => 'SKHUN',


        // AYAH

        'nama_ayah'         => 'Nama Ayah',
        'nik_ayah'          => 'NIK Ayah',
        'tahun_lahir_ayah'  => 'Tahun Lahir Ayah',
        'pendidikan_ayah'   => 'Pendidikan Ayah',
        'pekerjaan_ayah'    => 'Pekerjaan Ayah',
        'penghasilan_ayah'  => 'Penghasilan Ayah',


        // IBU

        'nama_ibu'          => 'Nama Ibu',
        'nik_ibu'           => 'NIK Ibu',
        'tahun_lahir_ibu'   => 'Tahun Lahir Ibu',
        'pendidikan_ibu'    => 'Pendidikan Ibu',
        'pekerjaan_ibu'     => 'Pekerjaan Ibu',
        'penghasilan_ibu'   => 'Penghasilan Ibu',


        // WALI

        'nama_wali'         => 'Nama Wali',
        'nik_wali'          => 'NIK Wali',
        'tahun_lahir_wali'  => 'Tahun Lahir Wali',
        'pendidikan_wali'   => 'Pendidikan Wali',
        'pekerjaan_wali'    => 'Pekerjaan Wali',
        'penghasilan_wali'  => 'Penghasilan Wali',


        // ORANG TUA

        'no_hp_ortu'        => 'No. HP Orang Tua'
    ];


    // =========================================================
    // SIMPAN BIODATA
    // =========================================================

    $update = $this->db
        ->where('id', $siswa->id)
        ->update('siswa', $data);


    // =========================================================
    // JIKA GAGAL
    // =========================================================

    if (!$update) {

        log_message(
            'error',
            'UPDATE BIODATA GAGAL SISWA ID: ' .
            $siswa->id .
            ' | ' .
            $this->db->last_query()
        );

        return $this->output
            ->set_status_header(500)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'  => false,
                'message' => 'Gagal menyimpan biodata.'
            ]));
    }


    // =========================================================
    // CATAT PERUBAHAN
    // =========================================================

    foreach ($field_labels as $field_name => $field_label) {

        // Nilai lama
        $old_value = isset($siswa->$field_name)
            ? trim((string) $siswa->$field_name)
            : '';


        // Nilai baru
        $new_value = isset($data[$field_name])
            ? trim((string) $data[$field_name])
            : '';


        // Tidak berubah → tidak perlu dicatat
        if ($old_value === $new_value) {
            continue;
        }


        // Simpan / timpa log
        $this->Siswa_biodata_log_model->save_change(
            $siswa->id,
            $field_name,
            $field_label,
            $old_value,
            $new_value,
            $siswa->id
        );
    }


    // =========================================================
    // MASUKKAN KE QUEUE SINKRONISASI
    // =========================================================

    $this->sync_queue->tandai_biodata_pending(
        $siswa->id
    );


    // =========================================================
    // SELESAI
    // =========================================================

    $this->session->set_flashdata(
        'success',
        'Biodata berhasil diperbarui dan menunggu sinkronisasi.'
    );

    redirect('SiswaDashboard/biodata');
}

private function syncGoogleSheet($lama, $baru)
{
    // =========================================================
    // TAMBAHKAN URL BERKAS
    // =========================================================

    $lama = $this->tambahkanUrlBerkas($lama);
    $baru = $this->tambahkanUrlBerkas($baru);


    // =========================================================
    // FIELD INTERNAL YANG TIDAK DIKIRIM
    // =========================================================

    unset(
        $lama['id'],
        $lama['id_kelas'],
        $lama['password'],
        $lama['token_qr'],
        $lama['foto'],
        $lama['rfid_uid'],
        $lama['created_at'],
        $lama['status_verval'],
        $lama['catatan_verval'],
        $lama['verval_by'],
        $lama['verval_at']
    );

    unset(
        $baru['id'],
        $baru['id_kelas'],
        $baru['password'],
        $baru['token_qr'],
        $baru['foto'],
        $baru['rfid_uid'],
        $baru['created_at'],
        $baru['status_verval'],
        $baru['catatan_verval'],
        $baru['verval_by'],
        $baru['verval_at']
    );


    // =========================================================
    // PAYLOAD
    // =========================================================

    $payload = [
        'lama' => $lama,
        'baru' => $baru
    ];


    // =========================================================
    // KIRIM KE GOOGLE APPS SCRIPT
    // =========================================================

    $ch = curl_init($this->sheet_url);

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

    curl_exec($ch);

    curl_close($ch);
}
private function tambahkanUrlBerkas($data)
{
    // =========================================================
    // PASTIKAN ADA ID SISWA
    // =========================================================

    $siswa_id = isset($data['id'])
        ? (int) $data['id']
        : 0;

    if (!$siswa_id) {

        return $data;
    }


    // =========================================================
    // AMBIL BERKAS
    // =========================================================

    $berkas = $this->db
        ->where('siswa_id', $siswa_id)
        ->get('siswa_berkas')
        ->result();


    // =========================================================
    // DEFAULT URL
    // =========================================================

    $url = [
        'kartu_keluarga_url' => '',
        'akta_lahir_url'     => '',
        'ijazah_sd_url'      => '',
        'ijazah_smp_url'     => '',
        'ktp_ayah_url'       => '',
        'ktp_ibu_url'        => ''
    ];


    // =========================================================
    // BUAT URL
    // =========================================================

    foreach ($berkas as $b) {

        if (
            empty($b->access_token)
        ) {
            continue;
        }


        $link =
            base_url(
                'SiswaBerkas/file/' .
                $b->access_token
            );


        switch ($b->jenis_berkas) {

            case 'kartu_keluarga':

                $url['kartu_keluarga_url'] =
                    $link;

                break;


            case 'akta_lahir':

                $url['akta_lahir_url'] =
                    $link;

                break;


            case 'ijazah_sd':

                $url['ijazah_sd_url'] =
                    $link;

                break;


            case 'ijazah_smp':

                $url['ijazah_smp_url'] =
                    $link;

                break;


            case 'ktp_ayah':

                $url['ktp_ayah_url'] =
                    $link;

                break;


            case 'ktp_ibu':

                $url['ktp_ibu_url'] =
                    $link;

                break;
        }
    }


    // =========================================================
    // GABUNGKAN KE DATA SISWA
    // =========================================================

    foreach ($url as $field => $value) {

        $data[$field] = $value;

    }


    return $data;
}
private function hari_ini()
{
    $hari = [
        'Sunday'    => 'Minggu',
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu'
    ];

    return $hari[date('l')];
}
}

?>
