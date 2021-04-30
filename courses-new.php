<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superhumans Academy | Create a new course</title>

    <!-- using for 'minimal' styling -->
    <link rel="stylesheet" href="https://taniarascia.github.io/primitive/css/main.css">
</head>
<body>
    <?php
        include 'database.php';

        if(!isset($_SESSION["logged_in"])){
            header("Location: login.php");
        }

        $course_name = "";
        $course_description = "";

        if (isset($_POST["submit"])){
            $course_name = $_POST["course_name"];
            $course_description = $_POST["course_description"];

            // get logged in user
            $user = select_user($conn, $_SESSION["logged_in"]);
            $new_course_data = [
                "user" => $user,
                "course_name" => $course_name,
                "course_description" => $course_description
            ];

            insert_course($conn, $new_course_data);
            // should be checking for failure though...
            header("Location: courses.php");
        }
        
    ?>

    <?php

    echo('
        <div class="small-container">
            <div class="flex-row">
                <div class="flex-small">
                    <h4>Let\'s create a new course</h4>
                    <p>In our academy, you can describe the specific details of the course you want to take, and we\'ll make it available to you.</p>

                    <form action="courses-new.php" method="post">
                        <label for="course_name">Course name</label>
                        <input type="text" name="course_name" id="course_name" placeholder="Basic time travel" value="" autocomplete="off">

                        <label for="course_description">Course description</label>
                        <textarea name="course_description" id="course_description" placeholder="Understanding the rudiments and practicalities of time travel"></textarea>

                        <input type="submit" name="submit" value="Create course" class="button">
                    </form>
                </div>
            </div>
        </div>
    ');
    ?>
</body>
</html>
