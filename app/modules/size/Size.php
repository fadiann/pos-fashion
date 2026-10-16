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
        header("location:index.php?main=size&success");
        break;
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