"use strict";

const regThumbnails = $("#regThumbnails");

let saveData;

$("#nav-showReg").addClass("active");

$.ajax({
    type: "GET",
    url: "/showReg/serverShowReg.php",
    dataType: "json",
    encode: true,
}).done(function (data) {
    console.log(data);

    if (!data.success) {
        let errString = "При запросе данных регистраций возникли ошибки:<br>";
        for (key in data.errors) 
        {
            errString += data.errors[key] + "<br>";
        }
        writeThumbnailMessage(errString);
        return;
    } 

    if (data.countRecords == 0) {
        writeThumbnailMessage("Записи о регистрации не найдены.");
        return;
    }
    
    saveData = data.dataToShow;
    for (let i = 0; i < data.countRecords; i++) {
        writeThumbnailReg(JSON.parse(data.dataToShow[i]));
    }

}).fail(function () {
    writeThumbnailMessage("Ошибка запроса данных регистраций.");
});


function addThumbnail(container) {
    let thumbnail = document.createElement('li');
    thumbnail.className = "span4";
    container.append(thumbnail);
    return thumbnail;
}

function getNewParagraphThumbnail(thumbnail) {
    let paragraph = document.createElement('p');
    paragraph.className = "thumbnail";
    paragraph.style = "padding: 20px";
    thumbnail.append(paragraph);
    return paragraph;
}

function addButtonDelEdit(thumbnail) {
    let buttonDel = document.createElement('button');
    buttonDel.type = "button";
    buttonDel.className = "btn btn-link pull-right";
    buttonDel.addEventListener("click", buttonDelClick);
    buttonDel.innerHTML = '<i class="icon-trash"> </i>';
    thumbnail.append(buttonDel);

    let buttonEdit = document.createElement('button');
    buttonEdit.type = "button";
    buttonEdit.className = "btn btn-link pull-right";
    buttonEdit.addEventListener("click", buttonEditClick);
    buttonEdit.innerHTML = '<i class="icon-pencil"> </i>';
    thumbnail.append(buttonEdit);
}

function writeThumbnailReg(registration) {
    let thumbnail = addThumbnail(regThumbnails);
    thumbnail.id = registration.id;
    addButtonDelEdit(thumbnail);
    let paragraph = getNewParagraphThumbnail(thumbnail);
    paragraph.innerHTML = 'Логин: ' + registration.login + '<br>E-mail: ' + registration.email + '<br>Номер телефона: ' + registration.telephone;
}

function writeThumbnailMessage(text) {
    let thumbnail = addThumbnail(regThumbnails);
    let paragraph = getNewParagraphThumbnail(thumbnail);
    paragraph.innerHTML = text;
}


function buttonDelClick() {
    if (!confirm('Удалить запись?')) {
        return;
    }

    let id = this.parentNode.id;
    
    let delData  = {
        id,
    };

    $.ajax({
        type: "POST",
        url: "/showReg/serverDel.php",
        data: delData,
        dataType: "json",
        encode: true,
    }).done(function (message) {
        if (message == "Запись удалена") {
            $("#" + id).remove();
        }

        alert(message);
        
    }).fail(function () {
        console.log("Ошибка удаления");
        alert("Ошибка удаления");
    });
}

function buttonEditClick() {
    let id = this.parentNode.id;
    
    let editData  = JSON.parse(saveData.find((item) => JSON.parse(item).id == id));

    window.location.href = "/editReg/editReg.php?login=" + editData.login;
}
