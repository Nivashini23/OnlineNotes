<!DOCTYPE html>
<html>
    <head>
        <title>LOGIN</title>
        <link rel="stylesheet" type="text/css" href="./styles/login_styles.css">
    </head>
    <body>
        <form action="http://localhost:8080/scripts/NotesApp/index.php" method="POST">
            <?php if (isset($_GET['error']))
                header("Location: ./register.php");
            ?>
            <div class="login">
                <h2 class="h2">User Registration</h2>
                <input type="text" name="Username" placeholder="Username"><br>
                <input type="password" name="Password" placeholder="Password"><br>
                <button type="submit" name="submit" class="submit" value="Register">Register</button><br>
                <center><a href="./login.php">Already have an account?</a></center>
            </div>
        </form>
    </body>
</html>