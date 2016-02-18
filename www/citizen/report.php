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
		$user_id = mysql_real_escape_string($_POST['user_id']);
		$category_id = mysql_real_escape_string($_POST['category_id']);
		$title = mysql_real_escape_string($_POST['title']);
		$description = mysql_real_escape_string($_POST['description']);
		$latitude = mysql_real_escape_string($_POST['latitude']);
		$longitude = mysql_real_escape_string($_POST['longitude']);
		$image = $_FILES['image']['name'];
		$tmp_image = $_FILES['image']['tmp_name'];



		$imageExt = explode(".", $image)[1];

		//if (strtoupper($imageExt) == 'PNG' || strtoupper($imageExt) == 'JPG') {
			//Will always be JPG for now
		//}

		$imageFile = rand(0, 100000).rand(0, 100000).rand(0, 100000).time().".".$imageExt;

		$id = $db->storeReport($user_id, $category_id, $title, $description, $latitude, $longitude, $imageFile);
		
		//echo "ID=".$id;

		if ($id) {


				echo $tmp_image;
				echo $imageFile;


				if(move_uploaded_file($tmp_image, "images/$imageFile")){
					// user stored successfully
					$response["success"] = 1;
					$response["id"] = $id;
					$response["image"] = $imageFile;
					
					echo json_encode($response);
				} else {
								// user failed to store
					$response["error"] = 14;
					$response["error_msg"] = "Unable to save image";
					echo json_encode($response);
				}

		} else {
			// user failed to store
			$response["error"] = 10;
			$response["error_msg"] = "Error occured while saving report";
			echo json_encode($response);
		}


	}	
	elseif ($tag == 'update') 
	{
		$id = mysql_real_escape_string($_POST['id']);
		$category_id = mysql_real_escape_string($_POST['category_id']);
		$title = mysql_real_escape_string($_POST['title']);
		$description = mysql_real_escape_string($_POST['description']);
		$latitude = mysql_real_escape_string($_POST['latitude']);
		$longitude = mysql_real_escape_string($_POST['longitude']);

		$result = $db->updateReport($id, $category_id, $title, $description, $latitude, $longitude, $image);
		
		if ($result) {
			// user stored successfully
			$response["success"] = 1;
			echo json_encode($response);

		} else {
			// user failed to store
			$response["error"] = 11;
			$response["error_msg"] = "Error occured while updating report";
			echo json_encode($response);
		}

	}

	elseif ($tag == 'select') 
	{
		$user_id = mysql_real_escape_string($_POST['user_id']);

		$result = $db->getReports($user_id);
		
		if ($result) {
			// user stored successfully
			$response["success"] = 1;
			$response["reports"] = $result;
			echo json_encode($response);

		} else {
			// user failed to store
			$response["error"] = 12;
			$response["error_msg"] = "Error occured while fetching report";
			echo json_encode($response);
		}

	} 
	elseif ($tag == 'delete') {
		$id = mysql_real_escape_string($_POST['id']);
		$user_id = mysql_real_escape_string($_POST['user_id']);

		$result = $db->deleteReport($id, $user_id);
		
		if ($result) {
			// user stored successfully
			$response["success"] = 1;
			echo json_encode($response);

		} else {
			// user failed to store
			$response["error"] = 13;
			$response["error_msg"] = "Error occured while deleting report";
			echo json_encode($response);
		}
	}
	else 
	{
		echo "Invalid Request";
	}

} else {
	echo "Access Denied";
}
?>
