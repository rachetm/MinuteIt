<?php
    session_start();
	require_once 'include/db.php';

    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
    {
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            $group = $_POST['grp_name'];
            $grp = str_replace(" ","_",$group[0]);

            $stmt = $con->prepare("DELETE FROM `groups` WHERE `grp_name` = ?");
            $stmt->bind_param("s",$grp);
            
            if($stmt->execute())
            {
                
                if(!isset($_POST['members']) || $_POST['members'] == "")
                {
                    echo "success";
                    exit();
                }
                
                $stmt1 = $con->prepare("INSERT INTO `groups` VALUES (?,?,?,?)");
                $stmt1->bind_param("ssss",$grp_name,$fac_id,$fac_name,$fac_email);
                
                $names = $_POST['names'];
                $members = $_POST['members'];
                $emails = $_POST['emails'];
                $i = 0;


                foreach ($members as $member)
                {
                    $grp_name = $group[0];
                    $fac_id = $member;
                    $fac_name = $names[$i];
                    $fac_email = $emails[$i];
                    
                    if(!($stmt1->execute()))
                    {
                        echo "error";
                        exit();
                    }

                    $i++;
                }

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
        $_SESSION['message'] = "You are logged out. Please login to continue!";
        $_SESSION['type'] = "danger";
        header("Location: ./index.php");
	    exit();
	}
?>