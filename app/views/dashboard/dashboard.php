<?php 
ob_start();
require_once models . "LaporanModel.php";
$laporan           = new LaporanModel();  
$omsetBelumDiBayar = $laporan->omsetBelumDiBayar();
$omset_cash        = $laporan->omset_harian('1');
$omset_nontunai    = $laporan->omset_harian('2') + $laporan->omset_harian('3');
$omset_promo       = $laporan->total_promo('all');
$total_hari_ini    = $omsetBelumDiBayar + $omset_cash + $omset_nontunai;

// Perhitungan Aset Toko
$total_aset_hpp  = $Laporan->getTotalAset()['total_aset_hpp'] ?? 0;
$total_aset_jual = $Laporan->getTotalAset()['total_aset_jual'] ?? 0;
$total_sold_hpp  = $Laporan->getTotalAsetSold()['total_aset_hpp'] ?? 0;
$total_sold_jual = $Laporan->getTotalAsetSold()['total_aset_jual'] ?? 0;
$total_out_hpp   = $Laporan->getTotalAsetOut()['total_aset_hpp'] ?? 0;
$total_out_jual  = $Laporan->getTotalAsetOut()['total_aset_jual'] ?? 0;

$sisa_aset_hpp   = $total_aset_hpp - $total_sold_hpp - $total_out_hpp;
$sisa_aset_jual  = $total_aset_jual - $total_sold_jual - $total_out_jual;
$sisa_keuntungan = $sisa_aset_jual - $sisa_aset_hpp;

// Kategori Data
$kategoriAsetList = $Laporan->getTotalAsetByKategori() ?? [];

// User session
$currentUser = strtoupper($_SESSION['username'] ?? 'KASIR');
?>

<div class="space-y-4 pb-16 md:pb-8">
    <!-- ACCESS DENIED ALERT -->
    <?php if (isset($_GET['access_denied']) && $_GET['access_denied'] === 'admin_only'): ?>
        <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs font-semibold flex items-center justify-between shadow-sm animate-in fade-in duration-150">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-shield-halved text-rose-500 text-base"></i>
                <div>
                    <span class="font-bold">Akses Ditolak:</span>
                    <span>Modul tersebut adalah menu <strong>Pengaturan Sistem</strong> khusus Administrator.</span>
                </div>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- HERO GREETING & QUICK ACTION CTA (COMPACT) -->
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-emerald-600 via-teal-600 to-slate-900 dark:from-emerald-950 dark:via-slate-900 dark:to-slate-950 p-4 md:p-5 text-white shadow-md border border-emerald-500/20">
        <!-- Background Ambient Glow -->
        <div class="absolute -top-16 -right-16 w-48 h-48 bg-emerald-400/20 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-teal-400/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/10 backdrop-blur-md text-[11px] font-semibold tracking-wide text-emerald-200 border border-white/10">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                    POINT OF SALE DASHBOARD
                </div>
                <h1 class="text-lg md:text-xl font-bold tracking-tight">
                    Selamat Datang, <span class="text-emerald-300 font-extrabold"><?= $currentUser ?></span>! 👋
                </h1>
                <p class="text-xs text-slate-200 dark:text-slate-300 max-w-lg leading-relaxed">
                    Pantau kinerja penjualan harian, omset kasir, dan valuasi aset persediaan produk secara realtime.
                </p>
            </div>
            <div class="flex items-center gap-2 pt-1 sm:pt-0">
                <a href="index.php?main=transaksi" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-white text-emerald-700 hover:bg-emerald-50 active:scale-95 font-bold shadow-md shadow-black/10 transition-all text-xs md:text-sm">
                    <i class="fa-solid fa-cash-register text-sm"></i>
                    <span>Buka Kasir</span>
                </a>
                <a href="index.php?main=laporan" class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-lg bg-white/15 hover:bg-white/20 active:scale-95 text-white font-semibold backdrop-blur-md border border-white/15 transition-all text-xs">
                    <i class="fa-solid fa-file-invoice text-xs"></i>
                    <span>Laporan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 3 KARTU UTAMA: VALUASI NILAI ASET (COMPACT) -->
    <div>
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                <i class="fa-solid fa-vault text-emerald-500"></i>
                Ringkasan Nilai Aset Inventaris
            </h2>
            <span class="text-[11px] text-slate-400">Update Stok Berjalan</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <!-- Nilai Aset Beli (HPP) -->
            <div class="relative overflow-hidden rounded-xl p-3.5 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase text-slate-500 dark:text-slate-400">Aset Beli (HPP)</span>
                    <span class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </span>
                </div>
                <div class="mt-1.5">
                    <div class="text-lg xl:text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                        <span class="text-xs font-semibold text-slate-400 mr-1">Rp</span><?= $Helpers->rupiah($sisa_aset_hpp) ?>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Modal persediaan saat ini</p>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-500"></div>
            </div>

            <!-- Nilai Aset Jual -->
            <div class="relative overflow-hidden rounded-xl p-3.5 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase text-slate-500 dark:text-slate-400">Total Nilai Jual</span>
                    <span class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-tags"></i>
                    </span>
                </div>
                <div class="mt-1.5">
                    <div class="text-lg xl:text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                        <span class="text-xs font-semibold text-slate-400 mr-1">Rp</span><?= $Helpers->rupiah($sisa_aset_jual) ?>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Perputaran harga jual katalog</p>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-emerald-500"></div>
            </div>

            <!-- Potensi Margin Keuntungan -->
            <div class="relative overflow-hidden rounded-xl p-3.5 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase text-slate-500 dark:text-slate-400">Potensi Keuntungan</span>
                    <span class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-chart-line"></i>
                    </span>
                </div>
                <div class="mt-1.5">
                    <div class="text-lg xl:text-xl font-extrabold text-purple-600 dark:text-purple-400 tracking-tight">
                        <span class="text-xs font-semibold text-purple-400 mr-1">Rp</span><?= $Helpers->rupiah($sisa_keuntungan) ?>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Margin bruto stok aktif</p>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-purple-500"></div>
            </div>
        </div>
    </div>

    <!-- 4 KARTU METRIK OMSET HARI INI (COMPACT) -->
    <div>
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                <i class="fa-solid fa-receipt text-blue-500"></i>
                Omset Hari Ini (<?= date('d M Y') ?>)
            </h2>
            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                Total: Rp <?= $Helpers->rupiah($total_hari_ini) ?>
            </span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <!-- Omset Cash / Tunai -->
            <div class="rounded-xl p-3 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Pembayaran Tunai</span>
                    <span class="w-6 h-6 rounded-md bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-[10px]">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </span>
                </div>
                <div class="text-base font-bold text-slate-900 dark:text-white">
                    Rp <?= $Helpers->rupiah($omset_cash) ?>
                </div>
                <span class="text-[10px] text-slate-400">Kas Tunai Masuk</span>
            </div>

            <!-- Omset Non Tunai -->
            <div class="rounded-xl p-3 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Pembayaran Non Tunai</span>
                    <span class="w-6 h-6 rounded-md bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 flex items-center justify-center text-[10px]">
                        <i class="fa-solid fa-qrcode"></i>
                    </span>
                </div>
                <div class="text-base font-bold text-slate-900 dark:text-white">
                    Rp <?= $Helpers->rupiah($omset_nontunai) ?>
                </div>
                <span class="text-[10px] text-slate-400">QRIS / Transfer / Debit</span>
            </div>

            <!-- Total Promo -->
            <div class="rounded-xl p-3 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Diskon Promo</span>
                    <span class="w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 flex items-center justify-center text-[10px]">
                        <i class="fa-solid fa-ticket"></i>
                    </span>
                </div>
                <div class="text-base font-bold text-rose-600 dark:text-rose-400">
                    Rp <?= $Helpers->rupiah($omset_promo) ?>
                </div>
                <span class="text-[10px] text-slate-400">Potongan Diberikan</span>
            </div>
        </div>
    </div>

    <!-- ROW: GRAFIK TREN OMSET & AKSES CEPAT (COMPACT) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        <!-- Grafik Tren Omset Bulanan -->
        <div class="lg:col-span-8 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 mb-3 pb-2.5 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-1.5">
                        <i class="fa-solid fa-chart-column text-emerald-500"></i>
                        Grafik Penjualan Bulan Ini
                    </h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">
                        Periode <?= date('F Y') ?>
                    </p>
                </div>
                <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 text-[11px] font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Omset Harian (Rp)
                </div>
            </div>
            
            <div class="relative w-full h-[250px]">
                <canvas id="grafik_omset_bulanan"></canvas>
            </div>
        </div>

        <!-- Akses Cepat Modul (Compact) -->
        <div class="lg:col-span-4">
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-sm h-full flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2.5 flex items-center gap-1.5">
                        <i class="fa-solid fa-bolt text-amber-500"></i>
                        Akses Cepat Modul
                    </h3>
                    <div class="space-y-2">
                        <a href="index.php?main=barang" class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/80 border border-slate-100 dark:border-slate-800 transition-colors group">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs group-hover:scale-105 transition-transform">
                                    <i class="fa-solid fa-box-archive"></i>
                                </span>
                                <div>
                                    <div class="text-xs font-bold text-slate-800 dark:text-slate-200">Katalog Produk</div>
                                    <div class="text-[10px] text-slate-400">Master data produk & ukuran</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                        </a>

                        <a href="index.php?main=stok" class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/80 border border-slate-100 dark:border-slate-800 transition-colors group">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs group-hover:scale-105 transition-transform">
                                    <i class="fa-solid fa-boxes-packing"></i>
                                </span>
                                <div>
                                    <div class="text-xs font-bold text-slate-800 dark:text-slate-200">Stok Masuk / Keluar</div>
                                    <div class="text-[10px] text-slate-400">Manajemen pergerakan gudang</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                        </a>

                        <a href="index.php?main=pelanggan" class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/80 border border-slate-100 dark:border-slate-800 transition-colors group">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs group-hover:scale-105 transition-transform">
                                    <i class="fa-solid fa-users"></i>
                                </span>
                                <div>
                                    <div class="text-xs font-bold text-slate-800 dark:text-slate-200">Data Pelanggan</div>
                                    <div class="text-[10px] text-slate-400">Daftar customer & kontak</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL TOTAL NILAI ASET PER KATEGORI (COMPACT) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3 pb-2.5 border-b border-slate-100 dark:border-slate-800">
            <div>
                <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-1.5">
                    <i class="fa-solid fa-layer-group text-emerald-500"></i>
                    Total Nilai Aset per Kategori Produk
                </h3>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">Distribusi nilai modal persediaan per kategori produk</p>
            </div>
            <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-medium">
                <?= count($kategoriAsetList) ?> Kategori Terdaftar
            </span>
        </div>

        <div class="overflow-x-auto rounded-lg border border-slate-100 dark:border-slate-800">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-800/80 text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="px-4 py-2.5">Kategori Produk</th>
                        <th class="px-4 py-2.5 text-right">Total Aset (HPP Sisa)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php 
                    $total_hpp_asset_per_produk = 0;
                    if (!empty($kategoriAsetList)):
                        foreach($kategoriAsetList as $kategori): 
                            $soldAset = $Laporan->getTotalAsetSoldByProduk($kategori['kategori_id'])['total_aset_hpp'] ?? 0;
                            $outAset  = $Laporan->getTotalAsetOutByProduk($kategori['kategori_id'])['total_aset_hpp'] ?? 0;
                            $hpp_asset_per_produk = ($kategori['total_aset_hpp'] ?? 0) - ($soldAset + $outAset);
                            $total_hpp_asset_per_produk += $hpp_asset_per_produk;
                    ?>
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-4 py-2.5 font-medium text-slate-900 dark:text-slate-200 flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-md bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px] text-slate-500">
                                <i class="fa-solid fa-tag"></i>
                            </span>
                            <span><?= htmlspecialchars($kategori['kategori_nama']) ?></span>
                        </td>
                        <td class="px-4 py-2.5 text-right font-bold text-slate-900 dark:text-slate-100">
                            <?= $Helpers->rp($hpp_asset_per_produk) ?>
                        </td>
                    </tr>
                    <?php 
                        endforeach; 
                    else:
                    ?>
                    <tr>
                        <td colspan="2" class="px-4 py-4 text-center text-slate-400">Belum ada data kategori aset</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="bg-slate-50/90 dark:bg-slate-800/90 font-bold border-t border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white">
                    <tr>
                        <td class="px-4 py-2.5 text-right uppercase text-[10px] tracking-wider">TOTAL KESELURUHAN ASET :</td>
                        <td class="px-4 py-2.5 text-right text-emerald-600 dark:text-emerald-400 font-extrabold text-sm">
                            <?= $Helpers->rp($total_hpp_asset_per_produk) ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- SCRIPT CHART.JS OMSET BULANAN (ADAPTIF THEME) -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    var chartCanvas = document.getElementById('grafik_omset_bulanan');
    if (!chartCanvas) return;

    var isDark = document.documentElement.classList.contains('dark');
    var textColor = isDark ? '#94a3b8' : '#64748b';
    var gridColor = isDark ? '#1e293b' : '#f1f5f9';

    var ctx = chartCanvas.getContext('2d');
    var gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.45)');
    gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

    var chartData = {
        labels: <?= json_encode($Laporan->labelGrafikOmsetByDay(date('m'))) ?>,
        datasets: [{
            label: "Omset Penjualan",
            borderColor: "#10b981",
            backgroundColor: gradient,
            pointBackgroundColor: "#10b981",
            pointBorderColor: isDark ? "#0f172a" : "#ffffff",
            pointBorderWidth: 2,
            pointRadius: 3,
            pointHoverRadius: 6,
            borderWidth: 2.5,
            fill: true,
            tension: 0.35,
            data: <?= json_encode($Laporan->dataGrafikOmsetByDay(date('m'))) ?>
        }]
    };

    var myChart = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: isDark ? '#0f172a' : '#1e293b',
                titleFontColor: '#f8fafc',
                bodyFontColor: '#34d399',
                bodyFontSize: 13,
                titleFontSize: 12,
                cornerRadius: 8,
                xPadding: 12,
                yPadding: 10,
                callbacks: {
                    label: function(tooltipItem) {
                        var val = Number(tooltipItem.yLabel) || 0;
                        return 'Omset: Rp ' + val.toLocaleString('id-ID');
                    }
                }
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        fontColor: textColor,
                        fontSize: 11,
                        maxTicksLimit: 10
                    }
                }],
                yAxes: [{
                    gridLines: {
                        color: gridColor,
                        drawBorder: false
                    },
                    ticks: {
                        fontColor: textColor,
                        fontSize: 11,
                        beginAtZero: true,
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(0) + 'k';
                            }
                            return 'Rp ' + value;
                        }
                    }
                }]
            }
        }
    });

    // Observer Theme Change agar warna grafik menyesuaikan seketika
    var themeObserver = new MutationObserver(function() {
        var darkNow = document.documentElement.classList.contains('dark');
        var newText = darkNow ? '#94a3b8' : '#64748b';
        var newGrid = darkNow ? '#1e293b' : '#f1f5f9';

        myChart.options.scales.xAxes[0].ticks.fontColor = newText;
        myChart.options.scales.yAxes[0].ticks.fontColor = newText;
        myChart.options.scales.yAxes[0].gridLines.color = newGrid;
        myChart.options.tooltips.backgroundColor = darkNow ? '#0f172a' : '#1e293b';
        myChart.data.datasets[0].pointBorderColor = darkNow ? "#0f172a" : "#ffffff";
        myChart.update();
    });

    themeObserver.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
});
</script>

<?php $content = ob_get_clean(); ?>