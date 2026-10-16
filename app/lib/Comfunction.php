<?php
class Comfunction {
    
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }   

    public function combo_pelanggan($selected = '', $select2 = false){
        $sql        = "SELECT * FROM tbl_pelanggan";
        $data      = $this->db->query($sql);
        if($select2 == true){
            $select2 = "select2";
        }
        $html       = "<select name='pelanggan_id' class='form-control ".$select2."'>";
        while($row = $data->FetchRow()){
            if($selected == $row['pelanggan_id']){
                $html .= "<option value = '".$row['pelanggan_id']."' selected>".$row['pelanggan_id']." | ".$row['pelanggan_nama']."</value>";
            }else{
                $html .= "<option value = '".$row['pelanggan_id']."'>".$row['pelanggan_id']." | ".$row['pelanggan_nama']."</value>";
            }
        }
        $html       .= "</select>";
        return $html;
    }
    public function combo_produk_kategori($value = ''){
        $sql      = "SELECT * FROM tbl_kategori";
        $data     = $this->db->query($sql);
        $html     = "<select name='kategori' class='form-control'>";
        $selected = '';
        while($row = $data->FetchRow()){
            if($value === $row['kategori_id']){
                $selected = "selected";
            }else{
                $selected = '';
            }
            $html .= "<option value = '".$row['kategori_id']."' $selected>".$row['kategori_nama']."</value>";
        }
        $html       .= "</select>";
        return $html;
    }
    public function combo_suplayer($value = ''){
        $sql      = "SELECT * FROM tbl_suplayer";
        $data     = $this->db->query($sql);
        $html     = "<select name='suplayer' class='form-control'>";
        $selected = '';
        while($row = $data->FetchRow()){
            if($value === $row['suplayer_id']){
                $selected = "selected";
            }else{
                $selected = '';
            }
            $html .= "<option value = '".$row['suplayer_id']."' $selected>".$row['suplayer_nama']."</value>";
        }
        $html       .= "</select>";
        return $html;
    }
    public function combo_produk_transaksi($value = ''){
        $sql      = "SELECT * FROM tbl_barang";
        $data     = $this->db->query($sql);
        $html     = "<select name='pesanan' class='form-control select2' id='pesanan' required>";
        $html     .= "<option value = ''>--Pilih Salah Satu--</value>";
        $selected = '';
        while($row = $data->FetchRow()){
            if($value === $row['barang_id']){
                $selected = "selected";
            }else{
                $selected = '';
            }
            $html .= "<option value = '".$row['barang_id']."' ".$selected.">".$row['barang_id']." | ".$row['nama_barang']."</value>";
        }
        $html       .= "</select>";
        return $html;
    }
    public function combo_size_bykategori($kategori, $value = ''){
        $sql      = "SELECT * FROM tbl_size INNER JOIN tbl_kategori ON tbl_size.size_kategori_id = tbl_kategori.kategori_id WHERE tbl_size.size_kategori_id = '$kategori'";
        // echo $sql;
        $data     = $this->db->query($sql);
        $html     = "<select name='size' class='form-control'>";
        $selected = '';
        while($row = $data->FetchRow()){
            if($value === $row['size_id']){
                $selected = "selected";
            }else{
                $selected = '';
            }
            $html .= "<option value = '".$row['size_id']."' $selected>".$row['size_nama']."</value>";
        }
        $html       .= "</select>";
        return $html;
    }
    public function combo_size_byproduk($produk_id){
        $sql      = "SELECT * FROM tbl_size 
                    INNER JOIN tbl_kategori ON tbl_size.size_kategori_id = tbl_kategori.kategori_id 
                    INNER JOIN tbl_barang ON tbl_barang.barang_kategori_id = tbl_kategori.kategori_id
                    WHERE tbl_barang.barang_id = '$produk_id'";
        $data     = $this->db->query($sql);
        // $html     = "<select name='size' class='form-control' required>";
        $html     = "<option value = ''>--Pilih Salah Satu--</value>";
        while($row = $data->FetchRow()){
            $html .= "<option value = '".$row['size_id']."'>".$row['size_nama']."</value>";
        }
        // $html       .= "</select>";
        return $html;
    }
    public function combo_kredit_kategori(){
        $sql        = "SELECT * FROM tbl_kredit_kategori";
        $data      = $this->db->query($sql);
        $kredit     = array();
        $html       = "<select name='kredit_kategori' class='form-control'>";
        while($row = $data->FetchRow()){
            $html .= "<option value = '".$row['kredit_kategori_id']."'>".$row['kredit_kategori']."</value>";
        }
        $html       .= "</select>";
        return $html;
    }
    public function combo_debet_kategori(){
        $sql        = "SELECT * FROM tbl_debet_kategori";
        $data      = $this->db->query($sql);
        $kredit     = array();
        $html       = "<select name='debet_kategori' class='form-control'>";
        while($row = $data->FetchRow()){
            $html .= "<option value = '".$row['debet_kategori_id']."'>".$row['debet_kategori']."</value>";
        }
        $html       .= "</select>";
        return $html;
    }

    public function checkList($var){
        if($var=="1"){
            echo "<i class='fa fa-check' aria-hidden='true' title='tersedia'></i>";
        }else{
            echo "<i class='fa fa-times' aria-hidden='true' title='kosong'></i>";
        }
    }    
    public function checkListBayar($var){
        if($var=="1"){
            echo "<i class='fa fa-check' aria-hidden='true' title='BELUM DIBAYAR'></i>";
        }else{
            echo "<i class='fa fa-times' aria-hidden='true' title='SUDAH DIBAYAR'></i>";
        }
    }
    public function checkListAntar($var){
        if($var=="2"){
            echo "<i class='fa fa-check' aria-hidden='true' title='SUDAH DIANTAR'></i>";
        }else{
            echo "<i class='fa fa-times' aria-hidden='true' title='BELUM DIANTAR'></i>";
        }
    }
    
    public function selectOption($var){
        if($var == "1"){
            echo "<option value='1' Selected>Tersedia</option>";
            echo "<option value='0'>Kosong</option>";
        }else{
            echo "<option value='1'>Tersedia</option>";
            echo "<option value='0' Selected>Kosong</option>";
        }
    }
    public function successAction(){
        if(isset($_GET['success'])){
            echo "<div class='alert alert-success'>";
            echo "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span></button>";
            echo "Operasi Berhasil!</div>";
        }
    }
    public function rupiah($angka = 0){
        $angka = (float)($angka ?? 0);
        $rupiah = number_format($angka, 0, ',', '.');
        echo $rupiah;
    }
    public function rp($angka = 0){
        $angka = (float)($angka ?? 0);
        $rupiah = number_format($angka, 0, ',', '.');
        echo "Rp. ".$rupiah;
    }
    function tanggal(){
        $tanggal   = date("Y-m-d");
        return $tanggal;
    }

    public function hargaGojek($nomial = 0) {
        $nomial = (float)($nomial ?? 0);
        $harga = $this->pembulatan((($nomial * (27/100)) + $nomial));
        return $harga;
    }
    function pembulatan($uang = 0){
        $uang = (float)($uang ?? 0);
        $mod    = (int)$uang % 500;
        if($mod < 100) {
            $harga = $uang - $mod; 
        }else{
            $harga = ($uang - $mod) + 500;
        }
        return $harga;
    }
    function balik_tanggal($tanggal) {
        if(empty($tanggal)) return '';
        $tanggal = explode('-', (string)$tanggal);
        if(count($tanggal) === 3) {
            return $tanggal[2]."-".$tanggal[1]."-".$tanggal[0];
        }
        return implode('-', $tanggal);
    }
    function xss_clean($data)
    {
            if($data === null || !is_string($data)) {
                return $data ?? '';
            }
            // Fix &entity\n;
            $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
            $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
            $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
            $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
    
            // Remove any attribute starting with "on" or xmlns
            $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
    
            // Remove javascript: and vbscript: protocols
            $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
            $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
            $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
    
            // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
            $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
            $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
            $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
    
            // Remove namespaced elements (we do not need them)
            $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
    
            do
            {
                    // Remove really unwanted tags
                    $old_data = $data;
                    $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
            }
            while ($old_data !== $data);
    
            // we are done...
            return $data;
    }
    public function debug(Array $data){
        echo "<pre>";
        var_dump($data); 
        echo "</pre>";
        die();
    }
    public function tanggal_indo($tanggal, $cetak_hari = false){
        if(empty($tanggal)) return '';
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
        $split 	  = explode('-', (string)$tanggal);
        if(count($split) < 3) return (string)$tanggal;
        $tgl_indo = $split[2] . ' ' . ($bulan[ (int)$split[1] ] ?? '') . ' ' . $split[0];
        
        if ($cetak_hari) {
            $time = strtotime((string)$tanggal);
            if ($time !== false) {
                $num = date('N', $time);
                return ($hari[$num] ?? '') . ', ' . $tgl_indo;
            }
        }
        return $tgl_indo;
    }
    public function get_nama_bulan($bulan){
        Switch ($bulan){
        case 1 : $bulan="Januari";
            Break;
        case 2 : $bulan="Februari";
            Break;
        case 3 : $bulan="Maret";
            Break;
        case 4 : $bulan="April";
            Break;
        case 5 : $bulan="Mei";
            Break;
        case 6 : $bulan="Juni";
            Break;
        case 7 : $bulan="Juli";
            Break;
        case 8 : $bulan="Agustus";
            Break;
        case 9 : $bulan="September";
            Break;
        case 10 : $bulan="Oktober";
            Break;
        case 11 : $bulan="November";
            Break;
        case 12 : $bulan="Desember";
            Break;
        }
        return $bulan;
    }

}