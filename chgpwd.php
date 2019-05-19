<?php

session_start();
require_once 'include/db.php';

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) 
{

    function unique_salt()
    {
        return substr(sha1(mt_rand()), 0, 22);
    }

    if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
    {
        $stmt=$con->prepare("SELECT * FROM `users` WHERE `username` = ?");
        $stmt->bind_param("s",$_SESSION['username']);
        $stmt->execute();
        $result= $stmt->get_result();

        $row = $result->fetch_assoc();

        if (password_verify($_POST['currentpassword'], $row['password']))
        {
            if ($_POST['newpassword'] == $_POST['confirmpassword']) 
            {
                // We get $_POST['email'] and $_POST['hash'] from the session variables
                $stmt = $con->prepare("UPDATE `users` SET `password`=? , `hash` = ? WHERE `username`=?");
                $stmt->bind_param("sss", $new_password, $new_hash, $_SESSION['username']);

                $new_password = password_hash($_POST['newpassword'], PASSWORD_BCRYPT);
                $new_hash = password_hash(unique_salt(), PASSWORD_BCRYPT);

                if ($stmt->execute()) 
                {
                    $_SESSION['message'] = "Your password has been reset successfully! Please login to continue!";
                    $_SESSION['type'] = "success";
                    $_SESSION['logged_in'] = false;
                    header('Location: ./index.php');
                    exit();
                }

            } 
            else 
            {
                $_SESSION['message'] = "Passwords do not match!";
                $_SESSION['type'] = "danger";
                header("Location: ./home.php");
                exit();
            }
        }
        else
        {
                $_SESSION['message'] = "Current password is incorrect!";
                $_SESSION['type'] = "danger";
                header("Location: ./home.php");
                exit();
        }
    }
}
else 
{
    $_SESSION['message'] = "You are logged out. Please login to continue!";
    $_SESSION['type'] = "danger";
    header('Location: ./index.php');
}
?>
