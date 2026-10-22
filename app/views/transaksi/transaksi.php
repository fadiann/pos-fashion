<?php ob_start(); ?>
<?php
$isTransactionActive = isset($_GET['idTransaksi']) && !empty($_GET['idTransaksi']);
$kodeTransaksi       = $isTransactionActive ? strip_tags($_GET['idTransaksi']) : $model->kodeTran();
$activePelangganId   = '1';
$activePelangganNama = 'Pelanggan Umum';

if ($isTransactionActive) {
    $rows = $model->cekKodeTran($kodeTransaksi);
    if (!empty($rows)) {
        $activePelangganId = $rows['transaksi_pelanggan_id'] ?? '1';
        $pelangganData     = $model->getPelangganById($activePelangganId);
        $activePelangganNama = $pelangganData['pelanggan_nama'] ?? 'Pelanggan Umum';
    }
}

$viewlistpesanan = $viewlistpesanan ?? [];
$totalBayarVal   = (float)($total[0] ?? ($total['total'] ?? 0));
$totalItemCount  = 0;
foreach ($viewlistpesanan as $pItem) {
    $totalItemCount += (int)($pItem['jumlah'] ?? 0);
}
?>

<div class="flex-1 flex flex-col h-full min-h-0 overflow-hidden space-y-2">

    <!-- SUBHEADER / QUICK STATS & CONTROLS (COMPACT) -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-2.5 md:p-3 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-2.5 shrink-0 transition-colors">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-sm border border-emerald-200 dark:border-emerald-800">
                <i class="fa-solid fa-cash-register"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-sm md:text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Kasir Point of Sales</h2>
                    <?php if ($isTransactionActive): ?>
                        <span class="bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-300 dark:border-emerald-700">
                            Aktif: <?= $kodeTransaksi ?>
                        </span>
                    <?php else: ?>
                        <span class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-semibold px-2 py-0.5 rounded-full">
                            Standby
                        </span>
                    <?php endif; ?>
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-0 leading-tight">Sentuh produk untuk memilih ukuran & menambahkan pesanan</p>
            </div>
        </div>

        <div class="flex items-center gap-1.5">
            <!-- Tab Button Switcher -->
            <button type="button" id="tab-btn-pos" onclick="switchMainTab('pos')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-emerald-600 text-white shadow-sm flex items-center gap-1.5">
                <i class="fa-solid fa-cart-shopping text-xs"></i> Layar Kasir
            </button>
            <button type="button" id="tab-btn-history" onclick="switchMainTab('history')" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-1.5">
                <i class="fa-solid fa-clock-rotate-left text-xs"></i> Riwayat (<?= count($viewlist ?? []) ?>)
            </button>
            <?php if ($isTransactionActive): ?>
                <a href="index.php?main=transaksi" onclick="return confirm('Membatalkan/keluar dari sesi transaksi ini?')" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/40 transition-colors" title="Batal Transaksi">
                    <i class="fa-solid fa-xmark text-xs"></i> Batal
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- WIDGET RINGKASAN TRANSAKSI HARIAN KASIR -->
    <div id="kasirDailySummaryWidget" class="bg-white dark:bg-slate-900 rounded-xl p-3 border border-slate-200/80 dark:border-slate-800 shadow-sm transition-all shrink-0">
        <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Ringkasan Transaksi Hari Ini</span>
                <span class="text-[10px] text-slate-400 font-medium">(<?= date('d M Y') ?>)</span>
            </div>
            <button type="button" onclick="toggleDailySummary()" id="toggleSummaryBtn" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 text-xs flex items-center gap-1.5 transition-colors">
                <span id="summaryToggleLabel" class="text-[10px] font-semibold">Tampilkan</span>
                <i id="summaryToggleIcon" class="fa-solid fa-chevron-down text-[10px] transition-transform"></i>
            </button>
        </div>

        <div id="summaryCardsContainer" class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 hidden">
            <!-- 1. Omset Penjualan Lunas -->
            <div class="p-2.5 rounded-xl bg-emerald-50/70 dark:bg-emerald-950/40 border border-emerald-200/70 dark:border-emerald-800/60 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-500 text-white flex items-center justify-center text-sm shadow-sm shrink-0">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Total Omset Lunas</div>
                    <div class="text-xs md:text-sm font-extrabold text-slate-900 dark:text-white truncate">
                        <?= $Helpers->rp($ringkasanHarian['omset_lunas'] ?? 0) ?>
                    </div>
                    <?php if (!empty($ringkasanHarian['diskon_lunas']) && $ringkasanHarian['diskon_lunas'] > 0): ?>
                        <div class="text-[10px] font-semibold text-rose-600 dark:text-rose-400 truncate">
                            Hemat: <?= $Helpers->rp($ringkasanHarian['diskon_lunas']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 2. Transaksi Selesai -->
            <div class="p-2.5 rounded-xl bg-blue-50/70 dark:bg-blue-950/40 border border-blue-200/70 dark:border-blue-800/60 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-500 text-white flex items-center justify-center text-sm shadow-sm shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-blue-700 dark:text-blue-400">Transaksi Selesai</div>
                    <div class="text-xs md:text-sm font-extrabold text-slate-900 dark:text-white truncate">
                        <?= (int)($ringkasanHarian['trx_selesai'] ?? 0) ?> <span class="text-[11px] font-normal text-slate-500">Struk</span>
                    </div>
                </div>
            </div>

            <!-- 3. Produk Terjual -->
            <div class="p-2.5 rounded-xl bg-purple-50/70 dark:bg-purple-950/40 border border-purple-200/70 dark:border-purple-800/60 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-purple-500 text-white flex items-center justify-center text-sm shadow-sm shrink-0">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-purple-700 dark:text-purple-400">Barang Terjual</div>
                    <div class="text-xs md:text-sm font-extrabold text-slate-900 dark:text-white truncate">
                        <?= (int)($ringkasanHarian['pcs_terjual'] ?? 0) ?> <span class="text-[11px] font-normal text-slate-500">Pcs</span>
                    </div>
                </div>
            </div>

            <!-- 4. Transaksi Pending / Belum Lunas -->
            <div class="p-2.5 rounded-xl bg-amber-50/70 dark:bg-amber-950/40 border border-amber-200/70 dark:border-amber-800/60 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-amber-500 text-white flex items-center justify-center text-sm shadow-sm shrink-0">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">Pending / Antrean</div>
                    <div class="text-xs md:text-sm font-extrabold text-slate-900 dark:text-white truncate">
                        <?= (int)($ringkasanHarian['trx_pending'] ?? 0) ?> <span class="text-[11px] font-normal text-slate-500">Struk</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 1: LAYAR KASIR POS (DUAL COLUMN) -->
    <div id="tab-content-pos" class="flex-1 flex flex-col md:flex-row gap-2.5 overflow-hidden min-h-0 h-full">
        
        <?php if (!$isTransactionActive): ?>
            <!-- JIKA BELUM ADA TRANSAKSI AKTIF: TAMPILKAN START PANEL (POSISI LEBIH NAIK / ELEVATED) -->
            <div class="w-full flex-1 bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 p-5 md:p-8 flex flex-col items-center justify-start pt-6 md:pt-10 text-center shadow-sm transition-colors">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl mb-2.5 border border-emerald-200 dark:border-emerald-800">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <h3 class="text-base md:text-lg font-bold text-slate-900 dark:text-white mb-1">Mulai Transaksi Kasir Baru</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mb-4 leading-relaxed">Pilih pelanggan jika terdaftar, atau langsung klik tombol di bawah untuk melayani Pelanggan Umum.</p>

                <form action="index.php?main=transaksi&idTransaksi=<?= $kodeTransaksi ?>" method="post" class="w-full max-w-xs space-y-3">
                    <input type="hidden" name="action" value="simpantransaksi">
                    <input type="hidden" name="idtran" value="<?= $kodeTransaksi ?>">
                    <input type="hidden" name="tipe" value="0">
                    
                    <div class="text-left">
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Pilih Pelanggan</label>
                        <div class="relative">
                            <?= $Helpers->combo_pelanggan('', true) ?>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-bold text-sm transition-all shadow-md shadow-emerald-600/20 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-play text-xs"></i> Buka Kasir Sekarang
                    </button>
                </form>
            </div>
        <?php else: ?>

            <!-- KOLOM KIRI (KATALOG & PENCARIAN SEPATU) -->
            <div class="flex-1 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 flex flex-col h-[52vh] md:h-full overflow-hidden shadow-sm transition-colors min-h-0">
                
                <!-- Search Bar & Category Filter -->
                <div class="p-3 border-b border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 space-y-2.5 shrink-0">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" id="pos-search-input" onkeyup="filterProductCards()" placeholder="Ketik nama atau kode produk..." class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                        <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                            <i class="fa-solid fa-circle-xmark text-sm"></i>
                        </button>
                    </div>

                    <!-- Category Pills -->
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5" id="category-filter-pills">
                        <button type="button" onclick="filterByCategory('all', this)" class="cat-pill active px-3 py-1 rounded-full text-xs font-bold bg-slate-900 dark:bg-emerald-600 text-white whitespace-nowrap transition-colors">
                            Semua Produk
                        </button>
                        <?php
                        $kategoriList = [];
                        if (!empty($daftarBarang)) {
                            foreach ($daftarBarang as $b) {
                                if (!empty($b['kategori_nama']) && !in_array($b['kategori_nama'], $kategoriList)) {
                                    $kategoriList[] = $b['kategori_nama'];
                                }
                            }
                        }
                        foreach ($kategoriList as $cat):
                        ?>
                            <button type="button" onclick="filterByCategory('<?= htmlspecialchars($cat) ?>', this)" class="cat-pill px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 whitespace-nowrap transition-colors">
                                <?= $cat ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Product Grid (Cards Touch-Friendly) -->
                <div class="flex-1 overflow-y-auto p-2.5 md:p-3 min-h-0">
                    <div id="product-grid-container" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2.5">
                        <?php if (!empty($daftarBarang)): ?>
                            <?php foreach ($daftarBarang as $barang): ?>
                                <div class="product-card bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 hover:border-emerald-500 dark:hover:border-emerald-500 rounded-xl p-3 flex flex-col justify-between cursor-pointer hover:shadow-md transition-all active:scale-[0.97]"
                                     data-id="<?= $barang['barang_id'] ?>"
                                     data-name="<?= htmlspecialchars($barang['nama_barang']) ?>"
                                     data-category="<?= htmlspecialchars($barang['kategori_nama'] ?? '') ?>"
                                     data-price="<?= (float)$barang['harga_barang'] ?>"
                                     onclick="openSizeSelector('<?= $barang['barang_id'] ?>', '<?= htmlspecialchars(addslashes($barang['nama_barang'])) ?>', <?= (float)$barang['harga_barang'] ?>)">
                                     
                                    <div>
                                        <!-- Product Icon / Image Placeholder -->
                                        <div class="w-full h-24 rounded-lg bg-slate-50 dark:bg-slate-900/60 flex items-center justify-center text-slate-300 dark:text-slate-600 mb-2 relative overflow-hidden group">
                                            <i class="fa-solid fa-box text-3xl group-hover:scale-110 group-hover:text-emerald-500 transition-all"></i>
                                            <span class="absolute top-1.5 left-1.5 text-[10px] font-bold px-1.5 py-0.5 rounded bg-slate-900/70 dark:bg-black/60 text-white">
                                                <?= $barang['barang_id'] ?>
                                            </span>
                                        </div>
                                        <p class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 mb-0.5"><?= $barang['kategori_nama'] ?? 'Produk' ?></p>
                                        <h4 class="text-xs md:text-sm font-bold text-slate-800 dark:text-slate-100 line-clamp-2 leading-snug mb-1">
                                            <?= $barang['nama_barang'] ?>
                                        </h4>
                                    </div>

                                    <div class="pt-2 border-t border-slate-100 dark:border-slate-700/50 flex items-center justify-between">
                                        <span class="text-xs md:text-sm font-extrabold text-slate-900 dark:text-white">
                                            <?= $Helpers->rupiah($barang['harga_barang']) ?>
                                        </span>
                                        <span class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs font-bold border border-emerald-200 dark:border-emerald-800">
                                            <i class="fa-solid fa-plus"></i>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-span-full py-12 text-center text-slate-400">
                                <i class="fa-solid fa-box-open text-4xl mb-2"></i>
                                <p class="text-sm">Belum ada data produk di sistem.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN (KERANJANG BELANJA & RINGKASAN CHECKOUT) -->
            <div class="w-full md:w-[350px] lg:w-[380px] xl:w-[410px] bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 flex flex-col h-[46vh] md:h-full overflow-hidden shadow-sm shrink-0 transition-colors min-h-0">
                
                <!-- Cart Header -->
                <div class="p-3 border-b border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-basket-shopping text-emerald-600 dark:text-emerald-400 text-sm"></i>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-0">Keranjang Kasir</h3>
                        <span class="bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 text-xs font-bold px-2 py-0.5 rounded-full border border-emerald-300 dark:border-emerald-700">
                            <?= $totalItemCount ?> item
                        </span>
                    </div>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                        <?= ucfirst($activePelangganNama) ?>
                    </span>
                </div>

                <!-- Cart Items List -->
                <div class="flex-1 overflow-y-auto p-2.5 space-y-2 min-h-0">
                    <?php if (!empty($viewlistpesanan)): ?>
                        <?php foreach ($viewlistpesanan as $item): ?>
                            <div class="bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/90 dark:border-slate-700/80 rounded-xl p-2.5 flex items-center justify-between gap-2.5 hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-1.5 mb-0.5">
                                        <span class="bg-slate-800 dark:bg-slate-700 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">
                                            Size <?= $item['size_nama'] ?>
                                        </span>
                                        <h5 class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate mb-0"><?= $item['nama_barang'] ?></h5>
                                    </div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 font-medium flex items-center gap-2">
                                        <span><?= $item['jumlah'] ?> pcs &times; <?= $Helpers->rupiah($item['harga_barang']) ?></span>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-xs md:text-sm font-extrabold text-slate-900 dark:text-white mb-0.5">
                                        <?= $Helpers->rupiah($item['subtotal']) ?>
                                    </p>
                                    <form action="" method="post" class="inline-block" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                        <input type="hidden" value="deletepesanan" name="action">
                                        <input type="hidden" value="<?= $item['unix_id'] ?>" name="unix_id">
                                        <input type="hidden" value="<?= $item['transaksi_id'] ?>" name="idTran">
                                        <button type="submit" class="p-0.5 text-rose-500 hover:text-rose-700 dark:hover:text-rose-400 text-[11px] font-medium transition-colors" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="h-full flex flex-col items-center justify-center text-center p-6 text-slate-400">
                            <i class="fa-solid fa-cart-arrow-down text-3xl mb-2 text-slate-300 dark:text-slate-600"></i>
                            <p class="text-xs font-bold text-slate-600 dark:text-slate-300 mb-0.5">Keranjang Masih Kosong</p>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mb-0">Pilih produk dari katalog di samping.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Cart Footer & Checkout Button (LOCKED AT THE BOTTOM) -->
                <div class="p-2.5 md:p-3 border-t border-slate-200/80 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/80 space-y-2 shrink-0 mt-auto shadow-[0_-4px_12px_rgba(0,0,0,0.03)]">
                    <div class="space-y-1 text-xs text-slate-600 dark:text-slate-400">
                        <div class="flex justify-between font-medium">
                            <span>Total Item</span>
                            <span class="text-slate-800 dark:text-slate-200 font-bold"><?= $totalItemCount ?> Pcs</span>
                        </div>
                        <div class="flex justify-between text-sm font-extrabold text-slate-900 dark:text-white pt-1 border-t border-slate-200 dark:border-slate-800">
                            <span>TOTAL TAGIHAN</span>
                            <span class="text-base md:text-lg text-emerald-600 dark:text-emerald-400 font-extrabold"><?= $Helpers->rupiah($totalBayarVal) ?></span>
                        </div>
                    </div>

                    <!-- Bayar Button -->
                    <?php if (!empty($viewlistpesanan) && $totalBayarVal > 0): ?>
                        <button type="button" onclick="openCheckoutModal(<?= $totalBayarVal ?>)" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-extrabold text-sm md:text-base transition-all shadow-md shadow-emerald-600/30 flex items-center justify-between cursor-pointer">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-credit-card text-sm"></i> PROSES BAYAR
                            </span>
                            <span class="bg-emerald-700/60 px-2.5 py-0.5 rounded-lg text-xs md:text-sm font-black">
                                <?= $Helpers->rupiah($totalBayarVal) ?>
                            </span>
                        </button>
                    <?php else: ?>
                        <button type="button" disabled class="w-full py-3 px-4 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-600 font-bold text-sm cursor-not-allowed text-center">
                            Keranjang Kosong
                        </button>
                    <?php endif; ?>
                </div>

            </div>

            <!-- FLOATING MOBILE QUICK CHECKOUT BAR (Khusus Smartphone: Langsung Bayar Tanpa Perlu Scroll) -->
            <?php if (!empty($viewlistpesanan) && $totalBayarVal > 0): ?>
                <div class="md:hidden fixed bottom-3 left-3 right-3 z-30 bg-slate-900/95 text-white p-2.5 rounded-2xl shadow-2xl border border-slate-700 flex items-center justify-between gap-3 backdrop-blur-md">
                    <div class="min-w-0">
                        <span class="text-[10px] text-slate-400 block font-semibold leading-tight"><?= $totalItemCount ?> Item di Keranjang</span>
                        <span class="text-sm font-black text-emerald-400 leading-tight"><?= $Helpers->rupiah($totalBayarVal) ?></span>
                    </div>
                    <button type="button" onclick="openCheckoutModal(<?= $totalBayarVal ?>)" class="py-2 px-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white text-xs font-black shadow-md flex items-center gap-1.5 shrink-0">
                        <i class="fa-solid fa-credit-card text-xs"></i>
                        <span>PROSES BAYAR</span>
                    </button>
                </div>
            <?php endif; ?>

        <?php endif; ?>

    </div>

    <!-- TAB 2: RIWAYAT TRANSAKSI HARI INI -->
    <div id="tab-content-history" class="hidden flex-1 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 flex flex-col overflow-hidden shadow-sm transition-colors">
        <div class="p-4 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between shrink-0">
            <h3 class="text-base font-bold text-slate-900 dark:text-white mb-0">Daftar Transaksi Hari Ini</h3>
            <span class="text-xs text-slate-500 dark:text-slate-400"><?= $Helpers->tanggal_indo(date('Y-m-d')) ?></span>
        </div>

        <div class="flex-1 overflow-y-auto p-4">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs md:text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 uppercase text-[11px] font-bold">
                            <th class="py-3 px-3">No</th>
                            <th class="py-3 px-3">Kode Transaksi</th>
                            <th class="py-3 px-3">Pelanggan</th>
                            <th class="py-3 px-3">Metode</th>
                            <th class="py-3 px-3 text-right">Total Bayar</th>
                            <th class="py-3 px-3 text-center">Status</th>
                            <th class="py-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php if (!empty($viewlist)): ?>
                            <?php $no = 1; foreach ($viewlist as $row): 
                                $isLunas  = ($row['status'] == '0');
                                $tTotal   = $model->getTotal($row['transaksi_id']);
                                $nominal  = (float)($tTotal[0] ?? 0);
                                if ($isLunas && isset($row['total_pembayaran']) && $row['total_pembayaran'] !== null && $row['total_pembayaran'] > 0) {
                                    $nominal = (float)$row['total_pembayaran'];
                                }
                                $promoVal = (float)($row['promo_nominal'] ?? 0);
                            ?>
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-3 px-3 text-slate-500 dark:text-slate-400 font-medium"><?= $no++ ?></td>
                                    <td class="py-3 px-3 font-bold text-slate-800 dark:text-slate-200"><?= $row['transaksi_id'] ?></td>
                                    <td class="py-3 px-3 text-slate-700 dark:text-slate-300"><?= $row['pelanggan_nama'] ?? 'Umum' ?></td>
                                    <td class="py-3 px-3">
                                        <?php
                                        if ($row['jenis_pembayaran'] == 1) {
                                            echo '<span class="px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300 font-bold text-[11px] border border-emerald-300 dark:border-emerald-700">Tunai</span>';
                                        } else {
                                            echo '<span class="px-2 py-0.5 rounded bg-sky-100 dark:bg-sky-950/80 text-sky-800 dark:text-sky-300 font-bold text-[11px] border border-sky-300 dark:border-sky-700">Non Tunai</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="py-3 px-3 text-right">
                                        <div class="font-extrabold text-slate-900 dark:text-white"><?= $Helpers->rupiah($nominal) ?></div>
                                        <?php if ($promoVal > 0): ?>
                                            <div class="text-[10px] font-bold text-rose-500 dark:text-rose-400">
                                                Diskon: -<?= $Helpers->rupiah($promoVal) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <?php if ($isLunas): ?>
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800">
                                                <i class="fa-solid fa-check"></i> Lunas
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/60 px-2 py-0.5 rounded-full border border-amber-200 dark:border-amber-800">
                                                <i class="fa-solid fa-clock"></i> Belum Selesai
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-3 text-center space-x-1">
                                        <?php if ($isLunas): ?>
                                            <a href="index.php?main=print&transaksi_id=<?= $row['transaksi_id'] ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-900 dark:bg-slate-800 text-white text-xs font-semibold hover:bg-slate-800 dark:hover:bg-slate-700 transition-colors" title="Cetak Struk">
                                                <i class="fa-solid fa-print"></i> Cetak
                                            </a>
                                        <?php else: ?>
                                            <a href="index.php?main=transaksi&idTransaksi=<?= $row['transaksi_id'] ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition-colors">
                                                <i class="fa-solid fa-arrow-right"></i> Lanjutkan
                                            </a>
                                            <a href="index.php?main=transaksi&action=deletetransaksi&idTransaksi=<?= $row['transaksi_id'] ?>" onclick="return confirm('Hapus transaksi ini?')" class="inline-flex items-center p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">Belum ada transaksi hari ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ========================================== -->
<!-- MODAL 1: PEMILIH UKURAN (SIZE SELECTOR)   -->
<!-- ========================================== -->
<div id="modal-size-selector" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800 flex flex-col max-h-[90vh] transition-colors">
        
        <!-- Header Modal Size -->
        <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold tracking-wider text-emerald-600 dark:text-emerald-400 uppercase" id="modal-size-id">ID SEPATU</span>
                <h3 class="text-base md:text-lg font-bold text-slate-900 dark:text-white mb-0" id="modal-size-name">Nama Sepatu</h3>
                <p class="text-sm font-extrabold text-slate-800 dark:text-slate-200 mb-0" id="modal-size-price">Rp 0</p>
            </div>
            <button type="button" onclick="closeSizeModal()" class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <!-- Form Add Pesanan -->
        <form action="index.php?main=transaksi&idTransaksi=<?= $kodeTransaksi ?>" method="post" id="form-add-item" class="p-5 flex-1 overflow-y-auto space-y-5">
            <input type="hidden" name="action" value="tambahpesanan">
            <input type="hidden" name="idtransaksi" value="<?= $kodeTransaksi ?>">
            <input type="hidden" name="pesanan" id="input-pesanan-val" value="">
            <input type="hidden" name="size" id="input-selected-size" value="" required>

            <!-- Size Chips Grid -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2.5">
                    Pilih Ukuran / Size Sepatu
                </label>
                <div id="modal-size-chips-container" class="grid grid-cols-3 sm:grid-cols-4 gap-2.5">
                    <div class="col-span-full py-6 text-center text-slate-400">
                        <i class="fa-solid fa-spinner fa-spin text-xl mb-2"></i>
                        <p class="text-xs">Memuat daftar ukuran...</p>
                    </div>
                </div>
            </div>

            <!-- Quantity Stepper Touch Friendly -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2.5">
                    Jumlah Pembelian
                </label>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="adjustQty(-1)" class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 active:scale-95 text-xl font-bold flex items-center justify-center transition-all">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                    <input type="number" name="jumlah" id="input-qty" value="1" min="1" class="flex-1 h-12 text-center text-lg font-extrabold text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20" required>
                    <button type="button" onclick="adjustQty(1)" class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 active:scale-95 text-xl font-bold flex items-center justify-center transition-all">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="btn-submit-cart" disabled class="w-full py-4 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-600 font-extrabold text-sm transition-all shadow-md flex items-center justify-center gap-2">
                Pilih Ukuran Dulu
            </button>
        </form>

    </div>
</div>


<!-- ========================================== -->
<!-- MODAL 2: CHECKOUT & PEMBAYARAN KASIR      -->
<!-- ========================================== -->
<div id="modal-checkout" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-3 md:p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-md overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800 flex flex-col max-h-[92vh] transition-colors">
        
        <!-- Modal Header (Compact) -->
        <div class="px-4 py-2.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/70 dark:bg-slate-900/50">
            <div>
                <span class="text-[10px] font-extrabold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">CHECKOUT PEMBAYARAN</span>
                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-0 leading-tight">Transaksi <?= $kodeTransaksi ?></h3>
            </div>
            <button type="button" onclick="closeCheckoutModal()" class="w-7 h-7 rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <form action="print.php" method="post" id="form-checkout" class="flex-1 overflow-y-auto p-3.5 space-y-2.5">
            <input type="hidden" name="idTran" value="<?= $kodeTransaksi ?>">
            <input type="hidden" name="status" value="0">
            <input type="hidden" name="total" id="co-input-total" value="<?= $totalBayarVal ?>">
            <input type="hidden" name="potongan" id="co-input-potongan" value="0">
            <input type="hidden" name="sisa_bayar" id="co-input-sisa" value="<?= $totalBayarVal ?>">

            <!-- Compact Display Tagihan & Rincian Diskon -->
            <div class="bg-slate-900 dark:bg-slate-950 text-white rounded-xl p-3 text-center border border-slate-800 transition-all shadow-sm">
                <!-- Breakdown Subtotal & Diskon (Tampil saat ada diskon) -->
                <div id="co-breakdown-container" class="hidden mb-1.5 pb-1.5 border-b border-slate-800 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400">Subtotal: <strong id="co-display-subtotal" class="text-slate-200 font-semibold">Rp 0</strong></span>
                    <span class="text-rose-400 font-bold flex items-center gap-1">
                        <i class="fa-solid fa-tag text-[9px]"></i> Diskon: <span id="co-display-diskon-badge">- Rp 0</span>
                    </span>
                </div>

                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5" id="co-label-total">Total Tagihan</span>
                <h2 class="text-xl md:text-2xl font-black text-emerald-400 mb-0 leading-tight" id="co-display-total">
                    <?= $Helpers->rupiah($totalBayarVal) ?>
                </h2>
            </div>

            <!-- Input Diskon / Potongan Harga (Nominal Rupiah) - Ringkas -->
            <div class="bg-slate-50 dark:bg-slate-800/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700/80 space-y-1.5">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-1 mb-0">
                        <i class="fa-solid fa-tags text-rose-500 text-xs"></i> Diskon / Potongan
                    </label>
                    <span class="text-[10px] text-slate-400 font-medium">Nominal Rp</span>
                </div>
                
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                    <input type="text" inputmode="numeric" id="co-input-potongan-display" oninput="handleDiscountInput(this.value)" class="w-full h-9 pl-9 pr-14 rounded-lg bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 font-bold text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500/20 transition-all" placeholder="0">
                    <button type="button" onclick="setPresetDiscount(0)" class="absolute right-1.5 top-1/2 -translate-y-1/2 px-2 py-0.5 text-[10px] font-bold text-slate-500 hover:text-rose-600 dark:text-slate-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded transition-colors">
                        Reset
                    </button>
                </div>

                <!-- Shortcut Preset Diskon Nominal Cepat -->
                <div class="flex items-center gap-1 flex-wrap pt-0.5">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mr-0.5">Preset:</span>
                    <button type="button" onclick="setPresetDiscount(1000)" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-rose-400 hover:text-rose-600 transition-colors shadow-2xs active:scale-95">
                        1.000
                    </button>
                    <button type="button" onclick="setPresetDiscount(5000)" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-rose-400 hover:text-rose-600 transition-colors shadow-2xs active:scale-95">
                        5.000
                    </button>
                    <button type="button" onclick="setPresetDiscount(10000)" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-rose-400 hover:text-rose-600 transition-colors shadow-2xs active:scale-95">
                        10.000
                    </button>
                    <button type="button" onclick="setPresetDiscount(20000)" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-rose-400 hover:text-rose-600 transition-colors shadow-2xs active:scale-95">
                        20.000
                    </button>
                    <button type="button" onclick="setPresetDiscount(50000)" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-rose-400 hover:text-rose-600 transition-colors shadow-2xs active:scale-95">
                        50.000
                    </button>
                    <button type="button" onclick="setPresetDiscount(100000)" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-rose-400 hover:text-rose-600 transition-colors shadow-2xs active:scale-95">
                        100.000
                    </button>
                </div>
            </div>

            <!-- Pilihan Metode Pembayaran - Ringkas -->
            <div>
                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="jenis_pembayaran" value="1" checked onchange="onPaymentMethodChange(1)" class="peer sr-only">
                        <div class="py-2 px-2 text-center rounded-lg border border-slate-200 dark:border-slate-700 peer-checked:border-emerald-600 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-950/60 peer-checked:text-emerald-800 dark:peer-checked:text-emerald-300 font-bold text-xs text-slate-700 dark:text-slate-300 transition-all flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-money-bill-wave text-xs"></i>
                            <span>Tunai (Cash)</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="jenis_pembayaran" value="2" onchange="onPaymentMethodChange(2)" class="peer sr-only">
                        <div class="py-2 px-2 text-center rounded-lg border border-slate-200 dark:border-slate-700 peer-checked:border-emerald-600 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-950/60 peer-checked:text-emerald-800 dark:peer-checked:text-emerald-300 font-bold text-xs text-slate-700 dark:text-slate-300 transition-all flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-qrcode text-xs"></i>
                            <span>Non Tunai</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Quick Cash Buttons (Dinamis & Ringkas) -->
            <div>
                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Pilihan Uang Cepat (Tap)</label>
                <div id="quick-cash-container" class="grid grid-cols-2 sm:grid-cols-4 gap-1.5">
                    <!-- Tombol pilihan uang cepat dirender otomatis via JavaScript -->
                </div>
            </div>

            <!-- Input Uang Diterima & Kembalian (Ringkas & Proporsional) -->
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Uang Diterima (Rp)</label>
                    <input type="hidden" name="cash" id="co-input-cash" value="0">
                    <input type="text" inputmode="numeric" id="co-input-cash-display" oninput="handleCashInput(this.value)" class="w-full h-10 px-3 rounded-lg bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 font-extrabold text-sm md:text-base text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500" placeholder="0" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Kembalian</label>
                    <div id="co-display-kembalian" class="h-10 px-3 rounded-lg bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 font-extrabold text-sm md:text-base text-slate-700 dark:text-slate-300 flex items-center truncate">
                        Rp 0
                    </div>
                </div>
            </div>

            <!-- Submit Checkout Button (Ringkas & Tegas) -->
            <button type="submit" id="btn-finish-checkout" class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-extrabold text-sm transition-all shadow-md shadow-emerald-600/30 flex items-center justify-center gap-2 cursor-pointer mt-1">
                <i class="fa-solid fa-print text-xs"></i> SELESAIKAN & CETAK STRUK
            </button>
        </form>

    </div>
</div>

<!-- JAVASCRIPT LOGIC KASIR TOUCH & POS -->
<script>
    let currentShoeId = '';
    let currentShoeName = '';
    let currentShoePrice = 0;
    let selectedStock = 0;

    // Tab Switcher (POS Screen vs Riwayat Hari Ini)
    function switchMainTab(tab) {
        const posTab = document.getElementById('tab-content-pos');
        const hisTab = document.getElementById('tab-content-history');
        const posBtn = document.getElementById('tab-btn-pos');
        const hisBtn = document.getElementById('tab-btn-history');

        if (tab === 'pos') {
            posTab.classList.remove('hidden');
            hisTab.classList.add('hidden');
            posBtn.className = 'px-4 py-2 rounded-xl text-xs md:text-sm font-bold transition-all bg-emerald-600 text-white shadow-md shadow-emerald-600/20 flex items-center gap-2';
            hisBtn.className = 'px-4 py-2 rounded-xl text-xs md:text-sm font-semibold transition-all bg-slate-100 text-slate-700 hover:bg-slate-200 flex items-center gap-2';
        } else {
            posTab.classList.add('hidden');
            hisTab.classList.remove('hidden');
            hisBtn.className = 'px-4 py-2 rounded-xl text-xs md:text-sm font-bold transition-all bg-emerald-600 text-white shadow-md shadow-emerald-600/20 flex items-center gap-2';
            posBtn.className = 'px-4 py-2 rounded-xl text-xs md:text-sm font-semibold transition-all bg-slate-100 text-slate-700 hover:bg-slate-200 flex items-center gap-2';
        }
    }

    // Filter Shoe Cards by Search Input
    function filterProductCards() {
        const query = document.getElementById('pos-search-input').value.toLowerCase();
        const cards = document.querySelectorAll('.product-card');
        cards.forEach(card => {
            const name = card.getAttribute('data-name').toLowerCase();
            const id = card.getAttribute('data-id').toLowerCase();
            if (name.includes(query) || id.includes(query)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function clearSearch() {
        document.getElementById('pos-search-input').value = '';
        filterProductCards();
    }

    // Filter by Category
    function filterByCategory(category, btn) {
        document.querySelectorAll('.cat-pill').forEach(b => {
            b.className = 'cat-pill px-3.5 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 whitespace-nowrap transition-colors';
        });
        btn.className = 'cat-pill active px-3.5 py-1.5 rounded-full text-xs font-bold bg-slate-900 text-white whitespace-nowrap transition-colors';

        const cards = document.querySelectorAll('.product-card');
        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category');
            if (category === 'all' || cardCat === category) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Open Size Selector Modal
    function openSizeSelector(id, name, price) {
        currentShoeId = id;
        currentShoeName = name;
        currentShoePrice = price;

        document.getElementById('modal-size-id').innerText = 'ID: ' + id;
        document.getElementById('modal-size-name').innerText = name;
        document.getElementById('modal-size-price').innerText = formatRupiah(price);
        document.getElementById('input-pesanan-val').value = id + '| ' + name;
        document.getElementById('input-qty').value = 1;
        document.getElementById('input-selected-size').value = '';

        const submitBtn = document.getElementById('btn-submit-cart');
        submitBtn.disabled = true;
        submitBtn.className = 'w-full py-4 rounded-xl bg-slate-200 text-slate-400 font-extrabold text-sm transition-all shadow-md flex items-center justify-center gap-2 cursor-not-allowed';
        submitBtn.innerText = 'Pilih Ukuran Dulu';

        const chipsContainer = document.getElementById('modal-size-chips-container');
        chipsContainer.innerHTML = `
            <div class="col-span-full py-6 text-center text-slate-400">
                <i class="fa-solid fa-spinner fa-spin text-xl mb-2"></i>
                <p class="text-xs">Memuat daftar ukuran...</p>
            </div>
        `;

        document.getElementById('modal-size-selector').classList.remove('hidden');

        // Fetch sizes with stock via AJAX
        $.ajax({
            url: 'Api.php',
            type: 'POST',
            data: { action: 'getsizeswithstock', param: id },
            dataType: 'json',
            success: function(data) {
                chipsContainer.innerHTML = '';
                if (!data || data.length === 0) {
                    chipsContainer.innerHTML = `<p class="col-span-full text-center text-xs text-rose-500 font-semibold">Belum ada data ukuran untuk produk ini.</p>`;
                    return;
                }
                data.forEach(item => {
                    const hasStock = item.stok > 0;
                    const chip = document.createElement('button');
                    chip.type = 'button';
                    chip.disabled = !hasStock;
                    chip.className = hasStock 
                        ? 'size-chip p-3 rounded-2xl border-2 border-slate-200 hover:border-emerald-500 font-bold text-center transition-all flex flex-col items-center justify-center'
                        : 'size-chip p-3 rounded-2xl border border-slate-100 bg-slate-50 text-slate-300 font-bold text-center cursor-not-allowed flex flex-col items-center justify-center';
                    
                    chip.innerHTML = `
                        <span class="text-base font-extrabold ${hasStock ? 'text-slate-900' : 'text-slate-400'}">${item.size_nama}</span>
                        <span class="text-[10px] font-semibold ${hasStock ? 'text-emerald-600' : 'text-slate-300'}">${hasStock ? 'Stok: ' + item.stok : 'Habis'}</span>
                    `;

                    if (hasStock) {
                        chip.onclick = function() {
                            selectSizeChip(item.size_id, item.stok, chip);
                        };
                    }
                    chipsContainer.appendChild(chip);
                });
            },
            error: function() {
                chipsContainer.innerHTML = `<p class="col-span-full text-center text-xs text-rose-500">Gagal mengambil ukuran.</p>`;
            }
        });
    }

    function selectSizeChip(sizeId, stok, chipEl) {
        document.querySelectorAll('.size-chip').forEach(c => {
            c.classList.remove('border-emerald-600', 'bg-emerald-50', 'text-emerald-800');
            c.classList.add('border-slate-200');
        });
        chipEl.classList.remove('border-slate-200');
        chipEl.classList.add('border-emerald-600', 'bg-emerald-50');

        document.getElementById('input-selected-size').value = sizeId;
        selectedStock = stok;

        const submitBtn = document.getElementById('btn-submit-cart');
        submitBtn.disabled = false;
        submitBtn.className = 'w-full py-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-extrabold text-sm transition-all shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 cursor-pointer';
        submitBtn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Tambahkan ke Keranjang';
    }

    function closeSizeModal() {
        document.getElementById('modal-size-selector').classList.add('hidden');
    }

    function adjustQty(amount) {
        const qtyEl = document.getElementById('input-qty');
        let current = parseInt(qtyEl.value) || 1;
        current += amount;
        if (current < 1) current = 1;
        if (selectedStock > 0 && current > selectedStock) {
            alert('Jumlah pembelian melebihi sisa stok (' + selectedStock + ')!');
            current = selectedStock;
        }
        qtyEl.value = current;
    }

    // Checkout Modal Handling
    let totalBillAmount = 0;
    let currentDiscount = 0;

    /**
     * Parsing string berformat titik/angka menjadi integer
     * Contoh: "100.000" -> 100000, "1.500" -> 1500
     */
    function parseNumberDot(val) {
        if (!val) return 0;
        const cleaned = String(val).replace(/[^\d]/g, '');
        return cleaned !== '' ? parseInt(cleaned, 10) : 0;
    }

    /**
     * Format angka dengan pemisah titik (.) untuk ribuan
     * Contoh: 50000 -> "50.000", 125000 -> "125.000"
     */
    function formatNumberDot(num) {
        const val = Math.round(Number(num) || 0);
        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function openCheckoutModal(total) {
        totalBillAmount = total;
        currentDiscount = 0;
        
        const discInput = document.getElementById('co-input-potongan-display');
        if (discInput) discInput.value = '';

        updateCheckoutCalculations(true);
        document.getElementById('modal-checkout').classList.remove('hidden');
    }

    function handleDiscountInput(val) {
        let disc = parseNumberDot(val);
        if (disc < 0) disc = 0;
        if (disc > totalBillAmount) {
            disc = totalBillAmount;
        }
        const discInput = document.getElementById('co-input-potongan-display');
        if (discInput) {
            discInput.value = disc > 0 ? formatNumberDot(disc) : '';
        }
        currentDiscount = disc;
        updateCheckoutCalculations(false);
    }

    function handleDiscountChange(val) {
        handleDiscountInput(val);
    }

    function setPresetDiscount(nominal) {
        nominal = Math.round(Number(nominal) || 0);
        if (nominal > totalBillAmount) {
            nominal = totalBillAmount;
        }
        const discInput = document.getElementById('co-input-potongan-display');
        if (discInput) {
            discInput.value = nominal > 0 ? formatNumberDot(nominal) : '';
        }
        currentDiscount = nominal;
        updateCheckoutCalculations(false);
    }

    function updateCheckoutCalculations(resetCash) {
        const netTotal = Math.max(0, totalBillAmount - currentDiscount);
        
        document.getElementById('co-input-total').value = totalBillAmount;
        document.getElementById('co-input-potongan').value = currentDiscount;
        document.getElementById('co-input-sisa').value = netTotal;

        const breakdownContainer = document.getElementById('co-breakdown-container');
        const dispSubtotal = document.getElementById('co-display-subtotal');
        const dispDiskonBadge = document.getElementById('co-display-diskon-badge');
        const labelTotal = document.getElementById('co-label-total');
        const dispTotal = document.getElementById('co-display-total');

        if (currentDiscount > 0) {
            if (breakdownContainer) breakdownContainer.classList.remove('hidden');
            if (dispSubtotal) dispSubtotal.innerText = formatRupiah(totalBillAmount);
            if (dispDiskonBadge) dispDiskonBadge.innerText = '- ' + formatRupiah(currentDiscount);
            if (labelTotal) labelTotal.innerText = 'TOTAL SETELAH DISKON';
        } else {
            if (breakdownContainer) breakdownContainer.classList.add('hidden');
            if (labelTotal) labelTotal.innerText = 'TOTAL TAGIHAN';
        }

        if (dispTotal) {
            dispTotal.innerText = formatRupiah(netTotal);
        }

        // Render Pilihan Uang Cepat (Tap) yang Mendekati Net Tagihan
        renderQuickCashOptions(netTotal);

        if (resetCash || isNonTunaiSelected()) {
            setCashAmount(netTotal);
        } else {
            calculateChange();
        }
    }

    function isNonTunaiSelected() {
        const radio = document.querySelector('input[name="jenis_pembayaran"]:checked');
        return radio && radio.value === '2';
    }

    function onPaymentMethodChange(jenis) {
        const netTotal = Math.max(0, totalBillAmount - currentDiscount);
        if (jenis === 2) {
            setCashAmount(netTotal);
        } else {
            calculateChange();
        }
    }

    /**
     * Menghasilkan pecahan uang cepat yang cerdas dan mendekati total tagihan
     * Format ribuan dipisahkan dengan titik (.)
     */
    function renderQuickCashOptions(total) {
        const container = document.getElementById('quick-cash-container');
        if (!container) return;
        container.innerHTML = '';

        const options = [];

        // 1. Opsi Uang Pas
        options.push({
            label: 'Uang Pas (' + formatNumberDot(total) + ')',
            amount: total,
            isExact: true
        });

        if (total > 0) {
            // 2. Pembulatan ke kelipatan 5.000 terdekat ke atas
            const round5k = Math.ceil(total / 5000) * 5000;
            if (round5k > total && !options.some(o => o.amount === round5k)) {
                options.push({
                    label: formatNumberDot(round5k),
                    amount: round5k
                });
            }

            // 3. Pembulatan ke kelipatan 10.000 terdekat ke atas
            const round10k = Math.ceil(total / 10000) * 10000;
            if (round10k > total && !options.some(o => o.amount === round10k)) {
                options.push({
                    label: formatNumberDot(round10k),
                    amount: round10k
                });
            }

            // 4. Pembulatan ke kelipatan 20.000 terdekat ke atas
            const round20k = Math.ceil(total / 20000) * 20000;
            if (round20k > total && !options.some(o => o.amount === round20k)) {
                options.push({
                    label: formatNumberDot(round20k),
                    amount: round20k
                });
            }

            // 5. Pembulatan ke kelipatan 50.000 terdekat ke atas
            const round50k = Math.ceil(total / 50000) * 50000;
            if (round50k > total && !options.some(o => o.amount === round50k)) {
                options.push({
                    label: formatNumberDot(round50k),
                    amount: round50k
                });
            }

            // 6. Pembulatan ke kelipatan 100.000 terdekat ke atas
            const round100k = Math.ceil(total / 100000) * 100000;
            if (round100k > total && !options.some(o => o.amount === round100k)) {
                options.push({
                    label: formatNumberDot(round100k),
                    amount: round100k
                });
            }

            // 7. Tambahkan opsi pecahan uang umum jika opsi masih kurang dari 4
            const standardDenoms = [10000, 20000, 50000, 100000, 200000, 500000];
            for (const denom of standardDenoms) {
                if (denom > total && !options.some(o => o.amount === denom)) {
                    options.push({
                        label: formatNumberDot(denom),
                        amount: denom
                    });
                }
                if (options.length >= 4) break;
            }
        }

        // Batasi maksimal 4 tombol cepat terbaik
        const finalOptions = options.slice(0, 4);

        finalOptions.forEach(opt => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.onclick = () => setCashAmount(opt.amount);
            btn.className = opt.isExact 
                ? 'py-1.5 px-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 font-bold text-xs border border-emerald-300 dark:border-emerald-700 active:scale-95 transition-all text-center truncate'
                : 'py-1.5 px-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-xs border border-slate-200 dark:border-slate-700 active:scale-95 transition-all text-center truncate';
            btn.innerText = opt.label;
            container.appendChild(btn);
        });
    }

    function closeCheckoutModal() {
        document.getElementById('modal-checkout').classList.add('hidden');
    }

    function handleCashInput(val) {
        const cash = parseNumberDot(val);
        const cashDisplay = document.getElementById('co-input-cash-display');
        if (cashDisplay) {
            cashDisplay.value = cash > 0 ? formatNumberDot(cash) : '';
        }
        document.getElementById('co-input-cash').value = cash;
        calculateChange();
    }

    function setCashAmount(nominal) {
        const num = Math.round(Number(nominal) || 0);
        const cashDisplay = document.getElementById('co-input-cash-display');
        if (cashDisplay) {
            cashDisplay.value = num > 0 ? formatNumberDot(num) : '';
        }
        document.getElementById('co-input-cash').value = num;
        calculateChange();
    }

    function calculateChange() {
        const cash = parseFloat(document.getElementById('co-input-cash').value) || 0;
        const netTotal = Math.max(0, totalBillAmount - currentDiscount);
        const kembalian = cash - netTotal;
        const dispKembalian = document.getElementById('co-display-kembalian');
        const submitBtn = document.getElementById('btn-finish-checkout');

        if (kembalian >= 0) {
            dispKembalian.className = 'h-10 px-3 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 font-extrabold text-sm md:text-base text-emerald-700 dark:text-emerald-300 flex items-center truncate';
            dispKembalian.innerText = formatRupiah(kembalian);
            submitBtn.disabled = false;
            submitBtn.className = 'w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-extrabold text-sm transition-all shadow-md shadow-emerald-600/30 flex items-center justify-center gap-2 cursor-pointer mt-1';
        } else {
            dispKembalian.className = 'h-10 px-3 rounded-lg bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 font-extrabold text-sm md:text-base text-rose-600 dark:text-rose-400 flex items-center truncate';
            dispKembalian.innerText = 'Kurang: ' + formatRupiah(Math.abs(kembalian));
            submitBtn.disabled = true;
            submitBtn.className = 'w-full py-2.5 px-4 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-600 font-extrabold text-sm transition-all flex items-center justify-center gap-2 cursor-not-allowed mt-1';
        }
    }

    function formatRupiah(amount) {
        return 'Rp ' + Number(amount).toLocaleString('id-ID');
    }

    // Toggle minimize/expand Daily Summary Widget
    function toggleDailySummary() {
        const container = document.getElementById('summaryCardsContainer');
        const label = document.getElementById('summaryToggleLabel');
        const icon = document.getElementById('summaryToggleIcon');
        if (!container) return;

        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            if (label) label.innerText = 'Sembunyikan';
            if (icon) icon.className = 'fa-solid fa-chevron-up text-[10px] transition-transform';
            localStorage.setItem('kasir_summary_collapsed', '0');
        } else {
            container.classList.add('hidden');
            if (label) label.innerText = 'Tampilkan';
            if (icon) icon.className = 'fa-solid fa-chevron-down text-[10px] transition-transform';
            localStorage.setItem('kasir_summary_collapsed', '1');
        }
    }

    // Restore state on page load (Default: ringkasan tertutup agar ruang POS maksimal)
    document.addEventListener('DOMContentLoaded', () => {
        const savedState = localStorage.getItem('kasir_summary_collapsed');
        if (savedState === '0') {
            const container = document.getElementById('summaryCardsContainer');
            const label = document.getElementById('summaryToggleLabel');
            const icon = document.getElementById('summaryToggleIcon');
            if (container) container.classList.remove('hidden');
            if (label) label.innerText = 'Sembunyikan';
            if (icon) icon.className = 'fa-solid fa-chevron-up text-[10px] transition-transform';
        }
    });
</script>

<?php $content = ob_get_clean(); ?>