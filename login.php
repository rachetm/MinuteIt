<?php

session_start();

require_once 'include/db.php';

$stmt = $con->prepare("SELECT * FROM `users` WHERE `username` = ?");
$stmt->bind_param("s", $username);
$username = $_POST['username'];
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) 
{ // User doesn't exist
    $_SESSION['message'] = "Incorrect username or password!";
    $_SESSION['type'] = 'danger';
    header('Location: ./index.php');
} 
else
{   // User exists 
    $row = $result->fetch_assoc();

    if (password_verify($_POST['password'], $row['password'])) 
    {
            $_SESSION['username'] = $row['username'];
            $_SESSION['logged_in'] = true;      // This is how we'll know the user is logged in
            header("location: ./home.php");
    } 
    else 
    {
        $_SESSION['message'] = "Incorrect username or password!";
        $_SESSION['type'] = 'danger';
        header('Location: ./index.php');
    }
}

//minuteit@nmit1245
