<?php
    if($module == '404'){
        $blogCategory = '';
    }
    $breadCrumb .= '<a href="/">Главная</a> / '.$pageName;
    if($pageName != ''){
        $pageName = '<h1>'.$pageName.'</h1>';
    }
    require_once($_SERVER['DOCUMENT_ROOT']."/templates/page.html");