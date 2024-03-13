<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/database/useDatabaseReg.php';

    $errors = [];
    $dataReg = [];
    
    $login = $_POST['login'];
    $email = $_POST['email'];
    $tel = $_POST['telephone'];
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
        $hash = password_hash($pass, PASSWORD_BCRYPT);

        try {
            $useDatabase = new UseDatabaseReg;
            
            if ($useDatabase->add($login, $email, $tel, $hash, $errors)) {
                $dataReg['success'] = true;
                $dataReg['message'] = 'Success!';
            } else {
                $dataReg['success'] = false;
                $dataReg['errors'] = $errors;
            }

        } catch (Exception $ex) {
            $errors['database'] = 'Ошибка на сервере, попробуйте позже.';
            $dataReg['success'] = false;
            $dataReg['errors'] = $errors;

            error_log($ex->getMessage());
        } finally {        
            unset($useDatabase);
        }
    }
    
    echo json_encode($dataReg);
