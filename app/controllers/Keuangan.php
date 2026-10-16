<?php
require_once models."KeuanganModel.php";
$m_keuangan = new KeuanganModel();
$comf       = $Helpers ?? new Comfunction();
if(isset($_GET['submain'])){
    $submain    = strip_tags($_GET['submain']);
    @$action     = strip_tags($_REQUEST['action']);
    if($submain == "kredit"){
        switch($action){
        case 'add':
            $kreditId        = strip_tags($_POST['kreditId']);
            $kredit_kategori = strip_tags($_POST['kredit_kategori']);
            $tanggal         = strip_tags($_POST['tanggal']);
            $keterangan      = strip_tags($_POST['keterangan']);
            $nominal         = strip_tags($_POST['nominal']);
            $m_keuangan->simpan_kredit($kreditId, $kredit_kategori, $tanggal, $keterangan, $nominal);
//            die();
            header('location: index.php?main=keuangan&submain=kredit');
        break;

        default:
            include_once views."/keuangan/kredit.php";
        break;
        }
    }elseif($submain == "debet"){
        switch($action){
        case 'add':
            $debetId        = strip_tags($_POST['debetId']);
            $debet_kategori = strip_tags($_POST['debet_kategori']);
            $tanggal        = strip_tags($_POST['tanggal']);
            $keterangan     = strip_tags($_POST['keterangan']);
            $nominal        = strip_tags($_POST['nominal']);
            $m_keuangan->simpan_debet($debetId, $debet_kategori, $tanggal, $keterangan, $nominal);
            header('location: index.php?main=keuangan&submain=debet');
        break;

        default:
            include_once views."/keuangan/debet.php";
        break;
        }
    }elseif($submain == "laporan_kredit"){
        $rs = $m_keuangan->view_all_kredit();
        include_once views."/keuangan/laporan_kredit.php";
    }elseif($submain == "laporan_debet"){
        if(isset($_POST['filter'])){
            $filter = strip_tags($_POST['debet_kategori']);
            include_once views."/keuangan/laporan_debet_filter.php";
        }else{
            include_once views."/keuangan/laporan_debet.php";
        }
    }else{
        include_once views."/keuangan/kredit.php";
    }
}else{
    include_once views."/keuangan/kredit.php";
}
?>