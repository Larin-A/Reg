<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/database/connect.php';

    $tableReg = Connect::$tableReg;
    $colLogin = Connect::$colLogin;
    $colEmail = Connect::$colEmail;
    $colTel = Connect::$colTel;

    $dataResponse = [];
    $errors = [];
    $dataToShow = [];
    $countRecords = 0;
    
    try {

        $link = mysqli_connect(Connect::$host, Connect::$user, Connect::$pass, Connect::$database);

        if ($link == false) {
            throw new Exception('Error connect database');  
        }

        mysqli_set_charset($link, Connect::$charset);

        $resultSQL = mysqli_query($link, "SELECT $colLogin, $colEmail, $colTel FROM $tableReg");

        while ($row = mysqli_fetch_array($resultSQL)) {

            $dataToShow[$countRecords] = 'Логин: '.$row[$colLogin].'<br>E-mail: '.$row[$colEmail].'<br>Номер телефона: '.$row[$colTel];
            $countRecords++;            
        }

        if ($countRecords) {
            $dataResponse['success'] = true;
            $dataResponse['message'] = 'Success!';
            $dataResponse['dataToShow'] = $dataToShow;
            $dataResponse['countRecords'] = $countRecords;
        }
        else {
            $dataResponse['success'] = true;
            $dataResponse['message'] = 'Записи в базе не найдены.';
            $dataResponse['countRecords'] = $countRecords;
        }
    }
    catch (Exception $ex) {
        $errors['dataBase'] = 'Ошибка на сервере, попробуйте позже.';
        $dataResponse['success'] = false;
        $dataResponse['errors'] = $errors;

        error_log($ex->getMessage());
    } finally {
        mysqli_close($link);
    }

    echo json_encode($dataResponse);
