# SIBARUKI - Project Guidelines

## Arsitektur Teknologi & Keamanan
- **Framework:** CodeIgniter 4 (PHP).
- **Styling:** Tailwind CSS (dikompilasi via PostCSS ke `public/css/app.css`).
- **Icons:** Lucide Icons (CDN).
- **Sistem Hak Akses (RBAC):** Dinamis berbasis tabel `roles`, `permissions`, dan `role_permissions`. 
- **Restriksi Detail:** Data sensitif (NIK, rincian teknis) wajib dilindungi izin khusus (`view_rtlh_detail`, `view_kumuh_detail`).
- **Data Integrity:** Fitur **Recycle Bin** (`trash_data`) untuk menyimpan data terhapus dalam format JSON agar bisa dipulihkan.
- **Auto-Archiving:** Seluruh operasi penghapusan (Delete & Bulk Delete) wajib memindahkan data ke `trash_data` sebelum dihapus dari tabel utama.

## Standar UI/UX ("Mewah" Style)
- **Tema Warna:** Biru Sangat Gelap (`blue-950`).
- **Layout Utama (Dashboard):**
  - **Tactical Command Center:** Fokus pada visualisasi data terpadu dari 7 tabel master.
  - **Clickable Metrics:** Kartu statistik di dashboard berfungsi sebagai navigasi langsung ke tabel data terkait.
- **Layout Utama (Data Spasial):** 
  - **Integrated Map & Table:** Peta Interaktif berada di bagian atas, diikuti oleh Tabel Data di bawahnya.
- **Sidebar & Navigasi:** 
  - **Floating Toggle:** Tombol buka/tutup sidebar yang menonjol di tengah (`h-52`) untuk aksesibilitas maksimal.
- **Bulk Action Bar:** UI terapung (floating) berwarna biru yang muncul otomatis saat baris tabel dicentang untuk aksi massal.

## Peta & Geospasial
- **Engine:** Leaflet.js & Leaflet.draw.
- **Interactive Picking:** 
  - **Click-to-Pin:** Input koordinat titik (RTLH) dilakukan dengan mengklik langsung pada peta.
  - **Polygon Drawing:** Input wilayah (Kumuh) menggunakan toolbar gambar interaktif dengan kalkulasi luas area otomatis.
- **Visualisasi:** Poligon otomatis berubah warna berdasarkan skor. Default tampilan peta menggunakan mode **Satelit** untuk akurasi posisi bangunan.

## Monitoring, Audit & Histori
- **Audit Trail:** Log detail mencakup "Data Diff" (lama vs baru), IP Address, User Agent, dan Latency.
- **Export Logging:** Aktivitas ekspor data (Excel/PDF) wajib dicatat dalam Audit Trail dengan aksi `'Export Excel'`.
- **Histori Transformasi (RLH):** Pencatatan snapshot data teknis sebelum dan sesudah bantuan (RTLH ➔ RLH).

## Konvensi Pengembangan
- **CRUD Terpadu:** Pengelolaan data lintas tabel wajib menggunakan Transaksi Database (`$db->transStart()`).
- **Robust CSV Import:** 
  - Wajib mendukung deteksi delimiter otomatis (`,` atau `;`).
  - Wajib menyertakan logika **Auto-Reset ID** (`ALTER TABLE ... AUTO_INCREMENT = 1`) jika tabel kosong saat import.
  - Wajib melakukan pembersihan data (NIK, Anggaran, Luas) dari karakter non-numerik.
- **Testing:** Seluruh siklus CRUD utama wajib memiliki pengujian E2E menggunakan **Cypress**.

## Perintah Pengembangan
- Run Localhost: `php spark serve`
- Build CSS: `npm run build`
- Run Testing: `npx cypress run`
- Truncate RTLH: `php spark db:truncate_rtlh`
- Truncate Aset: `php spark db:truncate_aset`
- Fix Data WKT: `php spark wkt:fix`
