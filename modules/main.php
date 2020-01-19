<?php
require_once($_SERVER['DOCUMENT_ROOT']."/core/functions.php");
$slider = getImgSlider();
$out = '';

if($query = mysqli_query($connect, "SELECT * FROM top_menu") and mysqli_fetch_assoc($query) !=''){
    mysqli_data_seek($query, 0);
    while($row = mysqli_fetch_assoc($query)){
        $out .= '
            <div class="'.$row['link'].'">
                <a name="'.$row['link'].'"></a>
                 <div class="header-block">
                    '.$row['title'].'
                </div>
                <div class="text">'.$row['text'].'</div>
            </div>
        ';
    }
}

require_once 'templates/main.html';