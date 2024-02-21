"use strict";

const regThumbnails = $("#regThumbnails");

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

function writeThumbnailReg(text) {
    let paragraph = getNewParagraphThumbnail(addThumbnail(regThumbnails));
    paragraph.innerHTML = text;
}

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
        writeThumbnailReg(errString);
        return;
    } 

    if (data.countRecords == 0) {
        writeThumbnailReg("Записи о регистрации не найдены.");
        return;
    }

    for (let i = 0; i < data.countRecords; i++) {
        writeThumbnailReg(data.dataToShow[i]);
    }

}).fail(function () {
    writeThumbnailReg("Ошибка запроса данных регистраций.");
});