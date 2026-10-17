<?php
    require_once models."TransaksiModel.php";
    require_once models."BarangModel.php";
    $model       = new TransaksiModel();
    $BarangModel = new BarangModel();
    $daftarBarang = $BarangModel->getAllBarang();
    if(isset($_POST['action'])){
        $action = htmlspecialchars($_POST['action']);
        switch ($action) {
            case 'simpantransaksi':
                $idTran         = $Helpers->xss_clean($_POST['idtran']);
                $pelanggan_id   = $Helpers->xss_clean($_POST['pelanggan_id'] ?? '1');
                $tipe           = $Helpers->xss_clean($_POST['tipe'] ?? '0');
                $execute        = $model->addTransaksi($idTran, $pelanggan_id, $tipe);
                AppLogger::action('transaksi', 'Mulai Transaksi Baru', "Nomor Transaksi: {$idTran}", [
                    'transaksi_id' => $idTran,
                    'pelanggan_id' => $pelanggan_id,
                    'tipe'         => $tipe
                ]);
                header("location: index.php?main=transaksi&idTransaksi=$idTran&success");
                exit;
                break;
            case 'tambahpesanan':
                $idTransaksi    = $Helpers->xss_clean($_POST['idtransaksi']);
                $pesanan        = trim($Helpers->xss_clean($_POST['pesanan']));
                $size_id        = $Helpers->xss_clean($_POST['size']);
                $pesananId      = explode("|", $pesanan);
                $jumlah         = (int)($_POST['jumlah']);
                $keterangan     = $Helpers->xss_clean($_POST['keterangan'] ?? '');
                $execute        = $model->addPesanan($idTransaksi, $pesananId[0], $size_id, $jumlah, $keterangan);
                AppLogger::action('transaksi', 'Tambah Item Belanja', "Transaksi {$idTransaksi}: Item {$pesanan} ({$jumlah} pcs)", [
                    'transaksi_id' => $idTransaksi,
                    'barang_id'    => $pesananId[0],
                    'size_id'      => $size_id,
                    'qty'          => $jumlah
                ]);
                header("location: index.php?main=transaksi&idTransaksi=$idTransaksi&success");
                exit;
                break;
            case 'checkTran':
                $idTransaksi = $Helpers->xss_clean($_POST['idTransaksi']);
                $checkTran   = $model->checkTran($idTransaksi);
                AppLogger::action('transaksi', 'Pembayaran Selesai', "Transaksi {$idTransaksi} berhasil dibayar & diselesaikan.", [
                    'transaksi_id' => $idTransaksi
                ]);
                header("location: index.php?main=transaksi&success");
                break;
            case 'deletetransaksi':
                $unix_id        = (int)($_POST['unix_id']);
                $idTransaksi    = $Helpers->xss_clean($_POST['idTran']);
                $execute        = $model->delPesanan($unix_id);
                header("location: index.php?main=transaksi&idTransaksi=$idTransaksi&success");
                break;
            case 'deletepesanan':
                $unix_id        = (int)($_POST['unix_id']);
                $idTransaksi    = $Helpers->xss_clean($_POST['idTran']);
                $execute        = $model->delPesanan($unix_id);
                header("location: index.php?main=transaksi&idTransaksi=$idTransaksi&success");
                break;
            case 'edittransaksi':
                $idTransaksi  = $Helpers->xss_clean($_GET['idTransaksi']);
                $pelanggan_id = $Helpers->xss_clean($_POST['pelanggan_id']);
                $tipe         = $Helpers->xss_clean($_POST['tipe']);
                $execute      = $model->editTransaksi($idTransaksi, $pelanggan_id, $tipe);
                header("location: index.php?main=transaksi&idTransaksi=$idTransaksi&success");
                break;
            
            default:
                break;
        }
    }
    if(isset($_GET['idTransaksi'])){
        @$action = $_GET['action'];
        if($action == 'deletetransaksi'){
            $idTransaksi        = $Helpers->xss_clean($_GET['idTransaksi']);
            $model->hapusTransaksi($idTransaksi);
            header("location: index.php?main=transaksi&success");
        }else{
            $idTransaksi        = $Helpers->xss_clean($_GET['idTransaksi']);
            $viewlistpesanan    = $model->getPesanan($idTransaksi);
            $total              = $model->getTotal($idTransaksi);
        }
    }
    
    $viewlist        = $model->getAllTransaksiToday();
    $viewlistgojek   = $model->getAllTransaksiTodayGojek();
    $ringkasanHarian = $model->getRingkasanHarianKasir();
    
    include_once views."/transaksi/transaksi.php";
?>