<?php
$blogCategory = '';
$pageName = '';
$prefix = 'portfolio/';
$urlItem = $url[2];
$breadCrumb = '<a href = "/">Главная</a> / ';

if($urlItem == null){
    $pageName = 'Портфолио';
    $breadCrumb .= $pageName;
    // Вывод всех работ
    if($query = mysqli_query($connect, "SELECT * FROM portfolio") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)){
            $out .= '
                <div class="item"><a href="/'.$prefix.$row['link'].'/">'.$row['name'].'</a></div>
            ';
        }
    }
}else{
    if($query = mysqli_query($connect, "SELECT * FROM portfolio WHERE link = '$urlItem'") and $row = mysqli_fetch_assoc($query) and $row != '') {
        $pageName = $row['name'];
        $date = $row['date'];
        $title = $row['title'];
        $technologies = $row['technologies'];
        $description = $row['description'];
        $out = '<div class="item"></div>';

        $out = '<div class="details">
                        <div class="date">Дата: '.$date.'</div>
                        <div class="category">Технологии: '.$technologies.'</div>
                    </div>'
            .$row['about'];
        $breadCrumb .= '<a href="/portfolio/">'.$portfolio.'</a> / '.$pageName;

    }
}

require_once($_SERVER['DOCUMENT_ROOT']."/templates/page.html");
