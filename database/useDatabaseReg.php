<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/database/ConfigDatabase.php';

class UseDatabaseReg {
    private $connect;

    public function __construct()
    {

        $this->connect = mysqli_connect(ConfigDatabase::$host, ConfigDatabase::$user, ConfigDatabase::$pass, ConfigDatabase::$database);
        if ($this->connect == false) {
           throw new Exception('Error connect database');
        }

        mysqli_set_charset($this->connect, ConfigDatabase::$charset);
    }

    public function __destruct()
   {
        mysqli_close($this->connect);
   }
  
    public function checkUniqueness($login, $email, $telephone, &$notUniqueness, $noId = -1)
    {

        $resultSQL = mysqli_query($this->connect, "SELECT login FROM user_data_reg WHERE login = '$login' && id != $noId");
        if (mysqli_fetch_array($resultSQL) != null) {
            $notUniqueness['loginNotUniqueness'] = 'Логин уже используется.';
        }

        $resultSQL = mysqli_query($this->connect, "SELECT email FROM user_data_reg WHERE email = '$email' && id != $noId");
        if (mysqli_fetch_array($resultSQL) != null) {
            $notUniqueness['emailNotUniqueness'] = 'E-mail уже используется.';
        }

        $resultSQL = mysqli_query($this->connect, "SELECT telephone FROM user_data_reg WHERE telephone = '$telephone' && id != $noId");
        if (mysqli_fetch_array($resultSQL) != null) {
            $notUniqueness['telephoneNotUniqueness'] = 'Номер телефона уже используется.';
        }

        if (!empty($notUniqueness)) {
            return false;
        } else {
            return true;
        }
    }

    public function checkCorrectness($login, $email, $telephone, &$notCorrectness)
    {

        if (
            empty($login) 
            || !preg_match('/^[a-zа-яё0-9]{3,32}$/iu', $login)
        ) {
            $notCorrectness['login'] = 'Логин должен иметь длину от 3 до 32 символов и состоять только из букв английского или русского алфавита и цифр.';
        }

        if (
            empty($email) 
            || strlen($email) > 64
            || !preg_match('/^[a-zа-яё0-9_\-\.]{1,}@[a-zа-яё0-9_\-\.]{1,}\.[a-zа-яё0-9_\-\.]{2,}$/iu', $email)
        ) {
            $notCorrectness['email'] = 'Некорректный e-mail.';
        }

        if (
            empty($telephone)
            || !preg_match('/^\+7\([0-9]{3}\)[ ][0-9]{3}\-[0-9]{4}$/', $telephone)
        ) {
            $notCorrectness['telephone'] = 'Некорректный номер телефона.';
        }

        if (!empty($notCorrectness)) {
            return false;
        } else {
            return true;
        }
    }

    public function add($login, $email, $telephone, $hash, &$errors)
    {
        
        $this->checkCorrectness($login, $email, $telephone, $errors);
        if (!empty($errors)) {
            return false;
        }

        $this->checkUniqueness($login, $email, $telephone, $errors);
        if (!empty($errors)) {
            return false;
        }

        if (
            !mysqli_query(
                $this->connect,
                "INSERT INTO user_data_reg (login, email, telephone, hashPass) VALUES ('$login', '$email', '$telephone', '$hash')"
                )
        ) {
            $errors['database'] = 'Ошибка добавления в базу данных';
            return false;
        }

        return true;
    } 

    public function getData()
    {
        return mysqli_query($this->connect, "SELECT id, login, email, telephone FROM user_data_reg");
    }

    public function getByLogin($login)
    {
        return mysqli_fetch_array(mysqli_query($this->connect, "SELECT id, login, email, telephone FROM user_data_reg WHERE login = '$login'"));
    }

    public function delete($id)
    {
        return mysqli_query($this->connect, "DELETE FROM user_data_reg WHERE id = '$id'");
    }

    public function update($id, $login, $email, $telephone, $hash, &$errors)
    {
        
        $this->checkCorrectness($login, $email, $telephone, $errors);
        if (!empty($errors)) {
            return false;
        }
        $this->checkUniqueness($login, $email, $telephone, $errors, $id);
        if (!empty($errors)) {
            return false;
        }

        if ($hash != null) {
            $request = "UPDATE user_data_reg SET login = '$login', email = '$email', telephone = '$telephone', hashPass = '$hash' WHERE id = $id";
        } else {
            $request = "UPDATE user_data_reg SET login = '$login', email = '$email', telephone = '$telephone' WHERE id = $id";
        }

        if (!mysqli_query($this->connect, $request)) {
            $errors['database'] = 'Ошибка обновления записи в базе';
            return false;
        }

        return true;
    }
}
