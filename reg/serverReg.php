<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/database/connect.php';

    $errors = [];
    $dataReg = [];
    
    $login = $_POST['login'];
    if (
        empty( $login ) 
        || !preg_match('/^[a-zа-яё0-9]{3,32}$/iu', $login)
    ) {
        $errors['login'] = 'Логин должен иметь длину от 3 до 32 символов и состоять только из букв английского или русского алфавита и цифр.';
    }
    
    $email = $_POST['email'];
    if (
        empty($email) 
        || strlen($email) > 64
        || !preg_match('/^[a-zа-яё0-9_\-\.]{1,}@[a-zа-яё0-9_\-\.]{1,}\.[a-zа-яё0-9_\-\.]{2,}$/iu', $email)
    ) {
        $errors['email'] = 'Некорректный e-mail.';
    }
    
    $tel = $_POST['telephone'];
    if (
        empty($tel)
        || !preg_match('/^\+7\([0-9]{3}\)[ ][0-9]{3}\-[0-9]{4}$/', $tel)
    ) {
        $errors['telephone'] = 'Некорректный номер телефона.';
    }

    $pass = $_POST['pass'];
    if (
        empty($pass)
        || !preg_match('/^[a-zа-яё0-9!@#$%^&*]{8,32}$/iu', $pass)
    ) {
        $errors['pass'] = 'Пароль должен иметь длину от 8 до 32 символов и содержать только английские/русские буквы и цифры или символы из набора !@#$%^&*';
    }

    if ($pass != $_POST['pass2'])
    {
        $errors['pass2'] = "Пароли должны совпадать.";
    }
    

    $tableReg = Connect::$tableReg;
    $colId = Connect::$colId;
    $colLogin = Connect::$colLogin;
    $colPass = Connect::$colPass;
    $colEmail = Connect::$colEmail;
    $colTel = Connect::$colTel;

    $link;
    try {
        $link = mysqli_connect(Connect::$host, Connect::$user, Connect::$pass, Connect::$database);

        if ($link == false) {
            throw new Exception('Error connect database');  
        }

        mysqli_set_charset($link, Connect::$charset);

        $resultSQL = mysqli_query($link, "SELECT $colLogin FROM $tableReg WHERE $colLogin = '$login'");
        if (mysqli_fetch_array($resultSQL) != null) {
            $errors['login2'] = 'Логин уже используется.';
        }

        $resultSQL = mysqli_query($link, "SELECT $colEmail FROM $tableReg WHERE $colEmail = '$email'");
        if (mysqli_fetch_array($resultSQL) != null) {
            $errors['email2'] = 'E-mail уже используется.';
        }

        $resultSQL = mysqli_query($link, "SELECT $colTel FROM $tableReg WHERE $colTel = '$tel'");
        if (mysqli_fetch_array($resultSQL) != null) {
            $errors['telephone2'] = 'Номер телефона уже используется.';
        }

    } catch (Exception $ex) {
        $errors['dataBase'] = 'Ошибка на сервере, попробуйте позже.';
        $dataReg['success'] = false;
        $dataReg['errors'] = $errors;

        error_log($ex->getMessage());
    } finally {        
        mysqli_close($link);
    }
  


    if (!empty($errors)) {
        $dataReg['success'] = false;
        $dataReg['errors'] = $errors;
    } else {

        try {
            $link = mysqli_connect(Connect::$host, Connect::$user, Connect::$pass, Connect::$database);

            if ($link == false) {
                throw new Exception('Error connect database');
            }

            mysqli_set_charset($link, Connect::$charset);

            $hash = password_hash($pass, PASSWORD_BCRYPT);

            if (
                !mysqli_query(
                    $link,
                    "INSERT INTO $tableReg ($colId, $colLogin, $colEmail, $colTel, $colPass) SELECT null, '$login', '$email', '$tel', '$hash'"
                    )
            ) {
                throw new Exception('Error insert database');
            }
            
            $dataReg['success'] = true;
            $dataReg['message'] = 'Success!';
        }
        catch (Exception $ex) {
            $errors['dataBase'] = 'Ошибка на сервере, попробуйте позже.';
            $dataReg['success'] = false;
            $dataReg['errors'] = $errors;

            error_log($ex->getMessage());
        } finally {        
            mysqli_close($link);
        }

    }
    
    echo json_encode($dataReg);
