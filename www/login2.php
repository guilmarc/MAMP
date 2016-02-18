<?php
 session_start();
 
 //DB configuration Constants
 define('_HOST_NAME_', 'localhost');
 define('_USER_NAME_', 'root');
 define('_DB_PASSWORD', 'root');
 define('_DATABASE_NAME_', 'citizen');
 
 //PDO Database Connection
 try {
 $databaseConnection = new PDO('mysql:host='._HOST_NAME_.';dbname='._DATABASE_NAME_, _USER_NAME_, _DB_PASSWORD);
 $databaseConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 } catch(PDOException $e) {
 echo 'ERROR: ' . $e->getMessage();
 }
 
 //if(isset($_POST['submit'])){
 $errMsg = '';
 //username and password sent from Form
 $email = trim($_GET['email']);
 $password = trim($_GET['password']);
 
 if($email == '')
 $errMsg .= 'You must enter your Username<br>';
 
 if($password == '')
 $errMsg .= 'You must enter your Password<br>';
 
 
 if($errMsg == ''){
 $records = $databaseConnection->prepare('SELECT * FROM  users WHERE email = :email');
 $records->bindParam(':email', $email);
 $records->execute();
 $results = $records->fetch(PDO::FETCH_ASSOC);
 if(count($results) > 0 && password_verify($password, $results['password'])){
	echo "SUCCESS";
 exit;
 }else{
	echo "FAILED";
 }
 }
 //}
 
?>