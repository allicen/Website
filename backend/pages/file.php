<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backend/functions.php");

$path = 'user-files';
$dir = $_SERVER['DOCUMENT_ROOT'].getPath($url, $path)[0];
$types = array('image/gif', 'image/png', 'image/jpeg');
$maxSize = 1024000; // В байтах
$max_thumb_size = 200; // В пикселах
$max_size = 1000; // В пикселах
$quality = 75; // Качество
$active = $url[count($url)-1];


$actionType = explode('=', $url[count($url)-1])[0];
$fileDelete =  explode('=', $url[count($url)-1])[1];

if($actionType == 'delete'){
    if($handle = opendir($dir)){
        while(false !== ($file = readdir($handle))) {
            if($file == $fileDelete){
                unlink($dir.$file);
                $save = '<script>location="?delete";</script>';
                break;
            }
        }
    }
}



if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (!in_array($_FILES['picture']['type'], $types)) die('<script>location="?type-error";</script>');
    if($_FILES['picture']['size'] > $maxSize) die('<script>location="?size-error";</script>');
    $name = resize($_FILES['picture'], $quality,  $_POST['size'], $dir);
    if($name){
        $info = '<div class="green info">Изображение <strong><a href="#'.$name.'">'.$name.'</a></strong> загружено успешно. <a href="/'.getPath($url, $path)[0].$name.'" target="_blank">Открыть в новой вкладке</a></div>';
    }else{
        $save = '<script>location="?error";</script>';
    }
}

if($active == 'type-error'){
    $info = '<div class="red info">Запрещенный тип файла!</div>';
}
if($active == 'size-error'){
    $info = '<div class="red info">Выбран слишком большой файл!</div>';
}

if($active == 'error'){
    $info = '<div class="red info">Произошла ошибка при загрузке файла</div>';
}

if($active == 'delete'){
    $info = '<div class="green info">Файл успешно удален</div>';
}


$navigate = '';
$index = 1;


if ($handle = opendir($dir)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != ".." && $file != '.gitkeep' && $file) {
            $isFile = explode('.', $file);
            if (in_array($isFile[count($isFile) - 1], array('jpg', 'jpeg', 'png', 'gif'))) {
                $out .= '<tr>';
                $out .= '<td><a name="' . $file . '"></a>' . $index . '</td>
                <td><img src="/' . getPath($url, $path)[0] . $file . '" alt="' . $file . '" class="thumb"/></td>
                <td><a href="/' . getPath($url, $path)[0] . $file . '" target="_blank">' . $file . '</a></td>
                <td>' . bcdiv(filesize(getPath($url, $path)[0] . $file), '1024', 2) . ' Кб</td>
                <td> <a href="?delete='.$file.'" class="img"><img src="/img/delete.png" alt="Удалить" onclick="return deleteCheck();" title="Удалить" class="icon"></a></td>';
                $out .= '</tr>';
                $index++;
            } else {
                $navigate .= '<div class="folder"><img src="/img/folder.png" alt="Папка" class="icon"><div class="link"><a href="/admin/' . getPath($url, $path)[0] . $file . '/">' . $file . "</a></div></div>";
            }
        }
    }
}




require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/file.html");