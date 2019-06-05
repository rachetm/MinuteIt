<?php

	session_start();
	require_once 'include/db.php';
	if (isset($_SESSION['logged_in'])  && $_SESSION['logged_in'] == true)
	{
		header("Location: ./home.php");
	}
	else 
	{
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>MinuteIt • Log In</title>
		<link rel="stylesheet" type="text/css" href="include/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/index.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700,900" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div  class="col-sm-12 col-md-6 col-lg-4">
					</div>
				</div>
				<h1>MinuteIt</h1><hr>
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-4">
					  <?php

						if (isset($_SESSION['message']) and !empty($_SESSION['message'])) {
						    echo "<div class='alert alert-".$_SESSION['type']."'>".$_SESSION['message']."</div>";
						    unset($_SESSION['message']);}

						?>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12 col-md-4 col-lg-4">
					<div class='formcontainer'>
						<form action="login.php" method="post">
							<div class="row form-row container form-group">
								<label for="username">Username</label>
								<input type="text" class="form-control field" name="username" value="test" required autofocus>
							</div>

							<div class="row form-row container form-group">
								<label for="password">Password</label>
								<input type="password" class=" field form-control" name="password" value="test" required>
							</div>
							
							<div class="row form-row container form-group">
								<input class="btn btn-success" type="submit" name="submit" value="Log In">
							</div>
						</form>
						<a href="frgtpwd.php">Forgot Password?</a>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
}
?>