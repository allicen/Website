<?php
$out .= file_get_contents($_SERVER['DOCUMENT_ROOT']."/backend/pages/form/options.html");


require_once($_SERVER['DOCUMENT_ROOT']."/core/connect.php");

if($query = mysqli_query($connect, "SELECT * FROM options") and mysqli_fetch_assoc($query) !=''){
    mysqli_data_seek($query, 0);
    while($row = mysqli_fetch_assoc($query)){
        $out .= '<tr>';
        $out .= '
                <td>'.$row['id'].'</td>
                <td>'.$row['name'].'</td>
                <td>'.$row['value'].'</td>
                <td><a href="?id='.$row['id'].'&action=edit">изменить</a></td>
            ';
        $out .= '</tr>';
    }
}

$out .= '</tbody></table>';