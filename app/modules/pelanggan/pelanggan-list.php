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

    <!-- TABLE PELANGGAN DENGAN BULK CHECKLIST FORM -->
    <form id="formBulkDeletePelanggan" action="index.php?main=pelanggan" method="post" onsubmit="return confirmBulkDeletePelanggan(event)">
        <input type="hidden" name="action" value="postdelete_bulk">

        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300" id="table-pelanggan">
                    <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-500 dark:text-slate-400 uppercase font-bold text-[11px] border-b border-slate-200/80 dark:border-slate-800 tracking-wider">
                        <tr>
                            <th class="py-3 px-3 text-center w-12">
                                <input type="checkbox" id="checkAllPelanggan" class="w-4 h-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500 cursor-pointer">
                            </th>
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
                                <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">
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
                                    <!-- Checkbox Item -->
                                    <td class="py-3 px-3 text-center">
                                        <input type="checkbox" name="pelanggan_ids[]" value="<?= htmlspecialchars($row['pelanggan_id']) ?>" class="pelanggan-checkbox w-4 h-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500 cursor-pointer">
                                    </td>

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
                                        <button type="button" onclick="singleDeletePelanggan('<?= $row['pelanggan_id'] ?>', '<?= htmlspecialchars($row['pelanggan_nama'], ENT_QUOTES) ?>')" class="w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900 border border-rose-200 dark:border-rose-800 flex items-center justify-center transition-colors text-xs" title="Hapus Pelanggan">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- FLOATING BULK ACTION BAR -->
    <div id="floatingPelangganBulkBar" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-40 bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-2xl border border-slate-700 flex items-center gap-4 animate-in fade-in slide-in-from-bottom-3 duration-200">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
            <span id="selectedPelangganCountText" class="text-xs font-bold text-slate-100">0 Pelanggan Terpilih</span>
        </div>
        <div class="h-4 w-px bg-slate-700"></div>
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-md shadow-rose-600/30 active:scale-95 transition-all">
                <i class="fa-solid fa-trash-can text-xs"></i>
                <span>Hapus Pelanggan Terpilih</span>
            </button>
            <button type="button" onclick="clearSelectedPelanggan()" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-colors">
                Batal
            </button>
        </div>
    </div>
    </form>

    <!-- FORM HAPUS SATUAN TERSIMPAN -->
    <form id="formSingleDeletePelanggan" action="index.php?main=pelanggan" method="post" style="display: none;">
        <input type="hidden" name="action" value="postdelete">
        <input type="hidden" name="pelanggan_id" id="singleDeletePelangganId" value="">
    </form>

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

    const checkAll = document.getElementById('checkAllPelanggan');
    const checkboxes = document.querySelectorAll('.pelanggan-checkbox');
    const bulkBar = document.getElementById('floatingPelangganBulkBar');
    const countText = document.getElementById('selectedPelangganCountText');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.pelanggan-checkbox:checked');
        if (checked.length > 0) {
            countText.innerText = checked.length + ' Pelanggan Terpilih';
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
            const visibleCheckboxes = document.querySelectorAll('.pelanggan-row:not([style*="display: none"]) .pelanggan-checkbox');
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

function clearSelectedPelanggan() {
    document.querySelectorAll('.pelanggan-checkbox').forEach(cb => cb.checked = false);
    const checkAll = document.getElementById('checkAllPelanggan');
    if (checkAll) checkAll.checked = false;
    const bulkBar = document.getElementById('floatingPelangganBulkBar');
    if (bulkBar) {
        bulkBar.classList.add('hidden');
        bulkBar.classList.remove('flex');
    }
}

function confirmBulkDeletePelanggan(e) {
    const checked = document.querySelectorAll('.pelanggan-checkbox:checked');
    if (checked.length === 0) {
        alert('Silakan pilih minimal 1 pelanggan yang ingin dihapus.');
        e.preventDefault();
        return false;
    }
    const confirmed = confirm(`Apakah Anda yakin ingin menghapus ${checked.length} pelanggan yang dipilih secara permanen?`);
    if (!confirmed) {
        e.preventDefault();
        return false;
    }
    return true;
}

function singleDeletePelanggan(pelangganId, pelangganName) {
    if (confirm(`Apakah Anda yakin ingin menghapus pelanggan ${pelangganName}?`)) {
        document.getElementById('singleDeletePelangganId').value = pelangganId;
        document.getElementById('formSingleDeletePelanggan').submit();
    }
}
</script>

<?php $content = ob_get_clean(); ?>