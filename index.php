<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superhumans Academy</title>

    <!-- using for 'minimal' styling -->
    <link rel="stylesheet" href="https://taniarascia.github.io/primitive/css/main.css">
</head>
<body>
    <?php
        if(!isset($_SESSION["logged_in"])){
            header("Location: login.php");
            exit();
        }

        function read_db(){
            if (file_exists("database.json")){
                $data = file_get_contents("database.json");
                $database = json_decode($data, true);
                return $database;
            } else {
                $database = array();
                return $database;
            }

        }

        $logged_in_user = $_SESSION["logged_in"];
        $database = read_db();
        
        $user = $database[$logged_in_user];

        echo('
        <div class="small-container">
            <div class="flex-row">
                <div class="flex-small center">
                    <h4>Hello superhuman, '.$user["username"].'</h4>
                    <p>Welcome to your Superhuman Academy Dashboard</p>
                    <div class="flex-row">
                        <div class="flex-small">
                            <b>Registered courses</b>
                            <hr/>
                            <p>You do not have any course registered. Create one!</p>
                        </div>
                        <div class="flex-small">
                            <b>Superhuman account</b>
                            <hr/>
                            <div><a href="logout.php" class="button">Logout</a></div>
                            <div><a href="reset.php" class="button">Reset password</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ');
    ?>
</body>
</html>
