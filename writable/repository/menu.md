Menu

Data perumahan

1\. RTLH (Rumah tidak layak huni) -> peta titik(MINUS, Kurang data titik koordinatnya)

**2. Sebaran PSU (Prasarana/Sarana Utilitas Umum) Terbangun  -> peta titik**

**3. Sebaran Perumahan formal  -> peta poligon**

**4. Sebaran bantuan sosial perbaikan RTLH  -> peta titik (buatkan menu (data))**

Data kawasan permukiman

1\. Kawasan kumuh -> peta polygon (DONE)(perbaiki fitur satelit)

**2. Sebaran PISEW (Pengembangan Infrastruktur Sosial Ekonomi Wilayah)  -> peta titik (buatkan menu (data))**

**3. Sebaran Arsinum (air siap minum) -> peta titik**

Data pertanahan

**1. Sebaran Aset Tanah Pemda -> peta titik (buatkan menu (data))**





tambahkan authentication (Done)

tambahkan monitoring aktivitas (Done)

tambahkan fitur crud untuk user Ketika kita login sebagai admin (Done)

tambahkan fitur kalau misal sudah tuntas menerima bantuan, dia akan langsung pindah ke tampilan telah menerima bantuan (tambahkan kolom status di table rtlh (table penerima atau table rumah)
tambahkan peta kecamatan (polygon) 
Tambahkan admin bisa melihat data rekapan masing2 desa untuk data rtlh (menu baru)
tambahkan peta grouping untuk data rtlh (kurang lebih mirip peta kumuh tapi ada fitur grouping)

tambahkan tabel baru sebagai penempatan history pasca berubah jadi rlh (dapat menampilkan data perubahan (Contoh : dapat bantuan dari baznas dll) agar jelas perubahannya)

hilangkan detail dari table rtlh dan kumuh untuk user umum (hanya admin yang bisa liat

pindahkan hak akses ke menu pengaturan agar tampak di sidebar hanya data

tambahkan filter yang lebih menampilkan status rtlh 





konsep database untuk user 1



&nbsp; Tabel `users` (Revisi Final):

&nbsp;  \* id (PK)

&nbsp;  \* username

&nbsp;  \* password

&nbsp;  \* instansi (Nama Kantor/Desa)

&nbsp;  \* `desa\_id` (Kolom ini bisa NULL untuk Admin Pusat, dan berisi Kode Desa untuk Petugas Desa)

&nbsp;  \* role\_id (FK ke roles)





Tabel `roles` (Master Role)

&nbsp; Tabel ini menyimpan jenis akses yang ada di sistem.

&nbsp;  - id (Primary Key, Auto Increment)

&nbsp;  - role\_name (Contoh: 'admin', 'operator')





konsep database untuk user 2



&nbsp;1. Tabel `users` (Data Login Utama)

&nbsp;  \* id (PK)

&nbsp;  \* username

&nbsp;  \* password

&nbsp;  \* instansi

&nbsp;  \* role\_id (FK ke roles)





&nbsp; 2. Tabel `roles` (Master Role)

&nbsp;  \* id (PK)

&nbsp;  \* role\_name (Admin Pusat, Petugas Lapangan)





&nbsp; 3. Tabel `user\_desa` (Tabel Penghubung)

&nbsp; Tabel ini yang akan mencatat petugas ini pegang desa mana saja.

&nbsp;  \* id (PK)

&nbsp;  \* user\_id (FK ke users)

&nbsp;  \* `desa\_id` (Kode Desa)





Admin: admin / password123

Petugas: petugas\_a / password123





apakah anda bisa menambahkan ini? (jangan di tambah dulu, dan coba kasih saran kalua ada yang kurang)



Menu Pengaturan biasanya menjadi tempat bagi Admin untuk mengelola hal-hal yang sifatnya konfigurasi sistem

tanpa harus menyentuh kode program. Untuk SIBARUKI, berikut adalah ide isi yang sangat pas:





&nbsp; 1. Profil Instansi (General Settings)

&nbsp; Mengelola data yang akan tampil di header laporan PDF atau Berita Acara.

&nbsp;  \* Nama Dinas/Instansi.

&nbsp;  \* Alamat Kantor.

&nbsp;  \* Logo Dinas (bisa upload logo baru untuk mengganti logo Sinjai saat ini).

&nbsp;  \* Nama Pimpinan \& NIP (untuk tanda tangan otomatis di laporan).





&nbsp; 2. Pengaturan Tampilan (UI Preferences)

&nbsp;  \* Mode Default: Light atau Dark.

&nbsp;  \* Warna Aksen: Memilih warna utama (Blue, Indigo, Emerald, dll).

&nbsp;  \* Bahasa: (Jika ingin mendukung multi-bahasa nantinya).





&nbsp; 3. Pemeliharaan Sistem (System Tools)

&nbsp;  \* Tombol "Backup Database": Admin bisa mendownload file SQL cadangan sewaktu-waktu.

&nbsp;  \* Tombol "Cek Update": Mengetahui jika ada fitur baru (simulasi).

&nbsp;  \* Log Error: Melihat rekaman error sistem secara teknis (hanya untuk pengembang/admin IT).





&nbsp; 4. Pengaturan Notifikasi (Notifications)

&nbsp;  \* Email Gateway: Konfigurasi akun email jika sistem ingin mengirim notifikasi otomatis ke user.

&nbsp;  \* Alert Threshold: Mengatur batas skor kumuh (misal: skor di atas 70 dianggap "Sangat Bahaya").



&nbsp; ---







&nbsp; 1. Teknologi \& Fitur Utama yang Digunakan

&nbsp;  \* PhpSpreadsheet: Library PHP standar industri untuk membaca dan menulis file Excel (.xlsx, .xls) dan CSV    

&nbsp;    secara presisi.

&nbsp;  \* Database Transactions: Menjamin jika proses impor 1 baris ke 3 tabel gagal di tengah jalan, seluruh data   

&nbsp;    baris tersebut akan ditarik kembali (Rollback) agar tidak ada data sampah.

&nbsp;  \* Smart ID Lookup: Algoritma pencarian otomatis untuk mengubah teks Excel (Nama Desa, Kondisi Bangunan)      

&nbsp;    menjadi ID database yang sesuai secara real-time.

&nbsp;  \* Header Mapping Engine: Antarmuka yang memungkinkan Admin mencocokkan kolom Excel dengan kolom Database     

&nbsp;    secara manual jika nama kolomnya berbeda.

&nbsp;  \* Validation Layer: Pengecekan duplikasi NIK, format koordinat yang salah, atau kolom wajib yang kosong      

&nbsp;    sebelum data benar-benar disimpan.



&nbsp; ---





&nbsp; 2. Alur Kerja Impor (Workflow)



&nbsp; Tahap 1: Persiapan (Template)

&nbsp;  \* Sistem menyediakan tombol "Unduh Template Impor".

&nbsp;  \* Template ini sudah berisi judul kolom yang paling dikenali oleh sistem (untuk meminimalisir mapping        

&nbsp;    manual).





&nbsp; Tahap 2: Unggah \& Parsing

&nbsp;  1. Admin mengunggah file Excel/CSV.

&nbsp;  2. Sistem membaca file tersebut dan menyimpannya sementara di memori.

&nbsp;  3. Mapping Preview: Admin diperlihatkan tabel berisi data dari Excel. Di atas setiap kolom, ada dropdown     

&nbsp;     untuk memilih target kolom di database.

&nbsp;      \* Contoh: Kolom Excel "Luas" dipetakan ke "luas\_rumah\_m2" di DB.





&nbsp; Tahap 3: Pemrosesan Data (The "Magic" Logic)

&nbsp; Sistem akan memproses setiap baris dengan logika berikut:

&nbsp;  1. Resolusi Desa: Mencari desa\_id di tabel kode\_desa berdasarkan teks "Desa" yang ada di Excel.

&nbsp;  2. Resolusi Ref Master: Mencari ID di tabel ref\_master untuk kolom seperti st\_pondasi, st\_atap, dll.

&nbsp;     berdasarkan teks (misal: "RUSAK" menjadi ID 12).

&nbsp;  3. Split Data: Membagi satu baris Excel menjadi 3 bagian:

&nbsp;      \* Bagian A -> Masuk ke rtlh\_penerima.

&nbsp;      \* Bagian B -> Masuk ke rtlh\_rumah (menggunakan nik dari Bagian A).

&nbsp;      \* Bagian C -> Masuk ke rtlh\_kondisi\_rumah (menggunakan id\_survei dari Bagian B).





&nbsp; Tahap 4: Eksekusi \& Feedback

&nbsp;  \* Sistem menampilkan laporan hasil impor:

&nbsp;      \* "Berhasil: 145 data."

&nbsp;      \* "Gagal: 2 data (Baris 10: NIK Duplikat, Baris 25: Nama Desa tidak ditemukan)."



&nbsp; ---





&nbsp; 3. Struktur Modul \& Menu



&nbsp; A. Halaman Impor RTLH (Advanced)

&nbsp;  \* Mendukung pemecahan 1 baris Excel ke 3 tabel database.

&nbsp;  \* Pendeteksian otomatis hubungan NIK antara tabel penerima dan rumah.





&nbsp; B. Halaman Impor Wilayah Kumuh (Simple)

&nbsp;  \* Mendukung file CSV/Excel.

&nbsp;  \* Fokus pada pemetaan kolom identitas kawasan dan skor kumuh.





&nbsp; C. Halaman Ekspor (Reporting)

&nbsp;  \* Ekspor RTLH: Menghasilkan satu file Excel besar yang sudah digabungkan (Join) dari 3 tabel, sehingga siap  

&nbsp;    dicetak atau dilaporkan ke pimpinan.

&nbsp;  \* Ekspor Wilayah Kumuh: Menghasilkan file data kawasan yang rapi.



&nbsp; ---





&nbsp; 4. Keuntungan Teknis bagi Anda

&nbsp;  1. Keamanan Truncate: Jika Anda mengosongkan database transaksi, Anda bisa mengimpor ulang kapan saja hanya  

&nbsp;     dengan bermodalkan file Excel. Sistem akan membangun kembali relasi ID-nya secara otomatis.

&nbsp;  2. Pembersihan Data: Selama proses impor, sistem bisa otomatis memperbaiki penulisan (misal: merapikan huruf 

&nbsp;     kapital pada nama).

&nbsp;  3. Audit: Setiap kali proses impor dilakukan, sistem akan mencatat di Monitoring Aktivitas: "Admin mengimpor 

&nbsp;     150 data RTLH dari file Data\_2024.xlsx".



&nbsp; ---





&nbsp; 5. Komponen UI (User Interface)

&nbsp; Tampilan akan mengikuti standar SIBARUKI Luxury Style:

&nbsp;  \* Area Upload dengan animasi Drag \& Drop.

&nbsp;  \* Tabel Preview dengan baris yang berwarna (Merah untuk data yang salah, Hijau untuk data yang siap impor).  

&nbsp;  \* Progress Bar saat proses impor sedang berjalan (untuk ribuan data).





&nbsp; Bagaimana menurut Anda rincian ini? Apakah ada poin tambahan yang ingin Anda masukkan ke dalam rencana ini? 





Berikut adalah analisis dan beberapa saran teknis tambahan dari saya untuk memperkuat sistem tersebut:        





&nbsp; 1. Analisis Menu Pengaturan (Settings)

&nbsp; Ide Anda untuk membagi pengaturan menjadi Profil Instansi, Tampilan, dan Pemeliharaan sudah sangat tepat.     

&nbsp; Untuk membuatnya lebih profesional ("Luxury"), saya menyarankan tambahan berikut:

&nbsp;  \* Mode Pemeliharaan (Maintenance Mode): Tombol untuk menonaktifkan akses bagi pengguna non-admin saat Anda   

&nbsp;    sedang melakukan update data besar atau perbaikan sistem.

&nbsp;  \* Informasi Sistem (System Health): Menampilkan versi PHP, versi Database, dan sisa kapasitas penyimpanan    

&nbsp;    server (ini sejalan dengan mandat monitoring di GEMINI.md).

&nbsp;  \* Log Error Teknis: Tab khusus untuk Admin IT melihat rekaman error sistem tanpa harus membuka file log di   

&nbsp;    server.





&nbsp; 2. Logika "Magic" Impor RTLH (Bagian Krusial)

&nbsp; Karena Anda akan memecah 1 baris Excel ke dalam 3 tabel (penerima, rumah, dan kondisi\_fisik), ada beberapa    

&nbsp; tantangan teknis yang perlu diantisipasi:

&nbsp;  \* Fuzzy Matching (Pencarian Cerdas): Terkadang user menulis "Rusak Berat", "rusak berat", atau hanya "RB" di 

&nbsp;    Excel. Sistem harus cukup pintar untuk mencocokkan teks tersebut dengan ID yang benar di tabel ref\_master  

&nbsp;    (tidak boleh case-sensitive).

&nbsp;  \* Proses Bertahap (Chunk Processing): Jika Admin mengunggah 5.000 data sekaligus, server bisa mengalami      

&nbsp;    timeout atau kehabisan memori. Sebaiknya data diproses per 100 baris menggunakan AJAX agar Progress Bar    

&nbsp;    berjalan mulus secara real-time.

&nbsp;  \* Tabel Transit (Staging Area): Sebelum data benar-benar masuk ke database utama, tampilkan dulu di tabel    

&nbsp;    sementara (preview). Di sini Admin bisa langsung memperbaiki data yang "Merah" (salah) sebelum menekan     

&nbsp;    tombol "Finalize Import".





&nbsp; 3. Struktur Kode \& Arsitektur (CI4)

&nbsp; Agar kode tetap rapi dan mudah dirawat (maintainable), saya menyarankan:

&nbsp;  \* Controller: Buat Settings.php untuk pengaturan dan DataExchange.php untuk Impor/Ekspor.

&nbsp;  \* Library Khusus: B yang sudah ada).vice.php di folder app/Libraries untuk menangani semua logika

