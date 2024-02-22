<?php
    $content = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/showReg/contentShowReg.php');
    $title = 'Просмотр данных регистраций';
    include $_SERVER['DOCUMENT_ROOT'].'/templates/layout.php';
