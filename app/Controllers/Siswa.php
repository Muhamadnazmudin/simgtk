<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

/**
 * Controller Siswa - CodeIgniter 4
 */
class Siswa extends BaseController
{
    protected SiswaModel $siswaModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->siswaModel = new SiswaModel();

        // Check login except public methods like cetak
        $method = $this->request->getUri()->getSegment(2);
        if (!in_array($method, ['cetak', 'surat_pernyataan'], true)) {
            if (!$this->session->get('logged_in')) {
                header('Location: ' . base_url('auth/login'));
                exit;
            }
        }
    }

    public function index($offset = 0)
    {
        $pager = service('pager');

        $kelasId = $this->request->getGet('kelas');
        $search  = trim((string) $this->request->getGet('search'));
        $limit   = (int) ($this->request->getGet('limit') ?: 10);

        $builder = $this->db->table('siswa')
            ->select('siswa.*, kelas.nama AS nama_kelas, tahun_ajaran.tahun AS tahun_ajaran')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.tahun_id', 'left')
            ->where('siswa.status', 'aktif');

        if (!empty($kelasId)) {
            $builder->where('siswa.id_kelas', (int) $kelasId);
        }

        if (!empty($search)) {
            $builder->groupStart()
                ->like('siswa.nama', $search)
                ->orLike('siswa.nis', $search)
                ->orLike('siswa.nisn', $search)
                ->groupEnd();
        }

        // Count total
        $totalBuilder = clone $builder;
        $total = $totalBuilder->countAllResults(false);

        $builder->orderBy('siswa.id', 'ASC');
        $builder->limit($limit, (int) $offset);
        $siswaList = $builder->get()->getResult();

        $data = [
            'title'      => 'Data Siswa Aktif',
            'active'     => 'siswa',
            'siswa'      => $siswaList,
            'pagination' => $pager->makeLinks((int) $offset / $limit + 1, $limit, $total),
            'kelas'      => $this->siswaModel->get_kelas_list(),
            'tahun'      => $this->siswaModel->get_tahun_list(),
            'start'      => (int) $offset,
            'kelas_id'   => $kelasId,
            'search'     => $search,
            'limit'      => $limit,
        ];

        return $this->renderView('siswa/index', $data);
    }

    public function add()
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/siswa');
        }

        $nisn = trim((string) $this->request->getPost('nisn'));

        // Cek duplikat NISN
        $cek = $this->db->table('siswa')->where('nisn', $nisn)->get()->getRow();
        if ($cek) {
            return redirect()->to('/siswa')->with('error', 'NISN <b>' . esc($nisn) . '</b> sudah terdaftar atas nama <b>' . esc($cek->nama) . '</b>');
        }

        $data = [
            'nis'          => trim((string) $this->request->getPost('nis')),
            'nisn'         => $nisn,
            'nama'         => trim((string) $this->request->getPost('nama')),
            'jk'           => $this->request->getPost('jk'),
            'agama'        => $this->request->getPost('agama'),
            'tempat_lahir' => trim((string) $this->request->getPost('tempat_lahir')),
            'tgl_lahir'    => $this->request->getPost('tgl_lahir'),
            'alamat'       => trim((string) $this->request->getPost('alamat')),
            'id_kelas'     => (int) $this->request->getPost('id_kelas'),
            'tahun_id'     => (int) $this->request->getPost('tahun_id'),
            'status'       => 'aktif',
            'token_qr'     => $nisn,
            'created_at'   => date('Y-m-d H:i:s'),
        ];

        $this->db->table('siswa')->insert($data);
        return redirect()->to('/siswa')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $siswa = $this->siswaModel->get_by_id((int) $id);
        if (!$siswa) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Siswa tidak ditemukan.');
        }

        if ($this->request->is('post')) {
            $data = [
                'nis'             => trim((string) $this->request->getPost('nis')),
                'nisn'            => trim((string) $this->request->getPost('nisn')),
                'nama'            => trim((string) $this->request->getPost('nama')),
                'jk'              => $this->request->getPost('jk'),
                'agama'           => $this->request->getPost('agama'),
                'tempat_lahir'    => trim((string) $this->request->getPost('tempat_lahir')),
                'tgl_lahir'       => $this->request->getPost('tgl_lahir'),
                'nomor_kk'        => trim((string) $this->request->getPost('nomor_kk')),
                'nik'             => trim((string) $this->request->getPost('nik')),
                'anak_keberapa'   => $this->request->getPost('anak_keberapa'),
                'alamat'          => trim((string) $this->request->getPost('alamat')),
                'rt'              => trim((string) $this->request->getPost('rt')),
                'rw'              => trim((string) $this->request->getPost('rw')),
                'dusun'           => trim((string) $this->request->getPost('dusun')),
                'kecamatan'       => trim((string) $this->request->getPost('kecamatan')),
                'kode_pos'        => trim((string) $this->request->getPost('kode_pos')),
                'telp'            => trim((string) $this->request->getPost('telp')),
                'hp'              => trim((string) $this->request->getPost('hp')),
                'email'           => trim((string) $this->request->getPost('email')),
                'desil'           => $this->request->getPost('desil'),
                'penerima_kps'    => $this->request->getPost('penerima_kps'),
                'no_kps'          => $this->request->getPost('no_kps'),
                'tinggi_badan'    => $this->request->getPost('tinggi_badan'),
                'berat_badan'     => $this->request->getPost('berat_badan'),
                'hobi'            => $this->request->getPost('hobi'),
                'cita_cita'       => $this->request->getPost('cita_cita'),
                'sekolah_asal'    => trim((string) $this->request->getPost('sekolah_asal')),
                'skhun'           => trim((string) $this->request->getPost('skhun')),
                'nama_ayah'       => trim((string) $this->request->getPost('nama_ayah')),
                'nik_ayah'        => trim((string) $this->request->getPost('nik_ayah')),
                'tahun_lahir_ayah'=> $this->request->getPost('tahun_lahir_ayah'),
                'pendidikan_ayah' => $this->request->getPost('pendidikan_ayah'),
                'pekerjaan_ayah'  => $this->request->getPost('pekerjaan_ayah'),
                'penghasilan_ayah'=> $this->request->getPost('penghasilan_ayah'),
                'nama_ibu'        => trim((string) $this->request->getPost('nama_ibu')),
                'nik_ibu'         => trim((string) $this->request->getPost('nik_ibu')),
                'tahun_lahir_ibu' => $this->request->getPost('tahun_lahir_ibu'),
                'pendidikan_ibu'  => $this->request->getPost('pendidikan_ibu'),
                'pekerjaan_ibu'   => $this->request->getPost('pekerjaan_ibu'),
                'penghasilan_ibu' => $this->request->getPost('penghasilan_ibu'),
                'nama_wali'       => trim((string) $this->request->getPost('nama_wali')),
                'nik_wali'        => trim((string) $this->request->getPost('nik_wali')),
                'tahun_lahir_wali'=> $this->request->getPost('tahun_lahir_wali'),
                'pendidikan_wali' => $this->request->getPost('pendidikan_wali'),
                'pekerjaan_wali'  => $this->request->getPost('pekerjaan_wali'),
                'penghasilan_wali'=> $this->request->getPost('penghasilan_wali'),
                'id_kelas'        => (int) $this->request->getPost('id_kelas'),
                'tahun_id'        => (int) $this->request->getPost('tahun_id'),
                'status'          => $this->request->getPost('status'),
                'no_hp_ortu'      => trim((string) $this->request->getPost('no_hp_ortu')),
            ];

            if ($this->request->getPost('punya_wali') === '0') {
                $data['nama_wali']          = null;
                $data['nik_wali']           = null;
                $data['tahun_lahir_wali']   = null;
                $data['pendidikan_wali']    = null;
                $data['pekerjaan_wali']     = null;
                $data['penghasilan_wali']   = null;
            }

            // Handle Photo upload
            $foto = $this->request->getFile('foto');
            if ($foto && $foto->isValid() && !$foto->hasMoved()) {
                $newName = 'foto_' . time() . '.' . $foto->getExtension();
                $foto->move(FCPATH . 'uploads/foto/', $newName);
                $data['foto'] = $newName;

                // Delete old photo
                if (!empty($siswa->foto) && file_exists(FCPATH . 'uploads/foto/' . $siswa->foto)) {
                    @unlink(FCPATH . 'uploads/foto/' . $siswa->foto);
                }
            }

            $this->db->table('siswa')->where('id', (int) $id)->update($data);
            return redirect()->to('/siswa')->with('success', 'Data siswa berhasil diperbarui.');
        }

        $data = [
            'siswa'  => $siswa,
            'kelas'  => $this->siswaModel->get_kelas_list(),
            'tahun'  => $this->siswaModel->get_tahun_list(),
            'title'  => 'Edit Siswa',
            'active' => 'siswa',
        ];

        return $this->renderView('siswa/edit', $data);
    }

    public function delete($id = null)
    {
        $this->db->table('siswa')->where('id', (int) $id)->delete();
        return redirect()->to('/siswa')->with('success', 'Data siswa berhasil dihapus.');
    }
}
