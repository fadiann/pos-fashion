<?php ob_start(); ?>

<div class="space-y-4 pb-12">
    <!-- TOP HEADER & CONTROLS -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 md:p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-base border border-emerald-200 dark:border-emerald-800">
                <i class="fa-solid fa-ruler-horizontal"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Data Ukuran & Size</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Kelola master variasi ukuran produk berdasarkan kategori</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="?main=size" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all bg-emerald-600 text-white shadow-sm flex items-center gap-1.5 no-underline">
                <i class="fa-solid fa-list"></i>
                <span>Daftar Ukuran</span>
                <span class="bg-emerald-800 text-emerald-100 text-[10px] px-1.5 py-0.2 rounded-full"><?= count($viewlist ?? []) ?></span>
            </a>
            <a href="?main=size&action=add" class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-1.5 no-underline">
                <i class="fa-solid fa-plus text-emerald-500"></i>
                <span>Tambah Ukuran</span>
            </a>
        </div>
    </div>

    <!-- NOTIFIKASI SUKSES -->
    <?php if (isset($_GET['success'])): ?>
        <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 flex items-center justify-between text-emerald-800 dark:text-emerald-300 text-xs font-semibold animate-fade-in">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-base text-emerald-500"></i>
                <?php if ($_GET['success'] === 'bulk_deleted'): ?>
                    <span>Berhasil menghapus <?= (int)($_GET['count'] ?? 0) ?> ukuran sekaligus!</span>
                <?php elseif ($_GET['success'] === 'deleted'): ?>
                    <span>Data ukuran berhasil dihapus!</span>
                <?php else: ?>
                    <span>Data ukuran berhasil diperbarui!</span>
                <?php endif; ?>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-100">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- FILTER / SEARCH BAR -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="relative w-full sm:w-80">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="filter-size-input" placeholder="Cari nama size atau kategori..." class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-colors">
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kategori:</span>
            <select id="filter-size-kategori" class="px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-200 focus:outline-none focus:border-emerald-500">
                <option value="">Semua Kategori</option>
                <?php
                $katUnik = [];
                foreach ($viewlist as $item) {
                    if (!empty($item['kategori_nama']) && !in_array($item['kategori_nama'], $katUnik)) {
                        $katUnik[] = $item['kategori_nama'];
                    }
                }
                foreach ($katUnik as $kat):
                ?>
                    <option value="<?= htmlspecialchars($kat) ?>"><?= htmlspecialchars($kat) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- TABEL DATA SIZE DENGAN BULK CHECKLIST FORM -->
    <form id="formBulkDeleteSize" action="index.php?main=size" method="post" onsubmit="return confirmBulkDelete(event)">
        <input type="hidden" name="action" value="postdelete_bulk">

        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300" id="table-size">
                    <thead class="bg-slate-50 dark:bg-slate-800/80 text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold border-b border-slate-200 dark:border-slate-700 select-none">
                        <tr>
                            <th class="px-3 py-3 text-center" style="width: 44px;">
                                <input type="checkbox" id="checkAllSize" class="w-4 h-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500 cursor-pointer">
                            </th>
                            <th class="px-4 py-3 text-center" style="width: 70px;">ID</th>
                            <th class="px-4 py-3">Kategori Produk</th>
                            <th class="px-4 py-3">Nama Ukuran / Size</th>
                            <th class="px-4 py-3 text-center" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php if (!empty($viewlist)): ?>
                            <?php foreach($viewlist as $row): ?>
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors size-row" data-kategori="<?= htmlspecialchars($row['kategori_nama'] ?? '') ?>" data-search="<?= strtolower(htmlspecialchars($row['size_nama'].' '.$row['kategori_nama'])) ?>">
                                <!-- Checkbox Item -->
                                <td class="px-3 py-3 text-center">
                                    <input type="checkbox" name="size_ids[]" value="<?= htmlspecialchars($row['size_id']) ?>" class="size-checkbox w-4 h-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500 cursor-pointer">
                                </td>

                                <!-- ID Size -->
                                <td class="px-4 py-3 text-center font-mono font-bold text-slate-400 text-[11px]">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                        <?= htmlspecialchars($row['size_id']) ?>
                                    </span>
                                </td>

                                <!-- Kategori -->
                                <td class="px-4 py-3 font-semibold text-slate-800 dark:text-slate-200">
                                    <?php if (!empty($row['kategori_nama'])): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs">
                                            <i class="fa-solid fa-tag text-[10px] text-emerald-500"></i>
                                            <?= htmlspecialchars($row['kategori_nama']) ?>
                                        </span>
                                    <?php else: ?>
                                        <a href="index.php?main=size&action=edit&id=<?= $row['size_id'] ?>" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 hover:underline" title="Klik untuk hubungkan ke kategori aktif">
                                            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                            <span>Pilih Kategori (ID: <?= htmlspecialchars($row['size_kategori_id'] ?? '-') ?>)</span>
                                        </a>
                                    <?php endif; ?>
                                </td>

                                <!-- Nama Size -->
                                <td class="px-4 py-3 font-extrabold text-slate-900 dark:text-white text-sm">
                                    <span class="px-3 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 inline-block font-mono">
                                        <?= htmlspecialchars($row['size_nama']) ?>
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="index.php?main=size&action=edit&id=<?= $row['size_id'] ?>" class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors" title="Edit Ukuran">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>
                                        <button type="button" onclick="singleDeleteSize('<?= $row['size_id'] ?>', '<?= htmlspecialchars($row['size_nama'], ENT_QUOTES) ?>')" class="w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 flex items-center justify-center transition-colors" title="Hapus Ukuran">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                                    <i class="fa-solid fa-ruler-horizontal text-3xl mb-2 text-slate-300 dark:text-slate-600"></i>
                                    <p class="text-xs">Belum ada data ukuran / size terdaftar.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FLOATING BULK ACTION BAR -->
        <div id="floatingSizeBulkBar" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-40 bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-2xl border border-slate-700 flex items-center gap-4 animate-in fade-in slide-in-from-bottom-3 duration-200">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                <span id="selectedSizeCountText" class="text-xs font-bold text-slate-100">0 Ukuran Terpilih</span>
            </div>
            <div class="h-4 w-px bg-slate-700"></div>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-md shadow-rose-600/30 active:scale-95 transition-all">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                    <span>Hapus Ukuran Terpilih</span>
                </button>
                <button type="button" onclick="clearSelectedSizes()" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </form>

    <!-- FORM HAPUS SATUAN TERSIMPAN -->
    <form id="formSingleDeleteSize" action="index.php?main=size" method="post" style="display: none;">
        <input type="hidden" name="action" value="postdelete">
        <input type="hidden" name="size_id" id="singleDeleteSizeId" value="">
    </form>

    <!-- PAGINATION CONTAINER -->
    <div id="sizePaginationContainer"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    window.sizePaginator = new TablePaginator({
        tableId: 'table-size',
        rowSelector: '.size-row',
        containerId: 'sizePaginationContainer',
        filterInputId: 'filter-size-input',
        categorySelectId: 'filter-size-kategori',
        defaultPerPage: 15,
        perPageOptions: [10, 15, 25, 50, 100, -1],
        itemLabel: 'ukuran'
    });

    const checkAll = document.getElementById('checkAllSize');
    const checkboxes = document.querySelectorAll('.size-checkbox');
    const bulkBar = document.getElementById('floatingSizeBulkBar');
    const countText = document.getElementById('selectedSizeCountText');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.size-checkbox:checked');
        if (checked.length > 0) {
            countText.innerText = checked.length + ' Ukuran Terpilih';
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
            // Hanya centang baris yang saat ini terlihat (misal hasil filter / pagination)
            const visibleCheckboxes = document.querySelectorAll('.size-row:not([style*="display: none"]) .size-checkbox');
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

function clearSelectedSizes() {
    document.querySelectorAll('.size-checkbox').forEach(cb => cb.checked = false);
    const checkAll = document.getElementById('checkAllSize');
    if (checkAll) checkAll.checked = false;
    const bulkBar = document.getElementById('floatingSizeBulkBar');
    if (bulkBar) {
        bulkBar.classList.add('hidden');
        bulkBar.classList.remove('flex');
    }
}

function confirmBulkDelete(e) {
    const checked = document.querySelectorAll('.size-checkbox:checked');
    if (checked.length === 0) {
        alert('Silakan pilih minimal 1 ukuran yang ingin dihapus.');
        e.preventDefault();
        return false;
    }
    const confirmed = confirm(`Apakah Anda yakin ingin menghapus ${checked.length} ukuran yang dipilih secara permanen?`);
    if (!confirmed) {
        e.preventDefault();
        return false;
    }
    return true;
}

function singleDeleteSize(sizeId, sizeName) {
    if (confirm(`Apakah Anda yakin ingin menghapus ukuran ${sizeName}?`)) {
        document.getElementById('singleDeleteSizeId').value = sizeId;
        document.getElementById('formSingleDeleteSize').submit();
    }
}
</script>

<?php $content = ob_get_clean(); ?>