<?php
ob_start();
// View template untuk Log & Audit Sistem
?>

<div class="space-y-5 pb-16">
    <!-- FLASH MESSAGE -->
    <?php if ($message): ?>
        <div class="p-4 rounded-xl flex items-center justify-between border shadow-sm transition-all <?= ($messageType === 'success') ? 'bg-emerald-50 dark:bg-emerald-950/60 border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200' : 'bg-rose-50 dark:bg-rose-950/60 border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200' ?>">
            <div class="flex items-center gap-3 text-sm font-semibold">
                <i class="fa-solid <?= ($messageType === 'success') ? 'fa-circle-check text-emerald-500' : 'fa-circle-exclamation text-rose-500' ?> text-lg"></i>
                <span><?= htmlspecialchars($message) ?></span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- HEADER & STAT CARDS -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300 text-[11px] font-bold tracking-wide border border-amber-200 dark:border-amber-800">
                <i class="fa-solid fa-shield-halved text-[10px]"></i> AUDIT TRAIL & SYSTEM MONITOR
            </div>
            <h1 class="text-xl md:text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight mt-1">Log & Aktivitas Sistem</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Tracking seluruh mutasi data, aktivitas kasir, load halaman, dan pencatatan error.</p>
        </div>

        <div class="flex items-center gap-2">
            <!-- Tombol Purge Modal -->
            <button onclick="document.getElementById('purgeModal').classList.remove('hidden')" class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 dark:hover:bg-rose-900 text-rose-700 dark:text-rose-300 text-xs font-bold transition-all border border-rose-200 dark:border-rose-800 flex items-center gap-2">
                <i class="fa-solid fa-trash-can text-rose-600"></i>
                <span>Bersihkan / Reset Log</span>
            </button>
            <a href="index.php?main=log_sistem" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-sm flex items-center gap-2 no-underline">
                <i class="fa-solid fa-rotate text-xs"></i>
                <span>Refresh</span>
            </a>
        </div>
    </div>

    <!-- METRICS OVERVIEW -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
        <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 border border-blue-100 dark:border-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                <i class="fa-solid fa-chart-simple text-base"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aktivitas Hari Ini</p>
                <p class="text-xl font-black text-slate-800 dark:text-slate-100"><?= number_format($summary['today_total']) ?></p>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-emerald-900 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                <i class="fa-solid fa-bolt text-base"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tindakan / Action</p>
                <p class="text-xl font-black text-emerald-600 dark:text-emerald-400"><?= number_format($summary['today_actions']) ?></p>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-900 flex items-center justify-center text-rose-600 dark:text-rose-400 shrink-0">
                <i class="fa-solid fa-triangle-exclamation text-base"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Error Hari Ini</p>
                <p class="text-xl font-black <?= ($summary['today_errors'] > 0) ? 'text-rose-600 dark:text-rose-400' : 'text-slate-700 dark:text-slate-300' ?>">
                    <?= number_format($summary['today_errors']) ?>
                </p>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/60 border border-purple-100 dark:border-purple-900 flex items-center justify-center text-purple-600 dark:text-purple-400 shrink-0">
                <i class="fa-solid fa-database text-base"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Rekaman</p>
                <p class="text-xl font-black text-slate-800 dark:text-slate-100"><?= number_format($summary['all_total']) ?></p>
            </div>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <form method="GET" action="index.php" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            <input type="hidden" name="main" value="log_sistem">

            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tingkat (Level)</label>
                <select name="level" class="w-full text-xs font-semibold px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200">
                    <option value="">Semua Level</option>
                    <option value="ACTION" <?= ($filters['level'] === 'ACTION') ? 'selected' : '' ?>>ACTION (Mutasi / Transaksi)</option>
                    <option value="WARNING" <?= ($filters['level'] === 'WARNING') ? 'selected' : '' ?>>WARNING (Peringatan)</option>
                    <option value="ERROR" <?= ($filters['level'] === 'ERROR') ? 'selected' : '' ?>>ERROR (Kegagalan / Exception)</option>
                    <option value="INFO" <?= ($filters['level'] === 'INFO') ? 'selected' : '' ?>>INFO (Informasi Umum)</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kategori</label>
                <select name="category" class="w-full text-xs font-semibold px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($summary['categories'] as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>" <?= ($filters['category'] === $cat) ? 'selected' : '' ?>><?= strtoupper($cat) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pengguna</label>
                <select name="username" class="w-full text-xs font-semibold px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200">
                    <option value="">Semua Pengguna</option>
                    <?php foreach ($summary['users'] as $usr): ?>
                        <option value="<?= htmlspecialchars($usr) ?>" <?= ($filters['username'] === $usr) ? 'selected' : '' ?>><?= htmlspecialchars($usr) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="<?= htmlspecialchars($filters['date_from']) ?>" class="w-full text-xs font-semibold px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="<?= htmlspecialchars($filters['date_to']) ?>" class="w-full text-xs font-semibold px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Cari Keyword</label>
                <div class="flex gap-1.5">
                    <input type="text" name="search" value="<?= htmlspecialchars($filters['search']) ?>" placeholder="Aksi / URL..." class="w-full text-xs font-semibold px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200">
                    <button type="submit" class="px-3 py-2 rounded-lg bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold shrink-0">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <?php if (array_filter($filters)): ?>
                        <a href="index.php?main=log_sistem" class="px-3 py-2 rounded-lg bg-rose-100 hover:bg-rose-200 text-rose-700 text-xs font-bold flex items-center shrink-0 no-underline" title="Reset Filter">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <!-- FORM BULK DELETE & LOG DATA TABLE -->
    <form id="formBulkDeleteLog" action="index.php?main=log_sistem" method="post" onsubmit="return confirmBulkDeleteLog(event)">
        <input type="hidden" name="action" value="postdelete_bulk">

        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200/80 dark:border-slate-800 text-slate-400 uppercase text-[10px] tracking-wider font-extrabold">
                            <th class="py-3 px-3 w-10 text-center">
                                <input type="checkbox" id="checkAllLogs" class="w-4 h-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500 cursor-pointer" title="Pilih Semua Log">
                            </th>
                            <th class="py-3 px-3 w-12 text-center">No</th>
                            <th class="py-3 px-4 w-40">Waktu & IP</th>
                            <th class="py-3 px-4 w-28 text-center">Level</th>
                            <th class="py-3 px-4 w-32">Pengguna</th>
                            <th class="py-3 px-4 w-32">Kategori</th>
                            <th class="py-3 px-4">Aksi & Deskripsi</th>
                            <th class="py-3 px-4 w-24 text-center">Aksi / Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-medium">
                        <?php if (empty($logs)): ?>
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-solid fa-inbox text-3xl text-slate-300 dark:text-slate-700"></i>
                                        <span>Tidak ada log yang sesuai dengan filter.</span>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = $offset + 1; foreach ($logs as $item): ?>
                                <?php
                                $badgeLevel = match($item['level']) {
                                    'ACTION'  => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                    'ERROR'   => 'bg-rose-100 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300 border-rose-200 dark:border-rose-800',
                                    'WARNING' => 'bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                    default   => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-200 dark:border-slate-700'
                                };
                                ?>
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                                    <!-- Checkbox Item -->
                                    <td class="py-3 px-3 text-center">
                                        <input type="checkbox" name="log_ids[]" value="<?= $item['id'] ?>" class="log-checkbox w-4 h-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500 cursor-pointer">
                                    </td>
                                    <td class="py-3 px-3 text-center text-slate-400 font-bold"><?= $no++ ?></td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-slate-700 dark:text-slate-200"><?= date('d M Y H:i:s', strtotime($item['created_at'])) ?></div>
                                        <div class="text-[10px] text-slate-400 font-mono"><?= htmlspecialchars($item['ip_address'] ?? '-') ?></div>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-extrabold border <?= $badgeLevel ?>">
                                            <?= $item['level'] ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-slate-800 dark:text-slate-100"><?= htmlspecialchars($item['username'] ?? 'Tamu') ?></div>
                                        <div class="text-[10px] text-slate-400 uppercase font-semibold"><?= htmlspecialchars($item['user_role'] ?? 'guest') ?></div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 uppercase">
                                            <?= htmlspecialchars($item['category']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-slate-800 dark:text-slate-100"><?= htmlspecialchars($item['action']) ?></div>
                                        <?php if (!empty($item['description'])): ?>
                                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5"><?= htmlspecialchars($item['description']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($item['url'])): ?>
                                            <div class="text-[10px] text-slate-400 font-mono mt-0.5 truncate max-w-md"><?= htmlspecialchars($item['url']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <?php if (!empty($item['context']) && $item['context'] !== '""'): ?>
                                                <button type="button" onclick="showContextModal(<?= htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8') ?>)" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center text-xs transition-colors" title="Lihat Payload Detail">
                                                    <i class="fa-solid fa-code"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button type="button" onclick="singleDeleteLog(<?= $item['id'] ?>)" class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 dark:hover:bg-rose-900 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xs transition-colors" title="Hapus Baris Log Ini">
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

            <!-- PAGINATION BAR -->
            <div class="p-3.5 bg-slate-50/70 dark:bg-slate-800/50 border-t border-slate-200/80 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-3">
                    <div class="text-slate-500 dark:text-slate-400">
                        Menampilkan <span class="font-bold text-slate-700 dark:text-slate-200"><?= $totalRows > 0 ? $offset + 1 : 0 ?></span> - <span class="font-bold text-slate-700 dark:text-slate-200"><?= min($offset + $limit, $totalRows) ?></span> dari <span class="font-bold text-slate-700 dark:text-slate-200"><?= number_format($totalRows) ?></span> rekaman
                    </div>
                    <!-- Per Page Selector -->
                    <div class="flex items-center gap-1.5 text-slate-500">
                        <span class="text-[11px]">Tampilkan:</span>
                        <?php
                        $baseQuery = $filters;
                        $baseQuery['main'] = 'log_sistem';
                        $baseQuery['page'] = 1;
                        ?>
                        <select onchange="window.location.href='index.php?<?= http_build_query($baseQuery) ?>&per_page=' + this.value" class="px-2 py-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none">
                            <?php foreach ([10, 15, 25, 50, 100] as $opt): ?>
                                <option value="<?= $opt ?>" <?= ($limit === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Page Number Links -->
                <?php if ($totalPages > 1): ?>
                    <div class="flex items-center gap-1">
                        <?php
                        $pageQuery = $filters;
                        $pageQuery['main'] = 'log_sistem';
                        $pageQuery['per_page'] = $limit;
                        ?>

                        <!-- First & Prev -->
                        <?php if ($page > 1): ?>
                            <a href="index.php?<?= http_build_query(array_merge($pageQuery, ['page' => 1])) ?>" class="w-8 h-8 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 flex items-center justify-center font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 no-underline transition-colors" title="Halaman Pertama">
                                <i class="fa-solid fa-angles-left text-[10px]"></i>
                            </a>
                            <a href="index.php?<?= http_build_query(array_merge($pageQuery, ['page' => $page - 1])) ?>" class="px-2.5 h-8 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 flex items-center justify-center font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 no-underline transition-colors gap-1">
                                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                                <span class="text-xs">Prev</span>
                            </a>
                        <?php endif; ?>

                        <!-- Numeric Links -->
                        <?php
                        $startPage = max(1, $page - 2);
                        $endPage   = min($totalPages, $page + 2);
                        for ($p = $startPage; $p <= $endPage; $p++):
                        ?>
                            <?php if ($p == $page): ?>
                                <span class="w-8 h-8 rounded-lg bg-emerald-600 text-white font-extrabold flex items-center justify-center text-xs shadow-sm">
                                    <?= $p ?>
                                </span>
                            <?php else: ?>
                                <a href="index.php?<?= http_build_query(array_merge($pageQuery, ['page' => $p])) ?>" class="w-8 h-8 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 flex items-center justify-center text-xs no-underline transition-colors">
                                    <?= $p ?>
                                </a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <!-- Next & Last -->
                        <?php if ($page < $totalPages): ?>
                            <a href="index.php?<?= http_build_query(array_merge($pageQuery, ['page' => $page + 1])) ?>" class="px-2.5 h-8 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 flex items-center justify-center font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 no-underline transition-colors gap-1">
                                <span class="text-xs">Next</span>
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                            <a href="index.php?<?= http_build_query(array_merge($pageQuery, ['page' => $totalPages])) ?>" class="w-8 h-8 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 flex items-center justify-center font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 no-underline transition-colors" title="Halaman Terakhir">
                                <i class="fa-solid fa-angles-right text-[10px]"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- FLOATING BULK ACTION BAR -->
        <div id="floatingLogBulkBar" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-40 bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-2xl border border-slate-700 flex items-center gap-4 animate-in fade-in slide-in-from-bottom-3 duration-200">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                <span id="selectedLogCountText" class="text-xs font-bold text-slate-100">0 Log Terpilih</span>
            </div>
            <div class="h-4 w-px bg-slate-700"></div>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-md shadow-rose-600/30 active:scale-95 transition-all">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                    <span>Hapus Log Terpilih</span>
                </button>
                <button type="button" onclick="clearSelectedLogs()" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </form>
</div>

<!-- FORM SINGLE DELETE LOG -->
<form id="formSingleDeleteLog" action="index.php?main=log_sistem" method="post" style="display: none;">
    <input type="hidden" name="action" value="postdelete">
    <input type="hidden" name="id" id="singleDeleteLogId" value="">
</form>

<!-- MODAL LIHAT DETAIL PAYLOAD / CONTEXT -->
<div id="contextModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-150">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden">
        <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-800/60">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-code text-amber-500"></i>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100" id="modalTitle">Detail Payload Log</h3>
            </div>
            <button onclick="document.getElementById('contextModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="p-4 space-y-3">
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div><span class="text-slate-400">Aksi:</span> <strong id="modalAction" class="text-slate-800 dark:text-slate-200"></strong></div>
                <div><span class="text-slate-400">Pengguna:</span> <strong id="modalUser" class="text-slate-800 dark:text-slate-200"></strong></div>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Raw Context Data (JSON)</label>
                <pre id="modalJson" class="p-3 rounded-xl bg-slate-950 text-emerald-400 font-mono text-[11px] overflow-x-auto max-h-72"></pre>
            </div>
        </div>
        <div class="p-3.5 bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200 dark:border-slate-800 flex justify-end">
            <button onclick="document.getElementById('contextModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-xs font-bold text-slate-700 dark:text-slate-200">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- MODAL PURGE / RESET LOGS -->
<div id="purgeModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-150">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden">
        <form method="POST" action="index.php?main=log_sistem" onsubmit="return confirmPurge(event)">
            <input type="hidden" name="action" value="purge">
            <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-800/60">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-trash-can text-rose-500"></i>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Bersihkan / Reset Log Sistem</h3>
                </div>
                <button type="button" onclick="document.getElementById('purgeModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-4 space-y-3">
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                    Pilih opsi pembersihan data riwayat log. Anda dapat mengosongkan seluruh riwayat log atau menghapus log yang telah berumur lebih dari batas hari tertentu.
                </p>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pilih Tindakan / Periode:</label>
                    <select name="days" id="purgeDaysSelect" class="w-full text-xs font-semibold px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 focus:outline-none focus:border-rose-500">
                        <option value="0" class="text-rose-600 font-bold">⚠️ Kosongkan Seluruh Riwayat Log (Reset Total)</option>
                        <option value="1">Hapus Log Lebih dari 1 Hari Lalu (Kemarin & Sebelumnya)</option>
                        <option value="3">Hapus Log Lebih dari 3 Hari Lalu</option>
                        <option value="7">Hapus Log Lebih dari 7 Hari Lalu</option>
                        <option value="15">Hapus Log Lebih dari 15 Hari Lalu</option>
                        <option value="30">Hapus Log Lebih dari 30 Hari Lalu</option>
                    </select>
                </div>
            </div>
            <div class="p-3.5 bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('purgeModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 text-xs font-bold text-slate-700 dark:text-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm active:scale-95 transition-all">
                    Eksekusi Pembersihan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showContextModal(item) {
    document.getElementById('modalAction').innerText = item.action || '-';
    document.getElementById('modalUser').innerText = (item.username || 'Tamu') + ' (' + (item.user_role || 'guest') + ')';
    try {
        var parsed = JSON.parse(item.context);
        document.getElementById('modalJson').innerText = JSON.stringify(parsed, null, 2);
    } catch(e) {
        document.getElementById('modalJson').innerText = item.context || 'Tidak ada detail payload';
    }
    document.getElementById('contextModal').classList.remove('hidden');
}

function confirmPurge(event) {
    const sel = document.getElementById('purgeDaysSelect');
    const val = sel ? sel.value : '30';
    if (val === '0') {
        return confirm('PERINGATAN KERAS: Apakah Anda yakin ingin MENGHAPUS SEMUA rekaman log sistem? Data tidak dapat dipulihkan kembali!');
    }
    return confirm('Apakah Anda yakin ingin menghapus data log sesuai filter periode yang dipilih?');
}

function singleDeleteLog(id) {
    if (confirm('Apakah Anda yakin ingin menghapus baris log ini?')) {
        document.getElementById('singleDeleteLogId').value = id;
        document.getElementById('formSingleDeleteLog').submit();
    }
}

function updateLogBulkBar() {
    const checked = document.querySelectorAll('.log-checkbox:checked');
    const bar = document.getElementById('floatingLogBulkBar');
    const txt = document.getElementById('selectedLogCountText');
    const checkAll = document.getElementById('checkAllLogs');

    if (checked.length > 0) {
        txt.innerText = checked.length + ' Log Terpilih';
        bar.classList.remove('hidden');
        bar.classList.add('flex');
    } else {
        bar.classList.add('hidden');
        bar.classList.remove('flex');
        if (checkAll) checkAll.checked = false;
    }
}

function clearSelectedLogs() {
    document.querySelectorAll('.log-checkbox').forEach(cb => cb.checked = false);
    const checkAll = document.getElementById('checkAllLogs');
    if (checkAll) checkAll.checked = false;
    updateLogBulkBar();
}

function confirmBulkDeleteLog(event) {
    const checked = document.querySelectorAll('.log-checkbox:checked');
    if (checked.length === 0) {
        alert('Silakan pilih minimal 1 log yang ingin dihapus.');
        event.preventDefault();
        return false;
    }
    return confirm(`Apakah Anda yakin ingin menghapus ${checked.length} log terpilih secara permanen?`);
}

document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('checkAllLogs');
    const checkboxes = document.querySelectorAll('.log-checkbox');

    if (checkAll) {
        checkAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = checkAll.checked;
            });
            updateLogBulkBar();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateLogBulkBar);
    });
});
</script>

<?php $content = ob_get_clean(); ?>
