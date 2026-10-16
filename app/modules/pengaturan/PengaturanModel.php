<?php
class PengaturanModel {
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }   
    public function getUserAll(){
        $sql    = "SELECT * FROM tbl_users ORDER BY level, user_id";
        $data   = $this->db->query($sql)->GetArray();
        return $data;
    }
    public function create_user($username, $passwd, $level){
        $username = addslashes(trim($username));
        $passwd   = addslashes($passwd);
        $level    = in_array($level, ['kasir', 'admin', 'owner']) ? $level : 'kasir';
        $sql      = "INSERT INTO tbl_users VALUES (NULL, '$username', '$passwd', '$level', '1')";
        $this->db->query($sql);
    }
    public function update_user_pass($user_id, $username, $cpassword, $level){
        $user_id   = (int)$user_id;
        $username  = addslashes(trim($username));
        $cpassword = addslashes($cpassword);
        $level     = in_array($level, ['kasir', 'admin', 'owner']) ? $level : 'kasir';
        $sql       = "UPDATE `tbl_users` SET `username` = '$username', `password` = '$cpassword', `level` = '$level' WHERE `tbl_users`.`user_id` = '$user_id'";
        $this->db->query($sql);
    }
    public function update_user($user_id, $username, $level){
        $user_id  = (int)$user_id;
        $username = addslashes(trim($username));
        $level    = in_array($level, ['kasir', 'admin', 'owner']) ? $level : 'kasir';
        $sql      = "UPDATE `tbl_users` SET `username` = '$username', `level` = '$level' WHERE `tbl_users`.`user_id` = '$user_id'";
        $this->db->query($sql);
    }
    public function getUserById($user_id){
        $user_id = (int)$user_id;
        $sql     = "SELECT * FROM tbl_users WHERE user_id = '$user_id'";
        $data    = $this->db->query($sql)->FetchRow();
        return $data;
    }
    public function delete_user($user_id){
        $user_id = (int)$user_id;
        $sql     = "DELETE FROM tbl_users WHERE user_id = '$user_id'";
        $this->db->query($sql);
    }
    public function getStokByProdukId($produk_id){
        $sql    = "SELECT *, SUM(tbl_stok_masuk.stok_masuk_jumlah) as total_per_size FROM tbl_stok_masuk 
                INNER JOIN tbl_barang ON tbl_barang.barang_id = tbl_stok_masuk.stok_masuk_produk_id 
                INNER JOIN tbl_size ON tbl_size.size_id = tbl_stok_masuk.stok_masuk_size_id
                WHERE tbl_stok_masuk.stok_masuk_produk_id = '$produk_id' GROUP BY tbl_stok_masuk.stok_masuk_size_id";
        $data   = $this->db->query($sql)->GetArray();
        return $data;
    }
}