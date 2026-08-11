<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berkas_siswa_model extends CI_Model
{
    private $table = 'siswa_berkas';

    /**
     * Daftar jenis berkas yang diperbolehkan
     */
    public function jenis_berkas()
{
    return array(
        'kartu_keluarga' => 'Kartu Keluarga',
        'akta_lahir'     => 'Akta Lahir',
        'ijazah_sd'      => 'Ijazah SD',
        'ijazah_smp'     => 'Ijazah SMP/MTs',
        'ktp_ayah'       => 'KTP Ayah',
        'ktp_ibu'        => 'KTP Ibu'
    );
}

    /**
     * Ambil semua siswa
     */
    public function get_siswa($keyword = '')
    {
        $this->db->select('
            s.id,
            s.nis,
            s.nisn,
            s.nama,
            s.id_kelas
        ');

        $this->db->from('siswa s');

        if ($keyword != '') {
            $this->db->group_start();
            $this->db->like('s.nama', $keyword);
            $this->db->or_like('s.nis', $keyword);
            $this->db->or_like('s.nisn', $keyword);
            $this->db->group_end();
        }

        $this->db->order_by('s.nama', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * Ambil data siswa berdasarkan ID
     */
    public function get_siswa_by_id($id)
{
    return $this->db
        ->where('id', $id)
        ->where('status', 'aktif')
        ->get('siswa')
        ->row();
}

    /**
     * Ambil semua berkas milik siswa
     */
    public function get_berkas_siswa($siswa_id)
    {
        return $this->db
            ->where('siswa_id', $siswa_id)
            ->get($this->table)
            ->result();
    }

    /**
     * Ambil berkas berdasarkan jenis
     */
    public function get_berkas($siswa_id, $jenis_berkas)
    {
        return $this->db
            ->where('siswa_id', $siswa_id)
            ->where('jenis_berkas', $jenis_berkas)
            ->get($this->table)
            ->row();
    }

    /**
     * Simpan data berkas
     */
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update data berkas
     */
    public function update($id, $data)
    {
        return $this->db
            ->where('id', $id)
            ->update($this->table, $data);
    }

    /**
     * Hapus data berkas
     */
    public function delete($id)
    {
        return $this->db
            ->where('id', $id)
            ->delete($this->table);
    }

    /**
     * Hitung jumlah berkas siswa
     */
    public function count_berkas($siswa_id)
    {
        return $this->db
            ->where('siswa_id', $siswa_id)
            ->count_all_results($this->table);
    }

    /**
 * Hitung total siswa aktif
 * berdasarkan filter
 */
public function count_siswa_aktif($keyword = '', $kelas_id = '')
{
    $this->db->from('siswa s');

    // Hanya siswa aktif
    $this->db->where('s.status', 'aktif');

    // Filter kelas
    if (!empty($kelas_id)) {
        $this->db->where('s.id_kelas', $kelas_id);
    }

    // Search
    if (!empty($keyword)) {

        $this->db->group_start();

        $this->db->like('s.nama', $keyword);
        $this->db->or_like('s.nis', $keyword);
        $this->db->or_like('s.nisn', $keyword);

        $this->db->group_end();
    }

    return $this->db->count_all_results();
}


/**
 * Ambil siswa aktif dengan pagination
 */
public function get_siswa_with_berkas(
    $keyword = '',
    $kelas_id = '',
    $limit = 20,
    $offset = 0
) {
    $this->db->select('
        s.id,
        s.nis,
        s.nisn,
        s.nama,
        s.id_kelas,
        k.nama AS nama_kelas,
        COUNT(sb.id) AS jumlah_berkas
    ');

    $this->db->from('siswa s');

    // Kelas
    $this->db->join(
        'kelas k',
        'k.id = s.id_kelas',
        'left'
    );

    // Berkas
    $this->db->join(
        'siswa_berkas sb',
        'sb.siswa_id = s.id',
        'left'
    );

    // Hanya siswa aktif
    $this->db->where('s.status', 'aktif');

    // Filter kelas
    if (!empty($kelas_id)) {
        $this->db->where('s.id_kelas', $kelas_id);
    }

    // Search
    if (!empty($keyword)) {

        $this->db->group_start();

        $this->db->like('s.nama', $keyword);
        $this->db->or_like('s.nis', $keyword);
        $this->db->or_like('s.nisn', $keyword);

        $this->db->group_end();
    }

    // GROUP BY
    $this->db->group_by([
        's.id',
        's.nis',
        's.nisn',
        's.nama',
        's.id_kelas',
        'k.nama'
    ]);

    // Urut nama
    $this->db->order_by('s.nama', 'ASC');

    // Pagination
    $this->db->limit($limit, $offset);

    return $this->db->get()->result();
}


/**
 * Daftar kelas yang memiliki siswa aktif
 */
public function get_kelas_aktif()
{
    $this->db->select('
        k.id,
        k.nama,
        COUNT(s.id) AS jumlah_siswa
    ');

    $this->db->from('kelas k');

    $this->db->join(
        'siswa s',
        "s.id_kelas = k.id AND s.status = 'aktif'",
        'inner'
    );

    $this->db->group_by([
        'k.id',
        'k.nama'
    ]);

    $this->db->order_by('k.nama', 'ASC');

    return $this->db->get()->result();
}

/**
 * =========================================================
 * AMBIL SEMUA BERKAS SISWA
 * =========================================================
 *
 * Digunakan oleh SiswaBerkas.
 *
 * Menggunakan tabel yang sama dengan Admin:
 * siswa_berkas
 */
public function get_all_berkas($siswa_id)
{
    $rows = $this->db
        ->where('siswa_id', (int) $siswa_id)
        ->order_by('id', 'ASC')
        ->get($this->table)
        ->result();

    $result = [];

    foreach ($rows as $row) {

        $result[$row->jenis_berkas] = $row;

    }

    return $result;
}


/**
 * =========================================================
 * AMBIL BERKAS BERDASARKAN ID + SISWA
 * =========================================================
 *
 * Untuk keamanan siswa:
 * ID berkas harus sekaligus milik siswa
 * yang sedang login.
 */
public function get_berkas_by_id_siswa($id, $siswa_id)
{
    return $this->db
        ->where('id', (int) $id)
        ->where('siswa_id', (int) $siswa_id)
        ->get($this->table)
        ->row();
}
}