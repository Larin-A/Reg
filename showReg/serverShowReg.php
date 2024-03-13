<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/database/useDatabaseReg.php';

    $dataResponse = [];
    $errors = [];
    $dataReg = [];
    $countRecords = 0;
    
    try {

        $useDatabase = new UseDatabaseReg;

        $resultSQL = $useDatabase->getData();

        while ($row = mysqli_fetch_array($resultSQL)) {

            $dataReg[$countRecords] = json_encode($row);
            $countRecords++;            
        }

        if ($countRecords) {
            $dataResponse['success'] = true;
            $dataResponse['message'] = 'Success!';
            $dataResponse['dataToShow'] = $dataReg;
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
        unset($useDatabase);
    }

    echo json_encode($dataResponse);
