<?php
$landingConfigFile = __DIR__ . '/../../config/landing_config.json';
$landingTitle = 'bakulsepatusolo';
if (file_exists($landingConfigFile)) {
    $cfg = json_decode(file_get_contents($landingConfigFile), true);
    if (!empty($cfg['title'])) {
        $landingTitle = $cfg['title'];
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Kasir & Manajemen | <?= htmlspecialchars($landingTitle) ?></title>
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
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
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
                radial-gradient(at 0% 0%, rgba(20, 184, 166, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.12) 0px, transparent 50%);
        }
    </style>
</head>

<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col justify-between p-4 mesh-gradient transition-colors duration-200">

    <!-- TOP BAR -->
    <div class="w-full max-w-md mx-auto flex items-center justify-between pt-2">
        <!-- Back to Landing Page -->
        <a href="index.php" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-slate-200/50 dark:hover:bg-slate-800/60 transition-all">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>Kembali ke Landing Page</span>
        </a>

        <!-- Theme Toggle -->
        <button id="themeToggleBtn" type="button" aria-label="Ganti Tema" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-200/50 dark:hover:bg-slate-800/60 transition-colors">
            <i class="fa-solid fa-moon hidden dark:inline text-xs"></i>
            <i class="fa-solid fa-sun inline dark:hidden text-xs text-amber-500"></i>
        </button>
    </div>

    <!-- LOGIN CARD -->
    <div class="w-full max-w-md mx-auto my-auto">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xl shadow-slate-900/5 p-6 md:p-8 space-y-6">
            
            <!-- LOGO & TITLE -->
            <div class="text-center space-y-2">
                <div class="w-14 h-14 rounded-2xl bg-teal-600 dark:bg-teal-500 text-white mx-auto flex items-center justify-center text-2xl shadow-lg shadow-teal-500/20">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <h1 class="text-lg font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Masuk ke Sistem Kasir
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                    POS & Manajemen Operasional <span class="font-bold text-teal-600 dark:text-teal-400"><?= htmlspecialchars($landingTitle) ?></span>
                </p>
            </div>

            <!-- FORM -->
            <form action="index.php?main=login" method="post" class="space-y-4">
                
                <!-- USERNAME -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                        Username
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="username" required autofocus placeholder="Masukkan username" class="w-full pl-10 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all">
                    </div>
                </div>

                <!-- PASSWORD -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Password
                        </label>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="password" id="loginPassword" name="password" required placeholder="Masukkan password" class="w-full pl-10 pr-10 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all">
                        <button type="button" id="togglePasswordBtn" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <div class="pt-2">
                    <button type="submit" name="login" value="1" class="w-full py-2.5 px-4 rounded-xl bg-teal-600 hover:bg-teal-500 active:scale-[0.98] text-white font-bold text-xs tracking-wider uppercase shadow-lg shadow-teal-600/30 flex items-center justify-center gap-2 transition-all">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                        <span>Masuk Aplikasi</span>
                    </button>
                </div>
            </form>

            <div class="pt-2 border-t border-slate-100 dark:border-slate-800 text-center">
                <span class="text-[11px] text-slate-400">
                    Lupa password? Silakan hubungi <strong>Admin Sistem</strong>.
                </span>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="w-full max-w-md mx-auto text-center text-xs text-slate-400 pb-2">
        <p>&copy; 2026 Hekya. All rights reserved.</p>
    </div>

    <script>
        // Password toggle
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passInput = document.getElementById('loginPassword');
        toggleBtn?.addEventListener('click', () => {
            if (passInput.type === 'password') {
                passInput.type = 'text';
                toggleBtn.innerHTML = '<i class="fa-regular fa-eye-slash"></i>';
            } else {
                passInput.type = 'password';
                toggleBtn.innerHTML = '<i class="fa-regular fa-eye"></i>';
            }
        });

        // Theme toggle
        const themeBtn = document.getElementById('themeToggleBtn');
        const html = document.documentElement;
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
    </script>
</body>
</html>