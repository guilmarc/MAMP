<?php
	
	include("connect.php");
	include("functions.php");


	if (logged_in()) {
		echo "You are logged in";
	} else {
		echo "You are not logged in";
	}


?>