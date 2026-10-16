<?php ob_start(); ?>

<div class="space-y-4 pb-12 max-w-2xl mx-auto">
    <!-- TOP HEADER -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 md:p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-base border border-blue-200 dark:border-blue-800">
                <i class="fa-solid fa-arrow-down-to-bracket"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Restok Masuk Produk</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Catat penambahan persediaan stok produk dari supplier</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="?main=barang" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-1.5 no-underline transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- NOTIFIKASI SUKSES -->
    <?php if (isset($_GET['success'])): ?>
        <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 flex items-center justify-between text-emerald-800 dark:text-emerald-300 text-xs font-semibold animate-fade-in">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-base text-emerald-500"></i>
                <span>Stok masuk berhasil dicatat ke inventaris!</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-100">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- FORM STOK MASUK -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-5 md:p-6 border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <form action="" method="post" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- ID Produk (Readonly) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">ID Produk</label>
                    <input type="text" name="barangid" value="<?= htmlspecialchars($row['barang_id']) ?>" readonly class="w-full px-3 py-2.5 bg-slate-100 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-mono font-bold text-slate-500 dark:text-slate-400 cursor-not-allowed">
                </div>

                <!-- Nama Produk (Readonly) -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nama Produk</label>
                    <input type="text" name="namabarang" value="<?= htmlspecialchars($row['nama_barang']) ?>" readonly class="w-full px-3 py-2.5 bg-slate-100 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-700 dark:text-slate-300 cursor-not-allowed">
                </div>
            </div>

            <!-- Supplier -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Supplier / Pemasok <span class="text-rose-500">*</span></label>
                <div class="[&>select]:w-full [&>select]:px-3 [&>select]:py-2.5 [&>select]:bg-slate-50 [&>select]:dark:bg-slate-800 [&>select]:border [&>select]:border-slate-300 [&>select]:dark:border-slate-700 [&>select]:rounded-lg [&>select]:text-xs [&>select]:font-semibold [&>select]:text-slate-900 [&>select]:dark:text-white [&>select]:focus:outline-none [&>select]:focus:border-emerald-500">
                    <?= $Helpers->combo_suplayer() ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Ukuran / Size -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Ukuran / Size <span class="text-rose-500">*</span></label>
                    <div class="[&>select]:w-full [&>select]:px-3 [&>select]:py-2.5 [&>select]:bg-slate-50 [&>select]:dark:bg-slate-800 [&>select]:border [&>select]:border-slate-300 [&>select]:dark:border-slate-700 [&>select]:rounded-lg [&>select]:text-xs [&>select]:font-semibold [&>select]:text-slate-900 [&>select]:dark:text-white [&>select]:focus:outline-none [&>select]:focus:border-emerald-500">
                        <?= $Helpers->combo_size_bykategori($row['kategori_id']) ?>
                    </div>
                </div>

                <!-- Jumlah Masuk -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Jumlah Pasang (Pcs) <span class="text-rose-500">*</span></label>
                    <input type="number" min="1" name="jumlah" placeholder="0" required class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Keterangan / Catatan</label>
                <input type="text" name="keterangan" placeholder="Contoh: Pengiriman batch 1, Restok toko, dll" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500">
            </div>

            <!-- ACTION BUTTONS -->
            <div class="pt-4 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-3">
                <input type="hidden" value="postaddstokmasuk" name="action">
                <a href="?main=barang" class="px-4 py-2.5 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors no-underline">
                    Batal
                </a>
                <button type="submit" name="simpan" class="px-5 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-emerald-600/20 flex items-center gap-1.5 transition-all">
                    <i class="fa-solid fa-plus"></i>
                    <span>Simpan Stok Masuk</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>