<?php
class PengaturanModel {
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }   
    public function getUserAll(){
        $sql    = "SELECT * FROM tbl_user 
                ORDER BY user_id";
        $data   = $this->db->query($sql);
        $arr = array();
        while($row = $data->FetchRow()){
            $arr[] = $row;
        }
        return $arr;
    }
    public function getStokByProdukId($produk_id){
        $sql    = "SELECT *, SUM(tbl_stok_masuk.stok_masuk_jumlah) as total_per_size FROM tbl_stok_masuk 
                INNER JOIN tbl_barang ON tbl_barang.barang_id = tbl_stok_masuk.stok_masuk_produk_id 
                INNER JOIN tbl_size ON tbl_size.size_id = tbl_stok_masuk.stok_masuk_size_id
                WHERE tbl_stok_masuk.stok_masuk_produk_id = '$produk_id' GROUP BY tbl_stok_masuk.stok_masuk_size_id";
                // echo $sql;
        // $data   = $this->db->assoc($sql);
        $data   = $this->db->query($sql)->GetArray();
        return $data;
    }
}