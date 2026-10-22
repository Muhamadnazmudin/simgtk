<?php

namespace App\Controllers;

class Mengajar extends BaseController
{
    private function checkGuru(): int
    {
        $this->requireAuth();
        $guruId = (int) $this->session->get('guru_id');
        if (!$guruId) {
            header('Location: ' . site_url('guru_dashboard'));
            exit;
        }
        return $guruId;
    }

    public function mulai($jadwalId = null)
    {
        $guruId = $this->checkGuru();
        $jadwalId = (int) $jadwalId;

        $jadwal = $this->db->table('jadwal_mengajar')->where('id_jadwal', $jadwalId)->get()->getRow();
        if (!$jadwal) {
            return redirect()->to(site_url('guru_dashboard'));
        }

        $existing = $this->db->table('log_mengajar')
            ->where('jadwal_id', $jadwalId)
            ->where('guru_id', $guruId)
            ->where('tanggal', date('Y-m-d'))
            ->get()
            ->getRow();

        if ($existing) {
            return redirect()->to(site_url('guru_dashboard'));
        }

        $this->db->table('log_mengajar')->insert([
            'jadwal_id'  => $jadwalId,
            'guru_id'    => $guruId,
            'rombel_id'  => $jadwal->rombel_id,
            'mapel_id'   => $jadwal->mapel_id,
            'tanggal'    => date('Y-m-d'),
            'jam_mulai'  => date('Y-m-d H:i:s'),
            'status'     => 'mulai',
        ]);

        return redirect()->to(site_url('guru_dashboard'));
    }

    public function selesai()
    {
        $guruId  = $this->checkGuru();
        $logId   = (int) $this->request->getPost('log_id');
        $catatan = trim((string) $this->request->getPost('catatan_keluar'));

        $log = $this->db->table('log_mengajar')
            ->where('id', $logId)
            ->where('guru_id', $guruId)
            ->get()
            ->getRow();

        if (!$log) {
            return redirect()->to(site_url('guru_dashboard'));
        }

        $jadwal = $this->db->table('jadwal_mengajar j')
            ->select('js.jam_selesai')
            ->join('jam_sekolah js', 'js.id_jam = j.jam_selesai_id')
            ->where('j.id_jadwal', $log->jadwal_id)
            ->get()
            ->getRow();

        if ($jadwal) {
            $now          = time();
            $jamSelesai   = strtotime(date('Y-m-d') . ' ' . $jadwal->jam_selesai);
            $selisihMenit = floor(($jamSelesai - $now) / 60);

            if ($selisihMenit >= 30 && empty($catatan)) {
                $this->session->setFlashdata('error', 'Keluar lebih awal (≥ 30 menit) wajib mengisi catatan');
                return redirect()->to(site_url('guru_dashboard'));
            }
        }

        $this->db->table('log_mengajar')->where('id', $logId)->update([
            'jam_selesai'    => date('Y-m-d H:i:s'),
            'status'         => 'menunggu_selfie',
            'catatan_keluar' => $catatan ?: null,
        ]);

        return redirect()->to(site_url('guru_dashboard'));
    }

    public function selfie($logId = null)
    {
        $guruId = $this->checkGuru();
        $logId  = (int) $logId;

        $log = $this->db->table('log_mengajar')
            ->where('id', $logId)
            ->where('guru_id', $guruId)
            ->get()
            ->getRow();

        if (!$log || $log->status !== 'menunggu_selfie') {
            return redirect()->to(site_url('guru_dashboard'));
        }

        $data = [
            'log'    => $log,
            'title'  => 'Selfie Mengajar',
            'active' => 'guru_dashboard',
        ];

        return $this->compatView->render('templates/header', $data)
            . $this->compatView->render('templates/sidebar_guru', $data)
            . $this->compatView->render('guru_dashboard/selfie', $data)
            . $this->compatView->render('templates/footer');
    }

    public function simpan_selfie()
    {
        $guruId = $this->checkGuru();
        $logId  = (int) $this->request->getPost('log_id');

        $file = $this->request->getFile('selfie');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Selfie kosong']);
        }

        $uploadDir = FCPATH . 'uploads/selfie/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        $fileName = 'selfie_' . $logId . '_' . time() . '.' . $file->getExtension();
        $file->move($uploadDir, $fileName);

        $this->db->table('log_mengajar')
            ->where('id', $logId)
            ->where('guru_id', $guruId)
            ->update([
                'selfie' => $fileName,
                'status' => 'selesai',
            ]);

        return $this->response->setJSON(['status' => 'ok']);
    }

    public function tidak_masuk()
    {
        $guruId   = $this->checkGuru();
        $jadwalId = (int) $this->request->getPost('jadwal_id');
        $status   = (string) $this->request->getPost('status');
        $ket      = (string) $this->request->getPost('keterangan');

        $jadwal = $this->db->table('jadwal_mengajar')->where('id_jadwal', $jadwalId)->get()->getRow();
        if ($jadwal) {
            $this->db->table('log_mengajar')->insert([
                'jadwal_id'      => $jadwalId,
                'guru_id'        => $guruId,
                'rombel_id'      => $jadwal->rombel_id,
                'mapel_id'       => $jadwal->mapel_id,
                'tanggal'        => date('Y-m-d'),
                'status'         => $status,
                'catatan_keluar' => $ket ?: null,
            ]);
        }

        return redirect()->to(site_url('guru_dashboard'));
    }
}
