<?php

namespace App\Models;

use CodeIgniter\Model;

class RoboticModel extends Model
{

    protected $DBGroup = 'robotic';
    protected $table = 'mstmember';     // nama tabel
    protected $primaryKey = 'notelp';
    protected $allowedFields = ['nopolisi', 'namamember', 'notelp'];
    protected $useTimestamps = false; // Jangan otomatis, kita set manual
}
