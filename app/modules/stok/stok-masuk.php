<?php ob_start(); ?>

<div class="space-y-4 pb-12">
    <!-- TOP HEADER & CONTROLS -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 md:p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-base border border-blue-200 dark:border-blue-800">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Manajemen Riwayat Stok</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Log pencatatan mutasi stok masuk dan keluar gudang</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="?main=stok" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all bg-emerald-600 text-white shadow-sm flex items-center gap-1.5 no-underline">
                <i class="fa-solid fa-arrow-down-to-bracket"></i>
                <span>Stok Masuk</span>
                <span class="bg-emerald-800 text-emerald-100 text-[10px] px-1.5 py-0.2 rounded-full"><?= count($viewlist ?? []) ?></span>
            </a>
            <a href="?main=stok&action=keluar" class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-1.5 no-underline">
                <i class="fa-solid fa-arrow-up-from-bracket text-amber-500"></i>
                <span>Stok Keluar</span>
            </a>
            <a href="?main=barang" class="px-3 py-2 rounded-xl text-xs font-semibold bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center gap-1.5 no-underline transition-colors" title="Input Restok Baru">
                <i class="fa-solid fa-plus text-xs"></i>
                <span class="hidden sm:inline">Restok Baru</span>
            </a>
        </div>
    </div>

    <!-- NOTIFIKASI SUKSES -->
    <?php if (isset($_GET['success'])): ?>
        <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 flex items-center justify-between text-emerald-800 dark:text-emerald-300 text-xs font-semibold animate-fade-in">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-base text-emerald-500"></i>
                <span>Operasi stok masuk berhasil diperbarui!</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-100">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- FILTER / SEARCH BAR -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between gap-3">
        <div class="relative w-full sm:w-80">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="filter-stok-input" placeholder="Cari produk atau keterangan stok masuk..." class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-colors">
        </div>
        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 hidden sm:inline">
            Total Log: <strong><?= count($viewlist ?? []) ?></strong> riwayat
        </span>
    </div>

    <!-- TABEL STOK MASUK -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300" id="table-stok">
                <thead class="bg-slate-50 dark:bg-slate-800/80 text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold border-b border-slate-200 dark:border-slate-700 select-none">
                    <tr>
                        <th class="px-4 py-3 text-center" style="width: 50px;">No.</th>
                        <th class="px-4 py-3">Nama Produk</th>
                        <th class="px-4 py-3 text-center" style="width: 100px;">Ukuran / Size</th>
                        <th class="px-4 py-3" style="width: 180px;">Waktu Masuk</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3 text-center" style="width: 110px;">Jumlah Masuk</th>
                        <th class="px-4 py-3 text-center" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php 
                    $no = 1;
                    $totalJumlahMasuk = 0;
                    if (!empty($viewlist)):
                        foreach($viewlist as $row): 
                            $totalJumlahMasuk += (int)$row['stok_masuk_jumlah'];
                    ?>
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors stok-row" data-search="<?= strtolower(htmlspecialchars($row['nama_barang'].' '.$row['stok_masuk_keterangan'])) ?>">
                        <td class="px-4 py-3 text-center font-bold text-slate-400"><?= $no++ ?></td>
                        
                        <!-- Nama Produk -->
                        <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs shrink-0">
                                    <i class="fa-solid fa-box"></i>
                                </span>
                                <span><?= htmlspecialchars($row['nama_barang']) ?></span>
                            </div>
                        </td>

                        <!-- Size -->
                        <td class="px-4 py-3 text-center">
                            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-extrabold text-[11px] border border-slate-200 dark:border-slate-700">
                                Size <?= htmlspecialchars($row['size_nama']) ?>
                            </span>
                        </td>

                        <!-- Tanggal Jam -->
                        <td class="px-4 py-3 text-slate-500 dark:text-slate-400 font-mono text-[11px]">
                            <i class="fa-regular fa-clock mr-1 text-slate-400"></i>
                            <?= htmlspecialchars($row['stok_masuk_tanggal']) ?>
                        </td>

                        <!-- Keterangan -->
                        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">
                            <?= !empty($row['stok_masuk_keterangan']) ? htmlspecialchars($row['stok_masuk_keterangan']) : '<span class="text-slate-400 italic">Tanpa catatan</span>' ?>
                        </td>

                        <!-- Jumlah -->
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-extrabold text-xs border border-emerald-200 dark:border-emerald-800/60">
                                +<?= $row['stok_masuk_jumlah'] ?> pcs
                            </span>
                        </td>

                        <!-- Aksi Hapus -->
                        <td class="px-4 py-3 text-center">
                            <form action="" method="post" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan riwayat stok masuk ini?')">
                                <input type="hidden" value="postdelstokmasuk" name="action">
                                <input type="hidden" value="<?= $row['stok_masuk_id'] ?>" name="stok_masuk_id">
                                <button type="submit" class="w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 flex items-center justify-center transition-colors mx-auto" title="Hapus Riwayat">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                            <i class="fa-solid fa-boxes-packing text-3xl mb-2 text-slate-300 dark:text-slate-600"></i>
                            <p class="text-xs">Belum ada riwayat stok masuk tercatat.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="bg-slate-50/90 dark:bg-slate-800/90 font-bold border-t border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white">
                    <tr>
                        <td colspan="5" class="px-4 py-2.5 text-right uppercase text-[10px] tracking-wider">TOTAL PCS MASUK :</td>
                        <td class="px-4 py-2.5 text-center text-emerald-600 dark:text-emerald-400 font-extrabold text-sm">
                            +<?= $totalJumlahMasuk ?> pcs
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- PAGINATION CONTAINER -->
    <div id="stokMasukPaginationContainer"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    window.stokMasukPaginator = new TablePaginator({
        tableId: 'table-stok',
        rowSelector: '.stok-row',
        containerId: 'stokMasukPaginationContainer',
        filterInputId: 'filter-stok-input',
        defaultPerPage: 20,
        perPageOptions: [10, 20, 50, 100, -1],
        itemLabel: 'riwayat'
    });
});
</script>

<?php $content = ob_get_clean(); ?>