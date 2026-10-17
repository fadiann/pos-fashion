<?php

return new class {
    /**
     * Jalankan migrasi: Buat tabel tbl_role_permissions dan isi permission default.
     *
     * @param Database $db Instance koneksi database
     */
    public function up($db)
    {
        $db->query("CREATE TABLE IF NOT EXISTS `tbl_role_permissions` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `role` VARCHAR(50) NOT NULL,
            `module` VARCHAR(50) NOT NULL,
            `can_access` TINYINT(1) NOT NULL DEFAULT 0,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY `unique_role_module` (`role`, `module`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Daftar modul sistem
        $modules = [
            'transaksi',
            'dashboard',
            'barang',
            'stok',
            'size',
            'kategori',
            'pelanggan',
            'suplayer',
            'laporan',
            'website',
            'migrasi',
            'pengguna',
            'role_akses'
        ];

        // Default permission matrix
        $defaultMatrix = [
            'admin' => array_fill_keys($modules, 1),
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
                $check = $db->query("SELECT id FROM tbl_role_permissions WHERE role = '{$role}' AND module = '{$mod}'");
                if ($check && $check->RecordCount() == 0) {
                    $db->query("INSERT INTO tbl_role_permissions (role, module, can_access) VALUES ('{$role}', '{$mod}', {$can})");
                }
            }
        }
    }

    /**
     * Batalkan migrasi (Rollback).
     *
     * @param Database $db Instance koneksi database
     */
    public function down($db)
    {
        $db->query("DROP TABLE IF EXISTS `tbl_role_permissions`");
    }
};
