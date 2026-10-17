<?php

class RoleManager {
    private static $cache = [];

    /**
     * Dapatkan instance Database
     */
    private static function getDb() {
        if (!class_exists('Database')) {
            require_once dirname(__DIR__) . '/config/Database.php';
        }
        return new Database();
    }

    /**
     * Daftar seluruh modul yang dapat diatur hak aksesnya
     */
    public static function getModules() {
        return [
            'transaksi' => [
                'name'        => 'Transaksi Kasir (POS)',
                'group'       => 'Kasir & Utama',
                'icon'        => 'fa-solid fa-cash-register text-emerald-500',
                'description' => 'Akses layar penjualan kasir, scan barcode, keranjang dan cetak struk'
            ],
            'dashboard' => [
                'name'        => 'Dashboard Toko',
                'group'       => 'Kasir & Utama',
                'icon'        => 'fa-solid fa-chart-pie text-blue-500',
                'description' => 'Melihat ringkasan omset harian, grafik penjualan, dan valuasi aset'
            ],
            'barang' => [
                'name'        => 'Data Produk',
                'group'       => 'Master Data',
                'icon'        => 'fa-solid fa-box-archive text-amber-500',
                'description' => 'Tambah, ubah, dan hapus master katalog produk'
            ],
            'stok' => [
                'name'        => 'Manajemen Stok',
                'group'       => 'Master Data',
                'icon'        => 'fa-solid fa-boxes-stacked text-indigo-500',
                'description' => 'Log riwayat stok masuk (pembelian) dan stok keluar'
            ],
            'size' => [
                'name'        => 'Data Ukuran / Size',
                'group'       => 'Master Data',
                'icon'        => 'fa-solid fa-ruler-horizontal text-sky-500',
                'description' => 'Kelola master variasi ukuran produk'
            ],
            'kategori' => [
                'name'        => 'Kategori Produk',
                'group'       => 'Master Data',
                'icon'        => 'fa-solid fa-tags text-purple-500',
                'description' => 'Kelola pengelompokan kategori barang'
            ],
            'pelanggan' => [
                'name'        => 'Data Pelanggan',
                'group'       => 'Master Data',
                'icon'        => 'fa-solid fa-users text-teal-500',
                'description' => 'Kelola database pelanggan dan member toko'
            ],
            'suplayer' => [
                'name'        => 'Data Supplier',
                'group'       => 'Master Data',
                'icon'        => 'fa-solid fa-truck text-orange-500',
                'description' => 'Kelola vendor, distributor, dan pemasok stok'
            ],
            'laporan' => [
                'name'        => 'Laporan Penjualan',
                'group'       => 'Laporan Keuangan',
                'icon'        => 'fa-solid fa-chart-line text-emerald-500',
                'description' => 'Akses laporan keuangan, omset, HPP, margin laba dan terlaris'
            ],
            'website' => [
                'name'        => 'Pengaturan Toko',
                'group'       => 'Pengaturan Toko',
                'icon'        => 'fa-solid fa-store text-amber-500',
                'description' => 'Identitas toko, kontak, format struk, dan landing page'
            ],
            'migrasi' => [
                'name'        => 'Migrasi Database',
                'group'       => 'Pengaturan Sistem',
                'icon'        => 'fa-solid fa-database text-cyan-500',
                'description' => 'Eksekusi dan rollback skema migrasi tabel database'
            ],
            'pengguna' => [
                'name'        => 'Kelola Pengguna',
                'group'       => 'Pengaturan Sistem',
                'icon'        => 'fa-solid fa-users-gear text-purple-500',
                'description' => 'Buat, ubah password, dan kelola akun login sistem'
            ],
            'role_akses' => [
                'name'        => 'Hak Akses Role (RBAC)',
                'group'       => 'Pengaturan Sistem',
                'icon'        => 'fa-solid fa-user-shield text-rose-500',
                'description' => 'Matriks perizinan dan kewenangan akses modul per group'
            ],
            'log_sistem' => [
                'name'        => 'Log & Audit Sistem',
                'group'       => 'Pengaturan Sistem',
                'icon'        => 'fa-solid fa-clock-rotate-left text-amber-500',
                'description' => 'Monitoring aktivitas user, mutasi data, audit trail, dan error log'
            ]
        ];
    }

    /**
     * Daftar grup role yang dapat diatur
     */
    public static function getRoles() {
        return [
            'kasir' => [
                'label'       => 'Kasir POS',
                'badge_class' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                'icon'        => 'fa-solid fa-cash-register'
            ],
            'owner' => [
                'label'       => 'Owner Toko',
                'badge_class' => 'bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                'icon'        => 'fa-solid fa-crown'
            ],
            'admin' => [
                'label'       => 'Administrator Sistem',
                'badge_class' => 'bg-purple-100 text-purple-800 dark:bg-purple-950/80 dark:text-purple-300 border-purple-200 dark:border-purple-800',
                'icon'        => 'fa-solid fa-shield-halved'
            ]
        ];
    }

    /**
     * Cek apakah role tertentu memiliki izin akses ke modul tertentu
     * 
     * @param string $role (misal: 'admin', 'owner', 'kasir')
     * @param string $module (misal: 'laporan', 'barang', 'migrasi')
     * @return bool
     */
    public static function can($role, $module) {
        // Administrator sistem selalu memiliki izin penuh
        if ($role === 'admin') {
            return true;
        }

        // Jika modul tidak terdaftar, izinkan jika bukan area sensitif
        $modules = self::getModules();
        if (!isset($modules[$module])) {
            return true;
        }

        $key = "{$role}_{$module}";
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        $db = self::getDb();
        $sql = "SELECT can_access FROM tbl_role_permissions WHERE role = '{$role}' AND module = '{$module}' LIMIT 1";
        $res = $db->query($sql);
        if ($res && ($row = $res->FetchRow())) {
            $allowed = (int)$row['can_access'] === 1;
            self::$cache[$key] = $allowed;
            return $allowed;
        }

        // Fallback default jika record belum ada
        if ($role === 'owner') {
            // Default owner: boleh semua kecuali website, pengguna, migrasi, role_akses
            $systemMods = ['website', 'pengguna', 'migrasi', 'role_akses'];
            $allowed = !in_array($module, $systemMods);
            self::$cache[$key] = $allowed;
            return $allowed;
        }

        if ($role === 'kasir') {
            // Default kasir: hanya transaksi
            $allowed = ($module === 'transaksi');
            self::$cache[$key] = $allowed;
            return $allowed;
        }

        return false;
    }

    /**
     * Ambil seluruh matriks permission dari database
     */
    public static function getPermissionMatrix() {
        $db = self::getDb();
        $sql = "SELECT role, module, can_access FROM tbl_role_permissions";
        $res = $db->query($sql);
        $matrix = [];

        if ($res) {
            while ($row = $res->FetchRow()) {
                $matrix[$row['role']][$row['module']] = (int)$row['can_access'];
            }
        }

        return $matrix;
    }

    /**
     * Simpan perubahan matriks hak akses untuk role tertentu
     */
    public static function saveRolePermissions($role, array $modulesAllowed) {
        $db = self::getDb();
        $allModules = array_keys(self::getModules());

        foreach ($allModules as $mod) {
            $canAccess = in_array($mod, $modulesAllowed) ? 1 : 0;
            // Admin tetap 1 untuk modul sistem agar tidak terkunci sendiri
            if ($role === 'admin') {
                $canAccess = 1;
            }

            $sql = "INSERT INTO tbl_role_permissions (role, module, can_access) 
                    VALUES ('{$role}', '{$mod}', {$canAccess})
                    ON DUPLICATE KEY UPDATE can_access = {$canAccess}";
            $db->query($sql);
        }

        // Bersihkan cache runtime
        self::$cache = [];
        return true;
    }

    /**
     * Reset hak akses role ke konfigurasi default
     */
    public static function resetToDefault() {
        $db = self::getDb();
        $db->query("TRUNCATE TABLE tbl_role_permissions");

        $allModules = array_keys(self::getModules());

        $defaultMatrix = [
            'admin' => array_fill_keys($allModules, 1),
            'owner' => [
                'transaksi'   => 1,
                'dashboard'   => 1,
                'barang'      => 1,
                'stok'        => 1,
                'size'        => 1,
                'kategori'    => 1,
                'pelanggan'   => 1,
                'suplayer'    => 1,
                'laporan'     => 1,
                'website'     => 0,
                'migrasi'     => 0,
                'pengguna'    => 0,
                'role_akses'  => 0,
            ],
            'kasir' => [
                'transaksi'   => 1,
                'dashboard'   => 0,
                'barang'      => 0,
                'stok'        => 0,
                'size'        => 0,
                'kategori'    => 0,
                'pelanggan'   => 0,
                'suplayer'    => 0,
                'laporan'     => 0,
                'website'     => 0,
                'migrasi'     => 0,
                'pengguna'    => 0,
                'role_akses'  => 0,
            ]
        ];

        foreach ($defaultMatrix as $role => $mods) {
            foreach ($mods as $mod => $can) {
                $db->query("INSERT INTO tbl_role_permissions (role, module, can_access) VALUES ('{$role}', '{$mod}', {$can})");
            }
        }

        self::$cache = [];
        return true;
    }
}
