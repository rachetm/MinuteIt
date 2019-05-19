<?php
/* Reset your password form, sends reset.php password link */

include 'include/sendmailbasic.php';

require_once 'include/db.php';
session_start();

// Check if form submitted with method="post"
if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
    
    $stmt=$con->prepare("SELECT * FROM `users` WHERE `email` = ?");
    $stmt->bind_param("s",$email);
    $email = $_POST['email'];
    $stmt->execute();
    $result= $stmt->get_result();
    if (  $result->num_rows == 0 ) // User doesn't exist
    {
     		$_SESSION['message'] = "If a user with this email exists, a password reset link will be sent!";
			$_SESSION['type'] = 'success';
            header('Location: ./index.php');
            exit();
    }
	else 
	{ // User exists (num_rows != 0)
    

        $user = $result->fetch_assoc(); // $user becomes array with user data

        $email = $user['email'];
        $username = $user['username'];
        $hash=$user['hash'];

        // Send Password Link confirmation link (reset.php)

        $to      = $email;
        $subject = 'Password Reset Link [Minute It]';
        $message_body = '
        Hello '.$username.',

        You have requested to reset your password.

        Please click on the following link to reset -

        https://minute/resetpwd.php?email='.$email.'&hash='.$hash;

        if(email_std($to, $subject, $message_body))
        {
            $_SESSION['message'] = "If a user with this email exists, a password reset link will be sent!";
			$_SESSION['type'] = 'success';
            header('Location: ./index.php');
            exit();
        }

    }
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Reset Password</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/index.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700,900" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-4">
					<p style="text-align: center; margin-top: 20%; font-family: Lato; font-size: 25px; color: white; text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);">Please enter your registered email below</p>
					<hr style="margin-bottom: 40px;">
					<div class='formcontainer'>
						<div class="row form-row container form-group">
							<form action="frgtpwd.php" method="post">
								<div class="form-group">
									<div class="row form-row container form-group">
										<label for="email">Email</label>
										<input type="email" class="form-control field" name="email" required autofocus>
										<button type="submit"  class="btn btn-success" style="font-family: Raleway; margin-top: 10%;">Submit</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>