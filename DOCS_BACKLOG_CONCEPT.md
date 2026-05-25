# RFC: Transformasi Manajemen Backlog (Individu)
**Status:** Draft / Conceptual
**Role:** Senior Developer Alignment

## 1. Latar Belakang & Masalah
Konsep Backlog saat ini di sistem SIBARUKI masih menggunakan angka agregat (statistik per desa). Untuk meningkatkan akurasi data intervensi perumahan, konsep ini diubah menjadi **Data Individu (By Name By Address)** yang mencakup informasi hubungan tempat tinggal saat ini.

## 2. Arsitektur Data Baru (`backlog_individu`)

Tabel ini akan menggantikan peran `perumahan_backlog_agregat` yang bersifat agregat.

| Kolom | Tipe Data | Deskripsi |
| :--- | :--- | :--- |
| `id` | INT (PK, AI) | Identitas unik record. |
| `nik` | VARCHAR(16) | NIK calon penerima (Wajib 16 digit). |
| `no_kk` | VARCHAR(16) | Nomor KK calon penerima. |
| `nama_lengkap` | VARCHAR(255) | Nama lengkap individu terdaftar backlog. |
| `desa_id` | VARCHAR(50) | FK ke master wilayah (untuk filter RBAC). |
| `alamat_detail` | TEXT | Lokasi tempat tinggal saat ini. |
| **`nama_pemilik_rumah`** | VARCHAR(255) | **Konsep Baru:** Nama pemilik rumah tempat individu menumpang/sewa. |
| `keterangan_hunian` | VARCHAR(100) | Misal: 'Sewa', 'Menumpang Keluarga', 'Asrama'. |
| `tahun_data` | VARCHAR(4) | Tahun pencatatan data. |
| `created_at` | DATETIME | Audit trail. |
| `updated_at` | DATETIME | Audit trail. |

## 3. Rencana Implementasi (Technical Roadmap)

### A. Database (Migration)
- Membuat file migrasi baru `CreateBacklogIndividuTable`.
- Menyiapkan logika *fallback* pada dashboard agar tetap menampilkan angka 0 jika tabel belum ada atau kosong.

### B. Controller (`app/Controllers/Rtlh.php`)
- **`backlog()`**: Mengubah query dari `SELECT SUM` pada tabel agregat menjadi `SELECT COUNT` pada tabel individu.
- **`storeBacklogIndividu()`**: Menangani input data individu baru.
- **`updateBacklogIndividu()`**: Menangani pembaruan data (Edit).
- **`deleteBacklogIndividu()`**: Menghapus record individu secara aman.

### C. UI/UX (`app/Views/rtlh/backlog.php`)
- **Index View:** Menggunakan DataTables untuk menampilkan list individu.
- **Form View:** Menambahkan input khusus untuk `nama_pemilik_rumah` sesuai request user.
- **Dashboard Widget:** Menyesuaikan widget statistik di `Home.php` agar sinkron dengan data individu.

## 4. Keamanan & Integritas Sistem
1. **Pencegahan Redundansi:** Sebelum insert, sistem harus mengecek apakah NIK sudah terdaftar di modul RTLH (Penerima) untuk menghindari duplikasi status.
2. **Scoping:** Admin Desa hanya diberikan akses *Create/Read/Update/Delete* untuk individu di dalam `desa_id` mereka sendiri.
3. **Data Diff (Logs):** Setiap perubahan pada data individu wajib masuk ke tabel `sys_logs` untuk audit trail forensik.

---
*Dokumen ini dibuat untuk menjaga konsistensi rencana sebelum eksekusi dimulai.*
