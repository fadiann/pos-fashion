<?php
class SuplayerModel {
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }   
    public function getAllSuplayer(){
        $sql    = "SELECT * FROM tbl_suplayer ORDER BY suplayer_id";
        $data   = $this->db->query($sql);
        $barang = array();
        while($row = $data->FetchRow()){
            $barang[] = $row;
        }
        return $barang;
    }
    public function addSuplayer($suplayer_id, $suplayer_nama, $suplayer_nomor, $suplayer_alamat, $suplayer_status){
        $sql = "INSERT INTO tbl_suplayer (suplayer_id, suplayer_nama, suplayer_nomor, suplayer_alamat, suplayer_status) VALUES ('$suplayer_id', '$suplayer_nama', '$suplayer_nomor', '$suplayer_alamat', '$suplayer_status')";
        $this->db->query($sql);
    }    
    public function getEditSuplayer($barangId){
        $sql    = "SELECT * FROM tbl_suplayer where suplayer_id = '$barangId'";
        $data   = $this->db->query($sql);
        $barang = array();
        while($row = $data->FetchRow()){
            $barang[] = $row;
        }
        return $barang;
    }
    public function saveEditSuplayer($suplayer_id, $suplayer_nama, $suplayer_nomor, $suplayer_alamat, $suplayer_status){
        $sql = "UPDATE tbl_suplayer SET suplayer_nama = '$suplayer_nama', suplayer_nomor = '$suplayer_nomor', suplayer_alamat = '$suplayer_alamat', suplayer_status = '$suplayer_status' WHERE suplayer_id = '$suplayer_id'";
        $this->db->query($sql);
    }
    public function deleteSuplayer($suplayer_id){
        $sql = "DELETE FROM tbl_suplayer where suplayer_id = '$suplayer_id'";
        $this->db->query($sql);
    }
}