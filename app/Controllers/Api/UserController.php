<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\MemberModel;
use App\Models\VehicleModel;
use App\Models\MultiDBModel;
use App\Models\RatingModel;
use App\Models\PromoModel;
use App\Models\PointModel;
use App\Models\WarrantyModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class UserController extends BaseController
{

    protected $db;

    // use ResponseTrait;
    public function __construct()
    {
        // $this->BookingHeaderModel = new BookingHeaderModel();
        date_default_timezone_set('Asia/Jakarta');
        $this->db = \Config\Database::connect();
    }

    // public function vehicles()
    // {

    //     try {
    //         $user = $this->request->user;
    //         $phone_number = $user->phone ?? null;

    //         if (!$phone_number) {
    //             log_message('error', 'Token missing phone number.');
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'code' => 401,
    //                 'message' => 'Akses tidak diizinkan.'
    //             ])->setStatusCode(401);
    //         }

    //         $userModel = new UserModel();
    //         $user = $userModel->where('phone_number', $phone_number)->first();

    //         if (!$user) {
    //             log_message('error', 'User not found for phone: ' . $phone_number);
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'code' => 401,
    //                 'message' => 'Akses tidak diizinkan.'
    //             ])->setStatusCode(401);
    //         }

    //         $memberModel = new MemberModel();
    //         $vehicleModel = new VehicleModel();

    //         $vehicles = $vehicleModel->getMemberVehicle($phone_number);



    //         $insertData = [];

    //         foreach ($vehicles as $item) {
    //             $nopol = $item['nopolisi'] ?? null;

    //             if (!$nopol) {
    //                 continue;
    //             }

    //             $exists = $memberModel->where('nopolisi', $nopol)->first();

    //             if (!$exists) {
    //                 $pointModel = new PointModel();
    //                 $point = $pointModel->getLatestByNopolisi($nopol);

    //                 $insertData[] = [
    //                     'nopolisi' => $nopol,
    //                     'namamember' => $item['nama'] ?? '',
    //                     'notelp' => $phone_number,
    //                     'typemotor' => $item['typemotor'] ?? '',
    //                     'odometer' => is_numeric($item['odometer'] ?? null) ? (int) $item['odometer'] : 0,
    //                     'flagregist' => isset($item['flagRegist']) && is_numeric($item['flagRegist']) ? (int) $item['flagRegist'] : 0,
    //                     'idjenismembership' => isset($item['idJenisMembership']) && is_numeric($item['idJenisMembership']) ? (int) $item['idJenisMembership'] : 0,
    //                     'checkstnk' => isset($item['checkStnk']) && is_numeric($item['checkStnk']) ? (int) $item['checkStnk'] : 0,
    //                     'flagkonfirmasi' => isset($item['flagKonfirmasi']) && is_numeric($item['flagKonfirmasi']) ? (int) $item['flagKonfirmasi'] : 0,
    //                     'tahunmotor' => is_numeric($item['tahunmotor'] ?? null) ? (int) $item['tahunmotor'] : 0,
    //                     'foto' => $item['varianmotor'] ?? 'default.png',
    //                     'create_date' => date('Y-m-d H:i:s'),
    //                     // 'total_point' => is_numeric($item['totalpoint'] ?? null) ? (int) $item['totalpoint'] : 0,
    //                     'total_point' => $point,
    //                 ];
    //             }
    //         }

    //         if (!empty($insertData)) {
    //             $memberModel->insertBatch($insertData);
    //             log_message('info', 'Inserted new vehicle data for phone: ' . $phone_number);
    //         }

    //         $member = $memberModel->where('notelp', $phone_number)->findAll();
    //         $baseUrl = base_url('upload');

    //         $vehicles = array_map(function ($item) use ($baseUrl) {
    //             return [
    //                 'id' => $item['id'] ?? null,
    //                 'license_plate_number' => $item['nopolisi'] ?? '',
    //                 'vehicle_name' => $item['typemotor'] ?? '',
    //                 // 'year' => $item['tahunmotor'] ?? '',
    //                 // 'odometer' => $item['odometer'] ?? '',
    //                 'year' => isset($item['tahunmotor']) ? (int) $item['tahunmotor'] : null,
    //                 'odometer' => isset($item['odometer']) ? (int) $item['odometer'] : null,
    //                 'image' => $baseUrl . '/' . ($item['foto'] ?? 'default.png'),
    //                 'total_point' => isset($item['totalpoint']) ? (int) $item['totalpoint'] : null,
    //             ];
    //         }, $member);


    //         log_message('info', 'User ' . $phone_number . ' successfully fetched vehicles.');
    //         // log_message('info', $phone_number);

    //         return $this->response->setJSON([
    //             'status' => 'success',
    //             'code' => 200,
    //             'token' => $user['token'],
    //             'vehicles' => $vehicles,
    //         ])->setStatusCode(200);

    //     } catch (\Exception $e) {

    //         log_message('error', 'Exception in vehicles(): ' . $e->getMessage());

    //         return $this->response->setJSON([
    //             'status' => 'error',
    //             'code' => 401,
    //             'message' => 'Akses tidak diizinkan.',
    //         ])->setStatusCode(401);
    //     }
    // }

    // versiterbaik 
    public function vehicles()
    {
        try {
            // log_message('debug', 'Start vehicles()');

            $user = $this->request->user;
            if (empty($user) || empty($user->phone)) {
                // log_message('error', 'Unauthorized: Missing phone number');
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }

            $phone = trim($user->phone);
            // log_message('debug', "Phone number: {$phone}");

            $userModel = new UserModel();
            $memberModel = new MemberModel();
            $vehicleModel = new VehicleModel();
            $pointModel = new PointModel();

            $userData = $userModel->where('phone_number', $phone)->first();
            if (!$userData) {
                // log_message('error', "User not found for phone: {$phone}");
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }

            $vehicles = $vehicleModel->getMemberVehicle($phone);
            // log_message('debug', 'Vehicles retrieved: ' . json_encode($vehicles));

            // Ambil semua kendaraan yang sudah ada berdasarkan nopolisi
            $existingMembers = $memberModel->where('notelp', $phone)->findAll();
            $existingNopolMap = [];
            foreach ($existingMembers as $member) {
                $existingNopolMap[$member['nopolisi']] = $member;
            }

            $insertData = [];
            $updateData = [];

            foreach ($vehicles ?? [] as $v) {
                $nopol = trim($v['nopolisi'] ?? '');
                if (!$nopol)
                    continue;

                // Ambil point
                $point = (int) ($pointModel->getLatestPointByNopolisi($nopol) ?? 0);
                // log_message('debug', "Point for {$nopol}: {$point}");

                // Jika kendaraan sudah ada → update
                if (isset($existingNopolMap[$nopol])) {
                    $id = $existingNopolMap[$nopol]['id'];
                    $updateData[] = [
                        'id' => $id,
                        'totalpoint' => $point,
                        'update_date' => date('Y-m-d H:i:s'),
                    ];
                    // log_message('debug', "Prepared update for existing vehicle: {$nopol}");
                    continue;
                }

                // Kalau belum ada → insert
                $insertData[] = [
                    'nopolisi' => $nopol,
                    'namamember' => $v['nama'] ?? '',
                    'notelp' => $phone,
                    'typemotor' => $v['typemotor'] ?? '',
                    'odometer' => (int) ($v['odometer'] ?? 0),
                    'flagregist' => (int) ($v['flagRegist'] ?? 0),
                    'idjenismembership' => (int) ($v['idJenisMembership'] ?? 0),
                    'checkstnk' => (int) ($v['checkStnk'] ?? 0),
                    'flagkonfirmasi' => (int) ($v['flagKonfirmasi'] ?? 0),
                    'tahunmotor' => (int) ($v['tahunmotor'] ?? 0),
                    'foto' => $v['varianmotor'] ?? 'default.png',
                    'create_date' => date('Y-m-d H:i:s'),
                    'update_date' => date('Y-m-d H:i:s'),
                    'totalpoint' => $point,
                    'exp_date_point' => strtotime(date('Y') . '-12-31')
                ];
                // log_message('debug', "Prepared insert for new vehicle: {$nopol}");
            }

            // Jalankan batch insert
            if (!empty($insertData)) {
                $memberModel->insertBatch($insertData);
                // log_message('info', 'Inserted vehicles: ' . count($insertData));
            }

            // Jalankan batch update
            if (!empty($updateData)) {
                foreach ($updateData as $data) {
                    $memberModel->update($data['id'], [
                        'totalpoint' => $data['totalpoint'],
                        'update_date' => $data['update_date']
                    ]);
                }
                // log_message('info', 'Updated vehicles: ' . count($updateData));
            }

            // Ambil data akhir untuk ditampilkan
            $members = $memberModel->where('notelp', $phone)->findAll();
            // log_message('debug', 'Members found: ' . count($members));

            $baseUrl = rtrim(base_url('upload'), '/');
            $formatted = array_map(fn($m) => [
                'id' => $m['id'],
                'license_plate_number' => $m['nopolisi'] ?? '',
                'vehicle_name' => $m['typemotor'] ?? '',
                'year' => (int) ($m['tahunmotor'] ?? 0),
                'odometer' => (int) ($m['odometer'] ?? 0),
                'image' => $baseUrl . '/' . ($m['foto'] ? $m['foto'] . '.png' : 'default.png'),
                // 'foto' => base_url('upload/servis/' . ($row['idRekomendasi'] ? $row['idRekomendasi'] . '.png' : 'default.png')),
                'total_point' => (int) ($m['totalpoint'] ?? 0),
                'expired_point_date' => strtotime(date('Y') . '-12-31'),
            ], $members);

            // log_message('info', "Returning " . count($formatted) . " vehicles for {$phone}");

            return $this->response->setJSON([
                'status' => 'success',
                'code' => 200,
                'token' => $userData['token'] ?? null,
                'vehicles' => $formatted
            ]);
        } catch (\Exception $e) {
            log_message('error', 'vehicles() exception: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'code' => 401,
                'message' => 'Akses tidak diizinkan.',
            ])->setStatusCode(401);
        }
    }


    public function vehicles_detail($id)
    {

        try {
            $user = $this->request->user;
            $phone_number = $user->phone ?? null;
            if (!$phone_number) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }

            $userModel = new UserModel();

            $userData = $userModel->where('phone_number', $phone_number)->first();
            if (!$userData) {
                // log_message('error', "User not found for phone: {$phone}");
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }

            $memberModel = new MemberModel();
            $vehicle = $memberModel->where([
                'id' => $id,
                'notelp' => $phone_number
            ])->first();

            if (!$vehicle) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }
            $imagePath = base_url('upload/' . ($vehicle['foto'] ? $vehicle['foto'] . '.png' : 'default.png'));
            // ($m['foto'] ? $m['foto'] . '.png' : 'default.png'),
            $pointModel = new PointModel();
            $point = (int) ($pointModel->getLatestPointByNopolisi($vehicle['nopolisi']) ?? 0);

            return $this->response->setJSON([
                'status' => 'success',
                'code' => 200,
                'token' => $userData['token'] ?? null,
                'vehicle' => [
                    'id' => $vehicle['id'],
                    'license_plate_number' => $vehicle['nopolisi'] ?? '',
                    'vehicle_name' => $vehicle['typemotor'] ?? '',
                    'year' => (int) $vehicle['tahunmotor'] ?? 0,
                    'odometer' => (int) $vehicle['odometer'] ?? 0,
                    'image' => $imagePath,
                    'total_point' => $point,
                    'expired_point_date' => strtotime(date('Y') . '-12-31'),
                ]
            ])->setStatusCode(200);


        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 401,
                'message' => 'Akses tidak diizinkan.',
            ])->setStatusCode(401);
        }
    }


    public function updateOdometer($vehicle_id)
    {

        try {
            $user = $this->request->user;
            $phone_number = $user->phone ?? null;
            if (!$phone_number) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }

            $data = $this->request->getJSON(true);

            $odometer = $data['odometer'] ?? null;
            if (!$odometer) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }

            $memberModel = new MemberModel();
            $vehicle = $memberModel->where([
                'id' => $vehicle_id,
                'notelp' => $phone_number
            ])->first();

            if (!$vehicle) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Kendaraan tidak ditemukan.'
                ])->setStatusCode(404);
            }

            // Update odometer
            $updateSuccess = $memberModel->update($vehicle_id, [
                'odometer' => $odometer,
                'update_date' => date('Y-m-d H:i:s')
            ]);

            if (!$updateSuccess) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }

            // Siapkan data response
            $updatedVehicle = $memberModel->find($vehicle_id);
            // $imagePath = base_url('upload/' . ($updatedVehicle['varianmotor'] ?? 'default.png'));
            $imagePath = base_url('upload/' . ($vehicle['foto'] ? $vehicle['foto'] . '.png' : 'default.png'));

            return $this->response->setJSON([
                'status' => 'success',
                'code' => 200,
                'message' => 'Odometer berhasil diperbarui.',
                'vehicle' => [
                    'id' => $updatedVehicle['id'],
                    'license_plate_number' => $updatedVehicle['nopolisi'] ?? '',
                    'vehicle_name' => $updatedVehicle['typemotor'] ?? '',
                    'year' => (int) ($updatedVehicle['tahunmotor'] ?? 0),
                    // 'odometer' => $updatedVehicle['odometer'] ?? '',
                    'odometer' => (int) ($updatedVehicle['odometer'] ?? 0),
                    'image' => $imagePath,
                    'total_point' => (int) $updatedVehicle['totalpoint'] ?? 0,
                    'expired_point_date' => strtotime(date('Y') . '-12-31'),
                ]
            ])->setStatusCode(200);


        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 401,
                'message' => 'Akses tidak diizinkan.',
            ])->setStatusCode(401);
        }
    }


    public function serviceRecommendations($vehicle_id)
    {

        try {
            $user = $this->request->user;
            $phone_number = $user->phone ?? null;
            if (!$phone_number) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }

            $memberModel = new MemberModel();
            $vehicle = $memberModel->where([
                'id' => $vehicle_id,
                'notelp' => $phone_number
            ])->first();

            if (!$vehicle) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Kendaraan tidak ditemukan.'
                ])->setStatusCode(404);
            }


            $nopol = $vehicle['nopolisi'];

            $vehicleModel = new VehicleModel();
            $packageservis = $vehicleModel->getMemberRekomendasi($nopol);

            $transaksi = [];
            $currentTransaksi = null;

            // Proses pengelompokan
            foreach ($packageservis as $row) {
                // Gunakan idTransaksi + last_service sebagai kunci unik
                $transaksiKey = $row['idRekomendasi'] . '|' . $row['last_service'];

                if ($currentTransaksi === null || $currentTransaksi['transaksiKey'] !== $transaksiKey) {
                    if ($currentTransaksi !== null) {
                        unset($currentTransaksi['transaksiKey']);
                        $transaksi[] = $currentTransaksi;
                    }

                    // print_r($row);
                    // die();
                    $currentTransaksi = [
                        'id' => $row['idRekomendasi'],
                        'name' => $row['namaRekomendasi'],
                        'icon' => base_url('upload/servis/' . ($row['idRekomendasi'] ? $row['idRekomendasi'] . '.png' : 'default.png')),
                        'services' => [],
                        'note' => $row['note'],
                        // 'last_service_date' => $row['last_service_date'],
                        'last_service_date' => strtotime($row['last_service']),
                        'last_service_odometer' => (int) ($row['last_odoo'] ?? 0),
                        // 'next_service_date' => $row['next_service_date'],
                        'next_service_date' => strtotime($row['next_service_date']),
                        'next_service_odometer' => (int) ($row['next_service_odometer'] ?? 0),
                        'transaksiKey' => $transaksiKey
                    ];

                }

                if ($row['kodeProduk'] !== null) {
                    $currentTransaksi['services'][] = [
                        'product_id' => $row['kodeProduk'],
                        'name' => $row['namaPanjang'],
                        'image' => base_url('upload/produk/' . ($row['kodeProduk'] ? $row['kodeProduk'] . '.png' : 'default.png')),
                        'price' => (int) ($row['harga'] ?? 0),
                        'promo_price' => (int) ($row['promo_price'] ?? 0),
                        'discount_percentage' => (int) ($row['discount_percentage'] ?? 0)
                    ];
                }
            }

            // Tambahkan transaksi terakhir
            if ($currentTransaksi !== null) {
                unset($currentTransaksi['transaksiKey']);
                $transaksi[] = $currentTransaksi;
            }

            // Struktur response
            $response = [
                'status' => 'success',
                'code' => 200,
                'message' => 'Mengembalikan data Rekomendasi Servis dari motor yang dipilih.',
                'service' => $transaksi

            ];

            // Kembalikan response JSON
            return $this->response->setJSON($response)->setStatusCode(200);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 401,
                'message' => 'Akses tidak diizinkan.',
            ])->setStatusCode(401);
        }
    }

    public function warranty($vehicle_id)
    {
        try {
            $user = $this->request->user;
            $phone_number = $user->phone ?? null;
            if (!$phone_number) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }

            $memberModel = new MemberModel();
            $vehicle = $memberModel->where([
                'id' => $vehicle_id,
                'notelp' => $phone_number
            ])->first();

            if (!$vehicle) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Kendaraan tidak ditemukan.'
                ])->setStatusCode(404);
            }

            $nopol = $vehicle['nopolisi'];

            $warrantyModel = new WarrantyModel();
            $warranty = $warrantyModel->where('nopolisi', $nopol)->findAll();

            if (empty($warranty)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Tidak ada data garansi untuk nomor polisi yang diberikan.',
                    'warranty' => []
                ])->setStatusCode(200);
            } else {
                $warrantyData = [];
                foreach ($warranty as $item) {
                    $warrantyData[] = [
                        'id' => $item['id'],
                        // 'nopolisi' => $item['nopolisi'],
                        'product_id' => $item['product_id'],
                        'product_name' => $item['product_name'],
                        // 'product_image' => $item['product_image'],
                        'product_image' => base_url('upload/produk/' . ($item['product_image'] ? $item['product_image'] . '.png' : 'default.png')),
                        'warranty_duration' => (int) $item['warranty_duration'],
                        'end_warranty_date' => strtotime($item['end_warranty_date']),
                    ];
                }

                return $this->response->setJSON([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Mengembalikan data garansi dari motor yang dipilih.',
                    'warranty' => $warrantyData
                ])->setStatusCode(200);
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 401,
                'message' => 'Akses tidak diizinkan.',
            ])->setStatusCode(401);
        }
    }

    public function transactions($vehicle_id)
    {
        try {
            $user = $this->request->user;
            $phone_number = $user->phone ?? null;

            if (!$phone_number) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }

            $memberModel = new MemberModel();
            $vehicle = $memberModel->where([
                'id' => $vehicle_id,
                'notelp' => $phone_number
            ])->first();

            if (!$vehicle) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Kendaraan tidak ditemukan.'
                ])->setStatusCode(404);
            }

            $vehicles = [
                'id' => $vehicle['id'],
                'license_plate_number' => $vehicle['nopolisi'],
                'vehicle_name' => $vehicle['typemotor'],
                'year' => (int) ($vehicle['tahunmotor'] ?? 0),
                'odometer' => (int) ($vehicle['odometer'] ?? 0),
                'image' => base_url('upload/' . ($vehicle['foto'] ? $vehicle['foto'] . '.png' : 'default.png')),
                'total_point' => (int) ($vehicle['totalpoint'] ?? 0),
                'expired_point_date' => strtotime(date('Y') . '-12-31'),
            ];

            $nopol = $vehicle['nopolisi'];
            $notelp = $vehicle['notelp'];

            if (strpos($notelp, '0') === 0) {
                $notelp62 = '62' . substr($notelp, 1);
            } else {
                $notelp62 = $notelp;
            }

            // Daftar koneksi database
            $connections = ['devDC', 'JKT', 'PLG', 'TNG'];
            $formattedResult = [];

            foreach ($connections as $dbGroup) {
                try {
                    $multiDBmodel = new \App\Models\MultiDBModel(['dbGroup' => $dbGroup]);
                    $results = $multiDBmodel->transactionsp('sp_get_service_history_by_nopol', [$nopol]);

                    foreach ($results as $row) {
                        $formattedRow = [
                            'id' => $row['nomorTransaksi'] ?? null,
                            'customer_phone' => $notelp62,
                            'vehicle' => $vehicles,
                            'service_name' => $row['service_name'] ?? null,
                            'odometer' => (int) ($row['odometer'] ?? 0),
                            // 'service_date' => $row['service_date'] ?? null,
                            'service_date' => strtotime($row['service_date']),
                            'store_name' => $row['store_name'] ?? null,
                            'store_id' => $row['store_id'] ?? null,
                            'mechanic_name' => $row['mekanik'] ?? null,
                            'bad_checklist' => $row['bad_cek'] ?? null,
                            'good_checklist' => $row['good_cek'] ?? null,
                            'points_earned' => (int) ($row['new_point'] ?? 0),
                            'points_redeemed' => (int) ($row['point_redem'] ?? 0),
                            'price' => (int) ($row['price'] ?? 0),
                            'total_discount' => (int) ($row['total_discount'] ?? 0),
                            'order_price' => (int) ($row['order_price'] ?? 0),
                            'total_price' => (int) ($row['total_price'] ?? 0),
                            // 'source_db' => $dbGroup // menandai asal data
                        ];
                        $formattedResult[] = $formattedRow;
                    }

                } catch (\Exception $e) {
                    // Log error jika koneksi gagal
                    log_message('error', "Gagal mengakses database {$dbGroup}: " . $e->getMessage());
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'code' => 200,
                'message' => 'Mengembalikan data History Transaksi dari motor yang dipilih.',
                'transaction' => $formattedResult
            ])->setStatusCode(200);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 401,
                'message' => 'Akses tidak diizinkan.',
                // 'error' => $e->getMessage()
            ])->setStatusCode(401);
        }
    }


    // public function transactionDetail($store_id, $transaction_id)
    // {
    //     try {
    //         if (empty($transaction_id) || empty($store_id)) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'code' => 404,
    //                 'message' => 'Kendaraan tidak ditemukan.'
    //             ])->setStatusCode(404);
    //         }

    //         // Cek nomor telepon user
    //         $user = $this->request->user;
    //         $phone_number = $user->phone ?? null;

    //         if (!$phone_number) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'code' => 401,
    //                 'message' => 'Akses tidak diizinkan.'
    //             ])->setStatusCode(401);
    //         }

    //         $vehicleModel = new VehicleModel();
    //         $cabang = $vehicleModel->getCabang($store_id);

    //         if (empty($cabang)) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'code' => 404,
    //                 'message' => 'Cabang tidak ditemukan.'
    //             ])->setStatusCode(404);
    //         }

    //         $iddc = $cabang['iddc'] ?? null;

    //         $connectionMap = [
    //             '1' => 'JKT',
    //             '2' => 'BDG',
    //             '3' => 'SMG',
    //             '4' => 'devDC',
    //             '5' => 'DPS',
    //             '6' => 'TNG',
    //             '7' => 'PLG'
    //         ];

    //         if (!isset($connectionMap[$iddc])) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'code' => 500,
    //                 'message' => 'Mapping koneksi tidak ditemukan untuk iddc: ' . $iddc
    //             ])->setStatusCode(500);
    //         }

    //         $dbGroup = $connectionMap[$iddc];

    //         if (!$dbGroup) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'code' => 400,
    //                 'message' => 'Koneksi database untuk store_id tidak ditemukan.'
    //             ])->setStatusCode(400);
    //         }

    //         // Inisialisasi Model dan ambil data
    //         $multiDBmodel = new MultiDBModel(['dbGroup' => $dbGroup]);
    //         $results = $multiDBmodel->transactionsp('sp_get_service_history_by_notrans_detail', [$store_id, $transaction_id]);

    //         if (empty($results)) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'code' => 404,
    //                 'message' => 'Transaksi tidak ditemukan.'
    //             ])->setStatusCode(404);
    //         }

    //         // Cek kendaraan
    //         $memberModel = new MemberModel();
    //         $vehicle = null;
    //         $transaksiceklist = [];
    //         $productdetail = [];
    //         $warranty = [];
    //         $rating = [];


    //         $ratingModel = new RatingModel();
    //         $ratingresult = $ratingModel->where('transaction_id', $transaction_id)
    //             ->where('store_id', $store_id)
    //             ->first();

    //         if ($ratingresult) {
    //             $rate_note_raw = $ratingresult['rate_note'] ?? '[]'; // JSON string
    //             $rate_note = json_decode($rate_note_raw, true);

    //             $rating = [
    //                 'rate' => (int) ($ratingresult['rate'] ?? 0),
    //                 'rate_note' => is_array($rate_note) ? $rate_note : [],
    //                 'feedback' => $ratingresult['feedback'] ?? '',
    //             ];
    //         }

    //         foreach ($results as $row) {
    //             $nopolisi = $row['nopolisi'] ?? null;

    //             if (!$vehicle || $vehicle['nopolisi'] !== $nopolisi) {
    //                 $vehicle = $memberModel->where([
    //                     'nopolisi' => $nopolisi,
    //                     'notelp' => $phone_number
    //                 ])->first();

    //                 if (!$vehicle) {
    //                     return $this->response->setJSON([
    //                         'status' => 'error',
    //                         'code' => 404,
    //                         'message' => 'Kendaraan tidak ditemukan.'
    //                     ])->setStatusCode(404);
    //                 }
    //             }

    //             $vehicles = [
    //                 'id' => $vehicle['id'] ?? null,
    //                 'nopolisi' => $vehicle['nopolisi'] ?? null,
    //                 'vehicle_name' => $vehicle['typemotor'] ?? null,
    //                 'year' => (int) ($vehicle['tahunmotor'] ?? 0),
    //                 'odometer' => (int) ($vehicle['odometer'] ?? 0),
    //                 'image' => base_url('upload/' . ($vehicle['foto'] ? $vehicle['foto'] . '.png' : 'default.png')),
    //                 'total_point' => (int) ($vehicle['totalpoint'] ?? 0),
    //             ];

    //             $formattedResult = [
    //                 'id' => $row['nomorTransaksi'] ?? null,
    //                 'customer_phone' => $phone_number,
    //                 'nopolisi' => $nopolisi,
    //                 'service_name' => $row['service_name'] ?? null,
    //                 'odometer' => (int) ($row['odometer'] ?? 0),
    //                 // 'service_date' => $row['service_date'] ?? null,
    //                 'service_date' => strtotime($row['service_date']) ?? null,
    //                 'store_name' => $row['store_name'] ?? null,
    //                 'store_id' => $row['store_id'] ?? null,
    //                 'mechanic_name' => $row['mekanik'] ?? null,
    //                 'bad_checklist' => $row['bad_cek'] ?? null,
    //                 'good_checklist' => $row['good_cek'] ?? null,
    //                 'points_earned' => (int) ($row['new_point'] ?? 0),
    //                 'points_redeemed' => (int) ($row['point_redem'] ?? 0),
    //                 'price' => (int) ($row['price'] ?? 0),
    //                 'order_price' => (int) ($row['order_price'] ?? 0),
    //                 'total_price' => (int) ($row['total_price'] ?? 0),
    //                 // 'source_db' => $dbGroup
    //             ];


    //             $warrantyModel = new WarrantyModel();
    //             $warrantyData = $warrantyModel->where('nopolisi', $nopolisi)->findAll();

    //             foreach ($warrantyData as $item) {
    //                 $warranty[] = [
    //                     'id' => $item['id'],
    //                     // 'nopolisi' => $item['nopolisi'],
    //                     'product_id' => $item['product_id'],
    //                     'product_name' => $item['product_name'],
    //                     // 'product_image' => $item['product_image'],
    //                     'product_image' => base_url('upload/produk/' . ($item['product_image'] ? $item['product_image'] . '.png' : 'default.png')),
    //                     'warranty_duration' => (int) $item['warranty_duration'],
    //                     'end_warranty_date' => strtotime($item['end_warranty_date']),
    //                 ];
    //             }
    //         }

    //         // Ambil checklist
    //         $checklist = $multiDBmodel->transactionsp('sp_get_service_detail_by_notrans', [$store_id, $transaction_id]);
    //         foreach ($checklist as $item) {
    //             $transaksiceklist[] = [
    //                 'id' => $item['id'] ?? null,
    //                 'component' => $item['namacekup'] ?? null,
    //                 'condition_before' => $item['cek_awal'] ?? null,
    //                 'condition_after' => $item['cek_akhir'] ?? null,
    //                 'note' => $item['note'] ?? null,
    //                 'image' => base_url('upload/rekomendasi/' . ($item['id'] ?? 'default') . '.png'),
    //             ];
    //         }

    //         // Ambil detail produk
    //         $produklist = $multiDBmodel->transactionsp('sp_get_service_history_product_detail', [$store_id, $transaction_id]);
    //         foreach ($produklist as $item) {
    //             $productdetail[] = [
    //                 'product_id' => $item['kodeProduk'] ?? null,
    //                 'name' => $item['namaPanjang'] ?? null,
    //                 'price' => (int) ($item['price'] ?? 0),
    //                 'promo_price' => (int) ($item['promo_price'] ?? 0),
    //                 'discount_percentage' => (int) ($item['diskon_persen'] ?? 0),
    //                 'image' => base_url('upload/produk/' . ($item['kodeProduk'] ?? 'default') . '.png'),
    //             ];
    //         }

    //         return $this->response->setJSON([
    //             'status' => 'success',
    //             'code' => 200,
    //             'message' => 'Mengembalikan data dari detail transaksi.',
    //             'transaction' => $formattedResult,
    //             'vehicle' => $vehicles,
    //             'rating' => $rating,
    //             'warranty' => $warranty,
    //             'checklist' => $transaksiceklist,
    //             'product' => $productdetail
    //         ])->setStatusCode(200);

    //     } catch (\Exception $e) {
    //         log_message('error', 'Error in transactionDetail: ' . $e->getMessage());
    //         return $this->response->setJSON([
    //             'status' => 'error',
    //             'code' => 500,
    //             'message' => 'Terjadi kesalahan saat memproses data.',
    //             'error' => $e->getMessage()
    //         ])->setStatusCode(500);
    //     }
    // }

    public function transactionDetail($store_id, $transaction_id)
    {
        try {
            if (empty($transaction_id) || empty($store_id)) {
                log_message('error', 'Missing transaction_id or store_id');
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }

            $user = $this->request->user;
            $phone_number = $user->phone ?? null;

            if (!$phone_number) {
                log_message('error', 'Unauthorized access: No phone number found');
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }

            $vehicleModel = new VehicleModel();
            $cabang = $vehicleModel->getCabang($store_id);

            if (empty($cabang)) {
                log_message('error', 'Cabang tidak ditemukan untuk store_id: ' . $store_id);
                $cabang = [];
            }

            $iddc = $cabang['iddc'] ?? null;

            $connectionMap = [
                '1' => 'devDC',
                '2' => 'devDC',
                '3' => 'devDC',
                '4' => 'devDC',
                '5' => 'devDC',
                '6' => 'devDC',
                '7' => 'devDC'
            ];


            if (!isset($connectionMap[$iddc])) {
                log_message('error', 'Mapping koneksi tidak ditemukan untuk iddc: ' . $iddc);
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }

            $dbGroup = $connectionMap[$iddc] ?? null;

            $multiDBmodel = new MultiDBModel(['dbGroup' => $dbGroup]);
            $results = $multiDBmodel->transactionsp('sp_get_service_history_by_notrans_detail', [$store_id, $transaction_id]);

            if (empty($results)) {
                log_message('error', 'Transaksi tidak ditemukan: ' . $transaction_id);
                $results = [];
            }

            $memberModel = new MemberModel();
            $vehicle = null;
            $transaksiceklist = [];
            $productdetail = [];
            $warranty = [];
            $rating = [];
            $vehicles = [];
            $formattedResult = [];

            $ratingModel = new RatingModel();
            $ratingresult = $ratingModel->where('transaction_id', $transaction_id)
                ->where('store_id', $store_id)
                ->first();

            if ($ratingresult) {
                $rate_note_raw = $ratingresult['rate_note'] ?? '[]';
                $rate_note = json_decode($rate_note_raw, true);

                $rating = [
                    'rate' => (int) ($ratingresult['rate'] ?? 0),
                    'rate_note' => is_array($rate_note) ? $rate_note : [],
                    'feedback' => $ratingresult['feedback'] ?? '',
                ];
            } else {
                log_message('error', 'Rating tidak ditemukan untuk transaksi: ' . $transaction_id);
            }

            foreach ($results as $row) {
                $nopolisi = $row['nopolisi'] ?? null;

                if (!$vehicle || $vehicle['nopolisi'] !== $nopolisi) {
                    $vehicle = $memberModel->where([
                        'nopolisi' => $nopolisi,
                        'notelp' => $phone_number
                    ])->first();

                    if (!$vehicle) {
                        log_message('error', 'Kendaraan tidak ditemukan untuk nopolisi: ' . $nopolisi . ' dan phone: ' . $phone_number);
                        continue;
                    }
                }

                $vehicles = [
                    'id' => $vehicle['id'] ?? null,
                    'license_plate_number' => $vehicle['nopolisi'] ?? null,
                    'vehicle_name' => $vehicle['typemotor'] ?? null,
                    'year' => (int) ($vehicle['tahunmotor'] ?? 0),
                    'odometer' => (int) ($vehicle['odometer'] ?? 0),
                    'image' => base_url('upload/' . ($vehicle['foto'] ? $vehicle['foto'] . '.png' : 'default.png')),
                    'total_point' => (int) ($vehicle['totalpoint'] ?? 0),
                ];

                $formattedResult = [
                    'id' => $row['nomorTransaksi'] ?? null,
                    'customer_phone' => $phone_number,
                    'license_plate_number' => $nopolisi,
                    'service_name' => $row['service_name'] ?? null,
                    'odometer' => (int) ($row['odometer'] ?? 0),
                    'service_date' => strtotime($row['service_date']) ?? null,
                    'store_name' => $row['store_name'] ?? null,
                    'store_id' => $row['store_id'] ?? null,
                    'mechanic_name' => $row['mekanik'] ?? null,
                    'bad_checklist' => $row['bad_cek'] ?? null,
                    'good_checklist' => $row['good_cek'] ?? null,
                    'points_earned' => (int) ($row['new_point'] ?? 0),
                    'points_redeemed' => (int) ($row['point_redem'] ?? 0),
                    'price' => (int) ($row['price'] ?? 0),
                    'total_discount' => (int) ($row['total_discount'] ?? 0),
                    'order_price' => (int) ($row['order_price'] ?? 0),
                    'total_price' => (int) ($row['total_price'] ?? 0),
                ];
            }

            $checklist = $multiDBmodel->transactionsp('sp_get_service_detail_by_notrans', [$store_id, $transaction_id]);
            foreach ($checklist as $item) {
                $transaksiceklist[] = [
                    'id' => $item['id'] ?? null,
                    'component' => $item['namacekup'] ?? null,
                    'condition_before' => $item['cek_awal'] ?? null,
                    'condition_after' => $item['cek_akhir'] ?? null,
                    'note' => $item['note'] ?? null,
                    'image' => base_url('upload/rekomendasi/' . ($item['id'] ?? 'default') . '.png'),
                ];
            }

            $produklist = $multiDBmodel->transactionsp('sp_get_service_history_product_detail', [$store_id, $transaction_id]);
            foreach ($produklist as $item) {

                $kodeProduk = $item['kodeProduk'] ?? 'default';

                $productdetail[] = [
                    'product_id' => $item['kodeProduk'] ?? null,
                    'name' => $item['namaPanjang'] ?? null,
                    'price' => (int) ($item['price'] ?? 0),
                    'promo_price' => (int) ($item['promo_price'] ?? 0),
                    'discount_percentage' => (int) ($item['diskon_persen'] ?? 0),
                    'image' => base_url('upload/produk/' . $kodeProduk . '.png'),
                ];

                if (!empty($item['id'])) {
                    $warranty[] = [
                        'id' => $item['id'],
                        'product_id' => $kodeProduk,
                        'product_name' => $item['namaPanjang'] ?? null,
                        'product_image' => base_url('upload/produk/' . $kodeProduk . '.png'),
                        'warranty_duration' => (int) ($item['warranty_duration'] ?? 0),
                        'end_warranty_date' => !empty($item['end_warranty_date']) ? strtotime($item['end_warranty_date']) : null,
                    ];
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'code' => 200,
                'message' => 'Mengembalikan data dari detail transaksi.',
                'transaction' => $formattedResult,
                'vehicle' => $vehicles,
                'rating' => $rating,
                'warranty' => $warranty,
                'checklist' => $transaksiceklist,
                'product' => $productdetail
            ])->setStatusCode(200);

        } catch (\Exception $e) {
            log_message('error', 'Error in transactionDetail: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 401,
                'message' => 'Akses tidak diizinkan.',
            ])->setStatusCode(401);
        }
    }


    public function ratingSubmit($store_id, $transaction_id)
    {
        $data = $this->request->getJSON(true);
        $rate = $data['rate'] ?? null;
        $rate_note_raw = $data['rate_note'] ?? null;
        $feedback = $data['feedback'] ?? null;

        if (empty($transaction_id) || empty($store_id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 404,
                'message' => 'Transaksi tidak ditemukan.'
            ])->setStatusCode(404);
        }

        $user = $this->request->user;
        $phone_number = $user->phone ?? null;

        if (!$phone_number) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 401,
                'message' => 'Akses tidak diizinkan.'
            ])->setStatusCode(401);
        }

        if (empty($rate)) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 404,
                'message' => 'Transaksi tidak ditemukan.'
            ])->setStatusCode(404);
        }

        $ratingModel = new RatingModel();
        $transaction = $ratingModel->where('transaction_id', $transaction_id)
            ->where('store_id', $store_id)
            ->first();

        if (!empty($transaction)) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 401,
                'message' => 'Akses tidak diizinkan.'
            ])->setStatusCode(401);
        }

        // Ubah awalan 0 menjadi 62
        if (strpos($phone_number, '0') === 0) {
            $phone_number = '62' . substr($phone_number, 1);
        }

        // Helper untuk ubah kosong menjadi null
        $toNullIfEmpty = function ($value) {
            if (is_string($value) && trim($value) === '') {
                return null;
            }
            if (is_array($value) && empty($value)) {
                return null;
            }
            return $value;
        };

        // Cek rate_note kosong (array kosong)
        if (is_array($rate_note_raw) && empty($rate_note_raw)) {
            $rate_note_json = null;
            $rate_notestring = null;
        } else {
            $rate_note_json = $toNullIfEmpty(json_encode($rate_note_raw));
            $rate_notestring = $toNullIfEmpty(is_array($rate_note_raw) ? implode(', ', $rate_note_raw) : $rate_note_raw);
        }

        $rate_notestring = $toNullIfEmpty(is_array($rate_note_raw) ? implode(', ', $rate_note_raw) : $rate_note_raw);
        $feedback_sanitized = $toNullIfEmpty(htmlspecialchars($feedback, ENT_QUOTES, 'UTF-8'));

        $ratingData = [
            'user_id' => $toNullIfEmpty($phone_number),
            'store_id' => $toNullIfEmpty($store_id),
            'transaction_id' => $toNullIfEmpty($transaction_id),
            'rate' => $toNullIfEmpty($rate),
            'rate_note' => $rate_note_json,
            'feedback' => $feedback_sanitized,
            'created_at' => date('Y-m-d H:i:s'),
            'note_rate' => $rate_notestring,
        ];

        try {
            $ratingModel->insert($ratingData);

            return $this->response->setJSON([
                'status' => 'success',
                'code' => 200,
                'message' => 'Rating berhasil tersimpan.'
            ])->setStatusCode(200);

        } catch (\Exception $e) {
            log_message('error', 'Error saat menyimpan rating: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 401,
                'message' => 'Akses tidak diizinkan.',
                // 'error' => $e->getMessage()
            ])->setStatusCode(401);
        }
    }


    // public function promos()
    // {
    //     try {
    //         $user = $this->request->user;
    //         $phone_number = $user->phone ?? null;

    //         if (!$phone_number) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'code' => 401,
    //                 'message' => 'Akses tidak diizinkan.'
    //             ])->setStatusCode(401);
    //         }

    //         $promoModel = new PromoModel();
    //         $list_promo = $promoModel->findAll();

    //         $promos = [];
    //         foreach ($list_promo as $row) {
    //             $promos[] = [
    //                 'id' => $row['id'] ?? null,
    //                 'title' => $row['title'] ?? null,
    //                 'image' => $row['image_url'] ?? null,
    //                 'target_url' => $row['target_url'] ?? null,
    //             ];
    //         }

    //         return $this->response->setJSON([
    //             'status' => 'success',
    //             'code' => 200,
    //             'message' => 'Mengembalikan data promo.',
    //             'promo' => $promos
    //         ])->setStatusCode(200);

    //     } catch (\Exception $e) {
    //         return $this->response->setJSON([
    //             'status' => 'error',
    //             'code' => 404,
    //             'message' => 'Promo tidak ditemukan.',
    //             // 'error' => $e->getMessage()
    //         ])->setStatusCode(404);
    //     }
    // }

    public function promos()
    {
        try {
            $user = $this->request->user;
            $phone_number = $user->phone ?? null;

            if (!$phone_number) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }

            $promoModel = new PromoModel();
            $list_promo = $promoModel->findAll();

            $promos = [];
            if (!empty($list_promo)) {
                foreach ($list_promo as $row) {
                    $promos[] = [
                        'id' => $row['id'] ?? null,
                        'title' => $row['title'] ?? null,
                        'image' => $row['image_url'] ?? null,
                        'target_url' => $row['target_url'] ?? null,
                    ];
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'code' => 200,
                'message' => count($promos) > 0 ? 'Mengembalikan data promo.' : 'Data promo kosong.',
                'promo' => $promos
            ])->setStatusCode(200);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 401,
                'message' => 'Akses tidak diizinkan.'
                // 'error' => $e->getMessage()
            ])->setStatusCode(401);
        }
    }


    public function pointHistory($vehicle_id)
    {
        try {
            $user = $this->request->user;
            $phone_number = $user->phone ?? null;


            if (!$phone_number) {
                log_message('error', 'Token missing phone number.');
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }

            $memberModel = new MemberModel();
            $vehicle = $memberModel->where([
                'id' => $vehicle_id,
                'notelp' => $phone_number
            ])->first();



            if (!$vehicle) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Kendaraan tidak ditemukan.'
                ])->setStatusCode(404);
            }

            $vehicles = [
                'id' => $vehicle['id'],
                'license_plate_number' => $vehicle['nopolisi'],
                'vehicle_name' => $vehicle['typemotor'],
                'year' => (int) ($vehicle['tahunmotor'] ?? 0),
                'odometer' => (int) ($vehicle['odometer'] ?? 0),
                'image' => base_url('upload/' . ($vehicle['foto'] ? $vehicle['foto'] . '.png' : 'default.png')),
                'total_point' => (int) ($vehicle['totalpoint'] ?? 0),
                'expired_point_date' => strtotime(date('Y') . '-12-31'),
            ];


            $nopol = $vehicle['nopolisi'];
            // $notelp = $vehicle['notelp'];

            // Daftar koneksi database
            $connections = ['devDC', 'JKT', 'PLG', 'TNG'];
            $formattedResult = [];

            foreach ($connections as $dbGroup) {
                try {
                    $multiDBmodel = new \App\Models\MultiDBModel(['dbGroup' => $dbGroup]);
                    $results = $multiDBmodel->transactionsp('sp_get_service_history_by_nopol', [$nopol]);

                    foreach ($results as $row) {
                        $formattedRow = [
                            'transaction_id' => $row['nomorTransaksi'] ?? null,
                            'store_id' => $row['store_id'] ?? null,
                            'service_name' => $row['service_name'] ?? null,
                            'icon' => base_url('upload/servis/default.png'),
                            // 'service_date' => $row['service_date'] ?? null,
                            'service_date' => strtotime($row['service_date']) ?? null,
                            'points_earned' => (int) ($row['new_point'] ?? 0),
                            'points_redeemed' => (int) ($row['point_redem'] ?? 0),
                            // 'source_db' => $dbGroup // menandai asal data
                        ];
                        $formattedResult[] = $formattedRow;
                    }

                } catch (\Exception $e) {
                    // Log error jika koneksi gagal
                    log_message('error', "Gagal mengakses database {$dbGroup}: " . $e->getMessage());
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'code' => 200,
                'message' => 'Mengembalikan data Point History dari motor yang dipilih.',
                'vehicle' => $vehicles,
                'point' => $formattedResult
            ])->setStatusCode(200);

        } catch (\Exception $e) {

            log_message('error', 'Exception in vehicles(): ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'code' => 401,
                'message' => 'Akses tidak diizinkan.',
            ])->setStatusCode(401);
        }
    }


}
