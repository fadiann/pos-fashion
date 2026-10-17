# Dokumentasi Arsitektur & Riwayat Perubahan Aplikasi (HKPOS / Bakul Sepatu Solo)

Dokumen ini berfungsi sebagai buku panduan (*developer handbook*) dan catatan riwayat (*changelog*) bagi pengembang, pemilik sistem, maupun agent AI baru yang bekerja pada repository ini.

---

## 1. Ikhtisar Arsitektur Aplikasi
- **Bahasa & Framework**: PHP Native / Modular MVC Architecture
- **Database Layer**: ADODB PHP (`Adodb/adodb.inc.php`) dengan driver MySQLi.
- **Frontend Stack**:
  - Tailwind CSS (via CDN) + Custom Tokens & Dark Mode support.
  - Font Awesome 6 & Bootstrap Icons.
  - jQuery 3.x & Select2.
- **Struktur Direktori**:
  - `app/config/`: Konfigurasi basis data (`Database.php`), identitas toko (`store_config.json`), dan landing page (`landing_config.json`).
  - `app/controllers/`: Controller halaman utama (misal: `Transaksi.php`, `Barang.php`, `Laporan.php`).
  - `app/models/`: Lapisan data logic model lama (Active Record via ADODB).
  - `app/modules/`: Modul-modul fitur mandiri (misal: `migrasi/`, `role_akses/`, `pengaturan/`, `log_sistem/`, `stok/`, `kategori/`, `size/`, `pelanggan/`, `suplayer/`).
  - `app/views/`: Layout utama (`template.php`) dan tampilan antarmuka kasir/admin.
  - `app/lib/`: Core helper pustaka (misal: `AuthSession.php`, `RoleManager.php`, `Migrator.php`, `AppLogger.php`, `Comfunction.php`).
  - `database/migrations/`: File migrasi skema database versi terstruktur.

---

## 2. Standar Koneksi Database & Hosting
1. **Singleton Shared Connection**:
   - Class `Database` di `app/config/Database.php` menggunakan properti statis `private static $sharedConnection`.
   - **Tujuan**: Mencegah *exhausted connection* (`max_user_connections`) pada hosting cPanel/Cloud dengan me-reuse 1 koneksi database per HTTP request.
   - Dilengkapi *Retry Mechanism* 3 kali saat terjadi lonjakan beban server.
2. **Kerahasiaan Kredensial**:
   - `app/config/Database.php`, `deploy.py`, dan `deploy_config.json` terdaftar di `.gitignore`.

---

## 3. Sistem Hak Akses & Perizinan (RBAC)
- Menggunakan `RoleManager` (`app/lib/RoleManager.php`) dan tabel `tbl_role_permissions`.
- Terdapat 3 role bawaan:
  1. **Kasir POS (`kasir`)**: Hanya mengakses POS Kasir Transaksi.
  2. **Owner Toko (`owner`)**: Mengakses Dashboard, Katalog Produk, Stok, Master Data, Laporan Omset, dan Log Sistem.
  3. **Administrator Sistem (`admin`)**: Akses penuh ke seluruh modul termasuk Migrasi Database, Kelola Pengguna, Hak Akses Role, dan Pengaturan Sistem.

---

## 4. Sistem Logging & Audit Trail (`AppLogger`)
Fitur ini mencatat seluruh aktivitas operasional ke tabel `tbl_logs`:
- **Tingkat Level**:
  - `ACTION`: Perubahan data (transaksi baru, item ditambah, bayar, ubah harga produk, restok).
  - `INFO`: Navigasi dan pembukaan halaman oleh kasir/admin.
  - `WARNING`: Percobaan login gagal atau warning sistem.
  - `ERROR`: Kesalahan query atau uncaught PHP exception.
- **Pemanggilan Kode**:
  ```php
  AppLogger::action('kategori', 'Aksi Dilakukan', 'Deskripsi detail', ['key' => 'data']);
  AppLogger::info('kategori', 'Informasi');
  AppLogger::warning('kategori', 'Peringatan');
  AppLogger::error('kategori', 'Pesan Error', 'Trace detail');
  ```
- **Menu Navigasi**: Dapat diakses di menu **Pengaturan Sistem -> Log & Audit Sistem** (`index.php?main=log_sistem`).

---

## 5. Prosedur Deploy Hosting (`deploy.py`)
Deploy dilakukan secara otomatis menggunakan SSH/SFTP tanpa perlu memasukkan kredensial manual:
- Deploy file-file terbaru yang diubah:
  ```bash
  python deploy.py
  ```
- Deploy satu file tertentu:
  ```bash
  python deploy.py --file app/views/template.php
  ```
- Cek status web & konektivitas hosting:
  ```bash
  python deploy.py --status
  ```
- Full deploy:
  ```bash
  python deploy.py --all
  ```

---

## 6. Riwayat Perubahan (Changelog)

### [2026-10-17] - Logging System, Audit Trail & Connection Pool Optimization
- **Database Connection Pool**:
  - Mengimplementasikan pola Singleton `self::$sharedConnection` pada `app/config/Database.php` untuk mengatasi error `"Tidak Dapat Terhubung Ke Database"` pada hosting.
  - Menambahkan retry loop otomatis 3x dengan backoff delay 100ms.
- **Modul Log & Audit Sistem (`log_sistem`)**:
  - Dibuat migrasi `2026_10_17_200000_create_system_logs_table.php` untuk membuat tabel `tbl_logs`.
  - Dibuat class helper `AppLogger` (`app/lib/AppLogger.php`) dengan fungsi pencatatan otomatis audit trail, page views, dan penangkap error PHP global (`set_error_handler` & `set_exception_handler`).
  - Dibuat model `LogSistemModel.php`, controller `LogSistem.php`, dan antarmuka UI `log_sistem-view.php` dengan filter canggih (level, kategori, user, rentang tanggal, keyword pencarian), pagination, modal payload context, dan fitur pembersihan log (*purge old logs*).
  - Mengintegrasikan audit logging pada event: Login/Logout, Transaksi Baru, Tambah Item Belanja, Pembayaran Selesai, Ubah Harga Produk (HPP/Jual), Restok Cepat, serta error PHP.
  - Akses halaman biasa (load view) dilewati agar log fokus dan ringkas.
  - Mendaftarkan modul `log_sistem` ke RBAC `RoleManager` dan menu navigasi sidebar `template.php`.
- **Otomatisasi Deploy & Standar Agent**:
  - Dibuat script `deploy.py` berbasis Paramiko SFTP untuk deployment instan.
  - Dibuat `AGENTS.md` agar setiap agent/sesi baru langsung memahami arsitektur dan perintah deploy tanpa perlu penjelasan ulang.
- **Standarisasi Fitur Hapus Masal (Bulk Delete) pada Seluruh Master Data**:
  - Menambahkan checkbox checklist di header dan baris tabel pada seluruh 5 modul Master Data:
    1. **Master Ukuran / Size (`main=size`)**: Checklist, select all, floating dark action bar, `deleteMultipleSizes` di [SizeModel.php](file:///C:/laragon/www/bakulsepatusolo/app/modules/size/SizeModel.php), `postdelete_bulk` di [Size.php](file:///C:/laragon/www/bakulsepatusolo/app/modules/size/Size.php).
    2. **Master Kategori (`main=kategori`)**: Checklist, select all, floating dark action bar, `deleteMultipleKategori` di [KategoriModel.php](file:///C:/laragon/www/bakulsepatusolo/app/modules/kategori/KategoriModel.php), `postdelete_bulk` di [Kategori.php](file:///C:/laragon/www/bakulsepatusolo/app/modules/kategori/Kategori.php).
    3. **Master Pelanggan (`main=pelanggan`)**: Checklist, select all, floating dark action bar, `deleteMultiplePelanggan` di [PelangganModel.php](file:///C:/laragon/www/bakulsepatusolo/app/modules/pelanggan/PelangganModel.php), `postdelete_bulk` di [Pelanggan.php](file:///C:/laragon/www/bakulsepatusolo/app/modules/pelanggan/Pelanggan.php).
    4. **Master Supplier / Pemasok (`main=suplayer`)**: Checklist, select all, floating dark action bar, `deleteMultipleSuplayer` di [SuplayerModel.php](file:///C:/laragon/www/bakulsepatusolo/app/modules/suplayer/SuplayerModel.php), `postdelete_bulk` di [Suplayer.php](file:///C:/laragon/www/bakulsepatusolo/app/modules/suplayer/Suplayer.php).
    5. **Master Produk / Barang (`main=barang`)**: Checklist, tombol "Hapus Produk Terpilih" berdampingan dengan "Restok Produk Terpilih" pada floating action bar, `deleteMultipleBarang` di [BarangModel.php](file:///C:/laragon/www/bakulsepatusolo/app/models/BarangModel.php), `postdelete_bulk` di [Barang.php](file:///C:/laragon/www/bakulsepatusolo/app/controllers/Barang.php).
  - Setiap aksi penghapusan masal otomatis mencatat log audit trail ke `AppLogger::action('produk', 'Hapus Banyak ... (Bulk)', ...)` dengan rincian array ID yang dihapus.
  - Dilengkapi dialog konfirmasi jumlah data sebelum eksekusi untuk mencegah ketidaksengajaan pengguna.

