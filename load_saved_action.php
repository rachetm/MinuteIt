<?php
    session_start();
    require_once 'include/db.php';

    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
    {
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            $stmt = $con->prepare("SELECT * FROM `actions` where `m_id` = ?");
            $stmt->bind_param("i",$_POST['m_id']);

            if($stmt->execute())
            {
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                
                echo json_encode($row);
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

?>