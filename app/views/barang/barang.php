<?php 
ob_start(); 
$allSuppliers = $barangs->getAllSuppliers();
$allSizesGrouped = $barangs->getAllSizesGrouped();

// Siapkan data ringkas produk untuk JavaScript selector
$productsJsonList = [];
foreach ($viewlist ?? [] as $b) {
    $productsJsonList[] = [
        'id'          => $b['barang_id'],
        'nama'        => $b['nama_barang'],
        'kategori_id' => $b['barang_kategori_id']
    ];
}
?>

<script>
window.ALL_SIZES_BY_KAT = <?= json_encode($allSizesGrouped, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
window.ALL_PRODUCTS_DATA = <?= json_encode($productsJsonList, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
window.ALL_SUPPLIERS_DATA = <?= json_encode($allSuppliers, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
</script>

<div class="space-y-4 pb-16">
    <!-- TOP HEADER & CONTROLS -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 md:p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-base border border-emerald-200 dark:border-emerald-800">
                <i class="fa-solid fa-box-archive"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Master Data Produk</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Kelola katalog barang, harga jual, HPP (edit inline), dan restok masal persediaan</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <!-- Tombol Restok Masal -->
            <button type="button" onclick="openBulkRestokModal()" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all bg-teal-600 hover:bg-teal-700 text-white shadow-sm shadow-teal-600/30 flex items-center gap-1.5 active:scale-95">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>Restok Masal</span>
            </button>
            <a href="?main=barang" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all bg-emerald-600 text-white shadow-sm flex items-center gap-1.5 no-underline">
                <i class="fa-solid fa-list"></i>
                <span>Daftar Produk</span>
                <span class="bg-emerald-800 text-emerald-100 text-[10px] px-1.5 py-0.2 rounded-full"><?= count($viewlist ?? []) ?></span>
            </a>
            <a href="?main=barang&action=add" class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-1.5 no-underline">
                <i class="fa-solid fa-plus text-emerald-500"></i>
                <span>Tambah Produk</span>
            </a>
        </div>
    </div>

    <!-- NOTIFIKASI SUKSES -->
    <?php if (isset($_GET['success'])): ?>
        <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 flex items-center justify-between text-emerald-800 dark:text-emerald-300 text-xs font-semibold animate-fade-in">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-base text-emerald-500"></i>
                <span>Operasi berhasil disimpan ke sistem!</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-100">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- PETUNJUK FITUR CEPAT -->
    <div class="px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-800 text-slate-500 dark:text-slate-400 text-xs flex items-center justify-between flex-wrap gap-2">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-bolt text-amber-500"></i>
            <span><strong>Tips Cepat:</strong> Centang beberapa produk pada kotak di kiri tabel untuk <strong>Restok Sekaligus</strong>, atau klik langsung nominal harga di tabel untuk <strong>Edit Harga / HPP</strong> seketika.</span>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="relative w-full sm:w-80">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="filter-barang-input" placeholder="Cari kode ID atau nama produk..." class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-colors">
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider shrink-0">Kategori:</span>
            <select id="filter-kategori-select" class="px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-200 focus:outline-none focus:border-emerald-500">
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

    <!-- TABEL DATA PRODUK -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300" id="table-barang">
                <thead class="bg-slate-50 dark:bg-slate-800/80 text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold border-b border-slate-200 dark:border-slate-700 select-none">
                    <tr>
                        <!-- Checkbox Select All -->
                        <th class="px-3 py-3 text-center" style="width: 40px;">
                            <input type="checkbox" id="checkAllProduk" onclick="toggleSelectAllProduk(this)" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 cursor-pointer" title="Pilih Semua Produk">
                        </th>
                        <th class="px-3 py-3 text-center" style="width: 75px;">ID</th>
                        <th class="px-4 py-3">Nama Produk</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3 min-w-[200px]">Stok per Ukuran</th>
                        <th class="px-4 py-3 text-right" style="width: 150px;">
                            <span title="Klik angka pada baris untuk edit harga modal">Harga HPP <i class="fa-solid fa-pen text-[9px] text-slate-400 ml-0.5"></i></span>
                        </th>
                        <th class="px-4 py-3 text-right" style="width: 150px;">
                            <span title="Klik angka pada baris untuk edit harga jual">Harga Jual <i class="fa-solid fa-pen text-[9px] text-slate-400 ml-0.5"></i></span>
                        </th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php if (!empty($viewlist)): ?>
                        <?php foreach($viewlist as $row): 
                            $statusTersedia = ($row['status'] == "1");
                        ?>
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors barang-row" data-name="<?= strtolower(htmlspecialchars($row['nama_barang'])) ?>" data-id="<?= strtolower(htmlspecialchars($row['barang_id'])) ?>" data-kategori="<?= htmlspecialchars($row['kategori_nama'] ?? '') ?>">
                            <!-- Checkbox Item -->
                            <td class="px-3 py-3 text-center">
                                <input type="checkbox" 
                                       class="check-item-produk w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 cursor-pointer" 
                                       value="<?= htmlspecialchars($row['barang_id']) ?>" 
                                       data-id="<?= htmlspecialchars($row['barang_id']) ?>" 
                                       data-name="<?= htmlspecialchars($row['nama_barang']) ?>" 
                                       data-kategori="<?= htmlspecialchars($row['barang_kategori_id'] ?? '1') ?>"
                                       onchange="onProductCheckChange()">
                            </td>

                            <!-- ID Barang -->
                            <td class="px-3 py-3 text-center font-mono font-bold text-slate-500 dark:text-slate-400 text-[11px]">
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                    <?= htmlspecialchars($row['barang_id']) ?>
                                </span>
                            </td>

                            <!-- Nama Produk -->
                            <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center text-xs shrink-0">
                                        <i class="fa-solid fa-box"></i>
                                    </span>
                                    <span><?= htmlspecialchars($row['nama_barang']) ?></span>
                                </div>
                            </td>

                            <!-- Kategori -->
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md">
                                    <i class="fa-solid fa-tag text-[10px] text-emerald-500"></i>
                                    <?= htmlspecialchars($row['kategori_nama'] ?? '-') ?>
                                </span>
                            </td>

                            <!-- Stok per Size -->
                            <td class="px-4 py-3" id="stok-col-<?= $row['barang_id'] ?>">
                                <?php
                                $stoks = $barangs->getStokByProdukId($row['barang_id']);
                                $hasStok = false;
                                if (!empty($stoks) && count($stoks) > 0):
                                ?>
                                    <div class="flex flex-wrap gap-1.5 items-center">
                                        <?php
                                        foreach($stoks as $stok):
                                            if(isset($stok['total_per_size'])):
                                                $total_per_size      = $stok['total_per_size'];
                                                $total_sold_per_size = $barangs->getSoldByProdukSize($stok['barang_id'], $stok['stok_masuk_size_id']);
                                                $total_out_per_size  = $barangs->getOutStokByProdukSize($stok['barang_id'], $stok['stok_masuk_size_id']);
                                                $total_stok          = $total_per_size - $total_sold_per_size - $total_out_per_size;
                                                if($total_stok > 0):
                                                    $hasStok = true;
                                        ?>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/80 text-[11px] font-semibold text-emerald-700 dark:text-emerald-300" title="Size <?= $stok['size_nama'] ?>: <?= $total_stok ?> pcs">
                                                <span class="text-slate-500 dark:text-slate-400">Sz.<?= $stok['size_nama'] ?>:</span>
                                                <strong class="font-extrabold text-emerald-600 dark:text-emerald-400"><?= $total_stok ?></strong>
                                            </span>
                                        <?php 
                                                endif;
                                            endif;
                                        endforeach; 
                                        ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!$hasStok): ?>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-rose-500 bg-rose-50 dark:bg-rose-950/50 px-2 py-0.5 rounded-md border border-rose-200 dark:border-rose-900/40">
                                        <i class="fa-solid fa-circle-exclamation text-[10px]"></i> Habis / Kosong
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- HPP (INLINE EDIT) -->
                            <td class="px-4 py-3 text-right">
                                <div class="inline-price-box group relative inline-flex items-center justify-end gap-1.5 cursor-pointer p-1 rounded hover:bg-slate-100 dark:hover:bg-slate-800 transition-all" 
                                     data-id="<?= $row['barang_id'] ?>" 
                                     data-field="hpp" 
                                     data-raw="<?= (float)$row['hpp_barang'] ?>"
                                     title="Klik untuk ubah HPP langsung">
                                    <span class="price-display font-medium text-slate-500 dark:text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                        <?= $Helpers->rp($row['hpp_barang']) ?>
                                    </span>
                                    <i class="fa-solid fa-pen-to-square text-[10px] text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </div>
                            </td>

                            <!-- HARGA JUAL (INLINE EDIT) -->
                            <td class="px-4 py-3 text-right">
                                <div class="inline-price-box group relative inline-flex items-center justify-end gap-1.5 cursor-pointer p-1 rounded hover:bg-slate-100 dark:hover:bg-slate-800 transition-all" 
                                     data-id="<?= $row['barang_id'] ?>" 
                                     data-field="harga" 
                                     data-raw="<?= (float)$row['harga_barang'] ?>"
                                     title="Klik untuk ubah Harga Jual langsung">
                                    <span class="price-display font-extrabold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                        <?= $Helpers->rp($row['harga_barang']) ?>
                                    </span>
                                    <i class="fa-solid fa-pen-to-square text-[10px] text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3 text-center">
                                <?php if ($statusTersedia): ?>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Tersedia
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Nonaktif
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Aksi Menu -->
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- TOMBOL RESTOK CEPAT SATUAN -->
                                    <button type="button" 
                                            onclick="openQuickRestok('<?= $row['barang_id'] ?>', '<?= addslashes(htmlspecialchars($row['nama_barang'])) ?>', '<?= $row['barang_kategori_id'] ?>')"
                                            class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1.5 shadow-sm shadow-emerald-600/30 active:scale-95 transition-all" 
                                            title="Restok Produk Cepat">
                                        <i class="fa-solid fa-boxes-packing text-xs"></i>
                                        <span>Restok</span>
                                    </button>

                                    <!-- Stok Keluar -->
                                    <a href="index.php?main=barang&action=keluar&id=<?= $row['barang_id'] ?>" class="w-7 h-7 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/60 flex items-center justify-center transition-colors" title="Catat Stok Keluar / Reject">
                                        <i class="fa-solid fa-arrow-up-from-bracket text-xs"></i>
                                    </a>

                                    <!-- Edit Produk Lengkap -->
                                    <a href="index.php?main=barang&action=edit&id=<?= $row['barang_id'] ?>" class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors" title="Edit Detail Produk">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>

                                    <!-- Hapus Produk -->
                                    <form action="" method="post" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk <?= htmlspecialchars($row['nama_barang']) ?>?')">
                                        <input type="hidden" value="postdelete" name="action">
                                        <input type="hidden" value="<?= $row['barang_id'] ?>" name="barangid">
                                        <button type="submit" class="w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 flex items-center justify-center transition-colors" title="Hapus Produk">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-box-open text-3xl mb-2 text-slate-300 dark:text-slate-600"></i>
                                <p class="text-xs">Belum ada data produk tersimpan.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION CONTAINER -->
    <div id="barangPaginationContainer"></div>
</div>

<!-- ============================================== -->
<!-- FLOATING ACTION BAR: SAAT PRODUK DICENTANG     -->
<!-- ============================================== -->
<div id="floatingBulkAction" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-40 bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-2xl border border-slate-700 flex items-center gap-4 animate-in fade-in slide-in-from-bottom-3 duration-200">
    <div class="flex items-center gap-2">
        <span class="w-2.5 h-2.5 rounded-full bg-teal-400 animate-pulse"></span>
        <span id="selectedCountText" class="text-xs font-bold text-slate-100">0 Produk Terpilih</span>
    </div>
    <div class="h-4 w-px bg-slate-700"></div>
    <div class="flex items-center gap-2">
        <button type="button" onclick="openBulkRestokWithSelected()" class="px-4 py-1.5 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-md shadow-teal-600/30 active:scale-95 transition-all">
            <i class="fa-solid fa-boxes-stacked text-xs"></i>
            <span>Restok Produk Terpilih</span>
        </button>
        <button type="button" onclick="clearSelectedProduk()" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-colors">
            Batal
        </button>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL 1: RESTOK CEPAT SATUAN (QUICK RESTOCK)   -->
<!-- ============================================== -->
<div id="quickRestokModal" class="fixed inset-0 z-50 hidden bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <!-- Header Modal -->
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-850">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-boxes-packing"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white leading-tight">Restok Masuk Cepat</h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-0">Tambah persediaan stok tanpa pindah halaman</p>
                </div>
            </div>
            <button type="button" onclick="closeQuickRestokModal()" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Form Modal -->
        <form id="formQuickRestok" onsubmit="submitQuickRestok(event)" class="p-5 space-y-4">
            <input type="hidden" id="qrBarangId" name="barangid" value="">

            <!-- Info Produk -->
            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-between">
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Produk</span>
                    <h4 id="qrNamaBarang" class="text-xs font-bold text-slate-900 dark:text-white leading-tight mt-0.5">-</h4>
                </div>
                <span id="qrBadgeId" class="text-[11px] font-mono font-bold text-emerald-600 dark:text-emerald-400 bg-white dark:bg-slate-900 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-700">-</span>
            </div>

            <!-- Pilih Ukuran / Size -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                    Pilih Ukuran / Size <span class="text-rose-500">*</span>
                </label>
                <select id="qrSelectSize" name="size" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500">
                    <option value="">-- Memuat Ukuran... --</option>
                </select>
            </div>

            <!-- Pilih Supplier -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                    Supplier / Vendor Pemasok
                </label>
                <select id="qrSelectSupplier" name="suplayer" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500">
                    <?php if (!empty($allSuppliers)): ?>
                        <?php foreach ($allSuppliers as $sup): ?>
                            <option value="<?= $sup['suplayer_id'] ?>"><?= htmlspecialchars($sup['suplayer_nama']) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="1">Supplier Umum</option>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Input Jumlah Pcs & Tombol Cepat -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                    Jumlah Masuk (Pcs) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="number" id="qrJumlah" name="jumlah" min="1" value="10" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-sm font-bold text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Pcs</span>
                </div>
                <!-- Shortcut Tombol Tambah -->
                <div class="flex items-center gap-1.5 mt-2">
                    <span class="text-[10px] text-slate-400 font-bold uppercase">Tambah:</span>
                    <button type="button" onclick="addJumlah(1)" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300">+1</button>
                    <button type="button" onclick="addJumlah(5)" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300">+5</button>
                    <button type="button" onclick="addJumlah(10)" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300">+10</button>
                    <button type="button" onclick="addJumlah(25)" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300">+25</button>
                    <button type="button" onclick="addJumlah(50)" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300">+50</button>
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                    Keterangan
                </label>
                <input type="text" id="qrKeterangan" name="keterangan" value="Restok Masuk" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500">
            </div>

            <!-- Footer Buttons -->
            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
                <button type="button" onclick="closeQuickRestokModal()" class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    Batal
                </button>
                <button type="submit" id="btnSubmitRestok" class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-emerald-600/30 flex items-center gap-1.5 transition-all">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Simpan Restok</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL 2: RESTOK MASAL (BANYAK BARANG SEKALIGUS)-->
<!-- ============================================== -->
<div id="bulkRestokModal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-3 md:p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        
        <!-- Header Modal Bulk -->
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/70 dark:bg-slate-850 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-teal-100 dark:bg-teal-950/80 text-teal-600 dark:text-teal-400 flex items-center justify-center font-bold text-base">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div>
                    <h3 class="text-sm md:text-base font-bold text-slate-900 dark:text-white leading-tight">Restok Masal Produk</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Tambah stok untuk beberapa barang sekaligus dalam satu kali simpan</p>
                </div>
            </div>
            <button type="button" onclick="closeBulkRestokModal()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Opsi Pengaturan Cepat Global -->
        <div class="p-4 bg-slate-50/50 dark:bg-slate-850/50 border-b border-slate-100 dark:border-slate-800 grid grid-cols-1 md:grid-cols-3 gap-3 shrink-0">
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Supplier Default</label>
                <select id="bulkGlobalSupplier" onchange="applyGlobalSupplierToRows()" class="w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-teal-500">
                    <?php if (!empty($allSuppliers)): ?>
                        <?php foreach ($allSuppliers as $sup): ?>
                            <option value="<?= $sup['suplayer_id'] ?>"><?= htmlspecialchars($sup['suplayer_nama']) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="1">Supplier Umum</option>
                    <?php endif; ?>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Set Jumlah Masuk Semua Baris</label>
                <div class="flex items-center gap-1.5">
                    <input type="number" id="bulkGlobalQty" value="10" min="1" class="w-24 px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-900 dark:text-white focus:outline-none focus:border-teal-500">
                    <button type="button" onclick="applyGlobalQtyToRows()" class="px-2.5 py-1.5 rounded-lg bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-200 text-xs font-bold transition-colors">
                        Terapkan
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Keterangan Umum</label>
                <input type="text" id="bulkGlobalNote" value="Restok Masal" placeholder="Contoh: Pengiriman batch baru" class="w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-900 dark:text-white focus:outline-none focus:border-teal-500">
            </div>
        </div>

        <!-- Tabel Daftar Produk yang Akan Direstok -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3" style="max-height: 50vh;">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300 border-collapse">
                <thead class="bg-slate-100/80 dark:bg-slate-800 text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700 sticky top-0 z-10">
                    <tr>
                        <th class="py-2.5 px-3 w-8 text-center">No</th>
                        <th class="py-2.5 px-3">Produk</th>
                        <th class="py-2.5 px-3 w-36">Ukuran / Size <span class="text-rose-500">*</span></th>
                        <th class="py-2.5 px-3 w-40">Supplier</th>
                        <th class="py-2.5 px-3 w-28 text-right">Jumlah (Pcs) <span class="text-rose-500">*</span></th>
                        <th class="py-2.5 px-3 w-12 text-center">Hapus</th>
                    </tr>
                </thead>
                <tbody id="bulkRestokTableBody" class="divide-y divide-slate-100 dark:divide-slate-800">
                    <!-- Baris di-generate via JavaScript -->
                </tbody>
            </table>

            <!-- Tombol Tambah Baris Produk Baru -->
            <div class="pt-2">
                <button type="button" onclick="addEmptyRowToBulk()" class="px-3.5 py-1.5 rounded-lg border border-dashed border-teal-500/80 text-teal-600 dark:text-teal-400 hover:bg-teal-50 dark:hover:bg-teal-950/40 text-xs font-bold flex items-center gap-1.5 transition-colors">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Baris Produk Lain</span>
                </button>
            </div>
        </div>

        <!-- Footer Modal Bulk -->
        <div class="px-5 py-3.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-850 flex items-center justify-between flex-wrap gap-3 shrink-0">
            <div class="flex items-center gap-3 text-xs">
                <span class="text-slate-500 dark:text-slate-400">Total Item: <strong id="bulkTotalItemCount" class="text-slate-900 dark:text-white font-extrabold">0</strong></span>
                <span class="text-slate-300">|</span>
                <span class="text-slate-500 dark:text-slate-400">Total Pcs: <strong id="bulkTotalPcsCount" class="text-teal-600 dark:text-teal-400 font-extrabold">0 Pcs</strong></span>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" onclick="closeBulkRestokModal()" class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    Batal
                </button>
                <button type="button" id="btnSubmitBulkRestok" onclick="submitBulkRestok()" class="px-5 py-2 rounded-lg bg-teal-600 hover:bg-teal-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-teal-600/30 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan Semua Restok</span>
                </button>
            </div>
        </div>

    </div>
</div>

<!-- FLOATING TOAST NOTIFICATION -->
<div id="quickToast" class="fixed bottom-6 right-6 z-50 hidden px-4 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold shadow-xl items-center gap-2 border border-slate-700 animate-in fade-in slide-in-from-bottom-2 duration-150">
    <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
    <span id="toastMsg">Berhasil!</span>
</div>

<script>
// ==========================================
// 1. INLINE PRICE & HPP EDIT LOGIC
// ==========================================
document.addEventListener("DOMContentLoaded", function() {
    // Inisialisasi Paginator Master Produk
    window.barangPaginator = new TablePaginator({
        tableId: 'table-barang',
        rowSelector: '.barang-row',
        containerId: 'barangPaginationContainer',
        filterInputId: 'filter-barang-input',
        categorySelectId: 'filter-kategori-select',
        defaultPerPage: 25,
        perPageOptions: [10, 25, 50, 100, -1],
        itemLabel: 'produk',
        onPageRendered: function() {
            // Uncheck header select-all saat pindah halaman
            const mainCb = document.getElementById('checkAllProduk');
            if (mainCb) mainCb.checked = false;
        }
    });

    // Event Listener Klik Inline Price Box
    document.querySelectorAll('.inline-price-box').forEach(box => {
        box.addEventListener('click', function(e) {
            if (this.querySelector('input')) return;

            const currentVal = parseFloat(this.getAttribute('data-raw')) || 0;
            const barangId = this.getAttribute('data-id');
            const field = this.getAttribute('data-field');
            const displaySpan = this.querySelector('.price-display');
            const oldDisplayHtml = displaySpan.innerHTML;

            const input = document.createElement('input');
            input.type = 'number';
            input.value = currentVal;
            input.step = '1000';
            input.min = '0';
            input.className = 'w-24 px-1.5 py-0.5 text-xs font-bold text-right bg-white dark:bg-slate-800 text-slate-900 dark:text-white border-2 border-emerald-500 rounded focus:outline-none shadow-sm';

            displaySpan.style.display = 'none';
            this.querySelector('.fa-pen-to-square')?.classList.add('hidden');
            this.appendChild(input);
            input.focus();
            input.select();

            let saved = false;

            const saveChange = () => {
                if (saved) return;
                saved = true;

                const newVal = parseFloat(input.value) || 0;
                input.remove();
                displaySpan.style.display = '';
                this.querySelector('.fa-pen-to-square')?.classList.remove('hidden');

                if (newVal === currentVal) {
                    return;
                }

                displaySpan.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-emerald-500"></i>';

                const formData = new FormData();
                formData.append('action', 'ajax_inline_price');
                formData.append('barangid', barangId);
                formData.append('field', field);
                formData.append('value', newVal);

                fetch('index.php?main=barang', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        displaySpan.innerText = res.formatted;
                        box.setAttribute('data-raw', res.raw);
                        
                        box.classList.add('bg-emerald-100', 'dark:bg-emerald-950/80');
                        setTimeout(() => {
                            box.classList.remove('bg-emerald-100', 'dark:bg-emerald-950/80');
                        }, 800);

                        showToast(field.toUpperCase() + ' berhasil diubah menjadi ' + res.formatted);
                    } else {
                        displaySpan.innerHTML = oldDisplayHtml;
                        alert(res.message || 'Gagal mengubah harga.');
                    }
                })
                .catch(() => {
                    displaySpan.innerHTML = oldDisplayHtml;
                    alert('Terjadi kesalahan koneksi.');
                });
            };

            input.addEventListener('keydown', function(evt) {
                if (evt.key === 'Enter') {
                    evt.preventDefault();
                    saveChange();
                } else if (evt.key === 'Escape') {
                    saved = true;
                    input.remove();
                    displaySpan.style.display = '';
                    box.querySelector('.fa-pen-to-square')?.classList.remove('hidden');
                }
            });

            input.addEventListener('blur', saveChange);
        });
    });
});

// ==========================================
// 2. CHECKBOX SELECTION & FLOATING ACTION
// ==========================================
function toggleSelectAllProduk(mainCheckbox) {
    const isChecked = mainCheckbox.checked;
    document.querySelectorAll('.check-item-produk').forEach(cb => {
        // Hanya centang baris yang saat ini terlihat (tidak ter-filter)
        const row = cb.closest('tr');
        if (row && row.style.display !== 'none') {
            cb.checked = isChecked;
        }
    });
    onProductCheckChange();
}

function onProductCheckChange() {
    const checked = document.querySelectorAll('.check-item-produk:checked');
    const floating = document.getElementById('floatingBulkAction');
    const countText = document.getElementById('selectedCountText');

    if (checked.length > 0) {
        floating.classList.remove('hidden');
        countText.innerText = checked.length + ' Produk Terpilih';
    } else {
        floating.classList.add('hidden');
    }
}

function clearSelectedProduk() {
    document.querySelectorAll('.check-item-produk').forEach(cb => cb.checked = false);
    const mainCb = document.getElementById('checkAllProduk');
    if (mainCb) mainCb.checked = false;
    onProductCheckChange();
}

// ==========================================
// 3. RESTOK MASAL (BULK RESTOCK LOGIC)
// ==========================================
function openBulkRestokModal(initialItems = []) {
    const modal = document.getElementById('bulkRestokModal');
    modal.classList.remove('hidden');

    const tbody = document.getElementById('bulkRestokTableBody');
    tbody.innerHTML = '';

    if (initialItems.length > 0) {
        initialItems.forEach(item => {
            addBulkRow(item.id, item.nama, item.kategori_id);
        });
    } else {
        // Ambil 3 produk pertama sebagai default awal
        const sample = (window.ALL_PRODUCTS_DATA || []).slice(0, 3);
        sample.forEach(item => {
            addBulkRow(item.id, item.nama, item.kategori_id);
        });
    }
    recalculateBulkTotals();
}

function openBulkRestokWithSelected() {
    const checked = document.querySelectorAll('.check-item-produk:checked');
    const selectedItems = [];
    checked.forEach(cb => {
        selectedItems.push({
            id: cb.getAttribute('data-id'),
            nama: cb.getAttribute('data-name'),
            kategori_id: cb.getAttribute('data-kategori')
        });
    });

    openBulkRestokModal(selectedItems);
}

function closeBulkRestokModal() {
    document.getElementById('bulkRestokModal').classList.add('hidden');
}

// Menambah Baris ke Tabel Bulk Restok
function addBulkRow(selectedId = '', selectedNama = '', kategoriId = '') {
    const tbody = document.getElementById('bulkRestokTableBody');
    const rowIdx = tbody.querySelectorAll('.bulk-row').length + 1;
    const globalQty = document.getElementById('bulkGlobalQty')?.value || '10';
    const globalSup = document.getElementById('bulkGlobalSupplier')?.value || '1';

    // Cari kategori jika belum ada
    if (!kategoriId && selectedId) {
        const pObj = (window.ALL_PRODUCTS_DATA || []).find(p => p.id === selectedId);
        if (pObj) kategoriId = pObj.kategori_id;
    }

    const tr = document.createElement('tr');
    tr.className = 'bulk-row hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors';

    // Options untuk dropdown produk
    let productOptionsHtml = '';
    (window.ALL_PRODUCTS_DATA || []).forEach(p => {
        const sel = (p.id === selectedId) ? 'selected' : '';
        productOptionsHtml += `<option value="${p.id}" data-kategori="${p.kategori_id}" ${sel}>${p.id} - ${p.nama}</option>`;
    });

    // Options untuk dropdown supplier
    let supplierOptionsHtml = '';
    (window.ALL_SUPPLIERS_DATA || []).forEach(s => {
        const sel = (s.suplayer_id == globalSup) ? 'selected' : '';
        supplierOptionsHtml += `<option value="${s.suplayer_id}" ${sel}>${s.suplayer_nama}</option>`;
    });

    tr.innerHTML = `
        <td class="py-2.5 px-3 text-center font-bold text-slate-400 row-number">${rowIdx}</td>
        <td class="py-2.5 px-3">
            <select class="bulk-prod-select w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-900 dark:text-white focus:outline-none focus:border-teal-500" onchange="onBulkProductChange(this)">
                ${productOptionsHtml}
            </select>
        </td>
        <td class="py-2.5 px-3">
            <select class="bulk-size-select w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-teal-500">
                <!-- Size options loaded dynamically -->
            </select>
        </td>
        <td class="py-2.5 px-3">
            <select class="bulk-sup-select w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-200 focus:outline-none focus:border-teal-500">
                ${supplierOptionsHtml}
            </select>
        </td>
        <td class="py-2.5 px-3 text-right">
            <input type="number" min="1" value="${globalQty}" class="bulk-qty-input w-24 px-2 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-right text-slate-900 dark:text-white focus:outline-none focus:border-teal-500" oninput="recalculateBulkTotals()">
        </td>
        <td class="py-2.5 px-3 text-center">
            <button type="button" onclick="removeBulkRow(this)" class="w-7 h-7 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/40 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-trash-can text-xs"></i>
            </button>
        </td>
    `;

    tbody.appendChild(tr);

    // Muat size sesuai kategori produk
    const prodSelect = tr.querySelector('.bulk-prod-select');
    populateSizesForRow(tr, kategoriId || prodSelect.options[prodSelect.selectedIndex]?.getAttribute('data-kategori'));
    recalculateBulkTotals();
}

function addEmptyRowToBulk() {
    const firstProd = (window.ALL_PRODUCTS_DATA || [])[0];
    if (firstProd) {
        addBulkRow(firstProd.id, firstProd.nama, firstProd.kategori_id);
    }
}

function removeBulkRow(btn) {
    const tr = btn.closest('tr');
    if (tr) {
        tr.remove();
        reindexBulkRows();
        recalculateBulkTotals();
    }
}

function reindexBulkRows() {
    document.querySelectorAll('#bulkRestokTableBody .bulk-row').forEach((tr, i) => {
        tr.querySelector('.row-number').innerText = (i + 1);
    });
}

function onBulkProductChange(select) {
    const tr = select.closest('tr');
    const opt = select.options[select.selectedIndex];
    const katId = opt?.getAttribute('data-kategori');
    populateSizesForRow(tr, katId);
}

function populateSizesForRow(tr, kategoriId) {
    const sizeSelect = tr.querySelector('.bulk-size-select');
    sizeSelect.innerHTML = '';

    const sizes = (window.ALL_SIZES_BY_KAT && window.ALL_SIZES_BY_KAT[kategoriId]) ? window.ALL_SIZES_BY_KAT[kategoriId] : [];
    if (sizes.length > 0) {
        sizes.forEach(sz => {
            const opt = document.createElement('option');
            opt.value = sz.id;
            opt.innerText = 'Size ' + sz.nama;
            sizeSelect.appendChild(opt);
        });
    } else {
        // Fallback jika tidak ada size terpetakan
        const opt = document.createElement('option');
        opt.value = '1';
        opt.innerText = 'All Size (Standar)';
        sizeSelect.appendChild(opt);
    }
}

function applyGlobalQtyToRows() {
    const globalQty = parseInt(document.getElementById('bulkGlobalQty').value) || 1;
    document.querySelectorAll('.bulk-qty-input').forEach(inp => inp.value = globalQty);
    recalculateBulkTotals();
}

function applyGlobalSupplierToRows() {
    const globalSup = document.getElementById('bulkGlobalSupplier').value;
    document.querySelectorAll('.bulk-sup-select').forEach(sel => sel.value = globalSup);
}

function recalculateBulkTotals() {
    const rows = document.querySelectorAll('#bulkRestokTableBody .bulk-row');
    let totalPcs = 0;
    rows.forEach(tr => {
        const qty = parseInt(tr.querySelector('.bulk-qty-input')?.value) || 0;
        totalPcs += qty;
    });

    document.getElementById('bulkTotalItemCount').innerText = rows.length;
    document.getElementById('bulkTotalPcsCount').innerText = totalPcs + ' Pcs';
}

function submitBulkRestok() {
    const rows = document.querySelectorAll('#bulkRestokTableBody .bulk-row');
    if (rows.length === 0) {
        alert('Tidak ada produk dalam daftar restok masal.');
        return;
    }

    const items = [];
    const globalNote = document.getElementById('bulkGlobalNote')?.value || 'Restok Masal';

    for (let i = 0; i < rows.length; i++) {
        const tr = rows[i];
        const pId = tr.querySelector('.bulk-prod-select')?.value;
        const sId = tr.querySelector('.bulk-size-select')?.value;
        const supId = tr.querySelector('.bulk-sup-select')?.value;
        const qty = parseInt(tr.querySelector('.bulk-qty-input')?.value) || 0;

        if (!pId || !sId || qty <= 0) {
            alert(`Baris ke-${i + 1}: Mohon lengkapi ukuran dan jumlah stok valid (> 0).`);
            return;
        }

        items.push({
            barangid: pId,
            size: sId,
            suplayer: supId,
            jumlah: qty,
            keterangan: globalNote
        });
    }

    const btn = document.getElementById('btnSubmitBulkRestok');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan Restok Masal...';
    btn.disabled = true;

    const formData = new FormData();
    formData.append('action', 'ajax_bulk_restok');
    formData.append('items', JSON.stringify(items));

    fetch('index.php?main=barang', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        btn.innerHTML = originalText;
        btn.disabled = false;

        if (res.success) {
            closeBulkRestokModal();
            clearSelectedProduk();
            showToast(res.message);
            setTimeout(() => {
                window.location.reload();
            }, 900);
        } else {
            alert(res.message || 'Gagal menyimpan restok masal.');
        }
    })
    .catch(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        alert('Terjadi kesalahan koneksi.');
    });
}

// ==========================================
// 4. RESTOK SATUAN (SINGLE QUICK RESTOCK)
// ==========================================
function openQuickRestok(barangId, namaBarang, kategoriId) {
    document.getElementById('qrBarangId').value = barangId;
    document.getElementById('qrBadgeId').innerText = barangId;
    document.getElementById('qrNamaBarang').innerText = namaBarang;
    document.getElementById('qrJumlah').value = '10';

    const selectSize = document.getElementById('qrSelectSize');
    selectSize.innerHTML = '';

    const sizes = (window.ALL_SIZES_BY_KAT && window.ALL_SIZES_BY_KAT[kategoriId]) ? window.ALL_SIZES_BY_KAT[kategoriId] : [];
    if (sizes.length > 0) {
        sizes.forEach(sz => {
            const opt = document.createElement('option');
            opt.value = sz.id;
            opt.innerText = 'Size ' + sz.nama;
            selectSize.appendChild(opt);
        });
    } else {
        selectSize.innerHTML = '<option value="1">All Size (Standar)</option>';
    }

    document.getElementById('quickRestokModal').classList.remove('hidden');
}

function closeQuickRestokModal() {
    document.getElementById('quickRestokModal').classList.add('hidden');
}

function addJumlah(n) {
    const el = document.getElementById('qrJumlah');
    const cur = parseInt(el.value) || 0;
    el.value = cur + n;
}

function submitQuickRestok(evt) {
    evt.preventDefault();
    const btn = document.getElementById('btnSubmitRestok');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
    btn.disabled = true;

    const form = document.getElementById('formQuickRestok');
    const formData = new FormData(form);
    formData.append('action', 'ajax_quick_restok');

    fetch('index.php?main=barang', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        btn.innerHTML = originalText;
        btn.disabled = false;

        if (res.success) {
            closeQuickRestokModal();
            showToast(res.message);
            setTimeout(() => {
                window.location.reload();
            }, 800);
        } else {
            alert(res.message || 'Gagal menambahkan stok.');
        }
    })
    .catch(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        alert('Terjadi kesalahan koneksi.');
    });
}

// Toast Notifikasi
function showToast(msg) {
    const toast = document.getElementById('quickToast');
    const toastMsg = document.getElementById('toastMsg');
    if (toast && toastMsg) {
        toastMsg.innerText = msg;
        toast.classList.remove('hidden');
        toast.classList.add('flex');
        setTimeout(() => {
            toast.classList.add('hidden');
            toast.classList.remove('flex');
        }, 3000);
    }
}
</script>

<?php $content = ob_get_clean(); ?>