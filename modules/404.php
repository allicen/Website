<?php
    $page = $url[2];
    $post = $url[3];
    $page404 = $url[4];

    $is404 = false;

    if($module == 'page' && $page !== ''){
        $module = '404';
    }

    if($module === 'blog'){
        if($page !== ''){
            if($query = mysqli_query($connect, "SELECT * FROM `blog_category` WHERE `link` = '$page'") and $row = mysqli_fetch_assoc($query) and $row != '') {
                $is404 = false;
            }else{
                $is404 = true;
            }
        }

        if($page !== '' && $post !== ''){
            if($query = mysqli_query($connect, "SELECT * FROM `blog_posts` WHERE `link` = '$post'") and $row = mysqli_fetch_assoc($query) and $row != '') {
                if($row['status'] == '0'){
                    $is404 = true;
                }else{
                    $is404 = false;
                }
            }else{
                $is404 = true;
            }
        }

        if($page !== '' && $post !== '' && $page404 !== ''){
            $is404 = true;
        }
    }

    if($module === 'portfolio'){
        if($page !== '' && $post !== ''){
            $is404 = true;
        }
        if($page !== '' && $post === ''){
            if($query = mysqli_query($connect, "SELECT * FROM `portfolio` WHERE `link` = '$page'") and $row = mysqli_fetch_assoc($query) and $row != '') {
                if($row['status'] == '0'){
                    $is404 = true;
                }else{
                    $is404 = false;
                }
            }else{
                $is404 = true;
            }
        }
    }

    if($is404 === true){
        $module = '404';
    }
