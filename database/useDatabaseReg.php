<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/database/connect.php';

class UseDatabaseReg {
    private $link;

    public function __construct() {

        $this->link = mysqli_connect(Connect::$host, Connect::$user, Connect::$pass, Connect::$database);
        if ($this->link == false) {
           throw new Exception('Error connect database');
        }

        mysqli_set_charset($this->link, Connect::$charset);
    }

    public function __destruct()
   {
        mysqli_close($this->link);
   }
  
    public function checkUniqueness($login, $email, $telephone, &$notUniqueness) {

        $resultSQL = mysqli_query($this->link, "SELECT login FROM user_data_reg WHERE login = '$login'");
        if (mysqli_fetch_array($resultSQL) != null) {
            $notUniqueness['loginNotUniqueness'] = 'Логин уже используется.';
        }

        $resultSQL = mysqli_query($this->link, "SELECT email FROM user_data_reg WHERE email = '$email'");
        if (mysqli_fetch_array($resultSQL) != null) {
            $notUniqueness['emailNotUniqueness'] = 'E-mail уже используется.';
        }

        $resultSQL = mysqli_query($this->link, "SELECT telephone FROM user_data_reg WHERE telephone = '$telephone'");
        if (mysqli_fetch_array($resultSQL) != null) {
            $notUniqueness['telephoneNotUniqueness'] = 'Номер телефона уже используется.';
        }

        if (!empty($notUniqueness)) {
            return false;
        } else {
            return true;
        }
    }

    public function checkCorrectness($login, $email, $telephone, &$notCorrectness) {

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

    public function add($login, $email, $telephone, $hash, &$errors) {

        $this->checkUniqueness($login, $email, $telephone, $errors);
        if (!empty($errors)) {
            return false;
        }
        
        $this->checkCorrectness($login, $email, $telephone, $errors);
        if (!empty($errors)) {
            return false;
        }

        if (
            !mysqli_query(
                $this->link,
                "INSERT INTO user_data_reg (login, email, telephone, hashPass) SELECT  '$login', '$email', '$telephone', '$hash'"
                )
        ) {
            $errors['database'] = 'Ошибка добавления в базу данных';
            return false;
        }

        return true;
    } 

    public function getData() {
        return mysqli_query($this->link, "SELECT id, login, email, telephone FROM user_data_reg");
    }
}
