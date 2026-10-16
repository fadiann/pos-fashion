<?php ob_start(); ?>

<div class="space-y-4 pb-12">
    <!-- ALERT NOTIFICATION -->
    <?php if ($message): ?>
        <?php 
            $alertBg = 'bg-blue-50 dark:bg-blue-950/60 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200';
            $alertIcon = 'fa-circle-info text-blue-500';
            if ($messageType === 'success') {
                $alertBg = 'bg-emerald-50 dark:bg-emerald-950/60 border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200';
                $alertIcon = 'fa-circle-check text-emerald-500';
            } elseif ($messageType === 'error') {
                $alertBg = 'bg-rose-50 dark:bg-rose-950/60 border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200';
                $alertIcon = 'fa-triangle-exclamation text-rose-500';
            }
        ?>
        <div class="p-3.5 rounded-xl border <?= $alertBg ?> text-xs font-semibold flex items-center justify-between shadow-sm animate-in fade-in duration-150">
            <div class="flex items-center gap-2">
                <i class="fa-solid <?= $alertIcon ?> text-sm"></i>
                <span><?= htmlspecialchars($message) ?></span>
            </div>
            <button onclick="this.parentElement.remove()" class="opacity-70 hover:opacity-100">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- TOP HEADER -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 md:p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-cyan-50 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-bold text-base border border-cyan-200 dark:border-cyan-800">
                <i class="fa-solid fa-database"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Migrasi Skema Database</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Version control & pembaharuan struktur tabel otomatis seperti di Laravel</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Modal Button Buat Migrasi Baru -->
            <button type="button" onclick="openModalMakeMigration()" class="px-3 py-2 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 flex items-center gap-1.5 transition-colors">
                <i class="fa-solid fa-file-code text-xs"></i>
                <span>Buat File Migrasi</span>
            </button>

            <!-- Rollback Button -->
            <form action="?main=migrasi" method="post" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan (rollback) migrasi batch terakhir?');" class="inline m-0">
                <input type="hidden" name="action" value="postrollback">
                <button type="submit" class="px-3 py-2 rounded-lg text-xs font-semibold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/60 border border-amber-200 dark:border-amber-800 flex items-center gap-1.5 transition-colors">
                    <i class="fa-solid fa-rotate-left text-xs"></i>
                    <span>Rollback Terakhir</span>
                </button>
            </form>

            <!-- Migrate Now Button -->
            <form action="?main=migrasi" method="post" class="inline m-0">
                <input type="hidden" name="action" value="postmigrate">
                <button type="submit" class="px-3.5 py-2 rounded-lg text-xs font-bold bg-cyan-600 hover:bg-cyan-700 active:scale-95 text-white shadow-sm flex items-center gap-1.5 transition-all">
                    <i class="fa-solid fa-bolt-lightning text-xs"></i>
                    <span>Jalankan Migrasi</span>
                    <?php if ($pendingCount > 0): ?>
                        <span class="px-1.5 py-0.2 rounded-full bg-rose-500 text-white text-[10px] font-black"><?= $pendingCount ?></span>
                    <?php endif; ?>
                </button>
            </form>
        </div>
    </div>

    <!-- 3 METRIC CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <!-- Total Migrations -->
        <div class="bg-white dark:bg-slate-900 rounded-xl p-3.5 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Total File Migrasi</span>
                <span class="text-xl font-black text-slate-900 dark:text-white"><?= $totalMigrations ?></span>
            </div>
            <div class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center text-sm">
                <i class="fa-solid fa-folder-tree"></i>
            </div>
        </div>

        <!-- Ran (Sudah Diterapkan) -->
        <div class="bg-white dark:bg-slate-900 rounded-xl p-3.5 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block">Sudah Diterapkan</span>
                <span class="text-xl font-black text-emerald-600 dark:text-emerald-400"><?= $ranCount ?></span>
            </div>
            <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm border border-emerald-200 dark:border-emerald-800">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- Pending (Menunggu Eksekusi) -->
        <div class="bg-white dark:bg-slate-900 rounded-xl p-3.5 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider block">Menunggu Migrasi</span>
                <span class="text-xl font-black <?= $pendingCount > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-400' ?>"><?= $pendingCount ?></span>
            </div>
            <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm border border-amber-200 dark:border-amber-800">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>
    </div>

    <!-- TABLE MIGRATIONS -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-3.5 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-list-check text-cyan-600 dark:text-cyan-400 text-xs"></i>
                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 mb-0">Daftar File Migrasi (<code class="text-cyan-600 dark:text-cyan-400">database/migrations/</code>)</h3>
            </div>
            <span class="text-[11px] text-slate-400 font-medium">Batch tracker: <code class="text-slate-600 dark:text-slate-300 font-mono">tbl: migrations</code></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-500 dark:text-slate-400 uppercase font-bold text-[11px] border-b border-slate-200/80 dark:border-slate-800 tracking-wider">
                    <tr>
                        <th class="py-3 px-3 md:px-4 text-center w-14">No.</th>
                        <th class="py-3 px-3 md:px-4">Nama File Migrasi</th>
                        <th class="py-3 px-3 md:px-4 text-center w-36">Status</th>
                        <th class="py-3 px-3 md:px-4 text-center w-24">Batch</th>
                        <th class="py-3 px-3 md:px-4 text-center w-48">Waktu Diterapkan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php if (empty($statusList)): ?>
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <i class="fa-solid fa-database text-2xl mb-2 block"></i>
                                Belum ada file migrasi di direktori <code class="font-mono">database/migrations/</code>.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $no = 1;
                        foreach ($statusList as $row): 
                            $isRan = ($row['status'] === 'Ran');
                        ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="py-3 px-3 md:px-4 text-center font-mono font-bold text-slate-400">
                                    <?= $no++ ?>.
                                </td>
                                <td class="py-3 px-3 md:px-4">
                                    <div class="font-mono font-semibold text-xs <?= $isRan ? 'text-slate-900 dark:text-white' : 'text-amber-600 dark:text-amber-400' ?>">
                                        <i class="fa-solid fa-file-lines mr-1.5 opacity-60"></i><?= htmlspecialchars($row['file']) ?>
                                    </div>
                                </td>
                                <td class="py-3 px-3 md:px-4 text-center">
                                    <?php if ($isRan): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Diterapkan (Ran)
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Menunggu (Pending)
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-3 md:px-4 text-center font-mono font-bold">
                                    <?= $row['batch'] !== null ? '#' . $row['batch'] : '<span class="text-slate-400">-</span>' ?>
                                </td>
                                <td class="py-3 px-3 md:px-4 text-center font-mono text-[11px] text-slate-500 dark:text-slate-400">
                                    <?= $row['applied_at'] ? date('d M Y H:i:s', strtotime($row['applied_at'])) : '<span class="text-slate-400 italic">Belum dieksekusi</span>' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- CLI CHEATSHEET INFO BOX -->
    <div class="bg-slate-900 text-slate-200 rounded-xl p-4 md:p-5 border border-slate-800 shadow-sm space-y-3">
        <div class="flex items-center gap-2 text-xs font-bold text-amber-400">
            <i class="fa-solid fa-terminal"></i>
            <span>CLI Migration Commands (Mirip Laravel Artisan)</span>
        </div>
        <p class="text-xs text-slate-400 leading-relaxed mb-0">
            Selain melalui antarmuka web di atas, Anda juga dapat menjalankan seluruh perintah migrasi langsung melalui terminal / command line di direktori proyek:
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs font-mono">
            <div class="bg-slate-950/80 p-2.5 rounded-lg border border-slate-800">
                <span class="text-emerald-400">php artisan.php migrate</span>
                <p class="text-[11px] text-slate-400 font-sans mt-0.5 mb-0">Jalankan semua file migrasi yang berstatus pending</p>
            </div>
            <div class="bg-slate-950/80 p-2.5 rounded-lg border border-slate-800">
                <span class="text-amber-400">php artisan.php migrate:rollback</span>
                <p class="text-[11px] text-slate-400 font-sans mt-0.5 mb-0">Batalkan migrasi batch terakhir</p>
            </div>
            <div class="bg-slate-950/80 p-2.5 rounded-lg border border-slate-800">
                <span class="text-cyan-400">php artisan.php migrate:status</span>
                <p class="text-[11px] text-slate-400 font-sans mt-0.5 mb-0">Tampilkan tabel status seluruh migrasi</p>
            </div>
            <div class="bg-slate-950/80 p-2.5 rounded-lg border border-slate-800">
                <span class="text-purple-400">php artisan.php make:migration &lt;nama&gt;</span>
                <p class="text-[11px] text-slate-400 font-sans mt-0.5 mb-0">Generate template file migrasi baru</p>
            </div>
        </div>
    </div>
</div>

<!-- MODAL MAKE MIGRATION -->
<div id="modalMakeMigration" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-4 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-cyan-50 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-sm font-bold border border-cyan-200 dark:border-cyan-800">
                    <i class="fa-solid fa-file-circle-plus"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-0">Buat File Migrasi Baru</h3>
            </div>
            <button type="button" onclick="closeModalMakeMigration()" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="?main=migrasi" method="post" class="p-5 space-y-4">
            <input type="hidden" name="action" value="postmake">

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                    Nama Migrasi / Deskripsi Perubahan <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="migration_name" placeholder="Misal: tambah_kolom_diskon_barang" autocomplete="off" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-mono font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-cyan-500">
                <span class="text-[10px] text-slate-400 mt-1 block">Gunakan format snake_case tanpa spasi. File akan otomatis dibuat di <code class="font-mono">database/migrations/</code>.</span>
            </div>

            <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
                <button type="button" onclick="closeModalMakeMigration()" class="px-3.5 py-2 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-cyan-600 hover:bg-cyan-700 active:scale-95 text-white font-bold text-xs shadow-sm flex items-center gap-1.5 transition-all">
                    <i class="fa-solid fa-plus"></i>
                    <span>Buat File Migrasi</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModalMakeMigration() {
    const modal = document.getElementById('modalMakeMigration');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModalMakeMigration() {
    const modal = document.getElementById('modalMakeMigration');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

window.addEventListener('click', (e) => {
    const modal = document.getElementById('modalMakeMigration');
    if (e.target === modal) closeModalMakeMigration();
});
</script>

<?php $content = ob_get_clean(); ?>
