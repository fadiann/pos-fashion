<?php
$login  = new Login();
// die();
if(isset($_POST['login'])){
    $username   = strip_tags($_POST['username']);
    $password   = strip_tags(md5($_POST['password']));
    $auth       = $login->userLogin($username, $password);
    $rows       = $login->userSession($username, $password);
    if($auth == 1){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['userId']    = $rows[0];
        $_SESSION['username']  = $rows[1];
        $_SESSION['userLevel'] = $rows[3];
        header("location:index.php");
    }
}
if(isset($_GET['main'])){
    $main   = strip_tags($_GET['main']);
    if($main == "logout"){
        $logout = $login->userLogout();
        header("location: index.php");
    }
}
?>