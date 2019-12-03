<?php
    $blogPreview = '';
    $blogCategory = '';
    $prefix = 'blog/';
    $categoryUrl = '';
    $postUrl = '';
    $urlId = '';
    $categoryName = '';
    $breadCrumb = '<a href = "/">Главная</a> / ';
    $postCount = 0;
    $emptyCategory = 'В этой категории нет записей.';

    $categoryUrl = $url[2];
    $categoryUrlAdd = $url[2];
    $postUrl = $url[3];

    // Вывод категорий
    if($query = mysqli_query($connect, "SELECT * FROM blog_category") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        $blogCategory = '<div class="categoryList">
                            <div class="title">Категории</div>';
        while($row = mysqli_fetch_assoc($query)){
            $blogCategory .= '
                <div class="item"><a href="/'.$prefix.$row['link'].'/">'.$row['name'].'</a></div>
            ';
        }
        $blogCategory .= '</div>';
    }

    // Вывод записей
    if(($module == 'blog' && $categoryUrl !== '' && $postUrl == null) || $module == 'main' || ($module == 'blog' && $categoryUrl == null)){
        if($module == 'blog'){ // Для блога удаляем префикс
            $prefix = '';
        }

        // Получить название категории
        if ($query = mysqli_query($connect, "SELECT * FROM blog_category WHERE link = '$categoryUrl'") and mysqli_fetch_assoc($query) !=''){
            mysqli_data_seek($query, 0);
            while($row = mysqli_fetch_assoc($query)){
                $pageName = $row['name'];
            }
        }


        if($query = mysqli_query($connect, "SELECT * FROM blog_posts") and mysqli_fetch_assoc($query) !=''){
            mysqli_data_seek($query, 0);
                while($row = mysqli_fetch_assoc($query)){
                    $urlId = $row['category_id'];
                    if($queryUrl = mysqli_query($connect, "SELECT * FROM blog_category WHERE id = '$urlId'") and $rowUrl = mysqli_fetch_assoc($queryUrl) and $rowUrl != ''){
                        $categoryUrl = $rowUrl['link'].'/';
                        $categoryName = $rowUrl['name'];
                    }

                if($module == 'main' || ($categoryUrlAdd == str_replace('/', '', $categoryUrl)) || ($module == 'blog' && $categoryUrlAdd == null)){ // Фильтруем по категориям в блоге, на главной выводим всё
                    if($categoryUrlAdd == null){
                        $pageName = $blog;
                    }
                    if($postUrl !== null){ // Если нет
                        $categoryUrl = '';
                    }

                    $blogPreview .= '
                        <article>
                            <div class="title">
                                <a href="'.$prefix.$categoryUrl.$row['link'].'/">'.$row['h1'].'</a>
                            </div>
                            <div class="photo">
                                <a href="'.$prefix.$categoryUrl.$row['link'].'/"><img src="'.$row['picture'].'" alt=""></a>
                            </div>
                            <div class="desc">
                                '.$row['anons'].'
                            </div>
                            <a href="'.$prefix.$categoryUrl.$row['link'].'/">читать далее</a>
                        </article>
                    ';
                    $postCount++;
                }
            }

            if($blogPreview == ''){ // Счетчик записей в блоге
                $out = $emptyCategory;
            }else{
                $out = 'В категории "'.$pageName.'" '.$postCount.' записи(ей).';
            }

            if($module == 'blog'){ // Для блога подключаем шаблон
                if($categoryUrlAdd == ''){
                    $breadCrumb .= $pageName;
                }else{
                    $breadCrumb .= '<a href="/blog/">'.$blog.'</a> / '.$pageName;
                }
                require_once($_SERVER['DOCUMENT_ROOT']."/templates/page.html");
            }
        }
    }else{
        if($query = mysqli_query($connect, "SELECT * FROM blog_posts WHERE link = '$postUrl'") and $row = mysqli_fetch_assoc($query) and $row != ''){
            $categoryId = $row['category_id'];
            if($queryUrl = mysqli_query($connect, "SELECT * FROM blog_category WHERE id = '$categoryId'") and $rowUrl = mysqli_fetch_assoc($queryUrl) and $rowUrl != ''){
                $categoryName = $rowUrl['name'];
                $linkPost = $rowUrl['link'];
            }
            $pageName = $row['h1'];
            $date = $row['date'];
            $out = '<div class="details">
                        <div class="date">Дата: '.$date.'</div>
                        <div class="category">Рубрика: <a href="/blog/'.$linkPost.'/">'.$categoryName.'</a></div>
                    </div>'
                .$row['text'];
            $blogPreview = '';
            $breadCrumb .= '<a href="/blog/">'.$blog.'</a> / <a href="/blog/'.$linkPost.'/">'.$categoryName.'</a> / '.$pageName;
            require_once($_SERVER['DOCUMENT_ROOT']."/templates/page.html");
        }
    }
