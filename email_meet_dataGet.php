<?php
    session_start();
    require_once 'include/db.php';

    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
    {
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            $stmt = $con->prepare("SELECT * FROM `meetings` where `m_id` = ?");
            $stmt->bind_param("i",$_POST['m_id']);

            if(isset($_POST['agenda']))
            {
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                echo json_encode($row);
                return;
            }

            $stmt1 = $con->prepare("SELECT `fac_name` FROM `attendees` where `m_id` = ?");
            $stmt1->bind_param("i",$_POST['m_id']);

            if($stmt->execute())
            {
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                if($stmt1->execute())
                {
                    $result1 = $stmt1->get_result();
                    $res = mysqli_fetch_all($result1, MYSQLI_ASSOC);
                }
                else
                {
                    echo "error";
                    exit();
                }
                
                $arr = array();
                array_push($arr,json_encode($row),$res);
                $arr1 = json_encode($arr);
                echo $arr1;
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
            header('Location: ./email_meet.php');
            exit();
        }
    }
    else
    {
        echo 'logout';
        header('Location: ./logout.php');
        exit();
    }
?>