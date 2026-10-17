<?php

return new class {
    /**
     * Jalankan migrasi: Tambahkan akun default owner jika belum ada.
     *
     * @param Database $db Instance koneksi database
     */
    public function up($db)
    {
        $check = $db->query("SELECT * FROM `tbl_users` WHERE `username` = 'owner'");
        if ($check && $check->RecordCount() == 0) {
            $pass = md5('owner');
            $db->query("INSERT INTO `tbl_users` (`username`, `password`, `level`, `status`) VALUES ('owner', '{$pass}', 'owner', 1)");
        }
    }

    /**
     * Batalkan migrasi (Rollback).
     *
     * @param Database $db Instance koneksi database
     */
    public function down($db)
    {
        $db->query("DELETE FROM `tbl_users` WHERE `username` = 'owner'");
    }
};
