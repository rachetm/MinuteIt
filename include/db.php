<?php
$host = 'remotemysql.com:3306';
$user = 'ICTaT1CX0N';
$password = 'jteyvcqTUk';
$database = 'ICTaT1CX0N';
$con = mysqli_connect($host, $user, $password, $database);
if (mysqli_connect_error($con)) {
	echo "Failed to connect to MySQL" . mysqli_connect_error();
}
?>