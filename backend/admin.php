<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backend/auth.php");
$userName = $_SESSION['user'];
$out = '';
$page = '';

$url = $_SERVER['REQUEST_URI'];
$delete = array('?', '&');
$url = explode('/', str_replace($delete, '/', $url));

if($url[2] == ''){
    $page = 'Вы успешно авторизовались как <strong>'.$userName .'</strong>';
}else{
    $page = 'Вы находитесь на странице: ';
}

if($url[2] == ''){
    $out = 'Вы авторизовались как <strong>'.$userName.'</strong>.<br><br>';
    $out .= 'Выберите раздел для редактирования.';
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/backend/templates/header.html");

switch ($url[2]){
    case ('options'):
        $page .= '<strong>Опции</strong>';
        require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/options.php");
        break;
    case ('pages'):
        $page .= '<strong>Страницы</strong>';
        require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/pages.php");
        break;
    case ('main'):
        $page .= '<strong>Главная</strong>';
        require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/main.php");
        break;
    case ('blog'):
        $page .= '<strong>Блог</strong>';
        if($url[3] == 'category'){
            require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/category.php");
        }else{
            require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/blog.php");
        }
        break;
    case ('portfolio'):
        $page .= '<strong>Портфолио</strong>';
        if($url[3] == 'technologies'){
            require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/technologies.php");
        }else{
            require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/portfolio.php");
        }
        break;
    case ('files'):
        $page .= '<strong>Файлы</strong>';
        break;
}

//print_r(get_defined_vars());
require_once($_SERVER['DOCUMENT_ROOT'] . "/backend/templates/footer.html");