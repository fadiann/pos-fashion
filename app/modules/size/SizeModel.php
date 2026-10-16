<?php
class SizeModel {
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }   
    public function getAllSize(){
        $sql    = "SELECT * FROM tbl_size
                LEFT JOIN tbl_kategori ON tbl_kategori.kategori_id = tbl_size.size_kategori_id 
                ORDER BY size_id";
        $data   = $this->db->query($sql);
        $barang = array();
        while($row = $data->FetchRow()){
            $barang[] = $row;
        }
        return $barang;
    }
    public function addSize($size_nama, $size_kategori_id){
        $sql = "INSERT INTO tbl_size (size_id, size_kategori_id, size_nama) VALUES (NULL, '$size_kategori_id',  '$size_nama')";
        // echo $sql;
        // die();
        $this->db->query($sql);
    }    
    public function getEditSize($size_id){
        $sql    = "SELECT * FROM tbl_size 
                LEFT JOIN tbl_kategori ON tbl_kategori.kategori_id = tbl_size.size_kategori_id 
                where size_id = '$size_id'";
        $data   = $this->db->query($sql);
        $barang = $data->FetchRow();
        return $barang;
    }
    public function saveEditSize($size_id, $size_nama, $size_kategori_id){
        $sql = "UPDATE tbl_size SET size_nama = '$size_nama', size_kategori_id = '$size_kategori_id' WHERE size_id = '$size_id'";
        $this->db->query($sql);
    }
    public function deleteSize($size_id){
        $sql = "DELETE FROM tbl_size where size_id = '$size_id'";
        $this->db->query($sql);
    }
}