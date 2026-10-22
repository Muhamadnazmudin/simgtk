<?php

namespace App\Controllers;

class Jam_sekolah extends BaseController
{
    public function index()
    {
        $this->requireRole([1, 4]);

        $data = [
            'title'  => 'Jam Sekolah',
            'active' => 'jam_sekolah',
            'jam'    => $this->db->table('jam_sekolah')->orderBy('urutan', 'ASC')->get()->getResult(),
        ];

        return $this->renderView('jam_sekolah/index', $data);
    }

    public function tambah()
    {
        $this->requireRole([1, 4]);

        $data = [
            'title'  => 'Tambah Jam Sekolah',
            'active' => 'jam_sekolah',
        ];

        return $this->renderView('jam_sekolah/tambah', $data);
    }

    public function store()
    {
        $this->requireRole([1, 4]);

        $hariInput = (string) $this->request->getPost('hari');
        $dataBase = [
            'nama_jam'    => (string) $this->request->getPost('nama_jam'),
            'jam_mulai'   => (string) $this->request->getPost('jam_mulai'),
            'jam_selesai' => (string) $this->request->getPost('jam_selesai'),
            'jenis'       => (string) $this->request->getPost('jenis'),
            'urutan'      => (int) $this->request->getPost('urutan'),
            'target'      => (string) $this->request->getPost('target'),
            'is_active'   => 1,
        ];

        $hariValid = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        if ($hariInput === 'ALL') {
            foreach ($hariValid as $h) {
                $exists = $this->db->table('jam_sekolah')
                    ->where('hari', $h)
                    ->where('urutan', $dataBase['urutan'])
                    ->where('target', $dataBase['target'])
                    ->countAllResults() > 0;

                if (!$exists) {
                    $insert = $dataBase;
                    $insert['hari'] = $h;
                    $this->db->table('jam_sekolah')->insert($insert);
                }
            }
        } elseif (in_array($hariInput, $hariValid, true)) {
            $dataBase['hari'] = $hariInput;
            $this->db->table('jam_sekolah')->insert($dataBase);
        } else {
            $this->session->setFlashdata('error', 'Hari tidak valid');
            return redirect()->to(site_url('jam_sekolah/tambah'));
        }

        $this->session->setFlashdata('success', 'Jam sekolah berhasil ditambahkan.');
        return redirect()->to(site_url('jam_sekolah'));
    }

    public function edit($id = null)
    {
        $this->requireRole([1, 4]);

        $jam = $this->db->table('jam_sekolah')->where('id_jam', $id)->get()->getRow();
        if (!$jam) {
            return redirect()->to(site_url('jam_sekolah'));
        }

        $data = [
            'title'  => 'Edit Jam Sekolah',
            'active' => 'jam_sekolah',
            'jam'    => $jam,
        ];

        return $this->renderView('jam_sekolah/edit', $data);
    }

    public function update()
    {
        $this->requireRole([1, 4]);

        $id = (int) $this->request->getPost('id_jam');
        $this->db->table('jam_sekolah')->where('id_jam', $id)->update([
            'hari'        => (string) $this->request->getPost('hari'),
            'nama_jam'    => (string) $this->request->getPost('nama_jam'),
            'jam_mulai'   => (string) $this->request->getPost('jam_mulai'),
            'jam_selesai' => (string) $this->request->getPost('jam_selesai'),
            'jenis'       => (string) $this->request->getPost('jenis'),
            'urutan'      => (int) $this->request->getPost('urutan'),
            'target'      => (string) $this->request->getPost('target'),
        ]);

        $this->session->setFlashdata('success', 'Jam sekolah diperbarui.');
        return redirect()->to(site_url('jam_sekolah'));
    }

    public function delete($id = null)
    {
        $this->requireRole([1, 4]);

        $this->db->table('jam_sekolah')->where('id_jam', $id)->delete();
        $this->session->setFlashdata('success', 'Jam sekolah dihapus.');
        return redirect()->to(site_url('jam_sekolah'));
    }
}
