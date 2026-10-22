<?php

namespace App\Models;

use CodeIgniter\Model;

class MutasiModel extends Model
{
    protected $table            = 'mutasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = false; // allow flex fields
    protected $useTimestamps    = false;

    public function countByYear(?int $tahunId = null): int
    {
        $builder = $this->db->table($this->table)
            ->where('status_mutasi', 'aktif');
        if ($tahunId) {
            $builder->where('tahun_id', $tahunId);
        }
        return $builder->countAllResults();
    }

    public function getAll(int $limit, int $offset, ?int $tahunId = null): array
    {
        $builder = $this->db->table($this->table)
            ->select('
                mutasi.*,
                siswa.nama AS nama_siswa,
                siswa.nis,
                ktujuan.nama AS tujuan_kelas_nama,
                tahun_ajaran.tahun AS tahun_ajaran
            ')
            ->join('siswa', 'siswa.id = mutasi.siswa_id', 'left')
            ->join('kelas ktujuan', 'ktujuan.id = mutasi.tujuan_kelas_id', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = mutasi.tahun_id', 'left')
            ->where('mutasi.status_mutasi', 'aktif');

        if ($tahunId) {
            $builder->where('mutasi.tahun_id', $tahunId);
        }

        return $builder->orderBy('mutasi.id', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResult();
    }

    public function getSiswaAktif(): array
    {
        return $this->db->table('siswa')
            ->where('status', 'aktif')
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResult();
    }

    public function getKelasList(): array
    {
        return $this->db->table('kelas')
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResult();
    }

    public function getTahunList(): array
    {
        return $this->db->table('tahun_ajaran')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResult();
    }

    public function resolveKelasAsal(int $siswaId, ?int $tahunId = null, ?int $providedKelasAsal = null): int
    {
        if (!empty($providedKelasAsal)) {
            return (int) $providedKelasAsal;
        }

        if ($tahunId) {
            $st = $this->db->table('siswa_tahun')
                ->where('siswa_id', $siswaId)
                ->where('tahun_id', $tahunId)
                ->where('status', 'aktif')
                ->where('kelas_id >', 0)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get()
                ->getRow();

            if ($st && !empty($st->kelas_id)) {
                return (int) $st->kelas_id;
            }
        }

        $st2 = $this->db->table('siswa_tahun')
            ->where('siswa_id', $siswaId)
            ->orderBy('tahun_id', 'DESC')
            ->get()
            ->getRow();

        if ($st2 && !empty($st2->kelas_id)) {
            return (int) $st2->kelas_id;
        }

        $siswa = $this->db->table('siswa')
            ->select('id_kelas')
            ->where('id', $siswaId)
            ->get()
            ->getRow();

        if ($siswa && !empty($siswa->id_kelas)) {
            return (int) $siswa->id_kelas;
        }

        return 0;
    }

    public function mutasiKeluar(array $data): bool
    {
        $this->db->transBegin();

        $kelasAsalId = $this->resolveKelasAsal(
            (int) $data['siswa_id'],
            isset($data['tahun_id']) ? (int) $data['tahun_id'] : null,
            isset($data['kelas_asal_id']) ? (int) $data['kelas_asal_id'] : null
        );

        $data['kelas_asal_id'] = $kelasAsalId;
        $this->db->table('mutasi')->insert($data);

        $statusKeluar = 'mutasi_keluar';
        if (isset($data['jenis_keluar'])) {
            if ($data['jenis_keluar'] === 'meninggal') {
                $statusKeluar = 'meninggal';
            } elseif ($data['jenis_keluar'] === 'mengundurkan diri') {
                $statusKeluar = 'keluar';
            }
        }

        $this->db->table('siswa')->where('id', $data['siswa_id'])->update(['status' => $statusKeluar]);

        $st = $this->db->table('siswa_tahun')
            ->where('siswa_id', $data['siswa_id'])
            ->where('tahun_id', $data['tahun_id'])
            ->get()
            ->getRow();

        if ($st) {
            $this->db->table('siswa_tahun')->where('id', $st->id)->update(['status' => 'mutasi_keluar']);
        } else {
            $this->db->table('siswa_tahun')->insert([
                'siswa_id' => $data['siswa_id'],
                'kelas_id' => $kelasAsalId,
                'tahun_id' => $data['tahun_id'],
                'status'   => 'mutasi_keluar',
            ]);
        }

        $this->db->table('siswa_history')->insert([
            'siswa_id' => $data['siswa_id'],
            'kelas_id' => $kelasAsalId,
            'tahun_id' => $data['tahun_id'],
            'status'   => 'mutasi_keluar',
        ]);

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return false;
        }

        $this->db->transCommit();
        return true;
    }

    public function mutasiMasuk(array $data): bool
    {
        $this->db->transBegin();

        $this->db->table('mutasi')->insert($data);

        $this->db->table('siswa')->where('id', $data['siswa_id'])->update([
            'id_kelas' => $data['tujuan_kelas_id'],
            'status'   => 'aktif',
        ]);

        $st = $this->db->table('siswa_tahun')
            ->where('siswa_id', $data['siswa_id'])
            ->where('tahun_id', $data['tahun_id'])
            ->get()
            ->getRow();

        if ($st) {
            $this->db->table('siswa_tahun')->where('id', $st->id)->update([
                'kelas_id' => $data['tujuan_kelas_id'],
                'status'   => 'aktif',
            ]);
        } else {
            $this->db->table('siswa_tahun')->insert([
                'siswa_id' => $data['siswa_id'],
                'kelas_id' => $data['tujuan_kelas_id'],
                'tahun_id' => $data['tahun_id'],
                'status'   => 'aktif',
            ]);
        }

        $this->db->table('siswa_history')->insert([
            'siswa_id' => $data['siswa_id'],
            'kelas_id' => $data['tujuan_kelas_id'],
            'tahun_id' => $data['tahun_id'],
            'status'   => 'mutasi_masuk',
        ]);

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return false;
        }

        $this->db->transCommit();
        return true;
    }

    public function batalkan(int $id): bool
    {
        $mutasi = $this->db->table('mutasi')->where('id', $id)->get()->getRow();
        if (!$mutasi) {
            return false;
        }

        $this->db->transBegin();

        $this->db->table('mutasi')->where('id', $id)->update(['status_mutasi' => 'dibatalkan']);

        if ($mutasi->jenis === 'keluar') {
            $update = ['status' => 'aktif'];
            if (!empty($mutasi->kelas_asal_id)) {
                $update['id_kelas'] = $mutasi->kelas_asal_id;
            }
            $this->db->table('siswa')->where('id', $mutasi->siswa_id)->update($update);
            $this->db->table('siswa_tahun')
                ->where('siswa_id', $mutasi->siswa_id)
                ->where('tahun_id', $mutasi->tahun_id)
                ->update(['status' => 'aktif']);
        } elseif ($mutasi->jenis === 'masuk') {
            $this->db->table('siswa_tahun')
                ->where('siswa_id', $mutasi->siswa_id)
                ->where('tahun_id', $mutasi->tahun_id)
                ->where('kelas_id', $mutasi->tujuan_kelas_id)
                ->delete();

            $this->db->table('siswa')->where('id', $mutasi->siswa_id)->update(['status' => 'aktif']);
        }

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return false;
        }

        $this->db->transCommit();
        return true;
    }

    public function getPublic(array $filter = []): array
    {
        $builder = $this->db->table('mutasi')
            ->select('
                mutasi.*,
                siswa.nama AS nama_siswa,
                siswa.nis,
                siswa.nisn,
                kelas_asal.nama AS kelas_asal,
                kelas_tujuan.nama AS kelas_tujuan,
                tahun_ajaran.tahun AS tahun_ajaran,
                users.nama AS dibuat_oleh
            ')
            ->join('siswa', 'siswa.id = mutasi.siswa_id', 'left')
            ->join('kelas kelas_asal', 'kelas_asal.id = mutasi.kelas_asal_id', 'left')
            ->join('kelas kelas_tujuan', 'kelas_tujuan.id = mutasi.tujuan_kelas_id', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = mutasi.tahun_id', 'left')
            ->join('users', 'users.id = mutasi.created_by', 'left')
            ->where('mutasi.status_mutasi', 'aktif');

        if (!empty($filter['tahun_id'])) {
            $builder->where('mutasi.tahun_id', $filter['tahun_id']);
        }
        if (!empty($filter['kelas'])) {
            $builder->where('mutasi.kelas_asal_id', $filter['kelas']);
        }
        if (!empty($filter['jenis'])) {
            $builder->where('mutasi.jenis', $filter['jenis']);
        }
        if (!empty($filter['search'])) {
            $builder->like('siswa.nama', $filter['search']);
        }

        return $builder->orderBy('mutasi.id', 'DESC')->get()->getResult();
    }
}
