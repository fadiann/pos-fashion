<?php
require_once modules."/size/SizeModel.php";
$model          = new SizeModel();
@$action        = $Helpers->xss_clean($_REQUEST['action']);
@$size_nama     = $Helpers->xss_clean($_REQUEST['size_nama']);
@$size_kategori_id = $Helpers->xss_clean($_REQUEST['kategori']);
switch ($action) {
    case 'postadd': 
        $execute = $model->addSize($size_nama, $size_kategori_id);
        header("location:index.php?main=size&success");
        break;
    case 'postedit': 
        $size_id = $Helpers->xss_clean($_REQUEST['size_id'] ?? '');
        $execute = $model->saveEditSize($size_id, $size_nama, $size_kategori_id);
        header("location:index.php?main=size&success");
        break;
    case 'postdelete': 
        $size_id  = $Helpers->xss_clean($_REQUEST['size_id']);
        $execute = $model->deleteSize($size_id);
        AppLogger::action('produk', 'Hapus Ukuran Size', "Menghapus size ID {$size_id}", ['size_id' => $size_id]);
        header("location:index.php?main=size&success=deleted");
        exit;
    case 'postdelete_bulk':
        $size_ids = $_POST['size_ids'] ?? [];
        if (!empty($size_ids) && is_array($size_ids)) {
            $model->deleteMultipleSizes($size_ids);
            $countDeleted = count($size_ids);
            AppLogger::action('produk', 'Hapus Banyak Ukuran Size (Bulk)', "Menghapus {$countDeleted} data ukuran size sekaligus", ['size_ids' => $size_ids]);
            header("location:index.php?main=size&success=bulk_deleted&count={$countDeleted}");
        } else {
            header("location:index.php?main=size");
        }
        exit;
    case 'edit': 
        $size_id  = $Helpers->xss_clean($_GET['id']);
        $row = $model->getEditSize($size_id);
        include_once modules."/size/size-edit.php";
        break;
    case 'add': 
        include_once modules."/size/size-add.php";
        break;
    default: 
        $viewlist = $model->getAllSize();
        include_once modules."/size/size-list.php";
        break;
}