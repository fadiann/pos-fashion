<?php
require_once modules."/pengaturan/PengaturanModel.php";
$pengaturan = new PengaturanModel();
@$action    = $Helpers->xss_clean($_REQUEST['action']);

switch ($action) {
    case 'postadd': 
        $username  = strtolower(str_replace(' ', '', trim($Helpers->xss_clean($_REQUEST['username']))));
        $password  = $Helpers->xss_clean($_REQUEST['password']);
        $cpassword = $Helpers->xss_clean($_REQUEST['cpassword']);
        $level     = $Helpers->xss_clean($_REQUEST['level']);

        if ($password !== $cpassword) {
            header("location:index.php?main=pengguna&error=password_mismatch");
            exit;
        }

        $passwd  = md5($password);
        $execute = $pengaturan->create_user($username, $passwd, $level);
        header("location:index.php?main=pengguna&success");
        break;

    case 'postedit': 
        $user_id   = $Helpers->xss_clean($_REQUEST['user_id']);
        $username  = strtolower(str_replace(' ', '', trim($Helpers->xss_clean($_REQUEST['username']))));
        $password  = $Helpers->xss_clean($_REQUEST['password']);
        $cpassword = $Helpers->xss_clean($_REQUEST['cpassword']);
        $level     = $Helpers->xss_clean($_REQUEST['level']);

        if (!empty($password)) {
            if ($password !== $cpassword) {
                header("location:index.php?main=pengguna&error=password_mismatch");
                exit;
            }
            $passwd  = md5($password);
            $execute = $pengaturan->update_user_pass($user_id, $username, $passwd, $level);
        } else {
            $execute = $pengaturan->update_user($user_id, $username, $level);
        }
        header("location:index.php?main=pengguna&success");
        break;

    case 'postdelete': 
        $user_id = $Helpers->xss_clean($_REQUEST['user_id']);
        // Cegah menghapus akun yang sedang login atau superadmin (id 1)
        if ($user_id != '1' && (!isset($_SESSION['userId']) || $_SESSION['userId'] != $user_id)) {
            $execute = $pengaturan->delete_user($user_id);
            header("location:index.php?main=pengguna&success");
        } else {
            header("location:index.php?main=pengguna&error=cannot_delete");
        }
        break;

    default: 
        $data = $pengaturan->getUserAll();
        include_once modules."/pengaturan/pengguna-list.php";
        break;
}