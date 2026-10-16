<?php
require_once modules."/stok/StokModel.php";
$StokModel = new StokModel();
@$action   = $Helpers->xss_clean($_REQUEST['action']);
switch ($action) {
    case 'postdelstokmasuk': 
        $id      = $Helpers->xss_clean($_REQUEST['stok_masuk_id']);
        $execute = $StokModel->deleteStokMasuk($id);
        header("location:index.php?main=stok&success");
        break;
    case 'postdelstokkeluar': 
        $id      = $Helpers->xss_clean($_REQUEST['stok_keluar_id']);
        $execute = $StokModel->deleteStokKeluar($id);
        header("location:index.php?main=stok&action=keluar&success");
        break;
    case 'keluar': 
        $viewlist = $StokModel->getStokKeluar();
        include_once modules."/stok/stok-keluar.php";
        break;
    default: 
        $viewlist = $StokModel->getStokMasuk();
        include_once modules."/stok/stok-masuk.php";
        break;
}