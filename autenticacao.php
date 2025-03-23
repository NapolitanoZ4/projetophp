<?php 

session_start();

if (!isset($_POST['usuario']) || $_POST['usuario'] == '') {
    header("Location: index.php");
    die;
}


if (!isset($_POST['senha']) || $_POST['senha'] == '') {
    header("Location: index.php");
    die;
}



?>