# POS Fashion — Modern Point of Sales & Retail Store Management System

A modern, fast, responsive web-based Point of Sale (POS) and inventory management system designed for fashion, footwear, apparel, and general retail stores. Built with PHP, Tailwind CSS, ADODB, and MySQL.

---

## 🌟 Fitur Utama (Key Features)

### 1. ⚡ Layar Kasir Point of Sales (POS) Touch-Friendly
- **Katalog Produk Cepat**: Navigasi kategori interaktif, pencarian langsung (instant search), dan kartu produk yang responsif.
- **Pemilihan Ukuran Dinamis**: Modal pemilihan varian ukuran/size produk dengan indikator ketersediaan stok realtime.
- **Penyederhanaan Metode Pembayaran**:
  - 💵 **Tunai (Cash)**
  - 📱 **Non Tunai (QRIS / Transfer)**
- **Pilihan Uang Cepat (Tap) Pintar**:
  - Tombol pecahan uang otomatis menyesuaikan nilai yang mendekati total belanja (*Uang Pas*, pembulatan kelipatan terdekat, dll.).
  - Semua nominal uang terformat rapi dengan tanda titik (`.`), misal: `50.000`, `100.000`, `150.000`.
- **Cetak Struk Kasir Termal**:
  - Mendukung printer thermal ukuran **58mm** dan **80mm**.
  - Header & footer toko yang dapat dikonfigurasi secara dinamis.
- **Widget Ringkasan Transaksi Kasir Harian**:
  - Menampilkan total omset kasir hari ini, jumlah transaksi lunas, total pcs terjual, dan status transaksi yang tertunda.
  - Dilengkapi fitur *minimize/expand* yang tersimpan di browser (`localStorage`).

### 2. 📦 Manajemen Master Data & Paging Universal
- **Paging / Pagination di Semua Master Data**:
  - Tersedia di: **Data Produk**, **Kategori**, **Ukuran/Size**, **Pelanggan**, **Supplier**, **Riwayat Stok Masuk**, **Riwayat Stok Keluar**, dan **Pengguna Sistem**.
  - Pilihan jumlah baris per halaman: `10`, `25`, `50`, `100`, atau `Semua`.
  - Terintegrasi otomatis dengan kotak pencarian dan filter kategori tanpa perlu reload halaman.
- **Restok Cepat Satuan**: Tambah persediaan stok langsung dari baris tabel tanpa pindah halaman.
- **Restok Masal (Bulk Restock)**:
  - Centang beberapa produk di tabel lalu restok sekaligus dalam satu modal batch.
  - Opsi terapkan jumlah dan supplier yang sama ke semua item sekaligus.
- **Inline Editing Harga**:
  - Klik angka nominal **Harga Jual** atau **HPP** pada tabel untuk mengubah harga secara instan dengan auto-save AJAX.
- **Kode Produk Unik Otomatis**: Generator otomatis kode ID produk (misal: `BRG-2610-8A3F`) saat penambahan produk baru.

### 3. 👥 Manajemen Akses & Pengguna Sistem (RBAC)
- **Role Pengguna**: Dukungan multi-level user (**Owner**, **Admin**, **Kasir**).
- **Matriks Hak Akses Dinamis**: Administrator dapat mengatur perizinan modul menu untuk masing-masing level user via checklist interaktif.

### 4. 📊 Dashboard Analitik & Laporan Keuangan
- **Valuasi Nilai Aset**: Menghitung total nilai modal (HPP), estimasi nilai jual, serta sisa valuasi persediaan di gudang.
- **Ringkasan Omset Harian**: Pemisahan jelas antara pendapatan Tunai, Non Tunai, dan total diskon promo.
- **Laporan Penjualan**:
  - Riwayat transaksi harian.
  - Rekapitulasi omset per tanggal dalam satu bulan dengan perhitungan laba bersih.
  - Peringkat tren produk paling laris (*Best Seller*).

### 5. 🌐 Landing Page & Pengaturan Toko
- **Landing Page Modern**: Tampilan etalase publik yang bersih dengan bio profil toko dan akses cepat ke panel kasir.
- **Pengaturan Toko**: Konfigurasi nama toko, alamat, kontak WhatsApp, profil, dan catatan struk kasir melalui GUI.
- **Dukungan Dark Mode**: Beralih tema gelap/terang secara mulus di seluruh halaman sistem.

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

- **Backend**: PHP 8.x (Native MVC-like architecture)
- **Database Layer**: ADODB (`adodb.inc.php`) & MySQL / MariaDB
- **Frontend Styling**: [Tailwind CSS](https://tailwindcss.com/) & [Bootstrap 5.3](https://getbootstrap.com/)
- **Icons & Typography**: Font Awesome 6, Bootstrap Icons, Google Font *Plus Jakarta Sans* & *Syne*
- **Libraries**: jQuery, Select2, Chart.js

---

## 🚀 Panduan Instalasi (Installation Guide)

### 1. Prasyarat Sistem
- Web server lokal (misal: **Laragon**, **XAMPP**, atau **WAMP**)
- PHP versi 7.4 / 8.0 / 8.1 / 8.2
- MySQL / MariaDB

### 2. Kloning Repositori
```bash
git clone https://github.com/fadiann/pos-fashion.git
```
Pindahkan direktori hasil clone ke folder web server Anda (contoh di Laragon: `C:\laragon\www\pos-fashion` atau `bakulsepatusolo`).

### 3. Konfigurasi File Lingkungan
Salin file template konfigurasi:
```bash
cp app/config/Database.php.example app/config/Database.php
cp app/config/store_config.json.example app/config/store_config.json
cp app/config/landing_config.json.example app/config/landing_config.json
```

Buka file `app/config/Database.php` dan sesuaikan kredensial database Anda:
```php
class Database {
    public $dbhost   = "localhost";
    public $dbuser   = "root";
    public $dbpass   = ""; // Password database Anda (default Laragon: root, XAMPP: kosong)
    public $dbname   = "pos_fashion";
    public $dbdriver = "mysqli";
}
```

### 4. Jalankan Aplikasi
Buka browser dan akses:
- **Landing Page**: `http://localhost/pos-fashion/`
- **Layar Kasir POS**: `http://localhost/pos-fashion/index.php?main=transaksi`
- **Login Admin/Kasir**: `http://localhost/pos-fashion/index.php?main=login`

---

## 👤 Kredit & Pengembang (Credits)

Proyek ini dikembangkan dan disempurnakan oleh:

**Fadian Adhitya Nugraha**  
- GitHub: [@fadiann](https://github.com/fadiann)

---

## 📄 Lisensi (License)

Hak Cipta © 2026 **Fadian Adhitya Nugraha**. Seluruh hak cipta dilindungi undang-undang.
Dilisensikan di bawah [MIT License](LICENSE).
