<?php ob_start() ?>
<div class="row">
	<h3>DATA PENGGUNA</h3>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <?php $Helpers->successAction(); ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="bootstrap-table">
                <thead>
                    <tr>
                        <th style="width:8%;">ID</th>
                        <th>Kategori</th>
                        <th>Nama Size</th>
                        <th style="width:11%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach($data as $row): 
                    ?>
                    <tr>
                        <td align="center"><?= $row['size_id'] ?></td>
                        <td><?= $row['kategori_nama'] ?></td>
                        <td><?= $row['size_nama'] ?></td>
                        <td align="center">
                            <form action="" enctype="multipart/form-data" method="post">
                                <a class="btn btn-warning" href="index.php?main=size&action=edit&id=<?= $row['size_id'] ?>"
                                    aria-label="Edit">
                                    <i class="fa fa-pencil " aria-hidden="true"></i>
                                </a>
                                <input type="hidden" value="postdelete" name="action">
                                <input type="hidden" value="<?= $row['size_id'] ?>" name="size_id">
                                <button type="submit" name="simpan" class="btn btn-danger" onclick="confirm('Press a button!');">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
	</div>
</div>
<hr>
<?php $content = ob_get_clean() ?>