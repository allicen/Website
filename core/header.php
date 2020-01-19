<?php
    $menu = '';
    $submenu = '';

    if($module == 'main'){
        $addTitle = '';
    }

    if($query = mysqli_query($connect, "SELECT * FROM top_menu") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)){
            $menu .= '
                <li>
                    <a href="/#'.$row['link'].'">
                        '.$row['name'].'
                    </a>
                </li>
            ';
        }
    }

    if($query = mysqli_query($connect, "SELECT * FROM submenu") and mysqli_fetch_assoc($query) !=''){
        mysqli_data_seek($query, 0);
        while($row = mysqli_fetch_assoc($query)){
            $submenu .= '
                    <a href="'.$row['link'].'"><span>'.$row['name'].'</span></a>
                ';
        }
    }

    require_once($_SERVER['DOCUMENT_ROOT']."/templates/header.html");