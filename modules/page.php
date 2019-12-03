<?php
    if($module == '404'){
        $blogCategory = '';
    }
    $breadCrumb .= '<a href="/">Главная</a> / '.$pageName;
    require_once($_SERVER['DOCUMENT_ROOT']."/templates/page.html");