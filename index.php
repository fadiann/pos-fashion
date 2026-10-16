<?php // file: index.php
error_reporting(E_ALL);
date_default_timezone_set('Asia/Jakarta');
require_once "app/lib/AuthSession.php";
AuthSession::init();
require_once 'Adodb/adodb.inc.php';
require_once "app/config/Database.php";
require_once "app/lib/Comfunction.php";
require_once "app/models/LaporanModel.php";
$DB      = new Database();
$Helpers = new Comfunction();
$Laporan = new LaporanModel();
//cek session login
if(!isset($_SESSION['userId'])){
    $main = $_GET['main'] ?? '';
    // Jika mengakses login atau submit login
    if ($main === 'login' || isset($_POST['login'])) {
        require_once models."LoginModel.php";
        require_once controllers."Login.php";
        include_once views."login/login.php";
    } else {
        // Akses publik default (domain utama) membuka Landing Page Linktree
        include_once views."landing/landing.php";
    }
}else{
    require_once "app/lib/RoleManager.php";
    $userLevel = $_SESSION['userLevel'] ?? 'kasir';

    if(isset($_GET['main'])){
        $main = htmlspecialchars($_GET['main']);

        // Jika user yang sudah login ingin melihat Landing Page publik
        if ($main === 'landing') {
            include_once views."landing/landing.php";
            exit;
        }

        // Jika user yang sudah login mengakses login, redirect ke beranda aplikasi
        if ($main === 'login') {
            header("location:index.php");
            exit;
        }

        // Proteksi Otorisasi Dinamis berbasis Role & Permission Database
        if (!RoleManager::can($userLevel, $main)) {
            $adminOnly = ['pengguna', 'migrasi', 'role_akses'];
            if (in_array($main, $adminOnly)) {
                header("location:index.php?access_denied=admin_only");
            } else {
                header("location:index.php?main=transaksi&access_denied=restricted");
            }
            exit;
        }

        if($main == "transaksi"){
            require_once controllers."/Transaksi.php";
            include_once views."template.php";

        // pakek modules
        
        }elseif($main == "pelanggan"){
            require_once modules."/pelanggan/Pelanggan.php";
            include_once views."template.php";

        }elseif($main == "suplayer"){
            require_once modules."/suplayer/Suplayer.php";
            include_once views."template.php";

        }elseif($main == "kategori"){
            require_once modules."/kategori/Kategori.php";
            include_once views."template.php";

        }elseif($main == "size"){
            require_once modules."/size/Size.php";
            include_once views."template.php";

        }elseif($main == "stok"){
            require_once modules."/stok/Stok.php";
            include_once views."template.php";

        }elseif($main == "print"){
            require_once modules."/print/Print.php";
            // include_once views."template.php";

        }elseif($main == "website"){
            require_once modules."website/Website.php";
            include_once views."template.php";
            // include_once views."template.php";

        }elseif($main == "pengguna"){
            require_once modules."pengaturan/Pengguna.php";
            include_once views."template.php";

        }elseif($main == "migrasi"){
            require_once modules."migrasi/Migrasi.php";
            include_once views."template.php";

        }elseif($main == "role_akses"){
            require_once modules."role_akses/RoleAkses.php";
            include_once views."template.php";

        // end pakek modules

        }elseif($main == "barang"){
            require_once controllers."/Barang.php";
            include_once views."template.php";

        }elseif($main == "laporan"){
            require_once controllers."/Laporan.php";
            include_once views."template.php";

        }elseif($main == "penjualan"){
            require_once controllers."/Penjualan.php";
            include_once views."template.php";

        }elseif($main == "keuangan"){
            require_once controllers."/Keuangan.php";
            include_once views."template.php";

        }elseif($main == "logout"){
            include_once models."LoginModel.php";
            include_once controllers."Login.php";
            include_once views."login/login.php";

        }else{
            include_once views."dashboard/dashboard.php";
            include_once views."template.php";
        }
    }else{
        if (RoleManager::can($userLevel, 'dashboard')) {
            $main = 'dashboard';
            include_once views."dashboard/dashboard.php";
            include_once views."template.php";
        } else {
            header("location:index.php?main=transaksi");
            exit;
        }
    }
}
?>