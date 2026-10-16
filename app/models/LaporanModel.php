<?php
class LaporanModel{

    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }   
    public function getAllTransaksiMonth(){
        $sql   = "SELECT * FROM tbl_transaksi ORDER BY status DESC";
        $data      = $this->db->query($sql)->GetArray();
        return $data;
    }    
    public function getAllTransaksiToday(){
        $tanggalToday = date("Y-m-d");
        $sql     = "SELECT tbl_transaksi.transaksi_id, tbl_transaksi.transaksi_pelanggan_id, tbl_transaksi.tanggal, tbl_transaksi.status, tbl_pesanan.barang_id, tbl_pesanan.jumlah , tbl_barang.harga_barang, SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) as subtotal, tbl_transaksi.tipe, tbl_pelanggan.pelanggan_nama, tbl_transaksi.jenis_pembayaran, tbl_transaksi.total_pembayaran FROM 
        tbl_transaksi inner join tbl_pesanan on tbl_transaksi.transaksi_id = tbl_pesanan.transaksi_id 
        INNER JOIN tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id 
        INNER JOIN tbl_pelanggan ON tbl_transaksi.transaksi_pelanggan_id = tbl_pelanggan.pelanggan_id
        WHERE tanggal LIKE '%$tanggalToday%' GROUP BY transaksi_id ORDER BY tbl_transaksi.tanggal DESC";
        $data      = $this->db->query($sql)->GetArray();
        return $data;
    }
    public function omsetHariIni(){
        $tanggalToday = date("Y-m-d");
        $sql   = "SELECT SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) as omset, SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang) as hpp, (SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) - SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang)) as untung, tbl_transaksi.tipe from tbl_pesanan inner join tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id inner join tbl_transaksi on tbl_pesanan.transaksi_id = tbl_transaksi.transaksi_id where tanggal LIKE '%$tanggalToday%' AND tbl_transaksi.tipe = '0'";
        //$sql   = "SELECT SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) as omset, SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang) as hpp, (SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) - SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang)) as untung from tbl_pesanan inner join tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id inner join tbl_transaksi on tbl_pesanan.transaksi_id = tbl_transaksi.transaksi_id where tanggal LIKE '%2018-06-02%'";
        $data      = $this->db->query($sql);
        $cek        = $data->recordCount();
        if($cek==0){
            $harian[] = array("0","0","0");
        }else{
            $harian     = array();
            while($row = $data->FetchRow()){
                $harian[]   = $row;
            }
        }
        return $harian;
    }  
    public function omsetSudahBayar(){
        $tanggalToday = date("Y-m-d");
        $sql   = "SELECT SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) as omset, SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang) as hpp, (SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) - SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang)) as untung, tbl_transaksi.tipe from tbl_pesanan inner join tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id inner join tbl_transaksi on tbl_pesanan.transaksi_id = tbl_transaksi.transaksi_id where tanggal LIKE '%$tanggalToday%' AND tbl_transaksi.tipe = '0' AND tbl_transaksi.status = '0'";
        //$sql   = "SELECT SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) as omset, SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang) as hpp, (SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) - SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang)) as untung from tbl_pesanan inner join tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id inner join tbl_transaksi on tbl_pesanan.transaksi_id = tbl_transaksi.transaksi_id where tanggal LIKE '%2018-06-02%'";
        $data      = $this->db->query($sql);
        $cek        = $data->recordCount();
        if($cek==0){
            $harian[] = array("0","0","0");
        }else{
            $harian     = array();
            while($row = $data->FetchRow()){
                $harian   = $row['omset'];
            }
        }
        return $harian;
    }  
    public function omsetBelumDiBayar(){
        $tanggalToday = date("Y-m-d");
        $sql   = "SELECT SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) as omset, SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang) as hpp, (SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) - SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang)) as untung, tbl_transaksi.tipe from tbl_pesanan inner join tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id inner join tbl_transaksi on tbl_pesanan.transaksi_id = tbl_transaksi.transaksi_id where tanggal LIKE '%$tanggalToday%' AND tbl_transaksi.tipe = '0' AND tbl_transaksi.status = '1'";
        //$sql   = "SELECT SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) as omset, SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang) as hpp, (SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) - SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang)) as untung from tbl_pesanan inner join tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id inner join tbl_transaksi on tbl_pesanan.transaksi_id = tbl_transaksi.transaksi_id where tanggal LIKE '%2018-06-02%'";
        $data      = $this->db->query($sql);
        $cek        = $data->recordCount();
        if($cek==0){
            $harian = 0;
        }else{
            $harian     = array();
            while($row = $data->FetchRow()){
                $harian   = $row['omset'];
            }
        }
        return $harian;
    }  
    public function omsetBulanIni(){
        $bulanIni   = date("Y-m");
        $sql   = "SELECT SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) as omset, 
                        SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang) as hpp, 
                        (SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) - SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang)) as untung 
                        from tbl_pesanan inner join tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id";
        $data      = $this->db->query($sql);
        $bulanan    = array();
        while($row = $data->FetchRow()){
            $bulanan[]   = $row;
        }
        return $bulanan;
    }
    public function omsetBulanan($bulan, $tahun) {
        // $bulanIni   = $bulan_tahun;
        $sql   = "SELECT tbl_transaksi.tanggal, SUM(tbl_transaksi.total_pembayaran) as total_pembayaran, SUM(tbl_transaksi.promo_nominal) as total_promo FROM tbl_transaksi 
                WHERE MONTH(tbl_transaksi.tanggal) = '$bulan' AND YEAR(tbl_transaksi.tanggal) = '$tahun' AND tbl_transaksi.status = '0' GROUP BY tbl_transaksi.tanggal ORDER BY tbl_transaksi.tanggal DESC";
        $data      = $this->db->query($sql);
        // echo $sql;
        $bulanan     = $data->GetArray();
        // echo "<br><br><br><br><br><br><pre>";
        // print_r($bulanan);
        // echo "</pre>";
        return $bulanan;
    }
    public function totalHppByDay($tanggal) {
        $sql   = "SELECT SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang) harga_hpp FROM tbl_transaksi 
                INNER JOIN tbl_pesanan ON tbl_pesanan.transaksi_id = tbl_transaksi.transaksi_id
                INNER JOIN tbl_barang ON tbl_pesanan.barang_id = tbl_barang.barang_id
                WHERE tbl_transaksi.tanggal = '$tanggal'";
        $data      = $this->db->query($sql);
        $row       = $data ? $data->FetchRow() : null;
        $bulanan   = ($row && isset($row['harga_hpp']) && $row['harga_hpp'] !== null) ? (int)$row['harga_hpp'] : 0;
        return $bulanan;
    }

    public function totalOmsetBulanan(){
        $bulanIni   = date("Y-m");
        $sql   = "SELECT SUM(tbl_pesanan.jumlah) as totalTransaksi,  SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) as subtotal, SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang) as hpp, SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) - SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang) as laba, tbl_transaksi.total_pembayaran FROM tbl_transaksi 
        inner join tbl_pesanan on tbl_transaksi.transaksi_id = tbl_pesanan.transaksi_id 
        inner join tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id 
        where tanggal LIKE '%$bulanIni%' AND tbl_transaksi.tipe = '0' AND jenis_pembayaran = '1'";
        // echo $sql;
        $data      = $this->db->query($sql)->GetArray();
        return $data;
        
    }
    function totalGojek() {
        $tanggalToday = date("Y-m-d");
        $sql = "SELECT tbl_barang.nama_barang, tbl_barang.harga_barang, tbl_pesanan.jumlah, 
                (tbl_barang.harga_barang * tbl_pesanan.jumlah) as subtotal FROM `tbl_transaksi` 
                INNER JOIN tbl_pesanan ON tbl_transaksi.transaksi_id =  tbl_pesanan.transaksi_id
                INNER JOIN tbl_barang ON tbl_pesanan.barang_id = tbl_barang.barang_id
                WHERE tanggal LIKE '%$tanggalToday%' AND tbl_transaksi.tipe = '1'";
        $data      = $this->db->query($sql);
        $laporan    = array();
        $total      = 0;
        while($row = $data->FetchRow()){
            $subtotal = ($this->hargaGojek($row[1])) * $row[2];
            $total    += $subtotal;
            //$laporan[]   = $row;
        }
        return $total;
    }

    function totalDebet() {
        $tanggalToday = date("Y-m-d");
        $sql = "SELECT tbl_barang.nama_barang, tbl_barang.harga_barang, tbl_pesanan.jumlah, 
                (tbl_barang.harga_barang * tbl_pesanan.jumlah) as subtotal FROM `tbl_transaksi` 
                INNER JOIN tbl_pesanan ON tbl_transaksi.transaksi_id =  tbl_pesanan.transaksi_id
                INNER JOIN tbl_barang ON tbl_pesanan.barang_id = tbl_barang.barang_id
                WHERE tanggal LIKE '%$tanggalToday%' AND tbl_transaksi.jenis_pembayaran = '3'";
        $data      = $this->db->query($sql);
        $laporan    = array();
        $total      = 0;
        while($row = $data->FetchRow()){
            $subtotal = ($this->hargaGojek($row[1])) * $row[2];
            $total    += $subtotal;
            //$laporan[]   = $row;
        }
        return $total;
    }

    function totalGojekByDay($date) {
        // $tanggalToday = date("Y-m-d");
        $sql = "SELECT tbl_barang.nama_barang, tbl_barang.harga_barang, tbl_pesanan.jumlah, 
                (tbl_barang.harga_barang * tbl_pesanan.jumlah) as subtotal FROM `tbl_transaksi` 
                INNER JOIN tbl_pesanan ON tbl_transaksi.transaksi_id =  tbl_pesanan.transaksi_id
                INNER JOIN tbl_barang ON tbl_pesanan.barang_id = tbl_barang.barang_id
                WHERE tanggal LIKE '%$date%' AND tbl_transaksi.tipe = '1'";
        // echo $sql;
        // die();
        $data      = $this->db->query($sql)->GetArray();
        $total      = 0;
        foreach($data as $row){
            $subtotal = ($this->hargaGojek($row[1])) * $row[2];
            $total    += $subtotal;
            //$laporan[]   = $row;
        }
        return $total;
    }
    
    function totalJenisByDay($date, $jenis_pembayaran) {
        // $tanggalToday = date("Y-m-d");
        $sql = "SELECT SUM(total_pembayaran) AS total_pembayaran FROM `tbl_transaksi` 
                -- INNER JOIN tbl_pesanan ON tbl_transaksi.transaksi_id =  tbl_pesanan.transaksi_id
                -- INNER JOIN tbl_barang ON tbl_pesanan.barang_id = tbl_barang.barang_id
                WHERE tbl_transaksi.tanggal = '$date' AND tbl_transaksi.jenis_pembayaran = '$jenis_pembayaran' GROUP BY tbl_transaksi.tanggal";
                // echo $sql;
                // die();
        $data      = $this->db->query($sql);
        $row       = $data ? $data->FetchRow() : null;
        return ($row && isset($row['total_pembayaran']) && $row['total_pembayaran'] !== null) ? (int)$row['total_pembayaran'] : 0;
    }
    
    public function hargaGojek($nomial = 0) {
        $nomial = (float)($nomial ?? 0);
        $harga = $this->pembulatan((($nomial * (27/100)) + $nomial));
        return $harga;
    }
    function pembulatan($uang = 0){
        $uang = (float)($uang ?? 0);
        $mod  = (int)$uang % 500;
        if($mod < 100) {
            $harga = $uang - $mod; 
        }else{
            $harga = ($uang - $mod) + 500;
        }
        return $harga;
    }
    
    
    //DASHBOARD
    public function omset_promo(){
        $tanggalToday = date("Y-m-d");
        $sql   = "SELECT SUM(tbl_transaksi.promo_nominal) as total_promo FROM tbl_transaksi where tanggal LIKE '%$tanggalToday%' AND tbl_transaksi.jenis_pembayaran = '1'";
        $data      = $this->db->query($sql);
        $row       = $data ? $data->FetchRow() : null;
        return ($row && isset($row['total_promo']) && $row['total_promo'] !== null) ? (int)$row['total_promo'] : 0;
    }  
    public function total_promo($jenis_pembayaran = 'all'){
        $tanggalToday = date("Y-m-d");
        if($jenis_pembayaran === 'all'){
            $sql   = "SELECT SUM(tbl_transaksi.promo_nominal) as total_promo FROM tbl_transaksi where tanggal LIKE '%$tanggalToday%' AND tbl_transaksi.status = '0'";
        }else{
            $sql   = "SELECT SUM(tbl_transaksi.promo_nominal) as total_promo FROM tbl_transaksi where tanggal LIKE '%$tanggalToday%' AND tbl_transaksi.jenis_pembayaran = '$jenis_pembayaran' AND tbl_transaksi.status = '0'";
        }
        $data      = $this->db->query($sql);
        $row       = $data ? $data->FetchRow() : null;
        return ($row && isset($row['total_promo']) && $row['total_promo'] !== null) ? (int)$row['total_promo'] : 0;
    }  
    public function omset_harian($jenis_pembayaran){
        $tanggalToday = date("Y-m-d");
        $sql   = "SELECT SUM(tbl_transaksi.total_pembayaran) AS total_pembayaran FROM tbl_transaksi where tanggal LIKE '%$tanggalToday%' AND tbl_transaksi.jenis_pembayaran = '$jenis_pembayaran' AND tbl_transaksi.status = '0'";
        $data      = $this->db->query($sql);
        $row       = $data ? $data->FetchRow() : null;
        return ($row && isset($row['total_pembayaran']) && $row['total_pembayaran'] !== null) ? (int)$row['total_pembayaran'] : 0;
    }  
    public function omset_cash(){
        $tanggalToday = date("Y-m-d");
        $sql   = "SELECT SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) as omset, SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang) as hpp, (SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) - SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang)) as untung from tbl_pesanan inner join tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id inner join tbl_transaksi on tbl_pesanan.transaksi_id = tbl_transaksi.transaksi_id where tanggal LIKE '%$tanggalToday%' AND tbl_transaksi.jenis_pembayaran = '1' AND tbl_transaksi.status = '0'";
        $data      = $this->db->query($sql);
        $row       = $data ? $data->FetchRow() : null;
        return ($row && isset($row['omset']) && $row['omset'] !== null) ? (int)$row['omset'] : 0;
    }  
    public function omset_debet(){
        $tanggalToday = date("Y-m-d");
        $sql   = "SELECT SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) as omset, SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang) as hpp, (SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) - SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang)) as untung from tbl_pesanan inner join tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id inner join tbl_transaksi on tbl_pesanan.transaksi_id = tbl_transaksi.transaksi_id where tanggal LIKE '%$tanggalToday%' AND tbl_transaksi.jenis_pembayaran = '3' AND tbl_transaksi.status = '0'";
        $data      = $this->db->query($sql);
        $row       = $data ? $data->FetchRow() : null;
        return ($row && isset($row['omset']) && $row['omset'] !== null) ? (int)$row['omset'] : 0;
    }  
    public function omset_emoney(){
        $tanggalToday = date("Y-m-d");
        $sql   = "SELECT SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) as omset, SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang) as hpp, (SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) - SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang)) as untung from tbl_pesanan inner join tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id inner join tbl_transaksi on tbl_pesanan.transaksi_id = tbl_transaksi.transaksi_id where tanggal LIKE '%$tanggalToday%' AND tbl_transaksi.jenis_pembayaran = '2' AND tbl_transaksi.status = '0'";
        $data      = $this->db->query($sql);
        $row       = $data ? $data->FetchRow() : null;
        return ($row && isset($row['omset']) && $row['omset'] !== null) ? (int)$row['omset'] : 0;
    }  
    public function omset_gojek(){
        $tanggalToday = date("Y-m-d");
        $sql   = "SELECT SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) as omset, SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang) as hpp, (SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) - SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang)) as untung from tbl_pesanan inner join tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id inner join tbl_transaksi on tbl_pesanan.transaksi_id = tbl_transaksi.transaksi_id where tanggal LIKE '%$tanggalToday%' AND tbl_transaksi.tipe = '1'";
        $data      = $this->db->query($sql);
        $row       = $data ? $data->FetchRow() : null;
        return ($row && isset($row['omset']) && $row['omset'] !== null) ? (int)$row['omset'] : 0;
    }  
    
    public function omsetByDay($tanggal) {
        $sql   = "SELECT SUM(total_pembayaran) as total_pembayaran FROM tbl_transaksi 
        WHERE tanggal = '$tanggal' AND tbl_transaksi.tipe = '0'";
        $data      = $this->db->query($sql);
        $row       = $data ? $data->FetchRow() : null;
        return ($row && isset($row['total_pembayaran']) && $row['total_pembayaran'] !== null) ? (int)$row['total_pembayaran'] : 0;
    }
    public function omsetByMonth($tahun) {
        $sql   = "SELECT SUM(tbl_pesanan.jumlah) as totalTransaksi, tbl_transaksi.transaksi_pelanggan_id, tbl_transaksi.tanggal, tbl_transaksi.status, tbl_pesanan.barang_id, tbl_pesanan.jumlah , tbl_barang.harga_barang, SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) as subtotal, SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang) as hpp, SUM(tbl_pesanan.jumlah * tbl_barang.harga_barang) - SUM(tbl_pesanan.jumlah * tbl_barang.hpp_barang) as laba, tbl_transaksi.status, jenis_pembayaran FROM tbl_transaksi 
        INNER JOIN tbl_pesanan on tbl_transaksi.transaksi_id = tbl_pesanan.transaksi_id 
        INNER JOIN tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id 
        WHERE YEAR(tanggal) = '$tahun' AND tbl_transaksi.tipe = '0' GROUP BY MONTH(tbl_transaksi.tanggal) ORDER BY tbl_transaksi.tanggal DESC";
        $data      = $this->db->query($sql);
        // var_dump($sql);
        // die();
        $cek        = $data->recordCount();
        if($cek==0){
            $bulanan[] = array("0","0","0");
        }else{
            $bulanan     = $data->GetArray();
        }
        //echo $sql;
        return $bulanan;
    }
    
    public function labelGrafikOmsetByDay($bulan) {
        $kalender = CAL_GREGORIAN;
        $tahun  = date('Y');
        $jumlah_hari = cal_days_in_month($kalender, $bulan, $tahun);
        $tanggal = array();
        for($day = 1; $day <= $jumlah_hari; $day++){
            $date = $tahun.'-'.$bulan.'-'.$day;
            array_push($tanggal, $this->tanggal_indo($date, true));
        }
        return $tanggal;
    }
    public function dataGrafikOmsetByDay($bulan) {
        $kalender = CAL_GREGORIAN;
        $tahun  = date('Y');
        $jumlah_hari = cal_days_in_month($kalender, $bulan, $tahun);
        $tanggal = array();
        for($day = 1; $day <= $jumlah_hari; $day++){
            $date = $tahun.'-'.$bulan.'-'.$day;
            array_push($tanggal, $this->omsetByDay($date));
        }
        return $tanggal;
    }
    
    public function tanggal_indo($tanggal, $cetak_hari = false){
        $hari = array ( 1 =>    'Senin',
                    'Selasa',
                    'Rabu',
                    'Kamis',
                    'Jumat',
                    'Sabtu',
                    'Minggu'
                );
                
        $bulan = array (1 =>   'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                );
        $split 	  = explode('-', $tanggal);
        $tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
        
        if ($cetak_hari) {
            $num = date('N', strtotime($tanggal));
            return $hari[$num] . ', ' . $tgl_indo;
        }
        return $tgl_indo;
    }
    
    public function getTotalAsetByKategori(){
        $sql    = "SELECT SUM(tbl_barang.hpp_barang * tbl_stok_masuk.stok_masuk_jumlah) as total_aset_hpp, SUM(tbl_barang.harga_barang * tbl_stok_masuk.stok_masuk_jumlah) as total_aset_jual, kategori_nama, kategori_id FROM tbl_stok_masuk 
                INNER JOIN tbl_barang ON tbl_barang.barang_id = tbl_stok_masuk.stok_masuk_produk_id 
                INNER JOIN tbl_kategori ON tbl_barang.barang_kategori_id = tbl_kategori.kategori_id
                INNER JOIN tbl_size ON tbl_size.size_id = tbl_stok_masuk.stok_masuk_size_id
                GROUP BY tbl_kategori.kategori_id ORDER BY total_aset_hpp DESC";
                // echo $sql;
        // $data   = $this->db->assoc($sql);
        $data   = $this->db->query($sql)->GetArray();
        return $data;
    }
    public function getTotalAset(){
        $sql    = "SELECT SUM(tbl_barang.hpp_barang * tbl_stok_masuk.stok_masuk_jumlah) as total_aset_hpp, SUM(tbl_barang.harga_barang * tbl_stok_masuk.stok_masuk_jumlah) as total_aset_jual FROM tbl_stok_masuk 
                INNER JOIN tbl_barang ON tbl_barang.barang_id = tbl_stok_masuk.stok_masuk_produk_id 
                INNER JOIN tbl_size ON tbl_size.size_id = tbl_stok_masuk.stok_masuk_size_id";
        $data   = $this->db->query($sql);
        $row    = $data ? $data->FetchRow() : null;
        return [
            'total_aset_hpp'  => ($row && isset($row['total_aset_hpp']) && $row['total_aset_hpp'] !== null) ? (int)$row['total_aset_hpp'] : 0,
            'total_aset_jual' => ($row && isset($row['total_aset_jual']) && $row['total_aset_jual'] !== null) ? (int)$row['total_aset_jual'] : 0
        ];
    }
    
    public function getTotalAsetSold(){
        $sql    = "SELECT SUM(tbl_barang.hpp_barang * tbl_pesanan.jumlah) as total_aset_hpp, SUM(tbl_barang.harga_barang * tbl_pesanan.jumlah) as total_aset_jual FROM tbl_pesanan 
                INNER JOIN tbl_barang ON tbl_barang.barang_id = tbl_pesanan.barang_id 
                INNER JOIN tbl_size ON tbl_size.size_id = tbl_pesanan.pesanan_size_id
                INNER JOIN tbl_transaksi ON tbl_pesanan.transaksi_id = tbl_transaksi.transaksi_id 
                WHERE tbl_transaksi.status = '0'";
        $data   = $this->db->query($sql);
        $row    = $data ? $data->FetchRow() : null;
        return [
            'total_aset_hpp'  => ($row && isset($row['total_aset_hpp']) && $row['total_aset_hpp'] !== null) ? (int)$row['total_aset_hpp'] : 0,
            'total_aset_jual' => ($row && isset($row['total_aset_jual']) && $row['total_aset_jual'] !== null) ? (int)$row['total_aset_jual'] : 0
        ];
    }
    public function getTotalAsetSoldByProduk($kategori){
        $sql    = "SELECT SUM(tbl_barang.hpp_barang * tbl_pesanan.jumlah) as total_aset_hpp, SUM(tbl_barang.harga_barang * tbl_pesanan.jumlah) as total_aset_jual FROM tbl_pesanan 
                INNER JOIN tbl_barang ON tbl_barang.barang_id = tbl_pesanan.barang_id 
                INNER JOIN tbl_size ON tbl_size.size_id = tbl_pesanan.pesanan_size_id
                INNER JOIN tbl_kategori ON tbl_barang.barang_kategori_id = tbl_kategori.kategori_id
                INNER JOIN tbl_transaksi ON tbl_pesanan.transaksi_id = tbl_transaksi.transaksi_id 
                WHERE tbl_transaksi.status = '0' AND tbl_kategori.kategori_id = '$kategori'";
        $data   = $this->db->query($sql);
        $row    = $data ? $data->FetchRow() : null;
        return [
            'total_aset_hpp'  => ($row && isset($row['total_aset_hpp']) && $row['total_aset_hpp'] !== null) ? (int)$row['total_aset_hpp'] : 0,
            'total_aset_jual' => ($row && isset($row['total_aset_jual']) && $row['total_aset_jual'] !== null) ? (int)$row['total_aset_jual'] : 0
        ];
    }
    public function getTotalAsetOutByProduk($kategori){
        $sql    = "SELECT SUM(tbl_barang.hpp_barang * tbl_stok_keluar.stok_keluar_jumlah) as total_aset_hpp, SUM(tbl_barang.harga_barang * tbl_stok_keluar.stok_keluar_jumlah) as total_aset_jual FROM tbl_stok_keluar 
                INNER JOIN tbl_barang ON tbl_barang.barang_id = tbl_stok_keluar.stok_keluar_produk_id 
                INNER JOIN tbl_size ON tbl_size.size_id = tbl_stok_keluar.stok_keluar_size_id
                INNER JOIN tbl_kategori ON tbl_barang.barang_kategori_id = tbl_kategori.kategori_id
                WHERE tbl_kategori.kategori_id = '$kategori'";
        $data   = $this->db->query($sql);
        $row    = $data ? $data->FetchRow() : null;
        return [
            'total_aset_hpp'  => ($row && isset($row['total_aset_hpp']) && $row['total_aset_hpp'] !== null) ? (int)$row['total_aset_hpp'] : 0,
            'total_aset_jual' => ($row && isset($row['total_aset_jual']) && $row['total_aset_jual'] !== null) ? (int)$row['total_aset_jual'] : 0
        ];
    }
    public function getTotalAsetOut(){
        $sql    = "SELECT SUM(tbl_barang.hpp_barang * tbl_stok_keluar.stok_keluar_jumlah) as total_aset_hpp, SUM(tbl_barang.harga_barang * tbl_stok_keluar.stok_keluar_jumlah) as total_aset_jual FROM tbl_stok_keluar 
                INNER JOIN tbl_barang ON tbl_barang.barang_id = tbl_stok_keluar.stok_keluar_produk_id 
                INNER JOIN tbl_size ON tbl_size.size_id = tbl_stok_keluar.stok_keluar_size_id";
        $data   = $this->db->query($sql);
        $row    = $data ? $data->FetchRow() : null;
        return [
            'total_aset_hpp'  => ($row && isset($row['total_aset_hpp']) && $row['total_aset_hpp'] !== null) ? (int)$row['total_aset_hpp'] : 0,
            'total_aset_jual' => ($row && isset($row['total_aset_jual']) && $row['total_aset_jual'] !== null) ? (int)$row['total_aset_jual'] : 0
        ];
    }
    public function getTrendPenjualan($month, $year){
        $sql    = "SELECT SUM(tbl_pesanan.jumlah) as jumlah, tbl_barang.nama_barang, kategori_nama FROM tbl_pesanan
        INNER JOIN tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id
        INNER JOIN tbl_transaksi ON tbl_pesanan.transaksi_id = tbl_transaksi.transaksi_id
        INNER JOIN tbl_kategori ON tbl_barang.barang_kategori_id = tbl_kategori.kategori_id
        WHERE YEAR(tbl_transaksi.tanggal) = '$year' AND MONTH(tbl_transaksi.tanggal) = '$month' AND tbl_transaksi.status = '0'
        GROUP BY tbl_barang.barang_id ORDER BY jumlah DESC";
                // echo $sql;
        $data   = $this->db->query($sql)->GetArray();
        return $data;
    }
}
?>