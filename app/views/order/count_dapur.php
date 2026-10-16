<?php
date_default_timezone_set('Asia/Jakarta');
require_once "../../lib/Database.php";
$DB     = new Database();
$tanggalToday = date("Y-m-d");
//$tanggal    = "2018-06-30";
$strQuery     = mysql_query("SELECT COUNT(*) FROM tbl_pesanan inner join tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id inner join tbl_transaksi on tbl_transaksi.transaksi_id = tbl_pesanan.transaksi_id where tbl_transaksi.tanggal LIKE '%$tanggalToday%' AND tbl_barang.barang_id NOT LIKE '%MI%' and tbl_pesanan.status = '1' order by tbl_pesanan.unix_id ASC");
$result       = mysql_fetch_row($strQuery);
echo $result[0];
?>
