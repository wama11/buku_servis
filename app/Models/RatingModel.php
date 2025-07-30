<?php

namespace App\Models;

use CodeIgniter\Model;

class RatingModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'ratings';     // nama tabel
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'rate', 'rate_note', 'feedback', 'created_at', 'updated_at', 'note_rate', 'store_id', 'transaction_id'];
    protected $useTimestamps = false; // Jangan otomatis, kita set manual
}
