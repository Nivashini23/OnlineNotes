<!DOCTYPE html>
<html>
    <head>
        <title>Notes App</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="./styles/style.css">
    </head>
    <?php
        session_start();
        include "db_conn.php";
        $count = 0;
        $post_data = file_get_contents('php://input');
        $arr = explode('&', $post_data);
        $username = explode('=', $arr[0]);
        $password = explode('=', $arr[1]);
        $submit = explode('=', $arr[2]);
        $contents = [];
        if(strval($submit[1]) === "Login") {
            if ($username && $password) {
                function validate($data){
                   $data = trim($data);
                   $data = stripslashes($data);
                   $data = htmlspecialchars($data);
                   return $data;
                }
                $Username = validate(strval($username[1]));
                $Password = validate(strval($password[1]));
                if (empty($Username)) {
                    header("Location: ./login.php?error=Username is required");
                    exit();
                } else if (empty($Password)){
                    header("Location: ./login.php?error=Password is required");
                    exit();      
                } else {
                    $sql = "SELECT * FROM users WHERE Username='$Username' AND Password='$Password'";
                    $conn = mysqli_connect("localhost", "root", "", "database", "3308");
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) === 1) {
                        $row = mysqli_fetch_assoc($result);
                        if ($row['Username'] === $Username && $row['Password'] === $Password) {
                            $_SESSION['Username'] = $row['Username'];
                            $_SESSION['ID'] = $row['ID'];
                            $id = $row['ID'];
                            $sql = "SELECT MAX(note_ID) FROM tbl_notes WHERE ID=".$id;
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $_SESSION['Count'] = (int)$row['MAX(note_ID)'];
                        } else {
                            header("Location: ./login.php?error=Incorrect username or password");
                            exit();
                        }
                    } else {
                        header("Location: ./login.php?error=Incorrect username or password");
                        exit();
                    }
                }
            } else {
                header("Location: http://localhost:8080/scripts/NotesApp/login.php?error=No username or password provided");
                exit();
            }
        } else {
            if ($username && $password) {
                function validate($data){
                   $data = trim($data);
                   $data = stripslashes($data);
                   $data = htmlspecialchars($data);
                   return $data;
                }
                $Username = validate(strval($username[1]));
                $Password = validate(strval($password[1]));
                if (empty($Username)) {
                    header("Location: ./register.php?error=Username is required");
                    exit();
                } else if (empty($Password)){
                    header("Location: ./register.php?error=Password is required");
                    exit();      
                } else {
                    $sql = "SELECT * FROM users WHERE Username='$Username'";
                    $conn = mysqli_connect("localhost", "root", "", "database", "3308");
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) !== 0) {
                        header("Location: ./register.php?error=Username already taken");
                        exit();
                    } else {
                        $sql = "SELECT * FROM users";
                        $conn = mysqli_connect("localhost", "root", "", "database", "3308");
                        $result = mysqli_query($conn, $sql);
                        $ID = mysqli_num_rows($result) + 1;
                        $sql = "INSERT INTO `users` (`ID`, `Username`, `Password`) VALUES ('$ID', '$Username', '$Password')";
                        mysqli_query($conn, $sql);
                        $sql = "INSERT INTO tbl_notes (ID, note_ID, content) VALUES ($ID, 1, 'Text Content')";
                        mysqli_query($conn, $sql);
                        $_SESSION['Username'] = $Username;
                        $_SESSION['ID'] = $ID;
                        $_SESSION['Count'] = 1;
                    }
                }
            } else {
                header("Location: http://localhost:8080/scripts/NotesApp/login.php?error=No username or password provided");
                exit();
            }
        }
    ?>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 custom-nav">
            <div class="container-fluid">
                <a class="navbar-brand px-3" href="#"><img src="./public/logo.png" alt="" width="25" height="20" class="d-inline-block align-text-top"> Notes App</a>
                <div class="nav-item dropdown">
                    <form class="d-flex">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown px-5">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo $Username ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <li><a class="dropdown-item" href="./logout.php?count=<?php echo $_SESSION['Count']?>">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </nav>
        <?php
            $id = $_SESSION['ID'];
            $count = $_SESSION['Count'];
            if((int)$count > 0) {
                echo "<ul class=\"ul\">";
                $conn = mysqli_connect("localhost", "root", "", "database", "3308");
                $sql = "SELECT * FROM tbl_notes WHERE ID='$id'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                foreach ($result as $row) {
                    if(isset($_COOKIE["fromCookie"]) && isset($_COOKIE["note_".$row["note_ID"]])) {
                        if($_COOKIE["fromCookie"]."" === "true") {  
                            echo "<li id=".$row["note_ID"]." class=\"li id\">";
                            echo "<a class=\"note-a\" contenteditable=\"true\" href=\"#\">";
                            echo "<span>".$_COOKIE['note_'.$row["note_ID"]]."</span>";
                            $sqlUpdateNote = "UPDATE tbl_notes SET `content`='".$_COOKIE["note_".$row["note_ID"]]."' WHERE ID='".$id."' AND note_ID='".($row["note_ID"])."'";
                            mysqli_query($conn, $sqlUpdateNote);
                            echo "</a>";
                            echo "<span class=\"btn-del\" onclick=\"del(".$row["note_ID"].");\">X</span>";
                            echo "</li>";
                        } else {
                            echo "<li id=".$row["note_ID"]." class=\"li id\">";
                            echo "<a class=\"note-a\" contenteditable=\"true\" href=\"#\">";
                            echo "<span>".$row['content']."</span>";
                            echo "</a>";
                            echo "<span class=\"btn-del\" onclick=\"del(".$row["note_ID"].");\">X</span>";
                            echo "</li>";
                        }
                    } else {
                        echo "<li id=".$row["note_ID"]." class=\"li id\">";
                        echo "<a class=\"note-a\" contenteditable=\"true\" href=\"#\">";
                        echo "<span>".$row['content']."</span>";
                        echo "</a>";
                        echo "<span class=\"btn-del\" onclick=\"del(".$row["note_ID"].");\">X</span>";
                        echo "</li>";
                    }
                }
                echo "</ul>";
            }
        ?>
        <button class="btn-add" onclick="add();"><img src="./public/add-black.svg" alt="Add"></button>
        <script src="./node_modules/jquery/dist/jquery.js"></script>
        <script>
            function add() {
                var count = <?php echo $_SESSION['Count'] ?>;
                var id = <?php echo $_SESSION['ID'] ?>;
                $.ajax({
                    type: "POST",
                    url: "add.php",
                    data: {
                        count,
                        id
                    }
                }).done(function () {
                    location.reload(true);
                });
            }
            function del(note_ID) {
                var id = <?php echo $_SESSION['ID'] ?>;
                $.ajax({
                    type: "POST",
                    url: "del.php",
                    data: {
                        id,
                        note_ID
                    }
                }).done(function () {
                    location.reload(true);
                })
            }
            all_notes = $("li .note-a");
            all_notes.on("keyup", function () {
                note_content = $(this).find("a").prevObject[0].text;
                item_key = "note_" + ($(this).parent().index() + 1);
                data = {
                    content: note_content
                };
                document.cookie = item_key + "=" + data.content;
                document.cookie = "fromCookie=true";
                console.log(document.cookie);
            });
            all_notes.on("focusout", function () {
                location.reload(true);
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>