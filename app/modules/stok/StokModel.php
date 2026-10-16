<?php
class StokModel {
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }   
    public function getStokMasuk(){
        $sql    = "SELECT * FROM tbl_stok_masuk
                INNER JOIN tbl_barang ON tbl_barang.barang_id = tbl_stok_masuk.stok_masuk_produk_id
                INNER JOIN tbl_size ON tbl_size.size_id = tbl_stok_masuk.stok_masuk_size_id
                -- LEFT JOIN tbl_kategori ON tbl_kategori.kategori_id = tbl_size.size_kategori_id 
                ORDER BY stok_masuk_tanggal DESC";
        $data   = $this->db->query($sql)->GetArray();
        return $data;
    }
    public function getStokKeluar(){
        $sql    = "SELECT * FROM tbl_stok_keluar
                INNER JOIN tbl_barang ON tbl_barang.barang_id = tbl_stok_keluar.stok_keluar_produk_id
                INNER JOIN tbl_size ON tbl_size.size_id = tbl_stok_keluar.stok_keluar_size_id
                -- LEFT JOIN tbl_kategori ON tbl_kategori.kategori_id = tbl_size.size_kategori_id 
                ORDER BY stok_keluar_tanggal DESC";
        $data   = $this->db->query($sql)->GetArray();
        return $data;
    }
    public function deleteStokMasuk($id){
        $sql = "DELETE FROM tbl_stok_masuk where stok_masuk_id = '$id'";
        $this->db->query($sql);
    }
    public function deleteStokKeluar($id){
        $sql = "DELETE FROM tbl_stok_keluar where stok_keluar_id = '$id'";
        $this->db->query($sql);
    }
}