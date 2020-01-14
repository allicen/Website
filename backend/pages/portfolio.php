<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backend/functions.php");

$action = 'Добавить новую запись:';
$name = '';
$link = '';
$picture = '';
$title = '';
$description = '';
$anons = '';
$github = '';
$technologies = '';
$technologiesCheck = '';
$status = '';
$end = '';
$text = '';

$endSelect = getSelect('', array('0', '1'), array('В разработке', 'Завершено'));
$statusSelect = getSelect('', array('0', '1'), array('Черновик', 'Опубликовано'));

if($url[3] == '' && $actionType != 'edit'){
    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        list($name, $link, $picture, $title, $description, $anons, $github, $technologies, $status, $end, $text) = getDataFields();
        if($query = mysqli_query($connect, "INSERT INTO `portfolio` (`id`, `name`, `link`, `picture`, `anons`, `title`, `description`, `github`, `about`, `date`, `status`, `end`, `technologies`) 
                                                   VALUES (NULL, '$name', '$link', '$picture', '$anons', '$title', '$description', '$github', '$text', '$date', '$status', '$end', '$technologies')") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно добавлена! <a href="#'.$link.'">Посмотреть</a> или <a href="/admin/portfolio/#edit">добавить еще одну</a></div>';
        }else{
            $info = '<div class="red info">Запись не была добавлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}

if($actionType == 'delete'){
    $info = delete($connect, $id, 'portfolio', '/admin/portfolio/');
}

if($actionType == 'edit'){
    $action = 'Редактирование записи';
    if($query = mysqli_query($connect, "SELECT * FROM portfolio WHERE id = ".$id."") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)){
            $name = $row['name'];
            $link = $row['link'];
            $picture = $row['picture'];
            $anons = $row['anons'];
            $title = $row['title'];
            $description = $row['description'];
            $github = $row['github'];
            $technologies = $row['technologies'];
            $statusSelect = getSelect($row['status'], array('0', '1'), array('Черновик', 'Опубликовано'));
            $endSelect = getSelect($row['end'], array('0', '1'), array('В разработке', 'Завершено'));
            $text = $row['about'];
            $technologiesCheck = (string) getTechnologies($connect, $technologiesCheck, $technologies);
        }
    }else{
        $info = '<div class="red info">Проверьте корректность подключения к БД.</div>';
    }

    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        list($name, $link, $picture, $title, $description, $anons, $github, $technologies, $status, $end, $text) = getDataFields();
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
                                                        `end` = '$end',
                                                        `about` = '$text',
                                                        `date` = '$date'
                                                        WHERE `portfolio`.`id` = ".$id."") and ($query)){
            $info = '<div class="green info">Запись <strong>'.$name.'</strong> успешно отредактирована! <a href="#'.$link.'">Посмотреть</a> или <a href="/admin/portfolio/#edit"> добавить еще одну</a></div>';
        }else{
            $info = '<div class="red info">Запись не была обновлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}

if($query = mysqli_query($connect, "SELECT * FROM portfolio") and mysqli_fetch_assoc($query) !=''){
    mysqli_data_seek($query, 0);
    while($row = mysqli_fetch_assoc($query)){
        $status = getStatus($row['status']);
        $end = $row['end'] == '1' ? 'Завершен' : 'В разработке';
        $githublink = $row['github'] != '' ? '<a href="'.$row['github'].'" target="_blank">Репозиторий</a>' : '';
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
                <td>'.$githublink.'</td>
                <td>'.$technologiesItems.'</td>
                <td>'.$end.'</td>
                <td>'.$status.'</td>
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

if(empty($technologiesCheck)){
    $technologiesCheck = (string) getTechnologies($connect, $technologiesCheck, '');
}


require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/portfolio-header.html");
require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/portfolio.html");