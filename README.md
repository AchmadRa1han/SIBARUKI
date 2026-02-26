# 🏠 SIBARUKI (Sama-Samaki Bangun Perumahan dan Permukiman)

![CI4](https://img.shields.io/badge/Framework-CodeIgniter%204.6-orange?style=for-the-badge&logo=codeigniter)
![Tailwind](https://img.shields.io/badge/Styling-Tailwind%20CSS-blue?style=for-the-badge&logo=tailwindcss)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql)

**SIBARUKI** adalah platform pusat data terpadu untuk manajemen dan monitoring program bedah rumah (RTLH) serta pemantauan kawasan permukiman kumuh di Kabupaten Sinjai. Dirancang dengan standar UI/UX modern ("Mewah" Style) untuk memberikan efisiensi maksimal bagi Admin Pusat dan Petugas Lapangan dalam mewujudkan hunian yang layak bagi masyarakat.

---

## ✨ Fitur Unggulan

### 🛡️ Autentikasi & Keamanan (RBAC)
- **Role-Based Access Control:** Pembatasan akses antara **Admin Pusat** dan **Petugas Desa**.
- **Multi-Village Assignment:** Satu petugas dapat ditugaskan untuk mengelola satu atau banyak desa sekaligus melalui integrasi tabel `user_desa`.
- **Data Isolation:** Petugas hanya dapat melihat dan mengelola data di wilayah desa yang ditugaskan secara spesifik kepada mereka.

### 📊 Dashboard Monitoring Real-time
- **Live Stats:** Visualisasi total unit RTLH dan lokasi Wilayah Kumuh secara instan.
- **System Health Check:** Pemantauan status database, beban server, dan indikator sinyal internet secara real-time.
- **Activity Log Detail:** Audit trail yang mencatat setiap perubahan data secara spesifik (siapa, kapan, dan detail kolom apa yang diubah).

### ⚡ UI/UX "Mewah" Experience
- **AJAX Live Search:** Pencarian data instan tanpa refresh halaman (Debounced).
- **Elegant Page Loader:** Transisi antar halaman yang halus dengan efek *glassmorphism* dan dukungan *bfcache*.
- **Persistent Sidebar:** Navigasi cerdas yang mengingat pilihan menu melalui *localStorage*.
- **Modern Form Input:** Menggunakan *Tom Select* untuk pemilihan desa yang searchable dan intuitif.

### 🗺️ GIS & Integrasi Data
- **WKT Support:** Mendukung penyimpanan dan visualisasi koordinat lokasi dalam format *Well-Known Text*.
- **Unified RTLH Management:** Pengelolaan data penerima, profil rumah, dan kondisi fisik bangunan dalam satu alur kerja yang terintegrasi.

---

## 🚀 Teknologi Utama

- **Backend:** CodeIgniter 4 (PHP 8.1+)
- **Frontend:** Tailwind CSS & Lucide Icons
- **Interactive:** Fetch API (Live Search) & Tom Select
- **Database:** MySQL (Relational Mapping)

---

## 👨‍💻 Kontribusi & Lisensi
Aplikasi ini dikembangkan secara eksklusif untuk kebutuhan internal **Dinas Perumahan dan Kawasan Permukiman Kabupaten Sinjai**. Seluruh data dan kode sumber bersifat rahasia sesuai dengan protokol keamanan data instansi.

---
**© 2026 DINAS PERKIM KABUPATEN SINJAI**
