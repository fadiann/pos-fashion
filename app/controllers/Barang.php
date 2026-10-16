<?php
require_once models."BarangModel.php";
$barangs       = new BarangModel();
@$action     = $Helpers->xss_clean($_REQUEST['action']);
@$barangid   = $Helpers->xss_clean($_REQUEST['barangid']);
@$namabarang = $Helpers->xss_clean($_REQUEST['namabarang']);
@$kategori   = $Helpers->xss_clean($_REQUEST['kategori']);
@$harga      = $Helpers->xss_clean($_REQUEST['harga']);
@$hpp        = $Helpers->xss_clean($_REQUEST['hpp']);
@$status     = $Helpers->xss_clean($_REQUEST['status']);
switch ($action) {
    case 'ajax_inline_price':
        header('Content-Type: application/json');
        $field = $Helpers->xss_clean($_POST['field'] ?? '');
        $col   = ($field === 'hpp') ? 'hpp_barang' : 'harga_barang';
        $val   = (float)str_replace(['Rp', '.', ',', ' '], '', $_POST['value'] ?? 0);
        $barangid = $Helpers->xss_clean($_POST['barangid'] ?? '');

        if (!$barangid || $val < 0) {
            echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
            exit;
        }

        $ok = $barangs->updateInlineField($barangid, $col, $val);
        echo json_encode([
            'success'   => (bool)$ok,
            'field'     => $field,
            'raw'       => $val,
            'formatted' => $Helpers->rp($val)
        ]);
        exit;

    case 'ajax_quick_restok':
        header('Content-Type: application/json');
        $produk_id   = $Helpers->xss_clean($_POST['barangid'] ?? '');
        $size_id     = $Helpers->xss_clean($_POST['size'] ?? '');
        $suplayer_id = $Helpers->xss_clean($_POST['suplayer'] ?? '');
        $jumlah      = (int)($_POST['jumlah'] ?? 0);
        $keterangan  = $Helpers->xss_clean($_POST['keterangan'] ?? 'Restok Cepat');

        if (!$produk_id || !$size_id || $jumlah <= 0) {
            echo json_encode(['success' => false, 'message' => 'Ukuran dan jumlah stok masuk harus valid!']);
            exit;
        }

        $barangs->addStok($produk_id, $size_id, $suplayer_id, $jumlah, $keterangan);
        echo json_encode([
            'success' => true,
            'message' => "Stok berhasil ditambahkan sebanyak {$jumlah} pcs!"
        ]);
        exit;

    case 'ajax_get_sizes':
        header('Content-Type: application/json');
        $kategori_id = $Helpers->xss_clean($_GET['kategori_id'] ?? '');
        $sizes = $barangs->getSizesByKategori($kategori_id);
        echo json_encode(['success' => true, 'sizes' => $sizes]);
        exit;

    case 'ajax_bulk_restok':
        header('Content-Type: application/json');
        $rawItems = $_POST['items'] ?? '[]';
        $items = is_string($rawItems) ? json_decode($rawItems, true) : $rawItems;

        if (!is_array($items) || empty($items)) {
            echo json_encode(['success' => false, 'message' => 'Tidak ada item yang dipilih untuk direstok.']);
            exit;
        }

        $successCount = 0;
        $totalPcs     = 0;

        foreach ($items as $item) {
            $pId    = $Helpers->xss_clean($item['barangid'] ?? '');
            $sId    = $Helpers->xss_clean($item['size'] ?? '');
            $supId  = $Helpers->xss_clean($item['suplayer'] ?? '1');
            $qty    = (int)($item['jumlah'] ?? 0);
            $note   = $Helpers->xss_clean($item['keterangan'] ?? 'Restok Masal');

            if ($pId && $sId && $qty > 0) {
                $barangs->addStok($pId, $sId, $supId, $qty, $note);
                $successCount++;
                $totalPcs += $qty;
            }
        }

        if ($successCount > 0) {
            echo json_encode([
                'success'     => true,
                'total_items' => $successCount,
                'total_pcs'   => $totalPcs,
                'message'     => "Berhasil merestok {$successCount} produk dengan total {$totalPcs} pcs!"
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal merestok. Pastikan ukuran dan jumlah stok valid.'
            ]);
        }
        exit;

    case 'postadd': 
        $execute = $barangs->addBarang($barangid, $namabarang, $kategori, $harga, $hpp, $status);
        header("location:index.php?main=barang&success");
        break;
    case 'postedit': 
        $execute = $barangs->saveEditBarang($barangid, $namabarang, $kategori, $harga, $hpp, $status);
        header("location:index.php?main=barang&success");
        break;
    case 'postdelete': 
        $execute = $barangs->deleteBarang($barangid);
        header("location:index.php?main=barang&success");
        break;
    case 'postaddstokmasuk': 
        $produk_id   = $Helpers->xss_clean($_REQUEST['barangid']);
        $size_id     = $Helpers->xss_clean($_REQUEST['size']);
        $suplayer_id = $Helpers->xss_clean($_REQUEST['suplayer']);
        $jumlah      = $Helpers->xss_clean($_REQUEST['jumlah']);
        $keterangan  = $Helpers->xss_clean($_REQUEST['keterangan']);
        if($keterangan == ''){
            $keterangan = 'Penambahan';
        }
        $execute     = $barangs->addStok($produk_id, $size_id, $suplayer_id, $jumlah, $keterangan);
        header("location:index.php?main=barang&action=masuk&id=".$produk_id."&success");
        break;
    case 'postaddstokkeluar': 
        $produk_id   = $Helpers->xss_clean($_REQUEST['barangid']);
        $size_id     = $Helpers->xss_clean($_REQUEST['size']);
        $jumlah      = $Helpers->xss_clean($_REQUEST['jumlah']);
        $keterangan  = $Helpers->xss_clean($_REQUEST['keterangan']);
        if($keterangan == ''){
            $keterangan = 'Reject';
        }
        $execute     = $barangs->addStokKeluar($produk_id, $size_id, $jumlah, $keterangan);
        header("location:index.php?main=barang&action=keluar&id=".$produk_id."&success");
        break;
    case 'edit': 
        $barangId = $Helpers->xss_clean($_GET['id']);
        $viewlist = $barangs->getEditBarang($barangId);
        include_once views."/barang/barang-edit.php";
        break;
    case 'masuk': 
        $barangId = $Helpers->xss_clean($_GET['id']);
        $row      = $barangs->getBarangById($barangId);
        include_once views."/barang/barang-stok-masuk.php";
        break;
    case 'keluar': 
        $barangId = $Helpers->xss_clean($_GET['id']);
        $row      = $barangs->getBarangById($barangId);
        include_once views."/barang/barang-stok-keluar.php";
        break;
    case 'add': 
        include_once views."/barang/barang-add.php";
        break;
    default: 
        $viewlist = $barangs->getAllBarang();
        include_once views."/barang/barang.php";
        break;
}