<?php
    session_start();
    require_once 'include/db.php';

    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $stmt = $con->prepare("DELETE FROM `meetings` WHERE `m_id` = ?");
            $stmt->bind_param("i",$m_id);

            $stmt1 = $con->prepare("DELETE FROM `actions` WHERE `m_id` = ?");
            $stmt1->bind_param("i",$m_id);

            $m_id = $_POST['m_id'];
             
            if($stmt->execute() and $stmt1->execute())
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