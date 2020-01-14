<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backend/functions.php");

$action = 'Редактирование записи';
$name = '';
$value = '';

if($actionType == 'edit'){
    if($query = mysqli_query($connect, "SELECT * FROM options WHERE id = ".$id."") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)){
            $name = $row['name'];
            $value = $row['value'];
        }
    }else{
        $info = '<div class="red info">Проверьте корректность подключения к БД.</div>';
    }
    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $name = $_POST['name'];
        $value = $_POST['value'];
        if($query = mysqli_query($connect, "UPDATE `options` SET `value` = '$value' WHERE `options`.`id` = ".$id."") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно отредактирована! <a href="#'.$name.'">Посмотреть</a></div>';
        }else{
            $info = '<div class="red info">Запись не была обновлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}

if($query = mysqli_query($connect, "SELECT * FROM options") and mysqli_fetch_assoc($query) !=''){
    mysqli_data_seek($query, 0);
    while($row = mysqli_fetch_assoc($query)){
        $out .= '<tr>';
        $out .= '
                <td><a name="'.$row['name'].'"></a>'.$row['id'].'</td>
                <td>'.$row['name'].'</td>
                <td>'.$row['value'].'</td>
                <td>'.editIcon($row['id']).'</td>
            ';
        $out .= '</tr>';
    }
}

require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/options.html");