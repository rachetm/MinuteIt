<?php
    session_start();
    require_once 'include/db.php';
    
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
    {
        if ( $_SERVER['REQUEST_METHOD'] == 'GET' )
        {
			$stmt = $con->prepare("SELECT `fac_id`,`fac_name`,`fac_email` FROM `members`");
            if($stmt->execute())
            {
                $result = $stmt->get_result();
                $mems = mysqli_fetch_all($result, MYSQLI_ASSOC);

                $stmt1 = $con->prepare("SELECT `fac_id`,`fac_name`,`fac_email` FROM `groups` WHERE `grp_name` = ? ");
                $stmt1->bind_param("s",$grp_name);
                // $grp_name = $_GET['grp'];
                $grp_name = str_replace(" ","_",$_GET['grp']);

                if($stmt1->execute())
                {
                    $result1 = $stmt1->get_result();
                    $grps = mysqli_fetch_all($result1, MYSQLI_ASSOC);
                }
                else
                {
                    echo "error";
                    exit();
                }

                $arr = array();
                array_push($arr,$mems,$grps);
                $arr1 = json_encode($arr);
                echo $arr1;
            }
            else
            {
                echo "error";
				exit();
            }
        }
        else if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $stmt = $con->prepare("SELECT * FROM `members`");
            if($stmt->execute())
            {
                $result = $stmt->get_result();
                $mems = mysqli_fetch_all($result, MYSQLI_ASSOC);
                echo json_encode($mems);
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