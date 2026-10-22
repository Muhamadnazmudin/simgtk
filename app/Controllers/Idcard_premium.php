<?php

namespace App\Controllers;

use App\Models\IdcardPremiumModel;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class Idcard_premium extends BaseController
{
    protected IdcardPremiumModel $model;

    public function __construct()
    {
        $this->model = new IdcardPremiumModel();
    }

    public function index()
    {
        $this->requireAuth();

        $nama  = trim((string) $this->request->getGet('nama'));
        $kelas = $this->request->getGet('kelas');

        $data = [
            'title'      => 'ID Card Premium',
            'active'     => 'idcard_premium',
            'group_data' => true,
            'siswa'      => $this->model->getList($nama ?: null, $kelas ?: null),
            'kelas_list' => $this->model->getKelas(),
        ];

        return $this->renderView('idcard_premium/index', $data);
    }

    public function preview($id = null)
    {
        $this->requireAuth();

        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $siswa = $this->model->getById((int) $id);
        if (!$siswa) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $qrPath = $this->generateQr($siswa->nisn ?: $siswa->nis);

        $data = [
            'title'      => 'Preview ID Card',
            'active'     => 'idcard_premium',
            'group_data' => true,
            'siswa'      => $siswa,
            'qr_path'    => $qrPath,
        ];

        return $this->compatView->render('idcard_premium/preview', $data);
    }

    public function cetak_kelas($kelasId = null)
    {
        $this->requireAuth();

        if (!$kelasId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $siswa = $this->model->getByKelas((int) $kelasId);
        if (empty($siswa)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Cetak Massal ID Card',
            'siswa' => $siswa,
        ];

        return $this->compatView->render('idcard_premium/cetak_massal', $data);
    }

    private function generateQr(string $text): string
    {
        $folder = FCPATH . 'assets/qrcodes/';
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $safeText = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $text);
        $file = $folder . $safeText . '.png';

        if (!file_exists($file)) {
            try {
                $options = new QROptions([
                    'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
                    'eccLevel'     => QRCode::ECC_H,
                    'scale'        => 6,
                    'imageBase64'  => false,
                ]);
                (new QRCode($options))->render($text, $file);
            } catch (\Throwable $e) {
                log_message('error', 'QR Code generation failed: ' . $e->getMessage());
            }
        }

        return 'assets/qrcodes/' . $safeText . '.png';
    }
}
