<?php

namespace App\Controllers;

class Rfid extends BaseController
{
    public function register()
    {
        $this->requireAuth();

        $data = [
            'active' => 'rfid',
            'title'  => 'Registrasi Kartu RFID',
            'siswa'  => $this->db->table('siswa')->orderBy('nama', 'ASC')->get()->getResult(),
        ];

        return $this->compatView->render('rfid/register', $data);
    }

    public function search_siswa()
    {
        $this->requireAuth();

        $keyword = trim((string) $this->request->getGet('q'));
        $kelas   = $this->request->getGet('kelas');

        $builder = $this->db->table('siswa')
            ->select('siswa.id, siswa.nama, kelas.nama AS nama_kelas')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->where('siswa.rfid_uid IS NULL', null, false);

        if (!empty($keyword)) {
            $builder->like('siswa.nama', $keyword);
        }

        if (!empty($kelas) && $kelas !== 'all') {
            $builder->where('siswa.id_kelas', $kelas);
        }

        $result = $builder->orderBy('siswa.nama', 'ASC')->limit(50)->get()->getResult();
        return $this->response->setJSON($result);
    }

    public function save()
    {
        $this->requireAuth();

        $uid     = trim((string) $this->request->getPost('uid'));
        $idSiswa = (int) $this->request->getPost('id_siswa');

        if (empty($uid) || empty($idSiswa)) {
            $this->session->setFlashdata('error', 'Data tidak valid.');
            return redirect()->to(site_url('rfid/register'));
        }

        $cek = $this->db->table('siswa')->where('rfid_uid', $uid)->get()->getRow();
        if ($cek) {
            $this->session->setFlashdata('error', 'UID sudah digunakan oleh siswa lain!');
            return redirect()->to(site_url('rfid/register'));
        }

        $this->db->table('siswa')->where('id', $idSiswa)->update(['rfid_uid' => $uid]);
        $this->session->setFlashdata('success', 'Kartu RFID berhasil ditautkan.');
        return redirect()->to(site_url('rfid/register'));
    }

    public function auto()
    {
        $this->requireAuth();
        return $this->compatView->render('rfid/auto_register');
    }

    public function auto_register_save()
    {
        $uid = trim((string) $this->request->getPost('uid'));
        if (empty($uid)) {
            return $this->response->setJSON(['status' => false, 'message' => 'UID kosong']);
        }

        $exist = $this->db->table('rfid_pending')->where('uid', $uid)->get()->getRow();
        if ($exist) {
            return $this->response->setJSON(['status' => true, 'message' => 'UID sudah tercatat sebelumnya']);
        }

        $this->db->table('rfid_pending')->insert([
            'uid'        => $uid,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['status' => true, 'message' => 'UID berhasil direkam']);
    }

    public function pending()
    {
        $this->requireAuth();
        $data = [
            'pending' => $this->db->table('rfid_pending')->orderBy('created_at', 'DESC')->get()->getResult(),
        ];
        return $this->compatView->render('rfid/list_pending', $data);
    }

    public function match()
    {
        $this->requireAuth();
        $data = [
            'pending' => $this->db->table('rfid_pending')->orderBy('uid', 'ASC')->get()->getResult(),
            'siswa'   => $this->db->table('siswa')->orderBy('nama', 'ASC')->get()->getResult(),
            'kelas'   => $this->db->table('kelas')->orderBy('nama', 'ASC')->get()->getResult(),
        ];
        return $this->compatView->render('rfid/match', $data);
    }

    public function save_match()
    {
        $this->requireAuth();

        $uid     = trim((string) $this->request->getPost('uid'));
        $idSiswa = (int) $this->request->getPost('id_siswa');

        if (empty($uid) || empty($idSiswa)) {
            $this->session->setFlashdata('error', 'Data tidak valid.');
            return redirect()->to(site_url('rfid/match'));
        }

        $this->db->table('siswa')->where('id', $idSiswa)->update(['rfid_uid' => $uid]);
        $this->db->table('rfid_pending')->where('uid', $uid)->delete();

        $this->session->setFlashdata('success', 'Berhasil memetakan UID ke siswa.');
        return redirect()->to(site_url('rfid/match'));
    }

    public function ajax_save_match()
    {
        $uid     = trim((string) $this->request->getPost('uid'));
        $idSiswa = (int) $this->request->getPost('id_siswa');

        if (empty($uid) || empty($idSiswa)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Data tidak lengkap']);
        }

        $this->db->table('siswa')->where('id', $idSiswa)->update(['rfid_uid' => $uid]);
        $this->db->table('rfid_pending')->where('uid', $uid)->delete();

        return $this->response->setJSON(['status' => true, 'message' => 'Berhasil ditautkan']);
    }
}
