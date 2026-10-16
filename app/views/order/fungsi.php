<?php
date_default_timezone_set('Asia/Jakarta');
require_once "../../lib/Database.php";
$DB     = new Database();
    $id    = $_POST['id'];
    $strSelesai = "UPDATE `tbl_pesanan` SET `status` = '2' WHERE `tbl_pesanan`.`unix_id` = '$id'";
    $query      = mysql_query($strSelesai);
?>