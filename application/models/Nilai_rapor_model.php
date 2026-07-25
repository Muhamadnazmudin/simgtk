<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nilai_rapor_model extends CI_Model {

    private $table = 'nilai_rapor';
private $tahun_id;
public function __construct()
{
    parent::__construct();

    $session_tahun = $this->session->userdata('tahun_id');

    if ($session_tahun) {

        $this->tahun_id = $session_tahun;

    } else {

        $t = $this->db
            ->get_where('tahun_ajaran', [
                'aktif' => 1
            ])
            ->row();

        $this->tahun_id = $t ? $t->id : null;
    }
}
    /* ===============================
       INSERT NILAI (HANYA KOLOM VALID)
    ================================= */
    public function insert($data)
    {
       return $this->db->insert($this->table, [
    'siswa_id'    => $data['siswa_id'],
    'mapel_id'    => $data['mapel_id'],
    'semester'    => $data['semester'],
    'tahun_id'    => $data['tahun_id'],
    'nilai_angka' => $data['nilai_angka']
]);
    }

    /* ===============================
       CEK DUPLIKAT NILAI
    ================================= */
   public function cek_nilai($siswa_id, $mapel_id, $semester)
{
    return $this->db->get_where($this->table, [
        'siswa_id' => $siswa_id,
        'mapel_id' => $mapel_id,
        'semester' => $semester,
        'tahun_id' => $this->tahun_id
    ])->row();
}

    /* ===============================
       NILAI SISWA (VIEW SISWA)
    ================================= */
    public function get_by_siswa($siswa_id, $semester)
    {
        return $this->db
            ->select('nr.nilai_angka, m.nama_mapel')
            ->from('nilai_rapor nr')
            ->join('mapel m', 'm.id_mapel = nr.mapel_id', 'left')
            ->where('nr.siswa_id', $siswa_id)
            ->where('nr.semester', $semester)
            ->order_by('m.nama_mapel', 'ASC')
            ->get()
            ->result();
    }

    /* ===============================
       REKAP NILAI (ADMIN)
    ================================= */
    public function rekap_nilai($kelas_id = null, $mapel_id = null)
    {
        $this->db->select('
            s.id AS siswa_id,
            s.nama AS nama_siswa,
            k.nama AS nama_kelas,
            m.id_mapel,
            m.nama_mapel,

            COALESCE(SUM(CASE WHEN nr.semester = 1 THEN nr.nilai_angka END),0) AS smt1,
            COALESCE(SUM(CASE WHEN nr.semester = 2 THEN nr.nilai_angka END),0) AS smt2,
            COALESCE(SUM(CASE WHEN nr.semester = 3 THEN nr.nilai_angka END),0) AS smt3,
            COALESCE(SUM(CASE WHEN nr.semester = 4 THEN nr.nilai_angka END),0) AS smt4,
            COALESCE(SUM(CASE WHEN nr.semester = 5 THEN nr.nilai_angka END),0) AS smt5,
            COALESCE(SUM(CASE WHEN nr.semester = 6 THEN nr.nilai_angka END),0) AS smt6
        ');

        $this->db->from('siswa s');
        $this->db->join('kelas k', 'k.id = s.id_kelas', 'left');
        $this->db->join('nilai_rapor nr', 'nr.siswa_id = s.id', 'left');
        $this->db->join('mapel m', 'm.id_mapel = nr.mapel_id', 'left');

        $this->db->where('s.status', 'aktif');

        if (!empty($kelas_id)) {
            $this->db->where('s.id_kelas', $kelas_id);
        }

        if (!empty($mapel_id)) {
            $this->db->where('nr.mapel_id', $mapel_id);
        }

        $this->db->group_by('s.id, m.id_mapel');
        $this->db->order_by('s.nama', 'ASC');

        return $this->db->get()->result();
    }
    public function rekap_by_siswa($siswa_id)
{
    return $this->db
        ->select('
            m.nama_mapel,

            COALESCE(SUM(CASE WHEN nr.semester = 1 THEN nr.nilai_angka END), 0) AS smt1,
            COALESCE(SUM(CASE WHEN nr.semester = 2 THEN nr.nilai_angka END), 0) AS smt2,
            COALESCE(SUM(CASE WHEN nr.semester = 3 THEN nr.nilai_angka END), 0) AS smt3,
            COALESCE(SUM(CASE WHEN nr.semester = 4 THEN nr.nilai_angka END), 0) AS smt4,
            COALESCE(SUM(CASE WHEN nr.semester = 5 THEN nr.nilai_angka END), 0) AS smt5
        ')
        ->from('mapel m')
        ->join('nilai_rapor nr', 'nr.mapel_id = m.id_mapel AND nr.siswa_id = '.$this->db->escape($siswa_id), 'left')
        ->group_by('m.id_mapel')
        ->order_by('m.nama_mapel', 'ASC')
        ->get()
        ->result();
}
public function rekap_by_siswa_kelas($siswa_id)
{
    $kelas_id = $this->db
        ->select('id_kelas')
        ->from('siswa')
        ->where('id', $siswa_id)
        ->get()
        ->row()
        ->id_kelas;

    return $this->db
        ->select('
            m.nama_mapel,

            MAX(CASE WHEN nr.semester = 1 AND nr.siswa_id = '.$this->db->escape($siswa_id).' THEN nr.nilai_angka END) AS smt1,
            MAX(CASE WHEN nr.semester = 2 AND nr.siswa_id = '.$this->db->escape($siswa_id).' THEN nr.nilai_angka END) AS smt2,
            MAX(CASE WHEN nr.semester = 3 AND nr.siswa_id = '.$this->db->escape($siswa_id).' THEN nr.nilai_angka END) AS smt3,
            MAX(CASE WHEN nr.semester = 4 AND nr.siswa_id = '.$this->db->escape($siswa_id).' THEN nr.nilai_angka END) AS smt4,
            MAX(CASE WHEN nr.semester = 5 AND nr.siswa_id = '.$this->db->escape($siswa_id).' THEN nr.nilai_angka END) AS smt5
        ')
        ->from('nilai_rapor nr')
        ->join('mapel m', 'm.id_mapel = nr.mapel_id')
        ->join('siswa s', 's.id = nr.siswa_id')
        ->where('s.id_kelas', $kelas_id)
        ->group_by('m.id_mapel')
        ->order_by('m.nama_mapel', 'ASC')
        ->get()
        ->result();
}
public function insert_or_update($data)
{
    $cek = $this->db
        ->where('siswa_id', $data['siswa_id'])
        ->where('mapel_id', $data['mapel_id'])
        ->where('semester', $data['semester'])
        ->where('tahun_id', $data['tahun_id'])
        ->get('nilai_rapor')
        ->row();

    if ($cek) {

        return $this->db
            ->where('id_nilai', $cek->id_nilai)
            ->update('nilai_rapor', [
                'nilai_angka' => $data['nilai_angka'],
                'updated_at'  => date('Y-m-d H:i:s')
            ]);

    }

    return $this->db->insert('nilai_rapor', $data);
}
public function rekap_siswa($kelas_id = null, $limit = 100, $offset = 0)
{
    /* ======================================
     * DAFTAR SISWA
     * ====================================== */

    $this->db->select('
    s.id,
    s.nisn,
    s.nama,
    k.nama AS nama_kelas
');

$this->db->from('siswa s');

/*
|--------------------------------------------------------------------------
| Tahun aktif siswa
|--------------------------------------------------------------------------
*/

$this->db->join(
    'siswa_tahun st',
    'st.siswa_id = s.id
     AND st.tahun_id = '.$this->db->escape($this->tahun_id).'
     AND st.status = "aktif"',
    'inner'
);

/*
|--------------------------------------------------------------------------
| Ambil nama kelas dari siswa_tahun
|--------------------------------------------------------------------------
*/

$this->db->join(
    'kelas k',
    'k.id = st.kelas_id',
    'left'
);

if (!empty($kelas_id)) {
    $this->db->where('st.kelas_id', $kelas_id);
}

$this->db->order_by('k.nama', 'ASC');
$this->db->order_by('s.nama', 'ASC');

$this->db->limit($limit, $offset);

$siswa = $this->db->get()->result();

    if (empty($siswa)) {
        return [];
    }

    /* ======================================
     * AMBIL ID SISWA
     * ====================================== */

    $ids = array_column($siswa, 'id');

    /* ======================================
     * AMBIL SEMUA NILAI
     * ====================================== */

    $nilai = $this->db
        ->select('
            siswa_id,
            mapel_id,
            semester,
            nilai_angka
        ')
        ->from('nilai_rapor')
        ->where('tahun_id', $this->tahun_id)
        ->where_in('siswa_id', $ids)
        ->get()
        ->result();

    /* ======================================
     * PIVOT
     * ====================================== */

    $pivot = [];

    foreach ($nilai as $n) {

        $pivot[$n->siswa_id][$n->mapel_id][$n->semester]
            = $n->nilai_angka;

    }

    /* ======================================
     * HASIL
     * ====================================== */

    $hasil = [];

    foreach ($siswa as $s) {

        $hasil[] = [

            'id'    => $s->id,
            'nisn'  => $s->nisn,
            'nama'  => $s->nama,
            'kelas' => $s->nama_kelas,

            'nilai' => isset($pivot[$s->id])
                ? $pivot[$s->id]
                : []

        ];

    }

    return $hasil;
}


public function count_rekap_siswa($kelas_id = null)
{
    $this->db->from('siswa_tahun');

    $this->db->where('tahun_id', $this->tahun_id);
    $this->db->where('status', 'aktif');

    if (!empty($kelas_id)) {
        $this->db->where('kelas_id', $kelas_id);
    }

    return $this->db->count_all_results();
}
public function get_ringkasan_siswa($siswa_id)
{
    return $this->db
        ->select("
            ta.tahun,
            nr.tahun_id,
            nr.semester,
            ROUND(AVG(nr.nilai_angka),2) AS rata_rata
        ")
        ->from('nilai_rapor nr')
        ->join('tahun_ajaran ta','ta.id=nr.tahun_id')
        ->where('nr.siswa_id',$siswa_id)
        ->group_by('nr.tahun_id,nr.semester')
        ->order_by('nr.tahun_id','ASC')
        ->order_by('nr.semester','ASC')
        ->get()
        ->result();
}
/* ==========================================
 * MAPEL YANG DIGUNAKAN PADA REKAP
 * ========================================== */
public function get_mapel_rekap($kelas_id = null)
{
    $this->db->distinct();

    $this->db->select('
        m.id_mapel,
        m.nama_mapel
    ');

    $this->db->from('nilai_rapor nr');

    $this->db->join(
        'mapel m',
        'm.id_mapel = nr.mapel_id'
    );

    $this->db->join(
        'siswa_tahun st',
        'st.siswa_id = nr.siswa_id
        AND st.tahun_id = nr.tahun_id'
    );

    $this->db->where('nr.tahun_id', $this->tahun_id);

    if (!empty($kelas_id)) {
        $this->db->where('st.kelas_id', $kelas_id);
    }

    $this->db->order_by('m.nama_mapel', 'ASC');

    return $this->db->get()->result();
}

/* ==========================================
 * HAPUS NILAI RAPOR
 * ========================================== */
public function hapus_nilai($kelas_id, $mapel_id, $semester = 'all')
{
    $this->db->select('nr.id_nilai');

    $this->db->from('nilai_rapor nr');

    $this->db->join(
        'siswa_tahun st',
        'st.siswa_id = nr.siswa_id
         AND st.tahun_id = nr.tahun_id',
        'inner'
    );

    $this->db->where('nr.tahun_id', $this->tahun_id);
    $this->db->where('st.kelas_id', $kelas_id);
    $this->db->where('st.status', 'aktif');
    $this->db->where('nr.mapel_id', $mapel_id);

    if ($semester != 'all') {

        $this->db->where('nr.semester', $semester);

    }

    $rows = $this->db->get()->result();

    if (empty($rows)) {

        return 0;

    }

    $ids = array_column($rows, 'id_nilai');

    $this->db->where_in('id_nilai', $ids);
    $this->db->delete('nilai_rapor');

    return count($ids);
}
}
