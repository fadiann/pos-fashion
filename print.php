<?php
require_once "app/lib/AuthSession.php";
AuthSession::init();

// Hanya user yang login (kasir/admin/owner) yang diizinkan memproses checkout
if (!isset($_SESSION['userId'])) {
    http_response_code(403);
    die("Akses ditolak. Silakan login terlebih dahulu.");
}

if(isset($_POST['idTran'])){
    $idTran     = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_POST['idTran'] ?? '');
    $status     = preg_replace('/[^0-9]/', '', $_POST['status'] ?? '0');

    $clean_num = function($v) {
        if (is_numeric($v)) return (float)$v;
        $s = preg_replace('/[^\d]/', '', (string)$v);
        return $s !== '' ? (float)$s : 0.0;
    };

    $total_bayar      = $clean_num($_POST['total'] ?? 0);
    $potongan_harga   = $clean_num($_POST['potongan'] ?? 0);
    $sisa_bayar       = $clean_num($_POST['sisa_bayar'] ?? 0);
    $cash             = $clean_num($_POST['cash'] ?? 0);
    $jenis_pembayaran = (int)($_POST['jenis_pembayaran'] ?? 1);

    date_default_timezone_set('Asia/Jakarta');
    require_once 'Adodb/adodb.inc.php';
    include_once 'app/config/Database.php';
    include_once 'app/models/TransaksiModel.php';
    @include_once 'app/lib/AppLogger.php';
    $DB           = new Database();
    $Transaksi    = new TransaksiModel();
    $tanggalToday = date("Y-m-d");

    //START PROMO / DISKON
    $total_pembayaran = $total_bayar;
    if($status == 1){
        $total_pembayaran = $total[0];
        $potongan_harga   = $total[1];
        $potongan_harga   = $total[2];
        $sisa_pembayaran  = $total[3];
        $cash             = $total[4];
        $kembalian        = $cash - $sisa_pembayaran;
    }else{
        if($potongan_harga < 0){
            $potongan_harga = 0;
        }
        if($potongan_harga > $total_pembayaran){
            $potongan_harga = $total_pembayaran;
        }
        $sisa_pembayaran = $total_pembayaran - $potongan_harga;
        $kembalian       = $cash - $sisa_pembayaran;
    }
    if($status == '0'){
        $Transaksi->updateStatusTransaksi($jenis_pembayaran, $total_pembayaran, $potongan_harga, $sisa_pembayaran, $cash, $idTran);
        if (class_exists('AppLogger')) {
            $diskonTxt = $potongan_harga > 0 ? " (Diskon: Rp " . number_format($potongan_harga, 0, ',', '.') . ")" : "";
            AppLogger::action('transaksi', 'Pembayaran Selesai', "Transaksi {$idTran} berhasil dibayar & diselesaikan. Total: Rp " . number_format($sisa_pembayaran, 0, ',', '.') . $diskonTxt, [
                'transaksi_id'         => $idTran,
                'total_sebelum_diskon' => $total_pembayaran,
                'potongan_harga'       => $potongan_harga,
                'total_pembayaran'     => $sisa_pembayaran,
                'cash'                 => $cash,
                'jenis_pembayaran'     => $jenis_pembayaran
            ]);
        }
    }
    header("location: index.php?main=print&transaksi_id=".$idTran);
    exit;
}else{
    echo "not allowed !!";
}
