<?php
session_start();


    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: ../index.php");
        exit;
    }

    session_destroy();
    header("Location: ../index.php");
    exit;
?>