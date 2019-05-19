<?php
    session_start();
    require_once 'include/db.php';
    
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
    {
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            $stmt = $con->prepare("DELETE FROM `members` WHERE `fac_id` = ?");
            $stmt->bind_param("s",$fac_id);
            $fac_id = $_POST['member'];
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
        $_SESSION['message'] = "You are logged out. Please login to continue!";
        $_SESSION['type'] = "danger";
        header("Location: ./index.php");
	    exit();
    }
?>