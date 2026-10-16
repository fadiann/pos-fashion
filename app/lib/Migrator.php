<?php

class Migrator {
    private $db;
    private $migrationsDir;

    public function __construct($dbInstance = null, $migrationsDir = null) {
        if ($dbInstance === null) {
            if (!class_exists('Database')) {
                require_once __DIR__ . '/../config/Database.php';
            }
            $database = new Database();
            $this->db = $database;
        } else {
            $this->db = $dbInstance;
        }

        if ($migrationsDir === null) {
            $this->migrationsDir = dirname(__DIR__, 2) . '/database/migrations';
        } else {
            $this->migrationsDir = rtrim($migrationsDir, '/\\');
        }

        if (!is_dir($this->migrationsDir)) {
            mkdir($this->migrationsDir, 0777, true);
        }

        $this->ensureMigrationTable();
    }

    /**
     * Pastikan tabel tracking migrasi ada di database
     */
    public function ensureMigrationTable() {
        $sql = "CREATE TABLE IF NOT EXISTS `migrations` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `migration` VARCHAR(255) NOT NULL UNIQUE,
            `batch` INT NOT NULL,
            `applied_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $this->db->query($sql);
    }

    /**
     * Dapatkan daftar nama migrasi yang sudah pernah dijalankan
     */
    public function getRanMigrations() {
        $sql = "SELECT `migration`, `batch`, `applied_at` FROM `migrations` ORDER BY `id` ASC";
        $res = $this->db->query($sql);
        $ran = [];
        if ($res) {
            while ($row = $res->FetchRow()) {
                $ran[$row['migration']] = [
                    'migration'  => $row['migration'],
                    'batch'      => (int)$row['batch'],
                    'applied_at' => $row['applied_at']
                ];
            }
        }
        return $ran;
    }

    /**
     * Dapatkan semua file migrasi yang tersedia di folder database/migrations
     */
    public function getAllMigrationFiles() {
        $files = glob($this->migrationsDir . '/*.php');
        if (!$files) {
            return [];
        }
        natsort($files);
        $result = [];
        foreach ($files as $filePath) {
            $result[] = [
                'filename' => basename($filePath),
                'path'     => $filePath
            ];
        }
        return $result;
    }

    /**
     * Dapatkan nomor batch berikutnya
     */
    public function getNextBatchNumber() {
        $sql = "SELECT MAX(`batch`) AS max_batch FROM `migrations`";
        $res = $this->db->query($sql);
        if ($res && ($row = $res->FetchRow()) && isset($row['max_batch']) && $row['max_batch'] !== null) {
            return (int)$row['max_batch'] + 1;
        }
        return 1;
    }

    /**
     * Jalankan migrasi yang belum dieksekusi (UP)
     */
    public function migrate() {
        $ran = $this->getRanMigrations();
        $allFiles = $this->getAllMigrationFiles();
        $batch = $this->getNextBatchNumber();

        $migrated = [];
        $errors = [];

        foreach ($allFiles as $fileInfo) {
            $filename = $fileInfo['filename'];
            $filePath = $fileInfo['path'];

            if (isset($ran[$filename])) {
                continue; // Sudah dijalankan sebelumnya
            }

            try {
                $migrationInstance = require $filePath;

                if (!is_object($migrationInstance) || !method_exists($migrationInstance, 'up')) {
                    throw new Exception("File migrasi {$filename} harus mengembalikan objek class dengan method up(\$db)!");
                }

                // Eksekusi migrasi up
                $migrationInstance->up($this->db);

                // Rekam ke tabel tracking
                $sqlInsert = "INSERT INTO `migrations` (`migration`, `batch`, `applied_at`) VALUES ('{$filename}', {$batch}, NOW())";
                $this->db->query($sqlInsert);

                $migrated[] = $filename;
            } catch (Throwable $e) {
                $errors[] = [
                    'file'    => $filename,
                    'message' => $e->getMessage()
                ];
                // Hentikan proses jika ada error agar konsisten
                break;
            }
        }

        return [
            'success'  => empty($errors),
            'batch'    => $batch,
            'migrated' => $migrated,
            'errors'   => $errors
        ];
    }

    /**
     * Batalkan batch migrasi terakhir (DOWN)
     */
    public function rollback() {
        $sqlLastBatch = "SELECT MAX(`batch`) AS max_batch FROM `migrations`";
        $res = $this->db->query($sqlLastBatch);
        $lastBatch = null;
        if ($res && ($row = $res->FetchRow()) && isset($row['max_batch']) && $row['max_batch'] !== null) {
            $lastBatch = (int)$row['max_batch'];
        }

        if (!$lastBatch || $lastBatch <= 0) {
            return [
                'success'    => true,
                'rolledBack' => [],
                'message'    => 'Tidak ada migrasi yang perlu di-rollback.'
            ];
        }

        // Ambil migrasi pada batch terakhir diurutkan secara terbalik
        $sql = "SELECT `migration` FROM `migrations` WHERE `batch` = {$lastBatch} ORDER BY `id` DESC";
        $res = $this->db->query($sql);
        $toRollback = [];
        if ($res) {
            while ($row = $res->FetchRow()) {
                $toRollback[] = $row['migration'];
            }
        }

        $rolledBack = [];
        $errors = [];

        foreach ($toRollback as $filename) {
            $filePath = $this->migrationsDir . '/' . $filename;
            if (!file_exists($filePath)) {
                $errors[] = [
                    'file'    => $filename,
                    'message' => "File migrasi fisik {$filename} tidak ditemukan."
                ];
                continue;
            }

            try {
                $migrationInstance = require $filePath;
                if (is_object($migrationInstance) && method_exists($migrationInstance, 'down')) {
                    $migrationInstance->down($this->db);
                }

                // Hapus dari tabel migrations
                $sqlDelete = "DELETE FROM `migrations` WHERE `migration` = '{$filename}'";
                $this->db->query($sqlDelete);

                $rolledBack[] = $filename;
            } catch (Throwable $e) {
                $errors[] = [
                    'file'    => $filename,
                    'message' => $e->getMessage()
                ];
                break;
            }
        }

        return [
            'success'    => empty($errors),
            'batch'      => $lastBatch,
            'rolledBack' => $rolledBack,
            'errors'     => $errors
        ];
    }

    /**
     * Dapatkan status seluruh file migrasi
     */
    public function getStatus() {
        $ran = $this->getRanMigrations();
        $allFiles = $this->getAllMigrationFiles();

        $statusList = [];
        foreach ($allFiles as $fileInfo) {
            $filename = $fileInfo['filename'];
            $isRan = isset($ran[$filename]);

            $statusList[] = [
                'file'       => $filename,
                'status'     => $isRan ? 'Ran' : 'Pending',
                'batch'      => $isRan ? $ran[$filename]['batch'] : null,
                'applied_at' => $isRan ? $ran[$filename]['applied_at'] : null
            ];
        }

        return $statusList;
    }

    /**
     * Buat file migrasi baru (generator)
     */
    public function make($name) {
        $cleanName = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower(trim($name)));
        $cleanName = trim($cleanName, '_');
        if (empty($cleanName)) {
            $cleanName = 'new_migration';
        }

        $timestamp = date('Y_m_d_His');
        $fileName = "{$timestamp}_{$cleanName}.php";
        $filePath = $this->migrationsDir . '/' . $fileName;

        $template = <<<PHP
<?php

return new class {
    /**
     * Jalankan migrasi (Ubah / Tambah struktur database).
     *
     * @param Database \$db Instance koneksi database
     */
    public function up(\$db)
    {
        // Contoh:
        // \$db->query("ALTER TABLE tbl_barang ADD COLUMN barcode VARCHAR(100) NULL AFTER barang_nama");
        // \$db->query("CREATE TABLE IF NOT EXISTS tbl_contoh (...)");
    }

    /**
     * Batalkan migrasi (Kembalikan ke struktur sebelumnya).
     *
     * @param Database \$db Instance koneksi database
     */
    public function down(\$db)
    {
        // Contoh:
        // \$db->query("ALTER TABLE tbl_barang DROP COLUMN barcode");
        // \$db->query("DROP TABLE IF EXISTS tbl_contoh");
    }
};

PHP;

        file_put_contents($filePath, $template);

        return [
            'filename' => $fileName,
            'filepath' => $filePath
        ];
    }
}
