<?php
    $content = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/reg/contentReg.php');
    $title = 'Регистрация';
    $styles[0] = '/reg/styleReg.css';
    include $_SERVER['DOCUMENT_ROOT'].'/templates/layout.php';