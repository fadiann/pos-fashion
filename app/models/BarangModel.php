<?php
class BarangModel {
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }   
    public function getAllBarang(){
        $sql    = "SELECT * FROM tbl_barang 
                LEFT JOIN tbl_kategori ON tbl_kategori.kategori_id = tbl_barang.barang_kategori_id 
                ORDER BY kategori_id";
        $data   = $this->db->query($sql);
        $barang = array();
        while($row = $data->FetchRow()){
            $barang[] = $row;
        }
        return $barang;
    }
    public function getStokByProdukId($produk_id){
        $sql    = "SELECT *, SUM(tbl_stok_masuk.stok_masuk_jumlah) as total_per_size FROM tbl_stok_masuk 
                INNER JOIN tbl_barang ON tbl_barang.barang_id = tbl_stok_masuk.stok_masuk_produk_id 
                INNER JOIN tbl_size ON tbl_size.size_id = tbl_stok_masuk.stok_masuk_size_id
                WHERE tbl_stok_masuk.stok_masuk_produk_id = '$produk_id' GROUP BY tbl_stok_masuk.stok_masuk_size_id";
                // echo $sql;
        // $data   = $this->db->assoc($sql);
        $data   = $this->db->query($sql)->GetArray();
        return $data;
    }
    public function getStokByProdukSize($produk_id, $size_id){
        $sql    = "SELECT SUM(tbl_stok_masuk.stok_masuk_jumlah) as total_per_size FROM tbl_stok_masuk 
                INNER JOIN tbl_barang ON tbl_barang.barang_id = tbl_stok_masuk.stok_masuk_produk_id 
                INNER JOIN tbl_size ON tbl_size.size_id = tbl_stok_masuk.stok_masuk_size_id
                WHERE tbl_stok_masuk.stok_masuk_produk_id = '$produk_id' AND tbl_size.size_id = '$size_id'";
        $data   = $this->db->query($sql);
        $row    = $data ? $data->FetchRow() : null;
        return ($row && isset($row[0]) && $row[0] !== null) ? (int)$row[0] : 0;
    }
    public function getSoldByProdukSize($produk_id, $size_id){
        $sql    = "SELECT SUM(tbl_pesanan.jumlah) as sold_per_size FROM tbl_pesanan 
                INNER JOIN tbl_barang ON tbl_barang.barang_id = tbl_pesanan.barang_id 
                INNER JOIN tbl_size ON tbl_size.size_id = tbl_pesanan.pesanan_size_id
                WHERE tbl_pesanan.barang_id = '$produk_id' AND tbl_size.size_id = '$size_id'";
        $data   = $this->db->query($sql);
        $row    = $data ? $data->FetchRow() : null;
        return ($row && isset($row[0]) && $row[0] !== null) ? (int)$row[0] : 0;
    }
    public function getOutStokByProdukSize($produk_id, $size_id){
        $sql    = "SELECT SUM(tbl_stok_keluar.stok_keluar_jumlah) as out_per_size FROM tbl_stok_keluar 
                INNER JOIN tbl_barang ON tbl_barang.barang_id = tbl_stok_keluar.stok_keluar_produk_id 
                INNER JOIN tbl_size ON tbl_size.size_id = tbl_stok_keluar.stok_keluar_size_id
                WHERE tbl_stok_keluar.stok_keluar_produk_id = '$produk_id' AND tbl_stok_keluar.stok_keluar_size_id = '$size_id'";
        $data   = $this->db->query($sql);
        $row    = $data ? $data->FetchRow() : null;
        return ($row && isset($row[0]) && $row[0] !== null) ? (int)$row[0] : 0;
    }
    public function addBarang($barangid, $namabarang, $kategori, $harga, $hpp, $status){
        $barangid   = addslashes(trim($barangid));
        $namabarang = addslashes(trim($namabarang));
        $kategori   = (int)$kategori;
        $harga      = (float)$harga;
        $hpp        = (float)$hpp;
        $status     = addslashes($status);
        $sql = "INSERT INTO tbl_barang (barang_id, barang_kategori_id, nama_barang, hpp_barang, harga_barang, status) VALUES ('$barangid', '$kategori', '$namabarang', '$hpp', '$harga', '$status')";
        $this->db->query($sql);
    }    
    public function addStok($produk_id, $size_id, $suplayer_id, $jumlah, $keterangan){
        $produk_id   = addslashes(trim($produk_id));
        $size_id     = (int)$size_id;
        $suplayer_id = (int)$suplayer_id;
        $jumlah      = (int)$jumlah;
        $keterangan  = addslashes(trim($keterangan));
        $sql = "INSERT INTO `tbl_stok_masuk` (`stok_masuk_id`, `stok_masuk_produk_id`, `stok_masuk_size_id`, `stok_masuk_suplayer_id`, `stok_masuk_jumlah`, `stok_masuk_keterangan`, `stok_masuk_tanggal`) VALUES (NULL, '$produk_id', '$size_id', '$suplayer_id', '$jumlah', '$keterangan', CURRENT_TIMESTAMP)";
        $this->db->query($sql);
    }    
    public function addStokKeluar($produk_id, $size_id, $jumlah, $keterangan){
        $produk_id   = addslashes(trim($produk_id));
        $size_id     = (int)$size_id;
        $jumlah      = (int)$jumlah;
        $keterangan  = addslashes(trim($keterangan));
        $sql = "INSERT INTO `tbl_stok_keluar` (`stok_keluar_id`, `stok_keluar_produk_id`, `stok_keluar_size_id`, `stok_keluar_jumlah`, `stok_keluar_keterangan`, `stok_keluar_tanggal`) VALUES (NULL, '$produk_id', '$size_id', '$jumlah', '$keterangan', CURRENT_TIMESTAMP)";
        $this->db->query($sql);
    }    
    public function getEditBarang($barangId){
        $barangId = addslashes(trim($barangId));
        $sql    = "SELECT * FROM tbl_barang
                LEFT JOIN tbl_kategori ON tbl_kategori.kategori_id = tbl_barang.barang_kategori_id 
                where barang_id = '$barangId'";
        $data   = $this->db->query($sql);
        $barang = array();
        while($row = $data->FetchRow()){
            $barang[] = $row;
        }
        return $barang;
    }
    public function getBarangById($barangId){
        $barangId = addslashes(trim($barangId));
        $sql    = "SELECT * FROM tbl_barang
                LEFT JOIN tbl_kategori ON tbl_kategori.kategori_id = tbl_barang.barang_kategori_id 
                where barang_id = '$barangId'";
        $data   = $this->db->query($sql);
        $barang = $data ? $data->FetchRow() : null;
        return $barang;
    }
    public function saveEditBarang($barangid, $namabarang, $kategori,  $harga, $hpp, $status){
        $barangid   = addslashes(trim($barangid));
        $namabarang = addslashes(trim($namabarang));
        $kategori   = (int)$kategori;
        $harga      = (float)$harga;
        $hpp        = (float)$hpp;
        $status     = addslashes($status);
        $sql = "UPDATE tbl_barang SET barang_kategori_id = '$kategori', nama_barang = '$namabarang', hpp_barang = '$hpp', harga_barang = '$harga', status = '$status' WHERE barang_id = '$barangid'";
        $this->db->query($sql);
    }
    public function deleteBarang($barangid){
        $barangid = addslashes(trim($barangid));
        $sql = "DELETE FROM tbl_barang where barang_id = '$barangid'";
        $this->db->query($sql);
    }
    
    function getStokProduk($produk_id, $size_id){
        $stoks               = $this->getStokByProdukSize($produk_id, $size_id);
        $total_sold_per_size = $this->getSoldByProdukSize($produk_id, $size_id);
        $total_out_per_size  = $this->getOutStokByProdukSize($produk_id, $size_id);
        $total_stok          = $stoks - $total_sold_per_size - $total_out_per_size;
        return $total_stok;
    }

    public function getSizesWithStockByProduk($produk_id){
        $sql = "SELECT tbl_size.size_id, tbl_size.size_nama FROM tbl_size 
                INNER JOIN tbl_kategori ON tbl_size.size_kategori_id = tbl_kategori.kategori_id 
                INNER JOIN tbl_barang ON tbl_barang.barang_kategori_id = tbl_kategori.kategori_id
                WHERE tbl_barang.barang_id = '$produk_id'
                ORDER BY tbl_size.size_nama ASC";
        $data = $this->db->query($sql);
        $result = [];
        if($data){
            while($row = $data->FetchRow()){
                $stok = $this->getStokProduk($produk_id, $row['size_id']);
                $result[] = [
                    'size_id'   => $row['size_id'],
                    'size_nama' => $row['size_nama'],
                    'stok'      => (int)$stok
                ];
            }
        }
        return $result;
    }

    /**
     * Generate kode produk unik otomatis (misal: BRG-2610-8A3F)
     */
    public function generateKodeBarangUnik($prefix = 'BRG') {
        do {
            $randomPart = strtoupper(substr(uniqid(), -4));
            $code = $prefix . '-' . date('ym') . '-' . $randomPart;
            
            $sql = "SELECT COUNT(*) FROM tbl_barang WHERE barang_id = '$code'";
            $res = $this->db->query($sql);
            $count = ($res && ($row = $res->FetchRow())) ? (int)$row[0] : 0;
        } while ($count > 0);
        
        return $code;
    }

    /**
     * Update harga atau HPP barang secara inline
     */
    public function updateInlineField($barangid, $field, $value) {
        if (!in_array($field, ['harga_barang', 'hpp_barang'])) {
            return false;
        }
        $barangid = addslashes(trim($barangid));
        $valClean = (float)$value;
        $sql = "UPDATE tbl_barang SET {$field} = '{$valClean}' WHERE barang_id = '$barangid'";
        return $this->db->query($sql);
    }

    /**
     * Ambil daftar ukuran berdasarkan ID kategori produk
     */
    public function getSizesByKategori($kategori_id) {
        $kategori_id = (int)$kategori_id;
        $sql = "SELECT * FROM tbl_size WHERE size_kategori_id = '$kategori_id' ORDER BY size_nama ASC";
        $data = $this->db->query($sql);
        $result = [];
        if ($data) {
            while ($row = $data->FetchRow()) {
                $result[] = $row;
            }
        }
        return $result;
    }

    /**
     * Ambil seluruh daftar supplier
     */
    public function getAllSuppliers() {
        $sql = "SELECT * FROM tbl_suplayer ORDER BY suplayer_nama ASC";
        $data = $this->db->query($sql);
        $result = [];
        if ($data) {
            while ($row = $data->FetchRow()) {
                $result[] = $row;
            }
        }
        return $result;
    }

    /**
     * Ambil seluruh data ukuran yang dikelompokkan berdasarkan kategori
     */
    public function getAllSizesGrouped() {
        $sql = "SELECT size_id, size_kategori_id, size_nama FROM tbl_size ORDER BY size_nama ASC";
        $data = $this->db->query($sql);
        $result = [];
        if ($data) {
            while ($row = $data->FetchRow()) {
                $katId = $row['size_kategori_id'];
                if (!isset($result[$katId])) {
                    $result[$katId] = [];
                }
                $result[$katId][] = [
                    'id'   => $row['size_id'],
                    'nama' => $row['size_nama']
                ];
            }
        }
        return $result;
    }
}