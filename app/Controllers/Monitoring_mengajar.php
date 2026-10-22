<?php

namespace App\Controllers;

use App\Models\MonitoringMengajarModel;
use CodeIgniter\HTTP\ResponseInterface;

class Monitoring_mengajar extends BaseController
{
    protected MonitoringMengajarModel $model;

    public function __construct()
    {
        $this->model = new MonitoringMengajarModel();
    }

    public function index()
    {
        $this->requireRole([1]);

        $data = [
            'title'  => 'Monitoring Mengajar Guru',
            'active' => 'monitoring_mengajar',
        ];

        return $this->renderView('monitoring_mengajar/index', $data);
    }

    public function load_data(): ResponseInterface
    {
        $this->requireRole([1]);

        $guruId  = $this->request->getGet('guru_id');
        $kelasId = $this->request->getGet('kelas_id');

        $rows = $this->model->getMonitoring($guruId ?: null, $kelasId ?: null);
        $result = [];

        foreach ($rows as $r) {
            if ($r->log_mulai && !$r->log_selesai) {
                $status = 'sedang';
            } elseif ($r->log_selesai) {
                $status = 'selesai';
            } else {
                $status = 'belum';
            }

            $result[] = [
                'guru'        => esc($r->nama_guru),
                'kelas'       => esc($r->nama_kelas),
                'mapel'       => esc($r->nama_mapel),
                'jam_awal'    => esc($r->jam_awal),
                'jam_akhir'   => esc($r->jam_akhir),
                'jam_mulai'   => $r->jam_mulai ? substr($r->jam_mulai, 0, 5) : '-',
                'jam_selesai' => $r->jam_selesai ? substr($r->jam_selesai, 0, 5) : '-',
                'materi'      => esc($r->materi ?: '-'),
                'selfie'      => $r->selfie ? base_url('uploads/selfie/' . $r->selfie) : '',
                'status'      => $status,
            ];
        }

        return $this->responseJson($result);
    }

    public function filter_data(): ResponseInterface
    {
        $this->requireRole([1]);

        return $this->responseJson([
            'guru'  => $this->model->getGuru(),
            'kelas' => $this->model->getKelas(),
        ]);
    }
}
