<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superhumans Academy | Logout</title>

    <!-- using for 'minimal' styling -->
    <link rel="stylesheet" href="https://taniarascia.github.io/primitive/css/main.css">
</head>
<body>
    <?php
      session_destroy();
    ?>

    <div class="small-container">
        <div class="flex-row">
            <div class="flex-small">
                <h4>Bye, for now, super student!</h4>
                <p>You've been logged out!</p>
                <a href="login.php" class="button">Login</a>
                <a href="register.php" class="button">Create an account</a>
            </div>
        </div>
    </div>
</body>
</html>
