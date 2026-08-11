<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berkas_siswa_model extends CI_Model
{
    private $table = 'siswa_berkas';


    /**
     * =========================================================
     * JENIS BERKAS
     * =========================================================
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
     * =========================================================
     * GENERATE ACCESS TOKEN
     * =========================================================
     *
     * Token 64 karakter.
     * Digunakan untuk URL publik berkas.
     */
    public function generate_access_token()
    {
        return bin2hex(random_bytes(32));
    }


    /**
     * =========================================================
     * AMBIL SEMUA SISWA
     * =========================================================
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
     * =========================================================
     * AMBIL SISWA BERDASARKAN ID
     * =========================================================
     */
    public function get_siswa_by_id($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->where('status', 'aktif')
            ->get('siswa')
            ->row();
    }


    /**
     * =========================================================
     * SEMUA BERKAS SISWA
     * =========================================================
     */
    public function get_berkas_siswa($siswa_id)
    {
        return $this->db
            ->where('siswa_id', (int) $siswa_id)
            ->get($this->table)
            ->result();
    }


    /**
     * =========================================================
     * BERKAS BERDASARKAN JENIS
     * =========================================================
     */
    public function get_berkas($siswa_id, $jenis_berkas)
    {
        return $this->db
            ->where('siswa_id', (int) $siswa_id)
            ->where('jenis_berkas', $jenis_berkas)
            ->get($this->table)
            ->row();
    }


    /**
     * =========================================================
     * INSERT BERKAS
     * =========================================================
     *
     * Jika access_token belum dikirim,
     * otomatis dibuat.
     */
    public function insert($data)
    {
        if (empty($data['access_token'])) {

            $data['access_token'] =
                $this->generate_access_token();
        }

        return $this->db->insert(
            $this->table,
            $data
        );
    }


    /**
     * =========================================================
     * UPDATE BERKAS
     * =========================================================
     *
     * Token lama TIDAK diubah ketika file diganti.
     *
     * Jadi URL Google Spreadsheet tetap sama.
     */
    public function update($id, $data)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update(
                $this->table,
                $data
            );
    }


    /**
     * =========================================================
     * HAPUS BERKAS
     * =========================================================
     */
    public function delete($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->delete($this->table);
    }


    /**
     * =========================================================
     * JUMLAH BERKAS SISWA
     * =========================================================
     */
    public function count_berkas($siswa_id)
    {
        return $this->db
            ->where('siswa_id', (int) $siswa_id)
            ->count_all_results($this->table);
    }


    /**
     * =========================================================
     * HITUNG SISWA AKTIF
     * =========================================================
     */
    public function count_siswa_aktif(
        $keyword = '',
        $kelas_id = ''
    ) {
        $this->db->from('siswa s');

        $this->db->where(
            's.status',
            'aktif'
        );

        if (!empty($kelas_id)) {

            $this->db->where(
                's.id_kelas',
                $kelas_id
            );
        }

        if (!empty($keyword)) {

            $this->db->group_start();

            $this->db->like(
                's.nama',
                $keyword
            );

            $this->db->or_like(
                's.nis',
                $keyword
            );

            $this->db->or_like(
                's.nisn',
                $keyword
            );

            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }


    /**
     * =========================================================
     * SISWA AKTIF + JUMLAH BERKAS
     * =========================================================
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

        $this->db->join(
            'kelas k',
            'k.id = s.id_kelas',
            'left'
        );

        $this->db->join(
            'siswa_berkas sb',
            'sb.siswa_id = s.id',
            'left'
        );

        $this->db->where(
            's.status',
            'aktif'
        );

        if (!empty($kelas_id)) {

            $this->db->where(
                's.id_kelas',
                $kelas_id
            );
        }

        if (!empty($keyword)) {

            $this->db->group_start();

            $this->db->like(
                's.nama',
                $keyword
            );

            $this->db->or_like(
                's.nis',
                $keyword
            );

            $this->db->or_like(
                's.nisn',
                $keyword
            );

            $this->db->group_end();
        }

        $this->db->group_by([
            's.id',
            's.nis',
            's.nisn',
            's.nama',
            's.id_kelas',
            'k.nama'
        ]);

        $this->db->order_by(
            's.nama',
            'ASC'
        );

        $this->db->limit(
            $limit,
            $offset
        );

        return $this->db
            ->get()
            ->result();
    }


    /**
     * =========================================================
     * KELAS AKTIF
     * =========================================================
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
            "s.id_kelas = k.id
             AND s.status = 'aktif'",
            'inner'
        );

        $this->db->group_by([
            'k.id',
            'k.nama'
        ]);

        $this->db->order_by(
            'k.nama',
            'ASC'
        );

        return $this->db
            ->get()
            ->result();
    }


    /**
     * =========================================================
     * SEMUA BERKAS SISWA
     * =========================================================
     */
    public function get_all_berkas($siswa_id)
    {
        $rows = $this->db
            ->where(
                'siswa_id',
                (int) $siswa_id
            )
            ->order_by(
                'id',
                'ASC'
            )
            ->get($this->table)
            ->result();

        $result = array();

        foreach ($rows as $row) {

            $result[
                $row->jenis_berkas
            ] = $row;
        }

        return $result;
    }


    /**
     * =========================================================
     * BERKAS BERDASARKAN ID + SISWA
     * =========================================================
     */
    public function get_berkas_by_id_siswa(
        $id,
        $siswa_id
    ) {
        return $this->db
            ->where(
                'id',
                (int) $id
            )
            ->where(
                'siswa_id',
                (int) $siswa_id
            )
            ->get($this->table)
            ->row();
    }


    /**
     * =========================================================
     * BERKAS BERDASARKAN ACCESS TOKEN
     * =========================================================
     *
     * Digunakan untuk membuka file
     * melalui URL dari Google Spreadsheet.
     */
    public function get_berkas_by_token(
        $access_token
    ) {
        return $this->db
            ->where(
                'access_token',
                trim($access_token)
            )
            ->get($this->table)
            ->row();
    }
}