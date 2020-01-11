<?php
require_once($_SERVER['DOCUMENT_ROOT']."/core/connect.php");
$id = explode('=', $url[4])[1];
$actionType = explode('=', $url[5])[1];
$date = (string) date("Y-m-d");

function delete($connect, $id, $tableName, $addNewPost){
    $info = '';
    if($query = mysqli_query($connect, "DELETE FROM `$tableName`WHERE `$tableName`.`id` = ".$id."") and ($query)){
        $info = '<div class="green info">Запись id=<strong>'.$id.'</strong> успешно удалена!';
        if($addNewPost != ''){
            $info .= ' <a href="'.$addNewPost.'#edit">Добавить новую</a></div>';
        }else{
            $info .= '</div>';
        }
    }else{
        $info = '<div class="red info">Произошла ошибка подключения к БД.</div>';
    }
    return $info;
}

function getDataFields(){
    $name = (string) $_POST['name'];
    $link = (string) $_POST['link'];
    $picture = (string) $_POST['picture'];
    $title = (string) $_POST['title'];
    $description = (string) $_POST['description'];
    $anons = (string) $_POST['anons'];
    $github = (string) $_POST['github'];
    $status = (string) $_POST['status'];
    $text = (string) $_POST['text'];
    $technologies = $_POST['technologies'];
    $technologiesCheck = '';
    if(!empty($technologies)){
        $count = count($technologies);
        for($i = 0; $i < $count; $i++){
            $technologiesCheck .= $technologies[$i];
            $technologiesCheck .= ',';
        }
    }

    $technologies = (string) $technologiesCheck;
    $technologies = substr($technologies, 0, -1);

    return array($name, $link, $picture, $title, $description, $anons, $github, $technologies, $status, $text);
}


// Вывод технологий
function getTechnologies($connect, $technologiesCheck, $useTech){
    if($query = mysqli_query($connect, "SELECT * FROM technologies") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)) {
            $useTechArr = explode(',', (string) $useTech);
            $checked = '';
            for($i = 0; $i < count($useTechArr); $i++){
                if($useTechArr[$i] == $row['id']) {
                    $checked = 'checked';
                    break;
                }
            }
            $technologiesCheck .= '
                    <div class="checkbox">
                      <input type="checkbox" id="'.$row['id'].'" name="technologies[]" value="'.$row['id'].'" '.$checked.'>
                      <label for="'.$row['id'].'">'.$row['name'].'</label>
                    </div>';
        }
    }
    return $technologiesCheck;
}

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

