<?php

namespace App\Controllers;

use App\Libraries\Idcard_lib;
use App\Models\SiswaBiodataLogModel;
use App\Models\SyncQueueModel;
use TCPDF;

/**
 * Controller SiswaDashboard - CodeIgniter 4
 */
class SiswaDashboard extends BaseController
{
    private function cekLogin()
    {
        if (!$this->session->get('siswa_login')) {
            header('Location: ' . base_url('siswa-login'));
            exit;
        }
    }

    private function getSiswa(): ?object
    {
        $siswaId = $this->session->get('siswa_id');
        if (!$siswaId) {
            return null;
        }

        return $this->db->table('siswa')
            ->select('siswa.*, kelas.nama AS nama_kelas, tahun_ajaran.tahun AS tahun_ajaran')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.tahun_id', 'left')
            ->where('siswa.id', $siswaId)
            ->get()
            ->getRow();
    }

    protected function renderSiswaView(string $view, array $data = []): string
    {
        $data['csrf_name'] = csrf_token();
        $data['csrf_hash'] = csrf_hash();
        return $this->compatView->render('siswa/layout/header', $data)
             . $this->compatView->render('siswa/layout/sidebar', $data)
             . $this->compatView->render($view, $data)
             . $this->compatView->render('siswa/layout/footer', $data);
    }

    public function index()
    {
        $this->cekLogin();

        $data['siswa'] = $this->getSiswa();
        if (!$data['siswa']) {
            return redirect()->to('/SiswaAuth/logout');
        }

        $namaKelas = strtoupper(trim((string) $data['siswa']->nama_kelas));
        $isXii = ($namaKelas === 'XII' || str_starts_with($namaKelas, 'XII '));

        $data['is_xii']    = $isXii;
        $data['tka_notif'] = false;
        $data['tka_data']  = null;

        if ($isXii) {
            $tka = $this->db->table('tka_siswa')
                ->select('id, siswa_id, tahun_id, mapel_1_id, mapel_2_id')
                ->where('siswa_id', (int) $data['siswa']->id)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get()
                ->getRow();

            if (!$tka || empty($tka->mapel_1_id) || empty($tka->mapel_2_id)) {
                $data['tka_notif'] = true;
            } else {
                $data['tka_data'] = $tka;
            }
        }

        $data['ketua_kelas'] = $this->db->table('kelas')
            ->select('kelas.id, kelas.nama')
            ->where('kelas.ketua_kelas_id', $data['siswa']->id)
            ->get()
            ->getRow();

        $kelasId = $this->session->get('kelas_id') ?: $data['siswa']->id_kelas;
        $hariIni = $this->hariIni();

        $data['hari_ini'] = $hariIni;
        $data['jadwal_hari_ini'] = $this->db->table('jadwal_mengajar jm')
            ->select('
                jm.*,
                CASE 
                    WHEN js2.nama_jam IS NOT NULL AND js2.nama_jam != js1.nama_jam 
                    THEN CONCAT(js1.nama_jam, " - ", js2.nama_jam)
                    ELSE js1.nama_jam
                END AS nama_jam,
                js1.jam_mulai,
                COALESCE(js2.jam_selesai, js1.jam_selesai) AS jam_selesai,
                g.nama AS nama_guru,
                m.nama_mapel
            ')
            ->join('jam_sekolah js1', 'js1.id_jam = jm.jam_mulai_id', 'left')
            ->join('jam_sekolah js2', 'js2.id_jam = jm.jam_selesai_id', 'left')
            ->join('guru g', 'g.id = jm.guru_id', 'left')
            ->join('mapel m', 'm.id_mapel = jm.mapel_id', 'left')
            ->where('jm.rombel_id', $kelasId)
            ->where('jm.hari', $hariIni)
            ->orderBy('js1.urutan', 'ASC')
            ->get()
            ->getResult();

        $data['active'] = 'dashboard';
        $data['title']  = 'Dashboard Siswa';

        return $this->renderSiswaView('siswa/dashboard', $data);
    }

    public function biodata()
    {
        $this->cekLogin();

        $data['siswa'] = $this->getSiswa();
        if (!$data['siswa']) {
            return redirect()->to('/SiswaAuth/logout');
        }

        $data['active'] = 'biodata';
        $data['title']  = 'Biodata Siswa';

        return $this->renderSiswaView('siswa/biodata', $data);
    }

    public function cetak()
    {
        $this->cekLogin();

        $data['siswa'] = $this->getSiswa();
        if (!$data['siswa']) {
            return redirect()->to('/SiswaAuth/logout');
        }

        $data['sekolah'] = $this->db->table('sekolah')->get()->getRow();

        $html = $this->compatView->render('siswa/cetak', $data);

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();
        $pdf->writeHTML($html);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('Biodata_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $data['siswa']->nama) . '.pdf', 'I');
        exit;
    }

    public function mutasi()
    {
        $this->cekLogin();

        $siswa = $this->getSiswa();
        if (!$siswa) {
            return redirect()->to('/SiswaAuth/logout');
        }

        $data['mutasi'] = $this->db->table('mutasi')
            ->where('siswa_id', $siswa->id)
            ->get()
            ->getResult();

        $data['siswa']   = $siswa;
        $data['active']  = 'mutasi';
        $data['title']   = 'Data Mutasi Siswa';

        return $this->renderSiswaView('siswa/mutasi', $data);
    }

    public function password()
    {
        $this->cekLogin();

        $data['siswa'] = $this->getSiswa();
        if (!$data['siswa']) {
            return redirect()->to('/SiswaAuth/logout');
        }

        $data['active'] = 'password';
        $data['title']  = 'Ubah Password Siswa';

        return $this->renderSiswaView('siswa/password', $data);
    }

    public function save_password()
    {
        $this->cekLogin();

        $siswa = $this->getSiswa();
        if (!$siswa) {
            return redirect()->to('/SiswaAuth/logout');
        }

        $old = (string) $this->request->getPost('old');
        $new = (string) $this->request->getPost('new');

        $passwordNow = $siswa->password ?: $siswa->nisn;
        $valid = password_verify($old, $passwordNow) || ($old === $passwordNow);

        if (!$valid) {
            $this->session->setFlashdata('error', 'Password lama salah!');
            return redirect()->to('/SiswaDashboard/password');
        }

        $newHashed = password_hash($new, PASSWORD_BCRYPT);
        $this->db->table('siswa')
            ->where('id', $siswa->id)
            ->update(['password' => $newHashed]);

        $this->session->setFlashdata('success', 'Password berhasil diubah!');
        return redirect()->to('/SiswaDashboard/password');
    }

    public function kartu()
    {
        $this->cekLogin();

        $data['siswa'] = $this->getSiswa();
        if (!$data['siswa']) {
            return redirect()->to('/SiswaAuth/logout');
        }

        $siswa = $data['siswa'];
        $data['active'] = 'kartu';
        $data['title']  = 'Kartu Siswa';

        $qrFolder = FCPATH . 'assets/qrcodes/';
        if (!is_dir($qrFolder)) {
            mkdir($qrFolder, 0777, true);
        }

        if (empty($siswa->token_qr)) {
            $token = uniqid('qr_');
            $this->db->table('siswa')->where('id', $siswa->id)->update(['token_qr' => $token]);
            $siswa->token_qr = $token;
        }

        $qrFile = $qrFolder . $siswa->token_qr . '.png';

        if (!file_exists($qrFile)) {
            try {
                $options = new \chillerlan\QRCode\QROptions([
                    'outputType'  => \chillerlan\QRCode\QRCode::OUTPUT_IMAGE_PNG,
                    'eccLevel'    => \chillerlan\QRCode\QRCode::ECC_M,
                    'scale'       => 6,
                    'imageBase64' => false,
                ]);
                (new \chillerlan\QRCode\QRCode($options))->render($siswa->token_qr, $qrFile);
            } catch (\Throwable $e) {
                log_message('error', 'QR Code generation failed: ' . $e->getMessage());
            }
        }

        $data['qr_file'] = base_url('assets/qrcodes/' . $siswa->token_qr . '.png');

        return $this->renderSiswaView('siswa/kartu', $data);
    }

    public function idcard()
    {
        $this->cekLogin();

        $siswa = $this->getSiswa();
        if (!$siswa) {
            return redirect()->to('/SiswaAuth/logout');
        }

        $data['siswa']  = $siswa;
        $data['active'] = 'idcard';
        $data['title']  = 'ID Card Siswa';

        $idcardLib = new Idcard_lib();
        $imgBinary = $idcardLib->generate($siswa->id);

        $data['idcard_base64'] = $imgBinary ? base64_encode($imgBinary) : '';

        return $this->renderSiswaView('siswa/idcard_preview', $data);
    }

    public function edit_biodata()
    {
        $this->cekLogin();

        $data['siswa'] = $this->getSiswa();
        if (!$data['siswa']) {
            return redirect()->to('/SiswaAuth/logout');
        }

        $data['active'] = 'edit_biodata';
        $data['title']  = 'Edit Biodata Siswa';

        return $this->renderSiswaView('siswa/edit_biodata', $data);
    }

    public function update_biodata()
    {
        $this->cekLogin();

        $siswa = $this->getSiswa();
        if (!$siswa) {
            return redirect()->to('/SiswaAuth/logout');
        }

        $fields = [
            'tempat_lahir'     => 'Tempat Lahir',
            'tgl_lahir'        => 'Tanggal Lahir',
            'nomor_kk'         => 'Nomor KK',
            'nik'              => 'NIK',
            'anak_keberapa'    => 'Anak Keberapa',
            'agama'            => 'Agama',
            'alamat'           => 'Alamat',
            'rt'               => 'RT',
            'rw'               => 'RW',
            'dusun'            => 'Dusun',
            'kecamatan'        => 'Kecamatan',
            'kode_pos'         => 'Kode Pos',
            'telp'             => 'Telepon',
            'hp'               => 'No. HP',
            'email'            => 'Email',
            'desil'            => 'Desil',
            'penerima_kps'     => 'Penerima KPS',
            'no_kps'           => 'No. KPS',
            'tinggi_badan'     => 'Tinggi Badan',
            'berat_badan'      => 'Berat Badan',
            'hobi'             => 'Hobi',
            'cita_cita'        => 'Cita-cita',
            'sekolah_asal'     => 'Sekolah Asal',
            'skhun'            => 'SKHUN',
            'nama_ayah'        => 'Nama Ayah',
            'nik_ayah'         => 'NIK Ayah',
            'tahun_lahir_ayah' => 'Tahun Lahir Ayah',
            'pendidikan_ayah'  => 'Pendidikan Ayah',
            'pekerjaan_ayah'   => 'Pekerjaan Ayah',
            'penghasilan_ayah' => 'Penghasilan Ayah',
            'nama_ibu'         => 'Nama Ibu',
            'nik_ibu'          => 'NIK Ibu',
            'tahun_lahir_ibu'  => 'Tahun Lahir Ibu',
            'pendidikan_ibu'   => 'Pendidikan Ibu',
            'pekerjaan_ibu'    => 'Pekerjaan Ibu',
            'penghasilan_ibu'  => 'Penghasilan Ibu',
            'nama_wali'        => 'Nama Wali',
            'nik_wali'         => 'NIK Wali',
            'tahun_lahir_wali' => 'Tahun Lahir Wali',
            'pendidikan_wali'  => 'Pendidikan Wali',
            'pekerjaan_wali'   => 'Pekerjaan Wali',
            'penghasilan_wali' => 'Penghasilan Wali',
            'no_hp_ortu'       => 'No. HP Orang Tua',
        ];

        $data = [];
        foreach (array_keys($fields) as $key) {
            $data[$key] = $this->request->getPost($key);
        }

        $update = $this->db->table('siswa')
            ->where('id', $siswa->id)
            ->update($data);

        if (!$update) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(500)->setJSON([
                    'status'  => false,
                    'message' => 'Gagal menyimpan biodata.',
                ]);
            }
            $this->session->setFlashdata('error', 'Gagal menyimpan biodata.');
            return redirect()->to('/SiswaDashboard/edit_biodata');
        }

        // Catat log perubahan
        $logModel = new SiswaBiodataLogModel();
        foreach ($fields as $fieldName => $fieldLabel) {
            $oldValue = isset($siswa->$fieldName) ? trim((string) $siswa->$fieldName) : '';
            $newValue = isset($data[$fieldName]) ? trim((string) $data[$fieldName]) : '';
            if ($oldValue === $newValue) {
                continue;
            }
            $logModel->save_change(
                $siswa->id,
                $fieldName,
                $fieldLabel,
                $oldValue,
                $newValue,
                $siswa->id
            );
        }

        // Masukkan ke queue sync
        $syncQueue = new SyncQueueModel();
        $syncQueue->tandai_biodata_pending((int) $siswa->id);

        $this->session->setFlashdata('success', 'Biodata berhasil diperbarui dan menunggu sinkronisasi.');

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Biodata berhasil diperbarui.',
            ]);
        }

        return redirect()->to('/SiswaDashboard/biodata');
    }

    private function hariIni(): string
    {
        $map = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        return $map[date('l')] ?? date('l');
    }
}
