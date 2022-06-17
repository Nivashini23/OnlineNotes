<?php
    session_start();
    $count = $_GET['count'];
    setcookie("fromCookie", "", time() - 3600);
    for($i = 0; $i <= (int)$count; $i++) {
        setcookie("note_".$i, "", time() - 3600);
    }
    session_unset();
    session_destroy();
    header("Location: ./login.php");
?>