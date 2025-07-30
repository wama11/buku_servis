<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class TestDatabase extends Controller
{
    // public function index()
    // {
    //     try {
    //         $db = Database::connect();
    //         echo "Database connected successfully!";
    //     } catch (\Exception $e) {
    //         echo "Failed to connect: " . $e->getMessage();
    //     }
    // }

    public function index()
    {
        try {
            // Inisialisasi koneksi ke grup 'secondary' (SQL Server)
            $db = \Config\Database::connect('point');

            // Tes koneksi dengan menjalankan query sederhana
            // $db->query('SELECT 1');

            // Jika berhasil, tampilkan pesan sukses
            echo "Database connected successfully to Postgres (dev point)!";
            // echo "OKKEE!";
            // Opsional: Panggil stored procedure untuk tes lebih lanjut
            // $model = new MemberVehicleModel();
            // $phoneNumber = '081234567890'; // Ganti dengan nomor telepon yang ada di database
            // $vehicles = $model->getMemberVehicle($phoneNumber);

            // Kirim data ke view
            // $data = [
            //     'vehicles' => $vehicles,
            //     'phoneNumber' => $phoneNumber,
            //     'connectionStatus' => 'Connected successfully'
            // ];

            // return view('member_vehicle_view', $data);

        } catch (\Exception $e) {
            echo "Failed to connect: " . $e->getMessage();
            // Tangani error koneksi
            // $errorMessage = "Failed to connect to SQL Server: " . $e->getMessage();

            // // Kirim pesan error ke view
            // $data = [
            //     'vehicles' => [],
            //     'phoneNumber' => '',
            //     'connectionStatus' => $errorMessage
            // ];

            // return view('member_vehicle_view', $data);
        }
    }
}
