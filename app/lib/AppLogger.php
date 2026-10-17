<?php

class AppLogger {
    private static $db = null;

    private static function getDb() {
        if (self::$db === null) {
            if (!class_exists('Database')) {
                require_once dirname(__DIR__) . '/config/Database.php';
            }
            self::$db = new Database();
        }
        return self::$db;
    }

    private static function getClientIp() {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($parts[0]);
        }
        return substr($ip, 0, 45);
    }

    /**
     * Rekam log ke database
     *
     * @param string $level 'INFO', 'ACTION', 'WARNING', 'ERROR'
     * @param string $category 'auth', 'transaksi', 'produk', 'stok', 'akses_halaman', 'error', 'sistem'
     * @param string $action Nama aksi singkat
     * @param string $description Penjelasan detail
     * @param array|null $context Data context / payload array
     */
    public static function log($level, $category, $action, $description = '', $context = null) {
        try {
            $db = self::getDb();
            if (!$db) return false;

            $userId = isset($_SESSION['userId']) ? (int)$_SESSION['userId'] : 'NULL';
            $username = isset($_SESSION['username']) ? addslashes($_SESSION['username']) : 'Tamu/Publik';
            $userRole = isset($_SESSION['userLevel']) ? addslashes($_SESSION['userLevel']) : 'guest';

            $levelSafe = in_array(strtoupper($level), ['INFO', 'ACTION', 'WARNING', 'ERROR']) ? strtoupper($level) : 'INFO';
            $categorySafe = addslashes(substr($category, 0, 50));
            $actionSafe = addslashes(substr($action, 0, 150));
            $descSafe = addslashes($description);
            $contextSafe = $context !== null ? addslashes(json_encode($context, JSON_UNESCAPED_UNICODE)) : '';

            $ip = addslashes(self::getClientIp());
            $ua = addslashes(substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255));
            $url = addslashes(substr($_SERVER['REQUEST_URI'] ?? '', 0, 255));

            $userIdVal = ($userId === 'NULL' || empty($userId)) ? "NULL" : $userId;

            $sql = "INSERT INTO tbl_logs 
                (created_at, level, category, user_id, username, user_role, action, description, context, ip_address, user_agent, url) 
                VALUES 
                (NOW(), '{$levelSafe}', '{$categorySafe}', {$userIdVal}, '{$username}', '{$userRole}', '{$actionSafe}', '{$descSafe}', '{$contextSafe}', '{$ip}', '{$ua}', '{$url}')";

            $res = @$db->query($sql);
            if (!$res) {
                self::logToFile($levelSafe, $categorySafe, $actionSafe, $descSafe, 'DB Query Failed');
            }
            return true;
        } catch (\Throwable $e) {
            self::logToFile($levelSafe ?? $level, $categorySafe ?? $category, $actionSafe ?? $action, $descSafe ?? $description, $e->getMessage());
            return false;
        }
    }

    /**
     * Fallback pencatatan log ke file lokal jika database terputus/unreachable
     */
    public static function logToFile($level, $category, $action, $description = '', $extra = '') {
        try {
            $logDir = dirname(__DIR__) . '/storage/logs';
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0755, true);
            }
            $logFile = $logDir . '/system_fallback.log';
            $time = date('Y-m-d H:i:s');
            $ip = self::getClientIp();
            $user = $_SESSION['username'] ?? 'Tamu';
            $extraStr = is_array($extra) ? json_encode($extra, JSON_UNESCAPED_UNICODE) : (string)$extra;
            $line = "[{$time}] [{$level}] [{$category}] [User: {$user}] [IP: {$ip}] Action: {$action} | Desc: {$description} | Detail: {$extraStr}" . PHP_EOL;
            @file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
        } catch (\Throwable $t) {
            // Ignore if filesystem is not writable
        }
    }

    public static function info($category, $action, $description = '', $context = null) {
        return self::log('INFO', $category, $action, $description, $context);
    }

    public static function action($category, $action, $description = '', $context = null) {
        return self::log('ACTION', $category, $action, $description, $context);
    }

    public static function warning($category, $action, $description = '', $context = null) {
        return self::log('WARNING', $category, $action, $description, $context);
    }

    public static function error($category, $action, $description = '', $context = null) {
        return self::log('ERROR', $category, $action, $description, $context);
    }

    /**
     * Log akses halaman (Page Load)
     */
    public static function logPageView($main) {
        // Jangan catat polling background atau aset statis berulang
        if (in_array($main, ['triger_bar', 'triger_dapur', 'count_dapur'])) {
            return;
        }

        $titleMap = [
            'dashboard'  => 'Melihat Dashboard',
            'transaksi'  => 'Membuka Menu Kasir Transaksi',
            'barang'     => 'Membuka Katalog Produk',
            'stok'       => 'Membuka Manajemen Stok',
            'size'       => 'Membuka Data Ukuran',
            'kategori'   => 'Membuka Data Kategori',
            'pelanggan'  => 'Membuka Data Pelanggan',
            'suplayer'   => 'Membuka Data Supplier',
            'laporan'    => 'Membuka Laporan Penjualan',
            'website'    => 'Membuka Pengaturan Toko',
            'migrasi'    => 'Membuka Migrasi Database',
            'pengguna'   => 'Membuka Kelola Pengguna',
            'role_akses' => 'Membuka Hak Akses Role',
            'log_sistem' => 'Membuka Log & Audit Sistem'
        ];

        $pageDesc = $titleMap[$main] ?? "Akses modul: {$main}";
        self::info('akses_halaman', "Buka Halaman: {$main}", $pageDesc, [
            'get_params' => $_GET
        ]);
    }

    /**
     * Daftarkan handler penangkap uncaught error & exception PHP
     */
    public static function registerGlobalErrorHandlers() {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            // Abaikan notice & deprecated jika error reporting dimatikan
            if (!(error_reporting() & $errno)) {
                return false;
            }

            $severity = 'WARNING';
            if (in_array($errno, [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])) {
                $severity = 'ERROR';
            }

            self::log($severity, 'error', "PHP Error: {$errstr}", "File: {$errfile} pada baris {$errline}", [
                'errno' => $errno,
                'file'  => $errfile,
                'line'  => $errline
            ]);

            return false; // Lanjutkan error handling default PHP
        });

        set_exception_handler(function ($exception) {
            self::error('error', "Uncaught Exception: " . $exception->getMessage(), $exception->getTraceAsString(), [
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
        });
    }
}
