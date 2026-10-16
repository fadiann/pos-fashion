<?php ob_start() ?>
<div class="row">
    <h3>LAPORAN PEMASUKAN</h3>   
</div>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link" href="?main=keuangan&submain=kredit">KREDIT</a>
    </li>
    <li class="nav-item active">
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
<div class="row">
    <div class="col col-md-12">
        <table class="table table-bordered table-dark table-hover">
            <tr>
                <th width="5%">No.</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Kredit Kategori</th>
                <th width="45%">Keterangan</th>
                <th width="20%">Nominal</th>
                <th>Action</th>
            </tr>
            <?php 
            $no = 1;
            $total = 0;
            foreach($m_keuangan->view_all_kredit() as $row): 
                $total += $row['kredit'];
            ?>
            <tr>
                <td class="text-center"><?=$no++?>.</td>
                <td class="text-center"><?=$comf->balik_tanggal($row['tanggal'])?></td>
                <td><?=$row['kredit_kategori']?></td>
                <td><?=$row['keterangan']?></td>
                <td class="text-right"><?=$comf->rp($row['kredit'])?></td>
                <td></td>
            </tr>
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