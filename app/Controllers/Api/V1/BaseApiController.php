<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class BaseApiController extends ResourceController
{
    use ResponseTrait;

    protected $format = 'json';

    /**
     * Helper untuk response sukses yang seragam
     */
    protected function respondSuccess($data = null, $message = 'Success', $code = 200)
    {
        return $this->respond([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Helper untuk response error yang seragam
     */
    protected function respondError($message = 'Error', $code = 400)
    {
        return $this->respond([
            'status' => false,
            'message' => $message
        ], $code);
    }

    /**
     * Mendapatkan data user dari token JWT yang sudah di-decode oleh Filter
     */
    protected function getUserData()
    {
        return $this->request->user ?? null;
    }
}
