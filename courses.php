<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superhumans Academy | Your courses</title>

    <!-- using for 'minimal' styling -->
    <link rel="stylesheet" href="https://taniarascia.github.io/primitive/css/main.css">
</head>
<body>
    <?php
        include 'database.php';

        if(!isset($_SESSION["logged_in"])){
            header("Location: login.php");
        }
    ?>

    <?php
    $user = select_user($conn, $_SESSION["logged_in"]);
    $user_courses = select_courses_by_user($conn, $user['user_id']);
    if (!$user_courses){
        echo('
            <div class="small-container">
                <div class="flex-row">
                    <div class="flex-small">
                        <h4>Your courses</h4>
                        <p><a href="courses-new.php">Create a new course</a></p>
                        <p>You have not created any courses. <a href="courses-new.php">Create one!</a></p>
                    </div>
                </div>
            </div>
        ');
    } else {
        // manually make table markup
        $table_rows = "";
        
        foreach ($user_courses as $course){
            $edit = '<a href="courses-edit.php?action=edit&course_id='.$course['course_id'].'">Edit</a>';
            $course_name = $course['name'];
            $course_description = $course['description'];

            $row = "<tr><td>$edit</td><td>$course_name</td><td>$course_description</td></tr>";
            $table_rows = $table_rows . $row;
        }
        echo('
            <div class="small-container">
                <div class="flex-row">
                    <div class="flex-small">
                        <h4>Your courses</h4>
                        <p><a href="courses-new.php">Create a new course</a></p>
                        <table>
                            <tr>
                                <th>Action</th>
                                <th>Course</th>
                                <th>Course description</th>
                            </tr>
                        ' . 
                        $table_rows
                        . '
                        </table>
                    </div>
                </div>
            </div>
        ');
    }
    ?>
</body>
</html>
