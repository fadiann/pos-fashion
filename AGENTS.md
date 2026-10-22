# Standar Proyek & Petunjuk Operasional Agent (HKPOS / Bakul Sepatu Solo)

Dokumen ini dibaca secara otomatis oleh AI Assistant / Agent pada setiap awal sesi di workspace ini.

---

## 1. Lingkungan & Server Hosting
- **Production URL**: `https://bakulsepatusolo.com` (sebelumnya `https://bss.hekyacafe.com`)
- **Root Directory Local**: `C:\laragon\www\bakulsepatusolo`
- **Remote Public HTML**: `/home/u130940415/domains/bakulsepatusolo.com/public_html`
- **Database Engine**: MySQL / ADODB PHP (`u130940415_bss`)

---

## 2. Prosedur Deploy Otomatis (Wajib Diketahui Agent)
Telah disediakan script deployment siap pakai di root proyek: `deploy.py`.
Agent **TIDAK PERLU** menanyakan kredensial SSH / FTP atau meminta instruksi ulang dari user.

### Cara Menjalankan Deploy:
1. **Deploy Perubahan Terbaru (Smart Deploy / Git Changed Files):**
   ```bash
   python deploy.py
   ```
   *Secara cerdas mendeteksi file yang diubah dan mengunggahnya ke server hosting.*

2. **Deploy File Tertentu (Contoh: hanya template atau controller):**
   ```bash
   python deploy.py --file app/views/template.php
   python deploy.py --file app/config/Database.php
   ```

3. **Cek Status & Konektivitas Hosting:**
   ```bash
   python deploy.py --status
   ```

4. **Full Deploy (Seluruh Kode Aplikasi):**
   ```bash
   python deploy.py --all
   ```

---

## 3. Aturan Database & Keamanan
1. **Singleton Connection**:
   - Class `Database` di `app/config/Database.php` menggunakan pola `self::$sharedConnection`.
   - Jangan pernah menghapus Singleton atau menutup koneksi secara agresif di dalam loop/destructor model, karena hosting membatasi koneksi per user (`max_user_connections`).
2. **Kerahasiaan Kredensial**:
   - `deploy.py` dan `app/config/Database.php` masuk dalam `.gitignore` untuk mencegah kebocoran kredensial ke Git publik.
