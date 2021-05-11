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
        include 'database.php';
        
        if(!isset($_SESSION["logged_in"])){
            header("Location: login.php");
            exit();
        }

        $logged_in_user = $_SESSION["logged_in"];
        $user = select_user($conn, $logged_in_user);

        echo('
        <div class="small-container">
            <div class="flex-row">
                <div class="flex-small center">
                    <h4>Hello superhuman, '.$user["username"].'</h4>
                    <p>Welcome to your Superhuman Academy Dashboard</p>
                    <div class="flex-row">
                        <div class="flex-small">
                            <b>Your courses</b>
                            <hr/>
                            <p>Check out <a href="courses.php">all your courses</a>
                            <p>Or <a href="courses-new.php">create a new one</a></p>
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
