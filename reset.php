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

        function write_to_db($database){
            $database_file = "./database.json";

            if (!file_exists($database_file)){
                touch($database_file);
            }

            $open_file = fopen($database_file, "w");
            $data_to_write = json_encode($database);
            
            fwrite($open_file, $data_to_write);
            fclose($open_file);
        }

        function read_db(){
            $database_file = "./database.json";

            if (!file_exists($database_file)){
                // file has not been created, use empty array
                $database = array();
                return $database;
            } else {
                $open_file = fopen($database_file, "r");
                $data = fread($open_file, filesize($database_file));
                $database = json_decode($data, true);

                return $database;
            }
        }

        $error_message = "";
        $username = "";
        $password = "";
        $password_again = "";

        if (isset($_POST["submit"])){
            $username = $_POST["username"];
            $password = $_POST["password"];
            $password_again = $_POST["password_again"];

            $database = read_db();

            if (isset($database[$username])){
                if ($password == $password_again){
                    $database[$username]["password"] = $password;
                    write_to_db($database);
                    
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
