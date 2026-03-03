<?php

namespace App\Controllers;

class Placeholder extends BaseController
{
    public function psu() { return $this->render('Sebaran PSU Terbangun'); }
    public function formal() { return $this->render('Sebaran Perumahan Formal'); }
    public function bansos() { return $this->render('Sebaran Bantuan Sosial Perbaikan RTLH'); }
    public function pisew() { return $this->render('Sebaran PISEW'); }
    public function arsinum() { return $this->render('Sebaran Arsinum'); }
    public function aset_tanah() { return $this->render('Sebaran Aset Tanah Pemda'); }

    private function render($title)
    {
        return view('placeholder', [
            'title' => $title,
            'message' => "Halaman <b>$title</b> sedang dalam tahap pengembangan. Fitur manajemen data dan pemetaan akan segera tersedia."
        ]);
    }
}
