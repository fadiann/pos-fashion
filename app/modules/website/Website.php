<?php
// app/modules/website/Website.php
$storeConfigFile   = __DIR__ . "/../../config/store_config.json";
$landingConfigFile = __DIR__ . "/../../config/landing_config.json";

// Default Store & Receipt Config
$defaultStoreConfig = [
    'nama_toko'            => 'bakulsepatusolo',
    'tagline'              => 'Pakaian, Celana, Aksesoris & Aneka Produk',
    'alamat'               => 'Jl. Ronggowarsito Bejen Karanganyar, Solo',
    'telepon'              => '0812-3456-7890',
    'footer_struk'         => "Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.\nTerima kasih telah berbelanja di bakulsepatusolo!",
    'lebar_kertas_default' => '58mm'
];

// Default Landing Page Config
$defaultLandingConfig = [
    'title'          => 'bakulsepatusolo',
    'tagline'        => 'Fashion, Apparel & Shoes Collection',
    'bio'            => 'Pusat pakaian, celana, kaos, aksesoris & aneka produk fashion berkualitas di Solo. Melayani eceran & reseller.',
    'location'       => 'Solo & Karanganyar, Jawa Tengah',
    'opening_hours'  => 'Buka Setiap Hari: 09.00 - 21.00 WIB',
    'instagram'      => 'bakulsepatusolo',
    'tiktok'         => 'bakulsepatusolo',
    'whatsapp_utama' => '0812-3456-7890',
    'logo'           => '',
    'links'          => []
];

// Load configs
$storeConfig = $defaultStoreConfig;
if (file_exists($storeConfigFile)) {
    $dec = json_decode(file_get_contents($storeConfigFile), true);
    if (is_array($dec)) $storeConfig = array_merge($defaultStoreConfig, $dec);
}

$landingConfig = $defaultLandingConfig;
if (file_exists($landingConfigFile)) {
    $dec = json_decode(file_get_contents($landingConfigFile), true);
    if (is_array($dec)) $landingConfig = array_merge($defaultLandingConfig, $dec);
}

$action = $Helpers->xss_clean($_REQUEST['action'] ?? '');
$activeTab = $Helpers->xss_clean($_GET['tab'] ?? 'receipt');

// Aksi 1: Simpan Pengaturan Toko & Format Struk
if ($action === 'postsave') {
    $storeConfig['nama_toko']            = trim($Helpers->xss_clean($_POST['nama_toko'] ?? 'bakulsepatusolo'));
    $storeConfig['tagline']              = trim($Helpers->xss_clean($_POST['tagline'] ?? ''));
    $storeConfig['alamat']               = trim($Helpers->xss_clean($_POST['alamat'] ?? ''));
    $storeConfig['telepon']              = trim($Helpers->xss_clean($_POST['telepon'] ?? ''));
    $storeConfig['footer_struk']         = trim($_POST['footer_struk'] ?? '');
    $storeConfig['lebar_kertas_default'] = in_array($_POST['lebar_kertas_default'] ?? '', ['58mm', '80mm']) ? $_POST['lebar_kertas_default'] : '58mm';

    file_put_contents($storeConfigFile, json_encode($storeConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    header("location:index.php?main=website&tab=receipt&success=receipt");
    exit;
}

// Aksi 2: Simpan Pengaturan Landing Page (Bio-Link & Links Dinamis)
if ($action === 'postsave_landing') {
    $landingConfig['title']          = trim($Helpers->xss_clean($_POST['title'] ?? 'bakulsepatusolo'));
    $landingConfig['tagline']        = trim($Helpers->xss_clean($_POST['tagline'] ?? ''));
    $landingConfig['bio']            = trim($Helpers->xss_clean($_POST['bio'] ?? ''));
    $landingConfig['location']       = trim($Helpers->xss_clean($_POST['location'] ?? ''));
    $landingConfig['opening_hours']  = trim($Helpers->xss_clean($_POST['opening_hours'] ?? ''));
    $landingConfig['whatsapp_utama'] = trim($Helpers->xss_clean($_POST['whatsapp_utama'] ?? ''));
    $landingConfig['instagram']      = trim($Helpers->xss_clean(str_replace('@', '', $_POST['instagram'] ?? '')));
    $landingConfig['tiktok']         = trim($Helpers->xss_clean(str_replace('@', '', $_POST['tiktok'] ?? '')));

    // Hapus Logo jika diminta
    $rootDir = dirname(__DIR__, 3);
    if (isset($_POST['delete_logo']) && $_POST['delete_logo'] === '1') {
        if (!empty($landingConfig['logo'])) {
            $oldFile = $rootDir . '/' . strtok($landingConfig['logo'], '?');
            if (file_exists($oldFile) && strpos($oldFile, 'landing_logo_') !== false) {
                @unlink($oldFile);
            }
        }
        $landingConfig['logo'] = '';
    }

    // Simpan gambar logo baru jika diunggah via Cropper (Base64)
    if (!empty($_POST['logo_base64'])) {
        $rawBase64 = $_POST['logo_base64'];
        if (preg_match('/^data:image\/(\w+);base64,/', $rawBase64, $typeMatch)) {
            $imageType = strtolower($typeMatch[1]);
            if (!in_array($imageType, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $imageType = 'png';
            }
            $base64Data = substr($rawBase64, strpos($rawBase64, ',') + 1);
            $decodedData = base64_decode($base64Data);

            if ($decodedData !== false) {
                $targetDir = $rootDir . '/app/assets/img';
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0755, true);
                }

                $filename = 'landing_logo_' . time() . '.' . ($imageType === 'jpeg' ? 'jpg' : $imageType);
                $targetFile = $targetDir . '/' . $filename;

                // Hapus logo lama jika ada
                if (!empty($landingConfig['logo'])) {
                    $oldFile = $rootDir . '/' . strtok($landingConfig['logo'], '?');
                    if (file_exists($oldFile) && strpos($oldFile, 'landing_logo_') !== false) {
                        @unlink($oldFile);
                    }
                }

                if (file_put_contents($targetFile, $decodedData)) {
                    $landingConfig['logo'] = 'app/assets/img/' . $filename;
                }
            }
        }
    }

    // Susun array links dari input dinamis
    $links = [];
    if (isset($_POST['link_title']) && is_array($_POST['link_title'])) {
        foreach ($_POST['link_title'] as $idx => $t) {
            $title = trim($Helpers->xss_clean($t));
            if ($title === '') continue; // abaikan baris yang judulnya dikosongkan

            $links[] = [
                'id'       => !empty($_POST['link_id'][$idx]) ? trim($Helpers->xss_clean($_POST['link_id'][$idx])) : ('link_' . ($idx + 1) . '_' . time()),
                'title'    => $title,
                'subtitle' => trim($Helpers->xss_clean($_POST['link_subtitle'][$idx] ?? '')),
                'url'      => trim($Helpers->xss_clean($_POST['link_url'][$idx] ?? '#')),
                'icon'     => trim($Helpers->xss_clean($_POST['link_icon'][$idx] ?? 'fa-solid fa-link')),
                'color'    => in_array($_POST['link_color'][$idx] ?? '', ['emerald', 'orange', 'dark', 'purple', 'blue', 'teal', 'rose']) ? $_POST['link_color'][$idx] : 'teal',
                'active'   => isset($_POST['link_active'][$idx]) && $_POST['link_active'][$idx] == '1'
            ];
        }
    }
    $landingConfig['links'] = $links;

    file_put_contents($landingConfigFile, json_encode($landingConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    header("location:index.php?main=website&tab=landing&success=landing");
    exit;
}

include_once modules . "/website/website-edit.php";