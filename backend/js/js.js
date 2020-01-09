function tabColor() {
    let url = document.location.pathname.toString().split('/')[2];
    let tabs = document.querySelectorAll('.tabs .tab-header a');

    for (let elem = 0; elem < tabs.length; elem++){
        let attr = tabs[elem].dataset.id;
        if(attr === url){
            tabs[elem].classList.add('active-tab');
        }else {
            tabs[elem].classList.remove('active-tab');
        }
    }
}

function deleteCheck() {
    return confirm('Удалить запись безвозвратно? Вы уверены?');
}

tabColor();
