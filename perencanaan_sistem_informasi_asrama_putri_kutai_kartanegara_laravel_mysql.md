# Perencanaan Pembangunan Website Sistem Informasi Asrama Putri Kutai Kartanegara (SIAP-Kukar)
**Tech Stack: Laravel Framework & MySQL Database**

Dokumen ini memuat rancangan komprehensif pembangunan Sistem Informasi Asrama Putri Kutai Kartanegara berbasis web, yang dirancang khusus menggunakan **Laravel** sebagai backend dan **MySQL** sebagai sistem manajemen basis data relasional.

---

## 1. Arsitektur & Teknologi Sistem

* **Backend Framework:** Laravel (PHP 8.x) dengan arsitektur MVC (Model-View-Controller).
* **Database Management System (DBMS):** MySQL / MariaDB.
* **Authentication & Authorization:** Laravel Breeze / Jetstream atau Spatie Laravel-Permission untuk manajemen *Role & Permission*.
* **Frontend Template:** Blade Templates + Bootstrap 5 / Tailwind CSS dengan AdminLTE atau Tailwind Admin Dashboard.
* **Library Tambahan:**
  * **PDF Generation:** `barryvdh/laravel-dompdf` atau `spatie/laravel-pdf`.
  * **Excel Export/Import:** `maatwebsite/excel` (Laravel Excel).

---

## 2. Perancangan Struktur Database (MySQL)

Berikut adalah rancangan tabel utama yang akan dibuat di database MySQL:

1. **Tabel `users`**
   * `id` (Primary Key)
   * `name`, `email`, `password`
   * `role` (Enum/Foreign Key: `admin`, `bendahara`, `penghuni`, `pendaftar`)
   * `timestamps`

2. **Tabel `pendaftaran` (Sistem Penerimaan Anggota Baru)**
   * `id` (PK)
   * `user_id` (FK ke `users`)
   * `nik`, `nama_lengkap`, `asal_daerah` (Kutai Kartanegara)
   * `institusi_pendidikan` (Universitas/Sekolah)
   * `status_pendaftaran` (`pending`, `diterima`, `ditolak`)
   * `timestamps`

3. **Tabel `berkas_pendaftaran`**
   * `id` (PK)
   * `pendaftaran_id` (FK ke `pendaftaran`)
   * `jenis_berkas` (`ktp`, `kk`, `surat_sehat`, `surat_pernyataan_aktif`, `pas_foto`)
   * `file_path` (path penyimpanan file di storage Laravel)
   * `timestamps`

4. **Tabel `kamar`**
   * `id` (PK)
   * `nomor_kamar`, `kapasitas`, `terisi`

5. **Tabel `pembayaran`**
   * `id` (PK)
   * `user_id` (FK ke `users` penghuni)
   * `bulan_tagihan`, `tahun_tagihan`
   * `jumlah_bayar`
   * `status_pembayaran` (`menunggu_verifikasi`, `lunas`, `ditolak`)
   * `bukti_transfer`
   * `timestamps`

6. **Tabel `laporan_keuangan`**
   * `id` (PK)
   * `jenis_transaksi` (`pemasukan`, `pengeluaran`)
   * `nominal`, `keterangan`, `tanggal`
   * `created_by` (FK ke `users`)

---

## 3. Pembagian Hak Akses (Role & Permission)

### A. Role Admin (Akses Penuh / Super Admin)
* **Manajemen Pengguna:** Mengatur akun admin, bendahara, dan penghuni asrama.
* **Manajemen Kamar & Fasilitas:** Alokasi kamar untuk penghuni baru yang lolos seleksi.
* **Validasi Penerimaan Anggota Baru:** Memeriksa formulir pendaftaran online dan memvalidasi berkas-berkas yang di-upload pendaftar.
* **Akses Keseluruhan Sistem:** Dapat melihat seluruh modul termasuk keuangan, laporan, dan data master.

### B. Role Bendahara (Akses Terbatas: Keuangan & Laporan)
* **Modul Pembayaran Asrama:**
  * Menerima dan memverifikasi bukti pembayaran iuran/syahriah asrama yang di-upload penghuni.
  * Mencatat transaksi pemasukan dan pengeluaran kas asrama secara manual.
* **Modul Laporan Asrama:**
  * Melihat rekapitulasi keuangan bulanan/tahunan.
  * **Fitur Ekspor Laporan:** Mengonversi laporan keuangan ke format **PDF** dan **Excel** secara instan menggunakan package Laravel (`maatwebsite/excel` dan `barryvdh/laravel-dompdf`).

---

## 4. Alur Kerja Modul Utama

### Alur Sistem Penerimaan Anggota Baru (SPAB)
1. **Pendaftar** mengakses halaman publik website, melakukan registrasi akun, dan mengisi formulir biodata.
2. Pendaftar melakukan **pengumpulan berkas digital** (upload file PDF/JPG seperti KTP, Kartu Keluarga, dan Surat Keterangan Aktif) yang langsung disimpan ke direktori `storage/app/public` Laravel.
3. **Admin** membuka dashboard, memverifikasi keabsahan data dan berkas.
4. Admin mengubah status menjadi **Diterima** atau **Ditolak**. Jika diterima, akun otomatis berstatus sebagai penghuni/calon penghuni asrama.

### Alur Pembayaran & Laporan (Akses Bendahara)
1. Penghuni melakukan konfirmasi pembayaran iuran asrama dengan mengunggah bukti transfer.
2. **Bendahara** memverifikasi data pembayaran di panel khusus bendahara.
3. Bendahara mengakses menu **Laporan Asrama**, memfilter data berdasarkan periode waktu.
4. Bendahara menekan tombol **"Export PDF"** atau **"Export Excel"**, dan sistem Laravel secara otomatis menghasilkan file unduhan dokumen laporan.

---

## 5. Rencana Endpoint / Route Laravel (Contoh)

```php
// Route untuk Tamu & Pendaftaran
Route::get('/pendaftaran', [PendaftaranController::class, 'create']);
Route::post('/pendaftaran', [PendaftaranController::class, 'store']);

// Route untuk Admin (Middleware: auth, role:admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index']);
    Route::resource('/pendaftaran', AdminPendaftaranController::class);
    Route::resource('/kamar', KamarController::class);
});

// Route untuk Bendahara & Admin (Middleware: auth, role:admin,bendahara)
Route::middleware(['auth', 'role:admin|bendahara'])->prefix('bendahara')->group(function () {
    Route::get('/pembayaran', [PembayaranController::class, 'index']);
    Route::post('/pembayaran/{id}/verifikasi', [PembayaranController::class, 'verifikasi']);
    
    Route::get('/laporan', [LaporanController::class, 'index']);
    Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf']);
    Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel']);
});
```

## 6. Tahapan Implementasi (Migration & Seeder)
1. Inisialisasi Project Laravel: `composer create-project laravel/laravel siap-kukar`
2. Konfigurasi koneksi database MySQL di file `.env`.
3. Pembuatan *Migration files* untuk tabel `pendaftaran`, `berkas_pendaratan`, `pembayaran`, dll.
4. Pembuatan *Seeder* untuk data awal akun Admin dan Bendahara default.
5. Implementasi logika *Upload File Storage* aman di Laravel.
6. Integrasi package PDF dan Excel untuk fitur laporan bendahara.