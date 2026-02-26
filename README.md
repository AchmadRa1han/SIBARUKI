# 🏠 SIBARUKI (*Sama-Samaki Bangun Perumahan dan Permukiman*)

![CI4](https://img.shields.io/badge/Framework-CodeIgniter%204.6-orange?style=for-the-badge&logo=codeigniter)
![Tailwind](https://img.shields.io/badge/Styling-Tailwind%20CSS-blue?style=for-the-badge&logo=tailwindcss)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql)

**SIBARUKI** adalah platform pusat data terpadu untuk manajemen dan monitoring program bedah rumah (RTLH) serta pemantauan kawasan permukiman kumuh di Kabupaten Sinjai. Dirancang dengan standar UI/UX modern ("Mewah" Style) untuk memberikan efisiensi maksimal bagi Admin Pusat dan Petugas Lapangan.

---

## ✨ Fitur Unggulan

### 🛡️ Autentikasi & Keamanan (RBAC)
- **Role-Based Access Control:** Pembatasan akses antara **Admin Pusat** dan **Petugas Desa**.
- **Multi-Village Assignment:** Satu petugas dapat ditugaskan untuk mengelola satu atau banyak desa sekaligus.
- **Data Isolation:** Petugas hanya dapat melihat dan mengelola data di wilayah desa yang ditugaskan kepada mereka.

### 📊 Dashboard Monitoring Real-time
- **Live Stats:** Visualisasi total unit RTLH dan lokasi Wilayah Kumuh secara instan.
- **System Health Check:** Pemantauan status database, beban server, dan indikator sinyal internet secara real-time.
- **Activity Log Detail:** Audit trail yang mencatat setiap perubahan data secara spesifik (siapa, kapan, dan apa yang diubah).

### ⚡ UI/UX "Mewah" Experience
- **AJAX Live Search:** Pencarian data instan tanpa refresh halaman (Debounced).
- **Elegant Page Loader:** Transisi antar halaman yang halus dengan efek *glassmorphism*.
- **Persistent Sidebar:** Navigasi cerdas yang mengingat pilihan menu Anda melalui *localStorage*.
- **Modern Form Input:** Menggunakan *Tom Select* untuk pemilihan desa yang searchable dan intuitif.

### 🗺️ GIS & Integrasi Data
- **WKT Support:** Mendukung penyimpanan koordinat lokasi dalam format *Well-Known Text*.
- **Unified RTLH Management:** Pengelolaan data penerima, profil rumah, dan kondisi fisik bangunan dalam satu pintu.

---

## 🚀 Teknologi yang Digunakan

| Komponen | Teknologi |
| --- | --- |
| **Backend** | PHP 8.1+ / CodeIgniter 4 |
| **Frontend** | Tailwind CSS (Compiled via PostCSS) |
| **Database** | MySQL / MariaDB |
| **Icons** | Lucide Icons |
| **Libraries** | Tom Select (Dropdown), Leaflet.js (Maps) |

---

## 📦 Instalasi & Persiapan

1. **Clone Repositori**
   ```bash
   git clone https://github.com/username/sibaruki.git
   cd sibaruki
   ```

2. **Instal Dependensi**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin file `env` menjadi `.env` dan sesuaikan pengaturan database Anda.
   ```bash
   cp env .env
   ```

4. **Migrasi & Seeding**
   Siapkan struktur database dan data awal (Admin & Roles).
   ```bash
   php spark migrate
   php spark db:seed AuthSeeder
   ```

5. **Build Aset CSS**
   ```bash
   npm run build
   ```

6. **Jalankan Aplikasi**
   ```bash
   php spark serve
   ```

---

## 🔑 Akun Akses Default (Development)

- **Admin:** `admin` / `password123`
- **Petugas:** `petugas_a` / `password123`

---

## 👨‍💻 Kontribusi
Aplikasi ini dikembangkan untuk kebutuhan Dinas Perkim Kabupaten Sinjai. Kontribusi bersifat tertutup sesuai dengan protokol keamanan data instansi.

---
**© 2026 DINAS PERKIM KABUPATEN SINJAI**
