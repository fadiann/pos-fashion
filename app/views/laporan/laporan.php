<?php 
ob_start();
if (!isset($bulan)) { $bulan = date('m'); }
if (!isset($tahun)) { $tahun = date('Y'); }

// Hitung Ringkasan Metrik Hari Ini
$totalHariIni = 0;
$totalItemHariIni = 0;
$totalCashHariIni = 0;
$totalDigitalHariIni = 0;

if (!empty($viewlistToday)) {
    foreach ($viewlistToday as $rowT) {
        $totalHariIni += (float)($rowT['total_pembayaran'] ?? 0);
        if (($rowT['jenis_pembayaran'] ?? 1) == 1) {
            $totalCashHariIni += (float)($rowT['total_pembayaran'] ?? 0);
        } else {
            $totalDigitalHariIni += (float)($rowT['total_pembayaran'] ?? 0);
        }
    }
}
?>

<div class="space-y-4 pb-12">
    <!-- TOP HEADER & CONTROLS -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 md:p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-base border border-emerald-200 dark:border-emerald-800">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Laporan Penjualan</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Rekap transaksi kasir, tren produk terlaris, dan rincian omset bulanan</p>
            </div>
        </div>

        <!-- FORM FILTER BULAN & TAHUN -->
        <form action="" method="post" class="flex flex-wrap items-center gap-2">
            <div class="flex items-center gap-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2.5 py-1.5">
                <i class="fa-regular fa-calendar text-slate-400 text-xs"></i>
                <select name="bulan" class="bg-transparent text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none cursor-pointer">
                    <?php
                    $bln = array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                    for($month=1; $month<=12; $month++){
                        $sel = ($month == (int)$bulan) ? 'selected' : '';
                        echo "<option value='$month' $sel class='dark:bg-slate-900'>$bln[$month]</option>"; 
                    }
                    ?>
                </select>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <select name="tahun" class="bg-transparent text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none cursor-pointer">
                    <?php
                    for($year=2021; $year <= (int)date('Y') + 1; $year++){
                        $sel = ($year == (int)$tahun) ? 'selected' : '';
                        echo "<option value='$year' $sel class='dark:bg-slate-900'>$year</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" name="filter" class="px-3.5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1.5 shadow-sm transition-all active:scale-95">
                <i class="fa-solid fa-filter text-xs"></i>
                <span>Terapkan</span>
            </button>

            <button type="button" onclick="window.print()" class="px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs flex items-center gap-1.5 transition-colors no-print" title="Cetak Laporan">
                <i class="fa-solid fa-print text-xs"></i>
                <span class="hidden sm:inline">Print</span>
            </button>
        </form>
    </div>

    <!-- 3 KARTU RINGKASAN HARI INI -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="rounded-xl p-3.5 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-bold uppercase text-slate-500 dark:text-slate-400">Total Omset Hari Ini</span>
                <span class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-receipt"></i>
                </span>
            </div>
            <div class="text-lg xl:text-xl font-extrabold text-slate-900 dark:text-white">
                Rp <?= $Helpers->rupiah($totalHariIni) ?>
            </div>
            <span class="text-[10px] text-slate-400"><?= count($viewlistToday) ?> transaksi berhasil</span>
        </div>

        <div class="rounded-xl p-3.5 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-bold uppercase text-slate-500 dark:text-slate-400">Kas Tunai Masuk</span>
                <span class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </span>
            </div>
            <div class="text-lg xl:text-xl font-extrabold text-slate-900 dark:text-white">
                Rp <?= $Helpers->rupiah($totalCashHariIni) ?>
            </div>
            <span class="text-[10px] text-slate-400">Pembayaran tunai di kasir</span>
        </div>

        <div class="rounded-xl p-3.5 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-bold uppercase text-slate-500 dark:text-slate-400">Pembayaran Non Tunai</span>
                <span class="w-7 h-7 rounded-lg bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-qrcode"></i>
                </span>
            </div>
            <div class="text-lg xl:text-xl font-extrabold text-sky-600 dark:text-sky-400">
                Rp <?= $Helpers->rupiah($totalDigitalHariIni) ?>
            </div>
            <span class="text-[10px] text-slate-400">QRIS, Transfer, Debit / E-Money</span>
        </div>
    </div>

    <!-- TAB NAVIGATION LAPORAN -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-1.5 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap gap-1.5">
        <button type="button" onclick="switchReportTab('today')" id="rep-btn-today" class="report-tab-btn active px-3.5 py-2 rounded-lg text-xs font-bold transition-all bg-emerald-600 text-white shadow-sm flex items-center gap-1.5">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>Transaksi Hari Ini</span>
            <span class="bg-emerald-800 text-emerald-100 text-[10px] px-1.5 py-0.2 rounded-full"><?= count($viewlistToday) ?></span>
        </button>

        <button type="button" onclick="switchReportTab('bulanan')" id="rep-btn-bulanan" class="report-tab-btn px-3.5 py-2 rounded-lg text-xs font-semibold transition-all bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-1.5">
            <i class="fa-solid fa-calendar-days text-slate-400"></i>
            <span>Rekap Bulanan (<?= htmlspecialchars($bln[(int)$bulan]) ?> <?= $tahun ?>)</span>
        </button>

        <button type="button" onclick="switchReportTab('produk')" id="rep-btn-produk" class="report-tab-btn px-3.5 py-2 rounded-lg text-xs font-semibold transition-all bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-1.5">
            <i class="fa-solid fa-boxes-stacked text-slate-400"></i>
            <span>Penjualan per Produk</span>
        </button>

        <button type="button" onclick="switchReportTab('trend')" id="rep-btn-trend" class="report-tab-btn px-3.5 py-2 rounded-lg text-xs font-semibold transition-all bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-1.5">
            <i class="fa-solid fa-fire text-amber-500"></i>
            <span>Produk Terlaris</span>
        </button>
    </div>

    <!-- TAB 1: TRANSAKSI HARI INI -->
    <div id="rep-content-today" class="report-content-panel space-y-3">
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-white mb-0.5">Rincian Penjualan Hari Ini</h3>
                    <p class="text-[11px] text-slate-400 mb-0"><?= date('l, d F Y') ?></p>
                </div>
                <span class="text-xs font-extrabold text-emerald-600 dark:text-emerald-400">
                    Total: Rp <?= $Helpers->rupiah($totalHariIni) ?>
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-800/80 text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold border-b border-slate-200 dark:border-slate-700 select-none">
                        <tr>
                            <th class="px-4 py-2.5 text-center" style="width: 50px;">No.</th>
                            <th class="px-4 py-2.5">Kode Transaksi</th>
                            <th class="px-4 py-2.5">Pelanggan</th>
                            <th class="px-4 py-2.5">Item Terjual</th>
                            <th class="px-4 py-2.5 text-center">Metode Bayar</th>
                            <th class="px-4 py-2.5 text-right">Total Tagihan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php 
                        $noToday = 1;
                        if (!empty($viewlistToday)):
                            foreach($viewlistToday as $row): 
                                $payLabel = 'TUNAI';
                                $payBadge = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800';
                                if (($row['jenis_pembayaran'] ?? 1) != 1) {
                                    $payLabel = 'NON TUNAI';
                                    $payBadge = 'bg-sky-50 text-sky-700 dark:bg-sky-950/60 dark:text-sky-400 border-sky-200 dark:border-sky-800';
                                }
                        ?>
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-4 py-2.5 text-center font-bold text-slate-400"><?= $noToday++ ?></td>
                            <td class="px-4 py-2.5 font-mono font-bold text-slate-900 dark:text-white">
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-[11px]">
                                    <?= htmlspecialchars($row['transaksi_id']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-2.5 font-semibold text-slate-800 dark:text-slate-200">
                                <?= htmlspecialchars($row['pelanggan_nama'] ?? 'Pelanggan Umum') ?>
                            </td>
                            <td class="px-4 py-2.5">
                                <div class="space-y-1">
                                    <?php
                                    $items = $modelTransaksi->getPesanan($row['transaksi_id']);
                                    if (!empty($items)):
                                        foreach($items as $item):
                                    ?>
                                        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-[11px] mr-1 mb-1">
                                            <span class="font-bold text-slate-800 dark:text-slate-200"><?= htmlspecialchars($item['nama_barang']) ?></span>
                                            <span class="text-slate-400">[Sz.<?= $item['size_nama'] ?>]</span>
                                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">&times; <?= $item['jumlah'] ?></span>
                                        </div>
                                    <?php 
                                        endforeach;
                                    endif;
                                    ?>
                                </div>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold border <?= $payBadge ?>">
                                    <?= $payLabel ?>
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-right font-extrabold text-slate-900 dark:text-white">
                                <?= $Helpers->rp($row['total_pembayaran']) ?>
                            </td>
                        </tr>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-receipt text-3xl mb-2 text-slate-300 dark:text-slate-600"></i>
                                <p class="text-xs">Belum ada transaksi penjualan tercatat hari ini.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="bg-slate-50/90 dark:bg-slate-800/90 font-bold border-t border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white">
                        <tr>
                            <td colspan="5" class="px-4 py-2.5 text-right uppercase text-[10px] tracking-wider">TOTAL PENJUALAN HARI INI :</td>
                            <td class="px-4 py-2.5 text-right text-emerald-600 dark:text-emerald-400 font-extrabold text-sm">
                                <?= $Helpers->rp($totalHariIni) ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: REKAP BULANAN -->
    <div id="rep-content-bulanan" class="report-content-panel space-y-3 hidden">
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-white mb-0.5">Rekap Omset Harian per Bulan</h3>
                    <p class="text-[11px] text-slate-400 mb-0">Periode: <?= htmlspecialchars($bln[(int)$bulan]) ?> <?= $tahun ?></p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-800/80 text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold border-b border-slate-200 dark:border-slate-700 select-none">
                        <tr>
                            <th class="px-3 py-2.5 text-center" style="width: 45px;">No.</th>
                            <th class="px-3 py-2.5">Tanggal</th>
                            <th class="px-3 py-2.5 text-right">Tunai</th>
                            <th class="px-3 py-2.5 text-right">Non Tunai</th>
                            <th class="px-3 py-2.5 text-right">Total HPP</th>
                            <th class="px-3 py-2.5 text-right">Laba Bersih</th>
                            <th class="px-3 py-2.5 text-right">Promo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php 
                        $noBln = 1;
                        $t_cash = 0; $t_nontunai = 0; $t_hpp = 0; $t_laba = 0; $t_diskon = 0;
                        if (!empty($viewOmsetBulanan)):
                            foreach($viewOmsetBulanan as $row): 
                                // Non Tunai mencakup jenis_pembayaran != 1 (misal 2: QRIS/Non Tunai atau data lama jenis 3)
                                $debet        = $model->totalJenisByDay($row['tanggal'], 3);
                                $emoney       = $model->totalJenisByDay($row['tanggal'], 2);
                                $non_tunai    = $debet + $emoney;
                                $hpp          = $model->totalHppByDay($row['tanggal']);
                                $t_hpp       += $hpp;
                                $t_nontunai  += $non_tunai;
                                
                                $cash         = $row['total_pembayaran'] - $non_tunai;
                                $t_cash      += $cash;
                                $omset        = $cash + $non_tunai;
                                $laba         = $omset - $hpp;
                                $t_laba      += $laba;
                                $sub_diskon   = (float)($row['total_promo'] ?? 0);
                                $t_diskon    += $sub_diskon;
                        ?>
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-3 py-2.5 text-center text-slate-400 font-bold"><?= $noBln++ ?></td>
                            <td class="px-3 py-2.5 font-semibold text-slate-900 dark:text-white">
                                <?= $Helpers->tanggal_indo($row['tanggal'], true) ?>
                            </td>
                            <td class="px-3 py-2.5 text-right font-medium"><?= $Helpers->rp($cash) ?></td>
                            <td class="px-3 py-2.5 text-right font-medium text-sky-600 dark:text-sky-400"><?= $Helpers->rp($non_tunai) ?></td>
                            <td class="px-3 py-2.5 text-right font-medium text-slate-400"><?= $Helpers->rp($hpp) ?></td>
                            <td class="px-3 py-2.5 text-right font-bold text-emerald-600 dark:text-emerald-400"><?= $Helpers->rp($laba) ?></td>
                            <td class="px-3 py-2.5 text-right font-medium text-rose-500"><?= $Helpers->rp($sub_diskon) ?></td>
                        </tr>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-calendar-xmark text-3xl mb-2 text-slate-300 dark:text-slate-600"></i>
                                <p class="text-xs">Tidak ada riwayat penjualan pada bulan ini.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="bg-slate-50/90 dark:bg-slate-800/90 font-bold border-t-2 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white">
                        <tr>
                            <td colspan="2" class="px-3 py-2.5 text-right uppercase text-[10px] tracking-wider">TOTAL BULAN INI :</td>
                            <td class="px-3 py-2.5 text-right"><?= $Helpers->rp($t_cash) ?></td>
                            <td class="px-3 py-2.5 text-right text-sky-600 dark:text-sky-400"><?= $Helpers->rp($t_nontunai) ?></td>
                            <td class="px-3 py-2.5 text-right text-slate-400"><?= $Helpers->rp($t_hpp) ?></td>
                            <td class="px-3 py-2.5 text-right text-emerald-600 dark:text-emerald-400 text-sm"><?= $Helpers->rp($t_laba) ?></td>
                            <td class="px-3 py-2.5 text-right text-rose-500"><?= $Helpers->rp($t_diskon) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 3: PENJUALAN PER PRODUK -->
    <div id="rep-content-produk" class="report-content-panel space-y-3 hidden">
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-white mb-0.5">Rincian Penjualan per Produk</h3>
                    <p class="text-[11px] text-slate-400 mb-0">Item dan ukuran produk yang terjual per tanggal</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-800/80 text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold border-b border-slate-200 dark:border-slate-700 select-none">
                        <tr>
                            <th class="px-4 py-2.5 text-center" style="width: 50px;">No.</th>
                            <th class="px-4 py-2.5" style="width: 220px;">Tanggal</th>
                            <th class="px-4 py-2.5">Rincian Produk Terjual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php 
                        $noPrd = 1;
                        if (!empty($viewOmsetBulanan)):
                            foreach($viewOmsetBulanan as $row): 
                                $itemsByDay = $modelTransaksi->getPesananByDay($row['tanggal']);
                        ?>
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-4 py-3 text-center font-bold text-slate-400"><?= $noPrd++ ?></td>
                            <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white align-top">
                                <?= $Helpers->tanggal_indo($row['tanggal'], true) ?>
                            </td>
                            <td class="px-4 py-3">
                                <?php if (!empty($itemsByDay)): ?>
                                    <div class="space-y-1.5">
                                        <?php foreach($itemsByDay as $it): ?>
                                            <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700 text-xs">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-6 h-6 rounded bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-[10px]">
                                                        <i class="fa-solid fa-box"></i>
                                                    </span>
                                                    <span class="font-bold text-slate-800 dark:text-slate-100"><?= htmlspecialchars($it['nama_barang']) ?></span>
                                                    <span class="text-[11px] text-slate-400 bg-slate-100 dark:bg-slate-700 px-1.5 py-0.5 rounded"><?= htmlspecialchars($it['kategori_nama']) ?></span>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Size: <strong class="text-slate-700 dark:text-slate-200"><?= $it['size_nama'] ?></strong></span>
                                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-extrabold text-[11px]">
                                                        <?= $it['total_item'] ?> pcs
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-400 italic">Tidak ada item</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-boxes-packing text-3xl mb-2 text-slate-300 dark:text-slate-600"></i>
                                <p class="text-xs">Tidak ada data penjualan produk pada bulan ini.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 4: TREND PRODUK TERLARIS -->
    <div id="rep-content-trend" class="report-content-panel space-y-3 hidden">
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-white mb-0.5">Peringkat Produk Paling Laris</h3>
                    <p class="text-[11px] text-slate-400 mb-0">Urutan produk berdasarkan kuantitas total penjualan bulan ini</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-800/80 text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold border-b border-slate-200 dark:border-slate-700 select-none">
                        <tr>
                            <th class="px-4 py-2.5 text-center" style="width: 60px;">Rank</th>
                            <th class="px-4 py-2.5">Nama Produk</th>
                            <th class="px-4 py-2.5">Kategori</th>
                            <th class="px-4 py-2.5 text-right" style="width: 150px;">Total Terjual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php 
                        $rank = 1;
                        if (!empty($trends)):
                            foreach($trends as $tr): 
                                $rankBadge = 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';
                                if ($rank == 1) $rankBadge = 'bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border border-amber-300';
                                elseif ($rank == 2) $rankBadge = 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200';
                                elseif ($rank == 3) $rankBadge = 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-400';
                        ?>
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-4 py-3 text-center">
                                <span class="w-6 h-6 rounded-full inline-flex items-center justify-center font-bold text-xs <?= $rankBadge ?>">
                                    <?= $rank++ ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-box"></i>
                                    </span>
                                    <span><?= htmlspecialchars($tr['nama_barang']) ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[11px] font-semibold">
                                    <?= htmlspecialchars($tr['kategori_nama']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-extrabold text-xs">
                                    <?= $tr['jumlah'] ?> pcs
                                </span>
                            </td>
                        </tr>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-chart-pie text-3xl mb-2 text-slate-300 dark:text-slate-600"></i>
                                <p class="text-xs">Belum ada data penjualan tercatat pada periode ini.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- CLIENT TAB SWITCHER SCRIPT -->
<script>
function switchReportTab(tabName) {
    // Hide all panels
    document.querySelectorAll('.report-content-panel').forEach(panel => {
        panel.classList.add('hidden');
    });

    // Reset button styles
    document.querySelectorAll('.report-tab-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-emerald-600', 'text-white', 'shadow-sm');
        btn.classList.add('bg-slate-100', 'dark:bg-slate-800', 'text-slate-700', 'dark:text-slate-300');
    });

    // Show active panel
    const activePanel = document.getElementById('rep-content-' + tabName);
    if (activePanel) {
        activePanel.classList.remove('hidden');
    }

    // Set active button
    const activeBtn = document.getElementById('rep-btn-' + tabName);
    if (activeBtn) {
        activeBtn.classList.add('active', 'bg-emerald-600', 'text-white', 'shadow-sm');
        activeBtn.classList.remove('bg-slate-100', 'dark:bg-slate-800', 'text-slate-700', 'dark:text-slate-300');
    }
}
</script>

<?php $content = ob_get_clean(); ?>