<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Walikelas_laporan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // ===========================
        // LOGIN
        // ===========================
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $guru_id = $this->session->userdata('guru_id');

        if (!$guru_id) {
            show_error('Akses ditolak.',403);
        }

        // ===========================
        // CEK WALI KELAS
        // ===========================
        $kelas = $this->db
            ->get_where('kelas',[
                'wali_kelas_id'=>$guru_id
            ])
            ->row();

        if(!$kelas){
            show_error('Anda bukan wali kelas.',403);
        }

        // simpan ke session
        $this->session->set_userdata([
            'kelas_id'   => $kelas->id,
            'kelas_nama' => $kelas->nama
        ]);

        // ===========================
        // LOAD MODEL
        // ===========================

        $this->load->model('Absensi/Absensi_model');
        $this->load->model('Kelas_model');
        $this->load->model('Siswa_model');
        $this->load->model('Hari_libur_model');

        $this->load->helper(array('url','form'));
    }

    /* ===========================================================
     * LAPORAN ABSENSI MANUAL
     * =========================================================== */

    public function index()
    {

        $kelas_id = $this->session->userdata('kelas_id');

        $data['judul']  = 'Laporan Absensi Manual';
        $data['title']  = 'Laporan Absensi Manual';
        $data['active'] = 'wk_absensi_manual';

        // wali hanya melihat kelas sendiri
        $kelas = $this->Kelas_model->get_by_id($kelas_id);

        if($kelas){
            $data['kelas'] = [$kelas];
        }else{
            $data['kelas'] = [];
        }

        $data['hasil'] = [];

        // ==============================
        // FILTER
        // ==============================

        if ($this->input->post('submit') == 'tampil') {

            $nama    = $this->input->post('nama');
            $dari    = $this->input->post('dari');
            $sampai  = $this->input->post('sampai');
            $tahun = $this->db
    ->where('aktif',1)
    ->get('tahun_ajaran')
    ->row();

$tahun = $tahun ? $tahun->tahun : '-';
            $ket     = $this->input->post('keterangan');

            // otomatis kelas wali
            $data['hasil'] = $this->Absensi_model->laporan_filter(

                $nama,
                $kelas_id,
                $dari,
                $sampai,
                $tahun,
                $ket

            );

            $data['periode'] = $dari.' s/d '.$sampai;
        }
$data['tahun_aktif'] = $this->db
    ->where('aktif',1)
    ->get('tahun_ajaran')
    ->row();
        // ==============================
        // TEMPLATE WALI KELAS
        // ==============================

        $this->load->view('walikelas/templates/header',$data);
        $this->load->view('walikelas/templates/sidebar',$data);
        $this->load->view('absensi/laporan',$data);
        $this->load->view('walikelas/templates/footer');

    }

    /* ===========================================================
 * EXPORT PDF
 * =========================================================== */

public function pdf()
{
    ob_clean();

    ini_set('memory_limit', '1024M');
    ini_set('max_execution_time', 300);

    $this->load->library('pdf');

    // =====================================
    // KELAS OTOMATIS DARI SESSION
    // =====================================

    $kelas = $this->session->userdata('kelas_id');

    $dari   = $this->input->get('dari');
$sampai = $this->input->get('sampai');

// Tahun ajaran aktif
$tahun = $this->db
    ->where('aktif',1)
    ->get('tahun_ajaran')
    ->row();

$tahun = $tahun ? $tahun->tahun : '-';

    if (!$dari || !$sampai) {
        show_error("Filter tanggal wajib diisi.");
    }

    $dari   = date('Y-m-d', strtotime($dari));
    $sampai = date('Y-m-d', strtotime($sampai));

    // =====================================
    // HANYA KELAS WALI
    // =====================================

    $kelas_list = $this->db
        ->where('id', $kelas)
        ->get('kelas')
        ->result();

    if (empty($kelas_list)) {
        show_error("Data kelas tidak ditemukan.");
    }

    // =====================================
    // RANGE TANGGAL
    // =====================================

    $tanggal_all = [];

    $start = new DateTime($dari);
    $end   = new DateTime($sampai);

    for ($i = $start; $i <= $end; $i->modify('+1 day')) {

        $tanggal_all[] = $i->format('Y-m-d');

    }

    // =====================================
    // GROUP PER BULAN
    // =====================================

    $tanggal_per_bulan = [];

    foreach ($tanggal_all as $tgl) {

        $bulan = date('Y-m', strtotime($tgl));

        if (!isset($tanggal_per_bulan[$bulan])) {

            $tanggal_per_bulan[$bulan] = [];

        }

        $tanggal_per_bulan[$bulan][] = $tgl;

    }

    // =====================================
    // HARI LIBUR
    // =====================================

    $tanggalMerah = $this->Hari_libur_model->get_all_dates();

    // =====================================
    // AMBIL ABSENSI
    // =====================================

    $qabsen = $this->db->query("

        SELECT

            d.id_siswa,
            a.tanggal,
            d.status

        FROM absensi_detail d

        JOIN absensi a
        ON a.id_absensi=d.id_absensi

        WHERE a.id_kelas=?

        AND a.tanggal BETWEEN ? AND ?

        ORDER BY a.tanggal ASC

    ",[
        $kelas,
        $dari,
        $sampai
    ])->result();

    $absen = [];

    foreach($qabsen as $r){

        $tgl = $r->tanggal;

        $hari = date('N',strtotime($tgl));

        $libur = in_array($tgl,$tanggalMerah);

        $weekend = ($hari==6 || $hari==7);

        if($libur || $weekend){

            $absen[$r->id_siswa][$tgl]='L';

        }else{

            $st = strtoupper(trim($r->status));

            if($st=='SAKIT' || $st=='S'){

                $kode='S';

            }elseif($st=='IZIN' || $st=='I'){

                $kode='I';

            }elseif($st=='ALPA' || $st=='A'){

                $kode='A';

            }else{

                $kode='H';

            }

            $absen[$r->id_siswa][$tgl]=$kode;

        }

    }

    // =====================================
    // SETUP PDF
    // =====================================

    $this->pdf->setPrintHeader(false);
    $this->pdf->setPrintFooter(false);

    $this->pdf->SetMargins(5,5,5);

    $this->pdf->SetAutoPageBreak(true,5);

    $this->pdf->SetFont('helvetica','',8);

    $this->pdf->SetTitle('Rekap Absensi Manual');

    // =====================================
    // CETAK PER BULAN
    // =====================================

    foreach($kelas_list as $k){

        $siswa = $this->db->query("

            SELECT
                id,
                nama

            FROM siswa

            WHERE id_kelas=?

            AND status='aktif'

            ORDER BY nama ASC

        ",[$k->id])->result();

        foreach($tanggal_per_bulan as $bulan=>$tgl_bulan){

            $data=[

                'siswa'=>$siswa,

                'tanggal'=>$tgl_bulan,

                'absen'=>$absen,

                'tanggalMerah'=>$tanggalMerah,

                'nama_kelas'=>$k->nama,

                'bulan_label'=>date('F Y',strtotime($bulan.'-01')),

                'tahun'=>$tahun

            ];

            $html = $this->load->view(

                'absensi/laporan_pdf_bulan',

                $data,

                true

            );

            $this->pdf->AddPage('L','A4');

            $this->pdf->writeHTML(

                $html,

                true,

                false,

                true,

                false,

                ''

            );

        }

    }

    $this->pdf->Output(

        'Rekap_Absensi_Manual.pdf',

        'I'

    );

}

public function excel()
{
    if (ob_get_length()) {
        ob_end_clean();
    }

    $kelas_id = $this->session->userdata('kelas_id');

    $dari   = $this->input->get('dari');
    $sampai = $this->input->get('sampai');

    if (!$dari || !$sampai) {
        show_error("Filter tanggal wajib diisi.");
    }

    $dari   = date('Y-m-d', strtotime($dari));
    $sampai = date('Y-m-d', strtotime($sampai));

   

    // ===========================
    // TANGGAL
    // ===========================

    $tanggal_all = [];

    $start = new DateTime($dari);
    $end   = new DateTime($sampai);

    for ($d = $start; $d <= $end; $d->modify('+1 day')) {
        $tanggal_all[] = $d->format('Y-m-d');
    }

    // ===========================
    // GROUP PER BULAN
    // ===========================

    $tanggal_per_bulan = [];

    foreach ($tanggal_all as $tgl) {

        $bulan = date('Y-m', strtotime($tgl));

        if (!isset($tanggal_per_bulan[$bulan])) {
            $tanggal_per_bulan[$bulan] = [];
        }

        $tanggal_per_bulan[$bulan][] = $tgl;
    }

    // ===========================
    // HARI LIBUR
    // ===========================

    $qLibur = $this->db
        ->select('start')
        ->get('hari_libur')
        ->result();

    $hariMerah = [];

    foreach ($qLibur as $l) {
        $hariMerah[] = $l->start;
    }

    // ===========================
    // AMBIL DATA SISWA
    // ===========================

    $siswa = $this->db
        ->select('id,nama')
        ->from('siswa')
        ->where('id_kelas', $kelas_id)
        ->where('status', 'aktif')
        ->order_by('nama', 'ASC')
        ->get()
        ->result();

    // ===========================
    // AMBIL DETAIL ABSENSI
    // ===========================

    $qabsen = $this->db->query("
        SELECT
            d.id_siswa,
            a.tanggal,
            d.status
        FROM absensi_detail d
        JOIN absensi a
            ON a.id_absensi=d.id_absensi
        WHERE a.id_kelas=?
        AND a.tanggal BETWEEN ? AND ?
    ", [
        $kelas_id,
        $dari,
        $sampai
    ])->result();

    $arrAbsen = [];

    foreach ($qabsen as $a) {

        $st = strtoupper(trim($a->status));

        if ($st == 'SAKIT' || $st == 'S') {
            $kode = 'S';
        }
        elseif ($st == 'IZIN' || $st == 'I') {
            $kode = 'I';
        }
        elseif ($st == 'ALPA' || $st == 'A') {
            $kode = 'A';
        }
        else {
            $kode = 'H';
        }

        $arrAbsen[$a->id_siswa][$a->tanggal] = $kode;
    }

   // =====================================================
// EXPORT MENGGUNAKAN LIBRARY
// =====================================================

$this->load->library('Spreadsheet_Lib');

$this->spreadsheet_lib->export_absensi_manual(

    $this->session->userdata('kelas_nama'),

    $dari,

    $sampai,

    $tanggal_per_bulan,

    $siswa,

    $arrAbsen,

    $hariMerah

);
}
/* =========================================================
 * AJAX DATA
 * ========================================================= */

public function data()
{
    $kelas_id = $this->session->userdata('kelas_id');

    $dari   = $this->input->post('dari');
    $sampai = $this->input->post('sampai');
    $ket    = $this->input->post('keterangan');

    $this->db->select("
        ad.id_detail,
        a.tanggal,
        s.nama AS nama_siswa,
        k.nama AS nama_kelas,
        ad.status,
        ad.keterangan
    ");

    $this->db->from('absensi_detail ad');
    $this->db->join('absensi a','a.id_absensi=ad.id_absensi');
    $this->db->join('siswa s','s.id=ad.id_siswa');
    $this->db->join('kelas k','k.id=s.id_kelas');

    // hanya kelas wali
    $this->db->where('a.id_kelas',$kelas_id);

    if(!empty($ket) && $ket!='all'){
        $this->db->where('ad.status',$ket);
    }

    if(!empty($dari)){
        $this->db->where('a.tanggal >=',$dari);
    }

    if(!empty($sampai)){
        $this->db->where('a.tanggal <=',$sampai);
    }

    $this->db->order_by('a.tanggal','ASC');
    $this->db->order_by('s.nama','ASC');

    echo json_encode($this->db->get()->result());
}

// =====================================================================
// 4. EXPORT REKAP ABSENSI MANUAL
// =====================================================================
public function export_absensi_manual(
    $kelas_nama,
    $dari,
    $sampai,
    $tanggal_per_bulan,
    $siswa,
    $arrAbsen,
    $hariMerah
)
{
    $spreadsheet = new Spreadsheet();

    $sheetIndex = 0;

    foreach ($tanggal_per_bulan as $bulanKey => $tglBulan) {

        if ($sheetIndex > 0) {
            $spreadsheet->createSheet();
        }

        $sheet = $spreadsheet->setActiveSheetIndex($sheetIndex);

        $sheet->setTitle(substr(date('F Y', strtotime($bulanKey.'-01')),0,31));

        $sheet->setCellValue('A1','REKAP ABSENSI MANUAL');
        $sheet->setCellValue('A2','KELAS : '.$kelas_nama);
        $sheet->setCellValue('A3','PERIODE : '.date('d-m-Y',strtotime($dari)).' s/d '.date('d-m-Y',strtotime($sampai)));
        $sheet->setCellValue('A4','BULAN : '.date('F Y',strtotime($bulanKey.'-01')));

        $sheet->setCellValue('A6','NO');
        $sheet->setCellValue('B6','NAMA SISWA');

        $col = 'C';

        foreach ($tglBulan as $tgl) {

            $sheet->setCellValue($col.'6',date('d',strtotime($tgl)));

            $sheet->getColumnDimension($col)->setWidth(4);

            $col++;
        }

        $sheet->setCellValue($col.'6','H'); $col++;
        $sheet->setCellValue($col.'6','S'); $col++;
        $sheet->setCellValue($col.'6','I'); $col++;
        $sheet->setCellValue($col.'6','A'); $col++;
        $sheet->setCellValue($col.'6','L');

        $row = 7;
        $no  = 1;

        foreach ($siswa as $s) {

            $sheet->setCellValue('A'.$row,$no++);
            $sheet->setCellValue('B'.$row,$s->nama);

            $jumlahH=0;
            $jumlahS=0;
            $jumlahI=0;
            $jumlahA=0;
            $jumlahL=0;

            $col='C';

            foreach($tglBulan as $tgl){

                $hari=date('N',strtotime($tgl));

                $isWeekend=($hari==6 || $hari==7);
                $isLibur=in_array($tgl,$hariMerah);

                if($isWeekend || $isLibur){

                    $kode='L';

                }else{

                    $kode='H';

                    if(isset($arrAbsen[$s->id][$tgl])){
                        $kode=$arrAbsen[$s->id][$tgl];
                    }

                }

                switch($kode){

                    case 'H': $jumlahH++; break;
                    case 'S': $jumlahS++; break;
                    case 'I': $jumlahI++; break;
                    case 'A': $jumlahA++; break;
                    case 'L': $jumlahL++; break;

                }

                $sheet->setCellValue($col.$row,$kode);

                if($kode=='L'){

                    $sheet->getStyle($col.$row)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);

                    $sheet->getStyle($col.$row)
                        ->getFill()
                        ->getStartColor()
                        ->setRGB('FFCCCC');

                }

                $col++;
            }

            $sheet->setCellValue($col.$row,$jumlahH); $col++;
            $sheet->setCellValue($col.$row,$jumlahS); $col++;
            $sheet->setCellValue($col.$row,$jumlahI); $col++;
            $sheet->setCellValue($col.$row,$jumlahA); $col++;
            $sheet->setCellValue($col.$row,$jumlahL);

            $row++;
        }

        foreach(range('A','Z') as $c){
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $sheetIndex++;
    }

    $spreadsheet->setActiveSheetIndex(0);

    $filename='Rekap_Absensi_Manual_'.$kelas_nama.'.xls';

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    $writer = new Xls($spreadsheet);
    $writer->save('php://output');
    exit;
}
}