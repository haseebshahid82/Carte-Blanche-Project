<?php

session_start();

$username = "";
$email = "";
$priority = "";

$errors = array();

//connecting to database

$db = mysqli_connect('localhost', 'root', '', 'cb_project') or die("Could not connect to database");


if (isset($_POST['register'])) {
    //User Registration

    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($db, $_POST['confirm_password']);

    //form validation

    if (empty($username)) {
        array_push($errors, "Username is required");
    }

    if (empty($email)) {
        array_push($errors, "Email is required");
    }

    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if ($password != $confirm_password) {
        array_push($errors, "Passwords do not match");
    }

    //checking database if user already exists

    $query = "select * from users where username = '$username' or email = '$email' LIMIT 1";

    $results = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($results);

    if ($user) {
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }
        if ($user['email'] === $email) {
            array_push($errors, "This email is already registered with a username");
        }
    }
    //register user if no errors found

    if (count($errors) == 0) {
        $encrypted_password = md5($password);
        $insert_query = "Insert into users (username, email, password) Values ('$username','$email','$encrypted_password')";
        mysqli_query($db, $insert_query);
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "Welcome User";
        header('location: index.php');
    }
}

//user login

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (count($errors) == 0) {
        $encrypted_password = md5($password);
        $insert_query = "select * from users where username = '$username' AND password = '$encrypted_password'";
        $results = mysqli_query($db, $insert_query);

        if (mysqli_num_rows($results)) {
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "Welcome User";
            header('location: index.php');
        }
    }
}

//Add Label for a Task

if (isset($_POST['add_label']))
{
    $label = mysqli_real_escape_string($db, $_POST['label']);
    $user = $_SESSION['username'];
    $add_query = "Insert into labels (TaskId, Label) values ('1', '$user')";
    mysqli_query($db, $add_query);
}

//Get Value from Priority Button on Add New Task Modal

if (isset($_POST['priority']))
    {
        $priority = $_POST['priority'];
    }


if (isset($_POST['add_task']))
{
    $task_title = mysqli_real_escape_string($db, $_POST['task_title']);
    $label = mysqli_real_escape_string($db, $_POST['label']);
    $user = $_SESSION['username'];
    $add_query = "INSERT into user_task (Username) VALUES ('$user')";
    mysqli_query($db, $add_query);
    $query = "SELECT LAST_INSERT_ID()";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);
    $num = $user['LAST_INSERT_ID()'];
    $add_query_task = "INSERT into tasks (TaskId, Task, _Priority) VALUES ('$num', '$task_title','$priority')";
    if ($label) {
        $query = "INSERT INTO `labels`(`TaskId`, `Label`) VALUES ('$num','$label')";
        mysqli_query($db, $query); }
    mysqli_query($db, $add_query_task);
}

if (isset($_POST['edit_priority']))
{
    $priority = $_POST['edit_priority'];
}

//Get Value of Task ID of task which is to be edited

if (isset($_POST['bookId']))
{
    $TaskId = $_POST['bookId'];
}

//Edit Task Functionality

if (isset($_POST['edit_task']))
{

    $task_title = mysqli_real_escape_string($db, $_POST['edit_task_title']);
    $label = mysqli_real_escape_string($db, $_POST['edit_label']);
    if ($label) {
        $query = "INSERT INTO `labels`(`TaskId`, `Label`) VALUES ('$TaskId','$label')";
        mysqli_query($db, $query); }
    $user = $_SESSION['username'];
    $edit_query = "UPDATE `tasks` SET `Task`='$task_title',`_Priority`='$priority' WHERE `TaskId` = '$TaskId'";
    mysqli_query($db, $edit_query);
}

//Delete Task Functionality

if (isset($_GET['del_task']))
{
    $TaskId = $_GET['del_task'];
    $query_1 = "DELETE FROM tasks WHERE tasks.TaskId = '$TaskId'";
    $query_2 = "DELETE FROM user_task WHERE user_task.TaskId = '$TaskId'";
    $query_3 = "DELETE FROM labels WHERE labels.TaskId = '$TaskId'";
    mysqli_query($db, $query_1);
    mysqli_query($db, $query_2);
    mysqli_query($db, $query_3);
    header('location: index.php');
}


