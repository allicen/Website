<?php
$pageName = '';
$previewPortfolio = '';
$prefix = 'portfolio/';
$urlItem = $url[2];
$breadCrumb = '<a href = "/">Главная</a> / ';

if($urlItem == null){
    if($module == 'portfolio'){
        $pageName = 'Портфолио';
        $breadCrumb .= $pageName;
    }else{
        $breadCrumb = '';
    }
    // Вывод всех работ
    if($query = mysqli_query($connect, "SELECT * FROM portfolio") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        $previewPortfolio = '<div class="articles">';
        while($row = mysqli_fetch_assoc($query)){
            $previewPortfolio .= '
                <article>
                    <div class="title">
                        <a href="/'.$prefix.$row['link'].'/">'.$row['name'].'</a>
                    </div>
                    <div class="details">'.$row['technologies'].'</div>
                    <div class="photo">
                        <a href="/'.$prefix.$row['link'].'/"><img src="'.$row['picture'].'" alt=""></a>
                    </div>
                    <div class="desc">
                        '.$row['anons'].'
                    </div>
                    <a href="/'.$prefix.$row['link'].'/">подробнее</a>
                </article>
            ';
        }
        $previewPortfolio .= '</div>';
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
