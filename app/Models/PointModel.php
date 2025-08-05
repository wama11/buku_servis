<?php

namespace App\Models;

use CodeIgniter\Model;

class PointModel extends Model
{

    protected $DBGroup = 'point';
    protected $table = 'smi_tbl_point_customer';     // nama tabel
    protected $primaryKey = 'id';
    // protected $primaryKey = null;
    protected $allowedFields = ['nopolisi', 'tglbisnis', 'kodetoko', 'nomortransaksi', 'statusproses', 'point'];
    protected $useTimestamps = false; // Jangan otomatis, kita set manual

    // public function getLatestByNopolisi(string $nopol)
    // {
    //     return $this->where('nopolisi', $nopol)
    //         ->orderBy('tglinsert', 'DESC')
    //         ->first(); // LIMIT 1 equivalent
    // }

    // public function getLatestPointByNopolisi(string $nopol)
    // {
    //     return $this->select('totalpoint')
    //         ->where('nopolisi', $nopol)
    //         ->orderBy('tglinsert', 'DESC')
    //         ->first(); // setara dengan LIMIT 1
    // }

    // public function getLatestPointByNopolisi(string $nopol)
    // {
    //     // print_r($nopol);
    //     // die();
    //     $result = $this->select('totalpoint')
    //         ->where('nopolisi', $nopol)
    //         ->orderBy('tglinsert', 'DESC')
    //         ->first();

    //     return $result ? $result['totalpoint'] : 0;
    // }

    public function getLatestPointByNopolisi(string $nopol): int
    {
        try {
            if (empty(trim($nopol))) {
                // log_message('error', 'Empty nopolisi provided to getLatestPointByNopolisi');
                return 0;
            }

            // log_message('debug', "Fetching latest point for nopolisi: {$nopol}");

            $result = $this->select('totalpoint')
                ->where('nopolisi', $nopol)
                ->where('statusproses', 3)
                ->orderBy('tglinsert', 'DESC')
                ->first();

            if (!$result || !isset($result['totalpoint'])) {
                log_message('debug', "No points found for nopolisi: {$nopol}");
                return 0;
            }

            $point = (int) $result['totalpoint'];
            // log_message('debug', "Found point {$point} for nopolisi: {$nopol}");
            return $point;

        } catch (\Exception $e) {
            log_message('error', "Error in getLatestPointByNopolisi for nopolisi {$nopol}: " . $e->getMessage());
            return 0;
        }
    }
}
