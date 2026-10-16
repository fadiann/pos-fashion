<?php
require_once __DIR__ . '/../../lib/RoleManager.php';

// Pastikan hanya admin yang bisa mengelola hak akses role
if (($userLevel ?? '') !== 'admin') {
    header("location:index.php?access_denied=admin_only");
    exit;
}

$action = $Helpers->xss_clean($_REQUEST['action'] ?? '');
$message = null;
$messageType = 'info';

if ($action === 'postsave') {
    // Input format: $_POST['perms']['kasir'] = ['transaksi', ...], $_POST['perms']['owner'] = [...]
    $permsInput = $_POST['perms'] ?? [];
    
    // Simpan untuk kasir dan owner (admin selalu punya akses penuh)
    $rolesToUpdate = ['kasir', 'owner'];
    foreach ($rolesToUpdate as $r) {
        $allowed = isset($permsInput[$r]) && is_array($permsInput[$r]) ? $permsInput[$r] : [];
        RoleManager::saveRolePermissions($r, $allowed);
    }

    $message = "Hak akses peran pengguna (Role Permissions) berhasil diperbarui!";
    $messageType = "success";
} elseif ($action === 'postreset') {
    RoleManager::resetToDefault();
    $message = "Hak akses seluruh grup role berhasil dikembalikan ke pengaturan default pabrik!";
    $messageType = "success";
}

$modules = RoleManager::getModules();
$roles   = RoleManager::getRoles();
$matrix  = RoleManager::getPermissionMatrix();

include_once __DIR__ . '/role-akses-view.php';
