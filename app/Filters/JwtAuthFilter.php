<?php


namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\Services;

class JwtAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return Services::response()
                ->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])
                ->setStatusCode(401);
        }

        $token = $matches[1];

        try {
            $key = config('App')->jwtSecretKey;
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            // Cek expired
            if (isset($decoded->exp) && $decoded->exp < time()) {
                return Services::response()
                    ->setJSON([
                        'status' => 'error',
                        'code' => 401,
                        'message' => 'Akses tidak diizinkan.'
                    ])
                    ->setStatusCode(401);
            }

            // Simpan user info ke request agar bisa diakses controller
            $request->user = $decoded;

        } catch (\Exception $e) {
            return Services::response()
                ->setJSON([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Akses tidak diizinkan.'
                ])
                ->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
