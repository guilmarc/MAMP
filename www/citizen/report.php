<?php

if (isset($_POST['tag']) && $_POST['tag'] != '') {

	// get tag
	$tag = $_POST['tag'];

	// include db handler
	require_once 'include/DB_Functions.php';
	$db = new DB_Functions();

	// response Array
	$response = array("tag" => $tag, "success" => 0, "error" => 0);

	if ($tag == 'insert') 
	{
		
		echo "insert";

		$user_id = mysql_real_escape_string($_POST['user_id']);
		$category_id = mysql_real_escape_string($_POST['category_id']);
		$title = mysql_real_escape_string($_POST['title']);
		$description = mysql_real_escape_string($_POST['description']);
		$latitude = mysql_real_escape_string($_POST['latitude']);
		$longitude = mysql_real_escape_string($_POST['longitude']);

		$id = $db->storeReport($user_id, $category_id, $title, $description, $latitude, $longitude, $image);
		
		echo "ID=".$id;

		if ($id) {
			// user stored successfully
			$response["success"] = 1;
			$response["id"] = $id;
			echo json_encode($response);

		} else {
			// user failed to store
			$response["error"] = 10;
			$response["error_msg"] = "Error occured while saving report";
			echo json_encode($response);
		}


	}
	else if ($tag == 'update') 
	{


	}
	else if ($tag == 'select') 
	{


	}

}


else {
	echo "Access Denied";
}