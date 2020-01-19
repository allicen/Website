<?php
require_once($_SERVER['DOCUMENT_ROOT']."/core/functions.php");

$pageName = '';
$previewPortfolio = '';
$prefix = 'portfolio/';
$urlItem = $url[2];
$breadCrumb = '<a href = "/">Главная</a> / ';
$blogCategory = '';

$shareBlock = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/share.html");

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
            if($row['status'] == '1'){
                $previewPortfolio .= '
                <article>
                    <div class="title">
                        <a href="/'.$prefix.$row['link'].'/">'.$row['name'].'</a>
                    </div>
                    <div class="details">'.getTech($connect, $row).'</div>
                    <div class="photo">
                        <a href="/'.$prefix.$row['link'].'/"><img src="'.$row['picture'].'" alt="'.$row['name'].'" title="Подробнее о проекте '.$row['name'].'"></a>
                    </div>
                    <div class="desc">
                        '.$row['anons'].'
                    </div>
                    <a href="/'.$prefix.$row['link'].'/">подробнее</a>
                </article>
            ';
            }
        }
        $previewPortfolio .= '</div>';
    }
}else{
    if($query = mysqli_query($connect, "SELECT * FROM portfolio WHERE link = '$urlItem'") and $row = mysqli_fetch_assoc($query) and $row != '') {
        if($row['status'] == '1'){
            $pageName = $row['name'];
            $date = dateFormat($row['date']);
            $title = $row['title'];
            $description = $row['description'];
            $out = '<div class="item"></div>';

            $out = '<div class="details">
                        <div class="date">Дата: '.$date.'</div>
                        <div class="category">Технологии: '.getTech($connect, $row).'</div>
                    </div>'
                .$row['about'];
            $breadCrumb .= '<a href="/portfolio/">'.$portfolio.'</a> / '.$pageName;

            $end = $row['end'] == '1' ? '<img src="/img/ok.png" alt="Завершен">Завершен' : '<img src="/img/process.png" alt="В разработке">В разработке';
            $github = $row['github'] != '' ? '<div class="item"><a href="'.$row['github'].'" target="_blank"><img src="/img/github.png" alt="Github">Ссылка на Github</a></div>' : '';

            $blogCategory = '<div class="categoryList">
                            <div class="header-blog">Детали проекта</div>';
            $blogCategory .= '<div class="item"><div class="text">'.$end.'</div></div>';
            $blogCategory .= '<div class="item"><div class="text"><img src="/img/calendar.png" alt="Дата"> Дата: '.$date.'</div></div>';
            $blogCategory .= $github;
            $blogCategory .= '<div class="other-block"><div class="header-blog">Кратко о проекте</div>';
            $blogCategory .= '<div class="item"><div class="text">'.$row['anons'].'</div></div></div>';
            $blogCategory .= '<div class="other-block"><div class="item">'.$shareBlock.'</div></div>';
            $blogCategory .= '</div>';
        }
    }
}

if($pageName != ''){
    $pageName = '<h1>'.$pageName.'</h1>';
}

require_once($_SERVER['DOCUMENT_ROOT']."/templates/page.html");