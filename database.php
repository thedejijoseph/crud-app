<?php

$server_name = getenv('SERVER_NAME');
$db_user = getenv('DB_USER');
$db_password = getenv('DB_PASSWORD');
$db_name = getenv('DB_NAME');

// get a connection to the database
$conn = new mysqli($server_name, $db_user, $db_password, $db_name);
if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

// move on to creating a database
$sql = "CREATE DATABASE IF NOT EXISTS `crud_app`";
if ($conn->query($sql) === TRUE) {
    error_log("Database crud_app created successfully");

    // create users table
    $create_users_table_sql = "CREATE TABLE IF NOT EXISTS `crud_app`.`users` ( `user_id` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR(255) NOT NULL , `email` VARCHAR(255) NOT NULL , `password` VARCHAR(255) NOT NULL , PRIMARY KEY (`user_id`)) ENGINE = InnoDB;";
    if ($conn->query($create_users_table_sql) === TRUE) {
        error_log("Created -users- table successfully");
    } else {
        error_log("Could not create -users- table [$conn->error]");
    }

    // create courses table
    $create_courses_table_sql = "CREATE TABLE IF NOT EXISTS `crud_app`.`courses` ( `course_id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(255) NOT NULL , `description` VARCHAR(2000) NOT NULL , `user_id` INT(255) NOT NULL , PRIMARY KEY (`course_id`)) ENGINE = InnoDB;";
    if ($conn->query($create_courses_table_sql) === TRUE) {
        error_log("Created -courses- table successfully");
    } else {
        error_log("Could not create -courses- table [$conn->error]");
    }
} else {
    error_log("Error creating a database [$conn->error]");
}



// database 'apis'

function insert_user($conn, $data){
    $insert_user_stmt = $conn->prepare("INSERT INTO `crud_app`.`users` (user_id, username, email, password) VALUES (NULL, ?, ?, ?);");

    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];

    $insert_user_stmt->bind_param("sss", $username, $email, $password);
    $insert_user_stmt->execute();

    if (!$insert_user_stmt->error) {
        error_log("Inserted new user: $username");
    } else {
        error_log("Could not insert user [$insert_user_stmt->error]");
    }
}

function select_user($conn, $username){
    $select_user_stmt = $conn->prepare("SELECT * FROM `users` WHERE `username` = ?");

    $select_user_stmt->bind_param("s", $username);
    $select_user_stmt->execute();

    $result = $select_user_stmt->get_result();

    if ($result->num_rows > 0){
        $user = array();
        
        $row = $result->fetch_assoc();
        $user = $row;
        return $user;
    } else {
        error_log("Could not fetch user: $username [$select_user_stmt->error]");
        $user = NULL;
        return $user;
    }
}

function update_user_password($conn, $data){
    $update_user_password_stmt = $conn->prepare("UPDATE `users` SET `password` = ? WHERE `users`.`username` = ?;");

    $new_password = md5($data['password']);
    $username = $data['username'];

    $update_user_password_stmt->bind_param("ss", $new_password, $username);
    $update_user_password_stmt->execute();

    if (!$update_user_password_stmt->error){
        error_log("Update password for $username");
    } else {
        error_log("Could not update password [$update_user_password_stmt->error]");
    }
}


function insert_course($conn, $data){
    $insert_course_stmt = $conn->prepare("INSERT INTO `crud_app`.`courses` (`course_id`, `name`, `description`, `user_id`) VALUES (NULL, ?, ?, ?)");
    
    $course_name = $data['course_name'];
    $course_description = $data['course_description'];
    $user_id = (int)$data['user']['user_id'];

    $insert_course_stmt->bind_param("ssi", $course_name, $course_description, $user_id);
    $insert_course_stmt->execute();
    
    if (!$insert_course_stmt->error) {
        error_log("Inserted new course: " . $data['course_name']);
    } else {
        error_log("Could not insert new course =>" . $insert_course_stmt->error);
    }
}

function select_courses_by_user($conn, $user_id) {
    $select_courses_by_user_stmt = $conn->prepare("SELECT * FROM `courses` WHERE `user_id` = ?");

    $select_courses_by_user_stmt->bind_param("i", $user_id);
    $select_courses_by_user_stmt->execute();
    
    $result = $select_courses_by_user_stmt->get_result();

    if($result) {
        $courses = array();

        while ($row = $result->fetch_assoc()){
            array_push($courses, $row);
        }
        return $courses;
    } else {
        error_log("Could not fetch courses with user_id: $user_id [$select_courses_by_user_stmt->error]");
        $courses = NULL;
        return $courses;
    }
}

function select_course_by_id($conn, $course_id){
    $select_course_by_id_stmt = $conn->prepare("SELECT * FROM `courses` WHERE `course_id` = ?");

    $select_course_by_id_stmt->bind_param("i", $course_id);
    $select_course_by_id_stmt->execute();

    $result = $select_course_by_id_stmt->get_result();

    if ($result) {
        $course = array();

        $row = $result->fetch_assoc();
        $course = $row;
        return $course;
    } else {
        error_log("Could not fetch course with course_id: $course_id [$select_course_by_id_stmt->error]");
        $course = NULL;
        return $course;
    }
}

function update_course($conn, $data){
    $update_course_stmt = $conn->prepare("UPDATE `courses` SET `name` = ?, `description` = ? WHERE `courses`.`course_id` = ?");

    $name = $data['course_name'];
    $description = $data['course_description'];
    $course_id = $data['course_id'];

    $update_course_stmt->bind_param("ssi", $name, $description, $course_id);
    $update_course_stmt->execute();

    if (!$update_course_stmt->error) {
        error_log("Updated course details for '$name'");
    } else {
        error_log("Could not update course details [$update_course_stmt->error]");
    }
}

function delete_course($conn, $course_id){
    $delete_course_stmt = $conn->prepare("DELETE FROM `courses` WHERE `courses`.`course_id` = ?");

    $delete_course_stmt->bind_param("i", $course_id);
    $delete_course_stmt->execute();

    if (!$delete_course_stmt->error) {
        error_log("Deleted course with id: $course_id");
    } else {
        error_log("Could not delete course with id: $course_id [$delete_course_stmt->error]");
    }
}

?>
