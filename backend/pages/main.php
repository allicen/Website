<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backend/functions.php");

$action = 'Добавление записи';
$link = '';
$name = '';
$title = '';
$text = '';

if($url[4] == '' && $actionType != 'edit'){
    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $name = $_POST['name'];
        $link = $_POST['link'];
        $title = $_POST['title'];
        $text = $_POST['text'];
        if($query = mysqli_query($connect, "INSERT INTO `top_menu` (`id`, `name`, `link`, `title`, `text`) 
                                                   VALUES (NULL, '$name', '$link', '$title', '$text')") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно добавлена! <a href="#'.$id.'">Посмотреть</a> или <a href="/admin/portfolio/technologies/#edit">добавить еще одну</a></div>';
        }else{
            $info = '<div class="red info">Запись не была добавлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}

if($actionType == 'edit'){
    $action = 'Редактирование записи';
    if($query = mysqli_query($connect, "SELECT * FROM top_menu WHERE id = ".$id."") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)){
            $name = $row['name'];
            $link = $row['link'];
            $title = $row['title'];
            $text = $row['text'];
        }
    }else{
        $info = '<div class="red info">Проверьте корректность подключения к БД.</div>';
    }
    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $name = $_POST['name'];
        $link = $_POST['link'];
        $title = $_POST['title'];
        $text = $_POST['text'];
        if($query = mysqli_query($connect, "UPDATE `top_menu` SET `link` = '$link', `name` = '$name', `title` = '$title', `text` = '$text' WHERE `top_menu`.`id` = ".$id."") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно отредактирована! <a href="#'.$id.'">Посмотреть</a></div>';
        }else{
            $info = '<div class="red info">Запись не была обновлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}


if($actionType == 'delete'){
    $info = delete($connect, $id, 'top_menu', '/admin/main/');
}

if($query = mysqli_query($connect, "SELECT * FROM top_menu") and mysqli_fetch_assoc($query) !=''){
    mysqli_data_seek($query, 0);
    while($row = mysqli_fetch_assoc($query)){
        $out .= '<tr>';
        $out .= '
            <td><a name="'.$row['id'].'"></a>'.$row['id'].'</td>
            <td>'.$row['link'].'</td>
            <td>'.$row['name'].'</td>
            <td>'.$row['title'].'</td>
            <td>'.$row['text'].'</td>
            <td>
                <div class="nowrap">
                    <a href="?id='.$row['id'].'&action=edit" class="img"><img src="/img/edit.png" alt="Редактировать" title="Редактировать" class="icon"></a>
                    <a href="?id='.$row['id'].'&action=delete" class="img"><img src="/img/delete.png" alt="Удалить" onclick="return deleteCheck();" title="Удалить" class="icon"></a>
                </div>
            </td>';
        $out .= '</tr>';
    }
}

require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/main.html");