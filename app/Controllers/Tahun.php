<?php

namespace App\Controllers;

use App\Models\TahunModel;

/**
 * Controller Tahun Ajaran - CodeIgniter 4
 */
class Tahun extends BaseController
{
    protected TahunModel $tahunModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->tahunModel = new TahunModel();

        if (!$this->session->get('logged_in')) {
            header('Location: ' . base_url('auth/login'));
            exit;
        }
    }

    public function index()
    {
        $data = [
            'title'      => 'Tahun Ajaran',
            'active'     => 'tahun',
            'tahun_list' => $this->tahunModel->get_all(),
        ];

        return $this->renderView('tahun/index', $data);
    }

    public function add()
    {
        $tahun = trim((string) $this->request->getPost('tahun'));
        $aktif = $this->request->getPost('aktif') ? 1 : 0;

        if ($aktif === 1) {
            $this->tahunModel->reset_active();
        }

        $this->tahunModel->insert(['tahun' => $tahun, 'aktif' => $aktif]);
        return redirect()->to('/tahun')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $tahun = trim((string) $this->request->getPost('tahun'));
        $aktif = $this->request->getPost('aktif') ? 1 : 0;

        if ($aktif === 1) {
            $this->tahunModel->reset_active();
        }

        $this->tahunModel->update((int) $id, ['tahun' => $tahun, 'aktif' => $aktif]);
        return redirect()->to('/tahun')->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        $this->tahunModel->delete((int) $id);
        return redirect()->to('/tahun')->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
