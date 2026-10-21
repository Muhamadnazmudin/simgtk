<?php

namespace App\Controllers;

use App\Models\MapelModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Controller Mapel - CodeIgniter 4
 */
class Mapel extends BaseController
{
    protected MapelModel $mapelModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->mapelModel = new MapelModel();

        if (!$this->session->get('logged_in')) {
            header('Location: ' . base_url('auth/login'));
            exit;
        }
    }

    public function index()
    {
        $data = [
            'title'  => 'Mata Pelajaran',
            'active' => 'mapel',
            'mapel'  => $this->mapelModel->get_all(),
        ];

        return $this->renderView('mapel/index', $data);
    }

    public function tambah()
    {
        $data = [
            'title'  => 'Tambah Mata Pelajaran',
            'active' => 'mapel',
        ];

        return $this->renderView('mapel/tambah', $data);
    }

    public function store()
    {
        $data = [
            'id_mapel'   => trim((string) $this->request->getPost('id_mapel')),
            'nama_mapel' => trim((string) $this->request->getPost('nama_mapel')),
            'kelompok'   => trim((string) $this->request->getPost('kelompok')),
            'is_active'  => 1,
        ];

        $this->mapelModel->insert($data);
        return redirect()->to('/mapel')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function import()
    {
        $data = [
            'title'  => 'Import Mata Pelajaran',
            'active' => 'mapel',
        ];

        return $this->renderView('mapel/import', $data);
    }

    public function import_excel()
    {
        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid()) {
            return redirect()->to('/mapel/import')->with('error', 'Pilih file Excel terlebih dahulu.');
        }

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $imported = 0;
            foreach ($sheet as $idx => $row) {
                if ($idx === 1) {
                    continue; // Skip header
                }

                $idMapel   = trim((string) ($row['A'] ?? ''));
                $namaMapel = trim((string) ($row['B'] ?? ''));
                $kelompok  = trim((string) ($row['C'] ?? ''));

                if ($namaMapel !== '') {
                    $this->mapelModel->insert([
                        'id_mapel'   => $idMapel,
                        'nama_mapel' => $namaMapel,
                        'kelompok'   => $kelompok,
                        'is_active'  => 1,
                    ]);
                    $imported++;
                }
            }

            return redirect()->to('/mapel')->with('success', "$imported mata pelajaran berhasil diimport.");
        } catch (\Exception $e) {
            return redirect()->to('/mapel/import')->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }
    }
}
