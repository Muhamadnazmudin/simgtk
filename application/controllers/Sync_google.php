<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_google extends CI_Controller
{
    private $sheet_url =
        'https://script.google.com/macros/s/AKfycbySQTOl8EZLnAxYaO5v2PEYwgTUIKgpK2ROeJUlkN2lpbI5UUcfUyUvAT6lc1A5cdtP2A/exec';

    public function __construct()
    {
        parent::__construct();

        $this->load->database();

        $this->load->model(
            'Sync_queue_model',
            'sync_queue'
        );
    }


    /**
     * Halaman sinkronisasi
     */
    public function index()
    {
        $data['active'] = 'sync_google';
        // Untuk sementara cek dulu jumlah pending
        $data['pending'] =
            $this->sync_queue->count_pending();

        $data['title'] =
            'Sinkronisasi Google Spreadsheet';

        
         $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view(
            'admin/sync_google',
            $data
        );
        $this->load->view('templates/footer');
    }


    /**
     * Proses sinkronisasi
     */
    public function proses()
    {
        $queue =
            $this->sync_queue->get_pending();

        if (empty($queue)) {

            $this->session->set_flashdata(
                'success',
                'Tidak ada data yang perlu disinkronkan.'
            );

            redirect('Sync_google');
            return;
        }

        $berhasil = 0;
        $gagal    = 0;

        foreach ($queue as $item) {

            // Tandai sedang diproses
            $this->sync_queue->set_processing(
                $item->id
            );

            try {

                /*
                 * Ambil DATA SISWA TERBARU
                 */
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
                        $item->siswa_id
                    )
                    ->get()
                    ->row_array();


                if (!$siswa) {

                    throw new Exception(
                        'Data siswa tidak ditemukan.'
                    );
                }


                /*
                 * Ambil BERKAS TERBARU
                 */
                $berkas = $this->db
                    ->where(
                        'siswa_id',
                        $item->siswa_id
                    )
                    ->get('siswa_berkas')
                    ->result();


                /*
                 * Default URL berkas
                 */
                $siswa['kartu_keluarga_url'] = '';
                $siswa['akta_lahir_url']     = '';
                $siswa['ijazah_sd_url']      = '';
                $siswa['ijazah_smp_url']     = '';
                $siswa['ktp_ayah_url']       = '';
                $siswa['ktp_ibu_url']        = '';


                /*
                 * Buat URL berkas
                 */
                foreach ($berkas as $b) {

                    if (empty($b->access_token)) {
                        continue;
                    }

                    $url =
                        base_url(
                            'SiswaBerkas/file/' .
                            $b->access_token
                        );

                    switch ($b->jenis_berkas) {

                        case 'kartu_keluarga':
                            $siswa['kartu_keluarga_url'] = $url;
                            break;

                        case 'akta_lahir':
                            $siswa['akta_lahir_url'] = $url;
                            break;

                        case 'ijazah_sd':
                            $siswa['ijazah_sd_url'] = $url;
                            break;

                        case 'ijazah_smp':
                            $siswa['ijazah_smp_url'] = $url;
                            break;

                        case 'ktp_ayah':
                            $siswa['ktp_ayah_url'] = $url;
                            break;

                        case 'ktp_ibu':
                            $siswa['ktp_ibu_url'] = $url;
                            break;
                    }
                }


                /*
                 * Hapus field internal
                 */
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

                    unset($siswa[$field]);

                }


                /*
                 * Untuk sync manual:
                 *
                 * data lama dan baru dibuat sama.
                 *
                 * Google akan mengambil
                 * kondisi TERBARU siswa.
                 */
                $payload = [

                    'lama' => $siswa,
                    'baru' => $siswa

                ];


                /*
                 * KIRIM KE GOOGLE
                 */
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

                $response =
                    curl_exec($ch);

                $http_code =
                    curl_getinfo(
                        $ch,
                        CURLINFO_HTTP_CODE
                    );

                $curl_error =
                    curl_error($ch);

                curl_close($ch);


                /*
                 * CEK HASIL
                 */
                if (
                    $curl_error ||
                    $http_code < 200 ||
                    $http_code >= 400 ||
                    trim($response) !== 'OK'
                ) {

                    throw new Exception(
                        'Google Sync gagal. ' .
                        'HTTP: ' . $http_code .
                        ' Response: ' .
                        substr(
                            strip_tags($response),
                            0,
                            500
                        ) .
                        (
                            $curl_error
                                ? ' CURL: ' . $curl_error
                                : ''
                        )
                    );
                }


                /*
                 * BERHASIL
                 */
                $this->sync_queue->set_synced(
                    $item->id
                );

                $berhasil++;

            }
            catch (Exception $e) {

                $this->sync_queue->set_error(
                    $item->id,
                    $e->getMessage()
                );

                log_message(
                    'error',
                    'SYNC GOOGLE GAGAL SISWA ID ' .
                    $item->siswa_id .
                    ': ' .
                    $e->getMessage()
                );

                $gagal++;
            }
        }


        /*
         * HASIL AKHIR
         */
        $this->session->set_flashdata(
            'success',
            'Sinkronisasi selesai. ' .
            'Berhasil: ' . $berhasil .
            ', Gagal: ' . $gagal
        );

        redirect('Sync_google');
    }
}