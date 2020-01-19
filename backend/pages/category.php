<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backend/functions.php");

$id = explode('=', $url[5])[1];
$actionType = explode('=', $url[6])[1];

$page .= ' (категории)';
$action = 'Добавление записи';
$name = '';
$link = '';
$title = '';
$description = '';
$text = '';

if($url[4] == '' && $actionType != 'edit'){
    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $name = $_POST['name'];
        $link = $_POST['link'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $text = replaceImgA($_POST['text']);

        if($query = mysqli_query($connect, "INSERT INTO `blog_category` (`id`, `link`, `name`, `title`, `description`, `about`) 
                                                   VALUES (NULL, '$link', '$name', '$title', '$description', '$text')") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно добавлена! <a href="#'.$id.'">Посмотреть</a> или <a href="/admin/blog/category/#edit">добавить еще одну</a></div>';
        }else{
            $info = '<div class="red info">Запись не была добавлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}

if($actionType == 'edit'){
    $action = 'Редактирование записи';
    if($query = mysqli_query($connect, "SELECT * FROM blog_category WHERE id = ".$id."") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)){
            $name = $row['name'];
            $link = $row['link'];
            $title = $row['title'];
            $description = $row['description'];
            $text = htmlspecialchars($row['about']);
        }
    }else{
        $info = '<div class="red info">Проверьте корректность подключения к БД.</div>';
    }
    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $name = $_POST['name'];
        $link = $_POST['link'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $text = replaceImgA($_POST['text']);
        if($query = mysqli_query($connect, "UPDATE `blog_category` SET `link` = '$link', `name` = '$name', `title` = '$title', `description` = '$description', `about` = '$text' WHERE `blog_category`.`id` = ".$id."") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно отредактирована! <a href="#'.$id.'">Посмотреть</a></div>';
        }else{
            $info = '<div class="red info">Запись не была обновлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}


if($actionType == 'delete'){
    $info = delete($connect, $id, 'blog_category', '/admin/blog/category/');
}

if($query = mysqli_query($connect, "SELECT * FROM blog_category") and mysqli_fetch_assoc($query) !=''){
    mysqli_data_seek($query, 0);
    while($row = mysqli_fetch_assoc($query)){
        $out .= '<tr>';
        $out .= '
                <td><a name="'.$row['id'].'"></a>'.$row['id'].'</td>
                <td>'.$row['name'].'</td>
                <td>'.$row['link'].'</td>
                <td>'.$row['title'].'</td>
                <td>
                    <div class="nowrap">
                        '.editIcon($row['id']).'
                        '.deleteIcon($row['id']).'
                        '.goIcon('/blog/'.$row['link'].'/').'
                    </div>
                </td>
            ';
        $out .= '</tr>';
    }
}

require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/blog-header.html");
require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/category.html");
