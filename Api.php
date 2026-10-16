<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hanya user yang login yang diizinkan mengakses API internal POS
if (!isset($_SESSION['userId'])) {
    http_response_code(403);
    die(json_encode(['error' => 'Akses ditolak. Silakan login terlebih dahulu.']));
}

require_once "Adodb/adodb.inc.php";
require_once "app/config/Database.php";
require_once "app/models/TransaksiModel.php";
require_once "app/models/BarangModel.php";
require_once "app/lib/Comfunction.php";
require_once modules."/pengaturan/PengaturanModel.php";
$DB             = new Database();
$Helper         = new Comfunction();
$TransaksiModel = new TransaksiModel();
$BarangModel    = new BarangModel();
$Pengaturan     = new PengaturanModel();

$action = isset($_POST['action']) ? $Helper->xss_clean($_POST['action']) : '';
$param  = isset($_POST['param']) ? $Helper->xss_clean($_POST['param']) : '';
switch ($action) {
    case 'getdatabarang': 
        $barang = array();
        $data   = $TransaksiModel->getbarang($param);
        $result = count($data);
        if($result > 0){
            foreach($data as $row){
                $pesananResult[] = $row['barang_id']."| ".$row['nama_barang'];
            }
            echo json_encode($pesananResult);
        }
        break;
    case 'getdatastokavailable': 
        $param = explode(':', $param);
        $produk_id = $param[0];
        $size_id = $param[1];
        $data = $BarangModel->getStokProduk($produk_id, $size_id);
        echo $data;
        break;
    case 'getdatasize': 
        $barang = array();
        $data   = $Helper->combo_size_byproduk($param);
        echo $data;
        break;
    case 'getsizeswithstock':
        header('Content-Type: application/json');
        $sizes = $BarangModel->getSizesWithStockByProduk($param);
        echo json_encode($sizes);
        break;

    case 'getedituser': 	
        $data_id = $Helper->xss_clean($_REQUEST['param']);
        $data    = $Pengaturan->getUserById($data_id);
        echo '
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Ubah Data User</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="'.$data['username'].'">
                    <input type="text" name="user_id" class="form-control" value="'.$data['user_id'].'">
                    <span class="text-muted">*) Huruf Kecil Tanpa Spasi</span>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control">
                    <input type="hidden" name="old_password" class="form-control" value="'.$data['password'].'">
                    <span class="text-muted">*) Kosongkan Jika Tidak Ingin Mengubah Password</span>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="cpassword" class="form-control">
                    <span class="text-muted">*) Kosongkan Jika Tidak Ingin Mengubah Password</span>
                </div>
                <div class="form-group">
                    <label>Level</label>
                    <select name="level" class="form-control">
                        <option value="kasir" '. (($data["level"] == "kasir") ? "selected" : '' ).' >Kasir</option>
                        <option value="admin" '. (($data["level"] == "admin") ? "selected" : '' ).'>Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
            </div>
        ';
        break;
    
    default: 
        echo "not allowed";
        break;
}