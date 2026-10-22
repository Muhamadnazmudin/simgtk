<?php

namespace App\Controllers;

use App\Models\TendikModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Tendik extends BaseController
{
    protected TendikModel $tendikModel;

    private array $tendikFields = [
        'nama', 'nuptk', 'jk', 'tempat_lahir', 'tanggal_lahir', 'nip',
        'status_kepegawaian', 'jenis_ptk', 'agama',
        'alamat_jalan', 'rt', 'rw', 'nama_dusun', 'desa_kelurahan', 'kecamatan', 'kode_pos',
        'hp', 'email', 'tugas_tambahan',
        'sk_cpns', 'tanggal_cpns', 'sk_pengangkatan', 'tmt_pengangkatan', 'lembaga_pengangkatan',
        'pangkat_golongan', 'sumber_gaji', 'nama_ibu_kandung',
        'status_perkawinan', 'nama_suami_istri', 'nip_suami_istri', 'pekerjaan_suami_istri',
        'tmt_pns', 'sudah_lisensi_kepala_sekolah', 'pernah_diklat_kepengawasan',
        'keahlian_braile', 'keahlian_bahasa_isyarat',
        'npwp', 'kewarganegaraan', 'nik', 'nomor_kk', 'karis_karsu',
        'lintang', 'bujur', 'nuks',
    ];

    public function __construct()
    {
        $this->tendikModel = new TendikModel();
    }

    private function getPostData(): array
    {
        $data = [];
        foreach ($this->tendikFields as $field) {
            $value = $this->request->getPost($field, FILTER_DEFAULT);
            $data[$field] = ($value === '' || $value === false) ? null : trim((string) $value);
        }
        return $data;
    }

    public function index($offset = 0)
    {
        $this->requireRole([1]);

        $search  = trim((string) $this->request->getGet('search'));
        $offset  = (int) $offset;
        $perPage = 10;

        $totalRows = $this->tendikModel->countSearch($search);
        $tendik    = $this->tendikModel->getAll($perPage, $offset, $search);

        $pager = \Config\Services::pager();
        $pager->makeLinks($offset / $perPage + 1, $perPage, $totalRows, 'default_full');

        // Statistik
        $statistik = $this->db->table('tendik')
            ->select("
                COUNT(*) AS total_tendik,
                SUM(CASE WHEN UPPER(TRIM(status_kepegawaian)) = 'PPPK PARUH WAKTU' THEN 1 ELSE 0 END) AS tendik_pppk,
                SUM(CASE WHEN UPPER(TRIM(status_kepegawaian)) = 'PNS' THEN 1 ELSE 0 END) AS tendik_pns,
                SUM(CASE WHEN UPPER(TRIM(status_kepegawaian)) = 'TENAGA HONOR SEKOLAH' THEN 1 ELSE 0 END) AS tendik_honorer
            ")
            ->get()
            ->getRow();

        $data = [
            'title'          => 'Data Tendik',
            'active'         => 'tendik',
            'tendik'         => $tendik,
            'pagination'     => $pager->links(),
            'start'          => $offset,
            'search'         => $search,
            'total_tendik'   => (int) ($statistik->total_tendik ?? 0),
            'tendik_pppk'    => (int) ($statistik->tendik_pppk ?? 0),
            'tendik_pns'     => (int) ($statistik->tendik_pns ?? 0),
            'tendik_honorer' => (int) ($statistik->tendik_honorer ?? 0),
        ];

        return $this->renderView('tendik/index', $data);
    }

    public function add()
    {
        $this->requireRole([1]);

        $data = [
            'title'  => 'Tambah Tendik',
            'active' => 'tendik',
        ];

        if ($this->request->getMethod() === 'POST') {
            $insert = $this->getPostData();

            if (empty($insert['nama']) || empty($insert['nuptk'])) {
                $data['error'] = 'Nama dan NUPTK wajib diisi.';
                return $this->renderView('tendik/add', $data);
            }

            if ($this->tendikModel->existsNuptk($insert['nuptk'])) {
                $data['error'] = 'NUPTK tersebut sudah terdaftar.';
                return $this->renderView('tendik/add', $data);
            }

            $this->tendikModel->insert($insert);
            $this->session->setFlashdata('success', 'Data tendik berhasil ditambahkan.');
            return redirect()->to(site_url('tendik'));
        }

        return $this->renderView('tendik/add', $data);
    }

    public function edit($id = null)
    {
        $this->requireRole([1]);

        $id = (int) $id;
        $tendik = $this->tendikModel->find($id);
        if (!$tendik) {
            return redirect()->to(site_url('tendik'));
        }

        $data = [
            'title'  => 'Edit Tendik',
            'active' => 'tendik',
            'tendik' => $tendik,
        ];

        if ($this->request->getMethod() === 'POST') {
            $update = $this->getPostData();

            if (empty($update['nama']) || empty($update['nuptk'])) {
                $data['error'] = 'Nama dan NUPTK wajib diisi.';
                return $this->renderView('tendik/edit', $data);
            }

            if ($this->tendikModel->existsNuptk($update['nuptk'], $id)) {
                $data['error'] = 'NUPTK tersebut sudah digunakan oleh tendik lain.';
                return $this->renderView('tendik/edit', $data);
            }

            $this->tendikModel->update($id, $update);
            $this->session->setFlashdata('success', 'Data tendik berhasil diperbarui.');
            return redirect()->to(site_url('tendik'));
        }

        return $this->renderView('tendik/edit', $data);
    }

    public function delete($id = null)
    {
        $this->requireRole([1]);

        $id = (int) $id;
        if ($id > 0) {
            $this->tendikModel->delete($id);
            $this->session->setFlashdata('success', 'Data tendik berhasil dihapus.');
        }

        return redirect()->to(site_url('tendik'));
    }

    public function export_excel()
    {
        $this->requireRole([1]);

        $data = $this->tendikModel->getAll(10000, 0);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $col = 'A';
        $sheet->setCellValue($col++ . '1', 'NO');
        foreach ($this->tendikFields as $field) {
            $sheet->setCellValue($col++ . '1', strtoupper($field));
        }

        $row = 2;
        $no  = 1;
        foreach ($data as $t) {
            $col = 'A';
            $sheet->setCellValue($col++ . $row, $no++);
            foreach ($this->tendikFields as $field) {
                $val = $t->$field ?? '';
                $sheet->setCellValueExplicit($col++ . $row, (string) $val, DataType::TYPE_STRING);
            }
            $row++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="data_tendik.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function import_excel()
    {
        $this->requireRole([1]);

        $file = $this->request->getFile('file');
        if (!$file || !$file->isValid()) {
            $this->session->setFlashdata('error', 'Silakan pilih file Excel terlebih dahulu.');
            return redirect()->to(site_url('tendik'));
        }

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            if (empty($sheet)) {
                $this->session->setFlashdata('error', 'File Excel kosong.');
                return redirect()->to(site_url('tendik'));
            }

            $header = array_shift($sheet);
            $headerNormalized = [];
            foreach ($header as $col => $field) {
                $headerNormalized[$col] = strtolower(str_replace(' ', '_', trim((string) $field)));
            }

            $inserted = 0;
            $updated  = 0;
            $skipped  = 0;

            foreach ($sheet as $row) {
                $record = [];
                foreach ($headerNormalized as $col => $fieldName) {
                    if (in_array($fieldName, $this->tendikFields, true)) {
                        $record[$fieldName] = trim((string) ($row[$col] ?? ''));
                    }
                }

                if (empty($record['nuptk'])) {
                    $skipped++;
                    continue;
                }

                $res = $this->tendikModel->importUpsert($record);
                if ($res['action'] === 'insert') {
                    $inserted++;
                } elseif ($res['action'] === 'update') {
                    $updated++;
                }
            }

            $this->session->setFlashdata('success', "Import selesai. Ditambahkan: $inserted, Diperbarui: $updated, Dilewati: $skipped");
        } catch (\Throwable $e) {
            $this->session->setFlashdata('error', 'Gagal memproses file: ' . $e->getMessage());
        }

        return redirect()->to(site_url('tendik'));
    }
}
