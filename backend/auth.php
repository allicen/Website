<?php
$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
if(!isset($_SESSION)) {
    session_start();
}

if(!$_SESSION['admin']){
    header("Location: ".$protocol.$_SERVER['HTTP_HOST']."/backend/login.php");
    exit;
}

if(isset($url[3]) and $url[3] == 'logout'){
    unset($_SESSION['admin']);
    session_destroy();
    header("Location: ".$protocol.$_SERVER['HTTP_HOST']."/backend/login.php");
    exit;
}