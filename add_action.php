<?php

    session_start();
    require_once 'include/db.php';
    date_default_timezone_set('Asia/Kolkata');

    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
    {
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            $stmt0=$con->prepare("SELECT * FROM `actions` WHERE `m_id` = ?");
            $stmt0->bind_param("i",$m_id);
            $m_id = $_POST['m_id'];

            if($stmt0->execute())
            {
                $result = $stmt0->get_result();

                if ($result->num_rows == 0) 
                {
                    $stmt1=$con->prepare("INSERT INTO `actions` VALUES (?,?,?)");
                    $stmt1->bind_param("iss",$m_id,$date,$action);

                    $m_id = $_POST['m_id'];
                    $date = $_POST['datepick'];
                    $action = $_POST['action_taken'];

                    if($stmt1->execute())
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
                    $stmt1=$con->prepare("UPDATE `actions` SET `date` = ?, `action_taken` = ? WHERE `m_id` = ?");
                    $stmt1->bind_param("ssi",$date, $action, $m_id);

                    $m_id = $_POST['m_id'];
                    $date = $_POST['datepick'];
                    $action = $_POST['action_taken'];

                    // $stmt1=$con->prepare("UPDATE `actions` SET `action_taken` = ? WHERE `m_id` = ?");
                    // $stmt1->bind_param("si", $action, $m_id);

                    // $m_id = $_POST['m_id'];
                    // $action = $_POST['action_taken'];

                    if($stmt1->execute())
                    {
                        echo "update_success";
                        exit();
                    }
                    else 
                    {
                        echo "error";
                        exit();
                    }
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
    } 
    else 
    {
            echo "error";
            exit();
    }
?>