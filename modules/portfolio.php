<?php
require_once($_SERVER['DOCUMENT_ROOT']."/core/functions.php");

$pageName = '';
$previewPortfolio = '';
$prefix = 'portfolio/';
$urlItem = $url[2];
$breadCrumb = '<a href = "/">Главная</a> / ';
$blogCategory = '';
$isChangelog = $url[3] == 'changelog';

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
        if($module == 'main'){
            $previewPortfolio .= '
            <div class="button">
                <a href="/portfolio/">'.$goToPortfolio.'</a>
            </div>
        ';
        }
    }

}else{
    if($query = mysqli_query($connect, "SELECT * FROM portfolio WHERE link = '$urlItem'") and $row = mysqli_fetch_assoc($query) and $row != '') {
        if($row['status'] == '1'){
            $id = $row['id'];
            $pageName = $row['name'];
            $date = dateFormat($row['date']);
            $title = $row['title'];
            $description = $row['description'];
            $out = '<div class="item"></div>';

            $out = '<div class="details">
                        <div class="date">Добавлен: '.$date.'</div>
                        <div class="category">Технологии: '.getTech($connect, $row).'</div>
                    </div>
                    <div class="tabs">
                        <a href="/portfolio/'.$urlItem.'/" data-id="">Описание</a>
                        <a href="/portfolio/'.$urlItem.'/changelog/" data-id="changelog">История обновлений</a>
                    </div>';

            if ($isChangelog) {
                if($queryChangeLog = mysqli_query($connect, "SELECT * FROM portfolio_changelog WHERE portfolio_id = '$id' ORDER BY date DESC") and mysqli_fetch_assoc($queryChangeLog) != '') {
                    mysqli_data_seek($queryChangeLog, 0);
                    while($rowChangeLog = mysqli_fetch_assoc($queryChangeLog)){
                        $logVersion = $rowChangeLog['version'];
                        $logDate = $rowChangeLog['date'];
                        $logText = $rowChangeLog['text'];

                        $out .= '
                        <p><strong>'.$logVersion.' от '.$logDate.'</strong></p>
                        '.$logText;
                    }
                }
                if ($row['end'] == '1') {
                    $out .= '
                        <p><strong>'.$date.'</strong></p>
                        <p>Выпуск первой версии.</p>
                    ';
                } else {
                    $out .= '<p>Продукт не опубликован</p>';
                }
            } else {
                $out .= $row['about'];
            }


            $breadCrumb .= '<a href="/portfolio/">'.$portfolio.'</a> / '.$pageName;

            $end = $row['end'] == '1' ? '<img src="/img/ok.png" alt="Завершен">Завершен' : '<img src="/img/process.png" alt="В разработке">В разработке';
            $github = $row['github'] != '' ? '<div class="item"><a href="'.$row['github'].'" target="_blank"><img src="/img/github.png" alt="Github">Ссылка на Github</a></div>' : '';

            $blogCategory = '<div class="categoryList">
                            <div class="header-blog">Детали проекта</div>';
            $blogCategory .= '<div class="item"><div class="text">'.$end.'</div></div>';
            $blogCategory .= '<div class="item"><div class="text"><img src="/img/calendar.png" alt="Дата"> Добавлен: '.$date.'</div></div>';
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