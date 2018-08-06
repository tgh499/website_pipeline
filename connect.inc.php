<?php
    $user = 'root';
    $password = 'root';
    $db = 'breast_cancer';
    $host = 'localhost';
    $port = 3306;
    $socket = 'localhost:/Applications/MAMP/tmp/mysql/mysql.sock';

    $link = mysqli_init();

    $mysqli = new mysqli($host, $user, $password, $db);
    $mysqli->select_db($db) or die( "Unable to select database");
?>
