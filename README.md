# 🏠 SIBARUKI (Sama-Samaki Bangun Perumahan dan Permukiman)

![CI4](https://img.shields.io/badge/Framework-CI4-orange?style=for-the-badge&logo=codeigniter)
![Tailwind](https://img.shields.io/badge/Styling-Tailwind%20CSS-blue?style=for-the-badge&logo=tailwindcss)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql)
![Status](https://img.shields.io/badge/Status-Development-green?style=for-the-badge)

**SIBARUKI** adalah *Single Data Portal* yang dikembangkan oleh **Dinas Komunikasi, Informatika, dan Persandian** berkolaborasi dengan **Dinas Perumahan, Kawasan Permukiman, dan Pertanahan Kabupaten Sinjai**. Sistem ini dirancang untuk memudahkan akses informasi, monitoring, dan pengambilan keputusan berbasis data di bidang perumahan dan kawasan permukiman secara terpadu.

---

## ✨ Fitur Unggulan Terbaru

### 📊 Monitoring RTLH & RLH
- **Village Recap:** Rekapitulasi data RTLH otomatis per desa/kelurahan untuk memudahkan alokasi bantuan.
- **Auto-Archive RLH:** Data RTLH yang telah tuntas dibantu otomatis pindah ke daftar RLH (Rumah Layak Huni).
- **Transformation History:** Fitur "Before & After" yang menyimpan potret data teknis rumah sebelum dan sesudah menerima bantuan untuk keperluan audit.

### 🗺️ GIS Interaktif (Click-to-Map)
- **Point-to-Pin:** Petugas dapat menentukan lokasi rumah hanya dengan mengklik peta, menggantikan input koordinat manual yang rumit.
- **Polygon Drawing:** Fitur menggambar area kumuh secara interaktif dengan kalkulasi luas otomatis (Hektar).
- **GPS Integration:** Mendukung pengambilan titik lokasi langsung via sensor GPS perangkat.

### 🛡️ Keamanan & User Experience (UX)
- **Floating Sidebar:** Navigasi modern dengan tombol toggle yang menonjol dan memori status otomatis.
- **Mobile First:** Sidebar mode HP dengan efek glassmorphism dan dukungan gestur **Swipe-to-Close**.
- **Audit Trail:** Pencatatan setiap detil perubahan data teknis (Old Value vs New Value).

---

## 🚀 Arsitektur Teknologi

| Layer | Komponen |
| --- | --- |
| **Core Framework** | CodeIgniter 4.6 (Modern PHP) |
| **Styling Engine** | Tailwind CSS v3 |
| **Database** | MySQL with Spatial Support (WKT) |
| **Interactive JS** | Leaflet.js, Leaflet.draw, Lucide Icons |
| **Caching** | LocalStorage for UI States |

---

## 🏛️ Instansi Terkait
- **Dinas Perumahan, Kawasan Permukiman, dan Pertanahan Kab. Sinjai** (Sebagai Pemilik Program)
- **Dinas Komunikasi, Informatika, dan Persandian Kab. Sinjai** (Sebagai Pengembang Sistem)

---
**© 2026 PEMERINTAH KABUPATEN SINJAI**
