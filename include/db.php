<?php
$host = 'remotemysql.com:3306';
$user = '9QipbsNlGV ';
$password = 'Z0yLEjvRWj';
$database = '9QipbsNlGV ';
$con = mysqli_connect($host, $user, $password, $database);
if (mysqli_connect_error($con)) {
	echo "Failed to connect to MySQL" . mysqli_connect_error();
}
?>
