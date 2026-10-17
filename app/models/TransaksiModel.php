<?php

class TransaksiModel{    

    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }   
    public function kodeTran(){
        $date = date("dmy");
        //$value  = rand(1000, 10000);
        $value         = date("his");
        $kodeTransaksi = "TR-".$date."-".$value;
        return $kodeTransaksi;
    }
    public function getPelangganById($pelanggan_id){        
        $sql  = "SELECT * from tbl_pelanggan where pelanggan_id = '$pelanggan_id'";
        $data = $this->db->query($sql)->FetchRow();
        return $data;
    }
    public function cekKodeTran($kodeTransaksi){        
        $sql  = "SELECT * from tbl_transaksi where transaksi_id = '$kodeTransaksi'";
        $data = $this->db->query($sql)->FetchRow();
        return $data;
    }
    public function getbarang($keyword){        
        $sql     = "SELECT * FROM tbl_barang WHERE nama_barang LIKE '%$keyword%'";
        $data    = $this->db->assc($sql);
        return $data;
    }
    public function getsizebyproduk($keyword){        
        $sql     = "SELECT * FROM tbl_barang 
                    INNER JOIN tbl_kategori ON tbl_kategori.kategori_id = tbl_barang.barang_kategori_id
                    INNER JOIN tbl_size ON tbl_size.size_kategori_id = tbl_kategori.kategori_id
                    WHERE tbl_barang.barang_id = '$keyword'";
        echo $sql;
        $data    = $this->db->assc($sql);
        return $data;
    }
    public function getIdTransaksi(){
        if(isset($_GET['idTransaksi'])){
            $idTransaksi   = strip_tags($_GET['idTransaksi']);
            $rows          = $this->cekKodeTran($idTransaksi);
            $kodeTransaksi = $rows['transaksi_id'];
            $transaksi_pelanggan_id  = $rows['transaksi_pelanggan_id'];
        }else{
            $transaksi_pelanggan_id = $this->kodeTran();
        }
        return $transaksi_pelanggan_id;
    }
    public function getAllTransaksiMonth(){
        $sql       = "SELECT * FROM tbl_transaksi ORDER BY status DESC";
        $data      = $this->db->query($sql);
        $transaksi = array();
        while($row = $data->FetchRow()){
            $transaksi[] = $row;
        }
        return $transaksi;
    }    
    public function getAllTransaksiToday(){
        $tanggalToday = date("Y-m-d");
        //$tanggal    = "2018-06-30";
        $sql = "SELECT * FROM tbl_transaksi 
        INNER JOIN tbl_pelanggan ON tbl_transaksi.transaksi_pelanggan_id = tbl_pelanggan.pelanggan_id
        where tanggal LIKE '%$tanggalToday%' AND tipe = '0' ORDER BY status DESC";
        //$sql     = "SELECT * FROM tbl_transaksi where tanggal LIKE '%$tanggal%' ORDER BY status DESC";
        $data      = $this->db->query($sql);
        $transaksi = array();
        while($row=$data->FetchRow()){
            $transaksi[] = $row;
        }
        return $transaksi;
    }
    public function getAllTransaksiTodayGojek(){
        $tanggalToday = date("Y-m-d");
        //$tanggal    = "2018-06-30";
        $sql = "SELECT * FROM tbl_transaksi where tanggal LIKE '%$tanggalToday%' AND tipe = '1' ORDER BY status DESC";
        //$sql     = "SELECT * FROM tbl_transaksi where tanggal LIKE '%$tanggal%' ORDER BY status DESC";
        $data      = $this->db->query($sql);
        $transaksi = array();
        while($row=$data->FetchRow()){
            $transaksi[] = $row;
        }
        return $transaksi;
    }
    public function addPesanan($idTransaksi, $pesananId, $size_id, $jumlah, $keterangan){
        $sql = "INSERT INTO tbl_pesanan (`unix_id`, `transaksi_id`, `barang_id`, `pesanan_size_id`, `jumlah`, `status`, `keterangan`) VALUES (NULL, '$idTransaksi', '$pesananId', '$size_id', '$jumlah', '1', '$keterangan')";
        $this->db->query($sql);
        $tipe = substr($pesananId, 0, 2);
        $this->update_triger($tipe);
    }
    public function update_triger($tipe){
        if($tipe == 'MK'){
            $sql = "UPDATE tbl_triger SET triger_dapur = '1' WHERE triger_id = '1'";
        }else if($tipe == 'MI'){
            $sql = "UPDATE tbl_triger SET triger_bar = '1' WHERE triger_id = '1'";
        }else{
            $sql = "UPDATE tbl_triger SET triger_bar = '1', triger_dapur = '1' WHERE triger_id = '1'";
        }
        $this->db->query($sql);
    }
    public function getPesanan($idTransaksi){
        $sql     = "SELECT *, tbl_barang.harga_barang * tbl_pesanan.jumlah as subtotal FROM tbl_pesanan 
                INNER JOIN tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id 
                INNER JOIN tbl_size on tbl_pesanan.pesanan_size_id = tbl_size.size_id
                WHERE transaksi_id = '$idTransaksi' order by tbl_pesanan.unix_id";
        // echo $sql;
        $data    = $this->db->query($sql);
        $pesanan = array();
        while($row = $data->FetchRow()){
            $pesanan[] = $row;
        }
        //echo $sql;
        return $pesanan;
    }
    public function getPesananByDay($tanggal){
        $sql     = "SELECT *, SUM(jumlah) as total_item FROM tbl_transaksi
                INNER JOIN tbl_pesanan ON tbl_pesanan.transaksi_id = tbl_transaksi.transaksi_id
                INNER JOIN tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id 
                INNER JOIN tbl_size on tbl_pesanan.pesanan_size_id = tbl_size.size_id
                INNER JOIN tbl_kategori on tbl_kategori.kategori_id = tbl_size.size_kategori_id
                WHERE tbl_transaksi.tanggal = '$tanggal' GROUP BY tbl_pesanan.pesanan_size_id, tbl_pesanan.barang_id";
        // echo $sql;
        $data    = $this->db->query($sql);
        $pesanan = array();
        while($row = $data->FetchRow()){
            $pesanan[] = $row;
        }
        //echo $sql;
        return $pesanan;
    }
    public function getTotal($idTransaksi){        
        $sql    = "SELECT SUM( tbl_barang.harga_barang * tbl_pesanan.jumlah ) AS total FROM tbl_pesanan INNER JOIN tbl_barang ON tbl_pesanan.barang_id = tbl_barang.barang_id WHERE transaksi_id =  '$idTransaksi'";
        $data   = $this->db->query($sql);
        $total  = $data ? $data->FetchRow() : null;
        if(empty($total) || !isset($total[0]) || $total[0] === null){
            $total = [0, 'total' => 0];
        }
        return $total;
    }    
    public function getTotalGojek($idTransaksi){     
        $viewPesananById = $this->getPesananById($idTransaksi);
        $totalBayarGojek = 0;
        foreach($viewPesananById as $rowPesanan): 
            $hargaSatuanGojek  = $this->hargaGojek($rowPesanan['harga_barang']);
            $subTotalGojek     = $hargaSatuanGojek * $rowPesanan[3];
            $totalBayarGojek  += $subTotalGojek;
        endforeach;
        //var_dump($hargaSatuanGojek);
        return $totalBayarGojek;
    }
    public function getPesananById($idTransaksi){        
        $sql     = "SELECT *, tbl_barang.harga_barang * tbl_pesanan.jumlah as subtotal FROM tbl_pesanan inner join tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id where transaksi_id = '$idTransaksi'";
        $data   = $this->db->query($sql);
        $pesanan = array();
        while($row = $data->FetchRow()){
            $pesanan[] = $row;
        }
        //echo $sql;
        return $pesanan;
    }
    public function addTransaksi($idTran, $pelanggan_id, $tipe){
        //$tanggal    = "2018-06-30 18:00:40";
        $tanggalToday = date("Y-m-d");
        $sql          = "INSERT INTO tbl_transaksi (transaksi_id, transaksi_pelanggan_id, tanggal, status, tipe) VALUES ('$idTran', '$pelanggan_id', '$tanggalToday', '1', $tipe)";
        //$sql   = "INSERT INTO tbl_transaksi (transaksi_id, transaksi_pelanggan_id, tanggal, status) VALUES ('$idTran', '$pelanggan_id', '$tanggal', '1');";
//        echo $sql;
        $this->db->query($sql);
    }
    public function checkTran($idTransaksi){
        $sql = "UPDATE  tbl_transaksi SET  status =  '0' WHERE CONVERT(  tbl_transaksi.transaksi_id USING utf8 ) =  '$idTransaksi' LIMIT 1 ;";
        $this->db->query($sql);
    }
    public function updateStatusTransaksi($jenis_pembayaran, $total_sebelum_diskon, $potongan_harga, $sisa_pembayaran, $post_cash, $idTran){
        $sql   = "UPDATE  tbl_transaksi SET  status =  '0', jenis_pembayaran = '$jenis_pembayaran', total_sebelum_diskon = '$total_sebelum_diskon', promo_nominal = '$potongan_harga', total_pembayaran = '$sisa_pembayaran', cash = '$post_cash' WHERE CONVERT(  tbl_transaksi.transaksi_id USING utf8 ) =  '$idTran' LIMIT 1 ;";
        // echo $sql;
        // die();
        $this->db->query($sql);
    }
    public function delPesanan($unix_id){
        $sql = "DELETE FROM tbl_pesanan where unix_id = '$unix_id'";
        $this->db->query($sql);
        $tipe = '';
        $this->update_triger($tipe);
    }
    public function editTransaksi($idTransaksi, $pelanggan_id, $tipe){
        $sql = "UPDATE tbl_transaksi SET transaksi_pelanggan_id = '$pelanggan_id', tipe = '$tipe' WHERE transaksi_id = '$idTransaksi'";
        //echo $sql;
        $this->db->query($sql);
        $tipe = '';
        $this->update_triger($tipe);
    }
    public function hapusTransaksi($idTransaksi){
        $idTransaksi = addslashes(trim($idTransaksi));
        $this->hapusTransaksiFromPesanan($idTransaksi);
        $sql = "DELETE FROM tbl_transaksi WHERE transaksi_id = '$idTransaksi'";
        $this->db->query($sql);
        $this->update_triger('');
    }
    public function hapusTransaksiFromPesanan($idTransaksi){
        $idTransaksi = addslashes(trim($idTransaksi));
        $sql = "DELETE FROM tbl_pesanan WHERE transaksi_id = '$idTransaksi'";
        $this->db->query($sql);
    }
    
    public function hargaGojek($nomial = 0) {
        $nomial = (float)($nomial ?? 0);
        $harga = $this->pembulatan((($nomial * (27/100)) + $nomial));
        return $harga;
    }
    function pembulatan($uang = 0){
        $uang = (float)($uang ?? 0);
        $mod = (int)$uang % 500;
        if($mod < 100) {
            $harga = $uang - $mod;
        }else{
            $harga = ($uang - $mod) + 500;
        }
        return $harga;
    }

    /**
     * Dapatkan ringkasan total transaksi harian kasir hari ini
     */
    public function getRingkasanHarianKasir() {
        $today = date("Y-m-d");

        // 1. Total Transaksi Selesai / Lunas (status = '0')
        $sqlLunas = "SELECT 
                        SUM(p.jumlah * b.harga_barang) as total_omset,
                        COUNT(DISTINCT t.transaksi_id) as total_transaksi_selesai,
                        SUM(p.jumlah) as total_pcs_terjual
                     FROM tbl_transaksi t
                     INNER JOIN tbl_pesanan p ON t.transaksi_id = p.transaksi_id
                     INNER JOIN tbl_barang b ON p.barang_id = b.barang_id
                     WHERE t.tanggal LIKE '%$today%' AND t.status = '0'";
        $resLunas = $this->db->query($sqlLunas);
        $rowLunas = $resLunas ? $resLunas->FetchRow() : null;

        $omsetLunas = (float)($rowLunas['total_omset'] ?? 0);
        $trxSelesai = (int)($rowLunas['total_transaksi_selesai'] ?? 0);
        $pcsTerjual = (int)($rowLunas['total_pcs_terjual'] ?? 0);

        // 2. Transaksi Hari Ini yang Masih Pending / Belum Lunas (status = '1')
        $sqlPending = "SELECT 
                          COUNT(DISTINCT t.transaksi_id) as total_pending,
                          SUM(p.jumlah * b.harga_barang) as nominal_pending
                       FROM tbl_transaksi t
                       LEFT JOIN tbl_pesanan p ON t.transaksi_id = p.transaksi_id
                       LEFT JOIN tbl_barang b ON p.barang_id = b.barang_id
                       WHERE t.tanggal LIKE '%$today%' AND t.status = '1'";
        $resPending = $this->db->query($sqlPending);
        $rowPending = $resPending ? $resPending->FetchRow() : null;

        $trxPending   = (int)($rowPending['total_pending'] ?? 0);
        $omsetPending = (float)($rowPending['nominal_pending'] ?? 0);

        return [
            'omset_lunas'   => $omsetLunas,
            'trx_selesai'   => $trxSelesai,
            'pcs_terjual'   => $pcsTerjual,
            'trx_pending'   => $trxPending,
            'omset_pending' => $omsetPending,
            'tanggal'       => $today
        ];
    }
}
?>