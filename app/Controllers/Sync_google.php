<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SyncQueueModel;

class Sync_google extends BaseController
{
    private string $sheet_url = 'https://script.google.com/macros/s/AKfycbySQTOl8EZLnAxYaO5v2PEYwgTUIKgpK2ROeJUlkN2lpbI5UUcfUyUvAT6lc1A5cdtP2A/exec';
    protected SyncQueueModel $syncQueue;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->syncQueue = new SyncQueueModel();
    }

    /**
     * Halaman sinkronisasi
     */
    public function index()
    {
        $this->requireRole([1, 2]);

        $data['active'] = 'sync_google';
        $data['title']  = 'Sinkronisasi Google Spreadsheet';

        $data['pending']        = $this->syncQueue->count_pending();
        $data['pending_detail'] = $this->syncQueue->get_pending_detail();

        $data['jumlah_biodata'] = 0;
        $data['jumlah_berkas']  = 0;

        foreach ($data['pending_detail'] as $item) {
            if ((int) $item->biodata_pending === 1) {
                $data['jumlah_biodata']++;
            }
            if ((int) $item->berkas_pending === 1) {
                $data['jumlah_berkas'] += (int) $item->jumlah_berkas;
            }
        }

        return $this->renderView('admin/sync_google', $data);
    }

    /**
     * Proses sinkronisasi
     */
    public function proses()
    {
        $this->requireRole([1, 2]);

        $queue = $this->syncQueue->get_pending();

        if (empty($queue)) {
            $this->session->setFlashdata('success', 'Tidak ada data yang perlu disinkronkan.');
            return redirect()->to(base_url('Sync_google'));
        }

        $berhasil = 0;
        $gagal    = 0;

        foreach ($queue as $item) {
            $this->syncQueue->set_processing($item->id);

            try {
                $siswa = $this->db->table('siswa')
                    ->select('siswa.*, kelas.nama AS kelas')
                    ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
                    ->where('siswa.id', $item->siswa_id)
                    ->get()
                    ->getRowArray();

                if (!$siswa) {
                    throw new \Exception('Data siswa tidak ditemukan.');
                }

                $berkas = $this->db->table('siswa_berkas')
                    ->where('siswa_id', $item->siswa_id)
                    ->get()
                    ->getResult();

                $siswa['kartu_keluarga_url'] = '';
                $siswa['akta_lahir_url']     = '';
                $siswa['ijazah_sd_url']      = '';
                $siswa['ijazah_smp_url']     = '';
                $siswa['ktp_ayah_url']       = '';
                $siswa['ktp_ibu_url']        = '';

                foreach ($berkas as $b) {
                    if (empty($b->access_token)) {
                        continue;
                    }

                    $url = base_url('SiswaBerkas/file/' . $b->access_token);

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
                    'verval_at',
                ];

                foreach ($remove_fields as $field) {
                    unset($siswa[$field]);
                }

                $payload = [
                    'lama' => $siswa,
                    'baru' => $siswa,
                ];

                $ch = curl_init($this->sheet_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);

                $response   = curl_exec($ch);
                $http_code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curl_error = curl_error($ch);
                curl_close($ch);

                if ($curl_error || $http_code < 200 || $http_code >= 400 || trim((string) $response) !== 'OK') {
                    throw new \Exception(
                        'Google Sync gagal. HTTP: ' . $http_code .
                        ' Response: ' . substr(strip_tags((string) $response), 0, 500) .
                        ($curl_error ? ' CURL: ' . $curl_error : '')
                    );
                }

                $this->syncQueue->set_synced($item->id);
                $berhasil++;
            } catch (\Exception $e) {
                $this->syncQueue->set_error($item->id, $e->getMessage());
                log_message('error', 'SYNC GOOGLE GAGAL SISWA ID ' . $item->siswa_id . ': ' . $e->getMessage());
                $gagal++;
            }
        }

        $this->session->setFlashdata(
            'success',
            'Sinkronisasi selesai. Berhasil: ' . $berhasil . ', Gagal: ' . $gagal
        );

        return redirect()->to(base_url('Sync_google'));
    }
}
