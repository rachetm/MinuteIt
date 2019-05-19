<?php
	session_start();
	require_once 'include/db.php';
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
    {
        if ( $_SERVER['REQUEST_METHOD'] == 'GET' )
        {
			$stmt = $con->prepare("SELECT * FROM `groups` WHERE `grp_name` = ?");
			$stmt->bind_param("s",$grp_name);
			$grp_name = $_GET['grp'];
			if($stmt->execute())
			{
				$result = $stmt->get_result();
				if($result->num_rows == 0)
				{
					echo "empty";
					exit();
				}
				else
				{
					$final_res = mysqli_fetch_all($result, MYSQLI_ASSOC);
					echo json_encode($final_res);
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
        exit();
	}
?>