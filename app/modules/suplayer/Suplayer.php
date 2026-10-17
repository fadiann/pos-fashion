<?php
require_once modules."/suplayer/SuplayerModel.php";
$model             = new SuplayerModel();
@$action           = $Helpers->xss_clean($_REQUEST['action']);
@$suplayer_id     = $Helpers->xss_clean($_REQUEST['suplayer_id']);
@$suplayer_nama   = $Helpers->xss_clean($_REQUEST['suplayer_nama']);
@$suplayer_nomor  = $Helpers->xss_clean($_REQUEST['suplayer_nomor']);
@$suplayer_alamat = $Helpers->xss_clean($_REQUEST['suplayer_alamat']);
@$suplayer_status = $Helpers->xss_clean($_REQUEST['suplayer_status']);
switch ($action) {
    case 'postadd': 
        $execute = $model->addSuplayer($suplayer_id, $suplayer_nama, $suplayer_nomor, $suplayer_alamat, $suplayer_status);
        header("location:index.php?main=suplayer&success");
        break;
    case 'postedit': 
        $execute = $model->saveEditSuplayer($suplayer_id, $suplayer_nama, $suplayer_nomor, $suplayer_alamat, $suplayer_status);
        header("location:index.php?main=suplayer&success");
        break;
    case 'postdelete': 
        $execute = $model->deleteSuplayer($suplayer_id);
        AppLogger::action('produk', 'Hapus Supplier', "Menghapus supplier ID {$suplayer_id}", ['suplayer_id' => $suplayer_id]);
        header("location:index.php?main=suplayer&success=deleted");
        exit;
    case 'postdelete_bulk':
        $suplayer_ids = $_POST['suplayer_ids'] ?? [];
        if (!empty($suplayer_ids) && is_array($suplayer_ids)) {
            $model->deleteMultipleSuplayer($suplayer_ids);
            $cnt = count($suplayer_ids);
            AppLogger::action('produk', 'Hapus Banyak Supplier (Bulk)', "Menghapus {$cnt} supplier sekaligus", ['suplayer_ids' => $suplayer_ids]);
            header("location:index.php?main=suplayer&success=bulk_deleted&count={$cnt}");
        } else {
            header("location:index.php?main=suplayer");
        }
        exit;
    case 'edit': 
        $suplayer_id = $Helpers->xss_clean($_GET['id']);
        $viewlist     = $model->getEditSuplayer($suplayer_id);
        include_once modules."/suplayer/suplayer-edit.php";
        break;
    case 'add': 
        include_once modules."/suplayer/suplayer-add.php";
        break;
    default: 
        $viewlist = $model->getAllSuplayer();
        include_once modules."/suplayer/suplayer-list.php";
        break;
}