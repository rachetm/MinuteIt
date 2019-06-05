<?php
    session_start();
    require_once 'include/db.php';

    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $stmt = $con->prepare("DELETE FROM `group_names` WHERE `grp_name` = ?");
            $stmt->bind_param("s",$_POST['grp_name']);
            if(isset($_POST['sure']))
            {
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
                echo "notsure";
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