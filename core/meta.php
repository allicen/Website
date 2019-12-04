<?php
    $blogPost = $url[3];
    $blogCategory = $url[2];

    if($module == 'page' || $module == 'main'){
        if($query = mysqli_query($connect, "SELECT * FROM links WHERE link = '$link'") and $row = mysqli_fetch_assoc($query) and $row != '') {
            $linkId = $row['id'];
        }
    }

    if($module == 'blog' && $blogCategory == null){
        $title = 'Блог';
    }

    if($query = mysqli_query($connect, "SELECT * FROM blog_category WHERE link = '$blogCategory'") and $row = mysqli_fetch_assoc($query) and $row != ''){
        $title = $row['title'];
        $description = $row['description'];
        if ($title == ''){
            $title = $row['name'];
        }
    }

    if($query = mysqli_query($connect, "SELECT * FROM blog_posts WHERE link = '$blogPost'") and $row = mysqli_fetch_assoc($query) and $row != ''){
        $title = $row['title'];
        $description = $row['description'];
        if ($title == ''){
            $title = $row['h1'];
        }
    }

    if ($module == '404'){
        $linkId = '3'; // 404
    }

    if($query = mysqli_query($connect, "SELECT * FROM content WHERE link_id = '$linkId'") and $row = mysqli_fetch_assoc($query) and $row != ''){
        $title = $row['title'];
        $description = $row['description'];
        $pageName = $row['name'];
        $out = $row['text'];
    }