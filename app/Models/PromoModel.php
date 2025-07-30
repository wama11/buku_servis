<?php

namespace App\Models;

use CodeIgniter\Model;

class PromoModel extends Model
{
    protected $table = 'promo';
    protected $primaryKey = 'id';

    protected $allowedFields = ['title', 'image_url', 'target_url', 'created_at'];
    protected $useTimestamps = false;

}

