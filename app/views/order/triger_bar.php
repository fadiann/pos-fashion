<?php
date_default_timezone_set('Asia/Jakarta');
require_once "../../lib/Database.php";
$DB     = new Database();
$tanggalToday = date("Y-m-d");
//$tanggal    = "2018-06-30";
$strQuery     = mysql_query("SELECT triger_bar FROM tbl_triger LIMIT 1");
//echo $strQuery;
$result       = mysql_fetch_row($strQuery);
echo $result[0];
?>
