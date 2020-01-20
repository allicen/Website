<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backend/functions.php");

$action = 'Редактировать запись';
$name = '';
$link = '';
$title = '';
$description = '';
$text = '';

if($url[4] == '' && $actionType != 'edit'){
    $links = getAllSelect($connect, 'links');
    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $name = $_POST['name'];
        $link_id = $_POST['select'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $text = replaceImgA($_POST['text']);
        if($query = mysqli_query($connect, "INSERT INTO `content` (`id`, `link_id`, `name`, `text`, `title`, `description`, `date`) 
                                                   VALUES (NULL, '$link_id', '$name', '$text', '$title', '$description', '$date')") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно добавлена!</div>';
        }else{
            $info = '<div class="red info">Запись не была добавлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}

if($actionType == 'edit'){
    if($query = mysqli_query($connect, "SELECT * FROM content WHERE id = ".$id."") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)){
            $name = $row['name'];
            $links = getSelectFromOtherTable($connect, $row['link_id'], 'links');
            $title = $row['title'];
            $description = $row['description'];
            $text = htmlspecialchars($row['text']);
        }
    }else{
        $info = '<div class="red info">Проверьте корректность подключения к БД.</div>';
    }

    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $name = $_POST['name'];
        $link_id = $_POST['select'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $text = replaceImgA($_POST['text']);
        if($query = mysqli_query($connect, "UPDATE `content` SET 
                                                        `name` = '$name', 
                                                        `link_id` = '$link_id', 
                                                        `title` = '$title', 
                                                        `description` = '$description', 
                                                        `text` = '$text',
                                                        `date` = '$date'
                                                        WHERE `content`.`id` = ".$id."") and ($query)){
            $link = getLink($connect, $link_id, 'links');
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно отредактирована! <a href="/'.$link.'/" target="_blank">Посмотреть</a></div>';
        }else{
            $info = '<div class="red info">Запись не была обновлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}

if($query = mysqli_query($connect, "SELECT * FROM content") and mysqli_fetch_assoc($query) !=''){
    mysqli_data_seek($query, 0);
    while($row = mysqli_fetch_assoc($query)){
        if($row['name'] != ''){
            $link = getLink($connect, $row['link_id'], 'links');
            $out .= '<tr>';
            $out .= '
                <td><a name="'.$row['link'].'"></a>'.$row['id'].'</td>
                <td>'.$row['name'].'</td>
                <td>'.$link.'</td>
                <td>'.$row['title'].'</td>
                <td>'.$row['description'].'</td>
                <td>
                    '.editIcon($row['id']).'
                    '.goIcon('/'.$link).'
                </td>';
            $out .= '</tr>';
        }
    }
}

require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/page-header.html");
require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/pages.html");