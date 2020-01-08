<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backend/auth.php");
$userName = $_SESSION['user'];
$out = '';

$url = $_SERVER['REQUEST_URI'];
$delete = array('?', '=');
$url = explode('/', str_replace($delete, '/', $url));

if($url[2] == ''){
    $out = 'Вы успешно авторизовались как <strong>'.$userName .'</strong>';
}else{
    $out = 'Вы находитесь на странице: ';
}


switch ($url[2]){
    case ('options'):
        $out .= '<strong>Опции</strong>';
        require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/options.php");
        break;
    case ('pages'):
        $out .= '<strong>Страницы</strong>';
        break;
    case ('blog'):
        $out .= '<strong>Блог</strong>';
        break;
    case ('portfolio'):
        $out .= '<strong>Портфолио</strong>';
        break;
    case ('files'):
        $out .= '<strong>Файлы</strong>';
        break;
}



//print_r(get_defined_vars());

require_once($_SERVER['DOCUMENT_ROOT']."/backend/admin.html");