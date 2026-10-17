<?php ob_start(); ?>

<div class="space-y-4 pb-12">
    <!-- TOP HEADER & CONTROLS -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 md:p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold text-base border border-purple-200 dark:border-purple-800">
                <i class="fa-solid fa-tags"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Kategori Produk</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Kelola pengelompokan jenis barang dan status aktifnya</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="?main=kategori" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all bg-emerald-600 text-white shadow-sm flex items-center gap-1.5 no-underline">
                <i class="fa-solid fa-list"></i>
                <span>Daftar Kategori</span>
                <span class="bg-emerald-800 text-emerald-100 text-[10px] px-1.5 py-0.2 rounded-full"><?= count($viewlist ?? []) ?></span>
            </a>
            <a href="?main=kategori&action=add" class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-1.5 no-underline">
                <i class="fa-solid fa-plus text-emerald-500"></i>
                <span>Tambah Kategori</span>
            </a>
        </div>
    </div>

    <!-- NOTIFIKASI SUKSES -->
    <?php if (isset($_GET['success'])): ?>
        <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 flex items-center justify-between text-emerald-800 dark:text-emerald-300 text-xs font-semibold animate-fade-in">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-base text-emerald-500"></i>
                <?php if ($_GET['success'] === 'bulk_deleted'): ?>
                    <span>Berhasil menghapus <?= (int)($_GET['count'] ?? 0) ?> kategori sekaligus!</span>
                <?php elseif ($_GET['success'] === 'deleted'): ?>
                    <span>Data kategori berhasil dihapus!</span>
                <?php else: ?>
                    <span>Data kategori berhasil diperbarui!</span>
                <?php endif; ?>
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
            <input type="text" id="filter-kategori-input" placeholder="Cari nama atau keterangan kategori..." class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-colors">
        </div>
        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 hidden sm:inline">
            Total: <strong><?= count($viewlist ?? []) ?></strong> kategori
        </span>
    </div>

    <!-- TABEL DATA KATEGORI DENGAN BULK CHECKLIST FORM -->
    <form id="formBulkDeleteKategori" action="index.php?main=kategori" method="post" onsubmit="return confirmBulkDeleteKategori(event)">
        <input type="hidden" name="action" value="postdelete_bulk">

        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300" id="table-kategori">
                    <thead class="bg-slate-50 dark:bg-slate-800/80 text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold border-b border-slate-200 dark:border-slate-700 select-none">
                        <tr>
                            <th class="px-3 py-3 text-center" style="width: 44px;">
                                <input type="checkbox" id="checkAllKategori" class="w-4 h-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500 cursor-pointer">
                            </th>
                            <th class="px-4 py-3 text-center" style="width: 70px;">ID</th>
                            <th class="px-4 py-3">Nama Kategori</th>
                            <th class="px-4 py-3">Keterangan / Deskripsi</th>
                            <th class="px-4 py-3 text-center" style="width: 120px;">Status</th>
                            <th class="px-4 py-3 text-center" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php if (!empty($viewlist)): ?>
                            <?php foreach($viewlist as $row): 
                                $statusAktif = ($row['kategori_status'] == "1");
                            ?>
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors kat-row" data-search="<?= strtolower(htmlspecialchars($row['kategori_nama'].' '.$row['kategori_keterangan'])) ?>">
                                <!-- Checkbox Item -->
                                <td class="px-3 py-3 text-center">
                                    <input type="checkbox" name="kategori_ids[]" value="<?= htmlspecialchars($row['kategori_id']) ?>" class="kat-checkbox w-4 h-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500 cursor-pointer">
                                </td>

                                <!-- ID -->
                                <td class="px-4 py-3 text-center font-mono font-bold text-slate-400 text-[11px]">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                        <?= htmlspecialchars($row['kategori_id']) ?>
                                    </span>
                                </td>

                                <!-- Nama Kategori -->
                                <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 h-7 rounded-lg bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs">
                                            <i class="fa-solid fa-tag"></i>
                                        </span>
                                        <span><?= htmlspecialchars($row['kategori_nama']) ?></span>
                                    </div>
                                </td>

                                <!-- Keterangan -->
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">
                                    <?= !empty($row['kategori_keterangan']) ? htmlspecialchars($row['kategori_keterangan']) : '<span class="text-slate-400 italic">-</span>' ?>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3 text-center">
                                    <?php if ($statusAktif): ?>
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Nonaktif
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="index.php?main=kategori&action=edit&id=<?= $row['kategori_id'] ?>" class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors" title="Edit Kategori">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>
                                        <button type="button" onclick="singleDeleteKategori('<?= $row['kategori_id'] ?>', '<?= htmlspecialchars($row['kategori_nama'], ENT_QUOTES) ?>')" class="w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 flex items-center justify-center transition-colors" title="Hapus Kategori">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                                    <i class="fa-solid fa-tags text-3xl mb-2 text-slate-300 dark:text-slate-600"></i>
                                    <p class="text-xs">Belum ada data kategori produk tersimpan.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FLOATING BULK ACTION BAR -->
        <div id="floatingKatBulkBar" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-40 bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-2xl border border-slate-700 flex items-center gap-4 animate-in fade-in slide-in-from-bottom-3 duration-200">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                <span id="selectedKatCountText" class="text-xs font-bold text-slate-100">0 Kategori Terpilih</span>
            </div>
            <div class="h-4 w-px bg-slate-700"></div>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-md shadow-rose-600/30 active:scale-95 transition-all">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                    <span>Hapus Kategori Terpilih</span>
                </button>
                <button type="button" onclick="clearSelectedKategori()" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </form>

    <!-- FORM HAPUS SATUAN TERSIMPAN -->
    <form id="formSingleDeleteKat" action="index.php?main=kategori" method="post" style="display: none;">
        <input type="hidden" name="action" value="postdelete">
        <input type="hidden" name="kategori_id" id="singleDeleteKatId" value="">
    </form>

    <!-- PAGINATION CONTAINER -->
    <div id="kategoriPaginationContainer"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    window.kategoriPaginator = new TablePaginator({
        tableId: 'table-kategori',
        rowSelector: '.kat-row',
        containerId: 'kategoriPaginationContainer',
        filterInputId: 'filter-kategori-input',
        defaultPerPage: 10,
        perPageOptions: [10, 25, 50, 100, -1],
        itemLabel: 'kategori'
    });

    const checkAll = document.getElementById('checkAllKategori');
    const checkboxes = document.querySelectorAll('.kat-checkbox');
    const bulkBar = document.getElementById('floatingKatBulkBar');
    const countText = document.getElementById('selectedKatCountText');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.kat-checkbox:checked');
        if (checked.length > 0) {
            countText.innerText = checked.length + ' Kategori Terpilih';
            bulkBar.classList.remove('hidden');
            bulkBar.classList.add('flex');
        } else {
            bulkBar.classList.add('hidden');
            bulkBar.classList.remove('flex');
            if (checkAll) checkAll.checked = false;
        }
    }

    if (checkAll) {
        checkAll.addEventListener('change', function() {
            const visibleCheckboxes = document.querySelectorAll('.kat-row:not([style*="display: none"]) .kat-checkbox');
            visibleCheckboxes.forEach(cb => {
                cb.checked = checkAll.checked;
            });
            updateBulkBar();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkBar);
    });
});

function clearSelectedKategori() {
    document.querySelectorAll('.kat-checkbox').forEach(cb => cb.checked = false);
    const checkAll = document.getElementById('checkAllKategori');
    if (checkAll) checkAll.checked = false;
    const bulkBar = document.getElementById('floatingKatBulkBar');
    if (bulkBar) {
        bulkBar.classList.add('hidden');
        bulkBar.classList.remove('flex');
    }
}

function confirmBulkDeleteKategori(e) {
    const checked = document.querySelectorAll('.kat-checkbox:checked');
    if (checked.length === 0) {
        alert('Silakan pilih minimal 1 kategori yang ingin dihapus.');
        e.preventDefault();
        return false;
    }
    const confirmed = confirm(`Apakah Anda yakin ingin menghapus ${checked.length} kategori yang dipilih secara permanen?`);
    if (!confirmed) {
        e.preventDefault();
        return false;
    }
    return true;
}

function singleDeleteKategori(katId, katName) {
    if (confirm(`Apakah Anda yakin ingin menghapus kategori ${katName}?`)) {
        document.getElementById('singleDeleteKatId').value = katId;
        document.getElementById('formSingleDeleteKat').submit();
    }
}
</script>

<?php $content = ob_get_clean(); ?>