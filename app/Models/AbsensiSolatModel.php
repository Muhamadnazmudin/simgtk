<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiSolatModel extends Model
{
    protected $table            = 'absensi_solat_sesi';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'tahun_id',
        'tanggal',
        'jenis_solat',
        'jam_mulai',
        'jam_selesai',
        'status',
        'dibuat_oleh'
    ];
    protected $useTimestamps    = false;

    protected string $table_sesi   = 'absensi_solat_sesi';
    protected string $table_detail = 'absensi_solat_detail';
    protected string $table_siswa  = 'siswa';
    protected string $table_kelas  = 'kelas';

    public function get_sesi(?int $tahunId = null): array
    {
        $builder = $this->builder();
        if ($tahunId !== null) {
            $builder->where('tahun_id', $tahunId);
        }
        $builder->orderBy('tanggal', 'DESC')->orderBy('jam_mulai', 'DESC');
        return $builder->get()->getResult();
    }

    public function get_sesi_hari_ini(int $tahunId): array
    {
        return $this->where('tahun_id', $tahunId)
            ->where('tanggal', date('Y-m-d'))
            ->orderBy('jam_mulai', 'DESC')
            ->findAll();
    }

    public function get_sesi_by_id(int $id, ?int $tahunId = null): ?object
    {
        $builder = $this->where('id', $id);
        if ($tahunId !== null) {
            $builder->where('tahun_id', $tahunId);
        }
        return $builder->first();
    }

    public function buat_sesi(array $data)
    {
        $dataSesi = [
            'tahun_id'    => (int) $data['tahun_id'],
            'tanggal'     => $data['tanggal'],
            'jenis_solat' => $data['jenis_solat'] ?? 'dzuhur',
            'jam_mulai'   => $data['jam_mulai'],
            'jam_selesai' => $data['jam_selesai'],
            'status'      => $data['status'] ?? 'buka',
            'dibuat_oleh' => $data['dibuat_oleh'] ?? null,
        ];
        return $this->insert($dataSesi);
    }

    public function update_sesi(int $id, int $tahunId, array $data): bool
    {
        return $this->where('id', $id)
            ->where('tahun_id', $tahunId)
            ->set([
                'tanggal'     => $data['tanggal'],
                'jam_mulai'   => $data['jam_mulai'],
                'jam_selesai' => $data['jam_selesai']
            ])
            ->update();
    }

    public function update_status_sesi(int $id, string $status): bool
    {
        if (!in_array($status, ['buka', 'tutup'], true)) {
            return false;
        }
        return $this->update($id, ['status' => $status]);
    }

    public function get_siswa_by_qr(string $token): ?object
    {
        $token = trim($token);
        if ($token === '') {
            return null;
        }
        return $this->db->table($this->table_siswa)
            ->where('nisn', $token)
            ->limit(1)
            ->get()
            ->getRow();
    }

    public function get_siswa_by_rfid(string $uid): ?object
    {
        $uid = trim($uid);
        if ($uid === '') {
            return null;
        }
        return $this->db->table($this->table_siswa)
            ->where('rfid_uid', $uid)
            ->limit(1)
            ->get()
            ->getRow();
    }

    public function get_siswa_by_id(int $siswaId): ?object
    {
        return $this->db->table($this->table_siswa)
            ->where('id', $siswaId)
            ->limit(1)
            ->get()
            ->getRow();
    }

    public function sudah_absen(int $sesiId, int $siswaId): ?object
    {
        return $this->db->table($this->table_detail)
            ->where('sesi_id', $sesiId)
            ->where('siswa_id', $siswaId)
            ->limit(1)
            ->get()
            ->getRow();
    }

    public function simpan_absensi(array $data): bool
    {
        $sesiId  = (int) ($data['sesi_id'] ?? 0);
        $siswaId = (int) ($data['siswa_id'] ?? 0);

        if ($sesiId <= 0 || $siswaId <= 0) {
            return false;
        }

        $metode = $data['metode_absen'] ?? 'qrcode';
        if (!in_array($metode, ['qrcode', 'rfid'], true)) {
            return false;
        }

        $status = $data['status'] ?? 'hadir';
        if (!in_array($status, ['hadir', 'terlambat'], true)) {
            return false;
        }

        $sesi = $this->get_sesi_by_id($sesiId);
        if (!$sesi) {
            return false;
        }

        if ($this->sudah_absen($sesiId, $siswaId)) {
            return false;
        }

        $dataDetail = [
            'sesi_id'      => $sesiId,
            'siswa_id'     => $siswaId,
            'waktu_absen'  => $data['waktu_absen'] ?? date('Y-m-d H:i:s'),
            'metode_absen' => $metode,
            'status'       => $status,
        ];

        return $this->db->table($this->table_detail)->insert($dataDetail);
    }

    public function get_statistik(int $sesiId): array
    {
        $totalSiswa = $this->db->table($this->table_siswa)
            ->where('status', 'aktif')
            ->countAllResults();

        $hasil = $this->db->table($this->table_detail)
            ->select("
                COUNT(*) AS total_hadir,
                SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN status = 'terlambat' THEN 1 ELSE 0 END) AS terlambat
            ", false)
            ->where('sesi_id', $sesiId)
            ->get()
            ->getRow();

        $totalHadir = $hasil ? (int) $hasil->total_hadir : 0;
        $hadir      = $hasil ? (int) $hasil->hadir : 0;
        $terlambat  = $hasil ? (int) $hasil->terlambat : 0;

        return [
            'total_siswa' => $totalSiswa,
            'total_hadir' => $totalHadir,
            'hadir'       => $hadir,
            'terlambat'   => $terlambat,
            'belum_absen' => max(0, $totalSiswa - $totalHadir),
        ];
    }

    public function get_rekap(array $filter = []): array
    {
        $builder = $this->db->table($this->table_detail . ' d')
            ->select("
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
            ", false)
            ->join($this->table_sesi . ' a', 'a.id = d.sesi_id', 'inner')
            ->join($this->table_siswa . ' s', 's.id = d.siswa_id', 'inner')
            ->join($this->table_kelas . ' k', 'k.id = s.id_kelas', 'left');

        if (!empty($filter['tahun_id'])) {
            $builder->where('a.tahun_id', (int) $filter['tahun_id']);
        }
        if (!empty($filter['tanggal_mulai'])) {
            $builder->where('a.tanggal >=', $filter['tanggal_mulai']);
        }
        if (!empty($filter['tanggal_selesai'])) {
            $builder->where('a.tanggal <=', $filter['tanggal_selesai']);
        }
        if (!empty($filter['tanggal'])) {
            $builder->where('a.tanggal', $filter['tanggal']);
        }
        if (!empty($filter['kelas_id'])) {
            $builder->where('s.id_kelas', (int) $filter['kelas_id']);
        }
        if (!empty($filter['sesi_id'])) {
            $builder->where('d.sesi_id', (int) $filter['sesi_id']);
        }
        if (!empty($filter['keyword'])) {
            $keyword = trim($filter['keyword']);
            $builder->groupStart()
                ->like('s.nama', $keyword)
                ->orLike('s.nis', $keyword)
                ->orLike('s.nisn', $keyword)
                ->groupEnd();
        }

        $builder->orderBy('a.tanggal', 'DESC')->orderBy('d.waktu_absen', 'DESC');
        return $builder->get()->getResult();
    }

    public function get_kelas(?int $tahunId = null): array
    {
        $builder = $this->db->table($this->table_kelas)->select('id, nama');
        if ($tahunId !== null) {
            $builder->where('tahun_id', $tahunId);
        }
        return $builder->orderBy('nama', 'ASC')->get()->getResult();
    }

    public function get_sesi_tanggal(int $tahunId, string $tanggal): ?object
    {
        return $this->where('tahun_id', $tahunId)
            ->where('tanggal', $tanggal)
            ->where('jenis_solat', 'dzuhur')
            ->first();
    }
}
