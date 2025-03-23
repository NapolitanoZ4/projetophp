<?php

if (!isset($_POST['usuario']) || $_POST['usuario'] == '') {
    die("FAVOR INFORMAR UMA SENHA");
}


if (!isset($_POST['senha']) || $_POST['senha'] == '') {
    die("FAVOR INFORMAR UMA SENHA");
}

if($_POST['usuario'] == '123' && $_POST['senha'] == '123'){
    session_start();

    $_SESSION['usuario'] = $_POST['usuario'];
    $_SESSION['senha'] = $_POST['senha'];

    header("Location: peincipal.php");
    die;
}else{
    die("USUARIO E SENHA INVALIDOS");
}
