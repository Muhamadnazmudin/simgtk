<?php

namespace App\Controllers;

use App\Models\GuruModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

/**
 * Controller Guru - CodeIgniter 4
 */
class Guru extends BaseController
{
    protected GuruModel $guruModel;

    private array $guru_fields = [
        'nip','nama','email','telp',
        'jenis_kelamin','tempat_lahir','tanggal_lahir','nama_ibu_kandung',
        'alamat_jalan','rt','rw','dusun','desa_kelurahan','kecamatan','kode_pos',
        'no_kk','nik','agama',
        'npwp','nama_wajib_pajak','kewarganegaraan',
        'status_perkawinan','nama_pasangan','nip_pasangan','pekerjaan_pasangan',
        'status_kepegawaian','nuptk','nrg',
        'sk_pengangkatan','tmt_pengangkatan','lembaga_pengangkat',
        'sk_cpns','tmt_cpns','tmt_pengangkatan_cpns',
        'pangkat_golongan','sumber_gaji',
        'kartu_pegawai','karis_karsu'
    ];

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->guruModel = new GuruModel();

        if (!$this->session->get('logged_in')) {
            header('Location: ' . base_url('auth/login'));
            exit;
        }
    }

    private function getPostData(): array
    {
        $data = [];
        foreach ($this->guru_fields as $field) {
            $data[$field] = $this->request->getPost($field) ?: null;
        }
        return $data;
    }

    public function index($offset = 0)
    {
        $search = trim((string) $this->request->getGet('search'));
        $pager = service('pager');
        $perPage = 10;
        $total = $this->guruModel->count_all($search);

        $guru = $this->guruModel->get_all($perPage, (int) $offset, $search);

        // Teacher statistics
        $statistik = $this->db->table('guru')
            ->select("
                COUNT(*) AS total_guru,
                SUM(CASE WHEN UPPER(TRIM(status_kepegawaian)) = 'PPPK' THEN 1 ELSE 0 END) AS guru_pppk,
                SUM(CASE WHEN UPPER(TRIM(status_kepegawaian)) = 'PNS' THEN 1 ELSE 0 END) AS guru_pns,
                SUM(CASE WHEN UPPER(TRIM(status_kepegawaian)) = 'PPPKPW' THEN 1 ELSE 0 END) AS guru_pppkpw,
                SUM(CASE WHEN UPPER(TRIM(status_kepegawaian)) IN ('HONOR', 'HONORER') THEN 1 ELSE 0 END) AS guru_honorer
            ", false)
            ->get()
            ->getRow();

        $data = [
            'title'        => 'Data Guru',
            'active'       => 'guru',
            'guru'         => $guru,
            'pagination'   => $pager->makeLinks((int) $offset / $perPage + 1, $perPage, $total),
            'start'        => (int) $offset,
            'search'       => $search,
            'total_guru'   => (int) ($statistik->total_guru ?? 0),
            'guru_pppk'    => (int) ($statistik->guru_pppk ?? 0),
            'guru_pns'     => (int) ($statistik->guru_pns ?? 0),
            'guru_pppkpw'  => (int) ($statistik->guru_pppkpw ?? 0),
            'guru_honorer' => (int) ($statistik->guru_honorer ?? 0),
        ];

        return $this->renderView('guru/index', $data);
    }

    public function add()
    {
        $data = [
            'title'  => 'Tambah Guru',
            'active' => 'guru',
        ];

        if ($this->request->is('post')) {
            $nama = trim((string) $this->request->getPost('nama'));
            if ($nama === '') {
                return redirect()->to('/guru/add')->with('error', 'Nama guru wajib diisi.');
            }

            $insertData = $this->getPostData();
            $this->guruModel->insert($insertData);
            return redirect()->to('/guru')->with('success', 'Data guru berhasil ditambahkan.');
        }

        return $this->renderView('guru/add', $data);
    }

    public function edit($id = null)
    {
        $guru = $this->guruModel->get_by_id((int) $id);
        if (!$guru) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Guru tidak ditemukan.');
        }

        if ($this->request->is('post')) {
            $nama = trim((string) $this->request->getPost('nama'));
            if ($nama === '') {
                return redirect()->to('/guru/edit/' . (int) $id)->with('error', 'Nama guru wajib diisi.');
            }

            $updateData = $this->getPostData();
            $this->guruModel->update((int) $id, $updateData);
            return redirect()->to('/guru')->with('success', 'Data guru berhasil diperbarui.');
        }

        $data = [
            'title'  => 'Edit Guru',
            'active' => 'guru',
            'guru'   => $guru,
        ];

        return $this->renderView('guru/edit', $data);
    }

    public function delete($id = null)
    {
        $this->guruModel->delete((int) $id);
        return redirect()->to('/guru')->with('success', 'Data guru berhasil dihapus.');
    }

    public function export_excel()
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = $this->guruModel->get_all(10000, 0);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data_Guru');

        $col = 'A';
        $sheet->setCellValue($col++ . '1', 'NO');
        foreach ($this->guru_fields as $field) {
            $sheet->setCellValue($col++ . '1', strtoupper($field));
        }

        $rowNum = 2;
        $no = 1;
        foreach ($data as $item) {
            $col = 'A';
            $sheet->setCellValue($col++ . $rowNum, $no++);
            foreach ($this->guru_fields as $field) {
                $val = $item->$field ?? '';
                $sheet->setCellValueExplicit($col++ . $rowNum, (string) $val, DataType::TYPE_STRING);
            }
            $rowNum++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="data_guru_' . date('Ymd_His') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $spreadsheet->disconnectWorksheets();
        exit;
    }
}
