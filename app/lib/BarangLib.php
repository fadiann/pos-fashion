<?php
require_once "Adodb/adodb.inc.php";
require_once "../config/Database.php";
require_once "Comfunction.php";
require_once "../models/TransaksiModel.php";
$DB      = new Database();
$comf    = new Comfunction();
$models  = new TransaksiModel();
$keyword = strip_tags($_POST['query']);
$barang  = array();
$data    = $models->getbarang($keyword);
$result  = count($data);
if($result > 0){
    foreach($data as $row){
		  $pesananResult[] = $row['barang_id']."| ".$row['nama_barang'];
    }
    echo json_encode($pesananResult);
}
?>