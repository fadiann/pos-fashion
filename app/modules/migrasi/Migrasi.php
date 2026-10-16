<?php
require_once __DIR__ . '/../../lib/Migrator.php';

$migrator = new Migrator();
$action = $Helpers->xss_clean($_REQUEST['action'] ?? '');

$message = null;
$messageType = 'info';

if ($action === 'postmigrate') {
    $result = $migrator->migrate();
    if (empty($result['migrated']) && empty($result['errors'])) {
        $message = "Tidak ada migrasi baru. Struktur database sudah yang terbaru!";
        $messageType = "info";
    } elseif (!empty($result['errors'])) {
        $message = "Gagal menjalankan migrasi: " . $result['errors'][0]['message'];
        $messageType = "error";
    } else {
        $count = count($result['migrated']);
        $message = "Berhasil menerapkan {$count} file migrasi baru pada Batch {$result['batch']}!";
        $messageType = "success";
    }
} elseif ($action === 'postrollback') {
    $result = $migrator->rollback();
    if (empty($result['rolledBack']) && empty($result['errors'])) {
        $message = $result['message'] ?? "Tidak ada migrasi yang di-rollback.";
        $messageType = "info";
    } elseif (!empty($result['errors'])) {
        $message = "Gagal melakukan rollback: " . $result['errors'][0]['message'];
        $messageType = "error";
    } else {
        $count = count($result['rolledBack']);
        $message = "Berhasil membatalkan {$count} file migrasi pada Batch {$result['batch']}!";
        $messageType = "success";
    }
} elseif ($action === 'postmake') {
    $migrationName = trim($Helpers->xss_clean($_POST['migration_name'] ?? ''));
    if (!empty($migrationName)) {
        $created = $migrator->make($migrationName);
        $message = "File migrasi baru '{$created['filename']}' berhasil dibuat di folder database/migrations/!";
        $messageType = "success";
    } else {
        $message = "Nama migrasi tidak boleh kosong!";
        $messageType = "error";
    }
}

$statusList = $migrator->getStatus();
$totalMigrations = count($statusList);
$ranCount = count(array_filter($statusList, fn($item) => $item['status'] === 'Ran'));
$pendingCount = $totalMigrations - $ranCount;

include_once __DIR__ . '/migrasi-view.php';
