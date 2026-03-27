DOKUMEN PANDUAN TEKNIS OPERASIONAL SISTEM SIBARUKI
(Sistem Informasi Bantuan Rumah dan Kawasan Permukiman)

DAFTAR ISI
1. Pendahuluan Sistem
2. Modul Rumah Tidak Layak Huni (RTLH)
   2.1. Data Personal Penerima (rtlh_penerima)
   2.2. Data Hunian dan Geospasial (rtlh_rumah)
   2.3. Penilaian Kondisi Teknis (rtlh_kondisi_rumah)
3. Modul Infrastruktur Permukiman
   4.1. Air Siap Minum (Arsinum)
   4.2. Infrastruktur Sosial Ekonomi (PISEW)
   4.3. Prasarana, Sarana, dan Utilitas (PSU Jalan)
   4.4. Kawasan Wilayah Kumuh
4. Modul Aset dan Pertanahan
5. Sistem Keamanan dan Hak Akses Pengguna
6. Prosedur Operasional Data (Import, Foto, dan Pemulihan)

---

1. PENDAHULUAN SISTEM
SIBARUKI adalah aplikasi basis data terpadu untuk mengelola informasi bantuan perumahan dan infrastruktur di Kabupaten Sinjai. Sistem ini berfungsi sebagai pusat data (Single Source of Truth) bagi Dinas Perkim untuk perencanaan, pelaksanaan, dan pemantauan bantuan agar tepat sasaran.

2. MODUL RUMAH TIDAK LAYAK HUNI (RTLH)
Data RTLH dikelola dalam tiga tabel yang saling terintegrasi berdasarkan NIK pemilik.

2.1. Data Personal Penerima (rtlh_penerima)
Berisi informasi dasar identitas penduduk:
- NIK: Nomor Induk Kependudukan (16 digit, unik untuk setiap pemilik).
- Nomor KK: Nomor Kartu Keluarga.
- Nama Kepala Keluarga: Nama lengkap sesuai dokumen kependudukan.
- Tempat dan Tanggal Lahir: Informasi kelahiran untuk verifikasi usia.
- Jenis Kelamin: Pilihan Laki-laki atau Perempuan.
- Pendidikan: Jenjang pendidikan terakhir pemilik.
- Pekerjaan: Jenis pekerjaan utama untuk menilai kelayakan bantuan.
- Penghasilan per Bulan: Total pendapatan rata-rata keluarga.
- Jumlah Anggota Keluarga: Jumlah orang yang bergantung hidup dalam satu rumah.

2.2. Data Hunian dan Geospasial (rtlh_rumah)
Berisi detail fisik lokasi dan kepemilikan:
- Desa dan ID Desa: Wilayah administratif lokasi rumah.
- Alamat Detail: Lokasi spesifik atau nama jalan/dusun.
- Kepemilikan Rumah: Status (Milik sendiri, sewa, kontrak, menumpang).
- Aset Rumah di Lokasi Lain: Informasi apakah pemilik memiliki rumah lain.
- Kepemilikan Tanah: Status lahan (Milik sendiri, tanah negara, tanah adat, dll).
- Sumber Penerangan: Jenis listrik (PLN dengan watt tertentu atau non-PLN).
- Bantuan Perumahan: Catatan apakah pernah menerima bantuan (BSPS, RTLH, dll).
- Jenis Kawasan: Lokasi rumah (Perdesaan, Perkotaan, Kawasan Pesisir).
- Luas Rumah dan Luas Lahan: Ukuran bangunan dan tanah dalam meter persegi.
- Jumlah Penghuni: Jumlah jiwa yang benar-benar menempati bangunan tersebut.
- Sumber Air Minum: Jenis akses air bersih yang digunakan sehari-hari.
- Jarak Sumber Air ke Pembuangan Tinja: Standar kesehatan lingkungan (minimal 10 meter).
- Fasilitas Sanitasi: Ketersediaan kamar mandi, jenis kloset, dan jenis tempat pembuangan akhir tinja (Septic tank atau lainnya).
- Lokasi Koordinat: Titik peta (Longitude dan Latitude) lokasi rumah berada.
- Dokumentasi Foto: Foto fisik tampak depan, samping, belakang, dan interior rumah.

2.3. Penilaian Kondisi Teknis (rtlh_kondisi_rumah)
Berisi penilaian kualitas material bangunan (Skor 1 sampai 5):
- Struktur Utama: Penilaian kondisi pondasi, tiang (kolom), balok, sloof, dan rangka atap.
- Komponen Non-Struktur: Penilaian plafon, jendela, dan ventilasi.
- Material Penutup:
  - Lantai: Jenis material (tanah, semen, keramik) dan kondisi kerusakannya.
  - Dinding: Jenis material (bambu, kayu, tembok) dan kondisi fisiknya.
  - Atap: Jenis material (rumbia, seng, genteng) dan kondisi kebocorannya.

3. MODUL INFRASTRUKTUR PERMUKIMAN

3.1. Air Siap Minum (Arsinum)
Mencatat pembangunan fasilitas air minum:
- Jenis Pekerjaan: Spesifikasi teknis alat atau bangunan arsinum.
- Volume: Kapasitas produksi air.
- Pelaksana: Pihak ketiga atau kelompok masyarakat yang mengerjakan.
- Anggaran dan Sumber Dana: Nilai kontrak dan asal anggaran (APBD/APBN).

3.2. Infrastruktur Sosial Ekonomi (PISEW)
Mencatat pembangunan pendukung ekonomi di desa, mencakup lokasi kecamatan, desa, nilai anggaran, dan tahun pelaksanaan.

3.3. Prasarana, Sarana, dan Utilitas (PSU Jalan)
Mencatat pembangunan jalan lingkungan:
- Nama Jalan: Nama ruas jalan yang dibangun.
- Data Spasial (WKT): Garis koordinat jalan yang ditampilkan pada peta sistem.

3.4. Kawasan Wilayah Kumuh
Mencatat data pemetaan kawasan kumuh:
- Luas Kumuh: Ukuran area dalam satuan Hektar.
- Skor Kumuh: Angka penilaian tingkat kekumuhan berdasarkan aturan kementerian.
- SK Kumuh: Nomor Surat Keputusan penetapan kawasan tersebut sebagai area kumuh.

4. MODUL ASET DAN PERTANAHAN
Digunakan untuk mengelola tanah milik Pemerintah Daerah:
- Nomor Sertifikat dan Nomor Hak: Legalitas hukum tanah.
- Nama Pemilik: Instansi atau pemegang hak atas tanah.
- Peruntukan: Tujuan penggunaan tanah (misal: Ruang Terbuka Hijau, Perkantoran).
- Nilai Aset: Nilai buku atau harga taksiran aset tanah tersebut.
- Status Tanah: Status aktif atau sengketa.

5. SISTEM KEAMANAN DAN HAK AKSES PENGGUNA
Akses sistem dibatasi berdasarkan peran (Role):
- Admin Global: Memiliki akses penuh ke seluruh data kabupaten.
- Petugas Kecamatan/Desa: Hanya dapat melihat dan mengolah data di wilayah tugasnya masing-masing.
- Autentikasi: Menggunakan sistem login dengan password terenkripsi (Bcrypt) dan Token JWT untuk akses via perangkat mobile.

6. PROSEDUR OPERASIONAL DATA

6.1. Impor Data (CSV)
Setiap proses unggah data masal (Import) mengikuti aturan:
- Pembersihan Data: Sistem otomatis menghapus tanda baca pada NIK dan No KK.
- Verifikasi Tanggal: Sistem mengenali berbagai format tanggal secara otomatis.
- Transaksi Aman: Jika terjadi kesalahan pada satu baris data, seluruh proses impor dibatalkan untuk menjaga keakuratan.

6.2. Manajemen Foto
- Unggah Foto: Foto harus dalam format gambar (JPG/PNG).
- Penggantian Foto: Saat foto lama diganti, sistem otomatis menghapus file lama dari server agar media penyimpanan tidak penuh.

6.3. Pemulihan Data (Recycle Bin)
- Data yang dihapus tidak langsung hilang dari database.
- Data dipindahkan ke tabel 'Trash Data' dalam format JSON.
- Admin dapat mengembalikan (Restore) data ke tabel asli jika terjadi kesalahan penghapusan.

6.4. Audit Trail (Log Sistem)
- Sistem mencatat setiap aksi: Tambah, Ubah, dan Hapus.
- Catatan mencakup: Nama pengguna, waktu kejadian, tabel yang diubah, dan detail data sebelum/sesudah perubahan.

---
Dokumen ini disusun sebagai referensi resmi operasional Sistem Informasi SIBARUKI Kabupaten Sinjai.
