<?php
require_once models."PenjualanModel.php";
$model              = new PenjualanModel();
$viewListMakanan    = $model->getJualMakanan();
$viewListMinuman    = $model->getJualMinuman();
if(isset($_GET['submain'])){
    $submain    = strip_tags($_GET['submain']);
    if($submain == "makanan"){
        include_once views."/penjualan/penjualanmakanan.php";
    }elseif($submain == "minuman"){
        include_once views."/penjualan/penjualanminuman.php";
    }else{
        include_once views."/penjualan/penjualanmakanan.php";
    }
}else{
    include_once views."/penjualan/penjualanmakanan.php";
}
?>