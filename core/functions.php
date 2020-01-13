<?php
function getImgSlider(){
    $slider = '';
    $dir = $_SERVER['DOCUMENT_ROOT'].'/user-files/slider/';
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            $isFile = explode('.', $file);
            if (in_array($isFile[count($isFile) - 1], array('jpg', 'jpeg', 'png', 'gif'))) {
                $slider .= '
                    <dl class="gallery-item">
                        <dt class="gallery-icon">
                            <span class="lupa"></span>
                            <img src="/user-files/slider/'.$file.'">
                        </dt>
                    </dl>
                ';
            }
        }
    }
    return $slider;
}
