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
- **Sidebar & Navigasi:** 
  - **Scroll Memory:** Posisi scroll sidebar tersimpan via `localStorage`.
  - **Auto-Open:** Dropdown otomatis terbuka berdasarkan rute URL aktif.
  - **Hub Pengaturan:** Menu manajemen (User, Role, Log, Trash) dipusatkan dalam satu halaman khusus (`/settings`), bukan dropdown sidebar.
- **Kartu Informasi:** Sudut sangat bulat (`rounded-[2.5rem]`), border tipis, dan bayangan lembut (`shadow-sm`).
- **Notifikasi:** **Toast Notification** melayang (pojok kanan atas) untuk feedback aksi.
- **Konfirmasi:** Wajib menggunakan **Custom Confirmation Modal** (`customConfirm`) untuk aksi krusial.
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
