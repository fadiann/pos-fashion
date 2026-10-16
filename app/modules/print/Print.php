<?php
require_once modules."/print/PrintModel.php";
$PrintModel = new PrintModel();
$Helpers    = $Helpers ?? new Comfunction();
@$action    = $Helpers->xss_clean($_REQUEST['action'] ?? '');
switch ($action) {
    case 'postdelstokmasuk': 
        $id = $Helpers->xss_clean($_REQUEST['stok_masuk_id'] ?? '');
        // $execute = $PrintModel->deleteStokMasuk($id);
        header("location:index.php?main=stok&success");
        break;
    default: 
        $transaksi_id = $Helpers->xss_clean($_REQUEST['transaksi_id'] ?? '');
        $transaksi    = $PrintModel->getDetailTransaksi($transaksi_id);
        $viewlist     = $PrintModel->getProdukByIdTransaksi($transaksi_id);
        include_once modules."/print/print-main.php";
        break;
}