<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas_model extends CI_Model {

    private $table = 'kelas';

    /* ==========================================================
     * GET ALL (JOIN wali kelas + ketua kelas)
     * ========================================================== */
    public function get_all($limit = null, $offset = null)
{
    $this->db->select('
        kelas.*,
        guru.nama AS wali_nama,
        siswa.nama AS ketua_nama
    ');

    $this->db->from($this->table);

    // HAPUS FILTER is_active

    $this->db->join('guru', 'guru.id = kelas.wali_kelas_id', 'left');
    $this->db->join('siswa', 'siswa.id = kelas.ketua_kelas_id', 'left');

    $this->db->order_by('kelas.nama', 'ASC');

    if ($limit !== null) {
        $this->db->limit($limit, $offset);
    }

    return $this->db->get()->result();
}

    /* ==========================================================
     * COUNT
     * ========================================================== */
    public function count_all()
{
    return $this->db->count_all($this->table);
}

    /* ==========================================================
     * CRUD BASIC
     * ========================================================== */
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_by_id($id)
    {
        $this->db->select('
            kelas.*,
            guru.nama  AS wali_nama,
            siswa.nama AS ketua_nama
        ');
        $this->db->from($this->table);
        $this->db->join('guru',  'guru.id = kelas.wali_kelas_id',  'left');
        $this->db->join('siswa', 'siswa.id = kelas.ketua_kelas_id', 'left');
        $this->db->where('kelas.id', $id);

        return $this->db->get()->row();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /* ==========================================================
     * GURU LIST (TAMBAH)
     * hanya guru yang BELUM jadi wali kelas
     * ========================================================== */
    public function get_guru_list()
    {
        $sql = "
            SELECT g.*
            FROM guru g
            LEFT JOIN kelas k ON k.wali_kelas_id = g.id
            WHERE k.wali_kelas_id IS NULL
            GROUP BY g.id
            ORDER BY g.nama ASC
        ";
        return $this->db->query($sql)->result();
    }

    /* ==========================================================
     * GURU LIST (EDIT)
     * guru available + wali kelas saat ini
     * ========================================================== */
    public function get_guru_list_edit($id_kelas)
    {
        $kelas = $this->get_by_id($id_kelas);
        $wali_now = $kelas ? $kelas->wali_kelas_id : 0;

        $sql = "
            SELECT g.*
            FROM guru g
            LEFT JOIN kelas k ON k.wali_kelas_id = g.id
            WHERE k.wali_kelas_id IS NULL
               OR g.id = ?
            GROUP BY g.id
            ORDER BY g.nama ASC
        ";

        return $this->db->query($sql, [$wali_now])->result();
    }

    /* ==========================================================
     * === FUNGSI LAMA (KENAIKAN KELAS) — TIDAK DIUBAH ===
     * ========================================================== */

    // Ambil semua kelas tanpa limit
    public function get_all_simple()
{
    return $this->db
        ->where('is_active', 1)
        ->order_by('nama', 'ASC')
        ->get($this->table)
        ->result();
}

    // Ambil kelas berdasarkan pola nama
   public function get_by_pattern($pattern)
{
    return $this->db
        ->where('is_active', 1)
        ->like('nama', $pattern)
        ->order_by('id', 'ASC')
        ->get($this->table)
        ->row();
}

    // Tentukan kelas berikutnya
    public function next_level($id_kelas)
    {
        $kelas = $this->db->get_where($this->table, ['id' => $id_kelas])->row();
        if (!$kelas) return null;

        $nama = strtoupper($kelas->nama);

        if (strpos($nama, 'XII') !== false) return null;
        elseif (strpos($nama, 'XI') !== false)  $next = 'XII';
        elseif (strpos($nama, 'X ') !== false || strpos($nama, 'X-') !== false) $next = 'XI';
        elseif (strpos($nama, 'IX') !== false)  $next = 'X';
        elseif (strpos($nama, 'VIII') !== false) $next = 'IX';
        elseif (strpos($nama, 'VII') !== false)  $next = 'VIII';
        else return null;

        return $this->db
    ->where('is_active', 1)
    ->like('nama', $next)
    ->order_by('id', 'ASC')
    ->get($this->table)
    ->row();
    }
    // ==========================================================
// 🔥 Ambil siswa dalam kelas tertentu (untuk ketua kelas)
// ==========================================================
public function get_siswa_by_kelas($id_kelas, $ketua_id = null)
{
    // Ambil siswa yang memang ada di kelas ini
    $this->db->where('id_kelas', $id_kelas);
    $this->db->order_by('nama', 'ASC');
    $siswa = $this->db->get('siswa')->result();

    // Jika ketua lama tidak ada di kelas ini, tetap tampilkan
    if ($ketua_id) {
        $exist = false;
        foreach ($siswa as $s) {
            if ($s->id == $ketua_id) {
                $exist = true;
                break;
            }
        }

        if (!$exist) {
            $ketua_lama = $this->db->get_where('siswa', ['id' => $ketua_id])->row();
            if ($ketua_lama) {
                array_unshift($siswa, $ketua_lama);
            }
        }
    }

    return $siswa;
}

}
