<?php
    $content = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/editReg/contentEditReg.php');
    $title = 'Редактирование записи';
    $styles[0] = '/reg/styleReg.css';

    error_log($content);

    include $_SERVER['DOCUMENT_ROOT'].'/templates/layout.php';
