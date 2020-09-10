<?php
session_start();
date_default_timezone_set('Asia/Yekaterinburg');
require_once($_SERVER['DOCUMENT_ROOT']."/core/connect.php");

$url = $_SERVER['REQUEST_URI'];
$replace = array("?", "=");
$url = str_replace($replace, "/", $url);
$url = explode("/", $url);

$module = $url[2];

if ($module != '') {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/manual/header.html");
    getCategory($module);
    require_once($_SERVER['DOCUMENT_ROOT'] . "/manual/footer.html");
} else {
    header('HTTP/1.1 200 OK');
    header('Location: https://'.$_SERVER['HTTP_HOST'].'/guide/');
    exit();
}

// Получить файлы для раздела справочника
function getCategory($module) {

    $sidebar = $_SERVER['DOCUMENT_ROOT'] . 'manual/category/'.$module.'/'.$module.'_sidebar.html';
    $main = $_SERVER['DOCUMENT_ROOT'] . 'manual/category/'.$module.'/'.$module.'.html';

    if(file_exists($sidebar)) {
        require_once($sidebar);
    }

    if(file_exists($main)) {
        require_once($main);
    }

    // директория, в которой хранятся подразделы справочника
    $dir = $_SERVER['DOCUMENT_ROOT'].'manual/category/'.$module.'/list/';

    if(is_dir($dir)) {
        $catalog = opendir($dir);

        $files = array();

        while ($file = readdir($catalog)) {
            if ($file != "." && $file != ".." && $file != '.gitkeep' && $file) {
                array_push($files, $file);
            }
        }
        closedir($catalog);
        asort($files);

        foreach ($files as $file) {
            require_once($dir.$file);
        }
    }
}