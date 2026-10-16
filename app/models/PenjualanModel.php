<?php
class PenjualanModel{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }   
    public function getJualMakanan(){
        $bulan = date("m");
        $tahun = date("Y");
        $sql   = "SELECT tbl_pesanan.barang_id, tbl_barang.nama_barang, SUM(tbl_pesanan.jumlah) as pcs, MONTH(tanggal) as bulan FROM tbl_transaksi inner join tbl_pesanan on tbl_transaksi.transaksi_id = tbl_pesanan.transaksi_id inner join tbl_barang on tbl_barang.barang_id = tbl_pesanan.barang_id WHERE MONTH(tbl_transaksi.tanggal) = '".$bulan."' AND YEAR(tbl_transaksi.tanggal) = '".$tahun."' AND tbl_pesanan.barang_id NOT LIKE '%MI%' GROUP BY tbl_pesanan.barang_id ORDER BY pcs DESC";
        $data      = $this->db->query($sql);
        $jualMakanan= array();
        while($row = $data->FetchRow()){
            $jualMakanan[]  = $row;
        }
        return $jualMakanan;
    }
    public function getJualMinuman(){
        $bulan = date("m");
        $tahun = date("Y");
        $sql   = "SELECT tbl_pesanan.barang_id, tbl_barang.nama_barang, SUM(tbl_pesanan.jumlah) as pcs FROM tbl_transaksi inner join tbl_pesanan on tbl_transaksi.transaksi_id = tbl_pesanan.transaksi_id inner join tbl_barang on tbl_barang.barang_id = tbl_pesanan.barang_id WHERE MONTH(tbl_transaksi.tanggal) = '".$bulan."' AND YEAR(tbl_transaksi.tanggal) = '".$tahun."' AND tbl_pesanan.barang_id LIKE '%MI%' GROUP BY tbl_pesanan.barang_id ORDER BY pcs DESC";
        $data      = $this->db->query($sql);
        $jualMinuman= array();
        while($row = $data->FetchRow()){
            $jualMinuman[]  = $row;
        }
        return $jualMinuman;
    }    
    public function getJualMakananHari($tanggal){
        $sql   = "SELECT tbl_pesanan.barang_id, tbl_barang.nama_barang, SUM(tbl_pesanan.jumlah) as pcs FROM tbl_transaksi inner join tbl_pesanan on tbl_transaksi.transaksi_id = tbl_pesanan.transaksi_id inner join tbl_barang on tbl_barang.barang_id = tbl_pesanan.barang_id WHERE tanggal LIKE '%$tanggal%' AND tbl_pesanan.barang_id LIKE '%MK%' GROUP BY tbl_pesanan.barang_id ORDER BY pcs DESC";
        $data      = $this->db->query($sql);
        $jualMakanan= array();
        while($row = $data->FetchRow()){
            $jualMakanan[]  = $row;
        }
        return $jualMakanan;
    }
    public function getJualMinumanHari($tanggal){
        $sql   = "SELECT tbl_pesanan.barang_id, tbl_barang.nama_barang, SUM(tbl_pesanan.jumlah) as pcs FROM tbl_transaksi inner join tbl_pesanan on tbl_transaksi.transaksi_id = tbl_pesanan.transaksi_id inner join tbl_barang on tbl_barang.barang_id = tbl_pesanan.barang_id WHERE tbl_transaksi.tanggal LIKE '%$tanggal%' AND tbl_pesanan.barang_id LIKE '%MN%' GROUP BY tbl_pesanan.barang_id ORDER BY pcs DESC";
        $data      = $this->db->query($sql);
        $jualMinuman= array();
        while($row = $data->FetchRow()){
            $jualMinuman[]  = $row;
        }
        return $jualMinuman;
    }
    
}
?>