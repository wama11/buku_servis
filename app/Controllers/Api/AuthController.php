<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\MemberModel;
use App\Models\RoboticModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthController extends BaseController
{

    protected $db;
    public function __construct()
    {
        // $this->BookingHeaderModel = new BookingHeaderModel();
        date_default_timezone_set('Asia/Jakarta');
        $this->db = \Config\Database::connect();
        helper(['sendmessage']);
    }

    public function verifyPhone()
    {
        $data = $this->request->getJSON(true);
        $phone_number = $data['phone_number'] ?? null;



        if (!$phone_number) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 404,
                'message' => 'No. telepon tidak terdaftar atau akun sedang dalam status blokir.'
            ])->setStatusCode(404);
        }

        // Ubah awalan 62 menjadi 0
        if (strpos($phone_number, '62') === 0) {
            $phone_number = '0' . substr($phone_number, 2);
        }

        // Validasi nomor setelah dikonversi
        if (!preg_match('/^0[0-9]{8,13}$/', $phone_number)) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 404,
                'message' => 'No. telepon tidak terdaftar atau akun sedang dalam status blokir.'
            ])->setStatusCode(404);
        }

        // Cek ke database
        $userModel = new UserModel();
        $user = $userModel->where('phone_number', $phone_number)->first();

        if (!$user) {

            $roboticModel = new RoboticModel();
            $userrobo = $roboticModel->where('notelp', $phone_number)->first();

            if (!$userrobo) {

                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'No. telepon tidak terdaftar atau akun sedang dalam status blokir.'
                ])->setStatusCode(404);
            }

            $insertmember = [
                'username' => $userrobo['namamember'],
                'password' => 'default123',
                'phone_number' => $phone_number,
                'create_date' => date('Y-m-d H:i:s')
            ];

            $userModel->insert($insertmember);

            return $this->response->setJSON([
                'status' => 'success',
                'code' => 200,
                'message' => 'No. telp terdaftar.',
                'is_pin_set' => 'false'
            ])->setStatusCode(200);

        }

        // Cek apakah pin sudah diatur
        $is_pin_set = !empty($user['pin']);

        return $this->response->setJSON([
            'status' => 'success',
            'code' => 200,
            'message' => 'No. telp terdaftar.',
            'is_pin_set' => $is_pin_set
        ])->setStatusCode(200);
    }



    public function requestOtp()
    {
        $data = $this->request->getJSON(true);
        $phone_number62 = $data['phone_number'] ?? null;
        $method = $data['method'] ?? null;
        $purpose = $data['purpose'] ?? 'login';

        if (!$phone_number62 || !$method) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 400,
                'message' => 'No. telepon tidak terdaftar atau akun sedang dalam status blokir.'
            ])->setStatusCode(400);
        }

        // Normalisasi nomor telepon (62xx → 0xx)
        if (substr($phone_number62, 0, 2) === '62') {
            $phone_number = '0' . substr($phone_number62, 2);
        }

        $userModel = new UserModel();
        $user = $userModel->where('phone_number', $phone_number)->first();

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 404,
                'message' => 'No. telepon tidak terdaftar atau akun sedang dalam status blokir.'
            ])->setStatusCode(404);
        }

        // Generate OTP 4 digit dan token ID
        $otp = random_int(1000, 9999);
        $tokenidwa = md5($phone_number62 . date("Y-m-d H:i:s"));

        try {
            // Coba kirim OTP sesuai metode
            if ($method === 'whatsapp') {
                helper('sendmessage');
                $xid = send_message($phone_number62, $otp, $tokenidwa);
                if ($xid === null) {
                    throw new \Exception('Kode OTP gagal terkirim, mohon coba kembali setelah 5 menit.');
                }
            } elseif ($method === 'sms') {
                // $sent = send_sms($phone_number62, $otp);
                // if (!$sent) {
                throw new \Exception('Kode OTP gagal terkirim, mohon coba kembali setelah 5 menit.');
                // }
            } else {
                throw new \Exception('Kode OTP gagal terkirim, mohon coba kembali setelah 5 menit.');
            }

            // Jika pengiriman sukses, simpan OTP ke database
            $tokenData = [
                'otp' => $otp,
                'created_otp' => date('Y-m-d H:i:s'),
                'expired_otp' => date('Y-m-d H:i:s', strtotime('+1 minutes')),
            ];

            $userModel->where('phone_number', $phone_number)->update(null, $tokenData);

            return $this->response->setJSON([
                'status' => 'success',
                'code' => 200,
                'message' => 'Kode OTP terkirim.',
                'purpose' => $purpose,
                // 'otp_preview' => $otp // hapus di production
            ])->setStatusCode(200);

        } catch (\Throwable $e) {
            // Kirim response error jika OTP gagal terkirim
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 400,
                'message' => $e->getMessage()
            ])->setStatusCode(400);
        }
    }

    public function verifyOtp()
    {
        $data = $this->request->getJSON(true);
        $phone_number62 = $data['phone_number'] ?? null;
        $otp = $data['otp'] ?? null;


        if (!$phone_number62 || !$otp) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 400,
                'message' => 'Kode OTP salah atau sudah kedaluwarsa.'
            ])->setStatusCode(400);
        }

        // Normalisasi nomor telepon (62xx → 0xx)
        if (substr($phone_number62, 0, 2) === '62') {
            $phone_number = '0' . substr($phone_number62, 2);
        }

        $userModel = new UserModel();
        $user = $userModel->where('phone_number', $phone_number)->first();


        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 404,
                'message' => 'No. telepon tidak terdaftar atau akun sedang dalam status blokir.'
            ])->setStatusCode(404);
        }

        // Cek OTP
        if (trim((string) $user['otp']) !== trim((string) $otp)) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 400,
                'message' => 'Kode OTP salah atau sudah kedaluwarsa.'
            ])->setStatusCode(400);
        }

        // Cek expired OTP
        $currentTime = time();
        $otpExpiry = strtotime($user['expired_otp']);

        if ($otpExpiry < $currentTime) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 400,
                'message' => 'Kode OTP salah atau sudah kedaluwarsa.'
            ])->setStatusCode(400);
        }

        // Generate JWT
        $config = config('App');
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600 * 24; // Token berlaku 24 jam
        $token_expiry = date('Y-m-d H:i:s', $expirationTime);

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'phone' => $user['phone_number']
        ];

        $jwt = JWT::encode($payload, $config->jwtSecretKey, 'HS256');
        $tokenData = [
            'token' => $jwt,
            'token_expiry' => $token_expiry
        ];

        $userModel->where('phone_number', $phone_number)->update(null, $tokenData);

        return $this->response->setJSON([
            'status' => 'success',
            'code' => 200,
            'message' => 'Verifikasi OTP berhasil.',
            'token' => $jwt
        ])->setStatusCode(200);
    }

    public function setPin()
    {

        try {
            $user = $this->request->user;
            $phone_number = $user->phone ?? null;

            if (!$phone_number) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(400);
            }

            $data = $this->request->getJSON(true);
            $pin = $data['pin'] ?? null;

            if (!$pin || strlen($pin) !== 6 || !ctype_digit($pin)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'PIN tidak berhasil tersimpan.'
                ])->setStatusCode(400);
            }

            $userModel = new UserModel();
            $user = $userModel->where('phone_number', $phone_number)->first();


            if (!$user) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])->setStatusCode(401);
            }

            // Simpan PIN (sebaiknya di-hash, untuk keamanan)
            // password_verify($input,$stored_hashed_pin);
            $hashedPin = password_hash($pin, PASSWORD_BCRYPT);
            $updated_pin = date('Y-m-d H:i:s');

            $updateData = [
                'pin' => $hashedPin,
                'updated_pin' => $updated_pin
            ];

            $userModel->update($phone_number, $updateData);

            return $this->response->setJSON([
                'status' => 'success',
                'code' => 200,
                'message' => 'PIN berhasil disimpan.'
            ])->setStatusCode(200);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 401,
                'message' => 'Akses tidak diizinkan.'
            ])->setStatusCode(401);
        }
    }

    public function loginPin()
    {
        $data = $this->request->getJSON(true);

        $phone_number62 = $data['phone_number'] ?? null;
        $pin = $data['pin'] ?? null;

        if (!$phone_number62 || !$pin) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 404,
                'message' => 'No. telepon tidak terdaftar atau akun sedang dalam status blokir.'
            ])->setStatusCode(404);
        }

        // Normalisasi nomor telepon (62xx → 0xx)
        if (substr($phone_number62, 0, 2) === '62') {
            $phone_number = '0' . substr($phone_number62, 2);
        }

        $userModel = new UserModel();
        $user = $userModel->where('phone_number', $phone_number)->first();

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 404,
                'message' => 'No. telepon tidak terdaftar atau akun sedang dalam status blokir.',
            ])->setStatusCode(404);
        }

        if (!password_verify($pin, $user['pin'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 400,
                'message' => 'PIN yang dimasukkan salah',
            ])->setStatusCode(400);
        }

        // Generate JWT
        $config = config('App');
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600 * 24; // Token berlaku 24 jam
        $token_expiry = date('Y-m-d H:i:s', $expirationTime);

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'phone' => $user['phone_number']
        ];

        $jwt = JWT::encode($payload, $config->jwtSecretKey, 'HS256');

        $tokenData = [
            'token' => $jwt,
            'token_expiry' => $token_expiry
        ];

        $userModel->where('phone_number', $phone_number)->update(null, $tokenData);

        return $this->response->setJSON([
            'status' => 'success',
            'code' => 200,
            'message' => 'Verifikasi PIN berhasil.',
            'token' => $jwt
        ])->setStatusCode(200);
    }
}
