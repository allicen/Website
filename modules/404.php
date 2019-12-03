<?php
    $page = $url[2];
    $post = $url[3];
    $page404 = $url[4];

    $is404 = false;

    if($module == 'page' && $page !== ''){
        $module = '404';
    }

    if($module === 'blog'){
        if($page !== '' && $post === ''){
            $is404 = true;
            if($query = mysqli_query($connect, "SELECT * FROM blog_category") and mysqli_fetch_assoc($query) !=''){
                mysqli_data_seek($query, 0);
                while($row = mysqli_fetch_assoc($query)){
                    if($row['link'] == $page){
                        $is404 = false;
                    }
                }
            }
        }
        if($page !== '' && $post !== ''){
            $is404 = true;
            if($query = mysqli_query($connect, "SELECT * FROM blog_posts") and mysqli_fetch_assoc($query) !=''){
                mysqli_data_seek($query, 0);
                while($row = mysqli_fetch_assoc($query)){
                    if($row['link'] == $post){
                        $is404 = false;
                    }
                }
            }
        }
        if($page !== '' && $post !== '' && $page404 !== ''){
            $is404 = true;
        }
    }
    if($is404 === true){
        $module = '404';
    }
