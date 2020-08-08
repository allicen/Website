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

function setStatus(event, id, table) { // Смена статуса без перезагрузки
    if (event.target.tagName === 'A'){
        event.target.classList.add('check-'+id);
    }

    event.preventDefault();
    let request = new XMLHttpRequest();

    request.onreadystatechange = function(){
       if(this.readyState === 4 && this.status === 200){
            document.querySelector('.check-'+id).innerText = this.responseText;
       }
    };

    request.open('GET', '/backend/pages/handlers/handler.php?id='+id+'&table='+table, true);
    request.send();
}

function deleteCheck() {
    return confirm('Удалить запись безвозвратно? Вы уверены?');
}

function hideEditForm() {
    let form = document.querySelector('.form-editor');
    form.remove();
}

function generateLink(event) {
    event.preventDefault();
    let text = document.querySelector('.name-for-generate').value;
    if (text === null || text === '') {
        return '';
    }

    const englishAndNumbers = 'abcdefghijklmnopqrstuvwxyz1234567890';
    const russian = ['а', 'a', 'б', 'b', 'в', 'v', 'г', 'g', 'д',  'd', 'е', 'e', 'ё', 'e', 'ж', 'zh', 'з', 'z', 'и', 'i', 'й', 'y', 'к', 'k',
                    'л', 'l', 'м', 'm', 'н', 'n', 'о', 'o', 'п', 'p', 'р', 'r', 'с', 's', 'т', 't', 'у', 'u', 'ф', 'f', 'х', 'h', 'ц', 'ts',
                    'ч', 'ch', 'ш', 'sh', 'щ', 'shch', 'ъ', '', 'ы', 'i', 'ь', '', 'э', 'e', 'ю', 'yu', 'я', 'ya' ];

    let newLink = '';
    let textArray = text.split('');

    textArray.forEach(function (item) {
        let itemLowerCase = item.toLowerCase();
        if(englishAndNumbers.indexOf(itemLowerCase) !== -1) {
            newLink += itemLowerCase;
        } else {
            let addSymbol = false;
            for (let index = 0; index < russian.length; index++) {
                if (russian[index] === itemLowerCase) {
                    newLink += russian[index+1];
                    addSymbol = true;
                    break;
                }
            }
            if (!addSymbol) {
                newLink += '-';
            }
        }
    });

    let inputLink = document.querySelector('.input-for-generate');
    inputLink.value = newLink.replace(/(-)+/g, '-');
}

if((pageUrl === 'options') && actionUrl !== 'edit'){
    hideEditForm();
}

tabColor();
changeSubMenu(); 
