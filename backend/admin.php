<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backend/auth.php");
$userName = $_SESSION['user'];
$out = '';

$url = $_SERVER['REQUEST_URI'];
$delete = array('?', '=');
$url = explode('/', str_replace($delete, '/', $url));

if($url[2] == ''){
    $out = 'Вы успешно авторизовались как <strong>'.$userName .'</strong>';
}


switch ($url[2]){
    case ('options'):
        $out = 'Опции';
        break;
    case ('pages'):
        $out = 'Страницы';
        break;
    case ('blog'):
        $out = 'Блог';
        break;
    case ('portfolio'):
        $out = 'Портфолио';
        break;
    case ('files'):
        $out = 'Файлы';
        break;
}



//print_r(get_defined_vars());

require_once($_SERVER['DOCUMENT_ROOT']."/backend/admin.html");