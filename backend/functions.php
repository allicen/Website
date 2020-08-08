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
    $end = (string) $_POST['end'];
    $text = (string) replaceImgA($_POST['text']);
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

    return array($name, $link, $picture, $title, $description, $anons, $github, $technologies, $status, $end, $text);
}

function replaceImgA($text){
    return str_replace('pages-cke-saved-', '', $text);
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

function getLink($connect, $linkId, $table){
    $link = '';
    if($query = mysqli_query($connect, "SELECT * FROM $table WHERE `id` = $linkId") and $row = mysqli_fetch_assoc($query) and $row != '') {
        $link = $row['link'];
    }
    return $link;
}

function getName ($connect, $linkId, $table) {
    $name = '';
    if($query = mysqli_query($connect, "SELECT * FROM $table WHERE `id` = $linkId") and $row = mysqli_fetch_assoc($query) and $row != '') {
        $name = $row['name'];
    }
    return $name;
}

function getSelectFromOtherTable($connect, $linkId, $table){ // Select из другой таблицы.
    $selectOut = '';
    if($query = mysqli_query($connect, "SELECT * FROM $table") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)){
            if($row['link'] != ''){
                $selected = '';
                if($row['id'] == $linkId){
                    $selected = ' selected';
                }
                $selectOut .= '<option value="'.$row['id'].'"'.$selected.'>'.$row['name'].'</option>';
            }
        }
    }
    return $selectOut;
}

function getSelect($id, $key, $value){
    $selectOut = '';
    for ($i = 0; $i < count($key); $i++){
        $selected = '';
        if($key[$i] == $id){
            $selected = ' selected';
        }
        $selectOut .= '<option value="'.$key[$i].'" '.$selected.'>'.$value[$i].'</option>';
    }
    return $selectOut;
}

function getStatus($status){
    return $status = $status == '1' ? 'Опубликовано' : 'Черновик';
}

function getAllSelect($connect, $table){
    $category = '';
    if($query = mysqli_query($connect, "SELECT * FROM $table") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)){
            $category .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }
    }
    return $category;
}

function getSelectId($connect, $table, $id, $link){
    $linkId = '';
    if($query = mysqli_query($connect, "SELECT * FROM $table WHERE `$id` = '$link'") and $row = mysqli_fetch_assoc($query) and $row != '') {
        $linkId = $row['id'];
    }
    return $linkId;
}

function getLinkPath($url, $path){
    $link = '';
    for($j = 0; $j < count($url); $j++){
        if($url[$j] == $path){
            $link .= $url[$j];
            $link .= '/';
            break;
        }else{
            $link .= $url[$j];
            if($url[$j] != '') $link .= '/';
        }
    }
    return $link;
}

function getPath($url, $path){
    $start = false;
    $fullPath = '';
    $fullPathWithTags = '';
    for($i = 0; $i < count($url); $i++){
        if($url[$i] != 'error' &&
            $url[$i] != 'type-error' &&
            $url[$i] != 'size-error' &&
            $url[$i] != 'delete' &&
            $url[$i] != 'add' &&
            !stripos(strtolower($url[$i]), strtolower('.png')) &&
            !stripos(strtolower($url[$i]), strtolower('.jpg')) &&
            !stripos(strtolower($url[$i]), strtolower('.jpeg')) &&
            !stripos(strtolower($url[$i]), strtolower('.svg')) &&
            !stripos(strtolower($url[$i]), strtolower('.pdf')) &&
            !stripos(strtolower($url[$i]), strtolower('.gif'))
        ){
            if($url[$i] == $path){
                $start = true;
            }
            if($start) {
                $fullPath .= $url[$i];
                $fullPathWithTags .= '<a href="/'.getLinkPath($url, $url[$i]).'">'.$url[$i].'</a>';
                if($url[$i] != '') {
                    $fullPath .= '/';
                    $fullPathWithTags .= '/';
                }
            }
        }
    }
    return array($fullPath, $fullPathWithTags);
}

function resize($file, $quality, $max_size, $dir){
    global $src;
    $img = true;

    if ($file['type'] == 'image/jpeg')
        $source = imagecreatefromjpeg($file['tmp_name']);
    elseif ($file['type'] == 'image/png')
        $source = imagecreatefrompng($file['tmp_name']);
    elseif ($file['type'] == 'image/gif')
        $source = imagecreatefromgif($file['tmp_name']);
    elseif ($file['type'] == 'image/svg+xml' || $file['type'] == 'application/pdf')
        $img = false;
    else
        return false;
    if($img){
        $src = $source;
        // Определяем ширину и высоту изображения
        $w_src = imagesx($src);
        $h_src = imagesy($src);

        if($w_src > $max_size){
            $ratio = $w_src / $max_size;
            $w_dest = round($w_src/$ratio);
            $h_dest = round($h_src/$ratio);
            $dest = imagecreatetruecolor($w_dest, $h_dest);
            imagealphablending($dest, true);
            imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);
            imagejpeg($dest, $dir.$file['name'], $quality);
            return $file['name'];
        }else{
            imagejpeg($src, $dir.$file['name'], $quality);
            imagedestroy($src);
            return $file['name'];
        }
    }else{
        if(copy($_FILES['picture']['tmp_name'], $dir.basename($_FILES['picture']['name']))){
            return $file['name'];
        }else{
            return false;
        }
    }
}

function changeStatusAjax($connect, $id, $table){
    $newStatus = '';
    if($query = mysqli_query($connect, "SELECT * FROM $table WHERE `id` = $id") and $row = mysqli_fetch_assoc($query) and $row != '') {
        $newStatus = $row['status'] == '1' ? '0' : '1';
    }
    if($newStatus != ''){
        print_r(getStatus($newStatus));
        return mysqli_query($connect, "UPDATE `$table` SET `status` = '$newStatus' WHERE `$table`.`id` = '$id';");
    }else return false;
}

function deleteIcon($id){
    return '<a href="?id='.$id.'&action=delete" class="img"><img src="/img/delete.png" alt="Удалить" onclick="return deleteCheck();" title="Удалить" class="icon"></a>';
}

function editIcon($id){
    return '<a href="?id='.$id.'&action=edit" class="img"><img src="/img/edit.png" alt="Редактировать" title="Редактировать" class="icon"></a>';
}

function goIcon($link){
    return '<a href="'.$link.'/" target="_blank" class="img"><img src="/img/go.png" alt="Открыть в новой вкладке" title="Открыть в новой вкладке" class="icon"></a>';
}


