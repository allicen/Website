$(function(){
    $("a").click(function () {
        if($(this).attr("href").indexOf("#") !== -1){
            let element = $(this).attr("href").replace("/#", "").replace("#", "");
            $("a").each(function () { // Плавный скролл до элемента
                if($(this).attr("name") === element){
                    $("html, body").animate({
                        scrollTop: $(this).offset().top
                    }, 500);
                    console.log(this);
                    return false;
                }
            });
        }
    });

    let menu = $("header menu li");
    menu.click(function () {
        $(menu).each(function () { // Изменить цвет
            $(this).removeClass("change-color");
        });
        $(this).addClass("change-color");
    });

    // Обрезание анонса блога до 100 символов до целого слова
    $(".articles article .desc").each(function () {
        $(this).text(function (i, text) {
            if(text.length > 200){
                text = text.substring(0, 99);
                let lastIndex = text.lastIndexOf(" ");
                text = text.substring(0, lastIndex) + "...";
            }
            $(this).text(text);
        });
    });

    // События при изменении ширины окна
    $(window).resize(function(){
        contentCenter();
        preWidth();
        heigthSertificats();
        getFirstLine(heightGallery);
    });

    contentCenter();
    preWidth();
    heigthSertificats();

    // Размещаем логотип по центру при изменении ширины окна
    function contentCenter(){
        let blockCenter = $("header .content .logo");
        if($("header").height() > 100){
            blockCenter.css({
                "flex" : "2 1",
                "text-align" : "center",
                "padding-top" : "30px"
            });
        }else{
            blockCenter.css({
                "flex" : "1 1",
                "text-align" : "left",
                "padding-top" : "15px"
            });
        }
    }

    // Установка ширины блоков с кодом
    function preWidth() {
        $("pre").css("max-width", window.innerWidth-100); // 100 дополнительный отступ
        $(".syntaxhighlighter").css("max-width", window.innerWidth-80);
    }

    //confirm("Внимание! Сайт находится в разработке. Внешний вид и функционал может отличаться от запланированного.");



    // Фотогаларея

    let photo = $(".gallery .gallery-item");
    let scrollPoint = $(".gallery").offset();
    let photoId = 1;
    let getPhotoId;
    let photoCount = photo.length;

    photo.each(function () {
        $(this).attr("data-id", photoId);
        photoId++;
    });

    photo.mouseenter(function () {
        $(this).find("img").addClass("blur");
        $(this).find("span").fadeIn(100);
    });

    photo.mouseleave(function () {
        $(this).find("img").removeClass("blur");
        $(this).find("span").fadeOut(100);
    });

    photo.click(function () {
        let prev = $(".gallery-background .left .prev");
        let next = $(".gallery-background .right .next");
        prev.show();
        next.show();
        $(".gallery-background").fadeIn();
        let pic = $(this).find("img").attr("src");
        let desc = $(this).find(".gallery-caption").text();
        $(".gallery-background .big .photo img").attr("src", pic);
        $(".gallery-background .big .photo .desc").text(desc);
        $("html").css("overflow", "hidden");
        getPhotoId = $(this).attr("data-id");
        $(".gallery-background .big .photo").attr("data-id", getPhotoId);
        if(Number(getPhotoId) >= photoCount){
            next.hide();
        }
        if(Number(getPhotoId) <= 1){
            prev.hide();
        }
    });

    $(".gallery-close").click(function () {
        $(".gallery-background").fadeOut(100);
        $("html").css("overflow", "auto");
    });

    $(".gallery-background .right .next").click(function () {
        $(".gallery-background .left .prev").show();
        let photo = $(".gallery-background .big .photo");
        getPhotoId = photo.attr("data-id");
        let next = Number(getPhotoId) + 1;
        if(next === photoCount){
            $(".gallery-background .right .next").hide();
        }
        let item = $(".gallery .gallery-item[data-id="+next+"]");
        let pic = item.find("img").attr("src");
        let desc = item.find(".gallery-caption").text();
        $(".gallery-background .big .photo img").attr("src", pic);
        $(".gallery-background .big .photo .desc").text(desc);
        photo.attr("data-id", next);
    });


    $(".gallery-background .left .prev").click(function () {
        $(".gallery-background .right .next").show();
        let photo = $(".gallery-background .big .photo");
        getPhotoId = photo.attr("data-id");
        let prev = Number(getPhotoId) - 1;
        console.log(prev);
        if(prev === 1){
            $(".gallery-background .left .prev").hide();
        }
        let item = $(".gallery .gallery-item[data-id="+prev+"]");
        let pic = item.find("img").attr("src");
        let desc = item.find(".gallery-caption").text();
        $(".gallery-background .big .photo img").attr("src", pic);
        $(".gallery-background .big .photo .desc").text(desc);
        photo.attr("data-id", prev);
    });

    function heigthSertificats(){
        $(".gallery-background .big .photo img").css("max-height", window.innerHeight/100*90);
    }


    // вывод сертификатов

    let index = 2;
    let heightGallery = 194;
    let stop = false;
    getFirstLine(heightGallery);
    function getFirstLine(height){
        let elem = $(".gallery .items dl");
        elem.each(function (i) {
            if($(".gallery .items").height() <= height || i === 0){
                $(this).show();
            }else {
                $(this).prev().hide();
                return false;
            }
            if(elem.length === i+1){
                $(".gallery .more").hide();
            }
        });
    }

    $(".gallery .more").click(function () {
        getFirstLine(heightGallery * index);
        index++;
    });

});

