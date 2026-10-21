
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal_solat_public extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->database();
    }

    /**
     * Halaman jadwal salat Dzuhur publik.
     * Menampilkan jadwal hari ini sampai 6 hari ke depan.
     */
    public function index()
    {
        // Zona waktu sekolah
        date_default_timezone_set('Asia/Jakarta');

        $tanggal_mulai = date('Y-m-d');
        $tanggal_akhir = date('Y-m-d', strtotime('+6 days'));

        // Ambil jadwal dari tabel sesi absensi
        $jadwal = array();

        if ($this->db->table_exists('absensi_solat_sesi')) {
            $jadwal = $this->db
                ->select('tanggal, jam_mulai AS jam_dzuhur')
                ->from('absensi_solat_sesi')
                ->where('tanggal >=', $tanggal_mulai)
                ->where('tanggal <=', $tanggal_akhir)
                ->where('jenis_solat', 'dzuhur')
                ->order_by('tanggal', 'ASC')
                ->get()
                ->result_array();
        }

        // Indeks jadwal berdasarkan tanggal
        $jadwal_by_tanggal = array();

        foreach ($jadwal as $row) {
            if (!empty($row['tanggal'])) {
                $jadwal_by_tanggal[$row['tanggal']] = $row;
            }
        }

        // Susun daftar 7 hari, termasuk hari tanpa jadwal
        $daftar_hari = array();

        for ($i = 0; $i < 7; $i++) {
            $tanggal = date(
                'Y-m-d',
                strtotime($tanggal_mulai . ' +' . $i . ' days')
            );

            $data_jadwal = isset($jadwal_by_tanggal[$tanggal])
                ? $jadwal_by_tanggal[$tanggal]
                : null;

            $jam_dzuhur = (
                $data_jadwal &&
                !empty($data_jadwal['jam_dzuhur'])
            ) ? $data_jadwal['jam_dzuhur'] : null;

            $daftar_hari[] = array(
                'tanggal'    => $tanggal,
                'hari'       => $this->nama_hari($tanggal),
                'tanggal_id' => $this->tanggal_indonesia($tanggal),
                'jam_dzuhur' => $jam_dzuhur,
                'kota'       => 'Kuningan',
                'sumber'     => 'Input Admin',
                'tersedia'   => !empty($jam_dzuhur)
            );
        }

        // Data untuk view
        $data = array(
            'title'              => 'Jadwal Salat Dzuhur',
            'jadwal'             => $daftar_hari,
            'tanggal_hari_ini'   => $tanggal_mulai,
            'tanggal_diperbarui' => date('d-m-Y H:i:s')
        );

        $this->load->view(
            'dashboard/jadwal_solat_public',
            $data
        );
    }

    /**
     * Nama hari dalam bahasa Indonesia.
     */
    private function nama_hari($tanggal)
    {
        $hari = array(
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu'
        );

        $nama = date('l', strtotime($tanggal));

        return isset($hari[$nama]) ? $hari[$nama] : $nama;
    }

    /**
     * Format tanggal dalam bahasa Indonesia.
     */
    private function tanggal_indonesia($tanggal)
    {
        $bulan = array(
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        );

        $timestamp = strtotime($tanggal);
        $tgl = date('j', $timestamp);
        $bln = (int) date('n', $timestamp);
        $thn = date('Y', $timestamp);

        return $tgl . ' ' . $bulan[$bln] . ' ' . $thn;
    }
}
