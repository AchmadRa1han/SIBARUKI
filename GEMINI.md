# SIBARUKI - Project Guidelines

## Arsitektur Teknologi & Keamanan
- **Framework:** CodeIgniter 4 (PHP).
- **Styling:** Tailwind CSS (dikompilasi via PostCSS ke `public/css/app.css`).
- **Icons:** Lucide Icons (CDN).
- **Sistem Hak Akses (RBAC):** Dinamis berbasis tabel `roles`, `permissions`, dan `role_permissions`. 
- **Cakupan Data (Scope):** 
  - `Global`: Akses data se-Kabupaten (Admin/Pimpinan).
  - `Local`: Akses data terbatas pada wilayah tugas (Petugas/Kepala Desa).
- **Data Integrity:** Dilengkapi fitur **Recycle Bin** (tabel `trash_data`) untuk menyimpan data yang dihapus dalam format JSON agar bisa dipulihkan.

## Standar UI/UX ("Mewah" Style)
- **Tema Warna:** Biru Sangat Gelap (`blue-950`).
- **Layout Utama:** Dashboard responsive dengan sidebar (Auto-open logic berbasis URL) dan Header statis.
- **Kartu Informasi:** Sudut sangat bulat (`rounded-[2rem]` / `rounded-[3rem]`), border tipis, dan bayangan lembut (`shadow-sm`).
- **Sistem Notifikasi:** Menggunakan **Toast Notification** melayang (pojok kanan atas) untuk feedback aksi, menggantikan alert statis.
- **Konfirmasi Aksi:** Wajib menggunakan **Custom Confirmation Modal** (`customConfirm`) untuk aksi krusial (Hapus, Restore, Clear Log).
- **Dark Mode:** Wajib didukung penuh (`dark:`) dengan persistensi tema menggunakan `localStorage`.

## Monitoring & Audit
- **Monitoring Aktivitas:** Pusat kendali terpusat yang mencakup:
  - **Audit Trail:** Log detail "Data Diff" (membandingkan nilai lama vs baru).
  - **User Analytics:** Pelacakan login sukses, login gagal (Security Alarm), dan status Online (5 menit terakhir).
  - **System Health:** Monitor beban CPU, status Database, dan penggunaan Disk Server secara real-time.
- **Automasi:** Fitur *Live Auto-Refresh* (30 detik) dan *Housekeeping* (pembersihan log > 6 bulan).

## Konvensi Pengembangan
- **CRUD Terpadu:** Pengelolaan data lintas tabel wajib menggunakan Transaksi Database (`$db->transStart()`).
- **Security Check:** Setiap aksi di Controller wajib divalidasi menggunakan helper `has_permission('nama_izin')`.
- **Filtering:** Admin wajib dibekali *Advanced Filter* (Dropdown Desa, Kriteria Teknis, dll) di setiap daftar tabel utama.
- **Pagination:** Menggunakan template kustom `tailwind_full` dengan navigasi di posisi **Tengah (Center)** dan fitur *Dynamic Per-Page*.

## Perintah Pengembangan
- run localhost: `php spark serve`
- Build CSS: `npm run build`
- Watch CSS: `npm run watch`
- Update Permissions: `php spark db:seed RbacSeeder`
