# 🏠 SIBARUKI (Sama-Samaki Bangun Perumahan dan Permukiman)

![CI4](https://img.shields.io/badge/Framework-CI4-orange?style=for-the-badge&logo=codeigniter)
![Tailwind](https://img.shields.io/badge/Styling-Tailwind%20CSS-blue?style=for-the-badge&logo=tailwindcss)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql)
![Status](https://img.shields.io/badge/Status-Development-green?style=for-the-badge)

**SIBARUKI** adalah *Single Data Portal* yang dikembangkan oleh **Dinas Komunikasi, Informatika, dan Persandian** berkolaborasi dengan **Dinas Perumahan, Kawasan Permukiman, dan Pertanahan Kabupaten Sinjai**. Sistem ini dirancang untuk memudahkan akses informasi, monitoring, dan pengambilan keputusan berbasis data di bidang perumahan dan kawasan permukiman secara terpadu.

---

## 🎯 Tujuan Sistem
- **Pusat Data Terpadu:** Menyediakan satu sumber data valid untuk informasi perumahan, kawasan permukiman, dan pertanahan di Kabupaten Sinjai.
- **Monitoring & Evaluasi:** Memfasilitasi pelaporan dan pemantauan Rumah Tidak Layak Huni (RTLH), backlog perumahan, serta program strategis dinas lainnya.
- **Akuntabilitas:** Meningkatkan transparansi melalui visualisasi data berbasis lokasi (GIS) dan audit trail aktivitas pengguna.

---

## ✨ Fitur Unggulan

### 📊 Single Data Portal & Monitoring
- **Dashboard Analisis:** Pemantauan statistik RTLH dan Wilayah Kumuh secara real-time dengan indikator kesehatan sistem (Database, Server Load, & Latensi Sinyal).
- **Audit Trail (Log Aktivitas):** Pencatatan detail setiap perubahan data secara spesifik (siapa, kapan, dan detail kolom apa yang diubah).

### 🛡️ Manajemen Pengguna & Keamanan (RBAC)
- **Role-Based Access Control:** Diferensiasi hak akses yang ketat antara Admin Pusat dan Petugas Lapangan.
- **Smart Assignment:** Fitur penugasan wilayah kerja yang memungkinkan satu petugas mengelola satu atau banyak desa menggunakan pencarian *Tom Select* yang intuitif.
- **Data Isolation:** Isolasi data otomatis yang memastikan petugas hanya dapat melihat dan mengelola data di wilayah desa penugasannya.

### ⚡ UI/UX "Mewah" Experience
- **AJAX Live Search:** Pencarian data dinamis tanpa *refresh* halaman menggunakan Fetch API untuk efisiensi kerja tinggi.
- **Glassmorphism Loader:** Transisi halaman yang halus dengan efek blur modern dan dukungan *Back-Forward Cache*.
- **Persistent Navigation:** Sidebar cerdas yang mengingat status menu terakhir pengguna melalui *LocalStorage*.

### 🗺️ GIS & Peta Digital
- **Spatial Data Integration:** Mendukung penyimpanan koordinat format *Well-Known Text* (WKT) untuk pemetaan titik lokasi RTLH dan zonasi wilayah kumuh.

---

## 🚀 Arsitektur Teknologi

| Layer | Komponen |
| --- | --- |
| **Core Framework** | CodeIgniter 4 (Modern PHP Framework) |
| **Styling Engine** | Tailwind CSS (Utility-First Framework) |
| **Database** | MySQL (Relational Management) |
| **Interactive JS** | Fetch API, Tom Select, Lucide Icons |
| **Mapping Engine** | Leaflet.js (GIS Visualization) |

---

## 🏛️ Instansi Terkait
- **Dinas Perumahan, Kawasan Permukiman, dan Pertanahan Kab. Sinjai** (Sebagai Pemilik Program)
- **Dinas Komunikasi, Informatika, dan Persandian Kab. Sinjai** (Sebagai Pengembang Sistem)

---
**© 2026 PEMERINTAH KABUPATEN SINJAI**
