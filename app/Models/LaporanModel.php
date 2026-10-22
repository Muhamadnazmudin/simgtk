<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    public function getLaporan(?int $tahunId, $kelas = null, $jenis = null, $search = null): array
    {
        $tahunAjaran = null;
        if ($tahunId) {
            $row = $this->db->table('tahun_ajaran')->select('tahun')->where('id', $tahunId)->get()->getRow();
            if ($row) {
                $tahunAjaran = $row->tahun;
            }
        }

        $builder = $this->db->table('v_mutasi_detail');

        if ($tahunAjaran) {
            $builder->where('tahun_ajaran', $tahunAjaran);
        }

        $builder->where('status_mutasi', 'aktif');

        if (!empty($kelas)) {
            $builder->where('kelas_asal_id', (int) $kelas);
        }

        if (!empty($jenis)) {
            $builder->where('jenis', strtolower($jenis));
        }

        if (!empty($search)) {
            $builder->groupStart()
                ->like('nama_siswa', $search)
                ->orLike('nis', $search)
                ->orLike('nisn', $search)
                ->groupEnd();
        }

        return $builder->orderBy('tanggal', 'DESC')->get()->getResult();
    }

    public function getKelas(): array
    {
        return $this->db->table('kelas')->orderBy('nama', 'ASC')->get()->getResult();
    }
}
