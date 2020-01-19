<?php
session_start();
$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
$login = '';
$password = '';

if(isset($_SESSION['admin'])){
    header("Location:".$protocol.$_SERVER['HTTP_HOST']."/admin");
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT']."/core/connect.php");
$url = $_SERVER['REQUEST_URI'];
$delete = array('?', '=');
$url = explode('/', str_replace($delete, '/', $url));

if(isset($_POST['submit']) && isset($_POST['submit']) != ''){
    $userName = $_POST['login'];
    if($query = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$userName'") and $row = mysqli_fetch_assoc($query) and $row != ''){
        $login = $row['login'];
        $password = $row['password'];
    }

    if($login === $_POST['login'] && $password === md5($_POST['password'])){
        $_SESSION['admin'] = $login;
        $_SESSION['user'] = $userName;
        header('Location: '.$protocol.$_SERVER['HTTP_HOST'].'/admin/');
        exit;
    }else{
        $note = 'Неверно введен логин и/или пароль!';
    }
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/backend/templates/login.html");
