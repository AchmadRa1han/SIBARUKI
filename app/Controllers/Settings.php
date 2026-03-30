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
        $captions = $this->request->getPost('caption');
        $oldImages = $this->request->getPost('old_image');
        $files = $this->request->getFileMultiple('image');

        $carouselData = [];
        $uploadPath = ROOTPATH . 'public/uploads/carousel/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

        foreach ($captions as $index => $caption) {
            $imageUrl = $oldImages[$index] ?? '';

            // Handle New Upload
            if (isset($files[$index]) && $files[$index]->isValid() && !$files[$index]->hasMoved()) {
                $newName = $files[$index]->getRandomName();
                $files[$index]->move($uploadPath, $newName);
                
                // Delete old file if exists and is local
                if (!empty($imageUrl) && str_contains($imageUrl, 'uploads/carousel')) {
                    $oldPath = str_replace(base_url(), ROOTPATH . 'public/', $imageUrl);
                    if (file_exists($oldPath)) @unlink($oldPath);
                }
                
                $imageUrl = base_url('uploads/carousel/' . $newName);
            }

            if (!empty($imageUrl)) {
                $carouselData[] = [
                    'image'   => $imageUrl,
                    'caption' => $caption
                ];
            }
        }

        $settingsModel->saveSetting('carousel_images', json_encode($carouselData));

        return redirect()->back()->with('message', 'Pengaturan carousel berhasil diperbarui.');
    }
}
