<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backend/functions.php");

$action = 'Добавление записи';
$name = '';
$text = '';
$anons = '';
$category = '';
$picture = '';
$title = '';
$description = '';
$link = '';
$status = '';

if($url[3] == '' && $actionType != 'edit'){
    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $name = $_POST['name'];
        $text = $_POST['text'];
        $anons = $_POST['anons'];
        $categoryId = getSelectId($connect, 'blog_category', $_POST['id']);
        $picture = $_POST['picture'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $link = $_POST['link'];
        $status = $_POST['status'];

        if($query = mysqli_query($connect, "INSERT INTO `blog_posts` (`id`, `h1`, `text`, `anons`, `category_id`, `picture`, `date`, `title`, `description`, `link`, `status`) 
                                                   VALUES (NULL, '$name',  '$text', '$anons', '$categoryId', '$picture', '$date', '$title', '$description', '$link', '$status')") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно добавлена! <a href="#'.$link.'">Посмотреть</a> или <a href="/admin/blog/#edit">добавить еще одну</a></div>';
        }else{
            $info = '<div class="red info">Запись не была добавлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}


if($actionType == 'delete'){
    $info = delete($connect, $id, 'blog_posts', '/admin/blog/');
}

if($actionType == 'edit'){
    $action = 'Редактирование записи';
    if($query = mysqli_query($connect, "SELECT * FROM blog_posts WHERE id = '$id'") and $row = mysqli_fetch_assoc($query) and $row != '') {
        $name = $row['h1'];
        $text = $row['text'];
        $anons = $row['anons'];
        $picture = $row['picture'];
        $title = $row['title'];
        $description = $row['description'];
        $link = $row['link'];
        $status = getStatus($row['status']);
        $allCategory = getSelect($connect, $row['category_id'], 'blog_category');
    }else{
        $info = '<div class="red info">Проверьте корректность подключения к БД.</div>';
    }

    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $name = $_POST['name'];
        $text = $_POST['text'];
        $anons = $_POST['anons'];
        $category = $_POST['select'];
        $picture = $_POST['picture'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $link = $_POST['link'];
        $status = $_POST['status'];

        if($query = mysqli_query($connect, "UPDATE `blog_posts` SET 
                                                        `h1` = '$text', 
                                                        `text` = '$link', 
                                                        `anons` = '$anons',
                                                        `category_id` = '$category', 
                                                        `picture` = '$picture', 
                                                        `date` = '$date', 
                                                        `title` = '$title', 
                                                        `description` = '$description', 
                                                        `link` = '$link', 
                                                        `status` = '$status'
                                                        WHERE `blog_posts`.`id` = $id") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно отредактирована! <a href="#'.$link.'">Посмотреть</a> или <a href="/admin/portfolio/#edit"> добавить еще одну</a></div>';
        }else{
            $info = '<div class="red info">Запись не была обновлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}

if($query = mysqli_query($connect, "SELECT * FROM blog_posts") and mysqli_fetch_assoc($query) !=''){
    mysqli_data_seek($query, 0);
    while($row = mysqli_fetch_assoc($query)){
        $status = getStatus($row['status']);
        $go = $row['status'] == '1' ? '<a href="/portfolio/'.$row['link'].'/" target="_blank" class="img"><img src="/img/go.png" alt="Открыть в новой вкладке" title="Открыть в новой вкладке" class="icon"></a>' : '';

        $category = getLink($connect, $row['category_id'], 'blog_category');
        if($actionType != 'edit'){
            $allCategory = getAllSelect($connect, 'blog_category');
        }

        $out .= '<tr>';
        $out .= '
                <td><a name="'.$row['id'].'"></a>'.$row['id'].'</td>
                <td>'.$row['h1'].'</td>
                <td>'.$row['link'].'</td>
                <td>'.$status.'</td>
                <td>'.$row['picture'].'</td>
                <td>'.$category.'</td>
                <td>'.$row['title'].'</td>
                <td>'.$date.'</td>
                <td>
                    <div class="nowrap">
                        <a href="?id='.$row['id'].'&action=edit" class="img"><img src="/img/edit.png" alt="Редактировать" title="Редактировать" class="icon"></a>
                        <a href="?id='.$row['id'].'&action=delete" class="img"><img src="/img/delete.png" alt="Удалить" onclick="return deleteCheck();" title="Удалить" class="icon"></a>
                        '.$go.'
                    </div>
                </td>
            ';
        $out .= '</tr>';
    }
}

require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/blog-header.html");
require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/blog.html");
