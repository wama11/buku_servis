<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'mst_member';     // nama tabel
    protected $primaryKey = 'id';
    protected $allowedFields = ['nopolisi', 'namamember', 'notelp', 'typemotor', 'odometer', 'flagregist', 'idjenismembership', 'checkstnk', 'flagkonfirmasi', 'tahunmotor', 'foto', 'create_date', 'update_date', 'totalpoint', 'exp_date_point'];

    protected $useTimestamps = false; // Jangan otomatis, kita set manual
}
