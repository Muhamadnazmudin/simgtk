<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_mengajar_model extends CI_Model
{

    /* ==========================================================
     * BASE QUERY
     * ========================================================== */

    private function _baseQuery($guru_id = null, $kelas_id = null)
    {
        $mapHari = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];

        $hari = $mapHari[date('l')];

        $this->db
            ->from('jadwal_mengajar j')

            ->join('guru g', 'g.id = j.guru_id')

            ->join('kelas k', 'k.id = j.rombel_id')

            ->join('mapel m', 'm.id_mapel = j.mapel_id')

            ->join('jam_sekolah js1', 'js1.id_jam = j.jam_mulai_id')

            ->join('jam_sekolah js2', 'js2.id_jam = j.jam_selesai_id')

            ->join(
                'log_mengajar lm',
                'lm.jadwal_id=j.id_jadwal
                 AND DATE(lm.tanggal)=CURDATE()',
                'left'
            )

            ->where('j.hari', $hari);

        if (!empty($guru_id)) {
            $this->db->where('g.id', $guru_id);
        }

        if (!empty($kelas_id)) {
            $this->db->where('k.id', $kelas_id);
        }
    }

    /* ==========================================================
     * DATA MONITORING
     * ========================================================== */

    public function getMonitoring($guru_id = null, $kelas_id = null)
    {
        $this->_baseQuery($guru_id, $kelas_id);

        $this->db->select("
            j.id_jadwal,

            g.id guru_id,
            g.nama nama_guru,

            k.id kelas_id,
            k.nama nama_kelas,

            m.nama_mapel,

            js1.nama_jam jam_awal,
            js1.jam_mulai,

            js2.nama_jam jam_akhir,
            js2.jam_selesai,

            lm.jam_mulai log_mulai,
            lm.jam_selesai log_selesai,

            lm.status,
            lm.materi,
            lm.selfie
        ");

        $this->db
            ->order_by('js1.urutan')
            ->order_by('g.nama');

        return $this->db->get()->result();
    }

    /* ==========================================================
     * FILTER GURU
     * ========================================================== */

    public function getGuru()
    {
        return $this->db
            ->select('id,nama')
            ->order_by('nama')
            ->get('guru')
            ->result();
    }

    /* ==========================================================
     * FILTER KELAS
     * ========================================================== */

    public function getKelas()
    {
        return $this->db
            ->select('id,nama')
            ->order_by('nama')
            ->get('kelas')
            ->result();
    }

}