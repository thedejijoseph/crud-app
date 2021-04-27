<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superhumans Academy | Edit a course</title>

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
            $course_id = $_POST["course_id"];
            $course_name = $_POST["course_name"];
            $course_description = $_POST["course_description"];
            
            $update_course_data = [
                "course_id" => $course_id,
                "course_name" => $course_name,
                "course_description" => $course_description
            ];

            update_course($conn, $update_course_data);
            // should be checking for failure though...
            header("Location: courses.php");
        }
    ?>

    <?php

    if(!isset($_GET["course_id"])){
        echo('
            <div class="small-container">
                <div class="flex-row">
                    <div class="flex-small">
                        <h4>Edit a course</h4>
                        <p>You have not selected a course. See <a href="courses.php">all courses</></p>
                    </div>
                </div>
            </div>
        ');
    } else {
        $course_id = $_GET["course_id"];
        $course = select_course_by_id($conn, $course_id);
        echo('
            <div class="small-container">
                <div class="flex-row">
                    <div class="flex-small">
                        <h4>Edit a course</h4>

                        <form action="courses-edit.php" method="post">
                            <input type="hidden" name="course_id" id="course_id" value="'.$course_id.'">
                            <label for="course_name">Course name</label>
                            <input type="text" name="course_name" id="course_name" placeholder="Basic time travel" value="'.$course['name'].'">

                            <label for="course_description">Course description</label>
                            <textarea name="course_description" id="course_description" placeholder="Understanding the rudiments and practicalities of time travel">'.$course['description'].'</textarea>

                            <input type="submit" name="submit" value="Update course" class="button">
                        </form>
                    </div>
                </div>
            </div>
        ');
    }
    ?>
</body>
</html>
