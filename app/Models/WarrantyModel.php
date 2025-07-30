<?php

namespace App\Models;

use CodeIgniter\Model;

class WarrantyModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'warranty';     // nama tabel
    protected $primaryKey = 'id';
    protected $allowedFields = ['nopolisi', 'product_id', 'product_name', 'product_image', 'warranty_duration', 'end_warranty_date', 'image_url'];
    protected $useTimestamps = false; // Jangan otomatis, kita set manual
}
