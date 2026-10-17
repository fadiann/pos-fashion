<?php
require_once dirname(__DIR__) . '/lib/RoleManager.php';
$main = 'dashboard';
if(isset($_REQUEST['main'])){
    $main = $Helpers->xss_clean($_GET['main']);
}
$userLevel = $_SESSION['userLevel'] ?? 'kasir';
$username  = $_SESSION['username'] ?? 'Kasir';
?>
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>HKPOS | Sistem Kasir & Manajemen Toko Fashion, Sepatu & Apparel</title>
    <meta name="description" content="Sistem Kasir & Manajemen Toko Fashion, Sepatu & Apparel" />
    
    <!-- PWA Primary Meta Tags -->
    <link rel="manifest" href="manifest.webmanifest">
    <meta name="theme-color" content="#059669">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="HKPOS">
    <link rel="apple-touch-icon" href="app/assets/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="app/assets/img/icon-192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="app/assets/img/icon-192.png">
    
    <script>
        const isDarkMode = localStorage.getItem('app_theme') === 'dark' || (!('app_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
        if (isDarkMode) {
            document.documentElement.classList.add('dark');
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.setAttribute('data-bs-theme', 'light');
        }
    </script>
    
    <!-- Google Font: Plus Jakarta Sans & Syne -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Bootstrap 5.3 CSS for backwards compatibility with existing modals & grids -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        pos: {
                            dark: '#0f172a',
                            card: '#1e293b',
                            accent: '#3b82f6'
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Select2 & jQuery UI -->
    <link rel="stylesheet" href="app/assets/css/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="shortcut icon" href="app/assets/img/icon.png">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        
        /* Modern Select2 Styling */
        .select2-container--default .select2-selection--single {
            height: 48px !important;
            padding: 8px 14px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            display: flex;
            align-items: center;
            background-color: #ffffff;
            font-size: 15px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 48px !important;
            padding-left: 0;
            color: #1e293b;
            font-weight: 500;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
            right: 10px;
        }
        .select2-dropdown {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            font-size: 14px;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #16a34a;
        }

        /* Touch Optimizations for Tablet */
        button, a, input, select {
            touch-action: manipulation;
        }
        
        /* Custom Scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .font-brand {
            font-family: 'Syne', sans-serif;
            letter-spacing: 1px;
        }

        /* Dark mode scrollbars & Select2 */
        .dark ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
        .dark .select2-container--default .select2-selection--single {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #f1f5f9 !important;
        }
        .dark .select2-dropdown {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        .dark .select2-container--default .select2-results__option {
            background-color: #1e293b;
            color: #f1f5f9;
        }
        .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #16a34a !important;
            color: #ffffff !important;
        }

        /* FORCE DARK THEME OVERRIDES (Menang mutlak atas Bootstrap !important) */
        html.dark, 
        html.dark body {
            background-color: #020617 !important; /* Slate-950 */
            color: #f8fafc !important;
        }

        html.dark header {
            background-color: #0f172a !important; /* Slate-900 */
            border-color: #1e293b !important;
            color: #f8fafc !important;
        }

        html.dark main {
            background-color: #020617 !important;
        }

        /* Panel & Box background override */
        html.dark .bg-white {
            background-color: #0f172a !important;
            border-color: #1e293b !important;
            color: #f8fafc !important;
        }

        html.dark .bg-slate-50,
        html.dark .bg-slate-50\/50,
        html.dark .bg-slate-100 {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }

        html.dark .text-slate-900,
        html.dark .text-slate-800,
        html.dark .text-slate-700 {
            color: #f8fafc !important;
        }

        html.dark .text-slate-600,
        html.dark .text-slate-500 {
            color: #94a3b8 !important;
        }

        html.dark .border-slate-200,
        html.dark .border-slate-200\/80,
        html.dark .border-slate-200\/90,
        html.dark .border-slate-100,
        html.dark .border-slate-300 {
            border-color: #1e293b !important;
        }

        html.dark input[type="text"],
        html.dark input[type="number"],
        html.dark input[type="search"],
        html.dark input[type="password"],
        html.dark textarea,
        html.dark select {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }

        html.dark input::placeholder {
            color: #64748b !important;
        }

        html.dark .product-card {
            background-color: #0f172a !important;
            border-color: #1e293b !important;
        }

        html.dark .product-card:hover {
            border-color: #10b981 !important;
        }

        html.dark .modal-content {
            background-color: #0f172a !important;
            border-color: #1e293b !important;
            color: #f8fafc !important;
        }

        html.dark table {
            color: #f8fafc !important;
        }

        html.dark table thead th {
            border-color: #1e293b !important;
            color: #94a3b8 !important;
        }

        html.dark tbody tr {
            border-color: #1e293b !important;
        }

        html.dark tbody tr:hover {
            background-color: #1e293b !important;
        }

        /* Sidebar Styling & Smooth Hide/Show Transition */
        #sidebar-menu {
            transition: width 0.28s cubic-bezier(0.4, 0, 0.2, 1), transform 0.28s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease;
            will-change: width, transform;
        }
        
        .sidebar-hidden {
            width: 0 !important;
            min-width: 0 !important;
            max-width: 0 !important;
            transform: translateX(-100%) !important;
            overflow: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }

        @media (max-width: 767px) {
            #sidebar-menu {
                position: fixed !important;
                inset: 0 auto 0 0 !important;
                height: 100vh !important;
                z-index: 50 !important;
            }
        }
    </style>
</head>

<body class="h-full flex flex-col overflow-hidden text-slate-800 dark:text-slate-100 bg-slate-100 dark:bg-slate-950 transition-colors duration-200">

    <!-- TOPBAR / HEADER -->
    <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shrink-0 z-30 px-3 md:px-5 flex items-center justify-between shadow-sm transition-colors duration-200">
        <div class="flex items-center gap-2.5">
            <!-- Brand Logo (Tulisan bakulsepatusolo) -->
            <a href="index.php" class="flex items-center no-underline select-none pl-1">
                <span class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white tracking-wide font-brand hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                    bakulsepatusolo
                </span>
            </a>

            <!-- Sidebar Toggle Button (Desktop & Tablet: Sebelah Kanan Logo) -->
            <button id="toggle-sidebar-btn" type="button" class="hidden md:inline-flex p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 active:scale-95 focus:outline-none transition-all ml-1" title="Buka/Tutup Menu">
                <i class="fa-solid fa-bars-staggered text-lg"></i>
            </button>
        </div>

        <!-- Clock, Theme, Status, & User Action -->
        <div class="flex items-center gap-2 md:gap-3.5">
            <!-- Digital Clock Realtime -->
            <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300">
                <i class="fa-regular fa-clock text-emerald-600 dark:text-emerald-400"></i>
                <span id="MyClockDisplay">00:00:00</span>
            </div>

            <!-- PWA Install Button (Tampil otomatis jika aplikasi belum terpasang di Android/Desktop) -->
            <button id="pwa-install-btn" type="button" onclick="installPWA()" class="hidden items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md shadow-emerald-600/30 active:scale-95 transition-all cursor-pointer" title="Pasang HKPOS di Android / Desktop">
                <i class="fa-solid fa-mobile-screen-button text-sm"></i>
                <span class="hidden sm:inline">Pasang HKPOS</span>
            </button>

            <!-- Theme Toggle Button (Light/Dark Mode) -->
            <button id="btn-theme-toggle" type="button" class="p-2 rounded-xl text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800 active:scale-95 transition-all" title="Ubah Mode (Terang / Gelap)">
                <i id="theme-toggle-icon" class="fa-solid fa-moon text-base"></i>
            </button>

            <!-- Fullscreen Button -->
            <button id="btn-fullscreen" type="button" class="p-2 rounded-xl text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors hidden md:block" title="Layar Penuh (Tablet)">
                <i class="fa-solid fa-expand text-base"></i>
            </button>

            <!-- User Info Pill -->
            <div class="flex items-center gap-2.5 pl-2 border-l border-slate-200 dark:border-slate-800">
                <?php 
                    $roleBg = 'bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border-emerald-300 dark:border-emerald-700';
                    $roleLabel = 'Kasir POS';
                    $roleColor = 'text-emerald-600 dark:text-emerald-400';
                    if ($userLevel === 'admin') {
                        $roleBg = 'bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 border-purple-300 dark:border-purple-700';
                        $roleLabel = 'Administrator';
                        $roleColor = 'text-purple-600 dark:text-purple-400';
                    } elseif ($userLevel === 'owner') {
                        $roleBg = 'bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border-amber-300 dark:border-amber-700';
                        $roleLabel = 'Owner Toko';
                        $roleColor = 'text-amber-600 dark:text-amber-400';
                    }
                ?>
                <div class="w-9 h-9 rounded-full <?= $roleBg ?> font-bold flex items-center justify-center text-sm border">
                    <?= strtoupper(substr($username, 0, 1)) ?>
                </div>
                <div class="hidden md:block text-left leading-none">
                    <p class="text-xs font-bold text-slate-800 dark:text-slate-100 mb-0.5"><?= ucfirst($username) ?></p>
                    <span class="text-[10px] font-bold <?= $roleColor ?>"><?= $roleLabel ?></span>
                </div>
                <a href="?main=logout" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition-colors ml-1" title="Keluar">
                    <i class="fa-solid fa-arrow-right-from-bracket text-base"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN WRAPPER (SIDEBAR + CONTENT) -->
    <div class="flex-1 flex overflow-hidden relative">
        
        <!-- SIDEBAR DRAWER -->
        <aside id="sidebar-menu" class="w-64 bg-slate-900 text-slate-200 shrink-0 flex flex-col z-20 shadow-xl md:shadow-none select-none">
            <div class="p-4 flex-1 overflow-y-auto space-y-1.5">
                
                <div class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">Kasir & Utama</div>
                
                <!-- Menu Transaksi (Highlight) -->
                <?php if(RoleManager::can($userLevel, 'transaksi')): ?>
                <a href="index.php?main=transaksi" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all no-underline <?= ($main == 'transaksi') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                    <i class="fa-solid fa-cash-register text-lg <?= ($main == 'transaksi') ? 'text-white' : 'text-emerald-400' ?>"></i>
                    <span>Transaksi</span>
                    <span class="ml-auto text-[10px] bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-1.5 py-0.5 rounded-full font-bold">POS</span>
                </a>
                <?php endif; ?>

                <!-- Dashboard -->
                <?php if(RoleManager::can($userLevel, 'dashboard')): ?>
                <a href="index.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all no-underline <?= ($main == 'dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                    <i class="fa-solid fa-chart-pie text-base text-slate-400"></i>
                    <span>Dashboard</span>
                </a>
                <?php endif; ?>

                <!-- MASTER DATA (Dinamis Berdasarkan Izin Role) -->
                <?php 
                $canSeeMaster = RoleManager::can($userLevel, 'barang') || 
                                RoleManager::can($userLevel, 'stok') || 
                                RoleManager::can($userLevel, 'size') || 
                                RoleManager::can($userLevel, 'kategori') || 
                                RoleManager::can($userLevel, 'pelanggan') || 
                                RoleManager::can($userLevel, 'suplayer');
                if ($canSeeMaster):
                ?>
                <div class="pt-4 px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">Master Data</div>
                <div class="space-y-1">
                    <?php if(RoleManager::can($userLevel, 'barang')): ?>
                    <a href="index.php?main=barang" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm transition-all no-underline <?= ($main == 'barang') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fa-solid fa-box-archive text-sm text-slate-400"></i>
                        <span>Data Produk</span>
                    </a>
                    <?php endif; ?>

                    <?php if(RoleManager::can($userLevel, 'stok')): ?>
                    <a href="index.php?main=stok" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm transition-all no-underline <?= ($main == 'stok') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fa-solid fa-boxes-stacked text-sm text-slate-400"></i>
                        <span>Manajemen Stok</span>
                    </a>
                    <?php endif; ?>

                    <?php if(RoleManager::can($userLevel, 'size')): ?>
                    <a href="index.php?main=size" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm transition-all no-underline <?= ($main == 'size') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fa-solid fa-ruler-horizontal text-sm text-slate-400"></i>
                        <span>Data Ukuran / Size</span>
                    </a>
                    <?php endif; ?>

                    <?php if(RoleManager::can($userLevel, 'kategori')): ?>
                    <a href="index.php?main=kategori" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm transition-all no-underline <?= ($main == 'kategori') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fa-solid fa-tags text-sm text-slate-400"></i>
                        <span>Kategori Produk</span>
                    </a>
                    <?php endif; ?>

                    <?php if(RoleManager::can($userLevel, 'pelanggan')): ?>
                    <a href="index.php?main=pelanggan" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm transition-all no-underline <?= ($main == 'pelanggan') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fa-solid fa-users text-sm text-slate-400"></i>
                        <span>Data Pelanggan</span>
                    </a>
                    <?php endif; ?>

                    <?php if(RoleManager::can($userLevel, 'suplayer')): ?>
                    <a href="index.php?main=suplayer" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm transition-all no-underline <?= ($main == 'suplayer') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fa-solid fa-truck text-sm text-slate-400"></i>
                        <span>Data Supplier</span>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- LAPORAN KEUANGAN -->
                <?php if(RoleManager::can($userLevel, 'laporan')): ?>
                <div class="pt-4 px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">Laporan Keuangan</div>
                <a href="index.php?main=laporan" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm transition-all no-underline <?= ($main == 'laporan') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                    <i class="fa-solid fa-chart-line text-sm text-emerald-400"></i>
                    <span>Laporan Penjualan</span>
                </a>
                <?php endif; ?>

                <!-- GRUP PENGATURAN TOKO (DI LUAR PENGATURAN SISTEM) -->
                <?php if(RoleManager::can($userLevel, 'website')): ?>
                <div class="pt-4 px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">Pengaturan Toko</div>
                <div class="space-y-1">
                    <a href="index.php?main=website" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm transition-all no-underline <?= ($main == 'website') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fa-solid fa-store text-sm text-slate-400"></i>
                        <span>Pengaturan Toko</span>
                    </a>
                    <a href="index.php?main=landing" target="_blank" class="flex items-center justify-between px-3.5 py-2 rounded-xl text-sm transition-all no-underline text-teal-400 hover:bg-slate-800 hover:text-teal-300">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-mobile-screen-button text-sm"></i>
                            <span>Buka Landing Page</span>
                        </div>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i>
                    </a>
                </div>
                <?php endif; ?>

                <!-- GRUP MENU KHUSUS ADMINISTRATOR SISTEM (STANDAR TANPA HIGHLIGHT) -->
                <?php if($userLevel === 'admin'): ?>
                <div class="pt-4 px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">Pengaturan Sistem</div>
                <div class="space-y-1">
                    <a href="index.php?main=migrasi" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm transition-all no-underline <?= ($main == 'migrasi') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fa-solid fa-database text-sm text-slate-400"></i>
                        <span>Migrasi Database</span>
                    </a>
                    <a href="index.php?main=pengguna" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm transition-all no-underline <?= ($main == 'pengguna') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fa-solid fa-users-gear text-sm text-slate-400"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                    <a href="index.php?main=role_akses" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm transition-all no-underline <?= ($main == 'role_akses') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fa-solid fa-user-shield text-sm text-slate-400"></i>
                        <span>Hak Akses Role</span>
                    </a>
                </div>
                <?php endif; ?>

            </div>
        </aside>

        <!-- BACKDROP OVERLAY FOR TABLET / MOBILE -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 hidden md:hidden"></div>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 overflow-y-auto bg-slate-100 dark:bg-slate-950 p-2.5 md:p-4 flex flex-col transition-colors duration-200">
            <div class="max-w-[1600px] w-full mx-auto flex-1 flex flex-col justify-between">
                <div>
                    <?= $content ?? '' ?>
                </div>
                <!-- FOOTER PATEN -->
                <footer class="mt-8 pt-4 pb-2 border-t border-slate-200/60 dark:border-slate-800/60 text-center text-xs text-slate-400 dark:text-slate-500 select-none">
                    <p class="mb-0">&copy; 2026 Hekya. All rights reserved.</p>
                </footer>
            </div>
        </main>
    </div>

    <!-- FLOATING MOBILE TOGGLE MENU (BOTTOM RIGHT) -->
    <button id="toggle-sidebar-mobile-btn" type="button" class="md:hidden fixed bottom-5 right-5 z-40 w-12 h-12 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-900/30 flex items-center justify-center active:scale-90 transition-all border border-emerald-400/40" title="Buka Menu">
        <i class="fa-solid fa-bars text-lg"></i>
    </button>

    <!-- SCRIPTS -->
    <script src="app/assets/js/jquery-2.1.1.min.js"></script>
    <script src="app/assets/js/jquery-ui.js"></script>
    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="app/assets/js/typeahead.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>
    <!-- Universal Table Pagination Script -->
    <script src="app/assets/js/app-pagination.js"></script>

    <script>
        // Realtime Clock
        function showTime() {
            var date = new Date();
            var H = date.getHours().toString().padStart(2, '0');
            var m = date.getMinutes().toString().padStart(2, '0');
            var s = date.getSeconds().toString().padStart(2, '0');
            var clockEl = document.getElementById("MyClockDisplay");
            if (clockEl) {
                clockEl.innerText = H + ":" + m + ":" + s;
            }
            setTimeout(showTime, 1000);
        }
        showTime();

        // Theme Switcher Logic (Light & Dark Mode)
        const themeToggleBtn = document.getElementById('btn-theme-toggle');
        const themeIcon = document.getElementById('theme-toggle-icon');

        function updateThemeIcon() {
            if (document.documentElement.classList.contains('dark')) {
                themeIcon.className = 'fa-solid fa-sun text-amber-400 text-base';
            } else {
                themeIcon.className = 'fa-solid fa-moon text-slate-600 text-base';
            }
        }
        updateThemeIcon();

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    document.documentElement.setAttribute('data-bs-theme', 'light');
                    localStorage.setItem('app_theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    document.documentElement.setAttribute('data-bs-theme', 'dark');
                    localStorage.setItem('app_theme', 'dark');
                }
                updateThemeIcon();
            });
        }

        // Responsive Sidebar Toggle with Persistence
        const toggleBtn       = document.getElementById('toggle-sidebar-btn');
        const toggleMobileBtn = document.getElementById('toggle-sidebar-mobile-btn');
        const sidebar         = document.getElementById('sidebar-menu');
        const backdrop        = document.getElementById('sidebar-backdrop');

        function isMobile() {
            return window.innerWidth < 768;
        }

        function setSidebarState(hide) {
            if (hide) {
                sidebar.classList.add('sidebar-hidden');
                if (backdrop) backdrop.classList.add('hidden');
                if (!isMobile()) localStorage.setItem('sidebar_hidden', '1');
            } else {
                sidebar.classList.remove('sidebar-hidden');
                if (isMobile() && backdrop) backdrop.classList.remove('hidden');
                if (!isMobile()) localStorage.setItem('sidebar_hidden', '0');
            }
        }

        function toggleSidebar() {
            const isCurrentlyHidden = sidebar.classList.contains('sidebar-hidden');
            setSidebarState(!isCurrentlyHidden);
        }

        // Init initial state based on device & previous preference
        if (isMobile()) {
            sidebar.classList.add('sidebar-hidden');
        } else {
            if (localStorage.getItem('sidebar_hidden') === '1') {
                sidebar.classList.add('sidebar-hidden');
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleSidebar);
        }
        if (toggleMobileBtn) {
            toggleMobileBtn.addEventListener('click', toggleSidebar);
        }
        if (backdrop) {
            backdrop.addEventListener('click', function() {
                setSidebarState(true);
            });
        }

        // Fullscreen Toggle for Tablet POS
        const fsBtn = document.getElementById('btn-fullscreen');
        if (fsBtn) {
            fsBtn.addEventListener('click', function() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen().catch(err => {});
                    this.innerHTML = '<i class="fa-solid fa-compress text-base"></i>';
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                        this.innerHTML = '<i class="fa-solid fa-expand text-base"></i>';
                    }
                }
            });
        }

        // Initialize Select2 & Datepicker
        $(document).ready(function() {
            $('.select2').select2();
            if ($.fn.datepicker) {
                $(".datepicker").datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            }
        });

        // AJAX Handler for Size & Stok (Legacy support)
        $('#pesanan').on('change', function() {
            $('#append_size').empty();
            var query = $(this).val();
            if (!query) return;
            $.ajax({
                url: 'Api.php',
                data: { action: 'getdatasize', param: query },
                type: "POST",
                success : function (data) {
                    $('#append_size').html(data);
                }
            });
        });

        $('.size').on('change', function() {
            $('#stok').val('');
            var produk_id = $('#pesanan').val();
            var size_id = $(this).val();
            if (!produk_id || !size_id) return;
            $.ajax({
                url: 'Api.php',
                data: { action: 'getdatastokavailable', param: produk_id + ':' + size_id },
                type: "POST",
                success : function (data) {
                    $('#stok').val(data);
                }
            });
        });

        // ==========================================
        // PWA (Progressive Web App) Service Worker & Install Logic
        // ==========================================
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('sw.js')
                    .then(function(registration) {
                        console.log('POS PWA Service Worker terdaftar dengan scope:', registration.scope);
                    })
                    .catch(function(err) {
                        console.warn('POS PWA Service Worker gagal didaftarkan:', err);
                    });
            });
        }

        let deferredInstallPrompt = null;
        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredInstallPrompt = e;
            var installBtn = document.getElementById('pwa-install-btn');
            if (installBtn && !window.matchMedia('(display-mode: standalone)').matches) {
                installBtn.classList.remove('hidden');
                installBtn.classList.add('inline-flex');
            }
        });

        function installPWA() {
            if (deferredInstallPrompt) {
                deferredInstallPrompt.prompt();
                deferredInstallPrompt.userChoice.then(function(choiceResult) {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('Pengguna menyetujui instalasi PWA POS Fashion');
                    }
                    deferredInstallPrompt = null;
                    var installBtn = document.getElementById('pwa-install-btn');
                    if (installBtn) {
                        installBtn.classList.add('hidden');
                        installBtn.classList.remove('inline-flex');
                    }
                });
            }
        }

        window.addEventListener('appinstalled', function() {
            console.log('PWA POS Fashion berhasil diinstal');
            var installBtn = document.getElementById('pwa-install-btn');
            if (installBtn) {
                installBtn.classList.add('hidden');
                installBtn.classList.remove('inline-flex');
            }
        });
    </script>
</body>
</html>
