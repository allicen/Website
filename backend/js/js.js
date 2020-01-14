const url = document.location;
const pageUrl = url.pathname.toString().split('/')[2];
const subPageUrl = url.pathname.toString().split('/')[3];
const actionUrl = url.toString().split('=')[2];

function tabColor() {
    let tabs = document.querySelectorAll('.tabs .tab-header a');
    for (let elem = 0; elem < tabs.length; elem++){
        let attr = tabs[elem].dataset.id;
        if(attr === pageUrl){
            tabs[elem].classList.add('active-tab');
        }else {
            tabs[elem].classList.remove('active-tab');
        }
    }
}

function changeSubMenu() {
    let tabs = document.querySelectorAll('.navigate div');
    for(let elem = 0; elem < tabs.length; elem++){
        let attr = tabs[elem].dataset.id;
        if(attr === subPageUrl){
            tabs[elem].classList.add('active');
        }else {
            tabs[elem].classList.remove('active');
        }
    }
}

function deleteCheck() {
    return confirm('Удалить запись безвозвратно? Вы уверены?');
}

function hideEditForm() {
    let form = document.querySelector('.form-editor');
    form.remove();
}

if((pageUrl === 'options' || (pageUrl === 'pages' && subPageUrl === '')) && actionUrl !== 'edit'){
    hideEditForm();
}

tabColor();
changeSubMenu(); 


