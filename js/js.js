$(function(){
    $("a").click(function () {
        if($(this).attr("href").indexOf("#") !== -1){
            let element = $(this).attr("href").replace("/#", "");
            $("a").each(function () { // Плавный скролл до элемента
                if($(this).attr("name") === element){
                    $("html, body").animate({
                        scrollTop: $(this).offset().top
                    }, 500);
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
            if(text.length > 100){
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
    });

    contentCenter();
    preWidth();

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

});

