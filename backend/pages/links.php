<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backend/functions.php");

$id = explode('=', $url[5])[1];
$actionType = explode('=', $url[6])[1];

$page .= ' (ссылки на страницы)';
$action = 'Добавление записи';
$name = '';
$link = '';
$module_id = '3';

if($url[4] == '' && $actionType != 'edit'){
    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $name = $_POST['name'];
        $link = $_POST['link'];
        if($query = mysqli_query($connect, "INSERT INTO `links` (`id`, `link`, `name`, `module_id`) 
                                                   VALUES (NULL, '$link', '$name', '$module_id')") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно добавлена!</div>';
        }else{
            $info = '<div class="red info">Запись не была добавлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}

if($actionType == 'edit'){
    $action = 'Редактирование записи';
    if($query = mysqli_query($connect, "SELECT * FROM links WHERE id = ".$id."") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)){
            $name = $row['name'];
            $link = $row['link'];
        }
    }else{
        $info = '<div class="red info">Проверьте корректность подключения к БД.</div>';
    }
    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $name = $_POST['name'];
        if($query = mysqli_query($connect, "UPDATE `links` SET `link` = '$link', `name` = '$name' WHERE `links`.`id` = ".$id."") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно отредактирована!</div>';
        }else{
            $info = '<div class="red info">Запись не была обновлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}

if($actionType == 'delete'){
    if($query = mysqli_query($connect, "SELECT * FROM `links` WHERE `id` = '$id'") and $row = mysqli_fetch_assoc($query) and $row != ''){
        if($module_id == '3'){
            $info = delete($connect, $id, 'links', '');
        }else{
            $info = '<div class="red info">Нельзя удалять НЕ страницы.</div>';
        }
    }
}

if($query = mysqli_query($connect, "SELECT * FROM links") and mysqli_fetch_assoc($query) !=''){
    mysqli_data_seek($query, 0);
    while($row = mysqli_fetch_assoc($query)){
        $moduleName = $row['module_id'] == '3' ? 'Страница' : '-';

        if($row['module_id'] == '3'){
            $out .= '<tr>';
            $out .= '
                <td><a name="'.$row['id'].'"></a>'.$row['id'].'</td>
                <td>'.$row['name'].'</td>
                <td>'.$row['link'].'</td>
                <td>'.$moduleName.'</td>
                <td>
                    <div class="nowrap">
                        '.editIcon($row['id']).'
                        '.deleteIcon($row['id']).'
                        '.goIcon('/'.$row['link']).'
                    </div>
                </td>
            ';
            $out .= '</tr>';
        }
    }
}

require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/page-header.html");
require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/links.html");
