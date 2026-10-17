<?php

return new class {
    /**
     * Jalankan migrasi.
     *
     * @param Database $db
     */
    public function up($db)
    {
        // 1. Pastikan tabel penting ada (contoh: tbl_barang memiliki indeks dan kolom barcode jika belum ada)
        $checkCol = $db->query("SHOW COLUMNS FROM `tbl_barang` LIKE 'barang_barcode'");
        if ($checkCol && $checkCol->RecordCount() == 0) {
            $db->query("ALTER TABLE `tbl_barang` ADD COLUMN `barang_barcode` VARCHAR(100) NULL AFTER `barang_nama`");
        }

        // 2. Tambahkan kolom catatan/keterangan pada tbl_transaksi jika belum ada
        $checkTrx = $db->query("SHOW COLUMNS FROM `tbl_transaksi` LIKE 'transaksi_catatan'");
        if ($checkTrx && $checkTrx->RecordCount() == 0) {
            $db->query("ALTER TABLE `tbl_transaksi` ADD COLUMN `transaksi_catatan` TEXT NULL AFTER `promo_nominal`");
        }
    }

    /**
     * Batalkan migrasi.
     *
     * @param Database $db
     */
    public function down($db)
    {
        // Kembalikan perubahan
        $checkCol = $db->query("SHOW COLUMNS FROM `tbl_barang` LIKE 'barang_barcode'");
        if ($checkCol && $checkCol->RecordCount() > 0) {
            $db->query("ALTER TABLE `tbl_barang` DROP COLUMN `barang_barcode`");
        }

        $checkTrx = $db->query("SHOW COLUMNS FROM `tbl_transaksi` LIKE 'transaksi_catatan'");
        if ($checkTrx && $checkTrx->RecordCount() > 0) {
            $db->query("ALTER TABLE `tbl_transaksi` DROP COLUMN `transaksi_catatan`");
        }
    }
};
