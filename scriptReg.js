function Reset(form)
{
    form.reset();
}

function Submit(form)
{
    //debugger;
    let message = document.getElementById("message");
    

    let login = document.getElementById("id-login");
    login.value = login.value.trim();
    if(login.value.match(/^[a-zA-Zа-яёА-ЯЁ0-9]{3,32}$/) == null)
    {
        message.innerHTML = "Логин может содержать только английские/русские буквы и цифры"
        return false;
    }

    if(document.getElementById("id-pass").value.match(/^[a-zA-Zа-яёА-ЯЁ0-9!@#$%^&*]{8,32}$/) == null)
    {
        message.innerHTML = "Пароль может содержать только английские/русские буквы и цифры или символы из набора !@#$%^&*"
        return false;
    }

    if(document.getElementById("id-pass").value != document.getElementById("id-pass2").value)
    {
        message.innerHTML = "Пароли должны совпадать"
        return false;
    }
    
    form.submit();

    alert("Регестрация выполнена");

    return true;
}