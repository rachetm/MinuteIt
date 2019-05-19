<?php
/* The password reset form. The link to this page is included
from the forgot.php email message
 */
session_start();

require_once 'include/db.php';

// Make sure email and hash variables aren't empty
if (isset($_GET['email']) && !empty($_GET['email']) and isset($_GET['hash']) && !empty($_GET['hash'])) 
{
    // Make sure user email with matching hash exist

    $stmt = $con->prepare("SELECT * FROM `users` WHERE email = ? AND `hash`= ?");
    $stmt->bind_param("ss", $_SESSION['email'], $_SESSION['hash']);
    $_SESSION['email'] = $_GET['email'];
    $_SESSION['hash'] = $_GET['hash'];

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) 
    {
        $_SESSION['message'] = "You have entered invalid URL for password reset!";
        $_SESSION['type'] = "danger";
        header("location: ./index.php");
    }
} 
else 
{
    $_SESSION['message'] = "Sorry, link verification failed. Please try again!";
    $_SESSION['type'] = "danger"; 
    header("location: ./index.php");
}
?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>MinuteIt • Reset Password</title>
		<link rel="stylesheet" type="text/css" href="include/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/index.css">
		<link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700,900" rel="stylesheet">
	<script>
    //To check wether both the passwords are same
    function validate() {
      if (document.getElementById('password').value == document.getElementById('password2').value)
        return true;
      else {
        document.getElementById('errmsg').innerHTML = "<div class='alert alert-danger'>Passwords do not match!</div>";
    
        return false;
      }
    } 
  </script>
	</head>
	<body>
		<div class="container">
			<div class="row" style="justify-content: center;">
				<div class="col-sm-12 col-md-6 col-lg-4">
					<div style="margin-top: 30%;" id="errmsg"></div>
						<div class='formcontainer'>
							<form action="reset_password.php" onsubmit="return validate();" method="post">
								<div class="form-group">
									<div class="row form-row container form-group">
										<label for="newpassword">New Password</label>
										<input type="password" class="form-control field" id="password" name="newpassword" required autofocus>

										<label for="comfirmpassword">Re-enter New Password</label>
										<input type="password" class="form-control field" id="password2" name="confirmpassword" required>

										<button type="submit"  class="btn btn-success" style="font-family: Raleway; margin-top: 10%;">Reset</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
	</body>
</html>