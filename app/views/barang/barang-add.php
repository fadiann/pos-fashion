<?php 
ob_start(); 
$autoKode = $barangs->generateKodeBarangUnik('BRG');
?>

<div class="space-y-4 pb-12 max-w-4xl mx-auto">
    <!-- TOP HEADER -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 md:p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-base border border-emerald-200 dark:border-emerald-800">
                <i class="fa-solid fa-plus"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Tambah Produk</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Daftarkan katalog produk baru ke dalam sistem</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="?main=barang" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-1.5 no-underline transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali ke Daftar</span>
            </a>
        </div>
    </div>

    <!-- FORM TAMBAH PRODUK -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-5 md:p-6 border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <form action="" method="post" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- ID Barang (Auto Generated & Unique) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Kode Produk <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1">
                            <i class="fa-solid fa-wand-magic-sparkles text-[9px]"></i> Otomatis Unik
                        </span>
                    </div>
                    <div class="relative flex items-center">
                        <input type="text" id="inputBarangId" name="barangid" value="<?= htmlspecialchars($autoKode) ?>" required class="w-full pl-3 pr-10 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-mono font-bold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
                        <button type="button" onclick="regenerateKode()" class="absolute right-2 p-1.5 text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-md hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors" title="Acak Kode Unik Baru">
                            <i class="fa-solid fa-arrows-rotate text-xs"></i>
                        </button>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Kode terisi otomatis dan dijamin unik, atau bisa Anda ubah sesuai barcode fisik.</span>
                </div>

                <!-- Nama Produk -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nama Produk <span class="text-rose-500">*</span></label>
                    <input type="text" name="namabarang" placeholder="Misal: Kaos Polos / Sepatu / Kemeja" required class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
                </div>
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Kategori Produk <span class="text-rose-500">*</span></label>
                <div class="[&>select]:w-full [&>select]:px-3 [&>select]:py-2.5 [&>select]:bg-slate-50 [&>select]:dark:bg-slate-800 [&>select]:border [&>select]:border-slate-300 [&>select]:dark:border-slate-700 [&>select]:rounded-lg [&>select]:text-xs [&>select]:font-semibold [&>select]:text-slate-900 [&>select]:dark:text-white [&>select]:focus:outline-none [&>select]:focus:border-emerald-500">
                    <?= $Helpers->combo_produk_kategori() ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- HPP Barang -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Harga Pokok (HPP) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                        <input type="number" name="hpp" placeholder="0" required class="w-full pl-9 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <!-- Harga Jual -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Harga Jual Katalog <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-emerald-500">Rp</span>
                        <input type="number" name="harga" placeholder="0" required class="w-full pl-9 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-emerald-600 dark:text-emerald-400 focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <!-- Status Ketersediaan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Status Produk</label>
                    <select name="status" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500">
                        <option value="1">Tersedia (Aktif)</option>
                        <option value="0">Kosong (Nonaktif)</option>
                    </select>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="pt-4 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-3">
                <input type="hidden" value="postadd" name="action">
                <a href="?main=barang" class="px-4 py-2.5 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors no-underline">
                    Batal
                </a>
                <button type="submit" name="simpan" class="px-5 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-emerald-600/20 flex items-center gap-1.5 transition-all">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Produk</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function regenerateKode() {
    const d = new Date();
    const ym = String(d.getFullYear()).slice(-2) + String(d.getMonth() + 1).padStart(2, '0');
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    let rand = '';
    for(let i=0; i<4; i++) {
        rand += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    const input = document.getElementById('inputBarangId');
    if (input) {
        input.value = 'BRG-' + ym + '-' + rand;
        input.classList.add('ring-2', 'ring-emerald-500');
        setTimeout(() => input.classList.remove('ring-2', 'ring-emerald-500'), 400);
    }
}
</script>

<?php $content = ob_get_clean(); ?>