<?php ob_start() ?>
<div class="row">
    <h3>KEUANGAN (PEMASUKAN)</h3>   
</div>
<ul class="nav nav-tabs">
    <li class="nav-item active">
        <a class="nav-link" href="?main=keuangan&submain=kredit">KREDIT</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="?main=keuangan&submain=laporan_kredit">LAPORAN KREDIT</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="?main=keuangan&submain=debet">DEBET</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="?main=keuangan&submain=laporan_debet">LAPORAN DEBET</a>
    </li>
</ul>
<hr>

<form action="" enctype="multipart/form-data" method="post">
  <div class="form-group row">
    <div class="col-md-3 mb-3">
      <label for="validationDefault01">KREDIT ID</label>
      <input type="text" class="form-control" id="validationDefault01" value="<?=$m_keuangan->KreditKode()?>" name="kreditId" readonly>
    </div>
    <div class="col-md-3 mb-3">
      <label for="validationDefault01">KREDIT KATEGORI</label>
      <?=$comf->combo_kredit_kategori()?>
    </div>
    <div class="col-md-3 mb-3">
      <label for="validationDefault02">TANGGAL</label>
      <input type="text" class="form-control datepicker" id="validationDefault02" name="tanggal" required>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-9 mb-3">
      <label for="validationDefault02">KETERANGAN</label>
      <input type="text" class="form-control" id="validationDefault02" name="keterangan" required>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-3 mb-3">
      <label for="validationDefault04">KREDIT</label>
        <div class="input-group">
            <input type="number" class="form-control" id="validationDefault04" style="text-align:right;" name="nominal" required>
              <span class="input-group-addon">.00</span>
        </div>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-3 mb-3">
        <input type="hidden" value="add" name="action">
        <input type="submit" name="simpan" value="SIMPAN" class="btn btn-primary">
    </div>
  </div>
</form>
<?php $content = ob_get_clean() ?>