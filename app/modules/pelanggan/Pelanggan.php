<?php
require_once modules."/pelanggan/PelangganModel.php";
$model             = new PelangganModel();
@$action           = $Helpers->xss_clean($_REQUEST['action']);
@$pelanggan_id     = $Helpers->xss_clean($_REQUEST['pelanggan_id']);
@$pelanggan_nama   = $Helpers->xss_clean($_REQUEST['pelanggan_nama']);
@$pelanggan_nomor  = $Helpers->xss_clean($_REQUEST['pelanggan_nomor']);
@$pelanggan_alamat = $Helpers->xss_clean($_REQUEST['pelanggan_alamat']);
@$pelanggan_status = $Helpers->xss_clean($_REQUEST['pelanggan_status']);
switch ($action) {
    case 'postadd': 
        $execute = $model->addPelanggan($pelanggan_id, $pelanggan_nama, $pelanggan_nomor, $pelanggan_alamat, $pelanggan_status);
        header("location:index.php?main=pelanggan&success");
        break;
    case 'postedit': 
        $execute = $model->saveEditPelanggan($pelanggan_id, $pelanggan_nama, $pelanggan_nomor, $pelanggan_alamat, $pelanggan_status);
        header("location:index.php?main=pelanggan&success");
        break;
    case 'postdelete': 
        $execute = $model->deletePelanggan($pelanggan_id);
        AppLogger::action('pelanggan', 'Hapus Pelanggan', "Menghapus pelanggan ID {$pelanggan_id}", ['pelanggan_id' => $pelanggan_id]);
        header("location:index.php?main=pelanggan&success=deleted");
        exit;
    case 'postdelete_bulk':
        $pelanggan_ids = $_POST['pelanggan_ids'] ?? [];
        if (!empty($pelanggan_ids) && is_array($pelanggan_ids)) {
            $model->deleteMultiplePelanggan($pelanggan_ids);
            $cnt = count($pelanggan_ids);
            AppLogger::action('pelanggan', 'Hapus Banyak Pelanggan (Bulk)', "Menghapus {$cnt} pelanggan sekaligus", ['pelanggan_ids' => $pelanggan_ids]);
            header("location:index.php?main=pelanggan&success=bulk_deleted&count={$cnt}");
        } else {
            header("location:index.php?main=pelanggan");
        }
        exit;
    case 'edit': 
        $pelanggan_id = $Helpers->xss_clean($_GET['id']);
        $viewlist     = $model->getEditPelanggan($pelanggan_id);
        include_once modules."/pelanggan/pelanggan-edit.php";
        break;
    case 'add': 
        include_once modules."/pelanggan/pelanggan-add.php";
        break;
    default: 
        $viewlist = $model->getAllPelanggan();
        include_once modules."/pelanggan/pelanggan-list.php";
        break;
}