# SIBARUKI - Blueprint Arsitektur & Kamus Data (Data Dictionary)

Dokumen ini adalah **Spesifikasi Arsitektur Tingkat Rendah** untuk Sistem Informasi SIBARUKI (Sama-samaki Bangun Perumahan dan Permukiman). Seluruh pengembangan lanjutan, query manual, dan integrasi API wajib merujuk pada struktur tabel di bawah ini.

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

## 6. Standar UI/UX & Design System ("Mewah" Style)

Sistem ini didesain bukan sebagai *admin panel* kaku, melainkan *Dashboard Eksekutif*. Untuk menjaga konsistensi, seluruh elemen UI wajib mengikuti pedoman di bawah ini.

### 6.1. Tipografi (Typography)
- **Font Family:** `font-sans` (System stack / Inter). Utamakan kejelasan di atas segalanya.
- **Ukuran Font (Sizing):**
  - **Judul Besar (Hero/Title):** `text-2xl` s/d `text-4xl`, `font-black`, `tracking-tighter`, `uppercase`.
  - **Heading (Section):** `text-lg` s/d `text-xl`, `font-bold`, `tracking-tight`.
  - **Sub-Heading / Label:** `text-[10px]`, `font-black`, `uppercase`, `tracking-[0.2em]`.
  - **Body Text:** `text-sm` (Default), `font-medium`, `leading-relaxed`.
  - **Small/Caption:** `text-[9px]`, `font-bold`, `uppercase`, `tracking-widest`.

### 6.2. Palet Warna (Color Palette)
- **Primary (Blue):** `blue-600` (#2563eb) untuk aksi utama, `blue-50` untuk background light, `blue-950` untuk teks gelap.
- **Secondary (Indigo):** `indigo-600` untuk tombol dashboard atau elemen pembeda.
- **Neutral (Slate/Gray):**
  - Light Mode: `bg-slate-50` (Body), `bg-white` (Cards), `text-slate-500` (Secondary text).
  - Dark Mode: `bg-slate-950` (Body), `bg-slate-900` (Cards), `text-slate-400` (Secondary text).
- **Accents:** Gunakan `backdrop-blur-md` dan `rgba` transparency (misal: `bg-white/70`) untuk elemen navigasi (Glassmorphism).

### 6.3. Elemen Visual & Kontainer
- **Border Radius:**
  - **Card Luar:** `rounded-[2rem]` (Melengkung elegan).
  - **Button/Small Card:** `rounded-xl` atau `rounded-lg`.
  - **Input Field:** `rounded-lg`.
- **Shadows (Penting):** Gunakan bayangan yang dalam tapi sangat lembut (soft & deep shadows).
  - **Standard Card:** `shadow-xl shadow-slate-200/50` (Light) atau `shadow-2xl shadow-black/20` (Dark).
  - **Accent Card:** Gunakan bayangan berwarna tipis, contoh: `shadow-xl shadow-blue-600/10`.
- **Borders:** Hindari border hitam pekat. Gunakan `border-slate-100` (Light) atau `border-slate-800` (Dark).

### 6.4. Komponen & Interaksi
- **Buttons:**
  - **Ukuran Standar:** `px-5 py-2.5` (Jangan terlalu besar).
  - **Ikon:** Gunakan `w-4 h-4`.
  - **Efek:** Wajib memiliki efek `active:scale-95` dan `transition-all`.
- **Icons:** Gunakan **Lucide Icons** dengan `stroke-width` yang konsisten (rekomendasi: `2`).
- **Cards:** Gunakan padding yang seimbang (`p-6` s/d `p-8`).
- **Micro-Interactions:**
  - Setiap unggah gambar wajib disertai script `previewImage()` (FileReader JS).
  - Hover state pada navigasi harus halus dengan transisi minimal `300ms`.

### 6.5. Mode Gelap (Dark Mode)
- Seluruh halaman **WAJIB** mendukung `dark:` prefix secara eksplisit.
- Jangan mengandalkan auto-invert. Warna dark mode harus dipilih untuk kenyamanan mata (Background `slate-950`, bukan hitam murni `#000`).

### 6.6. Fitur Khusus
- **Export PDF:** Fitur cetak diutamakan menggunakan *Client-Side PDF Rendering* via `html2pdf.js` dengan menyuntikkan kelas `.is-exporting` sementara ke tag body untuk menyembunyikan navigasi.

### 6.7. Standar Tata Letak (Layout Consistency)
Untuk memastikan seluruh modul memiliki pengalaman pengguna yang seragam, setiap halaman wajib mengikuti struktur berikut:

#### A. Halaman Index (Daftar Tabel)
1. **Header:** Judul halaman (`text-3xl`, `font-black`) di kiri, dan tombol aksi utama (Tambah Data) di kanan.
2. **Filter & Search:** Baris pencarian dan filter diletakkan di atas tabel, terbungkus dalam card kecil dengan `rounded-2xl`.
3. **Table Container:** Tabel wajib dibungkus dalam kontainer `overflow-x-auto` di dalam card utama `rounded-[2.5rem]`.
4. **Pagination:** Kontrol navigasi halaman diletakkan di bawah tabel dengan desain minimalis.

#### B. Halaman Detail (Single Data View)
1. **Breadcrumbs:** Navigasi hirarki di bagian paling atas (misal: Dashboard > RTLH > Detail).
2. **Main Header:** Menampilkan identitas utama (misal: Nama Pemilik & NIK) dengan Badge status di sampingnya.
3. **Information Grid:** Gunakan sistem grid (`grid-cols-1 md:grid-cols-2 lg:grid-cols-3`) untuk menampilkan label dan nilai data.
   - **Label:** `text-[10px]`, `font-bold`, `uppercase`, `text-slate-400`.
   - **Value:** `text-sm`, `font-bold`, `text-slate-700/white`.
4. **Photo Gallery:** Jika ada data visual (RTLH), gunakan layout grid untuk foto dengan aspek rasio tetap (misal: `aspect-video`) dan fitur klik untuk perbesar.

#### C. Halaman Formulir (Add/Edit)
1. **Grouped Fields:** Bagi input ke dalam beberapa kategori (misal: Data Personal, Data Lokasi) menggunakan pemisah visual atau card yang berbeda.
2. **Action Bar:** Tombol "Simpan" (Primary) dan "Batal" (Secondary) diletakkan di bagian bawah, biasanya rata kanan (sticky bottom pada mobile).
3. **Validation States:** Input yang error wajib memiliki border merah dan pesan peringatan kecil di bawahnya.

#### D. Integrasi Peta (Geospasial)
Setiap modul yang memiliki data koordinat (Point/Polygon/Linestring) wajib mengikuti standar tampilan peta berikut:
1. **Library & Base Layer:** Gunakan **Leaflet.js** dengan Tile Layer dari CartoDB (Voyager/Positron) untuk kesan bersih dan modern.
2. **Map Container:**
   - **Pada Index:** Peta diletakkan di dalam card `rounded-[2.5rem]` dengan tinggi minimal `500px`.
   - **Pada Detail:** Peta diletakkan di samping atau di bawah informasi teks, dengan aspek rasio `16:9` atau `square` tergantung ruang.
3. **Marker & Popup:**
   - Gunakan Custom Marker (Dot dengan Ring/Shadow) berwarna `Blue-600`.
   - Popup wajib menampilkan informasi ringkas (Nama/ID) dan tombol "Lihat Detail" yang konsisten.
4. **Interaksi:**
   - Wajib menyertakan tombol **"Focus to Location"** (Fly to) jika peta menampilkan banyak titik.
   - Pada form input koordinat, sertakan fitur **"Drag Marker to Pick"** atau pencarian lokasi (Geocoding).

#### E. Elemen UI Pendukung (Reusable Components)
1. **Badge Status:**
   - Gunakan padding `px-3 py-1`, `rounded-full`, `text-[10px]`, `font-bold`, `uppercase`.
   - **Warna:** Hijau (Sukses/Sudah), Kuning (Proses/Menunggu), Merah (Gagal/Belum), Biru (Info).
2. **Empty State:**
   - Jika data kosong, dilarang menampilkan tabel kosong. Tampilkan ilustrasi/ikon Lucide besar, teks `Data Tidak Ditemukan`, dan tombol "Refresh" atau "Tambah Data".
3. **Loading State:**
   - Gunakan skeleton screen atau spinner halus di tengah kontainer card utama saat mengambil data via Fetch API.

---
*Dokumen ini merupakan kitab panduan final (Single Source of Truth) untuk SIBARUKI. Pembaruan arsitektur wajib direfleksikan di dalam file ini.*

