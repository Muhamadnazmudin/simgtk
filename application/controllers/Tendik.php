<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Tendik extends CI_Controller
{

    // =========================================================
    // DAFTAR FIELD TENDIK
    // =========================================================

    private $tendik_fields = [

        'nama',
        'nuptk',
        'jk',
        'tempat_lahir',
        'tanggal_lahir',
        'nip',

        'status_kepegawaian',
        'jenis_ptk',
        'agama',

        'alamat_jalan',
        'rt',
        'rw',
        'nama_dusun',
        'desa_kelurahan',
        'kecamatan',
        'kode_pos',

        'hp',
        'email',

        'tugas_tambahan',

        'sk_cpns',
        'tanggal_cpns',

        'sk_pengangkatan',
        'tmt_pengangkatan',
        'lembaga_pengangkatan',

        'pangkat_golongan',
        'sumber_gaji',

        'nama_ibu_kandung',

        'status_perkawinan',
        'nama_suami_istri',
        'nip_suami_istri',
        'pekerjaan_suami_istri',

        'tmt_pns',

        'sudah_lisensi_kepala_sekolah',
        'pernah_diklat_kepengawasan',
        'keahlian_braile',
        'keahlian_bahasa_isyarat',

        'npwp',
        'kewarganegaraan',
        'nik',
        'nomor_kk',
        'karis_karsu',

        'lintang',
        'bujur',

        'nuks'
    ];


    // =========================================================
    // CONSTRUCTOR
    // =========================================================

    public function __construct()
    {
        parent::__construct();


        $this->load->model('Tendik_model');


        $this->load->library([
            'form_validation',
            'pagination',
            'Spreadsheet_Lib'
        ]);


        $this->load->helper([
            'url',
            'form'
        ]);


        // =====================================================
        // WAJIB LOGIN
        // =====================================================

        if (!$this->session->userdata('logged_in')) {

            redirect('dashboard');

            exit;
        }


        // =====================================================
        // WAJIB ADMIN
        // =====================================================

        if (
            $this->session->userdata('role_name')
            !== 'admin'
        ) {

            show_error(
                'Anda tidak memiliki akses ke halaman ini.',
                403,
                'Akses Ditolak'
            );

            exit;
        }
    }


    // =========================================================
    // AMBIL DATA POST
    // =========================================================

    private function get_post_data()
    {
        $data = [];


        foreach ($this->tendik_fields as $field) {

            $value =
                $this->input->post(
                    $field,
                    TRUE
                );


            if ($value === '') {
                $value = null;
            }


            $data[$field] = $value;
        }


        return $data;
    }


    // =========================================================
    // INDEX
    // =========================================================

    public function index($offset = 0)
    {

        // =====================================================
        // PENCARIAN
        // =====================================================

        $search = trim(
            $this->input->get(
                'search',
                TRUE
            )
        );


        // =====================================================
        // PAGINATION
        // =====================================================

        $config['base_url'] =
            site_url('tendik/index');


        $config['per_page'] = 10;


        $config['uri_segment'] = 3;


        $config['total_rows'] =
            $this->Tendik_model
                ->count_all($search);


        // Pertahankan pencarian
        // ketika pindah halaman

        if ($search !== '') {

            $config['suffix'] =
                '?search=' .
                rawurlencode($search);


            $config['first_url'] =
                $config['base_url'] .
                '?search=' .
                rawurlencode($search);
        }


        // =====================================================
        // STYLE PAGINATION
        // =====================================================

        $config['full_tag_open'] =
            '<ul class="pagination justify-content-center">';

        $config['full_tag_close'] =
            '</ul>';


        $config['first_tag_open'] =
            '<li class="page-item">';

        $config['first_tag_close'] =
            '</li>';


        $config['last_tag_open'] =
            '<li class="page-item">';

        $config['last_tag_close'] =
            '</li>';


        $config['next_tag_open'] =
            '<li class="page-item">';

        $config['next_tag_close'] =
            '</li>';


        $config['prev_tag_open'] =
            '<li class="page-item">';

        $config['prev_tag_close'] =
            '</li>';


        $config['num_tag_open'] =
            '<li class="page-item">';

        $config['num_tag_close'] =
            '</li>';


        $config['cur_tag_open'] =
            '<li class="page-item active">
                <span class="page-link">';

        $config['cur_tag_close'] =
            '</span></li>';


        $config['attributes'] = [
            'class' => 'page-link'
        ];


        $config['next_link'] = '&raquo;';

        $config['prev_link'] = '&laquo;';

        $config['first_link'] = 'First';

        $config['last_link'] = 'Last';


        $this->pagination->initialize(
            $config
        );


        // =====================================================
        // DATA
        // =====================================================

        $data['title'] =
            'Data Tendik';


        $data['active'] =
            'tendik';


        $data['tendik'] =
            $this->Tendik_model
                ->get_all(
                    $config['per_page'],
                    $offset,
                    $search
                );


        // =====================================================
        // PAGINATION
        // =====================================================

        $data['pagination'] =
            $this->pagination
                ->create_links();


        $data['start'] =
            $offset;


        $data['search'] =
            $search;


        // =====================================================
        // STATISTIK TENDIK
        // =====================================================

        $statistik = $this->db
    ->select("
        COUNT(*) AS total_tendik,

        SUM(
            CASE
                WHEN UPPER(TRIM(status_kepegawaian)) = 'PPPK PARUH WAKTU'
                THEN 1
                ELSE 0
            END
        ) AS tendik_pppk,

        SUM(
            CASE
                WHEN UPPER(TRIM(status_kepegawaian)) = 'PNS'
                THEN 1
                ELSE 0
            END
        ) AS tendik_pns,

        SUM(
            CASE
                WHEN UPPER(TRIM(status_kepegawaian)) = 'TENAGA HONOR SEKOLAH'
                THEN 1
                ELSE 0
            END
        ) AS tendik_honorer

    ", false)
    ->get('tendik')
    ->row();

$data['total_tendik']   = (int) $statistik->total_tendik;
$data['tendik_pppk']    = (int) $statistik->tendik_pppk;
$data['tendik_pns']     = (int) $statistik->tendik_pns;
$data['tendik_honorer'] = (int) $statistik->tendik_honorer;


        // =====================================================
        // LOAD VIEW
        // =====================================================

        $this->load->view(
            'templates/header',
            $data
        );


        $this->load->view(
            'templates/sidebar',
            $data
        );


        $this->load->view(
            'tendik/index',
            $data
        );


        $this->load->view(
            'templates/footer'
        );
    }


    // =========================================================
    // ADD
    // =========================================================

    public function add()
    {

        $data['title'] =
            'Tambah Tendik';


        $data['active'] =
            'tendik';


        if ($this->input->post()) {

            $this->form_validation
                ->set_rules(
                    'nama',
                    'Nama',
                    'required|trim'
                );


            $this->form_validation
                ->set_rules(
                    'nuptk',
                    'NUPTK',
                    'required|trim'
                );


            if (
                $this->form_validation
                    ->run()
            ) {

                $insert =
                    $this->get_post_data();


                // =================================================
                // CEK NUPTK
                // =================================================

                if (
                    $this->Tendik_model
                        ->exists_nuptk(
                            $insert['nuptk']
                        )
                ) {

                    $data['error'] =
                        'NUPTK tersebut sudah terdaftar.';

                } else {

                    $this->Tendik_model
                        ->insert(
                            $insert
                        );


                    $this->session
                        ->set_flashdata(
                            'success',
                            'Data tendik berhasil ditambahkan'
                        );


                    redirect(
                        'tendik'
                    );

                    return;
                }
            }
        }


        $this->load->view(
            'templates/header',
            $data
        );


        $this->load->view(
            'templates/sidebar',
            $data
        );


        $this->load->view(
            'tendik/add',
            $data
        );


        $this->load->view(
            'templates/footer'
        );
    }


    // =========================================================
    // EDIT
    // =========================================================

    public function edit($id)
    {

        $id = (int) $id;


        $data['tendik'] =
            $this->Tendik_model
                ->get_by_id($id);


        if (!$data['tendik']) {

            show_404();

            return;
        }


        $data['title'] =
            'Edit Tendik';


        $data['active'] =
            'tendik';


        if ($this->input->post()) {

            $this->form_validation
                ->set_rules(
                    'nama',
                    'Nama',
                    'required|trim'
                );


            $this->form_validation
                ->set_rules(
                    'nuptk',
                    'NUPTK',
                    'required|trim'
                );


            if (
                $this->form_validation
                    ->run()
            ) {

                $update =
                    $this->get_post_data();


                // =================================================
                // CEK NUPTK
                // =================================================

                if (
                    $this->Tendik_model
                        ->exists_nuptk(
                            $update['nuptk'],
                            $id
                        )
                ) {

                    $data['error'] =
                        'NUPTK tersebut sudah digunakan oleh tendik lain.';

                } else {

                    $this->Tendik_model
                        ->update(
                            $id,
                            $update
                        );


                    $this->session
                        ->set_flashdata(
                            'success',
                            'Data tendik berhasil diperbarui'
                        );


                    redirect(
                        'tendik'
                    );

                    return;
                }
            }
        }


        $this->load->view(
            'templates/header',
            $data
        );


        $this->load->view(
            'templates/sidebar',
            $data
        );


        $this->load->view(
            'tendik/edit',
            $data
        );


        $this->load->view(
            'templates/footer'
        );
    }


    // =========================================================
    // DELETE
    // =========================================================

    public function delete($id)
    {

        $id = (int) $id;


        if ($id <= 0) {

            redirect(
                'tendik'
            );

            return;
        }


        $this->Tendik_model
            ->delete($id);


        $this->session
            ->set_flashdata(
                'success',
                'Data tendik berhasil dihapus'
            );


        redirect(
            'tendik'
        );
    }


    // =========================================================
    // EXPORT EXCEL
    // =========================================================

    public function export_excel()
    {

        @ob_end_clean();

        ob_start();


        $data =
            $this->Tendik_model
                ->get_all(
                    10000,
                    0
                );


        $spreadsheet =
            new Spreadsheet();


        $sheet =
            $spreadsheet
                ->getActiveSheet();


        // =====================================================
        // HEADER
        // =====================================================

        $col = 'A';


        $sheet->setCellValue(
            $col++ . '1',
            'NO'
        );


        foreach (
            $this->tendik_fields
            as $field
        ) {

            $sheet->setCellValue(
                $col++ . '1',
                strtoupper($field)
            );
        }


        // =====================================================
        // DATA
        // =====================================================

        $row = 2;

        $no = 1;


        foreach ($data as $t) {

            $col = 'A';


            $sheet->setCellValue(
                $col++ . $row,
                $no++
            );


            foreach (
                $this->tendik_fields
                as $field
            ) {

                $value =
                    isset($t->$field)
                    ? $t->$field
                    : '';


                $sheet->setCellValueExplicit(
                    $col++ . $row,
                    $value,
                    DataType::TYPE_STRING
                );
            }


            $row++;
        }


        // =====================================================
        // HEADER DOWNLOAD
        // =====================================================

        header(
            'Content-Type: application/vnd.ms-excel'
        );


        header(
            'Content-Disposition: attachment; filename="data_tendik.xls"'
        );


        header(
            'Cache-Control: max-age=0'
        );


        $writer =
            new \PhpOffice\PhpSpreadsheet\Writer\Xls(
                $spreadsheet
            );


        $writer->save(
            'php://output'
        );


        exit;
    }


    // =========================================================
    // IMPORT EXCEL
    // =========================================================

    public function import_excel()
{
    /*
    |--------------------------------------------------------------------------
    | CEK FILE
    |--------------------------------------------------------------------------
    */
    if (empty($_FILES['file']['name'])) {

        $this->session->set_flashdata(
            'error',
            'Silakan pilih file Excel terlebih dahulu.'
        );

        redirect('tendik');
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD EXCEL
    |--------------------------------------------------------------------------
    */
    try {

        $spreadsheet = IOFactory::load(
            $_FILES['file']['tmp_name']
        );

    } catch (Exception $e) {

        $this->session->set_flashdata(
            'error',
            'File Excel tidak dapat dibaca: ' . $e->getMessage()
        );

        redirect('tendik');
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL SHEET
    |--------------------------------------------------------------------------
    */
    $sheet = $spreadsheet
        ->getActiveSheet()
        ->toArray(null, true, true, true);


    if (empty($sheet)) {

        $this->session->set_flashdata(
            'error',
            'File Excel kosong.'
        );

        redirect('tendik');
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */
    $header = array_shift($sheet);

    $header_normalized = [];

    foreach ($header as $col => $field) {

        $field = strtolower(trim($field));

        /*
        | Normalisasi beberapa kemungkinan nama header
        */
        $field = str_replace(' ', '_', $field);

        $header_normalized[$col] = $field;
    }


    /*
    |--------------------------------------------------------------------------
    | COUNTER
    |--------------------------------------------------------------------------
    */
    $inserted = 0;
    $updated  = 0;
    $same     = 0;
    $skipped  = 0;


    /*
    |--------------------------------------------------------------------------
    | PROSES SETIAP BARIS
    |--------------------------------------------------------------------------
    */
    foreach ($sheet as $row) {

        $data = [];


        /*
        |--------------------------------------------------------------------------
        | AMBIL FIELD YANG DIKENAL
        |--------------------------------------------------------------------------
        */
        foreach ($header_normalized as $col => $field) {

            if (!in_array($field, $this->tendik_fields)) {
                continue;
            }

            $value = isset($row[$col])
                ? trim((string) $row[$col])
                : '';

            /*
            | Kosong disimpan sebagai NULL
            */
            $data[$field] = ($value === '')
                ? null
                : $value;
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI NAMA + NUPTK
        |--------------------------------------------------------------------------
        */
        if (
            empty($data['nama']) ||
            empty($data['nuptk'])
        ) {

            $skipped++;
            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | UPSERT
        |--------------------------------------------------------------------------
        */
        $result = $this->Tendik_model->import_upsert($data);


        if (!$result['status']) {

            $skipped++;
            continue;
        }


        switch ($result['action']) {

            case 'insert':
                $inserted++;
                break;

            case 'update':
                $updated++;
                break;

            case 'same':
                $same++;
                break;

            default:
                $skipped++;
                break;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FLASH MESSAGE
    |--------------------------------------------------------------------------
    */
    $message =
        'Import selesai. ' .
        'Data baru: ' . $inserted .
        ', diperbarui: ' . $updated .
        ', tidak berubah: ' . $same .
        ', dilewati: ' . $skipped . '.';


    $this->session->set_flashdata(
        'success',
        $message
    );

    redirect('tendik');
}

public function download_template()
{
    @ob_end_clean();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setTitle('Template Tendik');

    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */
    $col = 1;

    $sheet->setCellValueByColumnAndRow($col++, 1, 'NO');

    foreach ($this->tendik_fields as $field) {
        $sheet->setCellValueByColumnAndRow(
            $col++,
            1,
            strtoupper($field)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STYLE HEADER
    |--------------------------------------------------------------------------
    */
    $highestColumn = $sheet->getHighestColumn();

    $sheet->getStyle(
        'A1:' . $highestColumn . '1'
    )->getFont()->setBold(true);

    $sheet->getStyle(
        'A1:' . $highestColumn . '1'
    )->getAlignment()->setHorizontal(
        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
    );


    /*
    |--------------------------------------------------------------------------
    | CONTOH DATA
    |--------------------------------------------------------------------------
    */
    $example = [
        'nama'               => 'CONTOH NAMA TENDIK',
        'nuptk'              => '1234567890123456',
        'jk'                 => 'L',
        'tempat_lahir'       => 'CIREBON',
        'tanggal_lahir'      => '01/01/1980',
        'status_kepegawaian' => 'PNS',
        'jenis_ptk'          => 'Tenaga Administrasi',
        'agama'              => 'Islam'
    ];


    /*
    |--------------------------------------------------------------------------
    | BARIS CONTOH
    |--------------------------------------------------------------------------
    */
    $sheet->setCellValue('A2', 1);

    $col = 2;

    foreach ($this->tendik_fields as $field) {

        $value = isset($example[$field])
            ? $example[$field]
            : '';

        /*
        | Semua data sebagai STRING.
        | Aman untuk NUPTK, NIK, NIP, KK, NPWP, kode pos, dll.
        */
        $sheet->setCellValueExplicit(
            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '2',
            $value,
            DataType::TYPE_STRING
        );

        $col++;
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT KOLOM DATA SEBAGAI TEXT
    |--------------------------------------------------------------------------
    |
    | Hanya baris contoh, bukan seluruh 1 juta baris Excel.
    |--------------------------------------------------------------------------
    */
    $sheet->getStyle(
        'B2:' . $highestColumn . '2'
    )->getNumberFormat()->setFormatCode('@');


    /*
    |--------------------------------------------------------------------------
    | FREEZE HEADER
    |--------------------------------------------------------------------------
    */
    $sheet->freezePane('A2');


    /*
    |--------------------------------------------------------------------------
    | AUTO WIDTH
    |--------------------------------------------------------------------------
    |
    | Hanya berdasarkan data yang benar-benar ada.
    |--------------------------------------------------------------------------
    */
    for (
        $i = 1;
        $i <= \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        $i++
    ) {

        $columnLetter =
            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);

        $sheet
            ->getColumnDimension($columnLetter)
            ->setAutoSize(true);
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD
    |--------------------------------------------------------------------------
    */
    $filename = 'template_tendik.xlsx';

    header(
        'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    );

    header(
        'Content-Disposition: attachment; filename="' . $filename . '"'
    );

    header('Cache-Control: max-age=0');

    header('Cache-Control: max-age=1');

    header(
        'Expires: Mon, 26 Jul 1997 05:00:00 GMT'
    );

    header(
        'Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'
    );

    header('Pragma: public');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
        $spreadsheet
    );

    $writer->save('php://output');

    $spreadsheet->disconnectWorksheets();

    unset($spreadsheet);

    exit;
}
}