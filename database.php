<?php

$server_name = "localhost";
$db_user = "manager";
$db_password = "manager";
$db_name = "crud_app";

// get a connection to the database
$conn = new mysqli($server_name, $db_user, $db_password, $db_name);
if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

// move on to creating a database
$sql = "CREATE DATABASE IF NOT EXISTS `crud_app`";
if ($conn->query($sql) === TRUE) {
    error_log("Database crud_app created successfully");
} else {
    error_log("Error creating a database " . $conn->error);
}

// create users table
$create_users_table_sql = "CREATE TABLE `crud_app`.`users` ( `user_id` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR(255) NOT NULL , `email` VARCHAR(255) NOT NULL , `password` VARCHAR(255) NOT NULL , PRIMARY KEY (`user_id`)) ENGINE = InnoDB;";
if ($conn->query($create_users_table_sql) === TRUE) {
    error_log("Created -users- table successfully");
} else {
    error_log("Could not create -users- table => ". $conn->error);
}

// create courses table
$create_courses_table_sql = "CREATE TABLE `crud_app`.`courses` ( `course_id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(255) NOT NULL , `description` VARCHAR(2000) NOT NULL , `user_id` INT(255) NOT NULL , PRIMARY KEY (`course_id`)) ENGINE = InnoDB;";
if ($conn->query($create_courses_table_sql) === TRUE) {
    error_log("Created -courses- table successfully");
} else {
    error_log("Could not create -courses- table => " . $conn->error);
}


// database 'apis'

function insert_user($conn, $data){
    $insert_user_sql = "INSERT INTO `crud_app`.`users` (user_id, username, email, password) VALUES (NULL, '".$data['username']."', '".$data['email']."', '".md5($data['password'])."')";

    if ($conn->query($insert_user_sql) === TRUE) {
        error_log("Inserted new user: " . $data['username']);
    } else {
        echo("Could not insert user =>" . $conn->error);
    }
}

function select_user($conn, $username){
    $select_user_sql = "SELECT * FROM `users` WHERE `username` = \"$username\"";
    $result = $conn->query($select_user_sql);

    if ($result->num_rows > 0){
        $user = array();
        
        $row = $result->fetch_assoc();
        $user = $row;
        return $user;
    } else {
        error_log("Could not fetch user -$username- => " . $conn->error);
        $user = NULL;
        return $user;
    }
}

function update_user_password($conn, $data){
    $update_user_password_sql = "UPDATE `users` SET `password` = '".md5($data['password'])."' WHERE `users`.`username` = '".$data['username']."';";

    if ($conn->query($update_user_password_sql) === TRUE) {
        error_log("Updated password for user: ".$data['username']);
    } else {
        error_log("Could not update password =>" . $conn->error);
    }
}

function insert_course($conn, $data){
    $insert_course_sql = "INSERT INTO `crud_app`.`courses` (`course_id`, `name`, `description`, `user_id`) VALUES (NULL, '".$data['course_name']."', '".$data['course_description']."', '".$data['user']['user_id']."')";

    if ($conn->query($insert_course_sql) === TRUE) {
        error_log("Inserted new course: " . $data['course_name']);
    } else {
        error_log("Could not insert new course =>" . $conn->error);
    }
}

function select_courses_by_user($conn, $user_id) {
    $select_courses_by_user_sql = "SELECT * FROM `courses` WHERE `user_id` = $user_id;";
    
    $result = $conn->query($select_courses_by_user_sql);
    if ($result->num_rows > 0) {
        $courses = array();

        while ($row = $result->fetch_assoc()){
            array_push($courses, $row);
        }
        return $courses;
    } else {
        error_log("Could not fetch courses with user_id -$user_id- => " . $conn->error);
        $courses = NULL;
        return $courses;
    }
}

function select_course_by_id($conn, $course_id){
    $select_course_by_id_sql = "SELECT * FROM `courses` WHERE `course_id` = $course_id";

    $result = $conn->query($select_course_by_id_sql);
    if ($result->num_rows > 0) {
        $course = array();

        $row = $result->fetch_assoc();
        $course = $row;
        return $course;
    } else {
        error_log("Could not fetch course with course_id -$course_id-" . $conn->error);
        $course = NULL;
        return $course;
    }
}

function update_course($conn, $data) {
    // need to fix: adding ' in the description messes things up
    // check out prepared statements
    $update_course_sql = "UPDATE `courses` SET `name` = '".$data['course_name']."', `description` = '".$data['course_description']."' WHERE `courses`.`course_id` = ".$data['course_id'].";";
    
    if ($conn->query($update_course_sql) === TRUE) {
        error_log("Updated course with course_id -".$data['course_id']."-");
    } else {
        error_log("Could not update course with course_id -".$data['course_id']."- =>" . $conn->error);
    }
}

?>
