<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/backend/functions.php");
if(isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['table']) && !empty($_GET['table'])){
    $id = $_GET['id'];
    $table = $_GET['table'];
    changeStatusAjax($connect, $id, $table);
    exit;
}
