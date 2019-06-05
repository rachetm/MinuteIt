<?php
    session_start();
    require_once 'include/db.php';
    
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            $stmt = $con->prepare("INSERT INTO `members` (`fac_name`, `fac_email`) VALUES (?,?)");
            
            $stmt->bind_param("ss",$name,$email);
            
            $name = $_POST['facname'];
            $email = $_POST['facemail'];

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