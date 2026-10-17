<?php
require_once dirname(__DIR__) . "/lib/AuthSession.php";
require_once dirname(__DIR__) . "/lib/AppLogger.php";
$login  = new Login();
// die();
if(isset($_POST['login'])){
    $username   = strip_tags($_POST['username']);
    $password   = strip_tags(md5($_POST['password']));
    $auth       = $login->userLogin($username, $password);
    $rows       = $login->userSession($username, $password);
    if($auth == 1){
        AuthSession::setLogin($rows[0], $rows[1], $rows[3]);
        AppLogger::action('auth', 'Login Sukses', "Pengguna {$rows[1]} ({$rows[3]}) berhasil masuk ke sistem.", [
            'username' => $rows[1],
            'level'    => $rows[3]
        ]);
        header("location:index.php");
        exit;
    } else {
        AppLogger::warning('auth', 'Login Gagal', "Percobaan login gagal untuk username: {$username}", [
            'attempted_user' => $username
        ]);
    }
}
if(isset($_GET['main'])){
    $main   = strip_tags($_GET['main']);
    if($main == "logout"){
        $loggedUser = $_SESSION['username'] ?? 'User';
        AppLogger::action('auth', 'Logout', "Pengguna {$loggedUser} keluar dari sistem.");
        AuthSession::logout();
        header("location: index.php");
        exit;
    }
}
?>