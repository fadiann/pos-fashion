<?php
require_once dirname(__DIR__) . "/lib/AuthSession.php";
$login  = new Login();
// die();
if(isset($_POST['login'])){
    $username   = strip_tags($_POST['username']);
    $password   = strip_tags(md5($_POST['password']));
    $auth       = $login->userLogin($username, $password);
    $rows       = $login->userSession($username, $password);
    if($auth == 1){
        AuthSession::setLogin($rows[0], $rows[1], $rows[3]);
        header("location:index.php");
        exit;
    }
}
if(isset($_GET['main'])){
    $main   = strip_tags($_GET['main']);
    if($main == "logout"){
        AuthSession::logout();
        header("location: index.php");
        exit;
    }
}
?>