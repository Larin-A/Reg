<div class="divForm">
    <form action="/editReg/serverEditReg.php" method="POST" id="formEditReg" autocomplete="on">
        <h1>Редактирование записи</h1>
        <div class="grid-form-reg">
            <label for="login">Логин</label>
            <input type="text" name="login" id="login" placeholder="User123" required minlength="3" maxlength="32" autocomplete="username">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" placeholder="user123@gmail.com" required minlength="5" maxlength="64" autocomplete="email">
            <label for="telephone">Номер телефона</label>
            <input type="text" name="telephone" id="telephone" placeholder="+7(900) 000-0000" required autocomplete="tel">
            <label for="pass">Пароль</label>
            <input type="password" name="pass" id="pass" placeholder="Qwerty123!" maxlength="32" autocomplete="new-password">
            <label for="pass2">Повторите пароль</label>
            <input type="password" name="pass2" id="pass2" placeholder="Qwerty123!"maxlength="32" autocomplete="new-password">
            <button type="reset" name="res" for="form-reg">Сброс</button>
            <button type="submit" name="subm" for="form-reg">Обновить</button>
            <p id="message" style="grid-column: 1/3;"></p>
        </div>
    </form>
</div>
<script src="/reg/mask.js"></script>
<script src="/editReg/scriptEditReg.js"></script>
<script>
    $("#telephone").mask("+7(999) 999-9999");
</script>
