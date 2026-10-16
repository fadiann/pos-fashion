<?php

class AuthSession {
    const COOKIE_NAME = 'pos_fashion_session_token';
    const SESSION_LIFETIME = 31536000; // 1 Tahun (365 Hari)
    const SECRET_KEY = 'pos_fashion_custom_persistent_session_key_solo_2026';

    /**
     * Inisialisasi custom persistent session
     */
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            // Konfigurasi session lifetime PHP agar tidak cepat expired
            @ini_set('session.cookie_lifetime', (string)self::SESSION_LIFETIME);
            @ini_set('session.gc_maxlifetime', (string)self::SESSION_LIFETIME);

            // Folder penyimpanan session lokal khusus aplikasi agar tidak terhapus cron OS
            $sessionDir = dirname(__DIR__) . '/storage/sessions';
            if (!is_dir($sessionDir)) {
                @mkdir($sessionDir, 0755, true);
            }
            if (is_dir($sessionDir) && is_writable($sessionDir)) {
                @session_save_path($sessionDir);
            }

            // Atur parameter cookie session agar tahan 1 tahun
            $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            @session_set_cookie_params([
                'lifetime' => self::SESSION_LIFETIME,
                'path'     => '/',
                'domain'   => '',
                'secure'   => $isSecure,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            session_start();
        }

        // Jika session kosong (misal baru buka browser / aplikasi PWA kembali),
        // coba auto-login menggunakan persistent cookie token
        if (empty($_SESSION['userId']) && !empty($_COOKIE[self::COOKIE_NAME])) {
            self::attemptTokenLogin($_COOKIE[self::COOKIE_NAME]);
        }
    }

    /**
     * Set data session dan persistent cookie token saat login berhasil
     */
    public static function setLogin($userId, $username, $userLevel) {
        if (session_status() === PHP_SESSION_NONE) {
            self::init();
        }

        $_SESSION['userId']    = $userId;
        $_SESSION['username']  = $username;
        $_SESSION['userLevel'] = $userLevel;

        // Buat persistent token di cookie agar login bertahan selamanya (1 tahun)
        $token = self::generateToken($userId, $username, $userLevel);
        $cookieExpire = time() + self::SESSION_LIFETIME;
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

        setcookie(self::COOKIE_NAME, $token, [
            'expires'  => $cookieExpire,
            'path'     => '/',
            'domain'   => '',
            'secure'   => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }

    /**
     * Hapus session dan persistent cookie saat logout
     */
    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            self::init();
        }

        $_SESSION['userId']    = false;
        unset($_SESSION['userId']);
        unset($_SESSION['username']);
        unset($_SESSION['userLevel']);
        session_destroy();

        if (isset($_COOKIE[self::COOKIE_NAME])) {
            $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            setcookie(self::COOKIE_NAME, '', [
                'expires'  => time() - 3600,
                'path'     => '/',
                'domain'   => '',
                'secure'   => $isSecure,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            unset($_COOKIE[self::COOKIE_NAME]);
        }
    }

    /**
     * Generate HMAC signed token
     */
    private static function generateToken($userId, $username, $userLevel) {
        $expiry = time() + self::SESSION_LIFETIME;
        $payload = $userId . '|' . $username . '|' . $userLevel . '|' . $expiry;
        $signature = hash_hmac('sha256', $payload, self::SECRET_KEY);
        return base64_encode($payload . '|' . $signature);
    }

    /**
     * Validasi persistent token dan restore session otomatis
     */
    private static function attemptTokenLogin($cookieValue) {
        $decoded = base64_decode($cookieValue, true);
        if (!$decoded) return false;

        $parts = explode('|', $decoded);
        if (count($parts) !== 5) return false;

        list($userId, $username, $userLevel, $expiry, $signature) = $parts;

        // Cek apakah token sudah kedaluwarsa
        if ((int)$expiry < time()) {
            return false;
        }

        // Verifikasi HMAC signature
        $payload = $userId . '|' . $username . '|' . $userLevel . '|' . $expiry;
        $expectedSignature = hash_hmac('sha256', $payload, self::SECRET_KEY);
        if (!hash_equals($expectedSignature, $signature)) {
            return false;
        }

        // Verifikasi ke database apakah user masih aktif di tbl_users
        $baseDir = dirname(__DIR__, 2);
        if (file_exists($baseDir . '/Adodb/adodb.inc.php') && file_exists($baseDir . '/app/config/Database.php')) {
            require_once $baseDir . '/Adodb/adodb.inc.php';
            require_once $baseDir . '/app/config/Database.php';
            
            $db = new Database();
            $safeId = (int)$userId;
            $res = $db->query("SELECT * FROM tbl_users WHERE user_id = {$safeId} AND status = 1 LIMIT 1");
            if ($res && $user = $res->FetchRow()) {
                $_SESSION['userId']    = $user['user_id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['userLevel'] = $user['level'];

                // Refresh persistent cookie
                self::setLogin($user['user_id'], $user['username'], $user['level']);
                return true;
            }
        }

        return false;
    }
}
