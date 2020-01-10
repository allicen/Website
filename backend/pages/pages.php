<?php
require_once($_SERVER['DOCUMENT_ROOT']."/core/connect.php");
$id = explode('=', $url[4])[1];
$actionType = explode('=', $url[5]);

$action = 'Редактировать запись';
$name = '';
$link = '';
$title = '';
$description = '';
$text = '';
$date = (string) date("Y-m-d");

function getLink($connect, $linkId){
    $link = '';
    if($query = mysqli_query($connect, "SELECT * FROM links WHERE `id` = ".$linkId."") and $row = mysqli_fetch_assoc($query) and $row != '') {
        $link = $row['link'];
    }
    return $link;
}

function getSelect($connect, $linkId){
    $selectOut = '<select required name="link">';
    if($query = mysqli_query($connect, "SELECT * FROM links") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)){
            if($row['link'] != ''){
                $selected = '';
                if($row['id'] == $linkId){
                    $selected = ' selected';
                }
                $selectOut .= '<option value="'.$row['id'].'"'.$selected.'>'.$row['link'].'</option>';
            }
        }
    }
    $selectOut .= '</select>';
    return $selectOut;
}


if($actionType[1] == 'edit'){
    if($query = mysqli_query($connect, "SELECT * FROM content WHERE id = ".$id."") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)){
            $name = $row['name'];
            $links = getSelect($connect, $row['link_id']);
            $title = $row['title'];
            $description = $row['description'];
            $text = $row['text'];
        }
    }else{
        $info = '<div class="red info">Проверьте корректность подключения к БД.</div>';
    }

    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $name = $_POST['name'];
        $link_id = $_POST['link'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $text = $_POST['text'];
        if($query = mysqli_query($connect, "UPDATE `content` SET 
                                                        `name` = '$name', 
                                                        `link_id` = '$link_id', 
                                                        `title` = '$title', 
                                                        `description` = '$description', 
                                                        `text` = '$text',
                                                        `date` = '$date'
                                                        WHERE `content`.`id` = ".$id."") and ($query)){
            $link = getLink($connect, $link_id);
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
            $link = getLink($connect, $row['link_id']);
            $out .= '<tr>';
            $out .= '
                <td><a name="'.$row['link'].'"></a>'.$row['id'].'</td>
                <td>'.$row['name'].'</td>
                <td>'.$link.'</td>
                <td>'.$row['title'].'</td>
                <td>'.$row['description'].'</td>
                <td><a href="?id='.$row['id'].'&action=edit" class="img"><img src="/img/edit.png" alt="Редактировать" title="Редактировать" class="icon"></a>
                <a href="/'.$link.'/" target="_blank" class="img"><img src="/img/go.png" alt="Открыть в новой вкладке" title="Открыть в новой вкладке" class="icon"></a>
                </td>';
            $out .= '</tr>';
        }
    }
}

require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/pages.html");