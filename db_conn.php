<?php
    $sname= "localhost";
    $uname= "root";
    $password = "";
    $db_name = "database";
    $port = "3308";
    $conn = mysqli_connect($sname, $uname, $password, $db_name, $port);
    if (!$conn) {
        echo "Connection failed!";
    }
?>