<?php 

session_start();

if (!isset($_SESSION['cpf']) || $_SESSION['cpf'] == '') {
    header("Location: index.php");
    die;
}

if (!isset($_SESSION['senha']) || $_SESSION['senha'] == '' ) {
    header("Location: index.php");
    die;
}

?>