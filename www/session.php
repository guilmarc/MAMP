
<?php
session_start();
$_SESSION['nom'] = 'Dupont';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>Mon super titre</title> 
	</head>

	</body>

		<p><?php echo 'Bonjour M.' . $_SESSION['nom']; ?> </p> 

	</body>
</html>