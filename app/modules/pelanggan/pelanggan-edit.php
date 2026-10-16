<?php ob_start(); ?>

<?php foreach ($viewlist as $row) : 
    $cek = $row['pelanggan_status'];
?>
<div class="space-y-4 pb-12 max-w-xl mx-auto">
    <!-- TOP HEADER -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 md:p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-base border border-blue-200 dark:border-blue-800">
                <i class="fa-solid fa-user-pen"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Ubah Data Pelanggan</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Perbarui kontak & alamat: <span class="font-bold text-blue-600 dark:text-blue-400"><?= htmlspecialchars($row['pelanggan_nama']) ?></span></p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="?main=pelanggan" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-1.5 no-underline transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- FORM EDIT PELANGGAN -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-5 md:p-6 border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <form action="" method="post" class="space-y-4">
            <!-- ID Pelanggan & Nama -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">ID Pelanggan</label>
                    <input type="text" name="pelanggan_id" value="<?= htmlspecialchars($row['pelanggan_id']) ?>" readonly class="w-full px-3 py-2.5 bg-slate-100 dark:bg-slate-800/60 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-mono font-bold text-slate-600 dark:text-slate-400 cursor-not-allowed">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="pelanggan_nama" value="<?= htmlspecialchars($row['pelanggan_nama']) ?>" placeholder="Contoh: Budi Santoso" autocomplete="off" required class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <!-- Nomor HP / WhatsApp -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nomor HP / WhatsApp <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <i class="fa-brands fa-whatsapp absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="pelanggan_nomor" value="<?= htmlspecialchars($row['pelanggan_nomor']) ?>" placeholder="Contoh: 081234567890" autocomplete="off" required class="w-full pl-9 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-mono font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <!-- Alamat Lengkap -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Alamat Tempat Tinggal / Kota</label>
                <textarea name="pelanggan_alamat" rows="2" placeholder="Contoh: Jl. Slamet Riyadi No. 45, Solo" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-blue-500"><?= htmlspecialchars($row['pelanggan_alamat']) ?></textarea>
            </div>

            <!-- Status Pelanggan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Status Keanggotaan</label>
                <select name="pelanggan_status" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-blue-500">
                    <option value="1" <?= $cek == '1' ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= $cek == '0' ? 'selected' : '' ?>>Tidak Aktif</option>
                </select>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="pt-4 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-3">
                <input type="hidden" value="postedit" name="action">
                <a href="?main=pelanggan" class="px-4 py-2.5 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors no-underline">
                    Batal
                </a>
                <button type="submit" name="simpan" class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-blue-600/20 flex items-center gap-1.5 transition-all">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<?php $content = ob_get_clean(); ?>