# SIBARUKI - Blueprint Arsitektur & Kamus Data (Data Dictionary)

Dokumen ini adalah **Spesifikasi Arsitektur Tingkat Rendah** untuk sistem SIBARUKI (Sistem Informasi Bantuan Rumah & Kawasan Permukiman). Seluruh pengembangan lanjutan, query manual, dan integrasi API wajib merujuk pada struktur tabel di bawah ini.

---

## 1. Panduan Instalasi & Setup Pertama Kali (Post-Clone)
Ikuti langkah-langkah di bawah ini setelah melakukan `git clone` agar sistem berjalan sempurna di lingkungan lokal Anda:

### 1.1. Instalasi Dependency
SIBARUKI menggunakan PHP (Composer) dan Node.js (NPM).
```bash
# Instal library Backend (CodeIgniter, PhpSpreadsheet, JWT, dll)
composer install

# Instal library Frontend (Tailwind CSS, Lucide Icons, dll)
npm install
```

### 1.2. Konfigurasi Environment (`.env`)
1. Salin file `env` menjadi `.env`.
2. Buka file `.env` dan sesuaikan pengaturan berikut:
   - `CI_ENVIRONMENT = development` (Agar error terlihat jelas saat pembangunan).
   - `app.baseURL = 'http://localhost:8080/'` (Sesuaikan dengan URL lokal Anda).
   - **Database:** Isi `database.default.hostname`, `database.default.database`, `username`, dan `password`.
   - **JWT Secret:** Tambahkan baris `JWT_SECRET = 'bebas_isi_apa_saja_untuk_rahasia_token_api'`.

### 1.3. Persiapan Database & Asset
1. Buat database kosong di MySQL dengan nama `sibaruki`.
2. Jalankan migrasi dan seeder untuk struktur tabel dan data awal:
   ```bash
   php spark migrate
   php spark db:seed MainSeeder
   ```
3. Buat folder untuk penyimpanan file dokumentasi:
   ```bash
   mkdir -p public/uploads/rtlh
   ```
4. Kompilasi Tailwind CSS (Agar tampilan "Mewah" aktif):
   ```bash
   npm run build
   ```

---

## 2. Arsitektur Teknologi & Core Stack
- **Backend Framework:** CodeIgniter 4.6.x (PHP 8.1+).
- **Frontend Styling:** Tailwind CSS v3 (Custom JIT Build dengan prefix `dark:` support).
- **Interaktivitas:** Vanilla JS (ES6+) dengan pola `async/await` dan Fetch API.
- **Database:** MySQL 8.0+ dengan dukungan ekstensi Spasial (`ST_GeomFromText`, `ST_AsText`).
- **Autentikasi:** 
  - Web: Session-based (`AuthFilter`).
  - Mobile/API: JWT - JSON Web Token (`ApiAuthFilter` via `.env`).

---

## 3. Kamus Data (Data Dictionary) - Struktur Tabel Master

### 3.1. Modul RTLH (Triad Relasional)
Data RTLH wajib dipecah menjadi 3 tabel untuk memastikan normalisasi data personal, spasial, dan teknis.

**Tabel `rtlh_penerima` (Data Personal)**
- `nik` (VARCHAR 16) - **PRIMARY KEY**
- `no_kk` (VARCHAR 16)
- `nama_kepala_keluarga` (VARCHAR)
- `tempat_lahir` (VARCHAR)
- `tanggal_lahir` (DATE)
- `jenis_kelamin` (ENUM: 'L', 'P')
- `pendidikan_id` (INT - *Ref to ref_master*)
- `pekerjaan_id` (INT - *Ref to ref_master*)
- `penghasilan_per_bulan` (VARCHAR)
- `jumlah_anggota_keluarga` (INT)

**Tabel `rtlh_rumah` (Data Hunian & Geospasial)**
- `id_survei` (INT Auto Increment) - **PRIMARY KEY**
- `nik_pemilik` (VARCHAR 16) - *Foreign Key ke rtlh_penerima*
- `desa` (VARCHAR), `desa_id` (VARCHAR)
- `alamat_detail` (TEXT)
- `kepemilikan_rumah`, `aset_rumah_di_lokasi_lain`, `kepemilikan_tanah` (VARCHAR)
- `sumber_penerangan`, `sumber_penerangan_detail` (VARCHAR)
- `bantuan_perumahan` (VARCHAR)
- `jenis_kawasan`, `fungsi_ruang` (VARCHAR)
- `luas_rumah_m2`, `luas_lahan_m2` (FLOAT)
- `jumlah_penghuni_jiwa` (INT)
- `sumber_air_minum`, `jarak_sam_ke_tpa_tinja` (VARCHAR)
- `kamar_mandi_dan_jamban`, `jenis_jamban_kloset`, `jenis_tpa_tinja` (VARCHAR)
- `lokasi_koordinat` (GEOMETRY - *WKT POINT*)
- `status_bantuan` (ENUM: 'Belum Menerima', 'Sudah Menerima')
- `tahun_bansos`, `status_backlog`, `desil_nasional` (VARCHAR)
- **Dokumentasi Visual:** `foto_depan`, `foto_samping`, `foto_belakang`, `foto_dalam` (VARCHAR 255 - *Path file*)

**Tabel `rtlh_kondisi_rumah` (Penilaian Teknis)**
- `id_survei` (INT) - **PRIMARY KEY** (*Relasi 1:1 dengan rtlh_rumah*)
- *Semua kolom di bawah berisi INT yang merujuk ke tabel `ref_master`:*
- Struktur: `st_pondasi`, `st_kolom`, `st_balok`, `st_sloof`, `st_rangka_atap`, `st_plafon`, `st_jendela`, `st_ventilasi`
- Material & Kondisi Penutup: `mat_lantai`, `st_lantai`, `mat_dinding`, `st_dinding`, `mat_atap`, `st_atap`

### 3.2. Modul Infrastruktur (Permukiman)
**Tabel `arsinum` (Air Siap Minum)**
- `id` (PK), `jenis_pekerjaan`, `volume`, `kecamatan`, `desa`, `pelaksana`, `anggaran`, `sumber_dana`, `koordinat`, `tahun`

**Tabel `pisew` (Pengembangan Infrastruktur Sosial Ekonomi)**
- `id` (PK), `jenis_pekerjaan`, `lokasi_desa`, `kecamatan`, `pelaksana`, `anggaran`, `sumber_dana`, `tahun`, `koordinat`

**Tabel `psu_jalan` (Prasarana, Sarana, Utilitas)**
- `id` (PK), `id_csv`, `nama_jalan`, `jalan`, `wkt` (GEOMETRY - *WKT LINESTRING*)

**Tabel `perumahan_formal`**
- `id` (PK), `nama_perumahan`, `luas_kawasan_ha`, `longitude`, `latitude`, `pengembang`, `tahun_pembangunan`, `wkt` (GEOMETRY - *WKT POLYGON*)

**Tabel `wilayah_kumuh`**
- `FID` (PK - *Harus sesuai dengan shapefile GIS*), `Provinsi`, `Kode_Prov`, `Kab_Kota`, `Kode_Kab`, `Kecamatan`, `Kode_Kec`, `Kelurahan`, `desa_id`, `Kode_Kel`, `Kode_RT_RW`, `Luas_kumuh`, `skor_kumuh`, `Sumber_data`, `Sk_Kumuh`, `Kawasan`, `WKT` (GEOMETRY)

### 3.3. Modul Aset & Pertanahan
**Tabel `aset_tanah`**
- `id` (PK), `no_sertifikat`, `nama_pemilik`, `luas_m2`, `lokasi`, `desa_kelurahan`, `kecamatan`, `tgl_terbit`, `nomor_hak`, `peruntukan`, `koordinat` (GEOMETRY), `nilai_aset`, `status_tanah`, `keterangan`

### 3.4. Sistem Referensi & Keamanan
**Tabel `ref_master` (Pusat Kamus ID)**
- `id` (PK), `kategori` (VARCHAR - e.g., 'PENDIDIKAN', 'KONDISI', 'MATERIAL_ATAP'), `nama_pilihan` (VARCHAR).
- *Catatan: Tidak boleh dihapus karena berelasi dengan ID numerik di tabel RTLH.*

**Tabel `users` & RBAC (Role-Based Access Control)**
- `users`: `id` (PK), `username`, `password` (Bcrypt), `instansi`, `role_id`
- `roles`: `id` (PK), `role_name`, `scope` (Kecamatan/Desa/Global)
- `user_desa` (Pivot): `user_id`, `desa_id`
- `permissions`: `id` (PK), `permission_name`, `description`
- `role_permissions` (Pivot): `role_id`, `permission_id`

### 3.5. Sistem Audit & Pemulihan
**Tabel `sys_logs` (Forensic Audit Trail)**
- `id` (PK), `user`, `action` (Tambah/Ubah/Hapus), `severity` (info/warning/critical), `table_name`, `description`, `details` (Data diff), `user_agent` (JSON metadata: browser, OS, latency), `ip_address`, `created_at`

**Tabel `trash_data` (Recycle Bin System)**
- Menampung data yang dihapus dari tabel manapun untuk menghindari hilangnya aset data vital.
- Struktur: `id` (PK), `entity_type` (e.g., 'RTLH', 'ASET_TANAH'), `entity_id` (ID asli dari tabel sumber), `data_json` (Seluruh *row* direpresentasikan dalam JSON), `deleted_by`, `created_at`.

---

## 4. Standar Logika Controller (Operasi Data)

### 4.1. Robust CSV Import (Pola Wajib)
Setiap fungsi `importCsv` pada Controller wajib:
1. **Auto-Reset ID:** Menjalankan `ALTER TABLE ... AUTO_INCREMENT = 1` jika tabel sasaran masih kosong.
2. **Delimiter Auto-Detection:** Menganalisa baris pertama untuk memutuskan apakah memotong berdasarkan `,` atau `;`.
3. **Regex Sanitization:** Membersihkan kolom NIK/No KK dari tanda baca (`preg_replace('/[^0-9]/', '', $val)`).
4. **Smart Date Parser:** Fungsi regex terintegrasi untuk mengenali "Tempat, DD Bulan YYYY" atau format ambigu seperti `m/d/Y` vs `d/m/Y`.
5. **Database Transaction:** Wajib dibungkus `$db->transStart()` dan `transComplete()`.

### 4.2. Penanganan Foto (File Uploading)
- Form wajib memiliki atribut `enctype="multipart/form-data"`.
- Wajib mengecek keberadaan direktori menggunakan `is_dir()` dan membuatnya dengan `mkdir($path, 0777, true)` jika belum ada, untuk menghindari gagal simpan data di OS Windows/Linux yang ketat strukturnya.
- Saat melakukan *Update* foto, controller **WAJIB** mengeksekusi fungsi `unlink()` untuk menghapus foto lama secara fisik dari server agar disk tidak kepenuhan.

---

## 5. Keamanan Web & API

- **CSRF (Cross-Site Request Forgery):** Diaktifkan secara global di `app/Config/Filters.php`.
- **Bypass API:** Semua rute dengan awalan `/api/*` **wajib** dikecualikan dari Filter Session Auth dan CSRF global agar JWT bisa bekerja murni di header `Authorization: Bearer <token>`.
- **JWT Key Fallback:** Dilarang menggunakan fallback text untuk JWT Secret Key di dalam source code. Rahasia sistem murni mengandalkan environment server (`getenv('JWT_SECRET')`).

---

## 6. Standar UI/UX ("Mewah" Style)

Sistem ini didesain bukan sebagai *admin panel* kaku, melainkan *Dashboard Eksekutif*.
- **Border Radius:** Gunakan kelas ekstrem Tailwind seperti `rounded-[2.5rem]` pada container card luar.
- **Micro-Interactions:** Setiap unggah gambar wajib disertai script `previewImage()` (FileReader JS) untuk memunculkan gambar sebelum form dikirim.
- **Export PDF:** Fitur cetak diutamakan menggunakan *Client-Side PDF Rendering* via `html2pdf.js` dengan menyuntikkan kelas `.is-exporting` sementara ke tag body untuk menyembunyikan navigasi.

---
*Dokumen ini merupakan kitab panduan final (Single Source of Truth) untuk SIBARUKI. Pembaruan arsitektur wajib direfleksikan di dalam file ini.*
