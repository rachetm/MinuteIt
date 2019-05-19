<?php
session_start();
require_once 'include/db.php';
require_once 'vendor/autoload.php';

date_default_timezone_set('Asia/Kolkata');

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        $stmt1 = $con->prepare("INSERT INTO `circulars` (`c_date`,`m_date`,`time`,`venue`,`agenda`) VALUES (?,?,?,?,?)");
        $stmt1->bind_param("sssss", $c_date, $m_date, $time, $venue, $agenda);

        $c_date = $_POST['cdatepick'];
        $m_date = $_POST['mdatepick'];
        $time = $_POST['timepick'];
        $venue = $_POST['venue'];
        $agenda = $_POST['agenda'];

        if (!$stmt1->execute()) 
        {
            echo "error";
            exit();
        }
        else 
        {
            echo "success";
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
    $_SESSION['type'] = 'danger';
    header('Location: ./index.php');
}