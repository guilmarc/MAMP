


<?php

	include("connect.php");
	include("functions.php");
	//include("profile.php");


	$error = "";


if(isset($_POST['submit']))
{
	$email = mysql_real_escape_string($_POST['email']);
	$password = mysql_real_escape_string($_POST['password']);

	//echo $password."<br>";

	if (email_exists($email, $con)) {
		
		$result = mysqli_query($con, "SELECT password FROM users WHERE email = '$email'");
		$retrievepassword = mysqli_fetch_assoc($result);

		//echo $retrievepassword['password']."<br>";

		if($password != $retrievepassword['password'])
		{
			$error = "Password is incorrect";

		} else {

			$_SESSION['email'] = $email;

			echo "Session email set to ".$email;

			header("location: profile.php");
			//exit();
		}


	} else {
		$error = "Email does not exist";
	}

}



?>

<!doctype html>

<html>

	<head>
		<title>Login Page</title>
		<link rel="stylesheet" href="css/styles.css"/>
	</head>

	<body>
		<div id="error">
		<?php
			echo $error;
		?>
		</div>

		<div id="wrapper">

			<div id="menu">
				<a href="index.php">Sign Up</a>
				<a href="login.php">Login</a>
			</div>

			<div id="formDiv">

				<form method="POST" action="login.php" enctype="multipart/form-data">

					<label>Email:</label><br>
					<input type="text" name="email" /><br><br>
					<label>Password:</label><br>
					<input type="password" name="password" /><br><br>

					<input type="checkbox" name="keep">
					<label>Keep me logged in</label><br><br>

					<input type="submit" name="submit" value="login" />
				</forms>

			</div>

		</div>

	</body>
</html>