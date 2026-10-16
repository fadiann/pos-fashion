<?php ob_start(); ?>

<?php foreach ($viewlist as $row) : 
    $cek = $row['kategori_status'];
?>
<div class="space-y-4 pb-12 max-w-xl mx-auto">
    <!-- TOP HEADER -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 md:p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold text-base border border-purple-200 dark:border-purple-800">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Ubah Kategori Produk</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Perbarui informasi kategori: <span class="font-bold text-purple-600 dark:text-purple-400"><?= htmlspecialchars($row['kategori_nama']) ?></span></p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="?main=kategori" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-1.5 no-underline transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- FORM EDIT KATEGORI -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-5 md:p-6 border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <form action="" method="post" class="space-y-4">
            <input type="hidden" name="kategori_id" value="<?= htmlspecialchars($row['kategori_id']) ?>">

            <!-- Nama Kategori -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nama Kategori <span class="text-rose-500">*</span></label>
                <input type="text" name="kategori_nama" value="<?= htmlspecialchars($row['kategori_nama']) ?>" placeholder="Misal: Pakaian, Celana, Aksesoris" autocomplete="off" required class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-purple-500">
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Keterangan / Deskripsi <span class="text-rose-500">*</span></label>
                <input type="text" name="kategori_keterangan" value="<?= htmlspecialchars($row['kategori_keterangan']) ?>" placeholder="Deskripsi kategori" required class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-purple-500">
            </div>

            <!-- Status -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Status Kategori</label>
                <select name="kategori_status" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-purple-500">
                    <option value="1" <?= $cek == '1' ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= $cek == '0' ? 'selected' : '' ?>>Tidak Aktif</option>
                </select>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="pt-4 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-3">
                <input type="hidden" value="postedit" name="action">
                <a href="?main=kategori" class="px-4 py-2.5 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors no-underline">
                    Batal
                </a>
                <button type="submit" name="simpan" class="px-5 py-2.5 rounded-lg bg-purple-600 hover:bg-purple-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-purple-600/20 flex items-center gap-1.5 transition-all">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<?php $content = ob_get_clean(); ?>