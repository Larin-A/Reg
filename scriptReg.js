$(document).ready(function() {
    $("#formReg").submit(function(event) {
        let message = document.getElementById("message");
    
        let loginElement = $("#login");
        loginElement.val(loginElement.val().trim());

        let login = $("#login").val();
        if(login.match(/^[a-zа-яё0-9]{3,32}$/iu) == null)
        {
            message.innerHTML = "Логин может содержать только английские/русские буквы и цифры";
            return false;
        }

        let pass = $("#pass").val();
        if(pass.match(/^[a-zа-яё0-9!@#$%^&*]{8,32}$/iu) == null)
        {
            message.innerHTML = "Пароль может содержать только английские/русские буквы и цифры или символы из набора !@#$%^&*";
            return false;
        }

        let pass2 = $("#pass2").val();
        if(pass2 != pass)
        {
            message.innerHTML = "Пароли должны совпадать";
            return false;
        }
        

        let email = $("#email").val();
        let telephone = $("#telephone").val();

        formData = {
            login,
            email,
            telephone,
            pass,
            pass2,
        };

        $.ajax({
            type: "POST",
            url: "serverReg.php",
            data: formData,
            dataType: "json",
            encode: true,
        }).done(function (data) {
            console.log(data);
            if (!data.success)
            {
                let errString = "В данных формы обнаружены ошибки:\n\n";
                
                errString += data.errors["login"] + "\n\n";
                errString += data.errors["email"] + "\n\n";
                errString += data.errors["telephone"] + "\n\n";
                errString += data.errors["pass"] + "\n\n";
                errString += data.errors["pass2"];

                alert(errString);
            } else
            {
                alert("Вы успешно зарегистрировались!");
                $("#formReg")[0].reset();
            }

        }).fail(function () {
            console.log("Ошибка отправки формы");
        });


        event.preventDefault();
    });
});