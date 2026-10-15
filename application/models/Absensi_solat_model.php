
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi_solat_model extends CI_Model
{
    protected $table_sesi = 'absensi_solat_sesi';
    protected $table_detail = 'absensi_solat_detail';
    protected $table_siswa = 'siswa';
    protected $table_kelas = 'kelas';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // =====================================================
    // DATA SESI ABSENSI
    // =====================================================

    public function get_sesi($tahun_id = null)
    {
        $this->db->select('*');
        $this->db->from($this->table_sesi);

        if ($tahun_id !== null) {
            $this->db->where('tahun_id', (int) $tahun_id);
        }

        $this->db->order_by('tanggal', 'DESC');
        $this->db->order_by('jam_mulai', 'DESC');

        return $this->db->get()->result();
    }

    public function get_sesi_hari_ini($tahun_id)
    {
        return $this->db
            ->where('tahun_id', (int) $tahun_id)
            ->where('tanggal', date('Y-m-d'))
            ->order_by('jam_mulai', 'DESC')
            ->get($this->table_sesi)
            ->result();
    }

    public function get_sesi_by_id($id, $tahun_id = null)
    {
        $this->db->where('id', (int) $id);

        if ($tahun_id !== null) {
            $this->db->where('tahun_id', (int) $tahun_id);
        }

        return $this->db
            ->limit(1)
            ->get($this->table_sesi)
            ->row();
    }

    public function buat_sesi($data)
    {
        $data_sesi = [
            'tahun_id'    => (int) $data['tahun_id'],
            'tanggal'     => $data['tanggal'],
            'jenis_solat' => isset($data['jenis_solat'])
                ? $data['jenis_solat'] : 'dzuhur',
            'jam_mulai'   => $data['jam_mulai'],
            'jam_selesai' => $data['jam_selesai'],
            'status'      => isset($data['status'])
                ? $data['status'] : 'buka',
            'dibuat_oleh' => isset($data['dibuat_oleh'])
                ? $data['dibuat_oleh'] : null
        ];

        return $this->db->insert($this->table_sesi, $data_sesi);
    }

    public function update_sesi($id, $tahun_id, $data)
    {
        return $this->db
            ->where('id', (int) $id)
            ->where('tahun_id', (int) $tahun_id)
            ->update($this->table_sesi, [
                'tanggal' => $data['tanggal'],
                'jam_mulai' => $data['jam_mulai'],
                'jam_selesai' => $data['jam_selesai']
            ]);
    }

    public function update_status_sesi($id, $status)
    {
        if (!in_array($status, ['buka', 'tutup'], true)) {
            return false;
        }

        return $this->db
            ->where('id', (int) $id)
            ->update($this->table_sesi, [
                'status' => $status
            ]);
    }

    // =====================================================
    // PENCARIAN SISWA
    // =====================================================

    // QR Code berisi NISN.
    public function get_siswa_by_qr($token)
    {
        $token = trim((string) $token);

        if ($token === '') {
            return null;
        }

        return $this->db
            ->where('nisn', $token)
            ->limit(1)
            ->get($this->table_siswa)
            ->row();
    }

    // RFID menggunakan kolom rfid_uid.
    public function get_siswa_by_rfid($uid)
    {
        $uid = trim((string) $uid);

        if ($uid === '') {
            return null;
        }

        return $this->db
            ->where('rfid_uid', $uid)
            ->limit(1)
            ->get($this->table_siswa)
            ->row();
    }

    // Primary key siswa adalah kolom id.
    public function get_siswa_by_id($siswa_id)
    {
        return $this->db
            ->where('id', (int) $siswa_id)
            ->limit(1)
            ->get($this->table_siswa)
            ->row();
    }

    // =====================================================
    // PENYIMPANAN ABSENSI
    // =====================================================

    public function sudah_absen($sesi_id, $siswa_id)
    {
        return $this->db
            ->where('sesi_id', (int) $sesi_id)
            ->where('siswa_id', (int) $siswa_id)
            ->limit(1)
            ->get($this->table_detail)
            ->row();
    }

    public function simpan_absensi($data)
    {
        $sesi_id  = isset($data['sesi_id'])
            ? (int) $data['sesi_id'] : 0;

        $siswa_id = isset($data['siswa_id'])
            ? (int) $data['siswa_id'] : 0;

        if ($sesi_id <= 0 || $siswa_id <= 0) {
            return false;
        }

        $metode = isset($data['metode_absen'])
            ? $data['metode_absen'] : 'qrcode';

        if (!in_array($metode, ['qrcode', 'rfid'], true)) {
            return false;
        }

        $status = isset($data['status'])
            ? $data['status'] : 'hadir';

        if (!in_array($status, ['hadir', 'terlambat'], true)) {
            return false;
        }

        // Pastikan sesi masih terbuka.
        $sesi = $this->get_sesi_by_id($sesi_id);

        if (!$sesi || $sesi->status !== 'buka') {
            return false;
        }

        // Mencegah absensi ganda pada sesi yang sama.
        if ($this->sudah_absen($sesi_id, $siswa_id)) {
            return false;
        }

        $data_detail = [
            'sesi_id'      => $sesi_id,
            'siswa_id'     => $siswa_id,
            'waktu_absen'  => isset($data['waktu_absen'])
                ? $data['waktu_absen'] : date('Y-m-d H:i:s'),
            'metode_absen' => $metode,
            'status'       => $status
        ];

        return $this->db->insert(
            $this->table_detail,
            $data_detail
        );
    }

    // =====================================================
    // STATISTIK ABSENSI
    // =====================================================

    public function get_statistik($sesi_id)
    {
        $total_siswa = $this->db
            ->where('status', 'aktif')
            ->count_all_results($this->table_siswa);

        $this->db->select("
            COUNT(*) AS total_hadir,
            SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END)
                AS hadir,
            SUM(CASE WHEN status = 'terlambat' THEN 1 ELSE 0 END)
                AS terlambat
        ", false);

        $this->db->where('sesi_id', (int) $sesi_id);

        $hasil = $this->db
            ->get($this->table_detail)
            ->row();

        $total_hadir = $hasil ? (int) $hasil->total_hadir : 0;
        $hadir = $hasil ? (int) $hasil->hadir : 0;
        $terlambat = $hasil ? (int) $hasil->terlambat : 0;

        return [
            'total_siswa' => $total_siswa,
            'total_hadir' => $total_hadir,
            'hadir'       => $hadir,
            'terlambat'   => $terlambat,
            'belum_absen' => max(0, $total_siswa - $total_hadir)
        ];
    }

    // =====================================================
    // REKAP ABSENSI
    // =====================================================

    public function get_rekap($filter = [])
    {
        
$this->db->select("
    d.id AS detail_id,
    d.sesi_id,
    d.siswa_id,
    d.waktu_absen,
    d.metode_absen,
    d.status AS status,
    s.nis,
    s.nisn,
    s.nama AS nama_siswa,
    s.id_kelas,
    k.nama AS nama_kelas,
    a.tanggal,
    a.jenis_solat,
    a.tahun_id
", false);


        $this->db->from($this->table_detail . ' d');
        $this->db->join(
            $this->table_sesi . ' a',
            'a.id = d.sesi_id',
            'inner'
        );
        $this->db->join(
            $this->table_siswa . ' s',
            's.id = d.siswa_id',
            'inner'
        );
        $this->db->join(
            $this->table_kelas . ' k',
            'k.id = s.id_kelas',
            'left'
        );

        if (!empty($filter['tahun_id'])) {
            $this->db->where(
                'a.tahun_id',
                (int) $filter['tahun_id']
            );
        }

        if (!empty($filter['tanggal_mulai'])) {
            $this->db->where(
                'a.tanggal >=',
                $filter['tanggal_mulai']
            );
        }

        if (!empty($filter['tanggal_selesai'])) {
            $this->db->where(
                'a.tanggal <=',
                $filter['tanggal_selesai']
            );
        }

        if (!empty($filter['tanggal'])) {
            $this->db->where('a.tanggal', $filter['tanggal']);
        }

        if (!empty($filter['kelas_id'])) {
            $this->db->where(
                's.id_kelas',
                (int) $filter['kelas_id']
            );
        }

        if (!empty($filter['sesi_id'])) {
            $this->db->where(
                'd.sesi_id',
                (int) $filter['sesi_id']
            );
        }

        if (!empty($filter['keyword'])) {
            $keyword = trim($filter['keyword']);

            $this->db->group_start();
            $this->db->like('s.nama', $keyword);
            $this->db->or_like('s.nis', $keyword);
            $this->db->or_like('s.nisn', $keyword);
            $this->db->group_end();
        }

        $this->db->order_by('a.tanggal', 'DESC');
        $this->db->order_by('d.waktu_absen', 'DESC');

        return $this->db->get()->result();
    }

    // =====================================================
    // DATA KELAS UNTUK FILTER REKAP
    // =====================================================

    public function get_kelas($tahun_id = null)
    {
        $this->db->select('id, nama');
        $this->db->from($this->table_kelas);

        if ($tahun_id !== null) {
            $this->db->where('tahun_id', (int) $tahun_id);
        }

        $this->db->order_by('nama', 'ASC');

        return $this->db->get()->result();
    }
    
    
    // =====================================================
    // PENGATURAN OTOMATIS
    // =====================================================

    public function get_sesi_tanggal($tahun_id, $tanggal)
    {
        return $this->db
            ->where('tahun_id', (int) $tahun_id)
            ->where('tanggal', $tanggal)
            ->where('jenis_solat', 'dzuhur')
            ->limit(1)
            ->get($this->table_sesi)
            ->row();
    }


}
