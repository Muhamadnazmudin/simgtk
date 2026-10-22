<?php

namespace App\Controllers;

use App\Models\KelasModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Controller Kelas - CodeIgniter 4
 */
class Kelas extends BaseController
{
    protected KelasModel $kelasModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->kelasModel = new KelasModel();

        if (!$this->session->get('logged_in')) {
            header('Location: ' . base_url('auth/login'));
            exit;
        }
    }

    public function index($offset = 0)
    {
        $pager   = service('pager');
        $perPage = 15;
        $total   = $this->kelasModel->countAllResults();

        $page = (int) ($this->request->getGet('page') ?: 0);
        if ($page > 0) {
            $offset = ($page - 1) * $perPage;
        } elseif ((int) $offset > 0) {
            $offset = (int) $offset;
            $page   = (int) floor($offset / $perPage) + 1;
        } else {
            $page   = 1;
            $offset = 0;
        }

        $kelas = $this->kelasModel->get_all($perPage, $offset);

        // Count active students in each class
        foreach ($kelas as &$k) {
            $k->jumlah_siswa = $this->db->table('siswa')
                ->where('id_kelas', $k->id)
                ->where('status', 'aktif')
                ->countAllResults();
        }

        $data = [
            'title'      => 'Data Kelas',
            'active'     => 'kelas',
            'kelas'      => $kelas,
            'pagination' => $pager->makeLinks($page, $perPage, $total, 'bootstrap_pagination'),
            'guru'       => $this->kelasModel->get_guru_list(),
            'siswa'      => $this->db->table('siswa')->orderBy('nama', 'ASC')->get()->getResult(),
            'start'      => $offset,
            'page'       => $page,
            'total'      => $total,
        ];

        return $this->renderView('kelas/index', $data);
    }

    public function add()
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/kelas');
        }

        $waliKelasId  = $this->request->getPost('wali_kelas_id') ?: null;
        $ketuaKelasId = $this->request->getPost('ketua_kelas_id') ?: null;

        // Check if teacher is already walikelas
        if ($waliKelasId) {
            $cek = $this->db->table('kelas')->where('wali_kelas_id', $waliKelasId)->get()->getRow();
            if ($cek) {
                return redirect()->to('/kelas')->with('error', 'Guru sudah menjadi wali kelas di ' . $cek->nama);
            }
        }

        $data = [
            'nama'           => trim((string) $this->request->getPost('nama')),
            'tingkat'        => trim((string) $this->request->getPost('tingkat')),
            'jurusan'        => trim((string) $this->request->getPost('jurusan')),
            'wali_kelas_id'  => $waliKelasId ? (int) $waliKelasId : null,
            'ketua_kelas_id' => $ketuaKelasId ? (int) $ketuaKelasId : null,
        ];

        $this->kelasModel->insert($data);
        return redirect()->to('/kelas')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $kelas = $this->kelasModel->get_by_id((int) $id);
        if (!$kelas) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kelas tidak ditemukan.');
        }

        if ($this->request->is('post')) {
            $waliKelasId  = $this->request->getPost('wali_kelas_id') ?: null;
            $ketuaKelasId = $this->request->getPost('ketua_kelas_id') ?: null;

            if ($waliKelasId) {
                $cek = $this->db->table('kelas')
                    ->where('wali_kelas_id', $waliKelasId)
                    ->where('id !=', (int) $id)
                    ->get()
                    ->getRow();
                if ($cek) {
                    return redirect()->to('/kelas/edit/' . (int) $id)->with('error', 'Guru sudah menjadi wali kelas di ' . $cek->nama);
                }
            }

            $updateData = [
                'nama'           => trim((string) $this->request->getPost('nama')),
                'tingkat'        => trim((string) $this->request->getPost('tingkat')),
                'jurusan'        => trim((string) $this->request->getPost('jurusan')),
                'wali_kelas_id'  => $waliKelasId ? (int) $waliKelasId : null,
                'ketua_kelas_id' => $ketuaKelasId ? (int) $ketuaKelasId : null,
            ];

            $this->kelasModel->update((int) $id, $updateData);
            return redirect()->to('/kelas')->with('success', 'Data kelas berhasil diperbarui.');
        }

        $data = [
            'title'  => 'Edit Data Kelas',
            'active' => 'kelas',
            'kelas'  => $kelas,
            'guru'   => $this->kelasModel->get_guru_list_edit((int) $id),
            'siswa'  => $this->db->table('siswa')->where('id_kelas', (int) $id)->orderBy('nama', 'ASC')->get()->getResult(),
        ];

        return $this->renderView('kelas/edit', $data);
    }

    public function delete($id = null)
    {
        $this->kelasModel->delete((int) $id);
        return redirect()->to('/kelas')->with('success', 'Data kelas berhasil dihapus.');
    }
}
