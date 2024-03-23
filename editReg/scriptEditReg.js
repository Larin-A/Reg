let dataGet = {
    login: new URLSearchParams(document.location.search).get('login')
}

let id = "-";

$.ajax({
    type: "GET",
    url: "/editReg/serverEditReg.php",
    dataType: "json",
    data: dataGet,
    encode: true,
}).done(function (dataUser) {
    if (!dataUser) {
        alert("Не удалось получить данные для данного пользователя");
        return;
    }
    $("#login").val(dataUser["login"]);
    $("#telephone").val(dataUser["telephone"]);
    $("#email").val(dataUser["email"]);

    id = dataUser["id"];
}).fail(function () {
    alert("Ошибка получения данных для редактирования");
});



$(function() {
    $("#formEditReg").submit(function(event) {
        event.preventDefault();

        let message = document.getElementById("message");
    
        let loginElement = $("#login");
        loginElement.val(loginElement.val().trim());

        let login = $("#login").val();
        if(login.match(/^[a-zа-яё0-9]{3,32}$/iu) == null) {
            message.innerHTML = "Логин может содержать только английские/русские буквы и цифры";
            return false;
        }

        let pass = $("#pass").val();
        if(pass.match(/^[a-zа-яё0-9!@#$%^&*]{8,32}$/iu) == null && pass != "") {
            message.innerHTML = "Пароль может содержать только английские/русские буквы и цифры или символы из набора !@#$%^&*";
            return false;
        }

        let pass2 = $("#pass2").val();
        if(pass2 != pass) {
            message.innerHTML = "Пароли должны совпадать";
            return false;
        }
        
        message.innerHTML = "";

        let email = $("#email").val();
        let telephone = $("#telephone").val();

        formData = {
            id,
            login,
            email,
            telephone,
            pass,
            pass2,
        };

        $.ajax({
            type: "POST",
            url: "/editReg/serverEditReg.php",
            data: formData,
            dataType: "json",
            encode: true,
        }).done(function (data) {
            console.log(data);
            if (!data.success) {
                let errString = "При обновлении записи обнаружены ошибки:\n\n";
                
                for (key in data.errors)
                {
                    errString += data.errors[key] + "\n\n";
                }

                alert(errString);
            } else {
                alert("Данные обновлены!");
                window.location.href = '/showReg/showReg.php';
            }

        }).fail(function () {
            console.log("Ошибка отправки формы");
        });

    });
});
