<?php

namespace App\Models;

use CodeIgniter\Model;

class TutorialModel extends Model
{
    protected $table = 'tutorial';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'judul',
        'tipe',
        'video_url',
        'video_file',
        'thumbnail',
    ];
    protected $returnType = 'object';

    public function getAll(): array
    {
        return $this->orderBy('id', 'DESC')->findAll();
    }
}
