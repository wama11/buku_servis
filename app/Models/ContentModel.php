<?php

namespace App\Models;

use CodeIgniter\Model;

class ContentModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'rate_note';     // nama tabel
    protected $primaryKey = 'rate_id';
    protected $allowedFields = ['rate_note', 'rate_note_text', 'updated_at'];
    protected $useTimestamps = false; // Jangan otomatis, kita set manual


    public function getTnC()
    {
        $builder = $this->db->query("SELECT tnc_text FROM point_tnc");
        return $builder->getResultArray(); // Hanya ambil satu baris
    }
}
