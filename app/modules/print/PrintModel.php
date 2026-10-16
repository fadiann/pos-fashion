<?php
class PrintModel {
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }   
    public function getProdukByIdTransaksi($transaksi_id){
        $sql     = "SELECT *, tbl_barang.harga_barang * tbl_pesanan.jumlah as subtotal FROM tbl_pesanan 
                INNER JOIN tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id 
                LEFT JOIN tbl_size on tbl_pesanan.pesanan_size_id = tbl_size.size_id
                WHERE transaksi_id = '$transaksi_id' order by tbl_pesanan.unix_id";
        // echo $sql;
        $res     = $this->db->query($sql);
        $items   = [];
        if($res){
            while($row = $res->FetchRow()){
                $items[] = $row;
            }
        }
        return $items;
    }
    
    public function getDetailTransaksi($kodeTransaksi){        
        $sql  = "SELECT * from tbl_transaksi 
                LEFT JOIN tbl_pelanggan ON tbl_pelanggan.pelanggan_id = tbl_transaksi.transaksi_pelanggan_id
                WHERE transaksi_id = '$kodeTransaksi'";
        $res  = $this->db->query($sql);
        $data = $res ? $res->FetchRow() : false;
        return $data;
    }
}