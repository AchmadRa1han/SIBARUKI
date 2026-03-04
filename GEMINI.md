# SIBARUKI - Project Guidelines

## Arsitektur Teknologi & Keamanan
- **Framework:** CodeIgniter 4 (PHP).
- **Styling:** Tailwind CSS (dikompilasi via PostCSS ke `public/css/app.css`).
- **Icons:** Lucide Icons (CDN).
- **Sistem Hak Akses (RBAC):** Dinamis berbasis tabel `roles`, `permissions`, dan `role_permissions`. 
- **Restriksi Detail:** Data sensitif (NIK, rincian teknis) wajib dilindungi izin khusus (`view_rtlh_detail`, `view_kumuh_detail`).
- **Data Integrity:** Fitur **Recycle Bin** (`trash_data`) untuk menyimpan data terhapus dalam format JSON agar bisa dipulihkan.

## Standar UI/UX ("Mewah" Style)
- **Tema Warna:** Biru Sangat Gelap (`blue-950`).
- **Layout Utama (Data Spasial):** 
  - **Integrated Map & Table:** Peta Interaktif berada di bagian atas, diikuti oleh Tabel Data di bagian bawah dalam satu halaman.
  - **Scroll Preservation:** Wajib menggunakan `localStorage` untuk menyimpan posisi scroll elemen `#main-content` saat auto-refresh atau pindah halaman pagination.
  - **Dynamic Sorting:** Header tabel harus mendukung pengurutan Ascending/Descending via klik judul kolom tanpa menghilangkan state filter lain.
- **Peta & Geospasial:** 
  - **Marker Clustering:** Wajib menggunakan `Leaflet.markercluster` untuk data titik yang banyak.
  - **Satellite Toggle:** Peta harus memiliki kontrol untuk berpindah antara layer standard (CartoDB) dan Satellite (Esri).
  - **Focus Map:** Tombol aksi pada tabel wajib memiliki fungsi `focusMap` yang melakukan auto-zoom ke objek dan auto-scroll ke kontainer peta.
- **Sidebar & Navigasi:** 
  - **Scroll Memory:** Posisi scroll sidebar tersimpan via `localStorage`.
  - **Auto-Open:** Dropdown otomatis terbuka berdasarkan rute URL aktif.
  - **Hub Pengaturan:** Menu manajemen dipusatkan dalam halaman `/settings`.
- **Kartu Informasi:** Sudut sangat bulat (`rounded-[2.5rem]`), border tipis, dan bayangan lembut (`shadow-sm`).
- **Dark Mode:** Didukung penuh (`dark:`) dengan persistensi tema.

## Peta & Geospasial
- **Engine:** Leaflet.js (CDN JSDelivr).
- **WKT Parser:** Menggunakan logika parser mandiri (Zero-dependency) di sisi JavaScript untuk stabilitas tinggi.
- **Visualisasi:** 
  - Poligon otomatis berubah warna berdasarkan skor (Merah: Berat, Oranye: Sedang, Kuning: Ringan).
  - Dilengkapi Legenda melayang (*Floating Legend*) dan popup detail interaktif.

## Monitoring & Audit
- **Audit Trail:** Log detail "Data Diff" (membandingkan nilai lama vs baru).
- **Security Alarm:** Pelacakan login gagal dan deteksi aktivitas mencurigakan.
- **System Health:** Monitoring beban CPU, status Database, dan Disk Server secara real-time.

## Konvensi Pengembangan
- **CRUD Terpadu:** Pengelolaan data lintas tabel wajib menggunakan Transaksi Database (`$db->transStart()`).
- **Security Check:** Validasi izin di Controller menggunakan helper `has_permission('nama_izin')`.
- **Filtering:** Mengutamakan **Live Search** pada header untuk akses data cepat. Filter lanjutan disembunyikan jika tidak diperlukan untuk menjaga kebersihan UI.
- **Pagination:** Template kustom `tailwind_full` dengan posisi **Tengah (Center)**.

## Perintah Pengembangan
- Run Localhost: `php spark serve`
- Build CSS: `npm run build`
- Update RBAC: `php spark db:seed RbacSeeder`
- Update Koordinat: `php spark wkt:update` (dari output.csv)
- Fix Data WKT: `php spark wkt:fix`
