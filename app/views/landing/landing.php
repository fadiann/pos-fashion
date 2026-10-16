<?php
// app/views/landing/landing.php
$landingConfigFile = __DIR__ . '/../../config/landing_config.json';
$storeConfigFile   = __DIR__ . '/../../config/store_config.json';

$defaultLanding = [
    'title'          => 'bakulsepatusolo',
    'tagline'        => 'Fashion, Apparel & Shoes Collection',
    'bio'            => 'Pusat pakaian, celana, kaos, aksesoris & aneka produk fashion berkualitas di Solo. Melayani eceran & reseller.',
    'location'       => 'Solo & Karanganyar, Jawa Tengah',
    'opening_hours'  => 'Buka Setiap Hari: 09.00 - 21.00 WIB',
    'instagram'      => 'bakulsepatusolo',
    'tiktok'         => 'bakulsepatusolo',
    'whatsapp_utama' => '0812-3456-7890',
    'links'          => []
];

$landingData = $defaultLanding;
if (file_exists($landingConfigFile)) {
    $decoded = json_decode(file_get_contents($landingConfigFile), true);
    if (is_array($decoded)) {
        $landingData = array_merge($defaultLanding, $decoded);
    }
}

$isLoggedIn = isset($_SESSION['userId']) && !empty($_SESSION['userId']);
$userLevel  = $_SESSION['userLevel'] ?? '';
$username   = $_SESSION['username'] ?? '';

// Icon color mapping helpers for link cards
$colorStyles = [
    'emerald' => [
        'bg'     => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
        'border' => 'hover:border-emerald-500/80',
        'glow'   => 'hover:shadow-emerald-500/10'
    ],
    'orange' => [
        'bg'     => 'bg-orange-50 dark:bg-orange-950/40 text-orange-600 dark:text-orange-400 border-orange-200 dark:border-orange-800',
        'border' => 'hover:border-orange-500/80',
        'glow'   => 'hover:shadow-orange-500/10'
    ],
    'dark' => [
        'bg'     => 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white border-slate-200 dark:border-slate-700',
        'border' => 'hover:border-slate-500/80',
        'glow'   => 'hover:shadow-slate-500/10'
    ],
    'purple' => [
        'bg'     => 'bg-fuchsia-50 dark:bg-fuchsia-950/40 text-fuchsia-600 dark:text-fuchsia-400 border-fuchsia-200 dark:border-fuchsia-800',
        'border' => 'hover:border-fuchsia-500/80',
        'glow'   => 'hover:shadow-fuchsia-500/10'
    ],
    'blue' => [
        'bg'     => 'bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-800',
        'border' => 'hover:border-blue-500/80',
        'glow'   => 'hover:shadow-blue-500/10'
    ],
    'teal' => [
        'bg'     => 'bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 border-teal-200 dark:border-teal-800',
        'border' => 'hover:border-teal-500/80',
        'glow'   => 'hover:shadow-teal-500/10'
    ],
    'rose' => [
        'bg'     => 'bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-800',
        'border' => 'hover:border-rose-500/80',
        'glow'   => 'hover:shadow-rose-500/10'
    ]
];
?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= htmlspecialchars($landingData['title']) ?> | Official Links & Katalog</title>
    <meta name="description" content="<?= htmlspecialchars($landingData['bio']) ?>">
    
    <!-- PWA Primary Meta Tags -->
    <link rel="manifest" href="manifest.webmanifest">
    <meta name="theme-color" content="#059669">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="POS Fashion">
    <link rel="apple-touch-icon" href="app/assets/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="app/assets/img/icon-192.png">
    <link rel="shortcut icon" href="app/assets/img/icon-192.png">
    <link rel="shortcut icon" href="app/assets/img/icon.png">
    
    <!-- Tailwind CSS v3 CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .mesh-gradient {
            background-image: 
                radial-gradient(at 10% 20%, rgba(20, 184, 166, 0.12) 0px, transparent 50%),
                radial-gradient(at 90% 10%, rgba(99, 102, 241, 0.10) 0px, transparent 50%),
                radial-gradient(at 50% 80%, rgba(244, 63, 94, 0.08) 0px, transparent 50%);
        }
        .glass-card {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen transition-colors duration-200 mesh-gradient selection:bg-teal-500 selection:text-white">

    <!-- TOP BAR NAVIGATION -->
    <header class="sticky top-0 z-30 w-full glass-card bg-white/70 dark:bg-slate-950/70 border-b border-slate-200/60 dark:border-slate-800/60">
        <div class="max-w-2xl mx-auto px-4 py-3 flex items-center justify-between">
            <!-- Brand Logo Link -->
            <a href="index.php" class="flex items-center gap-2 group">
                <div class="w-7 h-7 rounded-lg bg-teal-600 dark:bg-teal-500 flex items-center justify-center text-white shadow-sm font-bold text-xs group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <span class="font-extrabold text-sm tracking-tight text-slate-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                    <?= htmlspecialchars($landingData['title']) ?>
                </span>
            </a>

            <!-- Right Controls: Dark Mode Toggle -->
            <div class="flex items-center gap-2">
                <!-- Theme Toggle Button -->
                <button id="themeToggleBtn" type="button" aria-label="Ganti Tema" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition-colors border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
                    <i class="fa-solid fa-moon hidden dark:inline text-xs"></i>
                    <i class="fa-solid fa-sun inline dark:hidden text-xs text-amber-500"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="max-w-xl mx-auto px-4 pt-4 pb-8 flex flex-col items-center">

        <!-- PROFILE HERO SECTION -->
        <div class="text-center w-full flex flex-col items-center mb-4">
            <!-- Avatar Container with Gradient Ring -->
            <div class="relative mb-2.5 group">
                <div class="w-20 h-20 rounded-full p-1 bg-gradient-to-tr from-teal-500 via-indigo-500 to-rose-500 shadow-xl shadow-teal-500/10 group-hover:scale-105 transition-transform duration-300">
                    <div class="w-full h-full rounded-full bg-slate-900 border-2 border-white dark:border-slate-900 flex items-center justify-center overflow-hidden">
                        <!-- Use custom icon / logo -->
                        <div class="text-2xl text-teal-400">
                            <i class="fa-solid fa-shop"></i>
                        </div>
                    </div>
                </div>
                <!-- Verified Badge -->
                <div class="absolute bottom-0 right-0 w-6 h-6 rounded-full bg-teal-500 text-white flex items-center justify-center text-[10px] border-2 border-white dark:border-slate-950 shadow-md" title="Verified Store">
                    <i class="fa-solid fa-check"></i>
                </div>
            </div>

            <!-- Store Title & Tagline -->
            <h1 class="text-lg md:text-xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-0.5 flex items-center justify-center gap-2">
                <span><?= htmlspecialchars($landingData['title']) ?></span>
            </h1>
            <p class="text-xs font-semibold text-teal-600 dark:text-teal-400 mb-2">
                <?= htmlspecialchars($landingData['tagline']) ?>
            </p>

            <!-- Store Bio -->
            <p class="text-xs text-slate-600 dark:text-slate-300 max-w-md mx-auto leading-relaxed mb-3">
                <?= nl2br(htmlspecialchars($landingData['bio'])) ?>
            </p>

            <!-- Location & Opening Hours Badges -->
            <div class="flex flex-wrap items-center justify-center gap-1.5 text-[11px] font-medium text-slate-600 dark:text-slate-400">
                <?php if (!empty($landingData['location'])): ?>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80">
                        <i class="fa-solid fa-location-dot text-rose-500 text-[10px]"></i>
                        <span><?= htmlspecialchars($landingData['location']) ?></span>
                    </span>
                <?php endif; ?>

                <?php if (!empty($landingData['opening_hours'])): ?>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80">
                        <i class="fa-regular fa-clock text-teal-500 text-[10px]"></i>
                        <span><?= htmlspecialchars($landingData['opening_hours']) ?></span>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- DYNAMIC LINKS LIST (LINKTREE STYLE) -->
        <div class="w-full space-y-3 mb-5">
            <?php 
            $activeLinks = array_filter($landingData['links'] ?? [], function($item) {
                return !isset($item['active']) || $item['active'] === true || $item['active'] === 'true' || $item['active'] === 1;
            });
            ?>

            <?php if (empty($activeLinks)): ?>
                <div class="text-center p-8 bg-white dark:bg-slate-900 rounded-2xl border border-dashed border-slate-300 dark:border-slate-800 text-slate-400">
                    <i class="fa-regular fa-folder-open text-2xl mb-2"></i>
                    <p class="text-xs">Belum ada tautan aktif yang ditambahkan.</p>
                </div>
            <?php else: ?>
                <?php foreach ($activeLinks as $link): 
                    $color = $link['color'] ?? 'teal';
                    $style = $colorStyles[$color] ?? $colorStyles['teal'];
                    $icon  = !empty($link['icon']) ? $link['icon'] : 'fa-solid fa-arrow-up-right-from-square';
                ?>
                    <a href="<?= htmlspecialchars($link['url']) ?>" target="_blank" rel="noopener noreferrer" class="group relative flex items-center justify-between p-3.5 md:p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 hover:shadow-lg <?= $style['border'] ?> <?= $style['glow'] ?> transition-all duration-200 hover:-translate-y-0.5">
                        
                        <div class="flex items-center gap-3.5 min-w-0 pr-2">
                            <!-- Link Icon Badge -->
                            <div class="w-11 h-11 rounded-xl flex-shrink-0 flex items-center justify-center text-base border <?= $style['bg'] ?> group-hover:scale-110 transition-transform">
                                <i class="<?= htmlspecialchars($icon) ?>"></i>
                            </div>

                            <!-- Link Title & Subtitle -->
                            <div class="min-w-0">
                                <h3 class="text-xs md:text-sm font-bold text-slate-900 dark:text-white truncate group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                                    <?= htmlspecialchars($link['title']) ?>
                                </h3>
                                <?php if (!empty($link['subtitle'])): ?>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-0.5">
                                        <?= htmlspecialchars($link['subtitle']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Right Indicator Arrow -->
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 group-hover:text-teal-600 dark:group-hover:text-teal-400 group-hover:translate-x-1 transition-all flex-shrink-0">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- POS LOGIN COMPACT PANEL -->
        <div class="w-full flex items-center justify-between px-3.5 py-2 rounded-xl bg-white/70 dark:bg-slate-900/70 border border-slate-200/60 dark:border-slate-800/60 mb-6 backdrop-blur-sm text-xs">
            <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-[11px]">
                <i class="fa-solid fa-lock text-[10px] text-slate-400"></i>
                <span>Akses Kasir & Manajemen</span>
            </div>
            <div>
                <?php if ($isLoggedIn): ?>
                    <a href="index.php?main=dashboard" class="px-2.5 py-1 rounded-md bg-teal-600 hover:bg-teal-500 text-white text-[11px] font-bold shadow-sm inline-flex items-center gap-1 active:scale-95 transition-all">
                        <i class="fa-solid fa-gauge text-[10px]"></i>
                        <span>Buka POS</span>
                    </a>
                <?php else: ?>
                    <a href="index.php?main=login" class="px-2.5 py-1 rounded-md bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-200 dark:text-slate-100 text-[11px] font-semibold border border-slate-700 inline-flex items-center gap-1 active:scale-95 transition-all">
                        <i class="fa-solid fa-key text-[10px] text-teal-400"></i>
                        <span>Login POS</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- FOOTER -->
        <footer class="text-center text-xs text-slate-400 dark:text-slate-500 py-4">
            <p>&copy; 2026 Hekya. All rights reserved.</p>
        </footer>

    </main>

    <!-- TOAST NOTIFICATION -->
    <div id="copyToast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden px-4 py-2 rounded-full bg-slate-900 text-white text-xs font-semibold shadow-lg items-center gap-2">
        <i class="fa-solid fa-circle-check text-teal-400"></i>
        <span>Tautan berhasil disalin ke clipboard!</span>
    </div>

    <script>
        // Theme switcher
        const themeBtn = document.getElementById('themeToggleBtn');
        const html = document.documentElement;

        // Init theme from localStorage
        if (localStorage.getItem('theme') === 'light') {
            html.classList.remove('dark');
        } else {
            html.classList.add('dark');
        }

        themeBtn?.addEventListener('click', () => {
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        });

        // Share bio link function
        function shareBioLink() {
            const currentUrl = window.location.origin + window.location.pathname;
            if (navigator.share) {
                navigator.share({
                    title: '<?= addslashes(htmlspecialchars($landingData['title'])) ?>',
                    text: '<?= addslashes(htmlspecialchars($landingData['tagline'])) ?>',
                    url: currentUrl
                }).catch(() => {});
            } else if (navigator.clipboard) {
                navigator.clipboard.writeText(currentUrl).then(() => {
                    const toast = document.getElementById('copyToast');
                    toast.classList.remove('hidden');
                    toast.classList.add('flex');
                    setTimeout(() => {
                        toast.classList.add('hidden');
                        toast.classList.remove('flex');
                    }, 2500);
                });
            } else {
                alert('Tautan: ' + currentUrl);
            }
        }

        // Register PWA Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('sw.js').catch(function(err) {
                    console.warn('SW registration failed:', err);
                });
            });
        }
    </script>
</body>
</html>
