<?php
require_once modules."/kategori/KategoriModel.php";
$model             = new KategoriModel();
@$action           = $Helpers->xss_clean($_REQUEST['action']);
@$kategori_nama   = $Helpers->xss_clean($_REQUEST['kategori_nama']);
@$kategori_keterangan = $Helpers->xss_clean($_REQUEST['kategori_keterangan']);
@$kategori_status = $Helpers->xss_clean($_REQUEST['kategori_status']);
// die();
switch ($action) {
    case 'postadd': 
        $execute = $model->addKategori($kategori_nama, $kategori_keterangan, $kategori_status);
        header("location:index.php?main=kategori&success");
        break;
    case 'postedit': 
        $kategori_id = $Helpers->xss_clean($_REQUEST['kategori_id'] ?? '');
        $execute = $model->saveEditKategori($kategori_id, $kategori_nama, $kategori_keterangan, $kategori_status);
        header("location:index.php?main=kategori&success");
        break;
    case 'postdelete': 
        $kategori_id = $Helpers->xss_clean($_REQUEST['kategori_id']);
        $execute = $model->deleteKategori($kategori_id);
        header("location:index.php?main=kategori&success");
        break;
    case 'edit': 
        $kategori_id = $Helpers->xss_clean($_GET['id']);
        $viewlist     = $model->getEditKategori($kategori_id);
        include_once modules."/kategori/kategori-edit.php";
        break;
    case 'add': 
        include_once modules."/kategori/kategori-add.php";
        break;
    default: 
        $viewlist = $model->getAllKategori();
        include_once modules."/kategori/kategori-list.php";
        break;
}