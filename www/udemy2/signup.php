<!doctype html>


<?php

	
	$con = mysqli_connect("localhost", "root", "root", "citizen");

	if(mysqli_connect_errno()){
		echo "Error occured thile connecting with database : " . mysqli_connect_errno();
	}

	$error = "";

if(isset($_POST['name']) && isset($_POST['email']) & isset($_POST['password']))
{
	$name = $_POST['name'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	//echo $firstName . "<br>" . $lastName . "<br>" . $email . "<br>" . $password . "<br>" . $passwordConfirm . "<br>" . $image  . "<br>" . $imageSize;

	if (strlen($name) < 6) {
		$error = "Name is too short";
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$error = "Please enter a valid email address";
	} 
	else if (strlen($password) < 6) {
		$error = "Password must be greater than 6 characters";
	} 
	else {
		//$password = md5($password);
		 	
		$insertQuery = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

		if (mysqli_query($con, $insertQuery) or die(mysqli_error($con))){
			$error = "You are successfully registered";
		} else {
			$error = "Unable to INSERT " . $image . " ";
		}
	}

}



?>


<html>

	<head>
		<title>Registration Page</title>
		<link rel="stylesheet" href="css/styles.css"/>
	</head>

	<body>
		<div id="error">
		<?php
			echo $error;
		?>
		</div>

		<div id="wrapper">

			<div id="formDiv">

				<form method="POST" action="signup.php" enctype="multipart/form-data">
					<label>Name:</label><br><br>
					<input type="text" name="name" /><br><br>
					<label>Email:</label><br>
					<input type="text" name="email" /><br><br>
					<label>Password:</label><br>
					<input type="password" name="password" /><br><br>
					<input type="submit" name="submit" />
				</forms>

			</div>

		</div>

	</body>
</html>