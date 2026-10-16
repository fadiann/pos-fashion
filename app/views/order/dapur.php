<?php

error_reporting(0);
date_default_timezone_set('Asia/Jakarta');
require_once "../../lib/Database.php";
$DB     = new Database();
$tanggalToday = date("Y-m-d");
//$tanggal    = "2018-06-30";
$strQuery     = mysql_query("SELECT *, tbl_barang.harga_barang * tbl_pesanan.jumlah as subtotal FROM tbl_pesanan inner join tbl_barang on tbl_pesanan.barang_id = tbl_barang.barang_id inner join tbl_transaksi on tbl_transaksi.transaksi_id = tbl_pesanan.transaksi_id where tbl_transaksi.tanggal LIKE '%$tanggalToday%' AND tbl_barang.barang_id NOT LIKE '%MI%' and tbl_pesanan.status = '1' order by tbl_pesanan.unix_id ASC");
mysql_query("UPDATE tbl_triger SET triger_dapur = '0' WHERE triger_id = '1'");
$result       = array();
?>

    <?php
	$x = 0;
    while($row = mysql_fetch_array($strQuery)){
        $x++;
    ?>
    <tr class="row100 body">
        <td class="cell100 column6" align="right"><?php echo $x; ?>.</td>
        <td class="cell100 column1"><?php echo "$row[7]"; ?></td>
        <td class="cell100 column2"><?php echo "$row[5]"; ?></td>
        <td class="cell100 column3" align="center" style="font-weight: bold; background: #6c7ae0; color: #000;"><div style="font-weight: bold; font-size: 25px; padding-top: 17px; width: 60px; height: 60px; background: yellow; border-radius: 30px;"><?php echo "$row[3]"; ?></div></td>
        <td class="cell100 column4" align="center"><?php echo "$row[12]"; ?></td>
        <td class="cell100 column5" align="center" style="background: yellow;"><button class="btn btn-info delete" id="del_<?php echo "$row[0]"; ?>">SELESAI</button></td>
    </tr>
    <?php } ?>
    
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <script>
    $(document).ready(function(){
    // Delete 
        $('.delete').click(function(){
        var el = this;
        var id = this.id;
        var splitid = id.split("_");
        // Delete id
        var deleteid = splitid[1];
        // AJAX Request
        $.ajax({
            url: '../app/views/order/fungsidapur.php',
            type: 'POST',
            data: { id:deleteid },
            success: function(response){
                $(el).closest('tr').fadeOut(150, function(){ 
                 $(this).remove();
                });
            }
            });
        });
    });
    </script>
