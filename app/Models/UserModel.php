<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'ms_user';     // nama tabel
    protected $primaryKey = 'phone_number';
    protected $allowedFields = ['username', 'password', 'phone_number', 'signed_up_at', 'otp', 'created_otp', 'expired_otp', 'token', 'token_expiry', 'pin', 'updated_pin'];
    protected $useTimestamps = false; // Jangan otomatis, kita set manual
}
