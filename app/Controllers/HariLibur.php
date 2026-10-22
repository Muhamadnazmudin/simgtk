<?php

namespace App\Controllers;

class HariLibur extends BaseController
{
    public function index()
    {
        $this->requireRole([1, 4]);

        $data = [
            'judul'  => 'Hari Libur Nasional & Sekolah',
            'active' => 'hari_libur',
            'libur'  => $this->db->table('hari_libur')->orderBy('start', 'DESC')->get()->getResult(),
        ];

        return $this->renderView('hari_libur/index', $data);
    }

    public function tambah()
    {
        $this->requireRole([1, 4]);

        $nama     = (string) $this->request->getPost('nama');
        $start    = (string) $this->request->getPost('start');
        $jamMulai = $this->request->getPost('jam_mulai');

        $this->db->table('hari_libur')->insert([
            'nama'      => $nama,
            'start'     => $start,
            'jam_mulai' => $jamMulai ?: null,
        ]);

        $this->session->setFlashdata('success', 'Hari libur berhasil ditambahkan.');
        return redirect()->to(site_url('HariLibur'));
    }

    public function update($id = null)
    {
        $this->requireRole([1, 4]);

        $this->db->table('hari_libur')->where('id', $id)->update([
            'nama'      => (string) $this->request->getPost('nama'),
            'start'     => (string) $this->request->getPost('start'),
            'jam_mulai' => $this->request->getPost('jam_mulai') ?: null,
        ]);

        $this->session->setFlashdata('success', 'Hari libur berhasil diperbarui.');
        return redirect()->to(site_url('HariLibur'));
    }

    public function hapus($id = null)
    {
        $this->requireRole([1, 4]);

        $this->db->table('hari_libur')->where('id', $id)->delete();
        $this->session->setFlashdata('success', 'Hari libur berhasil dihapus.');
        return redirect()->to(site_url('HariLibur'));
    }
}
