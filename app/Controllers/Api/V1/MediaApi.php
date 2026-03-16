<?php

namespace App\Controllers\Api\V1;

class MediaApi extends BaseApiController
{
    /**
     * POST /api/v1/media/upload
     * Mengunggah file (Multipart Form Data)
     */
    public function upload()
    {
        $validationRule = [
            'file' => [
                'label' => 'Image File',
                'rules' => [
                    'uploaded[file]',
                    'is_image[file]',
                    'mime_in[file,image/jpg,image/jpeg,image/png,image/webp]',
                    'max_size[file,5120]', // Maks 5MB
                ],
            ],
        ];

        if (!$this->validate($validationRule)) {
            return $this->respondError($this->validator->getErrors());
        }

        $img = $this->request->getFile('file');

        if (!$img->hasMoved()) {
            // Tentukan folder penyimpanan (misal di writable/uploads/mobile)
            $newPath = 'mobile/' . date('Ymd');
            $newName = $img->getRandomName();
            $img->move(WRITABLE_PATH . 'uploads/' . $newPath, $newName);

            $data = [
                'file_name' => $newName,
                'file_path' => $newPath . '/' . $newName,
                'full_url' => base_url('uploads/' . $newPath . '/' . $newName),
                'file_type' => $img->getClientMimeType(),
                'file_size' => $img->getSizeByUnit('kb') . ' KB'
            ];

            return $this->respondSuccess($data, 'File berhasil diunggah');
        }

        return $this->respondError('Gagal memindahkan file ke server');
    }
}
