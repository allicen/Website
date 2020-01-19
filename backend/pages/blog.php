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
$allCategory = '';
$statusSelect = getSelect('', array('0', '1'), array('Черновик', 'Опубликовано'));

if($url[3] == '' && $actionType != 'edit'){
    $allCategory = getAllSelect($connect, 'blog_category');

    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $name = $_POST['name'];
        $text = replaceImgA($_POST['text']);
        $anons = $_POST['anons'];
        $picture = $_POST['picture'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $link = $_POST['link'];
        $status = $_POST['status'];
        $categoryId = $_POST['select'];
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
        $text = htmlspecialchars($row['text']);
        $anons = $row['anons'];
        $picture = $row['picture'];
        $title = $row['title'];
        $description = $row['description'];
        $link = $row['link'];
        $statusSelect = getSelect($row['status'], array('0', '1'), array('Черновик', 'Опубликовано'));
        $allCategory = getSelectFromOtherTable($connect, $row['category_id'], 'blog_category');
    }else{
        $info = '<div class="red info">Проверьте корректность подключения к БД.</div>';
    }

    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $name = $_POST['name'];
        $text = replaceImgA($_POST['text']);
        $anons = $_POST['anons'];
        $category = $_POST['select'];
        $picture = $_POST['picture'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $link = $_POST['link'];
        $status = $_POST['status'];

        if($query = mysqli_query($connect, "UPDATE `blog_posts` SET 
                                                        `h1` = '$name', 
                                                        `text` = '$text', 
                                                        `anons` = '$anons',
                                                        `category_id` = '$category', 
                                                        `picture` = '$picture', 
                                                        `date` = '$date', 
                                                        `title` = '$title', 
                                                        `description` = '$description', 
                                                        `link` = '$link', 
                                                        `status` = '$status'
                                                        WHERE `blog_posts`.`id` = $id") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно отредактирована! <a href="#'.$id.'">Посмотреть</a> или <a href="/admin/portfolio/#edit"> добавить еще одну</a></div>';
        }else{
            $info = '<div class="red info">Запись не была обновлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}

if($query = mysqli_query($connect, "SELECT * FROM blog_posts") and mysqli_fetch_assoc($query) !=''){
    mysqli_data_seek($query, 0);
    while($row = mysqli_fetch_assoc($query)){
        $status = getStatus($row['status']);
        $category = getLink($connect, $row['category_id'], 'blog_category');
        $go = $row['status'] == '1' ? goIcon('/blog/'.$category.'/'.$row['link']) : '';
        if($actionType != 'edit'){
            $allCategory = getAllSelect($connect, 'blog_category');
        }

        $pictureIcon = $row['picture'] != '' ? '<img src="'.$row['picture'].'" class="thumb" alt="icon"/>' : '-';

        $out .= '<tr>';
        $out .= '
                <td><a name="'.$row['id'].'"></a>'.$row['id'].'</td>
                <td>'.$row['h1'].'</td>
                <td>'.$row['link'].'</td>
                <td><a href="#" class="false" onclick="setStatus(event, '.(string)$row['id'].', \'blog_posts\'); return false;" data-id="'.$row['id'].'">'.$status.'</a></td>
                <td>'.$pictureIcon.'</td>
                <td>'.$category.'</td>
                <td>'.$row['title'].'</td>
                <td>'.$date.'</td>
                <td>
                    <div class="nowrap">
                        '.editIcon($row['id']).'
                        '.deleteIcon($row['id']).'
                        '.$go.'
                    </div>
                </td>
            ';
        $out .= '</tr>';
    }
}

//print_r($_POST['getStatus']);

require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/blog-header.html");
require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/blog.html");
