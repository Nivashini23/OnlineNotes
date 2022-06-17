<?php
    $sessionID = (int)$_POST['id'];
    $noteID = (int)$_POST['note_ID'];
    $conn = mysqli_connect("localhost", "root", "", "database", "3308");
    $sql = "DELETE FROM tbl_notes WHERE ID = ".$sessionID." AND note_ID = ".$noteID;
    mysqli_query($conn, $sql);
?>