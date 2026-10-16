<?php ob_start(); ?>

<div class="space-y-4 pb-12">
    <!-- TOP NOTIFICATION IF ANY -->
    <?php if (isset($_GET['success'])): ?>
        <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                <span>Data pelanggan berhasil diperbarui!</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- TOP HEADER -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 md:p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-base border border-blue-200 dark:border-blue-800">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Data Pelanggan</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Kelola informasi kontak dan profil pelanggan toko</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="?main=pelanggan&action=add" class="px-3.5 py-2 rounded-lg text-xs font-bold bg-blue-600 hover:bg-blue-700 active:scale-95 text-white shadow-sm flex items-center gap-1.5 no-underline transition-all">
                <i class="fa-solid fa-user-plus text-xs"></i>
                <span>Tambah Pelanggan</span>
            </a>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 md:p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3">
        <div class="relative flex-1 min-w-[240px]">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="searchPelanggan" placeholder="Cari nama pelanggan, nomor HP, atau alamat..." class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-blue-500">
        </div>
        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">
            Total: <span id="totalPelangganCount" class="font-bold text-blue-600 dark:text-blue-400"><?= count($viewlist) ?></span> Pelanggan
        </div>
    </div>

    <!-- TABLE PELANGGAN -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300" id="table-pelanggan">
                <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-500 dark:text-slate-400 uppercase font-bold text-[11px] border-b border-slate-200/80 dark:border-slate-800 tracking-wider">
                    <tr>
                        <th class="py-3 px-3 md:px-4 text-center w-16">ID</th>
                        <th class="py-3 px-3 md:px-4">Nama Pelanggan</th>
                        <th class="py-3 px-3 md:px-4">Nomor HP / WhatsApp</th>
                        <th class="py-3 px-3 md:px-4">Alamat</th>
                        <th class="py-3 px-3 md:px-4 text-center w-28">Status</th>
                        <th class="py-3 px-3 md:px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pelangganTableBody" class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php if (empty($viewlist)): ?>
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <i class="fa-solid fa-users-slash text-2xl mb-2 block"></i>
                                Belum ada data pelanggan yang terdaftar
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($viewlist as $row): 
                            $status = $row['pelanggan_status'];
                            $hp = trim($row['pelanggan_nomor']);
                            // Clean phone for whatsapp
                            $wa = preg_replace('/[^0-9]/', '', $hp);
                            if (str_starts_with($wa, '0')) {
                                $wa = '62' . substr($wa, 1);
                            }
                        ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors pelanggan-row" data-search="<?= strtolower(htmlspecialchars($row['pelanggan_id'] . ' ' . $row['pelanggan_nama'] . ' ' . $row['pelanggan_nomor'] . ' ' . $row['pelanggan_alamat'])) ?>">
                                <td class="py-3 px-3 md:px-4 text-center font-mono font-bold text-slate-500 dark:text-slate-400">
                                    #<?= htmlspecialchars($row['pelanggan_id']) ?>
                                </td>
                                <td class="py-3 px-3 md:px-4">
                                    <div class="font-bold text-slate-900 dark:text-white text-xs">
                                        <?= htmlspecialchars($row['pelanggan_nama']) ?>
                                    </div>
                                </td>
                                <td class="py-3 px-3 md:px-4 font-mono">
                                    <?php if (!empty($hp)): ?>
                                        <div class="flex items-center gap-1.5">
                                            <span><?= htmlspecialchars($hp) ?></span>
                                            <?php if (!empty($wa)): ?>
                                                <a href="https://wa.me/<?= $wa ?>" target="_blank" title="Kirim Pesan WhatsApp" class="inline-flex items-center justify-center w-5 h-5 rounded bg-emerald-100 text-emerald-700 hover:bg-emerald-200 dark:bg-emerald-950/80 dark:text-emerald-400 text-[10px] transition-colors">
                                                    <i class="fa-brands fa-whatsapp"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-slate-400 italic">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-3 md:px-4 text-slate-600 dark:text-slate-300">
                                    <?= !empty($row['pelanggan_alamat']) ? htmlspecialchars($row['pelanggan_alamat']) : '<span class="text-slate-400 italic">-</span>' ?>
                                </td>
                                <td class="py-3 px-3 md:px-4 text-center">
                                    <?php if ($status == '1'): ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Nonaktif
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-3 md:px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="?main=pelanggan&action=edit&id=<?= $row['pelanggan_id'] ?>" class="w-7 h-7 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900 border border-amber-200 dark:border-amber-800 flex items-center justify-center transition-colors text-xs" title="Edit Pelanggan">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pelanggan ini?');" class="inline-block m-0">
                                            <input type="hidden" value="postdelete" name="action">
                                            <input type="hidden" value="<?= $row['pelanggan_id'] ?>" name="pelanggan_id">
                                            <button type="submit" class="w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900 border border-rose-200 dark:border-rose-800 flex items-center justify-center transition-colors text-xs" title="Hapus Pelanggan">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION CONTAINER -->
    <div id="pelangganPaginationContainer"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    window.pelangganPaginator = new TablePaginator({
        tableId: 'table-pelanggan',
        rowSelector: '.pelanggan-row',
        containerId: 'pelangganPaginationContainer',
        filterInputId: 'searchPelanggan',
        defaultPerPage: 15,
        perPageOptions: [10, 15, 25, 50, 100, -1],
        itemLabel: 'pelanggan',
        onPageRendered: function(info) {
            const counter = document.getElementById('totalPelangganCount');
            if (counter) counter.innerText = info.totalFiltered;
        }
    });
});
</script>

<?php $content = ob_get_clean(); ?>