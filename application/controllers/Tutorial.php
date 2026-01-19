<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tutorial extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tutorial_model');
        $this->load->library(['upload','session']);
    }

    public function index()
    {
        $data['active'] = 'tutorial';
        $data['title']  = 'Daftar Tutorial';
        $data['tutorial'] = $this->Tutorial_model->get_all();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('tutorial/index', $data);
        $this->load->view('templates/footer');
    }

    public function add()
    {
        $data['active'] = 'tutorial_add';
        $data['title']  = 'Tambah Tutorial';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('tutorial/form', $data);
        $this->load->view('templates/footer');
    }

    public function store()
    {
        $tipe = $this->input->post('tipe');

        $video_url  = null;
        $video_file = null;

        if ($tipe == 'youtube') {
            $video_url = $this->input->post('video_url');
        } else {
            $config['upload_path']   = './uploads/tutorial/video/';
            $config['allowed_types'] = 'mp4|webm|mkv';
            $config['encrypt_name']  = true;
            $this->upload->initialize($config);

            if ($this->upload->do_upload('video_file')) {
                $video_file = $this->upload->data('file_name');
            }
        }

        // upload thumbnail
        $thumb = null;
        if (!empty($_FILES['thumbnail']['name'])) {
            $config['upload_path']   = './uploads/tutorial/thumbnail/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['encrypt_name']  = true;
            $this->upload->initialize($config);

            if ($this->upload->do_upload('thumbnail')) {
                $thumb = $this->upload->data('file_name');
            }
        }

        $data = [
            'judul'      => $this->input->post('judul'),
            'tipe'       => $tipe,
            'video_url'  => $video_url,
            'video_file' => $video_file,
            'thumbnail'  => $thumb
        ];

        $this->Tutorial_model->insert($data);
        redirect('tutorial');
    }

    public function delete($id)
    {
        $row = $this->Tutorial_model->get_by_id($id);

        if ($row) {
            if ($row->video_file)
                @unlink('./uploads/tutorial/video/'.$row->video_file);
            if ($row->thumbnail)
                @unlink('./uploads/tutorial/thumbnail/'.$row->thumbnail);

            $this->Tutorial_model->delete($id);
        }

        redirect('tutorial');
    }
    public function edit($id)
{
    $data['active'] = 'tutorial_edit';
    $data['title']  = 'Edit Tutorial';
    $data['row']    = $this->Tutorial_model->get_by_id($id);

    if (!$data['row']) show_404();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('tutorial/form', $data);
        $this->load->view('templates/footer');
}
public function update($id)
{
    $row  = $this->Tutorial_model->get_by_id($id);
    $tipe = $this->input->post('tipe');

    $video_url  = $row->video_url;
    $video_file = $row->video_file;

    if ($tipe == 'youtube') {
        $video_url  = $this->input->post('video_url');
        if ($video_file) {
            @unlink('./uploads/tutorial/video/'.$video_file);
            $video_file = null;
        }
    } else {
        if (!empty($_FILES['video_file']['name'])) {
            @unlink('./uploads/tutorial/video/'.$video_file);

            $config['upload_path']   = './uploads/tutorial/video/';
            $config['allowed_types'] = 'mp4|webm|mkv';
            $config['encrypt_name']  = true;
            $this->upload->initialize($config);

            if ($this->upload->do_upload('video_file')) {
                $video_file = $this->upload->data('file_name');
                $video_url  = null;
            }
        }
    }

    // thumbnail
    $thumbnail = $row->thumbnail;
    if (!empty($_FILES['thumbnail']['name'])) {
        @unlink('./uploads/tutorial/thumbnail/'.$thumbnail);

        $config['upload_path']   = './uploads/tutorial/thumbnail/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['encrypt_name']  = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('thumbnail')) {
            $thumbnail = $this->upload->data('file_name');
        }
    }

    $data = [
        'judul'      => $this->input->post('judul'),
        'tipe'       => $tipe,
        'video_url'  => $video_url,
        'video_file' => $video_file,
        'thumbnail'  => $thumbnail
    ];

    $this->Tutorial_model->update($id, $data);
    redirect('tutorial');
}

}
