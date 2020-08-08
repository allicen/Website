<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backend/functions.php");

$id = explode('=', $url[5])[1];
$actionType = explode('=', $url[6])[1];

$page .= ' (обновления)';
$action = 'Добавление записи';
$version = '';
$date = '';
$text = '';
$productSelect = '';

if ($url[4] == '' && $actionType != 'edit') {
    $productSelect = getAllSelect($connect, 'portfolio');

    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $date = date('Y-m-d H:i:s' ,strtotime($_POST['date']));
        $productId = (string) $_POST['product'];
        $text = $_POST['text'];
        $version = $_POST['version'];

        if($query = mysqli_query($connect, "INSERT INTO `portfolio_changelog` (`id`, `date`, `portfolio_id`, `text`, `version`) 
                                                   VALUES (NULL, '$date',  '$productId', '$text', '$version')") and ($query)){
            $info = '<div class="green info">Запись успешно добавлена!</div>';
        }else{
            $info = '<div class="red info">Запись не была добавлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}

if($actionType == 'delete'){
    $info = delete($connect, $id, 'portfolio_changelog', '/admin/portfolio/changelog/');
}

if($actionType == 'edit'){
    $action = 'Редактирование записи';
    if($query = mysqli_query($connect, "SELECT * FROM portfolio_changelog WHERE id = ".$id."") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)){
            $date = $row['date'];
            $productId = $row['portfolio_id'];
            $text = $row['text'];
            $version = $row['version'];

            $productSelect = getSelectFromOtherTable($connect, $row['portfolio_id'], 'portfolio');
        }
    }else{
        $info = '<div class="red info">Проверьте корректность подключения к БД.</div>';
    }
    if(isset($_POST['submit']) && $_POST['submit'] != ''){
        $date = date('Y-m-d H:i:s' ,strtotime($_POST['date']));
        $productId = (string) $_POST['product'];
        $text = $_POST['text'];
        $version = $_POST['version'];
        if($query = mysqli_query($connect, "UPDATE `portfolio_changelog` SET `date` = '$date', `portfolio_id` = '$productId', `text` = '$text', `version` = '$version' WHERE `portfolio_changelog`.`id` = ".$id."") and ($query)){
            $info = '<div class="green info">Запись успешно отредактирована!</div>';
        }else{
            $info = '<div class="red info">Запись не была обновлена! Проверьте корректность подключения к БД.</div>';
        }
    }
}

if($query = mysqli_query($connect, "SELECT * FROM portfolio_changelog") and mysqli_fetch_assoc($query) !=''){
    mysqli_data_seek($query, 0);
    while($row = mysqli_fetch_assoc($query)){
        $product = getName($connect, $row['portfolio_id'], 'portfolio');
        $out .= '<tr>';
        $out .= '
                <td><a name="'.$row['id'].'"></a>'.$row['id'].'</td>
                <td>'.$row['version'].'</td>
                <td>'.$row['date'].'</td>
                <td>'.$product.'</td>
                <td>'.$row['text'].'</td>
                <td>
                    <div class="nowrap">
                        '.editIcon($row['id']).'
                        '.deleteIcon($row['id']).'
                    </div>
                </td>
            ';
        $out .= '</tr>';
    }
}


require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/portfolio-header.html");
require_once($_SERVER['DOCUMENT_ROOT']."/backend/pages/templates/changelog.html");