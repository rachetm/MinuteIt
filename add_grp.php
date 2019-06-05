<?php
    session_start();
    require_once 'include/db.php';

    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $stmt = $con->prepare("INSERT INTO `group_names` VALUES (?)");
            $stmt->bind_param("s",$group);
            $group = str_replace(" ","_",$_POST['group_name']);
            if($stmt->execute())
            {
                echo "success";
                exit();
            }
            else 
            {
                echo "error";
                exit();
            }
        }
        else
        {
            echo "error";
            exit();
        }
    }
    else
    {
        echo "error";
        exit();
    }
?>