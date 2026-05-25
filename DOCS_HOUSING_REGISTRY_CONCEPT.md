# RFC: Transformasi Modul RTLH Menjadi Master "Profil Rumah"
**Status:** Draft / Conceptual
**Role:** Senior Developer Alignment

## 1. Latar Belakang & Masalah
Saat ini, SIBARUKI memisahkan data ke dalam kerangka biner (RTLH vs RLH) yang membingungkan secara operasional. Banyak data rumah (misalnya hasil import GIS/Kependudukan) yang status pastinya belum diketahui. Konsep lama juga membuat modul RTLH tercampur baur dengan fungsi pelaksanaan Bansos.

Untuk membangun **Database Spasial Terpadu** yang komprehensif, kerangka kerja ini akan diubah menjadi konsep *Lifecycle* (Siklus Hidup Data).

## 2. Kerangka Status Baru (The 4-Tier Model)

Kolom `status_bantuan` (atau ekuivalennya) pada tabel `perumahan_rtlh_rumah` akan diubah menjadi sistem 4 status (Enum):

1. **`Unknown` (Unidentified):** 
   - Status *default* untuk data mentah yang diimpor atau baru didata namun belum disurvei kondisinya. 
   - *Tindakan Lanjut:* Perlu survei lapangan.
2. **`RTLH` (Tidak Layak):** 
   - Rumah yang sudah diverifikasi kondisinya dan memenuhi syarat sebagai Rumah Tidak Layak Huni, namun belum masuk kuota bantuan.
   - *Tindakan Lanjut:* Masuk ke keranjang (backlog sasaran).
3. **`Target` (Earmarked / Diproses):** 
   - Rumah RTLH yang sudah diplot untuk mendapat bantuan pada tahun anggaran berjalan. 
   - Data ini akan memicu entri baru di modul "Bansos RTLH".
4. **`RLH` (Layak Huni):** 
   - Rumah yang sudah layak (baik dari hasil survei awal memang sudah layak, atau merupakan *outcome* dari selesainya proyek Bansos).
   - *Tindakan Lanjut:* Selesai (End of Lifecycle).

## 3. Rencana Implementasi (Technical Roadmap)

### A. Modifikasi Skema Database
- Eksekusi Migrasi `ALTER TABLE perumahan_rtlh_rumah MODIFY COLUMN status_bantuan ENUM('Unknown', 'RTLH', 'Target', 'RLH') DEFAULT 'Unknown'`.

### B. Pemisahan Tugas Modul (Separation of Concerns)
- **Modul Profil Rumah (`/rtlh` ➡️ `/profil-rumah`):** 
  Bertindak sebagai *Master Data*. Admin mengelola data spasial, kondisi teknis (pondasi, atap), dan identitas penghuni.
- **Modul Bansos RTLH (`/bansos-rtlh`):** 
  Bertindak sebagai *Project Management*. Ketika sebuah rumah di Profil Rumah di-set menjadi `Target`, maka progress pembangunannya dikelola di modul Bansos. Begitu progres 100%, status di Profil Rumah otomatis berubah menjadi `RLH`.

### C. Refactoring Antarmuka (UI/UX)
1. **Navigasi Sidebar:** Mengganti "RTLH" menjadi "Profil Rumah" atau "Master Perumahan" agar maknanya inklusif.
2. **Status Badges:** 
   - `Unknown`: Abu-abu (Slate)
   - `RTLH`: Merah (Rose)
   - `Target`: Biru (Indigo)
   - `RLH`: Hijau (Emerald)
3. **Smart Filters:** Memudahkan admin (khususnya admin desa) untuk memfilter tabel berdasarkan 4 status ini.

## 4. Keamanan & Integritas Sistem
1. Saat mengganti status `Target` menjadi status lain, sistem harus mengecek apakah rumah tersebut sedang "terkunci" di dalam proyek berjalan pada modul Bansos RTLH.
2. Setiap perubahan status (State Transition) wajib masuk ke tabel `sys_logs` dan `perumahan_rtlh_history` agar audit trail terjaga.

---
*Dokumen ini dibuat untuk menyepakati arsitektur sebelum eksekusi refactoring database besar-besaran dimulai.*
