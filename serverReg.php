<?php
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
    
    $telephone = $_POST['telephone'];
    if (
        empty($telephone)
        || !preg_match('/^\+7\([0-9]{3}\)[ ][0-9]{3}\-[0-9]{4}$/', $telephone)
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
    

    if (!empty($errors)) {
        $dataReg['success'] = false;
        $dataReg['errors'] = $errors;
    } else {

        try {
            $hash = password_hash($pass, PASSWORD_BCRYPT);

            define('FILE_BASE_NAME', 'database_XD.txt');

            $fileBase = fopen(FILE_BASE_NAME, 'a');

            if (!$fileBase)
            {
                throw new Exception('Ошибка при открытии файла базы данных регистрации');  
            }

            if (filesize(FILE_BASE_NAME))
            {
                fwrite($fileBase, "\n");
            }

            fwrite($fileBase, "Логин: $login\n");
            fwrite($fileBase, "E-mail: $email\n");
            fwrite($fileBase, "Номер телефона: $telephone\n");
            fwrite($fileBase, "$hash");
            
            $dataReg['success'] = true;
            $dataReg['message'] = 'Success!';
        }
        catch (Exception $ex) {
            $errors['dataBase'] = 'Ошибка на сервере, попробуйте позже.';
            $dataReg['success'] = false;
            $dataReg['errors'] = $errors;

            error_log($ex->getMessage());
        } finally {
            fclose($fileBase);
        }

    }
    
    echo json_encode($dataReg);
