<?php ob_start(); ?>

<div class="space-y-4 pb-12">
    <!-- TOP HEADER & CONTROLS -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 md:p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-base border border-amber-200 dark:border-amber-800">
                <i class="fa-solid fa-arrow-up-from-bracket"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Riwayat Stok Keluar</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Log pencatatan pengurangan stok karena retur, reject, atau display</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="?main=stok" class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-1.5 no-underline">
                <i class="fa-solid fa-arrow-down-to-bracket text-emerald-500"></i>
                <span>Stok Masuk</span>
            </a>
            <a href="?main=stok&action=keluar" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all bg-amber-600 text-white shadow-sm flex items-center gap-1.5 no-underline">
                <i class="fa-solid fa-arrow-up-from-bracket"></i>
                <span>Stok Keluar</span>
                <span class="bg-amber-800 text-amber-100 text-[10px] px-1.5 py-0.2 rounded-full"><?= count($viewlist ?? []) ?></span>
            </a>
            <a href="?main=barang" class="px-3 py-2 rounded-xl text-xs font-semibold bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center gap-1.5 no-underline transition-colors" title="Input Stok Keluar">
                <i class="fa-solid fa-plus text-xs"></i>
                <span class="hidden sm:inline">Catat Keluar</span>
            </a>
        </div>
    </div>

    <!-- NOTIFIKASI SUKSES -->
    <?php if (isset($_GET['success'])): ?>
        <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 flex items-center justify-between text-emerald-800 dark:text-emerald-300 text-xs font-semibold animate-fade-in">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-base text-emerald-500"></i>
                <span>Operasi stok keluar berhasil diperbarui!</span>
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
            <input type="text" id="filter-stok-input" placeholder="Cari produk atau alasan stok keluar..." class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-amber-500 transition-colors">
        </div>
        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 hidden sm:inline">
            Total Log: <strong><?= count($viewlist ?? []) ?></strong> riwayat
        </span>
    </div>

    <!-- TABEL STOK KELUAR -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300" id="table-stok">
                <thead class="bg-slate-50 dark:bg-slate-800/80 text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold border-b border-slate-200 dark:border-slate-700 select-none">
                    <tr>
                        <th class="px-4 py-3 text-center" style="width: 50px;">No.</th>
                        <th class="px-4 py-3">Nama Produk</th>
                        <th class="px-4 py-3 text-center" style="width: 100px;">Ukuran / Size</th>
                        <th class="px-4 py-3" style="width: 180px;">Waktu Keluar</th>
                        <th class="px-4 py-3">Alasan / Keterangan</th>
                        <th class="px-4 py-3 text-center" style="width: 110px;">Jumlah Keluar</th>
                        <th class="px-4 py-3 text-center" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php 
                    $no = 1;
                    $totalJumlahKeluar = 0;
                    if (!empty($viewlist)):
                        foreach($viewlist as $row): 
                            $totalJumlahKeluar += (int)$row['stok_keluar_jumlah'];
                    ?>
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors stok-row" data-search="<?= strtolower(htmlspecialchars($row['nama_barang'].' '.$row['stok_keluar_keterangan'])) ?>">
                        <td class="px-4 py-3 text-center font-bold text-slate-400"><?= $no++ ?></td>
                        
                        <!-- Nama Produk -->
                        <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs shrink-0">
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
                            <?= htmlspecialchars($row['stok_keluar_tanggal']) ?>
                        </td>

                        <!-- Keterangan -->
                        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">
                            <?= !empty($row['stok_keluar_keterangan']) ? htmlspecialchars($row['stok_keluar_keterangan']) : '<span class="text-slate-400 italic">Tanpa keterangan</span>' ?>
                        </td>

                        <!-- Jumlah -->
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 font-extrabold text-xs border border-rose-200 dark:border-rose-800/60">
                                -<?= $row['stok_keluar_jumlah'] ?> pcs
                            </span>
                        </td>

                        <!-- Aksi Hapus -->
                        <td class="px-4 py-3 text-center">
                            <form action="" method="post" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan riwayat stok keluar ini?')">
                                <input type="hidden" value="postdelstokkeluar" name="action">
                                <input type="hidden" value="<?= $row['stok_keluar_id'] ?>" name="stok_keluar_id">
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
                            <p class="text-xs">Belum ada riwayat stok keluar tercatat.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="bg-slate-50/90 dark:bg-slate-800/90 font-bold border-t border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white">
                    <tr>
                        <td colspan="5" class="px-4 py-2.5 text-right uppercase text-[10px] tracking-wider">TOTAL PCS KELUAR :</td>
                        <td class="px-4 py-2.5 text-center text-rose-600 dark:text-rose-400 font-extrabold text-sm">
                            -<?= $totalJumlahKeluar ?> pcs
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- PAGINATION CONTAINER -->
    <div id="stokKeluarPaginationContainer"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    window.stokKeluarPaginator = new TablePaginator({
        tableId: 'table-stok',
        rowSelector: '.stok-row',
        containerId: 'stokKeluarPaginationContainer',
        filterInputId: 'filter-stok-input',
        defaultPerPage: 20,
        perPageOptions: [10, 20, 50, 100, -1],
        itemLabel: 'riwayat'
    });
});
</script>

<?php $content = ob_get_clean(); ?>