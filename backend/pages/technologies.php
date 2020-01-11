<?php
require_once($_SERVER['DOCUMENT_ROOT']."/core/connect.php");
$id = explode('=', $url[5])[1];
$actionType = explode('=', $url[6])[1];

$page .= ' (технологии)';
$action = 'Добавление записи';
$name = '';

if($url[4] == '' && $actionType != 'edit'){
    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $name = $_POST['name'];
        if($query = mysqli_query($connect, "INSERT INTO `technologies` (`id`, `name`) 
                                                   VALUES (NULL, '$name')") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно добавлена! <a href="#'.$id.'">Посмотреть</a> или <a href="/admin/portfolio/technologies/#edit">добавить еще одну</a></div>';
        }else{
            $info = '<div class="red info">Запись не была добавлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}

if($actionType == 'edit'){
    $action = 'Редактирование записи';
    if($query = mysqli_query($connect, "SELECT * FROM technologies WHERE id = ".$id."") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)){
            $name = $row['name'];
        }
    }else{
        $info = '<div class="red info">Проверьте корректность подключения к БД.</div>';
    }
    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $name = $_POST['name'];
        if($query = mysqli_query($connect, "UPDATE `technologies` SET `name` = '$name' WHERE `technologies`.`id` = ".$id."") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно отредактирована! <a href="#'.$id.'">Посмотреть</a></div>';
        }else{
            $info = '<div class="red info">Запись не была обновлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}

if($actionType == 'delete'){
    if($query = mysqli_query($connect, "DELETE FROM `technologies`WHERE `technologies`.`id` = ".$id."") and ($query)){
        $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно удалена! <a href="/admin/portfolio/technologies/#edit">Добавить новую</a></div>';
    }else{
        $info = '<div class="red info">Произошал ошибка подключения к БД.</div>';
    }
}

if($query = mysqli_query($connect, "SELECT * FROM technologies") and mysqli_fetch_assoc($query) !=''){
    mysqli_data_seek($query, 0);
    while($row = mysqli_fetch_assoc($query)){
        $out .= '<tr>';
        $out .= '
                <td><a name="'.$row['id'].'"></a>'.$row['id'].'</td>
                <td>'.$row['name'].'</td>
                <td>
                    <div class="nowrap">
                        <a href="?id='.$row['id'].'&action=edit" class="img"><img src="/img/edit.png" alt="Редактировать" title="Редактировать" class="icon"></a>
                        <a href="?id='.$row['id'].'&action=delete" class="img"><img src="/img/delete.png" alt="Удалить" onclick="return deleteCheck();" title="Удалить" class="icon"></a>
                    </div>
                </td>
            ';
        $out .= '</tr>';
    }
}


require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/portfolio-header.html");
require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/technologies.html");