<?php
class PelangganModel {
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }   
    public function getAllPelanggan(){
        $sql    = "SELECT * FROM tbl_pelanggan ORDER BY pelanggan_id";
        $data   = $this->db->query($sql);
        $barang = array();
        while($row = $data->FetchRow()){
            $barang[] = $row;
        }
        return $barang;
    }
    public function addPelanggan($pelanggan_id, $pelanggan_nama, $pelanggan_nomor, $pelanggan_alamat, $pelanggan_status){
        $sql = "INSERT INTO tbl_pelanggan (pelanggan_id, pelanggan_nama, pelanggan_nomor, pelanggan_alamat, pelanggan_status) VALUES ('$pelanggan_id', '$pelanggan_nama', '$pelanggan_nomor', '$pelanggan_alamat', '$pelanggan_status')";
        $this->db->query($sql);
    }    
    public function getEditPelanggan($barangId){
        $sql    = "SELECT * FROM tbl_pelanggan where pelanggan_id = '$barangId'";
        $data   = $this->db->query($sql);
        $barang = array();
        while($row = $data->FetchRow()){
            $barang[] = $row;
        }
        return $barang;
    }
    public function saveEditPelanggan($pelanggan_id, $pelanggan_nama, $pelanggan_nomor, $pelanggan_alamat, $pelanggan_status){
        $sql = "UPDATE tbl_pelanggan SET pelanggan_nama = '$pelanggan_nama', pelanggan_nomor = '$pelanggan_nomor', pelanggan_alamat = '$pelanggan_alamat', pelanggan_status = '$pelanggan_status' WHERE pelanggan_id = '$pelanggan_id'";
        $this->db->query($sql);
    }
    public function deletePelanggan($pelanggan_id){
        $sql = "DELETE FROM tbl_pelanggan where pelanggan_id = '$pelanggan_id'";
        $this->db->query($sql);
    }
}