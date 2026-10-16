<?php ob_start(); ?>
<?php foreach ($viewlist as $row) : 
    $cek = $row['status'];
?>

<div class="space-y-4 pb-12 max-w-4xl mx-auto">
    <!-- TOP HEADER -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 md:p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-base border border-amber-200 dark:border-amber-800">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Ubah Data Produk</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Perbarui informasi harga, nama, atau kategori produk</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="?main=barang" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-1.5 no-underline transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- FORM EDIT PRODUK -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-5 md:p-6 border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <form action="" method="post" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- ID Barang (Readonly) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">ID / Kode Produk</label>
                    <input type="text" name="barangid" value="<?= htmlspecialchars($row['barang_id']) ?>" readonly class="w-full px-3 py-2.5 bg-slate-100 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-mono font-bold text-slate-500 dark:text-slate-400 cursor-not-allowed">
                </div>

                <!-- Nama Produk -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nama Produk <span class="text-rose-500">*</span></label>
                    <input type="text" name="namabarang" value="<?= htmlspecialchars($row['nama_barang']) ?>" required class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
                </div>
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Kategori Produk <span class="text-rose-500">*</span></label>
                <div class="[&>select]:w-full [&>select]:px-3 [&>select]:py-2.5 [&>select]:bg-slate-50 [&>select]:dark:bg-slate-800 [&>select]:border [&>select]:border-slate-300 [&>select]:dark:border-slate-700 [&>select]:rounded-lg [&>select]:text-xs [&>select]:font-semibold [&>select]:text-slate-900 [&>select]:dark:text-white [&>select]:focus:outline-none [&>select]:focus:border-emerald-500">
                    <?= $Helpers->combo_produk_kategori($row['barang_kategori_id']) ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- HPP Barang -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Harga Pokok (HPP) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                        <input type="number" name="hpp" value="<?= (float)$row['hpp_barang'] ?>" required class="w-full pl-9 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <!-- Harga Jual -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Harga Jual Katalog <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-emerald-500">Rp</span>
                        <input type="number" name="harga" value="<?= (float)$row['harga_barang'] ?>" required class="w-full pl-9 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-emerald-600 dark:text-emerald-400 focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <!-- Status Ketersediaan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Status Produk</label>
                    <select name="status" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500">
                        <?php $Helpers->selectOption($cek); ?>
                    </select>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="pt-4 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-3">
                <input type="hidden" value="postedit" name="action">
                <a href="?main=barang" class="px-4 py-2.5 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors no-underline">
                    Batal
                </a>
                <button type="submit" name="simpan" class="px-5 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-emerald-600/20 flex items-center gap-1.5 transition-all">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php endforeach; ?>
<?php $content = ob_get_clean(); ?>