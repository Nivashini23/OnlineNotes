<?php
    $sessionID = (int)$_POST['id'];
    $count = (int)$_POST['count'] + 1;
    $conn = mysqli_connect("localhost", "root", "", "database", "3308");
    $sql = "INSERT INTO tbl_notes (ID, note_ID, content) VALUES ($sessionID, $count, 'Text Content')";
    mysqli_query($conn, $sql);
?>