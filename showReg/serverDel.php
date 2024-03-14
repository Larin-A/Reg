<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/database/useDatabaseReg.php';

    $id = $_POST['id'];

    $message = "";

    if (!is_numeric($id)) {
        $message = "Некорректный id записи";
        echo json_encode($message);
        return;
    }
    
    $useDatabase = new UseDatabaseReg;

    if ($useDatabase->delete($id)) {
        $message = "Запись удалена";
    } else {
        $message = "Ошибка удаления";
    }

    echo json_encode($message);

