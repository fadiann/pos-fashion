<?php ob_start(); ?>

<div class="space-y-4 pb-12">
    <!-- ALERT NOTIFIKASI -->
    <?php if ($message): ?>
        <div class="p-3.5 rounded-xl border bg-emerald-50 dark:bg-emerald-950/60 border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-semibold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
                <span><?= htmlspecialchars($message) ?></span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- TOP HEADER -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold text-lg border border-purple-200 dark:border-purple-800">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Pengaturan Hak Akses Role (RBAC)</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Atur checklist izin modul yang dapat dilihat dan diakses oleh masing-masing grup pengguna</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <!-- Navigasi Pengguna -->
            <a href="?main=pengguna" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center gap-1.5 border border-slate-200 dark:border-slate-700 transition-colors">
                <i class="fa-solid fa-users text-xs text-slate-400"></i>
                <span>Kelola Akun Pengguna</span>
            </a>

            <!-- Reset to Default Button -->
            <form action="?main=role_akses" method="post" onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan seluruh hak akses ke konfigurasi bawaan default?');" class="m-0">
                <input type="hidden" name="action" value="postreset">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 border border-slate-200 dark:border-slate-700 transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-rotate-left text-xs"></i>
                    <span>Reset Default</span>
                </button>
            </form>
        </div>
    </div>

    <!-- PETUNJUK RINGKAS -->
    <div class="p-3 bg-amber-50/70 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 rounded-xl text-amber-900 dark:text-amber-200 text-xs flex items-start gap-2.5">
        <i class="fa-solid fa-lightbulb text-amber-500 mt-0.5 text-sm"></i>
        <div class="leading-relaxed">
            <strong>Panduan Pengaturan Akses:</strong> Centang kotak untuk memberikan izin akses modul ke grup pengguna terkait. Administrator selalu memiliki izin penuh ke seluruh fitur sistem untuk menjaga kestabilan teknis aplikasi. Klik tombol <strong>"Simpan Pengaturan Hak Akses"</strong> di bagian bawah setelah selesai mengubah centang.
        </div>
    </div>

    <!-- FORM MATRIKS HAK AKSES -->
    <form action="?main=role_akses" method="post" id="formRolePermissions">
        <input type="hidden" name="action" value="postsave">

        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300 border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-500 dark:text-slate-400 uppercase font-bold text-[11px] border-b border-slate-200/80 dark:border-slate-800 tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 md:px-5 w-5/12">Modul / Menu Aplikasi</th>
                            
                            <!-- Kolom Kasir POS -->
                            <th class="py-3.5 px-4 text-center w-2/12">
                                <div class="flex flex-col items-center gap-1">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        <i class="fa-solid fa-cash-register text-[10px]"></i>
                                        Kasir POS
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[10px] lowercase font-normal">
                                        <button type="button" onclick="toggleAllCheckboxes('kasir', true)" class="text-emerald-600 hover:underline">semua</button>
                                        <span class="text-slate-300">/</span>
                                        <button type="button" onclick="toggleAllCheckboxes('kasir', false)" class="text-slate-400 hover:underline">kosongkan</button>
                                    </div>
                                </div>
                            </th>

                            <!-- Kolom Owner Toko -->
                            <th class="py-3.5 px-4 text-center w-2/12">
                                <div class="flex flex-col items-center gap-1">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                        <i class="fa-solid fa-crown text-[10px]"></i>
                                        Owner Toko
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[10px] lowercase font-normal">
                                        <button type="button" onclick="toggleAllCheckboxes('owner', true)" class="text-amber-600 hover:underline">semua</button>
                                        <span class="text-slate-300">/</span>
                                        <button type="button" onclick="toggleAllCheckboxes('owner', false)" class="text-slate-400 hover:underline">kosongkan</button>
                                    </div>
                                </div>
                            </th>

                            <!-- Kolom Administrator -->
                            <th class="py-3.5 px-4 text-center w-3/12">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800 dark:bg-purple-950/80 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                    <i class="fa-solid fa-shield-halved text-[10px]"></i>
                                    Administrator
                                </div>
                                <div class="text-[10px] text-slate-400 lowercase font-normal mt-0.5">hak akses penuh</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php 
                        $currentGroup = '';
                        foreach ($modules as $modKey => $modInfo): 
                            // Tampilkan Group Header jika berpindah grup modul
                            if ($currentGroup !== $modInfo['group']):
                                $currentGroup = $modInfo['group'];
                        ?>
                            <tr class="bg-slate-100/70 dark:bg-slate-800/60 font-bold">
                                <td colspan="4" class="py-2 px-4 md:px-5 text-[11px] font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                    <i class="fa-solid fa-layer-group mr-1.5 text-slate-400"></i><?= htmlspecialchars($currentGroup) ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php
                            $kasirChecked = isset($matrix['kasir'][$modKey]) && $matrix['kasir'][$modKey] == 1;
                            $ownerChecked = isset($matrix['owner'][$modKey]) && $matrix['owner'][$modKey] == 1;
                        ?>
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-850 transition-colors">
                            <!-- Info Modul -->
                            <td class="py-3.5 px-4 md:px-5">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center shrink-0 mt-0.5">
                                        <i class="<?= $modInfo['icon'] ?> text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white text-xs">
                                            <?= htmlspecialchars($modInfo['name']) ?>
                                        </div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400 leading-tight mt-0.5">
                                            <?= htmlspecialchars($modInfo['description']) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kasir Checklist -->
                            <td class="py-3.5 px-4 text-center align-middle">
                                <label class="inline-flex items-center justify-center gap-2 p-2 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-950/30 cursor-pointer transition-colors select-none">
                                    <input type="checkbox" name="perms[kasir][]" value="<?= $modKey ?>" <?= $kasirChecked ? 'checked' : '' ?> class="perm-kasir w-5 h-5 rounded border-2 border-slate-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0 transition-all cursor-pointer">
                                    <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 kasir-status-label">
                                        <?= $kasirChecked ? '<span class="text-emerald-600 dark:text-emerald-400 font-bold">Aktif</span>' : '<span class="text-slate-400">Tutup</span>' ?>
                                    </span>
                                </label>
                            </td>

                            <!-- Owner Checklist -->
                            <td class="py-3.5 px-4 text-center align-middle">
                                <label class="inline-flex items-center justify-center gap-2 p-2 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-950/30 cursor-pointer transition-colors select-none">
                                    <input type="checkbox" name="perms[owner][]" value="<?= $modKey ?>" <?= $ownerChecked ? 'checked' : '' ?> class="perm-owner w-5 h-5 rounded border-2 border-slate-300 dark:border-slate-600 text-amber-600 focus:ring-amber-500 focus:ring-offset-0 transition-all cursor-pointer">
                                    <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 owner-status-label">
                                        <?= $ownerChecked ? '<span class="text-amber-600 dark:text-amber-400 font-bold">Aktif</span>' : '<span class="text-slate-400">Tutup</span>' ?>
                                    </span>
                                </label>
                            </td>

                            <!-- Administrator (Locked Full Access) -->
                            <td class="py-3.5 px-4 text-center align-middle">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-purple-50 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/80 select-none">
                                    <i class="fa-solid fa-lock text-[10px]"></i>
                                    <span>Selalu Aktif</span>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- FOOTER ACTION BAR -->
            <div class="p-4 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-between flex-wrap gap-3">
                <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-purple-600"></i>
                    <span>Perubahan hak akses langsung berlaku begitu tombol simpan ditekan.</span>
                </div>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-purple-600 hover:bg-purple-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-purple-600/30 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-floppy-disk text-sm"></i>
                    <span>Simpan Pengaturan Hak Akses</span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Fungsi untuk Check All / Uncheck All per role
function toggleAllCheckboxes(role, checked) {
    const selector = role === 'kasir' ? '.perm-kasir' : '.perm-owner';
    const checkboxes = document.querySelectorAll(selector);
    checkboxes.forEach(cb => {
        cb.checked = checked;
        // Trigger event change agar teks label ikut ter-update
        cb.dispatchEvent(new Event('change'));
    });
}

// Live update teks label status Aktif / Tutup saat dicentang
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.perm-kasir').forEach(cb => {
        cb.addEventListener('change', function() {
            const label = this.closest('label').querySelector('.kasir-status-label');
            if (label) {
                label.innerHTML = this.checked 
                    ? '<span class="text-emerald-600 dark:text-emerald-400 font-bold">Aktif</span>' 
                    : '<span class="text-slate-400">Tutup</span>';
            }
        });
    });

    document.querySelectorAll('.perm-owner').forEach(cb => {
        cb.addEventListener('change', function() {
            const label = this.closest('label').querySelector('.owner-status-label');
            if (label) {
                label.innerHTML = this.checked 
                    ? '<span class="text-amber-600 dark:text-amber-400 font-bold">Aktif</span>' 
                    : '<span class="text-slate-400">Tutup</span>';
            }
        });
    });
});
</script>

<?php $content = ob_get_clean(); ?>
