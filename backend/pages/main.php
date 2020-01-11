<?php
require_once($_SERVER['DOCUMENT_ROOT']."/core/connect.php");
$id = explode('=', $url[4])[1];
$actionType = explode('=', $url[5])[1];

$link = '';
$name = '';
$title = '';
$text = '';

if($actionType == 'delete'){
    if($query = mysqli_query($connect, "DELETE FROM `top_menu` WHERE `top_menu`.`id` = ".$id."") and ($query)){
        $info = '<div class="green info">Запись <strong>'.$id.'</strong> успешно удалена! <a href="/admin/main/#edit">Добавить новую</a></div>';
    }else{
        $info = '<div class="red info">Произошал ошибка подключения к БД.</div>';
    }
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