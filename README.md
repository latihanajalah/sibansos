# Sistem Informasi Pengajuan Bantuan Sosial (Bansos)

Aplikasi berbasis web untuk mengelola proses pengajuan bantuan sosial mulai dari pendaftaran, survei lapangan, verifikasi administrasi, persetujuan pimpinan, hingga penyaluran bantuan. Sistem ini juga dilengkapi dengan halaman *landing page* yang dapat diakses oleh masyarakat umum untuk mengecek status pengajuan bantuan berdasarkan NIK.

## Fitur Utama

- **Authentication & Authorization**: Akses berbasis peran (Super Admin, Admin, Petugas, Pimpinan).
- **Master Data**: Pengelolaan data jenis bantuan, pengguna, dan data penduduk penerima bantuan.
- **Pengajuan Bantuan**: Pendaftaran pengajuan bantuan oleh Petugas.
- **Survei Lapangan**: Input data survei, upload foto kondisi rumah (tampak depan, ruang tamu, dapur, kamar, dll), serta dokumen pendukung.
- **Verifikasi & Persetujuan**: Alur kerja bertingkat (menunggu verifikasi -> menunggu persetujuan -> siap disalurkan -> selesai) yang melibatkan Petugas, Admin, dan Pimpinan.
- **Penyaluran Bantuan**: Upload bukti penyerahan bantuan (foto) kepada penerima.
- **Laporan & Statistik**: Dashboard analitik dan laporan (PDF/Excel) untuk Pengajuan dan Penyaluran.
- **Audit Log**: Pencatatan riwayat aktivitas pengguna untuk keamanan dan transparansi.
- **Portal Publik (Landing Page)**: Informasi layanan dan cek status pengajuan (menggunakan NIK).

## Teknologi

- **Backend**: Laravel 11
- **Database**: SQLite (untuk mempermudah setup) atau MySQL/PostgreSQL
- **Frontend**: Blade Templating Engine dengan Bootstrap 5
- **Testing**: PHPUnit

## Prasyarat (Requirements)

- PHP >= 8.2
- Composer
- Node.js & npm (untuk mem-build asset frontend dengan Vite)

## Instalasi & Setup

1. **Clone repositori ini atau download source code-nya.**
   ```bash
   git clone <url-repo>
   cd Sembako
   ```

2. **Install dependensi PHP dan Node.js.**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment.**
   Copy file `.env.example` menjadi `.env`.
   ```bash
   cp .env.example .env
   ```
   Atur koneksi database (secara default sudah dikonfigurasi menggunakan SQLite) dan parameter lain jika diperlukan.

4. **Generate Application Key.**
   ```bash
   php artisan key:generate
   ```

5. **Jalankan Migrasi dan Seeder (Untuk Data Dummy).**
   ```bash
   php artisan migrate:fresh --seed
   ```
   > **Perhatian**: Menjalankan `--seed` akan memasukkan data dummy (termasuk Demo Seeder) minimal 20 records (penerima, pengajuan, penyaluran, dll) sehingga aplikasi siap didemokan.

6. **Tautkan Storage (Storage Link).**
   Jalankan perintah ini agar file upload (foto, bukti dokumen) dapat diakses via browser.
   ```bash
   php artisan storage:link
   ```

7. **Build Asset Vite & Jalankan Server Lokal.**
   Buka dua tab terminal, jalankan secara bersamaan:
   ```bash
   npm run build
   php artisan serve
   ```
   
   Aplikasi kini dapat diakses melalui browser pada alamat:
   [http://localhost:8000](http://localhost:8000)

## Akun Demo (Seeder)

Gunakan akun berikut untuk mencoba alur bisnis aplikasi:

| Role | Email | Password | Hak Akses |
| --- | --- | --- | --- |
| **Super Admin** | `superadmin@bansos.test` | `password` | Mengelola semua data pengguna, master data, dan laporan. |
| **Admin** | `admin@bansos.test` | `password` | Verifikasi pengajuan, kelola master data (Penerima & Jenis Bantuan), lihat laporan. |
| **Pimpinan** | `pimpinan@bansos.test` | `password` | Persetujuan akhir (setujui/tolak) pengajuan bantuan dan lihat laporan. |
| **Petugas** | `petugas@bansos.test` | `password` | Entri pengajuan, isi survei lapangan, dan lakukan penyaluran. |

> Terdapat juga akun petugas lapangan lain: `petugas1@bansos.test` hingga `petugas5@bansos.test` (password: `password`).

## Alur Bisnis Pengajuan Bantuan

1. **Pengajuan (Oleh Petugas):** Petugas membuat pengajuan baru berdasarkan NIK penerima (Status: *Menunggu Survei*).
2. **Survei Lapangan (Oleh Petugas):** Petugas mendatangi rumah penerima, mengisi kuesioner dan mengupload bukti foto (Status: *Menunggu Verifikasi*).
3. **Verifikasi (Oleh Admin):** Admin memverifikasi data dan foto. Admin dapat menyetujui, meminta revisi survei, atau menolak. Jika disetujui, status menjadi *Menunggu Persetujuan*.
4. **Persetujuan (Oleh Pimpinan):** Pimpinan memberikan keputusan akhir. Jika disetujui, status menjadi *Siap Disalurkan*.
5. **Penyaluran (Oleh Petugas):** Petugas menyerahkan bantuan dan mengupload foto bukti terima (Status: *Selesai*).
6. **Masyarakat (Landing Page):** Penerima manfaat dapat melihat riwayat proses ini dengan menginputkan NIK mereka.

## Testing

Aplikasi ini dilengkapi dengan pengujian (Feature Testing) untuk memastikan semua fitur berjalan normal (Total ~60+ test passed).

Jalankan pengujian dengan perintah:
```bash
php artisan test
```

## Keamanan

- **CSRF Protection**: Aktif di seluruh form POST/PUT/DELETE.
- **File Validation**: Ekstensi dan ukuran file divalidasi dengan ketat di form request (hanya gambar & PDF).
- **Authorization**: Diatur secara terpusat di `FormRequest::authorize()` dan Controller (`auth()->user()->role`).
- **Audit Log**: Seluruh CRUD utama dicatat dalam tabel `log_aktivitas` untuk pemantauan sistem.

---
*Dokumentasi disusun untuk proses finalisasi TAHAP 15.*
