<?php ob_start() ?>
<div class="row">
    <h3>LAPORAN PEMASUKAN</h3>   
</div>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link" href="?main=keuangan&submain=kredit">KREDIT</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="?main=keuangan&submain=laporan_kredit">LAPORAN KREDIT</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="?main=keuangan&submain=debet">DEBET</a>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="?main=keuangan&submain=laporan_debet">LAPORAN DEBET</a>
    </li>
</ul>
<hr>
<form action="" enctype="multipart/form-data" method="post">
  <div class="form-group row">
    <div class="col-md-3 mb-3">
      <?=$comf->combo_debet_kategori()?>
    </div>
<!--
    <div class="col-md-2 mb-2">
      <input type="text" class="form-control datepicker" id="validationDefault02" name="start" required>
    </div>
    <div class="col-md-2 mb-2">
      <input type="text" class="form-control datepicker" id="validationDefault02" name="end" required>
    </div>
-->
    <div class="col-md-3 mb-3">
        <input type="submit" name="filter" value="FILTER" class="btn btn-primary">
    </div>
  </div>
</form>
<div class="row">
    <div class="col col-md-12">
        <table class="table table-bordered table-dark table-hover">
            <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="10%">Tanggal</th>
                <th width="20%">Debet Kategori</th>
                <th width="45%">Keterangan</th>
                <th width="15%">Nominal</th>
                <th>Action</th>
            </tr>
            </thead>
            <?php 
            $no = 1;
            $total = 0;
            foreach($m_keuangan->view_all_debet() as $row): 
                $total += $row['debet'];
            ?>
            <tbody>
            <tr>
                <td class="text-center"><?=$no++?>.</td>
                <td class="text-center"><?=$comf->balik_tanggal($row['tanggal'])?></td>
                <td><?=$row['debet_kategori']?></td>
                <td><?=$row['keterangan']?></td>
                <td class="text-right"><?=$comf->rp($row['debet'])?></td>
                <td></td>
            </tr>
            </tbody>
            <?php endforeach; ?>
            <tr>
                <td colspan="4" class="text-right"><strong>TOTAL</strong></td>
                <td class="text-right"><?=$comf->rp($total)?></td>
                <td></td>
            </tr>
        </table>
    </div>
</div>
<?php $content = ob_get_clean() ?>