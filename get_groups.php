<?php
    session_start();
    require_once 'include/db.php';

    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $stmt = $con->prepare("SELECT * FROM `group_names`");
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows == 0)
            {
                echo "empty";
                exit();
            }
            else
            {
                $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
                echo json_encode($data);
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