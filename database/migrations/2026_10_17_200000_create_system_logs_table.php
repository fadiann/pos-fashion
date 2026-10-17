<?php

return new class {
    /**
     * Jalankan migrasi: Buat tabel tbl_logs untuk audit trail, error tracking & page load
     *
     * @param Database $db Instance koneksi database
     */
    public function up($db)
    {
        $db->query("CREATE TABLE IF NOT EXISTS `tbl_logs` (
            `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `level` ENUM('INFO', 'ACTION', 'WARNING', 'ERROR') NOT NULL DEFAULT 'INFO',
            `category` VARCHAR(50) NOT NULL DEFAULT 'general',
            `user_id` INT NULL,
            `username` VARCHAR(100) NULL,
            `user_role` VARCHAR(50) NULL,
            `action` VARCHAR(150) NOT NULL,
            `description` TEXT NULL,
            `context` LONGTEXT NULL,
            `ip_address` VARCHAR(45) NULL,
            `user_agent` VARCHAR(255) NULL,
            `url` VARCHAR(255) NULL,
            INDEX `idx_level` (`level`),
            INDEX `idx_category` (`category`),
            INDEX `idx_username` (`username`),
            INDEX `idx_created_at` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Daftarkan modul 'log_sistem' ke tabel permissions
        $checkCol = $db->query("SELECT 1 FROM tbl_role_permissions WHERE module = 'log_sistem' LIMIT 1");
        if ($checkCol && $checkCol->RecordCount() == 0) {
            $db->query("INSERT IGNORE INTO tbl_role_permissions (role, module, can_access) VALUES ('admin', 'log_sistem', 1)");
            $db->query("INSERT IGNORE INTO tbl_role_permissions (role, module, can_access) VALUES ('owner', 'log_sistem', 1)");
            $db->query("INSERT IGNORE INTO tbl_role_permissions (role, module, can_access) VALUES ('kasir', 'log_sistem', 0)");
        }
    }

    /**
     * Rollback migrasi
     *
     * @param Database $db Instance koneksi database
     */
    public function down($db)
    {
        $db->query("DROP TABLE IF EXISTS `tbl_logs`");
        $db->query("DELETE FROM tbl_role_permissions WHERE module = 'log_sistem'");
    }
};
