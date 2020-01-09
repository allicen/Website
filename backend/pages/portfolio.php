<?php
require_once($_SERVER['DOCUMENT_ROOT']."/core/connect.php");

$id = explode('=', $url[4])[1];
$actionType = explode('=', $url[5]);

$action = 'Добавить новую запись:';
$name = '';
$link = '';
$picture = '';
$title = '';
$description = '';
$anons = '';
$github = '';
$technologies = '';
$status = '';
$text = '';
$date = (string) date("Y-m-d");

function getDataFields(){
    $name = (string) $_POST['name'];
    $link = (string) $_POST['link'];
    $picture = (string) $_POST['picture'];
    $title = (string) $_POST['title'];
    $description = (string) $_POST['description'];
    $anons = (string) $_POST['anons'];
    $github = (string) $_POST['github'];
    $technologies = (string) $_POST['technologies'];
    $status = (string) $_POST['status'];
    $text = (string) $_POST['text'];
    return array($name, $link, $picture, $title, $description, $anons, $github, $technologies, $status, $text);
}

// Добавление записи
if($url[3] == '' && count($actionType) == 1){
    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        list($name, $link, $picture, $title, $description, $anons, $github, $technologies, $status, $text) = getDataFields();

        if($query = mysqli_query($connect, "INSERT INTO `portfolio` (`id`, `name`, `link`, `picture`, `anons`, `title`, `description`, `github`, `about`, `date`, `status`, `technologies`) 
                                                   VALUES (NULL, '$name', '$link', '$picture', '$anons', '$title', '$description', '$github', '$text', '$date', '$status', '$technologies')") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно добавлена! <a href="#'.$link.'">Посмотреть</a> или <a href="/admin/portfolio/#edit">добавить еще одну</a></div>';
        }else{
            $info = '<div class="red info">Запись не была добавлена! Провеьте корректность подключения к БД.</div>';
        }
    }
}

// Удаление записи
if($actionType[1] == 'delete'){
    if($query = mysqli_query($connect, "DELETE FROM `portfolio`WHERE `portfolio`.`id` = ".$id."") and ($query)){
        $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно удалена! <a href="/admin/portfolio/#edit">Добавить новую</a></div>';
    }else{
        $info = '<div class="red info">Произошал ошибка подключения к БД.</div>';
    }
}

// Редактирование записи
if($actionType[1] == 'edit'){
    $action = 'Редактирование записи';
    if($query = mysqli_query($connect, "SELECT * FROM portfolio WHERE id = ".$id."") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)){
            $name = $row['name'];
            $link = $row['link'];
            $picture = $row['picture'];
            $title = $row['title'];
            $description = $row['description'];
            $anons = $row['anons'];
            $github = $row['github'];
            $technologies = $row['technologies'];
            $status = $row['status'];
            $text = $row['about'];
        }
    }else{
        $info = '<div class="red info">Проверьте корректность подключения к БД.</div>';
    }

    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        list($name, $link, $picture, $title, $description, $anons, $github, $technologies, $status, $text) = getDataFields();

        if($query = mysqli_query($connect, "UPDATE `portfolio` SET 
                                                        `name` = '$name', 
                                                        `link` = '$link', 
                                                        `picture` = '$picture',
                                                        `anons` = '$anons', 
                                                        `title` = '$title', 
                                                        `description` = '$description', 
                                                        `github` = '$github', 
                                                        `technologies` = '$technologies', 
                                                        `status` = '$status', 
                                                        `about` = '$text',
                                                        `date` = '$date'
                                                        WHERE `portfolio`.`id` = ".$id."") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно отредактирована! <a href="#'.$link.'">Посмотреть</a> или <a href="/admin/portfolio/#edit"> добавить еще одну</a></div>';
        }else{
            var_dump($query);
            $info = '<div class="red info">Запись не была обновлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}

// Вывод технологий
$technologiesCheck = '';
if($query = mysqli_query($connect, "SELECT * FROM technologies") and mysqli_fetch_assoc($query) !=''){
    mysqli_data_seek($query, 0);
    while($row = mysqli_fetch_assoc($query)) {
        $technologiesCheck .= '
                    <div class="checkbox">
                      <input type="checkbox" id="'.$row['name'].'" name="'.$row['name'].'">
                      <label for="'.$row['name'].'">'.$row['name'].'</label>
                    </div>';
    }
}



// Вывод всех записей
if($query = mysqli_query($connect, "SELECT * FROM portfolio") and mysqli_fetch_assoc($query) !=''){
    mysqli_data_seek($query, 0);
    while($row = mysqli_fetch_assoc($query)){
        $status = $row['status'] == '1' ? 'Опубликовано' : 'Черновик';
        $github = $row['github'] != '' ? '<a href="'.$row['github'].'" target="_blank">Репозиторий</a>' : '';
        $go = $row['status'] == '1' ? '<a href="/portfolio/'.$row['link'].'/" target="_blank" class="img"><img src="/img/go.png" alt="Открыть в новой вкладке" title="Открыть в новой вкладке" class="icon"></a>' : '';
        $technologies = explode(',', $row['technologies']);
        $technologiesItems = '';

        for($i = 0; $i < count($technologies); $i++){
            if($tech = mysqli_query($connect, "SELECT * FROM technologies WHERE `id` = ".$technologies[$i]."") and $rowTech = mysqli_fetch_assoc($tech) and $rowTech != '') {
                $technologiesItems .= $rowTech['name'];
                $technologiesItems .= ' ';
            }
        }



        $out .= '<tr>';
        $out .= '
                <td><a name="'.$row['link'].'"></a>'.$row['id'].'</td>
                <td>'.$row['name'].'</td>
                <td>'.$row['link'].'</td>
                <td>'.$row['date'].'</td>
                <td>'.$row['picture'].'</td>
                <td>'.$row['title'].'</td>
                <td>'.$github.'</td>
                <td>'.$technologiesItems.'</td>
                <td>'.$status.'</td>
                <td>
                    <a href="?id='.$row['id'].'&action=edit" class="img"><img src="/img/edit.png" alt="Редактировать" title="Редактировать" class="icon"></a>
                    <a href="?id='.$row['id'].'&action=delete" class="img"><img src="/img/delete.png" alt="Удалить" onclick="return deleteCheck();" title="Удалить" class="icon"></a>
                    '.$go.'
                </td>
            ';
        $out .= '</tr>';
    }
    $github = '';
}

require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/portfolio.html");