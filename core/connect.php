<?php
require_once($_SERVER['DOCUMENT_ROOT']."/core/config.php");
$connect = mysqli_connect($host, $login, $password, $db_name);
mysqli_query($connect, "SET NAMES utf8");
