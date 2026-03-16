<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class ApiAuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $key = getenv('JWT_SECRET') ?: 'sibaruki_secret_key_2026';
        $header = $request->getServer('HTTP_AUTHORIZATION');

        if (!$header) {
            return service('response')
                ->setJSON([
                    'status' => false,
                    'message' => 'Token tidak ditemukan (Authorization Header missing)'
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $token = str_replace('Bearer ', '', $header);

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            
            // Simpan data user ke request agar bisa diakses di controller
            $request->user = $decoded;

        } catch (Exception $e) {
            return service('response')
                ->setJSON([
                    'status' => false,
                    'message' => 'Token tidak valid atau kadaluarsa: ' . $e->getMessage()
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
