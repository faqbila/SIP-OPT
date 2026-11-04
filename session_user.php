<?php
session_start();

if(!isset($_SESSION['username'])){

    header("Location: login.php");
    exit;

}

if($_SESSION['role'] != "user"){

    header("Location: login.php");
    exit;

}
?>