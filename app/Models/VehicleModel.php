<?php

namespace App\Models;

use CodeIgniter\Model;

class VehicleModel extends Model
{

    protected $DBGroup = 'ho';
    protected $table = 'mst_member';     // nama tabel
    protected $primaryKey = 'id';
    protected $allowedFields = ['nopolisi', 'namamember', 'notelp'];

    protected $useTimestamps = false; // Jangan otomatis, kita set manual

    public function getVehicle($phone_number)
    {
        // Misalkan ada function yang mengembalikan data tabular
        $query = $this->db->query("SELECT * FROM dbo.sp_get_member_vehicle(?)", [$phone_number]);
        return $query->getResultArray(); // Kembalikan semua baris sebagai array
    }

    public function getMemberVehicle($phone_number)
    {
        // Query untuk memanggil stored procedure
        $query = $this->db->query("EXEC dbo.sp_get_member_vehicle ?", [$phone_number]);
        return $query->getResultArray(); // Kembalikan hasil sebagai array
    }

    public function getMemberRekomendasi($nopol)
    {
        // Query untuk memanggil stored procedure
        $query = $this->db->query("EXEC dbo.sp_get_rekomendasi_servis ?", [$nopol]);
        return $query->getResultArray(); // Kembalikan hasil sebagai array
    }
    public function getCabang($store_id)
    {
        $builder = $this->db->query("SELECT iddc FROM MasterToolsToko WHERE kodetoko = '$store_id'");
        return $builder->getRowArray(); // Hanya ambil satu baris
    }


}
