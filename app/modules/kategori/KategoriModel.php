<?php
class KategoriModel {
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }   
    public function getAllKategori(){
        $sql    = "SELECT * FROM tbl_kategori ORDER BY kategori_id";
        $data   = $this->db->query($sql);
        $barang = array();
        while($row = $data->FetchRow()){
            $barang[] = $row;
        }
        return $barang;
    }
    public function addKategori($kategori_nama, $kategori_keterangan, $kategori_status){
        $sql = "INSERT INTO tbl_kategori (kategori_id, kategori_nama, kategori_keterangan, kategori_status) VALUES (NULL, '$kategori_nama', '$kategori_keterangan', '$kategori_status')";
        $this->db->query($sql);
    }    
    public function getEditKategori($kategori_id){
        $sql    = "SELECT * FROM tbl_kategori where kategori_id = '$kategori_id'";
        $data   = $this->db->query($sql);
        $barang = array();
        while($row = $data->FetchRow()){
            $barang[] = $row;
        }
        return $barang;
    }
    public function saveEditKategori($kategori_id, $kategori_nama, $kategori_keterangan, $kategori_status){
        $sql = "UPDATE tbl_kategori SET kategori_nama = '$kategori_nama', kategori_keterangan = '$kategori_keterangan', kategori_status = '$kategori_status' WHERE kategori_id = '$kategori_id'";
        $this->db->query($sql);
    }
    public function deleteKategori($kategori_id){
        $sql = "DELETE FROM tbl_kategori where kategori_id = '$kategori_id'";
        $this->db->query($sql);
    }
}