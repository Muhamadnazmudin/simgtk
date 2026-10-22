<?php

namespace App\Controllers;

use App\Models\TutorialModel;

class Tutorial extends BaseController
{
    protected TutorialModel $tutorialModel;

    public function __construct()
    {
        $this->tutorialModel = new TutorialModel();
    }

    public function index()
    {
        $this->requireRole([1]);

        $data = [
            'active'   => 'tutorial',
            'title'    => 'Daftar Tutorial',
            'tutorial' => $this->tutorialModel->getAll(),
        ];

        return $this->renderView('tutorial/index', $data);
    }

    public function add()
    {
        $this->requireRole([1]);

        $data = [
            'active' => 'tutorial_add',
            'title'  => 'Tambah Tutorial',
        ];

        return $this->renderView('tutorial/form', $data);
    }

    public function store()
    {
        $this->requireRole([1]);

        $tipe      = $this->request->getPost('tipe');
        $videoUrl  = null;
        $videoFile = null;

        if ($tipe === 'youtube') {
            $videoUrl = $this->request->getPost('video_url');
        } else {
            $vFile = $this->request->getFile('video_file');
            if ($vFile && $vFile->isValid() && !$vFile->hasMoved()) {
                $videoPath = FCPATH . 'uploads/tutorial/video/';
                if (!is_dir($videoPath)) {
                    mkdir($videoPath, 0755, true);
                }
                $videoFile = $vFile->getRandomName();
                $vFile->move($videoPath, $videoFile);
            }
        }

        $thumb = null;
        $tFile = $this->request->getFile('thumbnail');
        if ($tFile && $tFile->isValid() && !$tFile->hasMoved()) {
            $thumbPath = FCPATH . 'uploads/tutorial/thumbnail/';
            if (!is_dir($thumbPath)) {
                mkdir($thumbPath, 0755, true);
            }
            $thumb = $tFile->getRandomName();
            $tFile->move($thumbPath, $thumb);
        }

        $this->tutorialModel->insert([
            'judul'      => $this->request->getPost('judul'),
            'tipe'       => $tipe,
            'video_url'  => $videoUrl,
            'video_file' => $videoFile,
            'thumbnail'  => $thumb,
        ]);

        $this->session->setFlashdata('success', 'Tutorial berhasil ditambahkan');
        return redirect()->to(site_url('tutorial'));
    }

    public function edit($id = null)
    {
        $this->requireRole([1]);

        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $row = $this->tutorialModel->find((int) $id);
        if (!$row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'active' => 'tutorial_edit',
            'title'  => 'Edit Tutorial',
            'row'    => $row,
        ];

        return $this->renderView('tutorial/form', $data);
    }

    public function update($id = null)
    {
        $this->requireRole([1]);

        if (!$id) {
            return redirect()->to(site_url('tutorial'));
        }

        $row  = $this->tutorialModel->find((int) $id);
        if (!$row) {
            return redirect()->to(site_url('tutorial'));
        }

        $tipe      = $this->request->getPost('tipe');
        $videoUrl  = $row->video_url;
        $videoFile = $row->video_file;

        if ($tipe === 'youtube') {
            $videoUrl = $this->request->getPost('video_url');
            if ($videoFile) {
                @unlink(FCPATH . 'uploads/tutorial/video/' . $videoFile);
                $videoFile = null;
            }
        } else {
            $vFile = $this->request->getFile('video_file');
            if ($vFile && $vFile->isValid() && !$vFile->hasMoved()) {
                if ($videoFile) {
                    @unlink(FCPATH . 'uploads/tutorial/video/' . $videoFile);
                }
                $videoPath = FCPATH . 'uploads/tutorial/video/';
                if (!is_dir($videoPath)) {
                    mkdir($videoPath, 0755, true);
                }
                $videoFile = $vFile->getRandomName();
                $vFile->move($videoPath, $videoFile);
                $videoUrl  = null;
            }
        }

        $thumbnail = $row->thumbnail;
        $tFile = $this->request->getFile('thumbnail');
        if ($tFile && $tFile->isValid() && !$tFile->hasMoved()) {
            if ($thumbnail) {
                @unlink(FCPATH . 'uploads/tutorial/thumbnail/' . $thumbnail);
            }
            $thumbPath = FCPATH . 'uploads/tutorial/thumbnail/';
            if (!is_dir($thumbPath)) {
                mkdir($thumbPath, 0755, true);
            }
            $thumbnail = $tFile->getRandomName();
            $tFile->move($thumbPath, $thumbnail);
        }

        $this->tutorialModel->update((int) $id, [
            'judul'      => $this->request->getPost('judul'),
            'tipe'       => $tipe,
            'video_url'  => $videoUrl,
            'video_file' => $videoFile,
            'thumbnail'  => $thumbnail,
        ]);

        $this->session->setFlashdata('success', 'Tutorial berhasil diperbarui');
        return redirect()->to(site_url('tutorial'));
    }

    public function delete($id = null)
    {
        $this->requireRole([1]);

        if ($id) {
            $row = $this->tutorialModel->find((int) $id);
            if ($row) {
                if ($row->video_file) {
                    @unlink(FCPATH . 'uploads/tutorial/video/' . $row->video_file);
                }
                if ($row->thumbnail) {
                    @unlink(FCPATH . 'uploads/tutorial/thumbnail/' . $row->thumbnail);
                }
                $this->tutorialModel->delete((int) $id);
                $this->session->setFlashdata('success', 'Tutorial berhasil dihapus');
            }
        }

        return redirect()->to(site_url('tutorial'));
    }
}
