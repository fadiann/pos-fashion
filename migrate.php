<?php
/**
 * CLI Database Migration Runner untuk bakulsepatusolo
 * 
 * Penggunaan:
 *   php migrate.php                 -> Jalankan semua migrasi yang belum dieksekusi
 *   php migrate.php rollback        -> Batalkan migrasi batch terakhir
 *   php migrate.php status          -> Lihat status seluruh file migrasi
 *   php migrate.php make <nama>     -> Buat file migrasi baru
 */

if (php_sapi_name() !== 'cli') {
    die("Script ini hanya dapat dijalankan melalui terminal / Command Line Interface (CLI).\n");
}

chdir(__DIR__);
require_once 'Adodb/adodb.inc.php';
require_once 'app/config/Database.php';
require_once 'app/lib/Migrator.php';

$migrator = new Migrator();

$command = isset($argv[1]) ? strtolower(trim($argv[1])) : 'migrate';
$param   = isset($argv[2]) ? trim($argv[2]) : null;

// Normalisasi perintah jika ada awalan migrate: atau make:
if ($command === 'migrate:rollback') $command = 'rollback';
if ($command === 'migrate:status')   $command = 'status';
if ($command === 'make:migration')   $command = 'make';

echo "========================================================\n";
echo "   BAKULSEPATUSOLO - DATABASE MIGRATION SYSTEM\n";
echo "========================================================\n";

switch ($command) {
    case 'migrate':
    case 'up':
        echo "Menjalankan migrasi database...\n";
        $result = $migrator->migrate();

        if (empty($result['migrated']) && empty($result['errors'])) {
            echo "-> Tidak ada migrasi baru untuk dijalankan (Database sudah up-to-date).\n";
        } else {
            foreach ($result['migrated'] as $file) {
                echo "[MIGRATED] {$file} (Batch: {$result['batch']})\n";
            }
            if (!empty($result['errors'])) {
                echo "\n[ERROR] Terjadi kesalahan saat migrasi:\n";
                foreach ($result['errors'] as $err) {
                    echo "  - File: {$err['file']}\n    Pesan: {$err['message']}\n";
                }
            } else {
                echo "\n-> Berhasil! Total " . count($result['migrated']) . " migrasi berhasil diterapkan pada Batch {$result['batch']}.\n";
            }
        }
        break;

    case 'rollback':
    case 'down':
        echo "Melakukan rollback batch terakhir...\n";
        $result = $migrator->rollback();

        if (empty($result['rolledBack']) && empty($result['errors'])) {
            echo "-> " . ($result['message'] ?? 'Tidak ada migrasi yang di-rollback.') . "\n";
        } else {
            foreach ($result['rolledBack'] as $file) {
                echo "[ROLLED BACK] {$file}\n";
            }
            if (!empty($result['errors'])) {
                echo "\n[ERROR] Terjadi kesalahan saat rollback:\n";
                foreach ($result['errors'] as $err) {
                    echo "  - File: {$err['file']}\n    Pesan: {$err['message']}\n";
                }
            } else {
                echo "\n-> Berhasil! Total " . count($result['rolledBack']) . " migrasi pada Batch {$result['batch']} berhasil di-rollback.\n";
            }
        }
        break;

    case 'status':
        echo "Status Migrasi Database:\n";
        $list = $migrator->getStatus();

        if (empty($list)) {
            echo "Belum ada file migrasi di folder database/migrations/.\n";
            echo "Gunakan: php migrate.php make <nama_migrasi> untuk membuat file pertama.\n";
        } else {
            printf("%-4s | %-45s | %-10s | %-6s | %-20s\n", "No", "Nama File Migrasi", "Status", "Batch", "Tanggal Diterapkan");
            echo str_repeat("-", 95) . "\n";
            $no = 1;
            foreach ($list as $item) {
                printf(
                    "%-4d | %-45s | %-10s | %-6s | %-20s\n",
                    $no++,
                    substr($item['file'], 0, 45),
                    $item['status'] === 'Ran' ? '[Ran]' : '[Pending]',
                    $item['batch'] !== null ? $item['batch'] : '-',
                    $item['applied_at'] !== null ? $item['applied_at'] : '-'
                );
            }
        }
        break;

    case 'make':
        if (!$param) {
            echo "[ERROR] Harap sertakan nama migrasi!\nContoh: php migrate.php make tambah_kolom_catatan_barang\n";
            exit(1);
        }
        $created = $migrator->make($param);
        echo "[CREATED] File migrasi baru berhasil dibuat:\n";
        echo "Path: {$created['filepath']}\n";
        break;

    case 'help':
    default:
        echo "Perintah yang tersedia:\n";
        echo "  php migrate.php                 -> Jalankan migrasi pending (up)\n";
        echo "  php migrate.php rollback        -> Batalkan migrasi batch terakhir (down)\n";
        echo "  php migrate.php status          -> Cek status seluruh file migrasi\n";
        echo "  php migrate.php make <nama>     -> Buat file migrasi baru\n";
        break;
}
echo "========================================================\n";
