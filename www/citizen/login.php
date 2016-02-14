<?php

/**
 * File to handle all API requests
 * Accepts GET and POST
 *
 * Each request will be identified by TAG
 * Response will be JSON data

 /**
 * check for POST request
 */
if (isset($_POST['tag']) && $_POST['tag'] != '') {
	// get tag
	$tag = $_POST['tag'];

	// include db handler
	require_once 'include/DB_Functions.php';
	$db = new DB_Functions();

	// response Array
	$response = array("tag" => $tag, "success" => 0, "error" => 0);

	// check for tag type
	if ($tag == 'login') {
		// Request type is check Login
		$email = mysql_real_escape_string($_POST['email']);
		$password = mysql_real_escape_string($_POST['password']);

		// check for user
		$user = $db->getUserByEmailAndPassword($email, $password);
		if ($user != false) {
			// user found
			// echo json with success = 1
			$response["success"] = 1;
			$response["uid"] = $user["unique_id"];
			$response["user"]["name"] = $user["name"];
			$response["user"]["email"] = $user["email"];
			$response["user"]["created_at"] = $user["created_at"];
			$response["user"]["updated_at"] = $user["updated_at"];
			echo json_encode($response);
		} else {
			// user not found
			// echo json with error = 1
			$response["error"] = 1;
			$response["error_msg"] = "Incorrect email or password!";
			echo json_encode($response);
		}
	} else if ($tag == 'register') {
		// Request type is Register new user
		$name = mysql_real_escape_string($_POST['name']);
		$email = mysql_real_escape_string($_POST['email']);
		$password = mysql_real_escape_string($_POST['password']);


		if (strlen($name) < 3) {
			$response["error"] = 5;
			$response["error_msg"] = "Name is too short";
			echo json_encode($response);
		}
		else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$response["error"] = 4;
			$response["error_msg"] = "Invalid email entered";
			echo json_encode($response);
		} 
		else if (strlen($password) < 6) {
			$response["error"] = 3;
			$response["error_msg"] = "Password must be greater than 6 characters";
			echo json_encode($response);
		} 

		// check if user is already existed
		else if ($db->isUserExisted($email)) {
			// user is already existed - error response
			$response["error"] = 2;
			$response["error_msg"] = "User already existed";
			echo json_encode($response);
		} else {
			// store user
			$user = $db->storeUser($name, $email, $password);
			if ($user) {
				// user stored successfully
				$response["success"] = 1;
				$response["uid"] = $user["unique_id"];
				$response["user"]["name"] = $user["name"];
				$response["user"]["email"] = $user["email"];
				$response["user"]["created_at"] = $user["created_at"];
				$response["user"]["updated_at"] = $user["updated_at"];
				echo json_encode($response);
			} else {
				// user failed to store
				$response["error"] = 1;
				$response["error_msg"] = "Error occurred in Registration";
				echo json_encode($response);
			}
		}
	} else {
		echo "Invalid Request";
	}
} else {
	echo "Access Denied";
}
?>
