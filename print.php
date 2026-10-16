<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hanya user yang login (kasir/admin/owner) yang diizinkan memproses checkout
if (!isset($_SESSION['userId'])) {
    http_response_code(403);
    die("Akses ditolak. Silakan login terlebih dahulu.");
}

if(isset($_POST['idTran'])){
    $idTran     = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_POST['idTran'] ?? '');
    $status     = preg_replace('/[^0-9]/', '', $_POST['status'] ?? '0');

    $total_bayar      = (float)($_POST['total'] ?? 0);
    $potongan_harga   = (float)($_POST['potongan'] ?? 0);
    $sisa_bayar       = (float)($_POST['sisa_bayar'] ?? 0);
    $cash             = (float)($_POST['cash'] ?? 0);
    $jenis_pembayaran = (int)($_POST['jenis_pembayaran'] ?? 1);

    date_default_timezone_set('Asia/Jakarta');
    require_once 'Adodb/adodb.inc.php';
    include_once 'app/config/Database.php';
    include_once 'app/models/TransaksiModel.php';
    $DB           = new Database();
    $Transaksi    = new TransaksiModel();
    $tanggalToday = date("Y-m-d");
    //START PROMO
    $total_pembayaran = $total_bayar;
    if($status == 1){
        $total_pembayaran = $total[0];
        $potongan_harga   = $total[1];
        $potongan_harga   = $total[2];
        $sisa_pembayaran  = $total[3];
        $cash             = $total[4];
        $kembalian        = $cash - $sisa_pembayaran;
    }else{
        if($potongan_harga == 0){
            $potongan_harga = 0;
        }else{
            $potongan_harga = $potongan_harga;
        }
        $sisa_pembayaran = $total_pembayaran - $potongan_harga;
        $cash            = $cash;
        $kembalian       = $cash - $sisa_pembayaran;
    }
    if($status == '0'){
        $Transaksi->updateStatusTransaksi($jenis_pembayaran, $total_pembayaran, $potongan_harga, $sisa_pembayaran, $cash, $idTran);
    }
    header("location: index.php?main=print&transaksi_id=".$idTran);
}else{
    echo "not allowed !!";
}