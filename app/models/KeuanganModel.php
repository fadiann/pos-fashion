<?php
class KeuanganModel{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }   
    public function KreditKode(){
        $date = date("dmy");
        //$value  = rand(1000, 10000);
        $value = date("his");
        $kodeTransaksi  = "K-".$date."-".$value;
        return $kodeTransaksi;
    }
    public function DebetKode(){
        $date = date("dmy");
        //$value  = rand(1000, 10000);
        $value = date("his");
        $kodeTransaksi  = "D-".$date."-".$value;
        return $kodeTransaksi;
    }
    //KREDIT
    public function simpan_kredit($kreditId, $kredit_kategori, $tanggal, $keterangan, $nominal){
        $sql   = "INSERT INTO tbl_kredit VALUES ('$kreditId', '$kredit_kategori', '$tanggal', '$keterangan', '$nominal')";
//        echo $sql;
        $this->db->query($sql);
    }    
    public function view_all_kredit(){
        $bulan = date('m');
        $sql = "SELECT * FROM tbl_kredit INNER JOIN tbl_kredit_kategori ON tbl_kredit.kredit_kategori_id = tbl_kredit_kategori.kredit_kategori_id  WHERE MONTH(tanggal) = '$bulan' ORDER BY tanggal DESC";
        $data      = $this->db->query($sql);
        $kolom    = array();
        while($row = $data->FetchRow()){
            $kolom[]   = $row;
        }
//        echo $sql;
        return $kolom;
    }

    //DEBET
    public function simpan_debet($debetId, $debet_kategori, $tanggal, $keterangan, $nominal){
        $sql   = "INSERT INTO tbl_debet VALUES ('$debetId', '$debet_kategori', '$tanggal', '$keterangan', '$nominal')";
        $this->db->query($sql);
    }
    public function view_all_debet(){
        $bulan = date('m');
        $sql = "SELECT * FROM tbl_debet INNER JOIN tbl_debet_kategori ON tbl_debet.debet_kategori_id = tbl_debet_kategori.debet_kategori_id 
        WHERE MONTH(tanggal) = '$bulan' ORDER BY tanggal DESC";
        $data      = $this->db->query($sql);
        $kolom    = array();
        while($row = $data->FetchRow()){
            $kolom[]   = $row;
        }
        //echo $sql;
        return $kolom;
    }
    public function view_filter_debet($filter){
        $bulan = date('m');
        $sql = "SELECT * FROM tbl_debet INNER JOIN tbl_debet_kategori ON tbl_debet.debet_kategori_id = tbl_debet_kategori.debet_kategori_id 
        WHERE MONTH(tanggal) = '$bulan' AND tbl_debet.debet_kategori_id = '$filter' ORDER BY tanggal DESC";
        $data      = $this->db->query($sql);
        $kolom    = array();
        while($row = $data->FetchRow()){
            $kolom[]   = $row;
        }
//        echo $sql;
        return $kolom;
    }
    
}