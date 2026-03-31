<?php

namespace App\Controllers;

use App\Models\SettingsModel;

class Settings extends BaseController
{
    public function index()
    {
        // Pastikan hanya yang punya minimal satu izin akses sistem yang bisa masuk
        if (!has_permission('manage_users') && !has_permission('manage_roles')) {
            return redirect()->to('/dashboard')->with('message', 'Akses ke menu Pengaturan ditolak.');
        }

        $data = [
            'title' => 'Pusat Pengaturan Sistem',
        ];

        return view('settings/index', $data);
    }

    /**
     * Pengaturan Carousel Landing Page
     */
    public function carousel()
    {
        if (!has_permission('manage_roles')) {
            return redirect()->to('/dashboard')->with('message', 'Akses ditolak.');
        }

        $settingsModel = new SettingsModel();
        $carouselJson = $settingsModel->getSetting('carousel_images', '[]');

        $data = [
            'title'    => 'Pengaturan Carousel',
            'carousel' => json_decode($carouselJson, true)
        ];

        return view('settings/carousel', $data);
    }

    /**
     * Update Carousel Data
     */
    public function updateCarousel()
    {
        $settingsModel = new SettingsModel();
        $captions = $this->request->getPost('caption') ?? [];
        $oldImages = $this->request->getPost('old_image') ?? [];
        
        // Ambil SEMUA file yang diupload (baik index baru atau lama)
        $uploadedFiles = $this->request->getFiles();

        $carouselData = [];
        $uploadPath = FCPATH . 'uploads/carousel/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

        foreach ($captions as $index => $caption) {
            $imageUrl = $oldImages[$index] ?? '';

            // Cari file di dalam array multidimensional Files CI4
            // Formatnya biasanya image[index]
            $file = null;
            if (isset($uploadedFiles['image'][$index])) {
                $file = $uploadedFiles['image'][$index];
            }

            // Jika ada upload file baru untuk index ini
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);
                
                // HAPUS file lama secara fisik jika ada penggantian
                if (!empty($imageUrl)) {
                    $oldPath = FCPATH . str_replace(base_url(), '', $imageUrl);
                    if (file_exists($oldPath)) @unlink($oldPath);
                }
                
                $imageUrl = 'uploads/carousel/' . $newName;
            }

            // Hanya simpan jika ada gambar (baik yang baru atau yang lama tetap dipertahankan)
            if (!empty($imageUrl)) {
                $relativePath = str_replace(base_url(), '', $imageUrl);
                $relativePath = ltrim($relativePath, '/');

                $carouselData[] = [
                    'image'   => $relativePath,
                    'caption' => $caption
                ];
            }
        }

        $settingsModel->saveSetting('carousel_images', json_encode($carouselData));

        return redirect()->back()->with('message', 'Pengaturan carousel berhasil diperbarui.');
    }
}
