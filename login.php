<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superhumans Academy | Login</title>

    <!-- using for 'minimal' styling -->
    <link rel="stylesheet" href="https://taniarascia.github.io/primitive/css/main.css">
</head>
<body>
    <?php
        include 'database.php';

        $error_message = "";
        $username = "";
        $password = "";

        if (isset($_POST["submit"])){
            $username = $_POST["username"];
            $password = $_POST["password"];

            $user = select_user($conn, $username);

            if ($user){
                if ($user['password'] == md5($password)){
                    $_SESSION["logged_in"] = $username;
                    header("Location: index.php");
                } else {
                    $error_message = "Incorrect password";
                }
            } else {
                $error_message = "User does not exist";
            }
            
        }
    ?>

    <?php
    echo('
    <div class="small-container">
        <div class="flex-row">
            <div class="flex-small">
                <h4>Access your Superhuman Academy Dashboard</h4>

                <form action="login.php" method="post">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="your superhuman name" value="'.$username.'">

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="your access key">

                    <p style="color: red">'.$error_message.'</p>

                    <input type="submit" name="submit" value="Login" class="button"> or
                    <a href="register.php" class="button">Create an account</a>
                </form>
            </div>
        </div>
    </div>
    ');
    ?>
</body>
</html>
