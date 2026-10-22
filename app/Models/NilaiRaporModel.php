<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiRaporModel extends Model
{
    protected $table            = 'nilai_rapor';
    protected $primaryKey       = 'id_nilai';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = false;
    protected $useTimestamps    = false;

    public function insertOrUpdate(array $data): bool
    {
        $existing = $this->db->table($this->table)
            ->where('siswa_id', $data['siswa_id'])
            ->where('mapel_id', $data['mapel_id'])
            ->where('semester', $data['semester'])
            ->where('tahun_id', $data['tahun_id'])
            ->get()
            ->getRow();

        if ($existing) {
            return $this->db->table($this->table)
                ->where('id_nilai', $existing->id_nilai)
                ->update([
                    'nilai_angka' => $data['nilai_angka'],
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]);
        }

        return $this->db->table($this->table)->insert($data);
    }

    public function rekapBySiswaKelas(int $siswaId): array
    {
        $siswa = $this->db->table('siswa')->select('id_kelas')->where('id', $siswaId)->get()->getRow();
        $kelasId = $siswa ? $siswa->id_kelas : 0;

        return $this->db->table('nilai_rapor nr')
            ->select('
                m.nama_mapel,
                MAX(CASE WHEN nr.semester = 1 AND nr.siswa_id = ' . (int) $siswaId . ' THEN nr.nilai_angka END) AS smt1,
                MAX(CASE WHEN nr.semester = 2 AND nr.siswa_id = ' . (int) $siswaId . ' THEN nr.nilai_angka END) AS smt2,
                MAX(CASE WHEN nr.semester = 3 AND nr.siswa_id = ' . (int) $siswaId . ' THEN nr.nilai_angka END) AS smt3,
                MAX(CASE WHEN nr.semester = 4 AND nr.siswa_id = ' . (int) $siswaId . ' THEN nr.nilai_angka END) AS smt4,
                MAX(CASE WHEN nr.semester = 5 AND nr.siswa_id = ' . (int) $siswaId . ' THEN nr.nilai_angka END) AS smt5
            ')
            ->join('mapel m', 'm.id_mapel = nr.mapel_id')
            ->join('siswa s', 's.id = nr.siswa_id')
            ->where('s.id_kelas', $kelasId)
            ->groupBy('m.id_mapel')
            ->orderBy('m.nama_mapel', 'ASC')
            ->get()
            ->getResult();
    }

    public function rekapSiswa(?int $kelasId, int $tahunId, int $limit = 100, int $offset = 0): array
    {
        $builder = $this->db->table('siswa s')
            ->select('s.id, s.nisn, s.nama, k.nama AS nama_kelas')
            ->join('siswa_tahun st', 'st.siswa_id = s.id AND st.tahun_id = ' . (int) $tahunId . ' AND st.status = "aktif"')
            ->join('kelas k', 'k.id = st.kelas_id', 'left');

        if (!empty($kelasId)) {
            $builder->where('st.kelas_id', $kelasId);
        }

        $siswa = $builder->orderBy('k.nama', 'ASC')->orderBy('s.nama', 'ASC')->limit($limit, $offset)->get()->getResult();
        if (empty($siswa)) {
            return [];
        }

        $ids = array_column($siswa, 'id');
        $nilai = $this->db->table('nilai_rapor')
            ->select('siswa_id, mapel_id, semester, nilai_angka')
            ->where('tahun_id', $tahunId)
            ->whereIn('siswa_id', $ids)
            ->get()
            ->getResult();

        $pivot = [];
        foreach ($nilai as $n) {
            $pivot[$n->siswa_id][$n->mapel_id][$n->semester] = $n->nilai_angka;
        }

        $hasil = [];
        foreach ($siswa as $s) {
            $hasil[] = [
                'id'    => $s->id,
                'nisn'  => $s->nisn,
                'nama'  => $s->nama,
                'kelas' => $s->nama_kelas,
                'nilai' => $pivot[$s->id] ?? [],
            ];
        }

        return $hasil;
    }

    public function countRekapSiswa(?int $kelasId, int $tahunId): int
    {
        $builder = $this->db->table('siswa_tahun')
            ->where('tahun_id', $tahunId)
            ->where('status', 'aktif');

        if (!empty($kelasId)) {
            $builder->where('kelas_id', $kelasId);
        }

        return $builder->countAllResults();
    }

    public function getMapelRekap(?int $kelasId, int $tahunId): array
    {
        $builder = $this->db->table('nilai_rapor nr')
            ->distinct()
            ->select('m.id_mapel, m.nama_mapel')
            ->join('mapel m', 'm.id_mapel = nr.mapel_id')
            ->join('siswa_tahun st', 'st.siswa_id = nr.siswa_id AND st.tahun_id = nr.tahun_id')
            ->where('nr.tahun_id', $tahunId);

        if (!empty($kelasId)) {
            $builder->where('st.kelas_id', $kelasId);
        }

        return $builder->orderBy('m.nama_mapel', 'ASC')->get()->getResult();
    }
}
