<?php
require_once __DIR__ . '/LogSistemModel.php';
require_once dirname(__DIR__, 2) . '/lib/RoleManager.php';

$logModel = new LogSistemModel();
$action = $Helpers->xss_clean($_REQUEST['action'] ?? '');
$message = null;
$messageType = 'info';

if ($action === 'purge') {
    $days = (int)($_POST['days'] ?? 30);
    $logModel->purgeOldLogs($days);
    if ($days <= 0) {
        header("Location: index.php?main=log_sistem&success=purged_all");
    } else {
        AppLogger::action('sistem', 'Bersihkan Log', "Menghapus log yang berumur lebih dari {$days} hari.", ['days' => $days]);
        header("Location: index.php?main=log_sistem&success=purged&days={$days}");
    }
    exit;
} elseif ($action === 'postdelete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $logModel->deleteLog($id);
    }
    header("Location: index.php?main=log_sistem&success=deleted");
    exit;
} elseif ($action === 'postdelete_bulk') {
    $ids = $_POST['log_ids'] ?? [];
    if (!empty($ids) && is_array($ids)) {
        $logModel->deleteMultipleLogs($ids);
        $cnt = count($ids);
        header("Location: index.php?main=log_sistem&success=bulk_deleted&count={$cnt}");
    } else {
        header("Location: index.php?main=log_sistem");
    }
    exit;
}

if (isset($_GET['success'])) {
    if ($_GET['success'] === 'purged_all') {
        $message = "Seluruh riwayat log sistem berhasil dikosongkan!";
        $messageType = 'success';
    } elseif ($_GET['success'] === 'purged') {
        $days = (int)($_GET['days'] ?? 0);
        $message = "Log yang berumur lebih dari {$days} hari berhasil dibersihkan!";
        $messageType = 'success';
    } elseif ($_GET['success'] === 'deleted') {
        $message = "1 baris rekaman log berhasil dihapus!";
        $messageType = 'success';
    } elseif ($_GET['success'] === 'bulk_deleted') {
        $cnt = (int)($_GET['count'] ?? 0);
        $message = "Berhasil menghapus {$cnt} rekaman log terpilih!";
        $messageType = 'success';
    }
}

// Filter parameters
$filters = [
    'level'     => $Helpers->xss_clean($_GET['level'] ?? ''),
    'category'  => $Helpers->xss_clean($_GET['category'] ?? ''),
    'username'  => $Helpers->xss_clean($_GET['username'] ?? ''),
    'search'    => $Helpers->xss_clean($_GET['search'] ?? ''),
    'date_from' => $Helpers->xss_clean($_GET['date_from'] ?? ''),
    'date_to'   => $Helpers->xss_clean($_GET['date_to'] ?? '')
];

$page = max(1, (int)($_GET['page'] ?? 1));
$limit = (int)($_GET['per_page'] ?? 15);
if ($limit < 5 || $limit > 200) $limit = 15;
$offset = ($page - 1) * $limit;

$totalRows = $logModel->countLogs($filters);
$totalPages = max(1, ceil($totalRows / $limit));
$logs = $logModel->getLogs($filters, $limit, $offset);
$summary = $logModel->getSummary();

include_once __DIR__ . '/log_sistem-view.php';
