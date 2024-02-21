<?php
    define('FILE_BASE_NAME', $_SERVER['DOCUMENT_ROOT'].'/database/database_XD.txt');

    $dataResponse = [];
    $errors = [];
    $dataToShow = [];
    $countRecords = 0;
    $dataToShow[$countRecords] = "";

    try {

        $fileBase = fopen(FILE_BASE_NAME, 'r');

        if (!$fileBase) {
            throw new Exception('Ошибка при открытии файла базы данных регистрации');  
        }

        while (!feof($fileBase)) {
            $str = fgets($fileBase);
            
            if (!$str) {
                break;
            }

            if (preg_match('/^Логин: |^E-mail: |^Номер телефона: /', $str)) {
                $dataToShow[$countRecords] .= $str."<br>";
            }
            else {
                $countRecords++;
                $dataToShow[$countRecords] = "";
            }
            
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
        fclose($fileBase);
    }

    echo json_encode($dataResponse);
