<?php
    session_start();
    require_once 'include/db.php';
    
    date_default_timezone_set('Asia/Kolkata');

    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
    {
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            $stmt0=$con->prepare("SELECT max(`m_id`) FROM `meetings`");
            if($stmt0->execute())
            {
                $result0 = $stmt0->get_result();
                $row = $result0->fetch_assoc();
                
                $stmt1=$con->prepare("INSERT INTO `meetings` (`date`,`time`,`venue`,`agenda`,`minutes`) VALUES (?,?,?,?,?)");
                $stmt1->bind_param("sssss",$date,$time,$venue,$agenda,$minutes);
                
                $date = $_POST['datepick'];
                $time = $_POST['timepick'];
                $venue = $_POST['venue'];
                $agenda = $_POST['agenda'];
                $minutes = $_POST['minutes'];
                
                if (!$stmt1->execute())
                {
                    $_SESSION['message'] = "Something went wrong! Please try again.";
                    // echo "Meetings " . $stmt1->error;
                    $_SESSION['type'] = "danger";
                    $_SESSION['error'] = 1;
                    header('Location: ./new_meeting.php');
                    exit();
                }
                
                $stmt2=$con->prepare("INSERT INTO `attendees` VALUES (?,?,?,?)");
                $stmt2->bind_param("isss",$m_id,$fac_id,$fac_name,$grp_name);
                
                $names = $_POST['names'];
                $i = 0;
                
                $stmt0->execute();
                $result0 = $stmt0->get_result();
                $row = $result0->fetch_assoc();

                foreach ($_POST['attendees'] as $attendee)
                {
                    $m_id = $row['max(`m_id`)'];
                    $fac_id = $attendee;
                    $fac_name = $names[$i];
                    $grp_name = $_POST['group_selection'];
                    if(!$stmt2->execute())
                    {
                        $temp = TRUE;
                        $stmt = $con->prepare("DELETE FROM `meetings` where `m_id` = ?");
                        $stmt->bind_param("i",$m_id);
                        while($temp)
                        {
                            if($stmt->execute())
                                $temp = FALSE;
                        }
                        $_SESSION['message'] = "Something went wrong! Please try again.";
                        $_SESSION['type'] = "danger";
                        $_SESSION['error'] = 1;
                        // echo "Attendee" . $stmt2->error;
                        header('Location: ./new_meeting.php');
                        exit();
                    }
                    $i++;
                }

                $_SESSION['message'] = "Minutes saved successfully";
                $_SESSION['type'] = "success";
                header('Location: ./home.php');
                exit();
            }
            else
            {
                $_SESSION['message'] = "Something went wrong! Please try again.";
                $_SESSION['type'] = "danger";
                $_SESSION['error'] = 1;
                header('Location: ./new_meeting.php');
                exit();
            }

        }
        else
        {
            $_SESSION['message'] = "Something went wrong! Please try again.";
            $_SESSION['type'] = "danger";
            $_SESSION['error'] = 1;
            header('Location: ./new_meeting.php');
            exit();
        }
    }
    else 
    {
        $_SESSION['message'] = "You are logged out. Please login to continue!";
        $_SESSION['type'] = 'danger';
        header('Location: ./index.php');
    }

?>