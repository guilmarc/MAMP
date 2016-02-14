<!doctype html>


<?php

	include("connect.php");
	include("functions.php");

	$error = "";

if(isset($_POST['submit']))
{
	$firstName = mysql_real_escape_string($_POST['fname']);
	$lastName = $_POST['lname'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$passwordConfirm = $_POST['passwordConfirm'];
	$image = $_FILES['image']['name'];
	$tmp_image = $_FILES['image']['tmp_name'];
	$imageSize = $_FILES['image']['size'];

	$date = date("F, d y");

	//echo $firstName . "<br>" . $lastName . "<br>" . $email . "<br>" . $password . "<br>" . $passwordConfirm . "<br>" . $image  . "<br>" . $imageSize;

	if (strlen($firstName) < 3) {
		$error = "FirstName is too short";
	}
	else if (strlen($lastName) < 3) {
		$error = "LastName is too short";
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$error = "Please enter a valid email address";
	}
	else if (email_exists($email, $con)) {
		$error = "Someone is already registered with this email";
	} 
	else if (strlen($password) < 8) {
		$error = "Password must be greater than 8 characters";
	} 
	else if ($password !== $passwordConfirm){
		$error = "Password does not match";
	} 
	else if ($image == "") {
		$error = "Please upload your image";
	} 
	else {
		//$error = "OK";
		$password = md5($password);

		$imageExt = explode(".", $image)[1];

		if (strtoupper($imageExt) == 'PNG' || strtoupper($imageExt) == 'JPG') {
		
			$image = rand(0, 100000).rand(0, 100000).rand(0, 100000).time().".".$imageExt;

			$insertQuery = "INSERT INTO users (firstName, lastName, email, password, image) VALUES ('$firstName', '$lastName', '$email', '$password', '$image')";
			if (mysqli_query($con, $insertQuery) or die(mysqli_error($con))){
				if(move_uploaded_file($tmp_image, "images/$image")){
					$error = "You are successfully registered";
				} else {
					$error = "Image is not uploaded";
				}
			} else {
				$error = "Unable to INSERT " . $image . " ";
			}
		}
		else {
			$error = "File must be an image";
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

			<div id="menu">
				<a href="index.php">Sign Up</a>
				<a href="login.php">Login</a>
			</div>

			<div id="formDiv">

				<form method="POST" action="index.php" enctype="multipart/form-data">
					<label>First Name:</label><br><br>
					<input type="text" name="fname" /><br><br>
					<label>Last Name:</label><br>
					<input type="text" name="lname" /><br><br>
					<label>Email:</label><br>
					<input type="text" name="email" /><br><br>
					<label>Password:</label><br>
					<input type="password" name="password" /><br><br>
					<label>Confirm password:</label><br>
					<input type="passwordConfirm" name="passwordConfirm" /><br><br>
					<label>Image:</label><br>
					<input type="file" name="image" /><br><br>
					<input type="submit" name="submit" />
				</forms>

			</div>

		</div>

	</body>
</html>