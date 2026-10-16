<?php
require_once models."LaporanModel.php";
require_once models."TransaksiModel.php";
$model                 = new LaporanModel();
$modelTransaksi        = new TransaksiModel();
if(isset($_POST['filter'])){
    $bulan = $Helpers->xss_clean($_REQUEST['bulan']);
    $tahun = $Helpers->xss_clean($_REQUEST['tahun']);
    $bulan_tahun = $tahun.'-'.$bulan;
}else{
    $bulan = date('m');
    $tahun = date('Y');
    $bulan_tahun = date('Y-m');
}
$viewlistToday         = $model->getAllTransaksiToday();
// $viewlistMonth         = $model->getAllTransaksiMonth();
// $viewOmsetHariIni      = $model->omsetHariIni();
// $omsetSudahBayar       = $model->omsetSudahBayar();
// $totalGojek            = $model->totalGojek();
// $viewOmsetBulanIni     = $model->omsetBulanIni();
$viewOmsetBulanan      = $model->omsetBulanan($bulan, $tahun);
// $viewTotalOmsetBulanan = $model->totalOmsetBulanan();
$trends                = $model->getTrendPenjualan($bulan, $tahun);
$nama_bulan = $Helpers->get_nama_bulan($bulan);
include_once views."/laporan/laporan.php";
?>