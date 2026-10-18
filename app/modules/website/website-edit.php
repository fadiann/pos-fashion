<?php ob_start(); 
$currentTab = $_GET['tab'] ?? 'receipt';
$successType = $_GET['success'] ?? '';
?>

<!-- Cropper.js for Flexible Logo Cropping -->
<link rel="stylesheet" href="app/assets/css/cropper.min.css">
<script src="app/assets/js/cropper.min.js"></script>

<div class="space-y-4 pb-12">
    <!-- ALERT NOTIFIKASI SUKSES -->
    <?php if ($successType === 'receipt'): ?>
        <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                <span>Pengaturan toko dan format struk kasir berhasil disimpan!</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php elseif ($successType === 'landing'): ?>
        <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                <span>Pengaturan Landing Page dan tautan bio-link berhasil diperbarui!</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- TOP HEADER & TAB SWITCHER -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 md:p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center font-bold text-lg border border-teal-200 dark:border-teal-800">
                <i class="fa-solid fa-store"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Pengaturan Toko</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Kelola identitas usaha, format struk printer thermal, dan halaman bio-link publik</p>
            </div>
        </div>

        <!-- Action & Preview Link -->
        <div class="flex items-center gap-2">
            <a href="index.php?main=landing" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold border border-slate-200 dark:border-slate-700 transition-colors" title="Buka Landing Page di Tab Baru">
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-teal-500"></i>
                <span>Lihat Landing Page Toko</span>
            </a>
        </div>
    </div>

    <!-- TAB BUTTONS -->
    <div class="flex border-b border-slate-200 dark:border-slate-800 gap-2">
        <button type="button" onclick="switchSettingsTab('receipt')" id="tabBtnReceipt" class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 <?= $currentTab !== 'landing' ? 'border-teal-600 text-teal-600 dark:text-teal-400 bg-white dark:bg-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' ?>">
            <i class="fa-solid fa-receipt"></i>
            <span>1. Identitas & Struk Kasir</span>
        </button>
        <button type="button" onclick="switchSettingsTab('landing')" id="tabBtnLanding" class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 <?= $currentTab === 'landing' ? 'border-teal-600 text-teal-600 dark:text-teal-400 bg-white dark:bg-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' ?>">
            <i class="fa-solid fa-mobile-screen-button"></i>
            <span>2. Landing Page Bio-Link</span>
            <span class="px-1.5 py-0.5 rounded-full text-[9px] bg-teal-100 dark:bg-teal-900/60 text-teal-700 dark:text-teal-300 font-extrabold uppercase">Linktree</span>
        </button>
    </div>

    <!-- ========================================== -->
    <!-- TAB 1: IDENTITAS & FORMAT STRUK KASIR     -->
    <!-- ========================================== -->
    <div id="sectionTabReceipt" class="<?= $currentTab === 'landing' ? 'hidden' : '' ?> space-y-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
            
            <!-- FORM CONFIGURATION STRUK -->
            <div class="lg:col-span-7 bg-white dark:bg-slate-900 rounded-xl p-5 md:p-6 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-file-invoice text-teal-600"></i>
                        <span>Format & Identitas Struk Kasir</span>
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Data ini akan tercetak pada header dan footer struk pembayaran thermal</p>
                </div>

                <form action="?main=website&tab=receipt" method="post" class="space-y-4">
                    <input type="hidden" name="action" value="postsave">

                    <!-- Nama Usaha -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Nama Usaha / Toko <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="inputNamaToko" name="nama_toko" value="<?= htmlspecialchars($storeConfig['nama_toko'] ?? 'bakulsepatusolo') ?>" required class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-teal-500">
                    </div>

                    <!-- Tagline Usaha -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Tagline / Deskripsi Singkat
                        </label>
                        <input type="text" id="inputTagline" name="tagline" value="<?= htmlspecialchars($storeConfig['tagline'] ?? '') ?>" placeholder="Misal: Pakaian, Celana, Aksesoris & Aneka Produk" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-teal-500">
                    </div>

                    <!-- Alamat Toko -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Alamat Lengkap Toko <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="inputAlamat" name="alamat" rows="2" required class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-teal-500"><?= htmlspecialchars($storeConfig['alamat'] ?? '') ?></textarea>
                    </div>

                    <!-- Nomor Telepon / WA Toko -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Nomor Telepon / WhatsApp Kasir <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="inputTelepon" name="telepon" value="<?= htmlspecialchars($storeConfig['telepon'] ?? '') ?>" placeholder="Misal: 0812-3456-7890" required class="w-full pl-9 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-mono font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-teal-500">
                        </div>
                    </div>

                    <!-- Lebar Kertas Struk Default -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Ukuran Kertas Thermal Standar
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-2.5 p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 cursor-pointer hover:border-teal-500 transition-colors">
                                <input type="radio" name="lebar_kertas_default" value="58mm" <?= ($storeConfig['lebar_kertas_default'] ?? '58mm') === '58mm' ? 'checked' : '' ?> class="text-teal-600 focus:ring-teal-500">
                                <div>
                                    <div class="text-xs font-bold text-slate-900 dark:text-white">58mm (Kecil / Mobile)</div>
                                    <div class="text-[10px] text-slate-500">Printer bluetooth portable</div>
                                </div>
                            </label>
                            <label class="flex items-center gap-2.5 p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 cursor-pointer hover:border-teal-500 transition-colors">
                                <input type="radio" name="lebar_kertas_default" value="80mm" <?= ($storeConfig['lebar_kertas_default'] ?? '58mm') === '80mm' ? 'checked' : '' ?> class="text-teal-600 focus:ring-teal-500">
                                <div>
                                    <div class="text-xs font-bold text-slate-900 dark:text-white">80mm (Standar POS Desktop)</div>
                                    <div class="text-[10px] text-slate-500">Printer USB / LAN kasir</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Footer Struk -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Pesan Penutup Struk (Footer)
                        </label>
                        <textarea id="inputFooter" name="footer_struk" rows="3" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-teal-500"><?= htmlspecialchars($storeConfig['footer_struk'] ?? '') ?></textarea>
                        <span class="text-[10px] text-slate-400">Pesan ini akan tercetak di bagian paling bawah struk belanja</span>
                    </div>

                    <!-- SAVE BUTTON -->
                    <div class="pt-4 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end">
                        <button type="submit" class="px-5 py-2.5 rounded-lg bg-teal-600 hover:bg-teal-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-teal-600/20 flex items-center gap-1.5 transition-all">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Simpan Format Struk</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- LIVE PREVIEW THERMAL RECEIPT -->
            <div class="lg:col-span-5 bg-white dark:bg-slate-900 rounded-xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-sm sticky top-4">
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                        <i class="fa-solid fa-receipt text-teal-600 dark:text-teal-400"></i>
                        <span>Pratinjau Struk Kasir</span>
                    </div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">Thermal View</span>
                </div>

                <!-- RECEIPT SIMULATION -->
                <div class="bg-amber-50/50 p-3 rounded-lg border border-amber-200/60 flex justify-center">
                    <div class="w-full max-w-[280px] bg-white text-slate-950 p-3.5 shadow-sm rounded border border-slate-200 font-mono text-[11px] leading-tight select-none">
                        <!-- HEADER -->
                        <div class="text-center pb-2 border-b border-dashed border-slate-400">
                            <div id="prevNamaToko" class="font-extrabold uppercase text-xs tracking-wider"><?= htmlspecialchars($storeConfig['nama_toko'] ?? 'bakulsepatusolo') ?></div>
                            <div id="prevTagline" class="text-[9px] text-slate-600 my-0.5"><?= htmlspecialchars($storeConfig['tagline'] ?? '') ?></div>
                            <div id="prevAlamat" class="text-[9.5px] text-slate-700"><?= htmlspecialchars($storeConfig['alamat'] ?? '') ?></div>
                            <div id="prevTelepon" class="text-[9.5px] text-slate-700">Telp/WA: <?= htmlspecialchars($storeConfig['telepon'] ?? '') ?></div>
                        </div>

                        <!-- META INFO -->
                        <div class="py-2 border-b border-dashed border-slate-400 text-[10px] space-y-0.5">
                            <div class="flex justify-between">
                                <span>No. Transaksi</span>
                                <span class="font-bold">TRX-<?= date('ymd') ?>-001</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Waktu</span>
                                <span><?= date('d/m/Y H:i') ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Kasir / Pelanggan</span>
                                <span>Kasir / Umum</span>
                            </div>
                        </div>

                        <!-- SAMPLE ITEMS -->
                        <div class="py-2 border-b border-dashed border-slate-400 text-[10.5px] space-y-1.5">
                            <div>
                                <div class="font-bold">Kaos Polos Cotton Combed</div>
                                <div class="flex justify-between text-slate-700">
                                    <span>2 pcs x Rp 65.000 (Sz.L)</span>
                                    <span>130.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="font-bold">Celana Chino Slimfit</div>
                                <div class="flex justify-between text-slate-700">
                                    <span>1 pcs x Rp 145.000 (Sz.32)</span>
                                    <span>145.000</span>
                                </div>
                            </div>
                        </div>

                        <!-- TOTALS -->
                        <div class="py-2 border-b border-dashed border-slate-400 space-y-1 text-[11px]">
                            <div class="flex justify-between font-bold">
                                <span>TOTAL (3 pcs)</span>
                                <span>Rp 275.000</span>
                            </div>
                            <div class="flex justify-between text-[10px]">
                                <span>CASH</span>
                                <span>Rp 300.000</span>
                            </div>
                            <div class="flex justify-between text-[10px]">
                                <span>KEMBALI</span>
                                <span>Rp 25.000</span>
                            </div>
                        </div>

                        <!-- FOOTER -->
                        <div class="text-center pt-2 text-[9.5px] text-slate-600 whitespace-pre-line" id="prevFooter">
                            <?= htmlspecialchars($storeConfig['footer_struk'] ?? '') ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 2: PENGATURAN LANDING PAGE (LINKTREE) -->
    <!-- ========================================== -->
    <div id="sectionTabLanding" class="<?= $currentTab === 'landing' ? '' : 'hidden' ?> space-y-4">
        
        <form action="?main=website&tab=landing" method="post" id="formLandingSettings">
            <input type="hidden" name="action" value="postsave_landing">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
                
                <!-- KOLOM KIRI: FORM PENGATURAN BIO & LINKS DINAMIS -->
                <div class="lg:col-span-7 space-y-4">
                    
                    <!-- KARTU LOGO: ATUR LOGO & FOTO PROFIL LANDING PAGE -->
                    <div class="bg-white dark:bg-slate-900 rounded-xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                        <div class="border-b border-slate-100 dark:border-slate-800 pb-3 flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <i class="fa-solid fa-image text-teal-600"></i>
                                    <span>Logo Brand & Foto Profil</span>
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Atur logo toko untuk ditampilkan pada header dan avatar landing page</p>
                            </div>
                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-teal-50 dark:bg-teal-950/80 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800 flex items-center gap-1">
                                <i class="fa-solid fa-crop-simple text-[9px]"></i>
                                <span>Crop Fleksibel & 1:1</span>
                            </span>
                        </div>

                        <!-- Hidden Inputs untuk Data Crop & Status Hapus -->
                        <input type="hidden" name="logo_base64" id="landingLogoBase64" value="">
                        <input type="hidden" name="delete_logo" id="deleteLogoInput" value="0">

                        <!-- Preview Area & Kontrol Upload -->
                        <?php 
                        $hasExistingLogo = !empty($landingConfig['logo']) && file_exists(__DIR__ . '/../../' . strtok($landingConfig['logo'], '?'));
                        $existingLogoUrl = $hasExistingLogo ? htmlspecialchars($landingConfig['logo']) : '';
                        ?>
                        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-5 p-4 rounded-xl bg-slate-50/70 dark:bg-slate-850 border border-slate-200/80 dark:border-slate-800">
                            <!-- Avatar Preview Circle -->
                            <div class="relative shrink-0">
                                <div class="w-20 h-20 rounded-full p-1 bg-gradient-to-tr from-teal-500 via-indigo-500 to-rose-500 shadow-md">
                                    <div id="logoPreviewContainer" class="w-full h-full rounded-full bg-slate-900 border-2 border-white dark:border-slate-900 flex items-center justify-center overflow-hidden relative">
                                        <img id="logoPreviewImg" src="<?= $existingLogoUrl ?>" alt="Logo Preview" class="w-full h-full object-cover <?= $hasExistingLogo ? '' : 'hidden' ?>" onerror="this.classList.add('hidden'); document.getElementById('logoPreviewPlaceholder').classList.remove('hidden');">
                                        <div id="logoPreviewPlaceholder" class="flex flex-col items-center justify-center text-teal-400 <?= $hasExistingLogo ? 'hidden' : '' ?>">
                                            <i class="fa-solid fa-shop text-2xl"></i>
                                            <span class="text-[7.5px] text-slate-400 mt-0.5 uppercase tracking-wider">Default</span>
                                        </div>
                                    </div>
                                </div>
                                <span id="logoStatusBadge" class="hidden absolute -bottom-1 -right-1 px-1.5 py-0.5 rounded-full text-[8.5px] font-extrabold bg-emerald-500 text-white shadow border border-white dark:border-slate-900">
                                    Baru
                                </span>
                            </div>

                            <!-- Controls & Information -->
                            <div class="flex-1 text-center sm:text-left space-y-2">
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white" id="logoTitleText">
                                        <?= $hasExistingLogo ? 'Logo Kustom Aktif' : 'Belum Ada Logo Kustom' ?>
                                    </h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                                        Upload gambar format PNG, JPG, atau WEBP. Fitur crop fleksibel memungkinkan Anda menyesuaikan bingkai persegi (1:1), memutar, zoom, atau bebas tanpa distorsi.
                                    </p>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 pt-1 justify-center sm:justify-start">
                                    <!-- File Input Hidden -->
                                    <input type="file" id="logoFileInput" accept="image/png, image/jpeg, image/webp, image/gif" class="hidden">
                                    
                                    <!-- Upload & Crop Button -->
                                    <button type="button" onclick="document.getElementById('logoFileInput').click()" class="px-3.5 py-2 rounded-lg bg-teal-600 hover:bg-teal-700 active:scale-95 text-white text-xs font-bold shadow-sm shadow-teal-600/20 flex items-center gap-1.5 transition-all">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                        <span id="btnUploadText"><?= $hasExistingLogo ? 'Ganti / Upload Logo' : 'Upload & Crop Gambar' ?></span>
                                    </button>

                                    <!-- Recrop Button -->
                                    <button type="button" id="btnRecropLogo" onclick="recropCurrentLogo()" class="px-3 py-2 rounded-lg bg-slate-200 hover:bg-slate-300 dark:bg-slate-750 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold flex items-center gap-1.5 transition-all <?= $hasExistingLogo ? '' : 'hidden' ?>">
                                        <i class="fa-solid fa-crop-simple"></i>
                                        <span>Sesuaikan Ulang</span>
                                    </button>

                                    <!-- Delete Button -->
                                    <button type="button" id="btnDeleteLogo" onclick="deleteCurrentLogo()" class="px-3 py-2 rounded-lg bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/40 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 text-xs font-semibold flex items-center gap-1.5 transition-all <?= $hasExistingLogo ? '' : 'hidden' ?>">
                                        <i class="fa-solid fa-trash-can"></i>
                                        <span>Hapus Logo</span>
                                    </button>
                                </div>

                                <div id="logoNoticeMsg" class="hidden text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1 pt-0.5">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>Logo baru siap disimpan! Klik tombol "Simpan Pengaturan Landing Page" di bawah untuk menerapkan.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KARTU 1: PROFIL & BIO UTAMA -->
                    <div class="bg-white dark:bg-slate-900 rounded-xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                        <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-address-card text-teal-600"></i>
                                <span>Profil & Deskripsi Bio Link</span>
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Atur judul brand, bio toko, lokasi dan jam operasional</p>
                        </div>

                        <!-- Judul Brand -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Judul / Nama Brand <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="landingTitleInput" name="title" value="<?= htmlspecialchars($landingConfig['title'] ?? 'bakulsepatusolo') ?>" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-teal-500">
                        </div>

                        <!-- Tagline -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Tagline / Slogan Bio
                            </label>
                            <input type="text" id="landingTaglineInput" name="tagline" value="<?= htmlspecialchars($landingConfig['tagline'] ?? '') ?>" placeholder="Misal: Fashion, Apparel & Shoes Collection" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-teal-500">
                        </div>

                        <!-- Bio Deskripsi -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Deskripsi Bio Toko
                            </label>
                            <textarea id="landingBioInput" name="bio" rows="2" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-teal-500" placeholder="Jelaskan jenis barang dagangan toko Anda (pakaian, celana, aneka produk)..."><?= htmlspecialchars($landingConfig['bio'] ?? '') ?></textarea>
                        </div>

                        <!-- Grid Lokasi & Jam Buka -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                    Lokasi Toko
                                </label>
                                <input type="text" id="landingLocInput" name="location" value="<?= htmlspecialchars($landingConfig['location'] ?? '') ?>" placeholder="Misal: Solo & Karanganyar" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-teal-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                    Jam Operasional
                                </label>
                                <input type="text" id="landingHoursInput" name="opening_hours" value="<?= htmlspecialchars($landingConfig['opening_hours'] ?? '') ?>" placeholder="Misal: Buka Setiap Hari: 09.00 - 21.00 WIB" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-teal-500">
                            </div>
                        </div>

                        <!-- Grid Kontak & Sosmed -->
                        <div class="border-t border-slate-100 dark:border-slate-800 pt-3">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Kontak & Tombol Media Sosial</span>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">WhatsApp Admin</label>
                                    <div class="relative">
                                        <i class="fa-brands fa-whatsapp text-emerald-500 absolute left-2.5 top-1/2 -translate-y-1/2 text-xs"></i>
                                        <input type="text" id="landingWaInput" name="whatsapp_utama" value="<?= htmlspecialchars($landingConfig['whatsapp_utama'] ?? '') ?>" placeholder="0812-xxxx-xxxx" class="w-full pl-8 pr-2.5 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-mono text-slate-900 dark:text-white focus:outline-none focus:border-teal-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Instagram (@)</label>
                                    <div class="relative">
                                        <i class="fa-brands fa-instagram text-fuchsia-500 absolute left-2.5 top-1/2 -translate-y-1/2 text-xs"></i>
                                        <input type="text" id="landingIgInput" name="instagram" value="<?= htmlspecialchars($landingConfig['instagram'] ?? '') ?>" placeholder="username_ig" class="w-full pl-8 pr-2.5 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-mono text-slate-900 dark:text-white focus:outline-none focus:border-teal-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">TikTok (@)</label>
                                    <div class="relative">
                                        <i class="fa-brands fa-tiktok text-slate-900 dark:text-white absolute left-2.5 top-1/2 -translate-y-1/2 text-xs"></i>
                                        <input type="text" id="landingTiktokInput" name="tiktok" value="<?= htmlspecialchars($landingConfig['tiktok'] ?? '') ?>" placeholder="username_tiktok" class="w-full pl-8 pr-2.5 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-mono text-slate-900 dark:text-white focus:outline-none focus:border-teal-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KARTU 2: DAFTAR TAUTAN / TOMBOL LINKTREE (BISA TAMBAH & KURANG) -->
                    <div class="bg-white dark:bg-slate-900 rounded-xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <i class="fa-solid fa-list-check text-teal-600"></i>
                                    <span>Daftar Tautan Linktree</span>
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Tambah, ubah, atau hapus tautan promosi & kontak toko</p>
                            </div>
                            <button type="button" id="addNewLinkBtn" class="px-3 py-1.5 rounded-lg bg-teal-50 hover:bg-teal-100 dark:bg-teal-950/60 dark:hover:bg-teal-900/80 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800 font-bold text-xs flex items-center gap-1.5 transition-colors">
                                <i class="fa-solid fa-plus text-[11px]"></i>
                                <span>Tambah Tautan</span>
                            </button>
                        </div>

                        <!-- CONTAINER DAFTAR LINK -->
                        <div id="linksContainer" class="space-y-3">
                            <?php 
                            $linksList = $landingConfig['links'] ?? [];
                            if (empty($linksList)) {
                                // Default fallback jika kosong
                                $linksList = [
                                    [
                                        'id'       => 'link_1',
                                        'title'    => 'Chat WhatsApp / Order Online',
                                        'subtitle' => 'Konsultasi stok & order langsung ke admin',
                                        'url'      => 'https://wa.me/6281234567890',
                                        'icon'     => 'fa-brands fa-whatsapp',
                                        'color'    => 'emerald',
                                        'active'   => true
                                    ]
                                ];
                            }
                            ?>

                            <?php foreach ($linksList as $idx => $link): ?>
                                <div class="link-item p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 space-y-3 relative transition-all group">
                                    <input type="hidden" name="link_id[]" value="<?= htmlspecialchars($link['id'] ?? 'link_' . $idx) ?>">
                                    
                                    <!-- Bar Atas Kartu Link: Title & Hapus -->
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                            <i class="fa-solid fa-grip-vertical text-slate-400"></i>
                                            <span class="link-number-badge">Tautan #<?= $idx + 1 ?></span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <!-- Switch Aktif -->
                                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-slate-600 dark:text-slate-400">
                                                <input type="checkbox" name="link_active[<?= $idx ?>]" value="1" <?= (!isset($link['active']) || $link['active'] == true || $link['active'] === 'true' || $link['active'] == 1) ? 'checked' : '' ?> class="link-active-toggle rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                                                <span>Aktif</span>
                                            </label>
                                            <!-- Tombol Hapus Link -->
                                            <button type="button" onclick="removeLinkItem(this)" class="text-rose-500 hover:text-rose-700 text-xs font-bold px-2 py-1 rounded hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors" title="Hapus Tautan Ini">
                                                <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Input Judul & Subtitle -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Judul Tombol <span class="text-rose-500">*</span></label>
                                            <input type="text" name="link_title[]" value="<?= htmlspecialchars($link['title'] ?? '') ?>" required placeholder="Misal: Official Shopee Store" class="link-title-input w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-900 dark:text-white focus:outline-none focus:border-teal-500">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Keterangan Singkat (Subtitle)</label>
                                            <input type="text" name="link_subtitle[]" value="<?= htmlspecialchars($link['subtitle'] ?? '') ?>" placeholder="Misal: Katalog lengkap & promo diskon" class="link-sub-input w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:border-teal-500">
                                        </div>
                                    </div>

                                    <!-- Input URL & Pilihan Icon / Warna -->
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-2.5 items-end">
                                        <div class="md:col-span-6">
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">URL Tujuan <span class="text-rose-500">*</span></label>
                                            <input type="url" name="link_url[]" value="<?= htmlspecialchars($link['url'] ?? '') ?>" required placeholder="https://..." class="link-url-input w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-mono text-slate-800 dark:text-slate-200 focus:outline-none focus:border-teal-500">
                                        </div>
                                        <div class="md:col-span-3">
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Icon FontAwesome</label>
                                            <select name="link_icon[]" class="link-icon-select w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-200 focus:outline-none focus:border-teal-500">
                                                <option value="fa-brands fa-whatsapp" <?= ($link['icon'] ?? '') === 'fa-brands fa-whatsapp' ? 'selected' : '' ?>>WhatsApp</option>
                                                <option value="fa-solid fa-bag-shopping" <?= ($link['icon'] ?? '') === 'fa-solid fa-bag-shopping' ? 'selected' : '' ?>>Shopee / Belanja</option>
                                                <option value="fa-brands fa-tiktok" <?= ($link['icon'] ?? '') === 'fa-brands fa-tiktok' ? 'selected' : '' ?>>TikTok</option>
                                                <option value="fa-brands fa-instagram" <?= ($link['icon'] ?? '') === 'fa-brands fa-instagram' ? 'selected' : '' ?>>Instagram</option>
                                                <option value="fa-solid fa-map-location-dot" <?= ($link['icon'] ?? '') === 'fa-solid fa-map-location-dot' ? 'selected' : '' ?>>Google Maps</option>
                                                <option value="fa-solid fa-store" <?= ($link['icon'] ?? '') === 'fa-solid fa-store' ? 'selected' : '' ?>>Toko / Offline</option>
                                                <option value="fa-solid fa-shirt" <?= ($link['icon'] ?? '') === 'fa-solid fa-shirt' ? 'selected' : '' ?>>Pakaian / Kaos</option>
                                                <option value="fa-solid fa-tag" <?= ($link['icon'] ?? '') === 'fa-solid fa-tag' ? 'selected' : '' ?>>Promo / Diskon</option>
                                                <option value="fa-solid fa-link" <?= ($link['icon'] ?? '') === 'fa-solid fa-link' ? 'selected' : '' ?>>Link Umum</option>
                                            </select>
                                        </div>
                                        <div class="md:col-span-3">
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Warna Aksen</label>
                                            <select name="link_color[]" class="link-color-select w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-200 focus:outline-none focus:border-teal-500">
                                                <option value="emerald" <?= ($link['color'] ?? '') === 'emerald' ? 'selected' : '' ?>>Emerald (Hijau)</option>
                                                <option value="orange" <?= ($link['color'] ?? '') === 'orange' ? 'selected' : '' ?>>Orange (Shopee)</option>
                                                <option value="dark" <?= ($link['color'] ?? '') === 'dark' ? 'selected' : '' ?>>Dark (Hitam)</option>
                                                <option value="purple" <?= ($link['color'] ?? '') === 'purple' ? 'selected' : '' ?>>Purple (Ungu)</option>
                                                <option value="blue" <?= ($link['color'] ?? '') === 'blue' ? 'selected' : '' ?>>Blue (Biru)</option>
                                                <option value="teal" <?= ($link['color'] ?? '') === 'teal' ? 'selected' : '' ?>>Teal (Toska)</option>
                                                <option value="rose" <?= ($link['color'] ?? '') === 'rose' ? 'selected' : '' ?>>Rose (Merah)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- TOMBOL SIMPAN PENGATURAN LANDING PAGE -->
                        <div class="pt-4 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                <i class="fa-solid fa-circle-info mr-1 text-teal-500"></i>
                                Perubahan akan langsung tampil di halaman landing page
                            </span>
                            <button type="submit" class="px-5 py-2.5 rounded-lg bg-teal-600 hover:bg-teal-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-teal-600/20 flex items-center gap-1.5 transition-all">
                                <i class="fa-solid fa-floppy-disk"></i>
                                <span>Simpan Pengaturan Landing Page</span>
                            </button>
                        </div>
                    </div>

                </div>

                <!-- KOLOM KANAN: LIVE PREVIEW SMARTPHONE MOCKUP -->
                <div class="lg:col-span-5 sticky top-4">
                    <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col items-center">
                        
                        <div class="w-full flex items-center justify-between pb-3 mb-3 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                <i class="fa-solid fa-mobile-screen text-teal-600"></i>
                                <span>Live Mockup Smartphone</span>
                            </div>
                            <span class="text-[10px] uppercase font-bold text-teal-600 bg-teal-50 dark:bg-teal-950/80 px-2 py-0.5 rounded border border-teal-200 dark:border-teal-800">
                                Realtime Preview
                            </span>
                        </div>

                        <!-- PHONE CONTAINER -->
                        <div class="w-full max-w-[310px] rounded-[36px] bg-slate-950 p-3 shadow-2xl border-4 border-slate-800 relative">
                            <!-- Dynamic Island / Speaker Notch -->
                            <div class="w-24 h-4 bg-slate-900 rounded-full mx-auto mb-2 flex items-center justify-center gap-1.5">
                                <div class="w-2 h-2 rounded-full bg-slate-950 border border-slate-800"></div>
                                <div class="w-1.5 h-1.5 rounded-full bg-slate-800"></div>
                            </div>

                            <!-- PHONE SCREEN AREA -->
                            <div class="w-full bg-slate-900 rounded-[26px] p-3.5 text-white overflow-hidden text-center flex flex-col items-center min-h-[480px] max-h-[520px] overflow-y-auto" style="scrollbar-width: thin;">
                                
                                <!-- Screen Header & Avatar -->
                                <div class="w-14 h-14 rounded-full p-0.5 bg-gradient-to-tr from-teal-500 to-indigo-500 shadow-md mb-2">
                                    <div id="phonePrevAvatarContainer" class="w-full h-full rounded-full bg-slate-950 flex items-center justify-center text-teal-400 text-lg overflow-hidden">
                                        <img id="phonePrevLogoImg" src="<?= $existingLogoUrl ?>" alt="Logo" class="w-full h-full object-cover <?= $hasExistingLogo ? '' : 'hidden' ?>" onerror="this.classList.add('hidden'); document.getElementById('phonePrevDefaultIcon').classList.remove('hidden');">
                                        <i id="phonePrevDefaultIcon" class="fa-solid fa-shop <?= $hasExistingLogo ? 'hidden' : '' ?>"></i>
                                    </div>
                                </div>

                                <div id="phonePrevTitle" class="text-xs font-extrabold tracking-tight text-white mb-0.5">
                                    <?= htmlspecialchars($landingConfig['title'] ?? 'bakulsepatusolo') ?>
                                </div>
                                <div id="phonePrevTagline" class="text-[9.5px] font-semibold text-teal-400 mb-1 leading-tight">
                                    <?= htmlspecialchars($landingConfig['tagline'] ?? '') ?>
                                </div>
                                <div id="phonePrevBio" class="text-[8.5px] text-slate-400 leading-tight mb-2 line-clamp-2 px-1">
                                    <?= htmlspecialchars($landingConfig['bio'] ?? '') ?>
                                </div>

                                <!-- Badges -->
                                <div class="flex flex-wrap justify-center gap-1 mb-2.5">
                                    <span id="phonePrevHours" class="text-[7.5px] px-2 py-0.5 rounded-full bg-slate-800 text-slate-300 border border-slate-700">
                                        <?= htmlspecialchars($landingConfig['opening_hours'] ?? '') ?>
                                    </span>
                                </div>



                                <!-- DYNAMIC LINK BUTTONS PREVIEW -->
                                <div id="phonePrevLinks" class="w-full space-y-2 mb-3 text-left">
                                    <!-- Rendered dynamically via JS -->
                                </div>

                                <!-- POS LOGIN BUTTON PREVIEW (COMPACT) -->
                                <div class="w-full mt-auto pt-2 border-t border-slate-800 flex items-center justify-between text-[8px] text-slate-500">
                                    <span>Akses Kasir POS</span>
                                    <span class="py-0.5 px-2 rounded bg-teal-600 text-white font-bold flex items-center gap-1">
                                        <i class="fa-solid fa-lock text-[7px]"></i>
                                        <span>Buka</span>
                                    </span>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- TEMPLATE KARTU LINK BARU (DISSEMBUNYIKAN UNTUK DIKLONING OLEH JS) -->
<template id="linkItemTemplate">
    <div class="link-item p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 space-y-3 relative transition-all group">
        <input type="hidden" name="link_id[]" value="">
        
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                <i class="fa-solid fa-grip-vertical text-slate-400"></i>
                <span class="link-number-badge">Tautan Baru</span>
            </div>
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-slate-600 dark:text-slate-400">
                    <input type="checkbox" name="link_active[__INDEX__]" value="1" checked class="link-active-toggle rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                    <span>Aktif</span>
                </label>
                <button type="button" onclick="removeLinkItem(this)" class="text-rose-500 hover:text-rose-700 text-xs font-bold px-2 py-1 rounded hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors" title="Hapus Tautan">
                    <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Judul Tombol <span class="text-rose-500">*</span></label>
                <input type="text" name="link_title[]" value="" required placeholder="Misal: Katalog Lengkap & Promo" class="link-title-input w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-900 dark:text-white focus:outline-none focus:border-teal-500">
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Keterangan Singkat (Subtitle)</label>
                <input type="text" name="link_subtitle[]" value="" placeholder="Misal: Promo diskon mingguan" class="link-sub-input w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:border-teal-500">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-2.5 items-end">
            <div class="md:col-span-6">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">URL Tujuan <span class="text-rose-500">*</span></label>
                <input type="url" name="link_url[]" value="" required placeholder="https://..." class="link-url-input w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-mono text-slate-800 dark:text-slate-200 focus:outline-none focus:border-teal-500">
            </div>
            <div class="md:col-span-3">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Icon FontAwesome</label>
                <select name="link_icon[]" class="link-icon-select w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-200 focus:outline-none focus:border-teal-500">
                    <option value="fa-brands fa-whatsapp">WhatsApp</option>
                    <option value="fa-solid fa-bag-shopping">Shopee / Belanja</option>
                    <option value="fa-brands fa-tiktok">TikTok</option>
                    <option value="fa-brands fa-instagram">Instagram</option>
                    <option value="fa-solid fa-map-location-dot">Google Maps</option>
                    <option value="fa-solid fa-store">Toko / Offline</option>
                    <option value="fa-solid fa-shirt">Pakaian / Kaos</option>
                    <option value="fa-solid fa-tag">Promo / Diskon</option>
                    <option value="fa-solid fa-link" selected>Link Umum</option>
                </select>
            </div>
            <div class="md:col-span-3">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Warna Aksen</label>
                <select name="link_color[]" class="link-color-select w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-200 focus:outline-none focus:border-teal-500">
                    <option value="teal" selected>Teal (Toska)</option>
                    <option value="emerald">Emerald (Hijau)</option>
                    <option value="orange">Orange (Shopee)</option>
                    <option value="dark">Dark (Hitam)</option>
                    <option value="purple">Purple (Ungu)</option>
                    <option value="blue">Blue (Biru)</option>
                    <option value="rose">Rose (Merah)</option>
                </select>
            </div>
        </div>
    </div>
</template>

<!-- ========================================== -->
<!-- MODAL CROP GAMBAR LOGO                    -->
<!-- ========================================== -->
<div id="cropModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="cropModalTitle" role="dialog" aria-modal="true">
    <!-- Backdrop Blur -->
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" onclick="closeCropModal()"></div>

    <div class="flex min-h-screen items-center justify-center p-3 sm:p-4">
        <div class="relative w-full max-w-2xl rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden transition-all flex flex-col max-h-[92vh]">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-crop-simple"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white" id="cropModalTitle">Sesuaikan & Potong Logo Landing Page</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Atur bingkai, rasio crop, putar atau zoom agar pas dan proporsional</p>
                    </div>
                </div>
                <button type="button" onclick="closeCropModal()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Body (Cropper View & Controls) -->
            <div class="p-4 sm:p-5 overflow-y-auto space-y-4">
                
                <!-- Cropper Image Container -->
                <div class="w-full bg-slate-950 rounded-xl overflow-hidden border border-slate-800 flex items-center justify-center relative min-h-[280px] max-h-[380px]">
                    <img id="cropperImage" src="" alt="Source Image" class="max-w-full block" style="display: block; max-height: 380px;">
                </div>

                <!-- Aspect Ratio & Transform Toolbar -->
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                    
                    <!-- Aspect Ratio Switcher Buttons -->
                    <div class="sm:col-span-7 flex flex-wrap items-center gap-1.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mr-1">Rasio:</span>
                        <button type="button" onclick="setCropRatio(1, this)" class="crop-ratio-btn px-2.5 py-1 rounded-md text-[11px] font-bold bg-teal-600 text-white shadow-sm transition-all" data-ratio="1">
                            1:1 (Persegi/Bulat)
                        </button>
                        <button type="button" onclick="setCropRatio(NaN, this)" class="crop-ratio-btn px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all" data-ratio="free">
                            Bebas (Fleksibel)
                        </button>
                        <button type="button" onclick="setCropRatio(4/3, this)" class="crop-ratio-btn px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all" data-ratio="4/3">
                            4:3
                        </button>
                        <button type="button" onclick="setCropRatio(16/9, this)" class="crop-ratio-btn px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all" data-ratio="16/9">
                            16:9
                        </button>
                    </div>

                    <!-- Transform Tools (Zoom, Rotate, Flip, Reset) -->
                    <div class="sm:col-span-5 flex items-center justify-end gap-1">
                        <button type="button" onclick="zoomCropper(0.1)" title="Perbesar (+)" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 flex items-center justify-center text-xs transition-colors">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                        </button>
                        <button type="button" onclick="zoomCropper(-0.1)" title="Perkecil (-)" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 flex items-center justify-center text-xs transition-colors">
                            <i class="fa-solid fa-magnifying-glass-minus"></i>
                        </button>
                        <button type="button" onclick="rotateCropper(-90)" title="Putar Kiri 90°" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 flex items-center justify-center text-xs transition-colors">
                            <i class="fa-solid fa-rotate-left"></i>
                        </button>
                        <button type="button" onclick="rotateCropper(90)" title="Putar Kanan 90°" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 flex items-center justify-center text-xs transition-colors">
                            <i class="fa-solid fa-rotate-right"></i>
                        </button>
                        <button type="button" onclick="flipCropper()" title="Balik Horizontal" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 flex items-center justify-center text-xs transition-colors">
                            <i class="fa-solid fa-arrows-left-right"></i>
                        </button>
                        <button type="button" onclick="resetCropper()" title="Kembalikan Posisi Semula" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 flex items-center justify-center text-xs transition-colors">
                            <i class="fa-solid fa-arrow-rotate-right"></i>
                        </button>
                    </div>

                </div>

                <!-- Live Circular & Square Preview Box -->
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-3">
                        <!-- Round Preview -->
                        <div class="flex flex-col items-center">
                            <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-teal-500 bg-slate-900 crop-preview-box"></div>
                            <span class="text-[9px] font-bold text-slate-400 mt-1">Avatar</span>
                        </div>
                        <!-- Square Preview -->
                        <div class="flex flex-col items-center">
                            <div class="w-14 h-14 rounded-xl overflow-hidden border-2 border-slate-400 dark:border-slate-600 bg-slate-900 crop-preview-box"></div>
                            <span class="text-[9px] font-bold text-slate-400 mt-1">Kotak</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Pratinjau Hasil Potong</div>
                        <div class="text-[10px] text-teal-600 dark:text-teal-400 font-mono font-semibold" id="cropResolutionInfo">Menyesuaikan...</div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="px-5 py-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-850/50 flex flex-wrap items-center justify-between gap-2">
                <button type="button" onclick="closeCropModal()" class="px-4 py-2 rounded-lg bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs border border-slate-300 dark:border-slate-700 transition-colors">
                    Batal
                </button>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="applyFullImage()" title="Gunakan gambar penuh tanpa pemotongan" class="px-3.5 py-2 rounded-lg bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-200 font-semibold text-xs transition-colors">
                        Gunakan Ukuran Utuh
                    </button>
                    <button type="button" onclick="applyCrop()" class="px-5 py-2 rounded-lg bg-teal-600 hover:bg-teal-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-teal-600/20 flex items-center gap-1.5 transition-all">
                        <i class="fa-solid fa-check"></i>
                        <span>Terapkan Crop & Pakai Logo</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Switch between Tab 1 (Receipt) and Tab 2 (Landing Page)
function switchSettingsTab(tab) {
    const secReceipt = document.getElementById('sectionTabReceipt');
    const secLanding = document.getElementById('sectionTabLanding');
    const btnReceipt = document.getElementById('tabBtnReceipt');
    const btnLanding = document.getElementById('tabBtnLanding');

    if (tab === 'landing') {
        secReceipt.classList.add('hidden');
        secLanding.classList.remove('hidden');

        btnLanding.className = 'px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 border-teal-600 text-teal-600 dark:text-teal-400 bg-white dark:bg-slate-900';
        btnReceipt.className = 'px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300';
        
        // Update URL state without page reload
        history.replaceState(null, '', '?main=website&tab=landing');
        updatePhoneMockup();
    } else {
        secLanding.classList.add('hidden');
        secReceipt.classList.remove('hidden');

        btnReceipt.className = 'px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 border-teal-600 text-teal-600 dark:text-teal-400 bg-white dark:bg-slate-900';
        btnLanding.className = 'px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300';

        history.replaceState(null, '', '?main=website&tab=receipt');
    }
}

// Remove link item
function removeLinkItem(btn) {
    const item = btn.closest('.link-item');
    if (item) {
        item.style.opacity = '0';
        item.style.transform = 'scale(0.95)';
        setTimeout(() => {
            item.remove();
            reindexLinks();
            updatePhoneMockup();
        }, 150);
    }
}

// Reindex link number labels
function reindexLinks() {
    const items = document.querySelectorAll('#linksContainer .link-item');
    items.forEach((item, index) => {
        const badge = item.querySelector('.link-number-badge');
        if (badge) badge.innerText = 'Tautan #' + (index + 1);
        const activeInput = item.querySelector('.link-active-toggle');
        if (activeInput) activeInput.name = 'link_active[' + index + ']';
    });
}

// Live Update for Smartphone Mockup
function updatePhoneMockup() {
    const titleInput = document.getElementById('landingTitleInput');
    const taglineInput = document.getElementById('landingTaglineInput');
    const bioInput = document.getElementById('landingBioInput');
    const hoursInput = document.getElementById('landingHoursInput');

    const phoneTitle = document.getElementById('phonePrevTitle');
    const phoneTagline = document.getElementById('phonePrevTagline');
    const phoneBio = document.getElementById('phonePrevBio');
    const phoneHours = document.getElementById('phonePrevHours');
    const phoneLinks = document.getElementById('phonePrevLinks');

    if (phoneTitle && titleInput) phoneTitle.innerText = titleInput.value || 'Nama Toko';
    if (phoneTagline && taglineInput) phoneTagline.innerText = taglineInput.value || '';
    if (phoneBio && bioInput) phoneBio.innerText = bioInput.value || '';
    if (phoneHours && hoursInput) phoneHours.innerText = hoursInput.value || '';

    // Rebuild links preview
    if (phoneLinks) {
        phoneLinks.innerHTML = '';
        const items = document.querySelectorAll('#linksContainer .link-item');
        
        items.forEach(item => {
            const isActive = item.querySelector('.link-active-toggle')?.checked;
            if (!isActive) return;

            const title = item.querySelector('.link-title-input')?.value || 'Judul Tautan';
            const subtitle = item.querySelector('.link-sub-input')?.value || '';
            const icon = item.querySelector('.link-icon-select')?.value || 'fa-solid fa-link';
            const color = item.querySelector('.link-color-select')?.value || 'teal';

            let colorClasses = 'bg-slate-800 text-teal-400 border-slate-700';
            if (color === 'emerald') colorClasses = 'bg-emerald-950/40 text-emerald-400 border-emerald-800';
            else if (color === 'orange') colorClasses = 'bg-orange-950/40 text-orange-400 border-orange-800';
            else if (color === 'purple') colorClasses = 'bg-fuchsia-950/40 text-fuchsia-400 border-fuchsia-800';
            else if (color === 'blue') colorClasses = 'bg-blue-950/40 text-blue-400 border-blue-800';
            else if (color === 'rose') colorClasses = 'bg-rose-950/40 text-rose-400 border-rose-800';

            const card = document.createElement('div');
            card.className = 'p-2 rounded-xl border flex items-center justify-between ' + colorClasses;
            card.innerHTML = `
                <div class="flex items-center gap-2 min-w-0 pr-1">
                    <div class="w-6 h-6 rounded-lg bg-slate-900/60 flex items-center justify-center text-[10px] shrink-0">
                        <i class="${icon}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-[9.5px] font-bold text-white truncate leading-tight">${title}</div>
                        ${subtitle ? `<div class="text-[8px] text-slate-400 truncate leading-none mt-0.5">${subtitle}</div>` : ''}
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-[8px] text-slate-500 shrink-0"></i>
            `;
            phoneLinks.appendChild(card);
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Tab 1 Receipt Live Preview Listeners
    const inputNama = document.getElementById('inputNamaToko');
    const inputTagline = document.getElementById('inputTagline');
    const inputAlamat = document.getElementById('inputAlamat');
    const inputTelepon = document.getElementById('inputTelepon');
    const inputFooter = document.getElementById('inputFooter');

    const prevNama = document.getElementById('prevNamaToko');
    const prevTagline = document.getElementById('prevTagline');
    const prevAlamat = document.getElementById('prevAlamat');
    const prevTelepon = document.getElementById('prevTelepon');
    const prevFooter = document.getElementById('prevFooter');

    if (inputNama && prevNama) inputNama.addEventListener('input', () => prevNama.innerText = inputNama.value.toUpperCase() || 'NAMA TOKO');
    if (inputTagline && prevTagline) inputTagline.addEventListener('input', () => prevTagline.innerText = inputTagline.value);
    if (inputAlamat && prevAlamat) inputAlamat.addEventListener('input', () => prevAlamat.innerText = inputAlamat.value);
    if (inputTelepon && prevTelepon) inputTelepon.addEventListener('input', () => prevTelepon.innerText = 'Telp/WA: ' + inputTelepon.value);
    if (inputFooter && prevFooter) inputFooter.addEventListener('input', () => prevFooter.innerText = inputFooter.value);

    // Tab 2 Landing Page Listeners
    const addBtn = document.getElementById('addNewLinkBtn');
    const container = document.getElementById('linksContainer');
    const template = document.getElementById('linkItemTemplate');

    if (addBtn && container && template) {
        addBtn.addEventListener('click', () => {
            const count = container.querySelectorAll('.link-item').length;
            const clone = template.content.cloneNode(true);
            
            // Set dynamic index & id
            const itemEl = clone.querySelector('.link-item');
            const hiddenId = itemEl.querySelector('input[name="link_id[]"]');
            if (hiddenId) hiddenId.value = 'link_' + (count + 1) + '_' + Date.now();

            const badge = itemEl.querySelector('.link-number-badge');
            if (badge) badge.innerText = 'Tautan #' + (count + 1);

            const activeInput = itemEl.querySelector('.link-active-toggle');
            if (activeInput) activeInput.name = 'link_active[' + count + ']';

            container.appendChild(itemEl);
            updatePhoneMockup();

            // Focus on newly created title input
            itemEl.querySelector('.link-title-input')?.focus();
        });
    }

    // Attach delegated input change listener to container for mockup live preview
    if (container) {
        container.addEventListener('input', updatePhoneMockup);
        container.addEventListener('change', updatePhoneMockup);
    }

    const formLanding = document.getElementById('formLandingSettings');
    if (formLanding) {
        formLanding.addEventListener('input', updatePhoneMockup);
        formLanding.addEventListener('change', updatePhoneMockup);
    }

    // Listener untuk File Input Gambar Logo
    const logoFileInput = document.getElementById('logoFileInput');
    if (logoFileInput) {
        logoFileInput.addEventListener('change', function(e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;

            if (!file.type.match(/^image\//)) {
                alert('File yang dipilih harus berupa gambar (JPG, PNG, WEBP, GIF).');
                logoFileInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(evt) {
                openCropModal(evt.target.result);
                logoFileInput.value = ''; // Reset agar bisa pilih gambar yang sama lagi
            };
            reader.readAsDataURL(file);
        });
    }

    // Keyboard ESC to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('cropModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeCropModal();
            }
        }
    });

    // Initial render of phone mockup
    updatePhoneMockup();
});

// ==========================================
// CROPPER.JS & LOGO MANAGEMENT FUNCTIONS
// ==========================================
let cropperInstance = null;
let currentFlipX = 1;
let currentRawSource = '';

function openCropModal(imgSrc) {
    const modal = document.getElementById('cropModal');
    const imageEl = document.getElementById('cropperImage');
    if (!modal || !imageEl) return;

    currentRawSource = imgSrc;
    imageEl.src = imgSrc;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    if (cropperInstance) {
        cropperInstance.destroy();
        cropperInstance = null;
    }
    currentFlipX = 1;

    // Reset tombol rasio ke default 1:1
    document.querySelectorAll('.crop-ratio-btn').forEach(b => {
        b.className = 'crop-ratio-btn px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all';
        if (b.getAttribute('data-ratio') === '1') {
            b.className = 'crop-ratio-btn px-2.5 py-1 rounded-md text-[11px] font-bold bg-teal-600 text-white shadow-sm transition-all';
        }
    });

    // Inisialisasi Cropper setelah modal tampil di DOM
    setTimeout(() => {
        if (typeof Cropper === 'undefined') {
            alert('Modul pemotong gambar sedang dimuat, silakan tunggu sesaat.');
            return;
        }

        cropperInstance = new Cropper(imageEl, {
            aspectRatio: 1, // Default 1:1 untuk icon/avatar
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 0.9,
            restore: false,
            guides: true,
            center: true,
            highlight: true,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            preview: '.crop-preview-box',
            crop: function(e) {
                const info = document.getElementById('cropResolutionInfo');
                if (info) {
                    info.innerText = Math.round(e.detail.width) + ' × ' + Math.round(e.detail.height) + ' px';
                }
            }
        });
    }, 150);
}

function closeCropModal() {
    const modal = document.getElementById('cropModal');
    if (modal) modal.classList.add('hidden');
    document.body.style.overflow = '';
    if (cropperInstance) {
        cropperInstance.destroy();
        cropperInstance = null;
    }
}

function setCropRatio(ratio, btn) {
    if (!cropperInstance) return;
    cropperInstance.setAspectRatio(ratio);

    document.querySelectorAll('.crop-ratio-btn').forEach(b => {
        b.className = 'crop-ratio-btn px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all';
    });
    if (btn) {
        btn.className = 'crop-ratio-btn px-2.5 py-1 rounded-md text-[11px] font-bold bg-teal-600 text-white shadow-sm transition-all';
    }
}

function zoomCropper(delta) {
    if (cropperInstance) cropperInstance.zoom(delta);
}

function rotateCropper(degree) {
    if (cropperInstance) cropperInstance.rotate(degree);
}

function flipCropper() {
    if (!cropperInstance) return;
    currentFlipX = -currentFlipX;
    cropperInstance.scaleX(currentFlipX);
}

function resetCropper() {
    if (cropperInstance) {
        cropperInstance.reset();
        currentFlipX = 1;
    }
}

function applyCrop() {
    if (!cropperInstance) return;
    const canvas = cropperInstance.getCroppedCanvas({
        maxWidth: 1024,
        maxHeight: 1024,
        fillColor: '#ffffff00',
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high'
    });

    if (!canvas) {
        alert('Gagal memproses crop gambar. Silakan coba kembali.');
        return;
    }

    const base64 = canvas.toDataURL('image/png');
    setFinalLogo(base64);
    closeCropModal();
}

function applyFullImage() {
    if (!currentRawSource) return;
    setFinalLogo(currentRawSource);
    closeCropModal();
}

function setFinalLogo(base64Data) {
    document.getElementById('landingLogoBase64').value = base64Data;
    document.getElementById('deleteLogoInput').value = '0';

    const previewImg = document.getElementById('logoPreviewImg');
    const placeholder = document.getElementById('logoPreviewPlaceholder');
    const statusBadge = document.getElementById('logoStatusBadge');
    const noticeMsg = document.getElementById('logoNoticeMsg');
    const btnRecrop = document.getElementById('btnRecropLogo');
    const btnDelete = document.getElementById('btnDeleteLogo');
    const btnUploadText = document.getElementById('btnUploadText');
    const titleText = document.getElementById('logoTitleText');

    if (previewImg) {
        previewImg.src = base64Data;
        previewImg.classList.remove('hidden');
    }
    if (placeholder) placeholder.classList.add('hidden');
    if (statusBadge) statusBadge.classList.remove('hidden');
    if (noticeMsg) {
        noticeMsg.innerHTML = '<i class="fa-solid fa-circle-check"></i> <span>Logo baru siap disimpan! Klik tombol "Simpan Pengaturan Landing Page" di bawah untuk menerapkan.</span>';
        noticeMsg.classList.remove('hidden');
    }
    if (btnRecrop) btnRecrop.classList.remove('hidden');
    if (btnDelete) btnDelete.classList.remove('hidden');
    if (btnUploadText) btnUploadText.innerText = 'Ganti Gambar Logo';
    if (titleText) titleText.innerText = 'Logo Baru (Belum Disimpan)';

    // Update avatar di Smartphone Mockup secara instan
    const phoneLogo = document.getElementById('phonePrevLogoImg');
    const phoneDefault = document.getElementById('phonePrevDefaultIcon');
    if (phoneLogo) {
        phoneLogo.src = base64Data;
        phoneLogo.classList.remove('hidden');
    }
    if (phoneDefault) phoneDefault.classList.add('hidden');
}

function recropCurrentLogo() {
    const previewImg = document.getElementById('logoPreviewImg');
    if (previewImg && previewImg.src && !previewImg.classList.contains('hidden')) {
        openCropModal(previewImg.src);
    }
}

function deleteCurrentLogo() {
    if (!confirm('Apakah Anda yakin ingin menghapus logo kustom dan kembali ke icon toko default?')) {
        return;
    }

    document.getElementById('landingLogoBase64').value = '';
    document.getElementById('deleteLogoInput').value = '1';

    const previewImg = document.getElementById('logoPreviewImg');
    const placeholder = document.getElementById('logoPreviewPlaceholder');
    const statusBadge = document.getElementById('logoStatusBadge');
    const noticeMsg = document.getElementById('logoNoticeMsg');
    const btnRecrop = document.getElementById('btnRecropLogo');
    const btnDelete = document.getElementById('btnDeleteLogo');
    const btnUploadText = document.getElementById('btnUploadText');
    const titleText = document.getElementById('logoTitleText');

    if (previewImg) {
        previewImg.src = '';
        previewImg.classList.add('hidden');
    }
    if (placeholder) placeholder.classList.remove('hidden');
    if (statusBadge) statusBadge.classList.add('hidden');
    if (noticeMsg) {
        noticeMsg.innerHTML = '<i class="fa-solid fa-circle-info text-amber-500"></i> <span class="text-amber-600 dark:text-amber-400">Logo akan dihapus setelah Anda menekan tombol "Simpan Pengaturan Landing Page".</span>';
        noticeMsg.classList.remove('hidden');
    }
    if (btnRecrop) btnRecrop.classList.add('hidden');
    if (btnDelete) btnDelete.classList.add('hidden');
    if (btnUploadText) btnUploadText.innerText = 'Upload & Crop Gambar';
    if (titleText) titleText.innerText = 'Belum Ada Logo Kustom';

    // Update avatar di Smartphone Mockup ke default
    const phoneLogo = document.getElementById('phonePrevLogoImg');
    const phoneDefault = document.getElementById('phonePrevDefaultIcon');
    if (phoneLogo) {
        phoneLogo.src = '';
        phoneLogo.classList.add('hidden');
    }
    if (phoneDefault) phoneDefault.classList.remove('hidden');
}
</script>

<?php $content = ob_get_clean(); ?>