<?php
$host = 'remotemysql.com:3306';
$user = 'KelzoMawf2';
$password = 'fxzglmvWtH';
$database = 'KelzoMawf2';
$con = mysqli_connect($host, $user, $password, $database);
if (mysqli_connect_error($con)) {
	echo "Failed to connect to MySQL" . mysqli_connect_error();
}
?>
