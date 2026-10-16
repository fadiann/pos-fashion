<?php ob_start() ?>
<div class="row">
    <h3>REKAP PENJUALAN MAKANAN DAN MINUMAN HARI INI</h3>   
</div>
<br>
<ul class="nav nav-tabs">
    <li class="nav-item active">
        <a class="nav-link" href="?main=penjualan&submain=makanan">MAKANAN</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="?main=penjualan&submain=minuman">MINUMAN</a>
    </li>
</ul>
<hr>
<div class="col">
    <table class="table table-striped table-bordered table-hover" id="bootstrap-table">
        <thead>
        <tr>
            <th style="width:5%;">No.</th>
            <th style="width:10%;">ID Makanan</th>
            <th>Nama Makanan</th>
            <th style="width:10%;">PCS</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        foreach($viewListMakananHari as $row): 
        $no++;
        ?>
        <tr>
            <td align="center"><?= $no ?></td>
            <td align="center"><?= $row['0'] ?></td>
            <td><?= $row['1'] ?></td>
            <td align="center"><?= $row['2'] ?></td>
        </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php $content = ob_get_clean() ?>