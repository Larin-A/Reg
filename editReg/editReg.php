<?php
    $content = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/editReg/contentEditReg.php');
    $title = 'Редактирование записи';
    $styles[0] = '/reg/styleReg.css';
    
    $id = $_POST['id'];
    $login = $_POST['login'];
    $email = $_POST['email'];
    $tel = $_POST['telephone'];

    $content = sprintf( $content, $id, $login, $email, $tel); 

    error_log($content);

    include $_SERVER['DOCUMENT_ROOT'].'/templates/layout.php';
