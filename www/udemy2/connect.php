<?php

	$con = mysqli_connect("localhost", "root", "root", "registration");

	if(mysqli_connect_errno()){
		echo "Error occured thile connecting with database : " . mysqli_connect_errno();
	}

	session_start();

?>