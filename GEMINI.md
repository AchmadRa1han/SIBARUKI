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
- **Layout Utama (Dashboard):**
  - **Tactical Command Center:** Fokus pada visualisasi data terpadu dari 7 tabel master.
  - **Clickable Metrics:** Kartu statistik di dashboard berfungsi sebagai navigasi langsung ke tabel data terkait.
- **Layout Utama (Data Spasial):** 
  - **Integrated Map & Table:** Peta Interaktif berada di bagian atas, diikuti oleh Tabel Data di bawahnya.
- **Sidebar & Navigasi:** 
  - **Floating Toggle:** Tombol buka/tutup sidebar yang menonjol di tengah (`h-52`) untuk aksesibilitas maksimal.
  - **Scroll & State Memory:** Posisi scroll dan status (collapsed/expanded) tersimpan via `localStorage`.
  - **Mobile Responsive:** Sidebar mode HP menggunakan efek **Glassmorphism**, dukungan **Swipe-to-Close**, dan overlay backdrop.
- **Kartu Informasi:** Sudut sangat bulat (`rounded-[2.5rem]`), border tipis, dan bayangan lembut (`shadow-sm`).
- **Dark Mode:** Didukung penuh (`dark:`) dengan persistensi tema.

## Peta & Geospasial
- **Engine:** Leaflet.js & Leaflet.draw.
- **Interactive Picking:** 
  - **Click-to-Pin:** Input koordinat titik (RTLH) dilakukan dengan mengklik langsung pada peta atau menggunakan GPS.
  - **Polygon Drawing:** Input wilayah (Kumuh) menggunakan toolbar gambar interaktif dengan kalkulasi luas area otomatis.
- **Visualisasi:** 
  - Poligon otomatis berubah warna berdasarkan skor.
  - WKT Auto-Repair untuk memastikan validitas geometri yang disimpan ke database.
  - Satellite Toggle untuk akurasi posisi bangunan.

## Monitoring, Audit & Histori
- **Audit Trail:** Log detail "Data Diff" (membandingkan nilai lama vs baru).
- **Histori Transformasi (RLH):** 
  - Pencatatan snapshot data teknis sebelum dan sesudah bantuan (RTLH ➔ RLH).
  - Penyimpanan snapshot dalam format JSON untuk perbandingan *Side-by-Side*.
  - Akses melalui menu **Pengaturan ➔ Histori Perubahan (RLH)**.

## Konvensi Pengembangan
- **CRUD Terpadu:** Pengelolaan data lintas tabel wajib menggunakan Transaksi Database (`$db->transStart()`).
- **Status Bantuan:** Modul RTLH wajib memfilter data dengan `status_bantuan = 'Belum Menerima'` secara default untuk menjaga kebersihan daftar target.
- **Security Check:** Validasi izin di Controller menggunakan helper `has_permission('nama_izin')`.

## Perintah Pengembangan
- Run Localhost: `php spark serve`
- Build CSS: `npm run build`
- Update RBAC: `php spark db:seed RbacSeeder`
- Update Koordinat: `php spark wkt:update`
- Fix Data WKT: `php spark wkt:fix`
