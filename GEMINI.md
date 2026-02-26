# SIBARUKI - Project Guidelines

## Arsitektur Teknologi
- **Framework:** CodeIgniter 4 (PHP).
- **Styling:** Tailwind CSS (dikompilasi via PostCSS ke `public/css/app.css`).
- **Icons:** Lucide Icons (CDN).
- **GIS/Peta:** Leaflet.js dengan dukungan data format WKT (Well-Known Text).

## Standar UI/UX ("Mewah" Style)
- **Tema Warna Utama:** Biru Sangat Gelap (`blue-900` / `blue-950`).
- **Layout Utama:** Dashboard dengan sidebar navigasi di kiri (dengan sistem dropdown untuk daftar tabel) dan header statis.
- **Kartu Informasi:** Menggunakan sudut sangat bulat (`rounded-[2rem]` atau `rounded-3xl`), border abu-abu sangat tipis, dan bayangan lembut (`shadow-sm`).
- **Label Kolom:** Wajib menggunakan warna Biru Gelap (`text-blue-900` atau `text-blue-950`), font tebal (`font-black`), huruf kapital (`uppercase`), dan ukuran kecil (`text-[10px]`).
- **Traffic Light System:**
  - **LAYAK:** Emerald (Hijau) - `bg-emerald-50 text-emerald-700`.
  - **AGAK/MENUJU LAYAK:** Amber (Kuning/Oranye) - `bg-amber-50 text-amber-700`.
  - **TIDAK/KURANG LAYAK:** Rose (Merah) - `bg-rose-50 text-rose-700`.

## Konvensi Database & CRUD
- **RTLH Terpadu:** Pengelolaan 3 tabel (`rtlh_penerima`, `rumah_rtlh`, `rtlh_kondisi_rumah`) disatukan dalam satu Controller (`Rtlh.php`) dan satu form input/edit.
- **Transaksi:** Wajib menggunakan `$db->transStart()` dan `$db->transComplete()` saat menyimpan data yang melibatkan lebih dari satu tabel.
- **Null Safety:** Gunakan operator null coalescing (`??`) pada setiap variabel di View untuk mencegah crash jika data database kosong.
- **Pagination:** Gunakan template kustom `tailwind_full` (25 data per halaman).

## Fitur Khusus
- **Peta GIS:** Menampilkan visualisasi dari kolom `WKT`. Dilengkapi fitur *Auto-Repair* untuk menangani data koordinat yang terpotong dan opsi tampilan Satelit.
- **Detail Laporan:** Halaman detail harus menampilkan **seluruh** kolom yang tersedia di database tanpa kecuali, disusun dalam kategori yang logis.

## Perintah Pengembangan
- run localhost: `php spark serve`
- Build CSS: `npm run build`
- Watch CSS: `npm run watch`
