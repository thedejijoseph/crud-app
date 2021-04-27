<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superhumans Academy | Reset password</title>

    <!-- using for 'minimal' styling -->
    <link rel="stylesheet" href="https://taniarascia.github.io/primitive/css/main.css">
</head>
<body>
    <?php
        include 'database.php';

        if(!isset($_SESSION["logged_in"])){
            header("Location: login.php");
        }

        $error_message = "";
        $username = "";
        $password = "";
        $password_again = "";

        if (isset($_POST["submit"])){
            $username = $_POST["username"];
            $password = $_POST["password"];
            $password_again = $_POST["password_again"];

            $user = select_user($conn, $username);

            if ($user){
                if ($password == $password_again){
                    $update_data = [
                        "username" => $username,
                        "password" => $password_again
                    ];
                    update_user_password($conn, $update_data);
                    
                    $_SESSION["logged_in"] = $username;
                    header("Location: index.php");
                } else {
                    $error_message = "Passwords do not match";
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
                    <h4>Let\'s reset your password</h4>

                    <form action="reset.php" method="post">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" placeholder="your superhuman name" value="'.$username.'">

                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="your access key">

                        <label for="password">Password again</label>
                        <input type="password" name="password_again" id="password_again" placeholder="your access key">

                        <p style="color: red">'.$error_message.'</p>

                        <input type="submit" name="submit" value="Reset password" class="button"> or just
                        <a href="register.php" class="button">Create an account</a>
                    </form>
                </div>
            </div>
        </div>
    ');
    ?>
</body>
</html>
